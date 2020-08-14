<?php

/*
 *  File Name :
 *      Config.php
 *  Describe :
 *      設定時區與一些初始設定
 *  Start Date :
 *      2016.01.19
 *  Author :
 *      Lanker
 */
require_once(__DIR__. '/configure.php');

class Config {
    private $dbName;
    private $user;
    private $pass;
    private $host;
    private $mail;
    private $permission;

    public function __construct() {
        date_default_timezone_set("Asia/Taipei");

        $this->dbName = DB_BASE;
        $this->user = DB_USER;
        $this->pass = DB_PASS;

        $this->mailer = Array();
        $this->mailer['port'] = 25;
        //$this->mailer['user'] = "system@walnutfics.com";
        //$this->mailer['pass'] = "v$3c8eMf5";
        if($_SERVER['HTTP_HOST'] == "www.walnutfics.com") {
            $this->host = "216.97.238.71";
            $this->mailer['host'] = "test0010.axcell28.idv.tw";
        }
        else if($_SERVER['HTTP_HOST'] == "walnutfics.com") {
            $this->host = "216.97.238.71";
            $this->mailer['host'] = "test0010.axcell28.idv.tw";
        }
        else {
            $this->host = DB_HOST;
            $this->mailer['host'] = "192.168.3.16";
        }

        $permission = Array();
        $permission[0] = "passAdm.html";
        $permission[1] = "permitAdm.html";
        $permission[2] = "memberList.html#list/1";
        $permission[3] = "articleList.html#list/1";
        $permission[4] = "cpPanel.html#edit";
        $permission[5] = "synonyms.html#list/1";
        $this->permission = $permission;
    }

    public function getDB() {
        return $this->dbName;
    }

    public function getDBUser() {
        return $this->user;
    }

    public function getDBPass() {
        return $this->pass;
    }

    public function getHost() {
        return $this->host;
    }

    public function getMailHost() {
        return $this->mailer['host'];
    }

    public function getMailPort() {
        return $this->mailer['port'];
    }
}

?>
