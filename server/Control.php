<?php
/* 
 * file name : 
 *	Control.php
 * description :
 *	負責控制網址與POST的操作
 * start date :
 *	2016/07/07
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
        $mustBeLogin = Array("logout", "seriesAdd", "seriesList", "seriesUpd", "seriesDel", "seriesGet", "postArticle", "myData", "mySeriesList", "myLastArticle", "articleDel", "memSrsPages", "personalImg", "personalUpd", "passReset", "addMessage", "pressPraise", "articleEdit", "articleBySid", "changeArticleChapter", "myArticleList", "delArticleFromSeries");
	try {
	    if(!function_exists($this->instr))
		throw new Exception("instr not defined");
	    $instr = $this->instr;

            $logFile = fopen("log.txt", "a") or die("Unable to open file!");
            $txt = "[". date("Y-m-d H:i:s"). "]:". get_client_ip(). ":$instr\n";
            fwrite($logFile, $txt);
            fclose($logFile);

            if(in_array($instr, $mustBeLogin) && !isset($_SESSION['mid'])) {
                require_once("Admin/Admin.php");
                $adm = new Admin();
                if(!$adm->isOpen())
                    throw new Exception("web not open");
                throw new Exception("member not login");
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

function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

function test() {
    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "test success";
    return $reData;
}

function isLogin() {
    $reData = Array();
    if(isset($_SESSION['mid'])) {
        $reData['status'] = 200;
        $reData['msg'] = "login already";
    }
    else {
        $reData['status'] = 500;
        $reData['msg'] = "not login";
    }
    return $reData;
}

function register() {
    require_once("Member/Member.php");
    if(!isset($_SESSION['register']['captcha']))
	throw new Exception("captcha error");
    $captcha = $_SESSION['register']['captcha'];
    if($captcha != $_POST['captcha']) {
        unset($_SESSION['register']['captcha']);
	throw new Exception("captcha error");
    }
    unset($_SESSION['register']['captcha']);
    $member = new Member();
    $user = Array();
    $user['user'] = $_POST['user'];
    $user['pass'] = $_POST['pass'];
    $user['email'] = $_POST['email'];
    $member->register($user);
    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "register success";
    return $reData;
}

function upActive() {
    require_once("Member/Member.php");
    $member = new Member();
    $user = $_POST['user'];
    $activeCode = $_POST['email'];
    $member->authenticate($user, $activeCode);
    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "upActive success";
    return $reData;
}

function login() {
    require_once("Member/Member.php");
    if(!isset($_SESSION['login']['captcha']))
	throw new Exception("captcha error");
    $captcha = $_SESSION['login']['captcha'];
    if($captcha != $_POST['captcha']) {
        unset($_SESSION['login']['captcha']);
	throw new Exception("captcha error");
    }
    unset($_SESSION['login']['captcha']);
    $member = new Member();
    $_SESSION['mid'] = $member->login($_POST['user'], $_POST['pass']);
    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "login success";
    return $reData;
}

function logout() {
    if(isset($_SESSION['mid']))
        unset($_SESSION['mid']);
    else
        throw new Exception("you are not login");
    $reData['status'] = 200;
    $reData['msg'] = "logout success";
    return $reData;
}

function captchaLogin() {
    require_once("srvLib/Captcha.php");
    $_SESSION['login']['captcha'] = rand(1000, 9999);
    $captcha = new Captcha($_SESSION['login']['captcha']);
}

function captchaRegister() {
    require_once("srvLib/Captcha.php");
    $_SESSION['register']['captcha'] = rand(1000, 9999);
    $captcha = new Captcha($_SESSION['register']['captcha']);
}

function captchaForget() {
    require_once("srvLib/Captcha.php");
    $_SESSION['forget']['captcha'] = rand(1000, 9999);
    $captcha = new Captcha($_SESSION['forget']['captcha']);
}

function forgetPass() {
    require_once("Member/Member.php");
    if(!isset($_SESSION['forget']['captcha']))
	throw new Exception("captcha error");
    $captcha = $_SESSION['forget']['captcha'];
    if($captcha != $_POST['captcha']) {
        unset($_SESSION['forget']['captcha']);
	throw new Exception("captcha error");
    }
    $member = new Member();
    $member->forget($_POST['user']);
    unset($_SESSION['forget']['captcha']);
    $reData['status'] = 200;
    $reData['msg'] = "forgetPass success";
    return $reData;
}

function seriesAdd() {
    require_once("Article/Series.php");
    $series = new Series();
    $series->serAdd($_SESSION['mid'], $_POST['seriesName']);
    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "series add success";
    return $reData;
}

function seriesList() {
    require_once("Article/Series.php");
    $series = new Series();
    $mid = $_POST['mId'];
    $serieses = $series->serList($_POST, $mid);
    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "series list success";
    $reData['data'] = $serieses;
    return $reData;
}

function seriesUpd() {
    require_once("Article/Series.php");
    $data = Array();
    $series = new Series();
    $data['as_name'] = $_POST['seriesName'];
    $data['as_finally'] = $_POST['finallyCh'];
    $data['as_id'] = $_POST['asId'];
    $series->serUpd($data);

    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "series update success";
    return $reData;
}

function seriesDel() {
    require_once("Article/Series.php");
    $series = new Series();
    $series->serDel($_POST['seriesId']);
    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "series delete success";
    return $reData;
}

function seriesGet() {
    require_once("Article/Series.php");
    require_once("Article/Article.php");
    $series = new Series();
    $articleAdm = new Article();
    $para = Array();
    $para['asid'] = $_POST['sid'];
    $para['nowPage'] = $_POST['nowPage'];
    $para['mid'] = $_SESSION['mid'];

    $data = $series->getOne($_POST['sid']);
    $articlesList = $articleAdm->articleBySeries($para);
    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "seriesGet success";
    $reData['data'] = $data;
    $reData['articles'] = $articlesList;
    $reData['articleAmount'] = $articleAdm->articleAmountBySeries($_POST['sid']);

    return $reData;
}

//將文章從指定系列中惕除，不是刪除文章
function delArticleFromSeries() {
    require_once("Article/Article.php");
    $articleAdm = new Article();
    $articleAdm->deleteFromSeries($_POST['aid']);
    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "delArticleFromSeries success";
    return $reData;
}

function postArticle() {
    //require_once("Member/Member.php");
    require_once("Article/Series.php");
    require_once("Article/Article.php");
    require_once("Article/ArticleTitle.php");
    $series = new Series();
    $articleTitleAdm = new ArticleTitle();

    $articleAdm = new Article();
    $article = Array();
    foreach($_POST as $key => $val) {
        $article[$key] = $val;
    }
    $article['mId'] = $_SESSION['mid'];
    $article['cp1'] = implode(";", $article['cp1']);
    if(is_array($article['cp2'] ))
        $article['cp2'] = implode(";", $article['cp2']);
    if(isset($article['viceCp']))
        $article['subCp'] = $article['viceCp'];
    $article['tag'] = implode(";", $article['tag']);
    $article['alert'] = implode(";", $article['alert']);

    if(isset($article['newSeries'])) {
        $series->serAdd($_SESSION['mid'], $article['newSeries']);
        $article['series'] = $series->getLastOneId($_SESSION['mid']);
    }

    if(!$articleTitleAdm->isRepeat($article['title'])) {
        $insData = Array();
        $insData['title'] = $article['title'];
        $insData['mid'] = $_SESSION['mid'];
        $insData['asid'] = $seriesId;
        $articleTitleAdm->adds($insData);
    }
    $artTitle = $articleTitleAdm->get($article['title']);
    $article['atid'] = $artTitle['at_id'];

    if(isset($article['series'])) {
        $articleTitleAdm->updasid($artTitle['at_id'], $article['series']);
        unset($article['series']);
    }

    $articleAdm->articleAdd($article);
    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "post article success";
    return $reData;
}

function articleEdit() {
    require_once("Article/Series.php");
    require_once("Article/Article.php");
    require_once("Article/ArticleTitle.php");
    $series = new Series();
    $articleTitleAdm = new ArticleTitle();
    $articleAdm = new Article();

    $article = Array();
    foreach($_POST as $key => $val) {
        $article[$key] = $val;
    }
    $article['mId'] = $_SESSION['mid'];
    $article['cp1'] = implode(";", $article['cp1']);
    if(is_array($article['cp2'] ))
        $article['cp2'] = implode(";", $article['cp2']);
    if(isset($article['viceCp']))
        $article['subCp'] = $article['viceCp'];
    $article['tag'] = implode(";", $article['tag']);
    $article['alert'] = implode(";", $article['alert']);

    if(isset($article['newSeries'])) {
        $series->serAdd($_SESSION['mid'], $article['newSeries']);
        $article['series'] = $series->getLastOneId($_SESSION['mid']);
    }

    if(!$articleTitleAdm->isRepeat($article['title'])) {
        $insData = Array();
        $insData['title'] = $article['title'];
        $insData['mid'] = $_SESSION['mid'];
        $insData['asid'] = $seriesId;
        $articleTitleAdm->adds($insData);
    }
    $artTitle = $articleTitleAdm->get($article['title']);
    $article['atid'] = $artTitle['at_id'];

    if(isset($article['series'])) {
        $articleTitleAdm->updasid($artTitle['at_id'], $article['series']);
        unset($article['series']);
    }

    $articleAdm->articleUpd($article);
    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "articleEdit success";
    return $reData;
}

function myData() {
    require_once("Member/Member.php");
    $member = new Member();
    $myData = $member->getOneById($_SESSION['mid']);

    if($myData['m_headImg'] != "")
        $myData['headImg'] = "imgs/tmp/". $myData['m_headImg'];
    else
        $myData['headImg'] = "imgs/80x80.png";

    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "myData success";
    $reData['data'] = $myData;
    return $reData;
}

function mySeriesList() {
    require_once("Article/Series.php");
    $series = new Series();

    $para['nowPage'] = $_POST['nowPage'];
    $para['pageLimit'] = $_POST['pageLimit'];
    $Lists = $series->serList($para, $_SESSION['mid']);
    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "mySeriesList success";
    $reData['data'] = $Lists;
    return $reData;
}

function myLastArticle() {
    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "myLastArticle success";
    //$reData['data'] = $Lists;
    return $reData;
}

function memSrsPages() {
    require_once("Article/Series.php");
    $series = new Series();

    $amount = $series->amount($_SESSION['mid']);

    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "memSrsPages success";
    $reData['amount'] = $amount['amount'];
    return $reData;
}

function personalUpd() {
    require_once("Member/Member.php");
    $member = new Member();
    $colData = Array();
    $colData['m_email'] = $_POST['email'];
    $member->dataUpdate($colData, $_SESSION['mid']);

    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "personalUpd success";
    return $reData;
}

function personalImg() {
    require_once("upload/Upload.php");
    require_once("Member/Member.php");
    $member = new Member();
    $upfile = new Upload();

    $myData = $member->getOneById($_SESSION['mid']);
    $upResult = $upfile->uploadFinish($myData['m_user']);
    $colData = Array();
    $colData['m_headImg'] = $upResult[0]['newName'];
    $member->dataUpdate($colData, $_SESSION['mid']);

    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "personalImg success";
    $reData['info'] = $upResult;
    return $reData;
}

function passReset() {
    require_once("Member/Member.php");
    $member = new Member();
    $pass = $member->getOnePassById($_SESSION['mid']);
    if($pass == md5($_POST['oldPass'])) {
        $colData = Array();
        $colData['m_pass'] = md5($_POST['newPass']);
        $member->dataUpdate($colData, $_SESSION['mid']);
    }
    else
        throw new Exception("old pass error");

    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "passReset success";
    return $reData;
}

function memArticleList() {
    require_once("Article/Article.php");
    $articleAdm = new Article();
    $articleList = $articleAdm->lastList($_SESSION['mid']);

    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "memArticleList success";
    $reData['data'] = $articleList;
    return $reData;
}

function articleDel() {
    require_once("Article/Article.php");
    $articleAdm = new Article();
    $articleAdm->articleDel($_POST['aid']);

    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "articleDel success";
    return $reData;
}

function articleList() {
    require_once("Article/Article.php");
    $limit = Array();
    $limit['nowPage'] = $_POST['nowPage'];
    if(isset($_POST['pageLimit']))
        $limit['pageLimit'] = $_POST['pageLimit'];
    $articleAdm = new Article();
    $Lists = $articleAdm->articleList($limit);

    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "articleList success";
    $reData['data'] = $Lists;
    $reData['amount'] = $articleAdm->listAmount()['amount'];
    return $reData;
}

function myArticleList() {
    require_once("Article/Article.php");
    $articleAdm = new Article();
    $articleList = $articleAdm->myArticles($_SESSION['mid'], $_POST['nowPage']);
    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "myArticleList success";
    $reData['data'] = $articleList;
    $reData['amount'] = $articleAdm->articleAmountByMem($_SESSION['mid']);
    return $reData;
}

function articleGet() {
    require_once("Article/Article.php");
    $articleAdm = new Article();
    $data = $articleAdm->get($_POST['aid']);
    //if($data['as_id'] > 0)
        //$articlesList = $articleAdm->allArticleBySeries($data['as_id']);
    $articlesList = $articleAdm->articlesByArtTitle($data['at_id']);

    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "articleGet success";
    $reData['data'] = $data;
    $reData['articles'] = $articlesList;
    return $reData;
}

function addMessage() {
    require_once("Member/Member.php");
    $member = new Member();
    $para = Array();
    $para['mid'] = $_SESSION['mid'];
    $para['aid'] = $_POST['aid'];
    $para['message'] = $_POST['message'];
    $member->addMsg($para);

    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "addMessage success";
    return $reData;
}

function msgList() {
    require_once("Article/Message.php");
    $msg = new Message();
    $data = $msg->getList($_POST['aid'], $_POST['nowPage']);
    foreach($data as $i => $item) {
        if(file_exists("imgs/tmp/". $item['m_headImg']))
            $data[$i]['headImg'] = "imgs/tmp/". $item['m_headImg'];
        else
            $data[$i]['headImg'] = "imgs/80x80.png";
    }

    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "msgList success";
    $reData['data'] = $data;
    return $reData;
}

function msgMyList() {
    require_once("Article/Message.php");
    require_once("Article/Article.php");
    $msg = new Message();
    $articleAdm = new Article();
    $articleList = $articleAdm->myAllList($_SESSION['mid']);
    $aids = Array();
    foreach($articleList as $article) {
        $aids[] = $article['a_id'];
    }
    $data = $msg->myList($aids, $_POST['nowPage'], $_SESSION['mid']);

    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "msgList success";
    $reData['data'] = $data;
    $reData['amount'] = $msg->myListAmount($aids, $_SESSION['mid']);
    return $reData;
}

function pressPraise() {
    require_once("Article/Praise.php");
    $praise = new Praise();
    $praiseList = $praise->getPraise($_SESSION['mid'], $_POST['aid']);

    $reData = Array();
    if(count($praiseList) < 1) {
        $praise->addPraise($_SESSION['mid'], $_POST['aid']);
        $reData['status'] = 200;
        $msg = "pressPraise success";
    }
    else {
        $reData['status'] = 500;
        $msg = "press praise repeat";
    }

    $reData['msg'] = $msg;
    return $reData;
}

function articleBySid() {
    require_once("Article/Article.php");
    $articleAdm = new Article();
    $para = Array();
    $para['asid'] = $_POST['seriesId'];
    $para['nowPage'] = $_POST['nowPage'];
    $para['mid'] = $_SESSION['mid'];
    $articlesList = $articleAdm->articleBySeries($para);
    //print_r($articlesList);

    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "articleBySid success";
    $reData['data'] = $articlesList;
    return $reData;
}

function allArticleBySid() {
    require_once("Article/Article.php");
    $articleAdm = new Article();
    $para = Array();
    $articlesList = $articleAdm->allArticleBySeries($_POST['seriesId']);
    //print_r($articlesList);

    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "articleBySid success";
    $reData['data'] = $articlesList;
    return $reData;
}

function articleAmountBySid() {
    require_once("Article/Article.php");
    $articleAdm = new Article();
    $articlesList = $articleAdm->articleBySeries($_POST['seriesId']);

    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "articleAmountBySid success";
    return $reData;
}

function changeArticleChapter() {
    require_once("Article/Article.php");
    $articleAdm = new Article();
    $articleAdm->changeCh($_POST['aid'], $_POST['chapter']);

    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "changeArticleChapter success";
    return $reData;
}

?>
