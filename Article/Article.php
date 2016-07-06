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
        $insData['as_id'] = $article['series'];
        $insData['a_mainCp'] = $article['cp1'];
        $insData['a_mainCp2'] = $article['cp2'];
        $insData['a_subCp'] = $article['subCp']; 
        $insData['a_alert'] = $article['alert']; 
        $insData['m_id'] = $article['mId'];    
        $insData['a_tag'] = $article['tag'];
        $insData['a_aTitle'] = $article['aTitle'];
        $insData['a_chapter'] = $article['aChapter'];
        $insData['a_memo'] = $article['aMemo']  ;
        $insData['a_content'] = $article['content'];

        $dbAdm->insertData($tablename, $insData);
        $dbAdm->execSQL();
    }
}
