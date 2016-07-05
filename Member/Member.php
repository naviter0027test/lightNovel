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
    }

    public function login($user, $pass) {
        $dbAdm = $this->dbAdm;
        $table = $this->table;
        $columns = Array();
        $columns[0] = "*";

        $conditionArr = Array();
        $conditionArr['m_user'] = $user;
        $conditionArr['m_pass'] = $pass;
        $conditionArr['m_active'] = "Y";
        $dbAdm->selectData($table, $columns, $conditionArr);
        $dbAdm->execSQL();
        $mems = $dbAdm->getAll();
        if(count($mems) < 1)
            throw new Exception("not find member");
        $mem = $mems[0];
        return $mem['m_id'];
    }

    public function register($user) {
        $dbAdm = $this->dbAdm;
        $insData = Array();
        $insData['m_user'] = $user['user'];
        $insData['m_pass'] = md5($user['pass']);
        $insData['m_email'] = $user['email'];
        $insData['m_active'] = "Y";
        $dbAdm->insertData("Member", $insData);
        $dbAdm->execSQL();
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

    public function error() {
        $dbAdm = $this->dbAdm;
        return $dbAdm->errorMsg();
    }
}
