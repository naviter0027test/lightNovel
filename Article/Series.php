<?php
/*
 *  File Name :
 *	Series.php
 *  Describe :
 *	系列發表 、
 *	系列列表 、
 *	系列修改 、
 *	系列刪除
 *  Start Date :
 *	2016.07.06
 *  Author :
 *	Lanker
 */

class Series {
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
        $this->table = "ArticleSeries";

        //設定php mysql client 的編碼為utf8
        $this->dbAdm->sqlSet("SET NAMES 'utf8'");
        $this->dbAdm->execSQL();
    }
    public function serAdd($mid, $name) {
        $table = $this->table;
        $dbAdm = $this->dbAdm;
        $insData = Array();
        $insData['m_id'] = $mid;
        $insData['as_name'] = $name;

        $dbAdm->insertData($table, $insData);
        $dbAdm->execSQL();
    }

    public function serList($listPara, $mid) {
        $dbAdm = $this->dbAdm;
        $tablename = $this->table;

        $nowPage = 1;
	if(isset($listPara['nowPage']))
	    $nowPage = $listPara['nowPage'];
        $pageLimit = $listPara['pageLimit'];

	$columns = Array();
	$columns[0] = "*";
	$conditionArr = Array();
        $conditionArr['m_id'] = $mid;

        $limit = Array();
        $limit['offset'] = ($nowPage - 1) * $pageLimit;
        $limit['amount'] = $pageLimit;
	$dbAdm->selectData($tablename, $columns, $conditionArr, null, $limit);
	$dbAdm->execSQL();
	return $dbAdm->getAll();
    }

    public function serUpd($data) {
        $dbAdm = $this->dbAdm;
        $tablename = $this->table;

        $upData = Array();
        $upData['as_name'] = $data['as_name'];

        $conditionArr = Array();
        $conditionArr['as_id'] = $data['as_id'];
        $dbAdm->updateData($tablename, $upData, $conditionArr);
        $dbAdm->execSQL();
    }

    public function serDel($id) {
        $dbAdm = $this->dbAdm;
        $tablename = $this->table;
	$conditionArr = Array();
	$conditionArr['as_id'] = $id;

	$dbAdm->deleteData($tablename, $conditionArr);
	$dbAdm->execSQL();
    }

    public function getOne($id) {
        $dbAdm = $this->dbAdm;
        $tablename = $this->table;

	$columns = Array();
	$columns[0] = "*";
	$conditionArr = Array();
        $conditionArr['as_id'] = $id;

	$dbAdm->selectData($tablename, $columns, $conditionArr);
	$dbAdm->execSQL();
	return $dbAdm->getAll()[0];
    }

    public function amount($mid) {
        $dbAdm = $this->dbAdm;
        $tablename = $this->table;

	$columns = Array();
	$columns[0] = "count(*) amount";
	$conditionArr = Array();
        $conditionArr['m_id'] = $mid;

	$dbAdm->selectData($tablename, $columns, $conditionArr);
	$dbAdm->execSQL();
	return $dbAdm->getAll()[0];
    }
}
