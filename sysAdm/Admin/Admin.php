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
        $this->table = "Sys";

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
        $conditionArr['`key`'] = "account";
        $dbAdm->selectData($table, $columns, $conditionArr);
        $dbAdm->execSQL();
        $admUser = $dbAdm->getAll()[0];

        $conditionArr = Array();
        $conditionArr['`key`'] = "password";
        $dbAdm->selectData($table, $columns, $conditionArr);
        $dbAdm->execSQL();
        $admPass = $dbAdm->getAll()[0];

        if($user == $admUser['value'] && md5($pass) == $admPass['value'])
            return true;
        return false;
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

    public function close() {
        $dbAdm = $this->dbAdm;
        $table = $this->table;

        $colData = Array();
        $colData['value'] = "N";

        $conditionArr = Array();
        $conditionArr['`key`'] = "isOpenThisWeb";
        $dbAdm->updateData($table, $colData, $conditionArr);
        $dbAdm->execSQL();
    }

    public function open() {
        $dbAdm = $this->dbAdm;
        $table = $this->table;

        $colData = Array();
        $colData['value'] = "Y";

        $conditionArr = Array();
        $conditionArr['`key`'] = "isOpenThisWeb";
        $dbAdm->updateData($table, $colData, $conditionArr);
        $dbAdm->execSQL();
    }

    public function passUpd($oldPass, $newPass) {
        $dbAdm = $this->dbAdm;
        $table = $this->table;

        $columns = Array();
        $columns[0] = "*";

        $conditionArr = Array();
        $conditionArr['`key`'] = "password";

        $dbAdm->selectData($table, $columns, $conditionArr);
        $dbAdm->execSQL();
        $pass = $dbAdm->getAll()[0];

        if($pass['value'] != md5($oldPass)) {
            throw new Exception("old password error");
        }

        $colData = Array();
        $colData['value'] = md5($newPass);

        $dbAdm->updateData($table, $colData, $conditionArr);
        $dbAdm->execSQL();
    }

    public function passOverlap($newPass) {
        $dbAdm = $this->dbAdm;
        $table = $this->table;

        $columns = Array();
        $columns[0] = "*";

        $conditionArr = Array();
        $conditionArr['`key`'] = "password";

        $colData = Array();
        $colData['value'] = md5($newPass);

        $dbAdm->updateData($table, $colData, $conditionArr);
        $dbAdm->execSQL();
    }

    public function sysSet($post) {
        $dbAdm = $this->dbAdm;
        $table = $this->table;

        $colData = Array();
        $conditionArr = Array();
        //只有一次
        foreach($post as $key => $value) {
            $colData['value'] = $value;

            $conditionArr['`key`'] = $key;
        }

        $dbAdm->updateData($table, $colData, $conditionArr);
        $dbAdm->execSQL();
    }

    public function forgetMailGet() {
        $dbAdm = $this->dbAdm;
        $table = $this->table;

        $columns = Array();
        $columns[0] = "*";

        $conditionArr = Array();
        $conditionArr['`key`'] = "forgetPwSendMail";

        $dbAdm->selectData($table, $columns, $conditionArr);
        $dbAdm->execSQL();
        $forgetMail = $dbAdm->getAll()[0];
        return $forgetMail['value'];
    }

    public function forget() {
        if(file_exists("../../srvLib/SmailMail.php")) 
            require_once("../../srvLib/SmailMail.php");
        else
            require_once("../srvLib/SmailMail.php");
        $dbAdm = $this->dbAdm;
        $table = $this->table;
        $columns = Array();
        $columns[0] = "*";

        $conditionArr = Array();
        $conditionArr['`key`'] = "forgetPwSendMail";

        $dbAdm->selectData($table, $columns, $conditionArr);
        $dbAdm->execSQL();
        $email = $dbAdm->getAll()[0];

        $newPassword = $this->newPass(8);
        $this->passOverlap($newPassword);

        $content = "忘记密码信件，您的新密码：". $newPassword;
        sendMail($email['value'], "[核桃管理员]忘记密码信件（系统发信，请勿回覆）", $content);
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

    public function cpUpd($cp1, $cp2) {
        $dbAdm = $this->dbAdm;
        $table = $this->table;
        $colData = Array();
        $colData['value'] = $cp1;

        $conditionArr = Array();
        $conditionArr['`key`'] = "cp1";

        $dbAdm->updateData($table, $colData, $conditionArr);
        $dbAdm->execSQL();

        $colData['value'] = $cp2;

        $conditionArr['`key`'] = "cp2";

        $dbAdm->updateData($table, $colData, $conditionArr);
        $dbAdm->execSQL();
    }

    public function lists() {
        $dbAdm = $this->dbAdm;
        $tablename = "Admin";

        $dbAdm->sqlSet("select * from Admin where adm_id <> 1");
        $dbAdm->execSQL();
        return $dbAdm->getAll();
    }

    public function get($admid) {
        $dbAdm = $this->dbAdm;
        $tablename = "Admin";

        if($admid == 1) 
            throw new Exception("you can't get root");

        $dbAdm->sqlSet("select * from Admin where adm_id = $admid");
        $dbAdm->execSQL();
        return $dbAdm->getAll()[0];
    }

    public function newPass($num = 8) {
        $chars = "1qaz2wsx3edc4rfv5tgb6yhn7ujm8ik9ol0p";
        $newPassword = "";
        for($i = 0;$i < $num;++$i) {
            $pos = rand(0, strlen($chars)-1);
            $newPassword .= $chars[$pos];
        }
        return $newPassword;
    }
}

