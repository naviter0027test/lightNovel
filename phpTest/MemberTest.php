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
        $pass = md5("123456");
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
}
