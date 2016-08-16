<?php
/* 
 * file name : 
 *	Control.php
 * description :
 *	負責控制網址與POST的操作
 * start date :
 *	2016/04/20
 * author :
 *	Lanker
 */

session_start();

class Control {
    private $instr;
    public function __construct() {
	date_default_timezone_set('Asia/Taipei');

        if(isset($_GET['instr']))
	    $this->instr = $_GET['instr'];
	if(isset($_POST['instr']))
	    $this->instr = $_POST['instr'];
    }
    public function execInstr() {
	try {
	    if(!isset($this->instr))
		throw new Exception("instr not defined");
	    $instr = $this->instr;
	    $reData = $instr();
	    echo json_encode($reData);
	}
	catch(Exception $e) {
	    $reData = Array();
	    $reData['status'] = 500;
	    $reData['msg'] = $e->getMessage();
	    $reData['trace'] = $e->getTrace();
	    echo json_encode($reData);
	}
    }
}

function test() {
    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "test success";
    return $reData;
}

function captchaLogin() {
    require_once("../srvLib/Captcha.php");
    $_SESSION['login']['captcha'] = rand(1000, 9999);
    $captcha = new Captcha($_SESSION['login']['captcha']);
}

function articleList() {
    require_once("Article/Article.php");
    $article = new Article();
    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "articleList success";
    $reData['data'] = $article->articleList($_POST['nowPage'], $_POST['search']);
    return $reData;
}

function memberList() {
    require_once("Member/Member.php");
    $memAdm = new Member();
    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "memberList success";
    $reData['data'] = $memAdm->memberList($_POST['nowPage']);
    return $reData;
}

function memberActive() {
    require_once("Member/Member.php");
    $memAdm = new Member();
    $memAdm->activeUpd($_POST['mid'], $_POST['active']);
    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "memberActive success";
    return $reData;
}

function memberDel() {
    require_once("Member/Member.php");
    $memAdm = new Member();
    $memAdm->del($_POST['mid']);
    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "memberDel success";
    return $reData;
}

function isLogin() {
    $reData = Array();
    if(isset($_SESSION['adm'])) {
        $reData['status'] = 200;
        $reData['msg'] = "login already";
    }
    else {
        $reData['status'] = 500;
        $reData['msg'] = "not login";
    }
    return $reData;
}

function login() {
    require_once("Admin/Admin.php");
    if(!isset($_SESSION['login']['captcha']))
	throw new Exception("captcha error");
    $captcha = $_SESSION['login']['captcha'];
    if($captcha != $_POST['captcha']) {
        unset($_SESSION['login']['captcha']);
	throw new Exception("captcha error");
    }
    unset($_SESSION['login']['captcha']);
    $admin = new Admin();
    $reData = Array();
    if($admin->login($_POST['user'], $_POST['pass'])) {
        $_SESSION['adm'] = true;
        $reData['status'] = 200;
        $reData['msg'] = "login success";
    }
    else {
        $reData['status'] = 500;
        $reData['msg'] = "login failed";
    }
    return $reData;
}

function logout() {
    if(isset($_SESSION['adm']))
        unset($_SESSION['adm']);
    else
        throw new Exception("you are not login");
    $reData['status'] = 200;
    $reData['msg'] = "logout success";
    return $reData;
}

?>
