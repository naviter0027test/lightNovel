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

        //針對點擊數的前置作業
        if(!isset($_SESSION['articleClicked']))
            $_SESSION['articleClicked'] = Array();

        $mustBeLogin = Array("logout", "seriesAdd", "seriesList", "seriesUpd", "seriesDel", "seriesGet", "postArticle", "myData", "mySeriesList", "myLastArticle", "articleDel", "memSrsPages", "personalImg", "personalUpd", "passReset", "addMessage", "pressPraise", "articleEdit", "articleBySid", "changeArticleChapter", "myArticleList", "delArticleFromSeries", 
        "msgReply", "msgDelReply", "msgDel",
        "storeDraft", "myDraftDel", "editDraft", "myDraftList", "findMem", "subscript", "subScriptAll", "bookmark", "bookmarkCancel", "bookmarkList",
        "subScriptList", "giftList");
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
    require_once("Article/ArticleTitle.php");
    $series = new Series();
    $articleAdm = new Article();
    $artTitleAdm = new ArticleTitle();

    $para = Array();
    $para['asid'] = $_POST['sid'];
    $para['nowPage'] = $_POST['nowPage'];
    $para['mid'] = $_SESSION['mid'];

    $data = $series->getOne($_POST['sid']);
    //$articlesList = $articleAdm->articleBySeries($para);
    $articlesList = $artTitleAdm->titleBySeries($para);
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

    if(isset($article['sendUser'])) {
        require_once("Member/Member.php");
        $memAdm = new Member();
        $mem = $memAdm->find($article['sendUser']);
        if(isset($mem['m_id']))
            $article['sendUser'] = $mem['m_id'];
        else
            throw new Exception("send user not found");
    }

    if(!$articleTitleAdm->isRepeat($article['title'], $_SESSION['mid'])) {
        $insData = Array();
        $insData['title'] = $article['title'];
        $insData['mid'] = $_SESSION['mid'];
        $insData['asid'] = (isset($article['series'])?$article['series']:0);
        $insData['lastCh'] = ($article['chapterSum'] != '?'?$article['chapterSum']:0);
        $articleTitleAdm->adds($insData);
    }
    $artTitle = $articleTitleAdm->get($article['title']);
    $articleTitleAdm->updtime($artTitle['at_id']);
    $article['atid'] = $artTitle['at_id'];

    if(isset($article['series'])) {
        $articleTitleAdm->updasid($artTitle['at_id'], $article['series']);
        unset($article['series']);
    }

    if(isset($article['chapterSum'])) {
        $articleTitleAdm->updLastCh($artTitle['at_id'], ($article['chapterSum'] != '?'?$article['chapterSum']:0));
        unset($article['chapterSum']);
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

    if(!$articleTitleAdm->isRepeat($article['title'], $_SESSION['mid'])) {
        $insData = Array();
        $insData['title'] = $article['title'];
        $insData['mid'] = $_SESSION['mid'];
        $insData['asid'] = $seriesId;
        $articleTitleAdm->adds($insData);
    }
    $artTitle = $articleTitleAdm->get($article['title']);
    $articleTitleAdm->updtime($artTitle['at_id']);
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

function findMem() {
    require_once("Member/Member.php");
    $memAdm = new Member();
    $mem = $memAdm->find($_POST['user']);
    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "findMem success";
    $reData['data'] = $mem;
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
    require_once("Article/ArticleTitle.php");
    $articleAdm = new Article();
    $artTitleAdm = new ArticleTitle();

    $article = $articleAdm->get($_POST['aid']);
    $articles = $articleAdm->articlesByArtTitle($article['at_id']);
    if(count($articles) <= 1)
        $artTitleAdm->del($article['at_id'], $_SESSION['mid']);
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
    require_once("Article/ArticleTitle.php");
    require_once("Article/SubScript.php");
    require_once("Article/Bookmark.php");
    require_once("Article/Praise.php");
    $praise = new Praise();
    $articleAdm = new Article();
    $subscriptAdm = new SubScript();
    $bookmarkAdm = new Bookmark();
    $data = $articleAdm->get($_POST['aid']);
    //if($data['as_id'] > 0)
        //$articlesList = $articleAdm->allArticleBySeries($data['as_id']);
    $articlesList = $articleAdm->articlesByArtTitle($data['at_id']);
    $isScript = $subscriptAdm->isSubscript($_SESSION['mid'], $_POST['aid']);

    $articleTitleAdm = new ArticleTitle();
    $artTitle = $articleTitleAdm->getById($data['at_id']);
    if($artTitle['as_id'] > 0)
        $isSeriesSubscript = $subscriptAdm->isSeriesSubscript($_SESSION['mid'], $artTitle['as_id']);

    $isMemberSubscript = $subscriptAdm->isMemberSubscript($_SESSION['mid'], $data['m_id']);

    $isBook = $bookmarkAdm->isBook($_SESSION['mid'], $_POST['aid']);

    $praiseList = $praise->getPraise($_SESSION['mid'], $_POST['aid']);
    $isPraise = false;
    if(count($praiseList) > 0) 
        $isPraise = true;

    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "articleGet success";
    $reData['data'] = $data;
    $reData['articles'] = $articlesList;
    $reData['isSubScript'] = $isScript;
    $reData['isSeriesSubscript'] = $isSeriesSubscript;
    $reData['isMemberSubscript'] = $isMemberSubscript;
    $reData['isPraise'] = $isPraise;
    $reData['isBook'] = $isBook;
    return $reData;
}

function storeDraft() {
    require_once("Article/MyDraft.php");
    require_once("Article/Series.php");
    $myDraftAdm = new MyDraft();
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

    $myDraftAdm->draftAdd($article);
    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "storeDraft success";
    return $reData;
}

function editDraft() {
    require_once("Article/MyDraft.php");
    require_once("Article/Series.php");
    $series = new Series();

    $myDraftAdm = new MyDraft();
    $article = Array();
    foreach($_POST as $key => $val) {
        $article[$key] = $val;
    }
    //$article['mId'] = $_SESSION['mid'];
    $article['cp1'] = implode(";", $article['cp1']);
    if(is_array($article['cp2'] ))
        $article['cp2'] = implode(";", $article['cp2']);
    if(isset($article['viceCp']))
        $article['subCp'] = $article['viceCp'];
    $article['tag'] = implode(";", $article['tag']);
    $article['alert'] = implode(";", $article['alert']);

    /*
    if(isset($article['newSeries'])) {
        $series->serAdd($_SESSION['mid'], $article['newSeries']);
        $article['series'] = $series->getLastOneId($_SESSION['mid']);
    }
     */

    $myDraftAdm->upd($article);
    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "editDraft success";
    return $reData;
}

function draftGet() {
    require_once("Article/MyDraft.php");
    $myDraftAdm = new MyDraft();

    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "draftGet success";
    $reData['data'] = $myDraftAdm->getOne($_POST['mdid']);
    return $reData;
}

function myDraftList() {
    require_once("Article/MyDraft.php");
    $myDraftAdm = new MyDraft();
    $myDraftList = $myDraftAdm->myDraftList($_SESSION['mid'], $_POST['nowPage']);
    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "myDraftList success";
    $reData['data'] = $myDraftList;
    return $reData;
}

function myDraftDel() {
    require_once("Article/MyDraft.php");
    $myDraftAdm = new MyDraft();
    $myDraftAdm->myDraftDel($_SESSION['mid'], $_POST['md_id']);
    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "myDraftDel success";
    return $reData;
}

function addMessage() {
    require_once("Member/Member.php");
    $member = new Member();
    $para = Array();
    $para['mid'] = $_SESSION['mid'];
    $para['aid'] = $_POST['aid'];
    $para['message'] = nl2br($_POST['message']);
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
        if($item['m_headImg'] == "")
            $data[$i]['headImg'] = "imgs/80x80.png";
        else if(file_exists("imgs/tmp/". $item['m_headImg']))
            $data[$i]['headImg'] = "imgs/tmp/". $item['m_headImg'];
        else
            $data[$i]['headImg'] = "imgs/80x80.png";

        //留言者是否為登入者本身
        if($item['m_id'] == $_SESSION['mid'])
            $data[$i]['isMe'] = true;
        else
            $data[$i]['isMe'] = false;
    }

    $author = $msg->getAuthor($_POST['aid']);
    if($author['m_headImg'] == "")
        $authorImg = "imgs/80x80.png";
    else if(file_exists("imgs/tmp/". $author['m_headImg']))
        $authorImg = "imgs/tmp/". $author['m_headImg'];
    else
        $authorImg = "imgs/80x80.png";
    if($author['m_id'] == $_SESSION['mid'])
        $isAuthor = true;
    else
        $isAuthor = false;

    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "msgList success";
    $reData['data'] = $data;
    $reData['msgAmount'] = $msg->listAmount($_POST['aid']);
    $reData['authorImg'] = $authorImg;
    $reData['isAuthor'] = $isAuthor;
    return $reData;
}

function msgReply() {
    require_once("Article/Message.php");
    $msg = new Message();
    $msg->reply($_POST['msid'], $_POST['replyText']);

    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "msgReply success";
    return $reData;
}

function msgDel() {
    require_once("Article/Message.php");
    $msg = new Message();
    $msg->del($_POST['msid']);

    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "msgDel success";
    return $reData;
}

function msgDelReply() {
    require_once("Article/Message.php");
    $msg = new Message();

    $author = $msg->getAuthorBymsid($_POST['msid']);
    if($author['m_id'] == $_SESSION['mid'])
        $msg->delReply($_POST['msid']);
    else
        throw new Exception("you are not author");

    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "msgDelReply success";
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

//第三階段
function subscript() {
    require_once("Article/SubScript.php");
    $ssAdm = new SubScript();

    $subScriptItem = Array();
    if(isset($_POST['mid']) && $_POST['mid'] != "")
        $subScriptItem['m_id'] = $_POST['mid'];
    else if(isset($_POST['asid']) && $_POST['asid'] != "")
        $subScriptItem['as_id'] = $_POST['asid'];
    else if(isset($_POST['aid']) && $_POST['aid'] != "")
        $subScriptItem['a_id'] = $_POST['aid'];

    $reData = Array();

    if($ssAdm->isRepeat($_SESSION['mid'], $subScriptItem)) {
        $ssAdm->cancel($_SESSION['mid'], $subScriptItem);
        $reData['status'] = 200;
        $reData['msg'] = "subscriptCancel success";
    }
    else {
        $ssAdm->subscript($_SESSION['mid'], $subScriptItem);
        $reData['status'] = 200;
        $reData['msg'] = "subscript success";
    }

    return $reData;
}

function subScriptList() {
    require_once("Article/SubScript.php");
    $ssAdm = new SubScript();

    $chooseCls = Array();
    if(isset($_POST['subCls']))
        $chooseCls = $_POST['subCls'];
    else
        $chooseCls = null;

    $data = $ssAdm->lists($_SESSION['mid'], $_POST['nowPage'], $chooseCls);
    $amount = $ssAdm->amount($_SESSION['mid'], $chooseCls);
    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "subScriptList success";
    $reData['data'] = $data;
    $reData['amount'] = $amount;
    return $reData;
}

function subScriptAll() {
    require_once("Article/SubScript.php");
    $ssAdm = new SubScript();

    $data = $ssAdm->all($_SESSION['mid'], $_POST['nowPage']);
    $amount = $ssAdm->allAmount($_SESSION['mid']);
    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "subScriptAll success";
    $reData['data'] = $data;
    $reData['amount'] = $amount;
    return $reData;
}

function subScriptDel() {
    require_once("Article/SubScript.php");
    $ssAdm = new SubScript();

    $subScriptItem = Array();
    if(isset($_POST['mid']) && $_POST['mid'] != "")
        $subScriptItem['m_id'] = $_POST['mid'];
    else if(isset($_POST['asid']) && $_POST['asid'] != "")
        $subScriptItem['as_id'] = $_POST['asid'];
    else if(isset($_POST['aid']) && $_POST['aid'] != "")
        $subScriptItem['a_id'] = $_POST['aid'];

    $ssAdm->cancel($_SESSION['mid'], $subScriptItem);

    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "subScriptDel success";
    return $reData;
}

function articleListBySubSrs() {
    require_once("Article/Article.php");
    $articleAdm = new Article();
    $para = Array();
    $para['asid'] = $_POST['asid'];
    $para['nowPage'] = $_POST['nowPage'];
    $articlesList = $articleAdm->articleBySubscriptSeries($para);

    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "articleBySid success";
    $reData['data'] = $articlesList;
    return $reData;
}

function search() {
    require_once("Article/Article.php");
    $articleAdm = new Article();
    $condition = Array();

    //要求是主、副CP合併搜尋,mainCp 有含subCp字串
    if($_POST['mainCp'] != "")
        $condition['mainCp'] = "%". str_replace(";", "%", $_POST['mainCp']). "%";
    if($_POST['nonMainCp'] != "")
        $condition['nonMainCp'] = "%". str_replace(";", "%", $_POST['nonMainCp']). "%";
    /*
    if($_POST['subCp'] != "")
        $condition['subCp'] = "%". str_replace(";", "%", $_POST['subCp']). "%";
    if($_POST['nonSubCp'] != "")
        $condition['nonSubCp'] = "%". str_replace(";", "%", $_POST['nonSubCp']). "%";
     */
    if($_POST['title'] != "")
        $condition['title'] = "%". str_replace(";", "%", $_POST['title']). "%";
    if($_POST['series'] != "")
        $condition['series'] = str_replace(";", "','", $_POST['series']);
    if($_POST['member'] != "")
        $condition['member'] = "%". str_replace(";", "%", $_POST['member']). "%";
    if(isset($_POST['level'][0]))
        $condition['level'] = implode("','", $_POST['level']);
    if(isset($_POST['alert'][0]))
        $condition['alert'] = "%". implode("%", $_POST['alert']);
    if(isset($_POST['tag'][0]))
        $condition['tag'] = "%". implode("%", $_POST['tag']);

    //print_r($condition);

    $articleList = $articleAdm->search($_POST['nowPage'], $condition);
    $searchAmount = $articleAdm->searchAmount($condition);

    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "search success";
    $reData['data'] = $articleList;
    $reData['amount'] = $searchAmount;
    return $reData;
}

function bookmark() {
    require_once("Article/Bookmark.php");
    $bookmarkAdm = new Bookmark();

    $reData = Array();
    if($bookmarkAdm->isBook($_SESSION['mid'], $_POST['bookId'])) {
        $bookmarkAdm->cancel($_SESSION['mid'], $_POST['bookId']);
        $reData['status'] = 200;
        $reData['msg'] = "bookmarkCancel success";
    }
    else {
        $bookmarkAdm->adds($_SESSION['mid'], $_POST['bookId']);
        $reData['status'] = 200;
        $reData['msg'] = "bookmark success";
    }

    return $reData;
}

function bookmarkCancel() {
    require_once("Article/Bookmark.php");
    $bookmarkAdm = new Bookmark();

    $bookmarkAdm->cancel($_SESSION['mid'], $_POST['bookId']);

    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "bookmarkCancel success";
    return $reData;
}

function bookmarkList() {
    require_once("Article/Bookmark.php");
    $bookmarkAdm = new Bookmark();

    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "bookmarkList success";
    $reData['data'] = $bookmarkAdm->lists($_SESSION['mid'], $_POST['nowPage']);
    $reData['amount'] = $bookmarkAdm->listAmount($_SESSION['mid']);
    return $reData;
}

//session 還在的情況下點擊第二次的文章不會累計點擊數
function articleClick() {
    $aid = $_POST['aid'];
    require_once("Article/Article.php");
    $articleAdm = new Article();

    //如果有點擊過，拋出此文章已點擊
    if(isset($_SESSION['articleClicked'][$aid]))
        throw new Exception("this article clicked");
    else {
        $_SESSION['articleClicked'][$aid] = true;
        $articleAdm->clicked($aid);
    }
    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "articleClick success";
    return $reData;
}

function giftList() {
    require_once("Article/Article.php");
    $articleAdm = new Article();

    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "giftList success";
    $reData['data'] = $articleAdm->myGiftList($_SESSION['mid'], $_POST['nowPage']);
    $reData['amount'] = $articleAdm->myGiftListAmount($_SESSION['mid']); 
    return $reData;
}

?>
