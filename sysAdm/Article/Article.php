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
        $order['col'] = "a_crtime";
        $order['order'] = "desc";

        //$dbAdm->selectData($tablename, $column, null, $order, $limit);
        $dbAdm->sqlSet("
            select m.m_user, count(p.p_id) praiseAmount ,
            case when (ass.as_finally = 0) then '?' else ass.as_finally end as as_finally, a.* 
            from `Article` a
            inner join Member m on a.m_id = m.m_id and m.m_user like '%$search%'
            left join ArticleSeries ass on a.as_id = ass.as_id
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
}