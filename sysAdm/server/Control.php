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
        $mustBeLogin = Array("articleList", "articleDel", "memberList", "memberActive", "memberDel", "logout", "passEdit", "cpGet", "synonymsAdd", "synonymsDel", "synonymsList", "articleShowUpd");
	try {
	    if(!isset($this->instr))
		throw new Exception("instr not defined");

	    $instr = $this->instr;

            //檢查管理員有無登入，才可使用
            if(in_array($instr, $mustBeLogin) && !isset($_SESSION['adm'])) {
                throw new Exception("admin not login");
            }

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
    $reData['amount'] = $article->amount();
    return $reData;
}

function articleDel() {
    require_once("Article/Article.php");
    require_once("Article/ArticleTitle.php");
    $articleAdm = new Article();
    $artTitleAdm = new ArticleTitle();

    $article = $articleAdm->get($_POST['aid']);
    $articles = $articleAdm->articlesByArtTitle($article['at_id']);
    if(count($articles) <= 1)
        $artTitleAdm->del($article['at_id']);
    $articleAdm->del($_POST['aid']);
    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "articleDel success";
    return $reData;
}

function memberList() {
    require_once("Member/Member.php");
    $memAdm = new Member();
    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "memberList success";
    $reData['data'] = $memAdm->memberList($_POST['nowPage']);
    $reData['amount'] = $memAdm->amount();
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

function passEdit() {
    require_once("Admin/Admin.php");
    $admin = new Admin();

    $admin->passUpd($_POST['oldPass'], $_POST['newPass']);

    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "passEdit success";
    return $reData;
}

function cpGet() {
    require_once("Admin/Admin.php");
    $admin = new Admin();

    $cpData = $admin->cpGet();

    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "cpGet success";
    $reData['cpData'] = $cpData;
    return $reData;
}

function cpUpdate() {
    require_once("Admin/Admin.php");
    $admin = new Admin();

    $cpData = $admin->cpUpd($_POST['cp1'], $_POST['cp2']);
    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "cpUpdate success";
    return $reData;
}

function synonymsAdd() {
    require_once("Article/Synonyms.php");
    $synAdm = new Synonyms();
    $synAdm->adds($_POST['key'], $_POST['value']);

    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "synonymsAdd success";
    return $reData;
}

function synonymsDel() {
    require_once("Article/Synonyms.php");
    $synAdm = new Synonyms();
    $synAdm->del($_POST['syid']);

    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "synonymsDel success";
    return $reData;
}

function synonymsList() {
    require_once("Article/Synonyms.php");
    $synAdm = new Synonyms();
    $lists = $synAdm->lists($_POST['nowPage']);

    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "synonymsList success";
    $reData['data'] = $lists;
    return $reData;
}

function articleShowUpd() {
    require_once("Article/Article.php");
    $articleAdm = new Article();

    $article = $articleAdm->showUpd($_POST['aid'], $_POST['active']);

    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "articleShowUpd success";
    return $reData;
}

function permitList() {
    require_once("Admin/Admin.php");
    $admOper = new Admin();
    $data = $admOper->lists();

    //權限數字表示對應的連結
    $permitHash = Array(
        "0" => "passAdm.html",
        "1" => "permitAdm.html#list", 
        "2" => "memberList.html#list/1",
        "3" => "articleList.html#list/1", 
        "4" => "cpPanel.html#edit", 
        "5" => "synonyms.html#list/1"
    );

    //將權限替換為網頁連結
    foreach($data as $idx => $item) {
        foreach($permitHash as $num => $link) {
            $item['adm_permission'] = str_replace($num, $link, $item['adm_permission']);
        }
        $data[$idx] = $item;
        unset($data[$idx]["3"]);
    }

    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "permitList success";
    $reData['data'] = $data;
    return $reData;
}

function permitGet() {
    require_once("Admin/Admin.php");
    $admOper = new Admin();

    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "permitGet success";
    $reData['data'] = $admOper->get($_POST['admid']);
    return $reData;
}

?>
