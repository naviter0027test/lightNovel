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

    public function __construct() {
        date_default_timezone_set("Asia/Taipei");

        $this->dbName = "walnu10_Novel";
        $this->user = "walnu10_novel";
        $this->pass = "n,4n8kkvn";
        $this->host = "localhost";
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
}

?>
