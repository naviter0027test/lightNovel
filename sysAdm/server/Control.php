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

function articleList() {
    require_once("Article/Article.php");
    $article = new Article();
    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "articleList success";
    $reData['data'] = $article->articleList($_POST['nowPage']);
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

?>
