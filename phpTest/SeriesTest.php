<meta charset="utf-8" />
<?php
/*
 *  File Name :
 *	SeriesTest.php
 *  Describe :
 *      測試以下功能
 *	系列發表 、
 *	系列列表 、
 *	系列修改 、
 *	系列刪除
 *  Start Date :
 *	2016.07.05
 *  Author :
 *	Lanker
 */
require_once("../srvLib/simpletest/autorun.php");

class SeriesTest extends UnitTestCase {
    function testInit() {
        require_once("../Article/Series.php");
        $this->assertEqual(true, true);
    }

    function testAdd() {
        require_once("../Member/Member.php");
        require_once("../Article/Series.php");
        $seriesArr = Array( "明", "楼", "蔺", "晨", "谭", "宗", "明", "凌", "远", "荣", "石" ,"黄", "志", "雄" ,"胡", "八", "一" ,"杜", "见", "锋" ,"沈", "剑", "秋" ,"李", "川", "奇" ,"何", "鸣" ,"徐", "安" ,"刘", "彻" ,"岳", "振", "声" ,"牧", "良", "逢" ,"秦", "玄", "策" ,"周", "永", "嘉" ,"陈", "近", "南" ,"刘", "华", "盛" ,"李", "天", "北" ,"徐", "世", "平" ,"董", "警", "官" ,"刘", "一", "魁" ,"马", "少", "飞" ,"刘", "凯", "强" ,"张", "红", "兵" ,"王", "开", "复", "重", "光", "葵");
        $member = new Member();
        $user = "test2";
        $pass = md5("123456");

        $series = new Series();
        try {
            $mid = $member->login($user, $pass);
            $this->assertNotEqual($mid, 0);
            $seriesName = $seriesArr[rand(0, count($seriesArr)-1)]. $seriesArr[rand(0, count($seriesArr)-1)];
            $series->serAdd($mid, $seriesName);
        }
        catch (Exception $e) {
            echo $e->getMessage();
            $this->assertTrue(false);
            echo $member->error();
        }
    }

    function testList() {
        require_once("../Article/Series.php");
        $series = new Series();
        $listPara = Array();
        $listPara['nowPage'] = 1;
        $listPara['pageLimit'] = 25;
        $seriesList = $series->serList($listPara);
        //print_r($seriesList);
        $this->assertEqual(1, $seriesList[0]['m_id']);
    }

    function testUpd() {
        require_once("../Article/Series.php");
        $seriesArr = Array( "明", "楼", "蔺", "晨", "谭", "宗", "明", "凌", "远", "荣", "石" ,"黄", "志", "雄" ,"胡", "八", "一" ,"杜", "见", "锋" ,"沈", "剑", "秋" ,"李", "川", "奇" ,"何", "鸣" ,"徐", "安" ,"刘", "彻" ,"岳", "振", "声" ,"牧", "良", "逢" ,"秦", "玄", "策" ,"周", "永", "嘉" ,"陈", "近", "南" ,"刘", "华", "盛" ,"李", "天", "北" ,"徐", "世", "平" ,"董", "警", "官" ,"刘", "一", "魁" ,"马", "少", "飞" ,"刘", "凯", "强" ,"张", "红", "兵" ,"王", "开", "复", "重", "光", "葵");
        $series = new Series();
        $listPara = Array();
        $listPara['nowPage'] = 1;
        $listPara['pageLimit'] = 25;
        $seriesList = $series->serList($listPara);
        $seriesData = $seriesList[rand(1, count($seriesList) -1)];
        $seriesName = $seriesArr[rand(0, count($seriesArr)-1)]. $seriesArr[rand(0, count($seriesArr)-1)];
        $seriesData['as_name'] = $seriesName;
        //print_r($seriesData);
        $series->serUpd($seriesData);
    }

    function testDel() {
        require_once("../Article/Series.php");
        $series = new Series();
        $listPara = Array();
        $listPara['nowPage'] = 2;
        $listPara['pageLimit'] = 25;
        $seriesList = $series->serList($listPara);
        //print_r($seriesList);
        $seriesData = $seriesList[rand(0, count($seriesList) -1)];
        //print_r($seriesData);
        $series->serDel($seriesData['as_id']);
    }

    function testGetOne() {
        require_once("../Article/Series.php");
        $series = new Series();
        $asId = 1;
        $seriesData = $series->getOne(2);
        //print_r($seriesData);
        $this->assertEqual(1, $seriesData['m_id']);
        $this->assertEqual(2, $seriesData['as_id']);
        $this->assertEqual("何院", $seriesData['as_name']);
    }
}
