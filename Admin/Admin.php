<?php
/*
 *  File Name :
 *	Member.php
 *  Describe :
 *      管理員登入登出
 *      網站開啟或關閉
 *  Start Date :
 *	2016.07.19
 *  Author :
 *	Lanker
 */

class Admin {
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
        $this->table = "Sys";

        //設定php mysql client 的編碼為utf8
        $this->dbAdm->sqlSet("SET NAMES 'utf8'");
        $this->dbAdm->execSQL();
    }

    public function isOpen() {
        $dbAdm = $this->dbAdm;
        $table = $this->table;
        $columns = Array();
        $columns[0] = "*";

        $conditionArr = Array();
        $conditionArr['`key`'] = "isOpenThisWeb";
        $dbAdm->selectData($table, $columns, $conditionArr);
        $dbAdm->execSQL();
        $admRes = $dbAdm->getAll()[0];

        if("Y" == $admRes['value'])
            return true;
        return false;
    }

    public function cpGet() {
        $dbAdm = $this->dbAdm;
        $table = $this->table;
        $cpData = Array();

        $columns = Array();
        $columns[0] = "*";

        $conditionArr = Array();
        $conditionArr['`key`'] = "cp1";
        $dbAdm->selectData($table, $columns, $conditionArr);
        $dbAdm->execSQL();

        $cpData['cp1'] = $dbAdm->getAll()[0];

        $conditionArr['`key`'] = "cp2";
        $dbAdm->selectData($table, $columns, $conditionArr);
        $dbAdm->execSQL();
        $cpData['cp2'] = $dbAdm->getAll()[0];

        return $cpData;
    }
}
?>
