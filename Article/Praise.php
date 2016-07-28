<?php
/*
 *  File Name :
 *	Praise.php
 *  Describe :
 *	點讚模組 、
 *  Start Date :
 *	2016.07.06
 *  Author :
 *	Lanker
 */

class Praise {
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
        $this->table = "Praise";

        //設定php mysql client 的編碼為utf8
        $this->dbAdm->sqlSet("SET NAMES 'utf8'");
        $this->dbAdm->execSQL();
    }

    public function getPraise($mid, $aid) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;
        $column = Array();
        $column[0] = "*";

        $conditionArr = Array();
        $conditionArr['a_id'] = $aid;
        $conditionArr['m_id'] = $mid;

        $dbAdm->selectData($tablename, $column, $conditionArr);
        $dbAdm->execSQL();

        return $dbAdm->getAll();
    }

    public function addPraise($mid, $aid) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $insData = Array();
        $insData['a_id'] = $aid;
        $insData['m_id'] = $mid;
        $insData['p_crtime'] = date('Y-m-d H:i:s');

        $dbAdm->insertData($tablename, $insData);
        $dbAdm->execSQL();
    }
}
?>
