<?php
/*
 *  File Name :
 *	ArticleTitle.php
 *  Describe :
 *	文章標題的處理 、
 *  Start Date :
 *	2016.09.02
 *  Author :
 *	Lanker
 */

class ArticleTitle {
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
        $this->table = "ArticleTitle";

        //設定php mysql client 的編碼為utf8
        $this->dbAdm->sqlSet("SET NAMES 'utf8'");
        $this->dbAdm->execSQL();
    }

    public function isRepeat($title) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $column = Array();
        $column[0] = "*";

        $conditionArr = Array();
        $conditionArr['at_title'] = $title;

        $dbAdm->selectData($tablename, $column, $conditionArr);
        $dbAdm->execSQL();

        if(count($dbAdm->getAll()) > 0) 
            return true;
        else
            return false;
    }

    public function getById($atid) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $column = Array();
        $column[0] = "*";

        $conditionArr = Array();
        $conditionArr['at_id'] = $atid;

        $dbAdm->selectData($tablename, $column, $conditionArr);
        $dbAdm->execSQL();

        $datas = $dbAdm->getAll();
        if(count($datas) < 1) 
            throw new Exception("not found the article title");

        return $datas[0];
    }

    public function get($title) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $column = Array();
        $column[0] = "*";

        $conditionArr = Array();
        $conditionArr['at_title'] = $title;

        $dbAdm->selectData($tablename, $column, $conditionArr);
        $dbAdm->execSQL();

        $datas = $dbAdm->getAll();
        if(count($datas) < 1) 
            throw new Exception("not found the article title");

        return $datas[0];
    }

    public function titleBySeries($para) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $column = Array();
        $column[0] = "*";

        $conditionArr = Array();
        $conditionArr['as_id'] = $para['asid'];
        $conditionArr['m_id'] = $para['mid'];

        $limit = Array();
        $limit['offset'] = $para['nowPage'] - 1;
        $limit['amount'] = 25;

        $dbAdm->selectData($tablename, $column, $conditionArr, null, $limit);
        $dbAdm->execSQL();

        $datas = $dbAdm->getAll();
        if(count($datas) < 1) 
            throw new Exception("not found the article title");

        return $datas;
    }

    public function adds($articletitle) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $insData = Array();
        $insData['at_title'] = $articletitle['title'];
        $insData['m_id'] = $articletitle['mid'];
        $insData['as_id'] = $articletitle['asid'];
        $insData['at_lastCh'] = $articletitle['lastCh'];
        $insData['at_updtime'] = date("Y-m-d H:i:s");
        $insData['at_crtime'] = date("Y-m-d H:i:s");

        $dbAdm->insertData($tablename, $insData);
        //echo $dbAdm->echoSQL();
        $dbAdm->execSQL();
    }

    public function updtime($atid) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $upData = Array();
        $upData['at_updtime'] = date("Y-m-d H:i:s");

        $conditionArr = Array();
        $conditionArr['at_id'] = $atid;
        $dbAdm->updateData($tablename, $upData, $conditionArr);
        $dbAdm->execSQL();
    }

    public function updasid($atid, $asid) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $upData = Array();
        $upData['as_id'] = $asid;

        $conditionArr = Array();
        $conditionArr['at_id'] = $atid;
        $dbAdm->updateData($tablename, $upData, $conditionArr);
        $dbAdm->execSQL();
    }

    public function updLastCh($atid, $lastCh) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $upData = Array();
        $upData['at_lastCh'] = $lastCh;

        $conditionArr = Array();
        $conditionArr['at_id'] = $atid;
        $dbAdm->updateData($tablename, $upData, $conditionArr);
        $dbAdm->execSQL();
    }
}
