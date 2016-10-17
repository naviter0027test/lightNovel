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

    public function isRepeat($who, $insData) {
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
            return true;
        return false;
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
            $sql = "select ss.*, a.a_chapter, att.at_title, att.at_lastCh from $tablename ss, Article a, ArticleTitle att where ss.a_id = a.a_id and att.at_id = a.at_id";
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

        $sql = "select ss.*, att.at_title, m.m_user, s.as_name from $tablename ss
            left join Article a on a.a_id = ss.a_id
            left join Member m on m.m_id = ss.m_id
            left join ArticleSeries s on s.as_id = ss.as_id
            left join ArticleTitle att on att.at_id = a.at_id
            where ss.m_who = $mid 
            order by ss_updTime desc
            limit ". $limit['offset']. ", ". $limit['amount'];
        //echo $sql;
        $dbAdm->sqlSet($sql);
        $dbAdm->execSQL();
        $data = $dbAdm->getAll();
        foreach($data as $idx => $item) {
            if($item['a_id'] != 0)
                $data[$idx]['cls'] = "article";
            else if($item['as_id'] != 0)
                $data[$idx]['cls'] = "series";
            else if($item['m_id'] != 0)
                $data[$idx]['cls'] = "member";
        }
        return $data;
    }

    public function allSubscript($mid) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $column = Array();
        $column[0] = "*";

        $dbAdm->selectData($tablename, $column);
        $dbAdm->execSQL();
        $data = $dbAdm->getAll();
        return $data;
    }

    public function allAmount($mid) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $column = Array();
        $column[0] = "*";

        $sql = "select count(ss.a_id) amount from $tablename ss
            left join Article a on a.a_id = ss.a_id
            left join Member m on m.m_id = ss.m_id
            left join ArticleSeries s on s.as_id = ss.as_id
            left join ArticleTitle att on att.at_id = a.at_id
            where ss.m_who = $mid 
            order by ss_updTime desc ";
        //echo $sql;
        $dbAdm->sqlSet($sql);
        $dbAdm->execSQL();
        $amount = $dbAdm->getAll()[0]['amount'];
        return $amount;
    }

    public function amount($mid, $cls) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $column = Array();
        $column[0] = "*";

        $sql = "select count(ss.a_id) amount from $tablename ss
            left join Article a on a.a_id = ss.a_id
            left join Member m on m.m_id = ss.m_id
            left join ArticleSeries s on s.as_id = ss.as_id
            left join ArticleTitle att on att.at_id = a.at_id
            where ss.m_who = $mid 
            and ss.$cls <> 0
            order by ss_updTime desc ";
        //echo $sql;
        $dbAdm->sqlSet($sql);
        $dbAdm->execSQL();
        $amount = $dbAdm->getAll()[0]['amount'];
        return $amount;
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

    public function isSubscript($who, $aid) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $column = Array();
        $column[0] = "*";

        $conditionArr = Array();
        $conditionArr['m_who'] = $who;
        $conditionArr['a_id'] = $aid;

        $dbAdm->selectData($tablename, $column, $conditionArr);
        $dbAdm->execSQL();
        $data = $dbAdm->getAll();
        if(isset($data[0]))
            return true;
        else
            return false;
    }

    public function isSeriesSubscript($who, $sid) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $column = Array();
        $column[0] = "*";

        $conditionArr = Array();
        $conditionArr['m_who'] = $who;
        $conditionArr['as_id'] = $sid;

        $dbAdm->selectData($tablename, $column, $conditionArr);
        $dbAdm->execSQL();
        $data = $dbAdm->getAll();
        if(isset($data[0]))
            return true;
        else
            return false;
    }

    public function isMemberSubscript($who, $author) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $column = Array();
        $column[0] = "*";

        $conditionArr = Array();
        $conditionArr['m_who'] = $who;
        $conditionArr['m_id'] = $author;

        $dbAdm->selectData($tablename, $column, $conditionArr);
        $dbAdm->execSQL();
        $data = $dbAdm->getAll();
        if(isset($data[0]))
            return true;
        else
            return false;
    }
}
