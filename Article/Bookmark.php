<?php
/*
 *  File Name :
 *	SubScript.php
 *  Describe :
 *      訂閱單篇、系列與指定作者
 *  Start Date :
 *	2016.08.25
 *  Author :
 *	Lanker
 */

class Bookmark {
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
        $this->table = "Bookmark";

        //設定php mysql client 的編碼為utf8
        $this->dbAdm->sqlSet("SET NAMES 'utf8'");
        $this->dbAdm->execSQL();
    }

    public function adds($who, $bookid) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $insData = Array();
        $insData['bm_who'] = $who;
        $insData['b_bookId'] = $bookid;
        $insData['b_crtime'] = date("Y-m-d H:i:s");

        $dbAdm->insertData($tablename, $insData);
        $dbAdm->execSQL();
    }

    public function isBook($who, $bookid) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $column = Array();
        $column[0] = "*";

        $conditionArr = Array();
        $conditionArr['bm_who'] = $who;
        $conditionArr['b_bookId'] = $bookid;

        $dbAdm->selectData($tablename, $column, $conditionArr);
        $dbAdm->execSQL();
        if(count($dbAdm->getAll()) > 0)
            return true;
        else
            return false;
    }

    public function cancel($who, $bookid) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $conditionArr = Array();
        $conditionArr['bm_who'] = $who;
        $conditionArr['b_bookId'] = $bookid;

        $dbAdm->deleteData($tablename, $conditionArr);
        $dbAdm->execSQL();
    }

    public function lists($who, $nowPage) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $column = Array();
        $column[0] = "*";

        $conditionArr = Array();
        $conditionArr['bm_who'] = $who;

        $limit = Array();
        $limit['offset'] = ($nowPage -1) * 25;
        $limit['amount'] = 25;

        //$dbAdm->selectData($tablename, $column, $conditionArr, null, $limit);
        $dbAdm->sqlSet("select a.*, att.at_title, att.at_lastCh, bm.b_crtime, bm.b_id from Bookmark bm 
            inner join Article a on a.a_id = bm.b_bookId 
            inner join ArticleTitle att on att.at_id = a.at_id
            where bm_who = $who
            limit ". $limit['offset']. ", ". $limit['amount']);
        $dbAdm->execSQL();
        return $dbAdm->getAll();
    }
}
