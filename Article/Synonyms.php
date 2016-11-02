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

        //echo $str;
        $strArr = preg_split('/;/', $str);
        $synonStr = $str;
        foreach($strArr as $sy_key) {
            $column = Array();
            $column[0] = "*";

            $conditionArr = Array();
            $conditionArr['sy_key'] = "$sy_key";

            $dbAdm->selectData($tableName, $column, $conditionArr);
            $dbAdm->execSQL();
            $data = $dbAdm->getAll();
            if(count($data) > 0)
                $synonStr = preg_replace("/$sy_key/", $data[0]['sy_value'], $synonStr);
        }
        //echo $synonStr;
        return $synonStr;
    }
}
