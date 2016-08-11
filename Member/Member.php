<?php
/*
 *  File Name :
 *	Member.php
 *  Describe :
 *	會員登入 、
 *	會員修改一般資料 、
 *	會員註冊、
 *	會員修改密碼
 *  Version : 
 *	1.0
 *  Start Date :
 *	2016.07.05
 *  Author :
 *	Lanker
 */

class Member {
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
        $this->table = "Member";
        
        //設定php mysql client 的編碼為utf8
        $this->dbAdm->sqlSet("SET NAMES 'utf8'");
        $this->dbAdm->execSQL();
    }

    public function login($user, $pass) {
        $dbAdm = $this->dbAdm;
        $table = $this->table;
        $columns = Array();
        $columns[0] = "*";

        $conditionArr = Array();
        $conditionArr['m_user'] = $user;
        $conditionArr['m_pass'] = md5($pass);
        $conditionArr['m_active'] = "Y";
        $dbAdm->selectData($table, $columns, $conditionArr);
        $dbAdm->execSQL();
        $mems = $dbAdm->getAll();
        if(count($mems) < 1)
            throw new Exception("not find member");
        $mem = $mems[0];
        return $mem['m_id'];
    }

    public function getOneById($mid) {
        $dbAdm = $this->dbAdm;
        $table = $this->table;
        $columns = Array();
        $columns[0] = "m_user";
        $columns[1] = "m_email";
        $columns[2] = "m_headImg";

        $conditionArr = Array();
        $conditionArr['m_id'] = $mid;
        $dbAdm->selectData($table, $columns, $conditionArr);
        $dbAdm->execSQL();
        $mems = $dbAdm->getAll();
        $mem = $mems[0];
        return $mem;
    }

    public function getOnePassById($mid) {
        $dbAdm = $this->dbAdm;
        $table = $this->table;
        $columns = Array();
        $columns[0] = "m_pass";

        $conditionArr = Array();
        $conditionArr['m_id'] = $mid;
        $dbAdm->selectData($table, $columns, $conditionArr);
        $dbAdm->execSQL();
        $mems = $dbAdm->getAll();
        $pass = $mems[0]['m_pass'];
        return $pass;
    }

    public function getOne($user) {
        $dbAdm = $this->dbAdm;
        $table = $this->table;
        $columns = Array();
        $columns[0] = "*";

        $conditionArr = Array();
        $conditionArr['m_user'] = $user;
        $dbAdm->selectData($table, $columns, $conditionArr);
        $dbAdm->execSQL();
        $mems = $dbAdm->getAll();
        $mem = $mems[0];
        return $mem;
    }

    public function register($user) {
        $dbAdm = $this->dbAdm;
        if($this->isRegister($user['user']))
            throw new Exception("member repeat");
        $insData = Array();
        $insData['m_user'] = $user['user'];
        $insData['m_pass'] = md5($user['pass']);
        $insData['m_email'] = $user['email'];
        $insData['m_active'] = "N";
        $dbAdm->insertData("Member", $insData);
        $dbAdm->execSQL();
        $this->upActiveMail($user);
    }

    public function upActiveMail($user) {
        if(file_exists("../srvLib/SmailMail.php")) 
            require_once("../srvLib/SmailMail.php");
        else
            require_once("srvLib/SmailMail.php");
        $lastSlash = strrpos($_SERVER['PHP_SELF'], '/', -1);
        $upActive = "http://". $_SERVER['HTTP_HOST']. substr($_SERVER['PHP_SELF'], 0, $lastSlash) . "/verification.html?instr=upActive&user=".
            $user['user']. "&email=". md5($user['email'].$user['user']);
        $content = "歡迎加入樓誠文庫，<a target='_blank' href='$upActive'>啟用連結</a>";
        sendMail($user['email'], "[樓誠]啟用信件（系統發信，請勿回覆）", $content);
    }

    public function authenticate($user, $authCode) {
        $dbAdm = $this->dbAdm;
        $tablename = $this->table;
        $mem = $this->getOne($user);
        if(!isset($mem['m_id']))
            throw new Exception("member not found");
        if($authCode == md5($mem['m_email'].$mem['m_user'])) {
            $updata = Array();
            $updata['m_active'] = "Y";
            $conditionArr = Array();
            $conditionArr['m_id'] = $mem['m_id'];
            $dbAdm->updateData($tablename, $updata, $conditionArr);
            $dbAdm->execSQL();
        }
        else
            throw new Exception("authentication code error");
    }

    public function isRegister($account) {
        $dbAdm = $this->dbAdm;
        $columns = Array();
        $columns[0] = "*";
        $conditionArr = Array();
        $conditionArr['m_user'] = $account;
        $dbAdm->selectData("Member", $columns, $conditionArr);
        $dbAdm->execSQL();
        $memAmount = count($dbAdm->getAll());
        if($memAmount != 0)
            return true;
        return false;
    }

    public function dataUpdate($colData, $mid) {
        $dbAdm = $this->dbAdm;
        $tablename = $this->table;
        $conditionArr = Array();
        $conditionArr['m_id'] = $mid;

        $dbAdm->updateData($tablename, $colData, $conditionArr);
        $dbAdm->execSQL();
    }

    public function error() {
        $dbAdm = $this->dbAdm;
        return $dbAdm->errorMsg();
    }
}
