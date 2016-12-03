<?php
/*
 *  File Name :
 *	Article.php
 *  Describe :
 *      server 管理端
 *	文章列表 、
 *  Start Date :
 *	2016.08.12
 *  Author :
 *	Lanker
 */

class Article {
    private $dbAdm;
    private $table;

    public function __construct() {
        if(file_exists("../../srvLib/MysqlCon.php")) 
            require_once("../../srvLib/MysqlCon.php");
        else
            require_once("../srvLib/MysqlCon.php");
        if(file_exists("../../server/config.php"))
            require_once("../../server/config.php");
        else
            require_once("../server/config.php");
        $this->config = new Config();
        $config = $this->config;
        $this->dbAdm = new MysqlCon(
            $config->getHost(), $config->getDBUser(),
            $config->getDBPass(), $config->getDB());
        $this->table = "Article";

        //設定php mysql client 的編碼為utf8
        $this->dbAdm->sqlSet("SET NAMES 'utf8'");
        $this->dbAdm->execSQL();
    }

    public function articleList($nowPage, $search = "") {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $column = Array();
        $column[0] = "*";

        $limit = Array();
        $limit['offset'] = 0;
        $limit['amount'] = 20;
        if(isset($nowPage))
            $limit['offset'] = ($nowPage -1) * $limit['amount'];

        $order = Array();
        $order['col'] = "a_updtime";
        $order['order'] = "desc";

        //$dbAdm->selectData($tablename, $column, null, $order, $limit);
        $dbAdm->sqlSet("
            select m.m_user, count(p.p_id) praiseAmount , att.at_title,
            case when (att.at_lastCh = 0) then '?' else att.at_lastCh end as at_lastCh, a.`a_id`,a.`a_attr`,a.`a_level`,a.`at_id`,a.`a_mainCp`,a.`a_mainCp2`,a.`a_subCp`,a.`a_alert`,a.`m_id`,a.`g_sendMid`,a.`a_tag`,a.`a_aTitle`,a.`a_chapter`,a.`a_isShow`, a.`a_clickCount`, a.`a_updtime`, a.`a_crtime`
            from `Article` a
            inner join Member m on a.m_id = m.m_id and m.m_user like '%$search%'
            left join ArticleSeries ass on a.as_id = ass.as_id
            inner join ArticleTitle att on att.at_id = a.at_id
            left join Praise p on a.a_id = p.a_id group by a.a_id
            order by ". $order['col']. " ". $order['order'].
            " limit ". $limit['offset']. ", ". $limit['amount']);
        $dbAdm->execSQL();

        return $dbAdm->getAll();
    }

    public function amount($search = "") {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $column = Array();
        $column[0] = "count(*)";
        $dbAdm->selectData($tablename, $column);
        $dbAdm->sqlSet("select count(*) as amount from `Article` a 
            inner join Member m on a.m_id = m.m_id and m.m_user like '%$search%' ");
        $dbAdm->execSQL();

        return $dbAdm->getAll()[0]['amount'];
    }

    public function del($aid) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $conditionArr = Array();
        $conditionArr['a_id'] = $aid;

        $dbAdm->deleteData($tablename, $conditionArr);
        $dbAdm->execSQL();
    }

    public function get($aid) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $column = Array();
        $column[0] = "*";

        $conditionArr = Array();
        $conditionArr['a_id'] = $aid;

        //$dbAdm->selectData($tablename, $column, $conditionArr, null, null);
        $dbAdm->sqlSet("select count(p.m_id) praiseAmount, m.m_user,
                case when (att.at_lastCh = 0) then '?' 
                else att.at_lastCh end as at_lastCh, 
                a.*, att.at_title, att.at_lastCh, att.as_id asid,
                ss.as_name
                from `Article` a 
                inner join ArticleTitle att on a.at_id = att.at_id
                left join ArticleSeries ss on ss.as_id = att.as_id
                left join Praise p on p.a_id = a.a_id
                inner join Member m on m.m_id = a.m_id
            where a.a_id = ". $aid. " group by p.a_id");
        $dbAdm->execSQL();

        return $dbAdm->getAll()[0];
    }

    public function articlesByArtTitle($atid) {
        $dbAdm = $this->dbAdm;
        $tablename = $this->table;

	$columns = Array();
	$columns[0] = "*";

        $conditionArr = Array();
        $conditionArr['at_id'] = $atid;

        $order = Array();
        $order['col'] = "a_chapter";
        $order['order'] = "asc";

	$dbAdm->selectData($tablename, $columns, $conditionArr, $order);
	$dbAdm->execSQL();
	return $dbAdm->getAll();
    }

    public function showUpd($aid, $act) {
        $dbAdm = $this->dbAdm;
        $tablename = $this->table;

        $colData = Array();
        $colData['a_isShow'] = $act;

        $conditionArr = Array();
        $conditionArr['a_id'] = $aid;

        $dbAdm->updateData($tablename, $colData, $conditionArr);
	$dbAdm->execSQL();
    }
}
