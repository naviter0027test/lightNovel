<?php
/*
 *  File Name :
 *	Member.php
 *  Describe :
 *	會員登入 、
 *	會員修改一般資料 、
 *	會員註冊、啟用、認證
 *	會員修改密碼、
 *	會員留言
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
        //$conditionArr['m_active'] = "Y";
        $dbAdm->selectData($table, $columns, $conditionArr);
        $dbAdm->execSQL();
        $mems = $dbAdm->getAll();
        if(count($mems) < 1)
            throw new Exception("not find member");
        $mem = $mems[0];
        /*
        if($mem['m_active'] == "N")
            throw new Exception("member not active");
         */
        if($mem['m_active'] == "D")
            throw new Exception("member is disable");
        return $mem['m_id'];
    }

    public function getOneById($mid) {
        $dbAdm = $this->dbAdm;
        $table = $this->table;
        $columns = Array();
        $columns[0] = "m_user";
        $columns[1] = "m_email";
        $columns[2] = "m_headImg";
        $columns[3] = "isEmailForGetMsg";
        $columns[4] = "isEmailForGetPraise";
        $columns[5] = "isEmailForGetGift";

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
        if($this->isRegister($user['user']) || $this->isMailRepeat($user['email']))
            throw new Exception("member or email repeat");
        $insData = Array();
        $insData['m_user'] = $user['user'];
        $insData['m_pass'] = md5($user['pass']);
        $insData['m_email'] = $user['email'];
        $insData['m_active'] = "N";
        $insData['m_crtime'] = date("Y-m-d H:i:s");
        $dbAdm->insertData("Member", $insData);
        $dbAdm->execSQL();
        $this->upActiveMail($user);
    }

    public function upActiveMail($user) {
        $mem = $this->getOne($user['user']);
        if(file_exists("../srvLib/SmailMail.php")) 
            require_once("../srvLib/SmailMail.php");
        else
            require_once("srvLib/SmailMail.php");
        $lastSlash = strrpos($_SERVER['PHP_SELF'], '/', -1);
        $upActive = "http://". $_SERVER['HTTP_HOST']. substr($_SERVER['PHP_SELF'], 0, $lastSlash) . "/verification.html?instr=upActive&user=".
            $user['user']. "&email=". md5($user['email'].$mem['m_id']);
        //$content = "歡迎加入樓誠文庫，<a target='_blank' href='$upActive'>啟用連結</a>";
        $content = "欢迎加入核桃文库，<a target='_blank' href='$upActive'>啟用連結</a>
            <br /> <br />
            欢迎注册本站。
            <br /> <br />
            本站为女性向同人创作网站。如您不了解或不喜欢女性向、耽美、同人作品，请勿进入。
            <br /> <br />
            本站不接受如下作品：<br />
            1. 主CP非女性向配对的作品；<br />
            2. 含有真人CP内容的作品；<br />
            3. 描写儿童色情内容的作品；<br />
            4. 含有侮辱人物或过度令人不安内容的作品。<br />
            <br /> <br />
            使用本站请遵守如下规则：<br />
            1. 遵守同人礼仪，请勿在真人在场场合或不宜提及同人创作的社交媒体提及本站；<br />
            2. 理性交流、文明用语。<br />
            3. 如需投诉发布有误的文章，或任何需求及建议，请联系管理组（walnutfics@163.com）或shijinhetao.lofter.com<br />
            <br /> <br />
            ";
        sendMail($user['email'], "[核桃文库]启用信件（系统发信，请勿回覆）", $content);
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

    public function forget($user) {
        if(file_exists("../srvLib/SmailMail.php")) 
            require_once("../srvLib/SmailMail.php");
        else
            require_once("srvLib/SmailMail.php");
        $dbAdm = $this->dbAdm;
        $tablename = $this->table;
        $mem = $this->getOne($user);
        $newPassword = $this->newPass(8);
        $colData = Array();
        $colData['m_pass'] = md5($newPassword);
        $this->dataUpdate($colData, $mem['m_id']);
        $content = "忘记密码信件，您的新密码：". $newPassword;
        sendMail($mem['m_email'], "[核桃]忘记密码信件（系统发信，请勿回覆）", $content);
    }

    public function authenticate($user, $authCode) {
        $dbAdm = $this->dbAdm;
        $tablename = $this->table;
        $mem = $this->getOne(urldecode($user));
        if(!isset($mem['m_id']))
            throw new Exception("member not found");
        if($authCode == md5($mem['m_email'].$mem['m_id'])) {
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

    public function isMailRepeat($email) {
        $dbAdm = $this->dbAdm;
        $columns = Array();
        $columns[0] = "*";
        $conditionArr = Array();
        $conditionArr['m_email'] = $email;
        $conditionArr['m_active'] = "Y";
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

    public function randOneMem() {
        $dbAdm = $this->dbAdm;
        $tablename = $this->table;

        $dbAdm->sqlSet("select * from Member where m_active = 'Y' order by rand() limit 0, 1");
        $dbAdm->execSQL();
        return $dbAdm->getAll()[0];
    }

    public function addMsg($para) {
        $dbAdm = $this->dbAdm;
        $tablename = "Message";
        if(!isset($para['mid']))
            throw new Exception("member not login");
        if(!isset($para['aid']))
            throw new Exception("article not choose");
        if(!isset($para['message']) || $para['message'] == "")
            throw new Exception("message is empty");

        $dbAdm->sqlSet("select max(ms_floor) floor from Message where a_id = ". $para['aid']);
        $dbAdm->execSQL();
        $maxFloor = $dbAdm->getAll()[0]['floor'];
        if($maxFloor == null)
            $maxFloor = 0;
        else
            $maxFloor += 1;

        $insData = Array();
        $insData['ms_floor'] = $maxFloor;
        $insData['a_id'] = $para['aid'];
        $insData['m_id'] = $para['mid'];
        $insData['ms_text'] = $para['message'];
        $insData['ms_crtime'] = date("Y-m-d H:i:s");
        $dbAdm->insertData($tablename, $insData);
        $dbAdm->execSQL();
    }

    public function find($user) {
        $dbAdm = $this->dbAdm;
        $columns = Array();
        $columns[0] = "*";

        $conditionArr = Array();
        $conditionArr['m_user'] = $user;
        $conditionArr['m_active'] = "Y";

        $dbAdm->selectData("Member", $columns, $conditionArr);
        $dbAdm->execSQL();
        $mems = $dbAdm->getAll();
        if(count($mems) < 1)
            throw new Exception("member not find");
        return $mems[0];
    }

    public function error() {
        $dbAdm = $this->dbAdm;
        return $dbAdm->errorMsg();
    }
}
