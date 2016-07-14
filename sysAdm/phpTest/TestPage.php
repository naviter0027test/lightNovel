<?php
require_once("../../srvLib/simpletest/autorun.php");

class TestPage extends UnitTestCase {
    function testConfig() {
        require_once("../../config/config.php");
        $config = new Config();

        $this->assertEqual("ManaWorld", $config->dbName);
        $this->assertEqual("manaWorld", $config->user);
        $this->assertEqual("5k8c03kd,", $config->pass);
        $this->assertEqual("localhost", $config->host);
    }

    function testPageContent() {
        require_once("../pageAdm/Page.php");
        $page = new Page();
        $pData = $page->show("test");
        echo "page data:test \n<br />";
        print_r($pData);
        $this->assertEqual("content update", $pData['p_content']);
        $this->assertEqual("update title", $pData['p_title']);
    }

    function testUpdateContent() {
        require_once("../pageAdm/Page.php");
        $page = new Page();
        $pageData = Array();
        $pageData['title'] = "update title";
        $pageData['content'] = "content update";
        $page->edit("test", $pageData);

        $pData = $page->show("test");
        $this->assertEqual("content update", $pData['p_content']);
        $this->assertEqual("update title", $pData['p_title']);
    }
}

