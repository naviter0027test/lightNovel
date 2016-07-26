<meta charset="utf-8" />
<?php
/*
 *  File Name :
 *	MessageTest.php
 *  Describe :
 *      tdd測試
 *	留言列表 、
 *  Start Date :
 *	2016.07.25
 *  Author :
 *	Lanker
 */
require_once("../srvLib/simpletest/autorun.php");

class MemberTest extends UnitTestCase {
    function testInit() {
        require_once("../Article/Message.php");
        $this->assertEqual(true, true);
    }

    function testList() {
        require_once("../Article/Message.php");
        $msg = new Message();
        $aid = 24;
        $nowPage = 1;
        $msgList = $msg->getList($aid, $nowPage);
        $this->assertNotEqual(count($msgList), 0);
    }
}

?>
