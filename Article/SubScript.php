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

        $sql = "";
        if($chooseCls == "m_id")
            $sql = "select ss.*, m.m_user from $tablename ss, Member m where ss.m_id = m.m_id ";
        else if($chooseCls == "as_id")
            $sql = "select ss.*, `as`.as_name from $tablename ss, ArticleSeries `as` where ss.as_id = `as`.as_id ";
        else if($chooseCls == "a_id")
            $sql = "select ss.*, a.a_title from $tablename ss, Article a where ss.a_id = a.a_id ";
        if($chooseCls != null)
            $sql .= " and ss.$chooseCls <> 0 ";
        $sql .= " and ss.m_who = $who limit ". $limit['offset']. ", ". $limit['amount'];
        $dbAdm->sqlSet($sql);
        $dbAdm->execSQL();
        return $dbAdm->getAll();
    }

    public function all($mid, $nowPage) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $column = Array();
        $column[0] = "*";

        $limit = Array();
        $limit['offset'] = ($nowPage - 1) * 25;
        $limit['amount'] = 25;

        $sql = "select * from $tablename 
            where m_who = $mid limit ". $limit['offset']. ", ". $limit['amount'];
        $dbAdm->sqlSet($sql);
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
