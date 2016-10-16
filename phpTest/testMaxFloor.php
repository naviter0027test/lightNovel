<?php
        if(file_exists("../srvLib/MysqlCon.php")) 
            require_once("../srvLib/MysqlCon.php");
        else
            require_once("srvLib/MysqlCon.php");
        if(file_exists("../server/config.php"))
            require_once("../server/config.php");
        else
            require_once("server/config.php");
        $config = new Config();
        $dbAdm = new MysqlCon(
            $config->getHost(), $config->getDBUser(),
            $config->getDBPass(), $config->getDB());
        $table = "Member";
        
        //設定php mysql client 的編碼為utf8
        $dbAdm->sqlSet("SET NAMES 'utf8'");
        $dbAdm->execSQL();

        $dbAdm->sqlSet("select max(ms_floor) floor from Message where a_id = 112");
        $dbAdm->execSQL();
        $maxFloor = $dbAdm->getAll()[0]['floor'];
        print_r($maxFloor);
        print_r($maxFloor == null);
?>
