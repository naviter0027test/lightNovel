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
}
?>
