<?php
/*
 *  File Name :
 *	MyDraft.php
 *  Describe :
 *      草稿新增、草稿修改、草稿刪除、草稿列表
 *  Start Date :
 *	2016.08.18
 *  Author :
 *	Lanker
 */

class MyDraft {
    private $dbAdm;
    private $table;

    public function __construct() {
        if(file_exists("../srvLib/MysqlCon.php")) 
            require_once("../srvLib/MysqlCon.php");
        else
            require_once("srvLib/MysqlCon.php");
        if(file_exists("../server/config.php"))
            require_once("../server/config.php");
        else
            require_once("server/config.php");
        $this->config = new Config();
        $config = $this->config;
        $this->dbAdm = new MysqlCon(
            $config->getHost(), $config->getDBUser(),
            $config->getDBPass(), $config->getDB());
        $this->table = "MyDraft";

        //設定php mysql client 的編碼為utf8
        $this->dbAdm->sqlSet("SET NAMES 'utf8'");
        $this->dbAdm->execSQL();
    }

    public function draftAdd($article) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $insData = Array();
        $insData['a_title'] = $article['title'];
        $insData['a_attr'] = $article['articleType'];
        $insData['a_level'] = $article['level'];
        if(isset($article['series']))
            $insData['as_id'] = $article['series'];
        $insData['a_mainCp'] = $article['cp1'];
        $insData['a_mainCp2'] = $article['cp2'];
        if(isset($article['subCp']))
            $insData['a_subCp'] = $article['subCp']; 
        $insData['a_alert'] = $article['alert']; 
        $insData['m_id'] = $article['mId'];    
        $insData['a_tag'] = $article['tag'];
        if(isset($article['aTitle']))
            $insData['a_aTitle'] = $article['aTitle'];
        if(isset($article['aChapter']))
            $insData['a_chapter'] = $article['aChapter'];
        if(isset($article['aMemo']))
            $insData['a_memo'] = $article['aMemo']  ;
        $insData['a_content'] = $article['content'];
        $insData['a_crtime'] = date('Y-m-d H:i:s');

        $dbAdm->insertData($tablename, $insData);
        //echo $dbAdm->echoSQL();
        $dbAdm->execSQL();
    }
    
    public function getOne($md_id) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $column = Array();
        $column[0] = "*";

        $conditionArr = Array();
        $conditionArr['md_id'] = $md_id;

        $dbAdm->selectData($tablename, $column, $conditionArr);
        /*
        $dbAdm->sqlSet("select count(p.m_id) praiseAmount,
            case when (ss.as_finally = 0) then '?' 
            else ss.as_finally end as as_finally ,a.*
                from `Article` a 
                left join ArticleSeries ss on ss.as_id = a.as_id
                left join Praise p on p.a_id = a.a_id
            where a.a_id = ". $aid. " group by p.a_id");
         */
        $dbAdm->execSQL();

        return $dbAdm->getAll()[0];
    }

    public function upd($article) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $updData = Array();
        $updData['a_title'] = $article['title'];
        $updData['a_attr'] = $article['articleType'];
        $updData['a_level'] = $article['level'];
        if(isset($article['series']))
            $updData['as_id'] = $article['series'];
        $updData['a_mainCp'] = $article['cp1'];
        $updData['a_mainCp2'] = $article['cp2'];
        if(isset($article['subCp']))
            $updData['a_subCp'] = $article['subCp']; 
        $updData['a_alert'] = $article['alert']; 
        $updData['a_tag'] = $article['tag'];
        $updData['a_aTitle'] = $article['aTitle'];
        if(isset($article['aChapter']))
            $updData['a_chapter'] = $article['aChapter'];
        if(isset($article['aMemo']))
            $updData['a_memo'] = $article['aMemo']  ;
        $updData['a_content'] = $article['content'];
        $updData['a_crtime'] = date('Y-m-d H:i:s');

        $conditionArr = Array();
        $conditionArr['md_id'] = $article['mdid'];

        $dbAdm->updateData($tablename, $updData, $conditionArr);
        $dbAdm->execSQL();
    }

    public function myDraftList($mid, $nowPage) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $column = Array();
        $column[0] = "*";

        $conditionArr = Array();
        $conditionArr['m_id'] = $mid;

        $limit = Array();
        $limit['offset'] = 0;
        $limit['amount'] = 25;
        if(isset($nowPage))
            $limit['offset'] = ($nowPage -1) * $limit['amount'];

        $order = Array();
        $order['col'] = "a_crtime";
        $order['order'] = "desc";

        $dbAdm->selectData($tablename, $column, $conditionArr, $order, $limit);
        /*
        $dbAdm->sqlSet("
            select count(p.p_id) praiseAmount ,
            case when (ass.as_finally = 0) then '?' else ass.as_finally end as as_finally, a.* 
            from `Article` a
            left join ArticleSeries ass on a.as_id = ass.as_id
            left join Praise p on a.a_id = p.a_id group by a.a_id
            order by ". $order['col']. " ". $order['order'].
            " limit ". $limit['offset']. ", ". $limit['amount']);
         */
        $dbAdm->execSQL();

        return $dbAdm->getAll();
    }

    public function myDraftDel($mid, $mdid) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;
        $conditionArr = Array();
        $conditionArr['md_id'] = $mdid;
        $dbAdm->deleteData($tablename, $conditionArr);
        $dbAdm->execSQL();
    }
}
