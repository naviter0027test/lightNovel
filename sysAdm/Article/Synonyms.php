<?php
/*
 *  File Name :
 *	Synonyms.php
 *  Describe :
 *      server 管理端
 *	同義字新增與刪除
 *  Start Date :
 *	2016.10.27
 *  Author :
 *	Lanker
 */

class Synonyms {
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

    public function adds($k, $val) {
        $dbAdm = $this->dbAdm;
        $table = "Synonyms";

        $insData = Array();
        $insData['sy_key'] = $k;
        $insData['sy_value'] = $val;
        $insData['sy_crTime'] = date("Y-m-d H:i:s");

        $dbAdm->insertData($table, $insData);
        $dbAdm->execSQL();
    }

    public function del($syid) {
        $dbAdm = $this->dbAdm;
        $table = "Synonyms";

        $conditionArr = Array();
        $conditionArr['sy_id'] = $syid;

        $dbAdm->deleteData($table, $conditionArr);
        $dbAdm->execSQL();
    }

    public function lists($nowPage) {
        $dbAdm = $this->dbAdm;
        $table = "Synonyms";

        $column = Array();
        $column[0] = "*";

        $limit = Array();
        $limit['offset'] = ($nowPage - 1) * 20;
        $limit['amount'] = 20;

        $dbAdm->selectData($table, $column, null, null, $limit);
        $dbAdm->execSQL();
        return $dbAdm->getAll();
    }
}
