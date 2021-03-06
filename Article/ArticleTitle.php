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

    public function isRepeat($title, $mid) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $column = Array();
        $column[0] = "*";

        $conditionArr = Array();
        $conditionArr['at_title'] = $title;
        $conditionArr['m_id'] = $mid;

        $dbAdm->selectData($tablename, $column, $conditionArr);
        $dbAdm->execSQL();

        $data = $dbAdm->getAll();
        if(count($data) > 0) {
            /*
            if($data[0]['m_id'] != $mid) 
                throw new Exception("title is used");
             */
            return true;
        }
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

    public function del($atid, $mid) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $conditionArr = Array();
        $conditionArr['at_id'] = $atid;
        $conditionArr['m_id'] = $mid;

        $dbAdm->deleteData($tablename, $conditionArr);
        $dbAdm->execSQL();
    }

    public function delOnlyTitle() {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;
        $sql = "DELETE FROM ArticleTitle WHERE NOT EXISTS (
            SELECT *
            FROM Article a
            WHERE ArticleTitle.at_id = a.at_id
        )";
        $dbAdm->sqlSet($sql);
        $dbAdm->execSQL();
    }
}
