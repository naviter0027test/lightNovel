<meta charset="utf-8" />
<?php
/*
 *  File Name :
 *	AdminTest.php
 *  Describe :
 *      測試以下功能
 *      管理員登入登出
 *      網站開啟或關閉
 *  Start Date :
 *	2016.07.05
 *  Author :
 *	Lanker
 */
require_once("../../srvLib/simpletest/autorun.php");

class AdminTest extends UnitTestCase {
    function testInit() {
        $this->assertEqual(true, true);
    }

    function testLogin() {
        require_once("../Admin/Admin.php");
        $adm = new Admin();

        $user = "admin";
        $pass = "123456";

        $this->assertEqual(true, $adm->login($user, $pass));
    }

    function testIsOpen() {
        require_once("../Admin/Admin.php");
        $adm = new Admin();

        $this->assertEqual(true, $adm->isOpen());
    }

    function testClose() {
        require_once("../Admin/Admin.php");
        //$adm = new Admin();
        //$adm->close();
    }

    function testOpen() {
        require_once("../Admin/Admin.php");
        //$adm = new Admin();
        //$adm->open();
    }
}
