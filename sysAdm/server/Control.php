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

function pageShow() {
    require_once("pageAdm/Page.php");
    $page = new Page();
    $p_page = $_POST['page'];

    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "page show success";
    $reData['data'] = $page->show($p_page);
    return $reData;
}

function pageEdit() {
    require_once("pageAdm/Page.php");
    $page = new Page();
    $p_page = $_POST['page'];
    $pageData = Array();
    $pageData['title'] = $_POST['p_title'];
    $pageData['content'] = $_POST['p_content'];
    $page->edit($p_page, $pageData);

    $reData = Array();
    $reData['status'] = 200;
    $reData['msg'] = "page edit success";
    return $reData;
}

?>
