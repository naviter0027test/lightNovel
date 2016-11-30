<meta charset="utf-8" />
<?php
/*
 *  File Name :
 *	ArticleTitleTest.php
 *  Describe :
 *      測試以下功能
 *	文章標題、標題取得、系列底下的所有標題
 *	修改為最新時間、修改所屬系列
 *	修改最後一章章數、刪除標題
 *  Start Date :
 *	2016.11.25
 *  Author :
 *	Lanker
 */
require_once("../srvLib/simpletest/autorun.php");

class ArticleTitleTest extends UnitTestCase {
    public function testGetById() {
        require_once("../Article/ArticleTitle.php");
        $articleTitleAdm = new ArticleTitle();
        $idArr = Array(2, 3, 4, 5, 6, 8, 9, 10, 13, 14, 15, 16, 17, 18, 19, 20);
        $id = $idArr[rand(0, count($idArr)-1)];
        $artitle = $articleTitleAdm->getById($id);
        $this->assertTrue($artitle['at_title'] != "");
    }

    public function testAdds() {
        require_once("../Article/ArticleTitle.php");
        require_once("spanData.php");
        $articleTitleAdm = new ArticleTitle();
        $baseData = new BaseData();

        try {
            $artitle = Array();
            $artitle['title'] = $baseData->spanTitle();
            $artitle['mid'] = 0;
            $artitle['asid'] = 0;
            $artitle['lastCh'] = 0;
            //print_r($artitle);
            $articleTitleAdm->adds($artitle);
            $this->assertTrue(true);
        }
        catch (Exception $e) {
            echo $e->getMessage();
            $this->assertTrue(false);
        }
    }

    public function testDelOnlyTitle() {
        require_once("../Article/ArticleTitle.php");
        $articleTitleAdm = new ArticleTitle();
        try {
            $articleTitleAdm->delOnlyTitle();
        }
        catch (Exception $e) {
            echo $e->getMessage();
            $this->assertTrue(false);
        }
    }

    public function testIsRepeat() {
        require_once("../Article/ArticleTitle.php");
        $articleTitleAdm = new ArticleTitle();
        $idArr = Array(2, 3, 4, 5, 6, 8, 9, 10, 13, 14, 15, 16, 17, 18, 19, 20);
        $id = $idArr[rand(0, count($idArr)-1)];
        $artitle = $articleTitleAdm->getById($id);
        $this->assertEqual(true, $articleTitleAdm->isRepeat($artitle['at_title'], $artitle['m_id']));
        $this->assertEqual(false, $articleTitleAdm->isRepeat($artitle['at_title']. "123", $artitle['m_id']));
    }

    public function testGetByTitle() {
        require_once("../Article/ArticleTitle.php");
        $articleTitleAdm = new ArticleTitle();
        $idArr = Array(2, 3, 4, 5, 6, 8, 9, 10, 13, 14, 15, 16, 17, 18, 19, 20);
        $id = $idArr[rand(0, count($idArr)-1)];
        $artitle = $articleTitleAdm->getById($id);
        $artitle2 = $articleTitleAdm->get($artitle['at_title']);
        $this->assertEqual($artitle['at_title'], $artitle2['at_title']);
    }
}
