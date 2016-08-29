<?php
/*
 *  File Name :
 *	Member.php
 *  Describe :
 *      server 管理端
 *	所有會員管理 、
 *  Start Date :
 *	2016.08.12
 *  Author :
 *	Lanker
 */

class Member {
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
        $this->table = "Member";

        //設定php mysql client 的編碼為utf8
        $this->dbAdm->sqlSet("SET NAMES 'utf8'");
        $this->dbAdm->execSQL();
    }

    public function memberList($nowPage) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $column = Array();
        $column[0] = "*";

        $limit = Array();
        $limit['offset'] = 0;
        $limit['amount'] = 20;
        if(isset($nowPage))
            $limit['offset'] = ($nowPage -1) * 20;

        $dbAdm->selectData($tablename, $column, null, null, $limit);
        $dbAdm->execSQL();

        return $dbAdm->getAll();
    }

    public function activeUpd($mid, $active) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $colData = Array();
        $colData['m_active'] = $active;

        $conditionArr = Array();
        $conditionArr['m_id'] = $mid;

        $dbAdm->updateData($tablename, $colData, $conditionArr);
        $dbAdm->execSQL();
    }

    public function del($mid) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $conditionArr = Array();
        $conditionArr['m_id'] = $mid;

        $dbAdm->deleteData($tablename, $conditionArr);
        $dbAdm->execSQL();
    }
}

