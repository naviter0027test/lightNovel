<meta charset="utf-8" />
<?php
/*
 *  File Name :
 *	MemberTest.php
 *  Describe :
 *      測試以下功能
 *	會員登入 、
 *	會員修改一般資料 、
 *	會員註冊、
 *	會員修改密碼
 *  Start Date :
 *	2016.07.05
 *  Author :
 *	Lanker
 */
require_once("../srvLib/simpletest/autorun.php");

class MemberTest extends UnitTestCase {
    function testInit() {
        require_once("../Member/Member.php");
        $this->assertEqual(true, true);
    }

    function testLogin() {
        require_once("../Member/Member.php");
        $member = new Member();
        $user = "test2";
        $pass = "123456";
        try {
            $mid = $member->login($user, $pass);
            $this->assertNotEqual($mid, 0);
        }
        catch (Exception $e) {
            echo $e->getMessage();
            $this->assertTrue(false);
            echo $member->error();
        }
    }

    function testRegister() {
        require_once("../Member/Member.php");
        $member = new Member();
        $user = Array();
        $user['user'] = "test". rand(1000, 9999);
        $user['pass'] = "123456";
        $user['email'] = "t". rand(10000, 99999). "@test.com.tw";
        $member->register($user);
    }

    function testIsRegister() {
        require_once("../Member/Member.php");
        $member = new Member();
        $this->assertEqual(true, $member->isRegister("test2"));
    }

    function testUpActiveMail() {
        require_once("../Member/Member.php");
        $member = new Member();
        $user = Array();
        $user['user'] = "test". rand(1000, 9999);
        $user['pass'] = "123456";
        $user['email'] = "naviter0027test@gmail.com";
        $member->upActiveMail($user);
    }

    function testLeaveMsg() {
        require_once("../Member/Member.php");
        $member = new Member();
        $text = Array();
        $text[0] = "位人信龍：回常明和座，它什位：是我病年不生時對。";
        $text[1] = "研斷育可受嗎他的奇工的不加背生。";
        $text[2] = "只望園感少何說文錢領師以唱因費形術樣的，神象角他外一健中河數來、看媽決？";
        $text[3] = "進期工標母望經原我後時行人，過品個小清類，有哥直資了一界……基人國、樣那市到來金事公王個的件孩關故收長人急？";
        $text[4] = "思人界合代，大師頭新落萬面股藝低多日會……廣自的些由的、東落樂微";
        $text[5] = "以實亞化覺考技學氣……包英關提急算驚中朋";
        $text[6] = "出陽實：年造的，字打健自為樂但老但程安重兩從民弟不策不題上她確此嚴老安黃！";
        try {
            $para = Array();
            $para['mid'] = $member->randOneMem()['m_id'];
            $para['aid'] = 2;
            $para['message'] = $text[rand(0, count($text)-1)];
            $member->addMsg($para);
        }
        catch (Exception $e) {
            echo $e->getMessage();
            $this->assertEqual(true, false);
        }
    }
}
