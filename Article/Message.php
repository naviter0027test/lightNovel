<?php
/*
 *  File Name :
 *	Message.php
 *  Describe :
 *	留言列表 、
 *  Start Date :
 *	2016.07.25
 *  Author :
 *	Lanker
 */

class Message {
    private $dbAdm;
    private $table;

    public function __construct() {
        if(file_exists("../srvLib/MysqlCon.php")) 
            require_once("../srvLib/MysqlCon.php");
        else
            require_once("srvLib/MysqlCon.php");
        if(file_exists("../server/config.php"))
            require_once("../server/config.php");
        else
            require_once("server/config.php");
        $this->config = new Config();
        $config = $this->config;
        $this->dbAdm = new MysqlCon(
            $config->getHost(), $config->getDBUser(),
            $config->getDBPass(), $config->getDB());
        $this->table = "Message";

        //設定php mysql client 的編碼為utf8
        $this->dbAdm->sqlSet("SET NAMES 'utf8'");
        $this->dbAdm->execSQL();
    }

    public function getList($aid, $nowPage) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $columns = Array();
        $columns[0] = "*";

        $conditionArr = Array();
        $conditionArr['a_id'] = $aid;

        $order = Array();
        $order['col'] = "ms_crtime";
        $order['order'] = "desc";

        $limit = Array();
        $limit['offset'] = ($nowPage -1) * 50;
        $limit['amount'] = 50;
        //$dbAdm->selectData($tablename, $columns, $conditionArr, $order, $limit);
        $dbAdm->sqlSet("select ms.*, m.m_user, m.m_headImg from Message ms inner join Member m on ms.m_id = m.m_id where ms.a_id = $aid and ms.parentMs_id = 0 order by ms_crtime asc limit ". $limit['offset']. ", ". $limit['amount']);
        $dbAdm->execSQL();
        return $dbAdm->getAll();
    }

    public function getReplyList($aid, $parentId) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $dbAdm->sqlSet("select ms.*, m.m_user, m.m_headImg from Message ms inner join Member m on ms.m_id = m.m_id where ms.parentMs_id = $parentId order by ms_crtime asc");
        $dbAdm->execSQL();
        return $dbAdm->getAll();
    }

    public function listAmount($aid) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;
        $dbAdm->sqlSet("select count(ms.ms_id) amount from Message ms where ms.a_id = $aid ");
        $dbAdm->execSQL();

        return $dbAdm->getAll()[0]['amount'];
    }

    public function myList($aids, $nowPage, $mid) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $nowPage = ($nowPage-1) *10;
        $aidsStr = implode(",", $aids);
        $dbAdm->sqlSet("select ms.*, m.m_user, a.a_chapter, att.at_lastCh, att.at_title
            from Message ms 
            inner join Member m on m.m_id = ms.m_id 
            inner join Article a on a.a_id = ms.a_id 
            inner join ArticleTitle att on att.at_id = a.at_id 
            where ms.a_id in ($aidsStr) order by ms.ms_crtime desc limit $nowPage, 10");
        $dbAdm->execSQL();

        return $dbAdm->getAll();
    }

    public function myListAmount($aids, $mid) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;
        $aidsStr = implode(",", $aids);
        $dbAdm->sqlSet("select count(ms.ms_id) as amount from Message ms inner join Member m on m.m_id = ms.m_id where ms.a_id in ($aidsStr) ");
        $dbAdm->execSQL();

        return $dbAdm->getAll()[0]['amount'];
    }

    public function reply($msid, $text) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $dbAdm->sqlSet("select * from Message ms where ms_id = $msid");
        $dbAdm->execSQL();
        $message = $dbAdm->getAll()[0];

        $insData = Array();
        $insData['a_id'] = $message['a_id'];
        $insData['m_id'] = $message['m_id'];
        $insData['parentMs_id'] = $msid;
        $insData['ms_reply'] = $text;
        $insData['ms_crtime'] = date('Y-m-d H:i:s');

        $dbAdm->insertData($tablename, $insData);
        $dbAdm->execSQL();

        /*
        $colData = Array();
        $colData['ms_reply'] = $text;

        $conditionArr = Array();
        $conditionArr['ms_id'] = $msid;
        $dbAdm->updateData($tablename, $colData, $conditionArr);
        */
    }

    public function del($msid) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $conditionArr = Array();
        $conditionArr['ms_id'] = $msid;
        $dbAdm->deleteData($tablename, $conditionArr);
        $dbAdm->execSQL();
    }

    public function delReply($msid) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $colData = Array();
        $colData['ms_reply'] = "";

        $conditionArr = Array();
        $conditionArr['ms_id'] = $msid;
        $dbAdm->updateData($tablename, $colData, $conditionArr);
        $dbAdm->execSQL();
    }

    public function getAuthor($aid) {
        $dbAdm = $this->dbAdm;

        $dbAdm->sqlSet("select m.* from Member m inner join Article a on a.a_id = $aid and a.m_id = m.m_id");
        $dbAdm->execSQL();
        return $dbAdm->getAll()[0];
    }

    public function getAuthorBymsid($msid) {
        $dbAdm = $this->dbAdm;

        $dbAdm->sqlSet("select m.* from Message ms 
            inner join Article a on a.a_id = ms.a_id 
            inner join Member m on m.m_id = a.m_id
            where ms.ms_id = $msid");
        $dbAdm->execSQL();
        return $dbAdm->getAll()[0];
    }
}
?>
