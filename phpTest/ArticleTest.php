<meta charset="utf-8" />
<?php
/*
 *  File Name :
 *	ArticleTest.php
 *  Describe :
 *      測試以下功能
 *	文章新增、文章修改、文章刪除、文章列表
 *  Start Date :
 *	2016.07.05
 *  Author :
 *	Lanker
 */
require_once("../srvLib/simpletest/autorun.php");

class ArticleTest extends UnitTestCase {
    private $articleTitle;
    private $mid;
    function testInit() {
        require_once("../Article/Article.php");
        $this->assertEqual(true, true);
    }

    function testAdd() {
        require_once("../Article/Series.php");
        require_once("../Article/Article.php");
        require_once("../Article/ArticleTitle.php");
        require_once("spanData.php");
        $series = new Series();
        $articleAdm = new Article();
        $articleTitleAdm = new ArticleTitle();
        $baseData = new BaseData();

        require_once("../Member/Member.php");
        $randMember = new Member();
        $mid = $randMember->randOneMem()['m_id'];

        try { 
            $article = Array();
            $article['title'] = $baseData->spanTitle();
            $article['articleType'] = $baseData->spanArticleType();
            $article['level'] = $baseData->spanLevel();
            $article['author'] = $baseData->spanAuthor();
            $article['cp1'] = $baseData->spanCp();
            $article['subCp'] = ""; 
            $article['series'] = $series->getRandSidForMid($mid)['as_id'];

            $article['alert'] = $baseData->spanAlert();
            $article['tag'] = $baseData->spanTag();
            $article['aTitle'] = $baseData->spanAtitle();
            $article['aChapter'] = $baseData->spanAchapter();
            $article['chapterSum'] = "?";
            $article['aMemo'] = "memo test insert";
            $article['content'] = $baseData->spanText();;
            $article['mId'] = $mid;
            //print_r($article);

            if(!$articleTitleAdm->isRepeat($article['title'], $mid)) {
                $insData = Array();
                $insData['title'] = $article['title'];
                $insData['mid'] = $mid;
                $insData['asid'] = (isset($article['series'])?$article['series']:0);
                $insData['lastCh'] = ($article['chapterSum'] != '?'?$article['chapterSum']:0);
                $articleTitleAdm->adds($insData);
            }
            $artTitle = $articleTitleAdm->get($article['title']);
            $articleTitleAdm->updtime($artTitle['at_id']);
            $article['atid'] = $artTitle['at_id'];

            $articleAdm->articleAdd($article);
            $this->articleTitle = $article['title'];
            $this->mid = $mid;
        }
        catch(Exception $e) {
            echo $e->getMessage();
            assertTrue(false);
        }
    }

    function testList() {
        require_once("../Article/Article.php");
        $articleAdm = new Article();

        require_once("../Member/Member.php");
        $randMember = new Member();
        $mid = $randMember->randOneMem()['m_id'];

        $lists = $articleAdm->lastList($mid);
        //print_r($lists);
        $article1 = $lists[0];
        $article2 = $lists[1];
        $this->assertTrue($article1['a_crtime'] > $article2['a_crtime']);
    }

    function testGet() {
        require_once("../Article/Article.php");
        require_once("../Article/ArticleTitle.php");
        $articleAdm = new Article();
        $artTitle = new ArticleTitle();
        $lists = $articleAdm->lastList($this->mid);
        $data = $articleAdm->get($lists[0]['a_id']);
        $articleTitle = $artTitle->getById($data['at_id']);
        $this->assertEqual($articleTitle['at_title'], $this->articleTitle);
    }

    function testUpd() {
        require_once("../Article/Article.php");
        require_once("spanData.php");
        $articleAdm = new Article();
        $baseData = new BaseData();
        $lists = $articleAdm->lastList($this->mid);
        $data = $articleAdm->get($lists[0]['a_id']);
        $article['articleType'] = $data['a_attr'];
        $article['level'] = $data['a_level'];
        if(isset($data['as_id']))
            $article['series'] = $data['as_id'];
        $article['author'] = $baseData->spanAuthor();
        $article['cp1'] = $data['a_mainCp'];
        if(isset($data['a_subCp']))
            $article['subCp'] = $data['a_subCp']; 
        $article['alert'] = $data['a_alert']; 
        $article['mId'] = $data['m_id'];    
        $article['tag'] = $data['a_tag'];
        if(isset($data['a_aTitle']))
            $article['aTitle'] = $data['a_aTitle'];
        if(isset($data['a_chapter']))
            $article['aChapter'] = $data['a_chapter'];
        if(isset($data['a_memo']))
            $article['aMemo'] = $data['a_memo']  ;
        $article['content'] = $data['a_content'];
        $article['aid'] = $data['a_id'];
        $articleAdm->articleUpd($article);
        $data2 = $articleAdm->get($lists[0]['a_id']);
        $this->assertNotEqual($data['a_author'], $data2['a_author']);
    }
}
