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

class Config {
    private $dbName;
    private $user;
    private $pass;
    private $host;
    private $mail;

    public function __construct() {
        date_default_timezone_set("Asia/Taipei");

        $this->dbName = "walnu10_Novel";
        $this->user = "walnu10_novel";
        $this->pass = "n,4n8kkvn";

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
            $this->host = "localhost";
            $this->mailer['host'] = "192.168.3.16";
        }
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
