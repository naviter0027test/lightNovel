<?php
/*
 *  File Name :
 *	Article.php
 *  Describe :
 *	文章發表 、
 *	文章列表 、
 *  Start Date :
 *	2016.07.06
 *  Author :
 *	Lanker
 */

class Article {
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
        $this->table = "Article";

        //設定php mysql client 的編碼為utf8
        $this->dbAdm->sqlSet("SET NAMES 'utf8'");
        $this->dbAdm->execSQL();
    }

    public function articleAdd($article) {
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
        $insData['a_aTitle'] = $article['aTitle'];
        if(isset($article['aChapter']))
            $insData['a_chapter'] = $article['aChapter'];
        if(isset($article['aMemo']))
            $insData['a_memo'] = $article['aMemo']  ;
        $insData['a_content'] = $article['content'];
        $insData['a_crtime'] = date('Y-m-d H:i:s');

        $dbAdm->insertData($tablename, $insData);
        $dbAdm->execSQL();
    }

    public function articleDel($aid) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $conditionArr = Array();
        $conditionArr['a_id'] = $aid;
        $dbAdm->deleteData($tablename, $conditionArr);
        $dbAdm->execSQL();
    }

    public function articleUpd($article) {
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
        $updData['m_id'] = $article['mId'];    
        $updData['a_tag'] = $article['tag'];
        $updData['a_aTitle'] = $article['aTitle'];
        if(isset($article['aChapter']))
            $updData['a_chapter'] = $article['aChapter'];
        if(isset($article['aMemo']))
            $updData['a_memo'] = $article['aMemo']  ;
        $updData['a_content'] = $article['content'];
        $updData['a_crtime'] = date('Y-m-d H:i:s');

        $conditionArr = Array();
        $conditionArr['a_id'] = $article['aid'];

        $dbAdm->updateData($tablename, $updData, $conditionArr);
        $dbAdm->execSQL();
    }

    public function lastList($mid) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $column = Array();
        $column[0] = "*";

        $conditionArr = Array();
        $conditionArr['m_id'] = $mid;

        $order = Array();
        $order['col'] = "a_crtime";
        $order['order'] = "desc";

        $limit = Array();
        $limit['offset'] = 0;
        $limit['amount'] = 5;

        $dbAdm->selectData($tablename, $column, $conditionArr, $order, $limit);
        $dbAdm->execSQL();

        return $dbAdm->getAll();
    }

    public function articleList($page) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $column = Array();
        $column[0] = "*";

        $limit = Array();
        $limit['offset'] = 0;
        $limit['amount'] = 25;
        if(isset($page['nowPage']))
            $limit['offset'] = ($page['nowPage'] -1) * 25;
        if(isset($page['pageLimit']))
            $limit['amount'] = $page['pageLimit'];

        $order = Array();
        $order['col'] = "a_crtime";
        $order['order'] = "desc";

        //$dbAdm->selectData($tablename, $column, null, $order, $limit);
        $dbAdm->sqlSet("
            select case when (ass.as_finally = 0) then '?' else ass.as_finally end as as_finally, a.* 
            from `Article` a
            left join ArticleSeries ass on a.as_id = ass.as_id
            order by ". $order['col']. " ". $order['order'].
            " limit ". $limit['offset']. ", ". $limit['amount']);
        $dbAdm->execSQL();

        return $dbAdm->getAll();
    }

    public function get($aid) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $column = Array();
        $column[0] = "*";

        $conditionArr = Array();
        $conditionArr['a_id'] = $aid;

        $dbAdm->selectData($tablename, $column, $conditionArr, null, null);
        $dbAdm->execSQL();

        return $dbAdm->getAll()[0];
    }

    public function listAmount() {
        $dbAdm = $this->dbAdm;
        $tablename = $this->table;

	$columns = Array();
	$columns[0] = "count(*) amount";

	$dbAdm->selectData($tablename, $columns);
	$dbAdm->execSQL();
	return $dbAdm->getAll()[0];
    }
}
