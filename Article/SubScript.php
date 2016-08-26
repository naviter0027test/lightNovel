<?php
/*
 *  File Name :
 *	SubScript.php
 *  Describe :
 *      訂閱單篇、系列與指定作者
 *  Start Date :
 *	2016.08.25
 *  Author :
 *	Lanker
 */

class SubScript {
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
        $this->table = "SubScription";

        //設定php mysql client 的編碼為utf8
        $this->dbAdm->sqlSet("SET NAMES 'utf8'");
        $this->dbAdm->execSQL();
    }

    public function subscript($who, $insData) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        if(count($insData) != 1) 
            throw new Exception("need only one argu");

        foreach($insData as $selecCol => $findValue) {
            $col = $selecCol;
            $val = $findValue;
        }
        $dbAdm->sqlSet("select * from SubScription where m_who = $who and $col = $val");
        $dbAdm->execSQL();
        if(count($dbAdm->getAll()) > 0)
            throw new Exception("have been subscript");

        $insData['m_who'] = $who;
        $insData['ss_chkTime'] = date("Y-m-d H:i:s");
        $insData['ss_updTime'] = date("Y-m-d H:i:s");
        $dbAdm->insertData($tablename, $insData);
        $dbAdm->execSQL();
    }

    public function lists($who, $nowPage = 1, $chooseCls = null) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $column = Array();
        $column[0] = "*";

        $limit = Array();
        $limit['offset'] = ($nowPage - 1) * 25;
        $limit['amount'] = 25;

        $sql = "select * from $tablename where 1 = 1 ";
        if($chooseCls != null)
            $sql .= "and $chooseCls <> 0 ";
        $sql .= " and m_who = $who limit ". $limit['offset']. ", ". $limit['amount'];
        $dbAdm->sqlSet($sql);
        $dbAdm->execSQL();
        return $dbAdm->getAll();
    }

    public function cancel($who, $conditionArr) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        if(count($conditionArr) != 1) 
            throw new Exception("need only one argu");

        $conditionArr['m_who'] = $who;
        $dbAdm->deleteData($tablename, $conditionArr);
        $dbAdm->execSQL();
    }
}
