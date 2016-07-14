<?php 
/*
 *  File Name :
 *      Page.php
 *  Describe :
 *      頁面的編輯
 *  Start Date :
 *      2016.04.19
 *  Author :
 *      Lanker
 */
class Page {

    private $dbAdm;
    private $config;

    public function __construct() {
        if(file_exists("../../srvLib/MysqlCon.php"))
            require_once("../../srvLib/MysqlCon.php");
        else
            require_once("../srvLib/MysqlCon.php");
        if(file_exists("../../config/config.php"))
            require_once("../../config/config.php");
        else
            require_once("../config/config.php");
        $this->config = new Config();
        $config = $this->config;
        $this->dbAdm = new MysqlCon(
            $config->host, $config->user,
            $config->pass, $config->dbName);
    }

    public function show($p_page) {
        $dbAdm = $this->dbAdm;
        $table = "Page";
        $columns = Array();
        $columns[0] = "*";

        $conditionArr = Array();
        $conditionArr['p_page'] = $p_page;
        $dbAdm->selectData($table, $columns, $conditionArr);
        $dbAdm->execSQL();
        $data = $dbAdm->getAll();
        if(count($data) < 1) {
            throw new Exception("no data");
        }
        return $data[0];
    }

    public function edit($p_page, $pageData) {
        $dbAdm = $this->dbAdm;
        $table = "Page";
        $upData = Array();
        $upData['p_title'] = $pageData['title'];
        $upData['p_content'] = $pageData['content'];

        $conditionArr = Array();
        $conditionArr['p_page'] = $p_page;

        $dbAdm->updateData($table, $upData, $conditionArr);
        $dbAdm->execSQL();
    }
}
