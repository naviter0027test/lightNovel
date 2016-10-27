<?php
/*
 *  File Name :
 *	Synonyms.php
 *  Describe :
 *	同義字的更換
 *  Start Date :
 *	2016.10.27
 *  Author :
 *	Lanker
 */

class Synonyms {
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
        $this->table = "Synonyms";

        //設定php mysql client 的編碼為utf8
        $this->dbAdm->sqlSet("SET NAMES 'utf8'");
        $this->dbAdm->execSQL();
    }

    public function substitute($str) {
        $dbAdm = $this->dbAdm;
        $tableName = $this->table;

        $column = Array();
        $column[0] = "*";

        $conditionArr = Array();
        $conditionArr['sy_key'] = "$str";

        $dbAdm->selectData($tableName, $column, $conditionArr);
        $dbAdm->execSQL();
        $data = $dbAdm->getAll();
        $synonStr = $str;
        if(count($data) > 0)
            $synonStr = $data[0]['sy_value'];
        return $synonStr;
    }
}
