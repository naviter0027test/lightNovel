<?php
/*
 *  File Name :
 *	Message.php
 *  Describe :
 *	留言列表 、
 *  Start Date :
 *	2016.07.25
 *  Author :
 *	Lanker
 */

class Message {
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
        $this->table = "Message";

        //設定php mysql client 的編碼為utf8
        $this->dbAdm->sqlSet("SET NAMES 'utf8'");
        $this->dbAdm->execSQL();
    }

    public function getList($aid, $nowPage) {
        $tablename = $this->table;
        $dbAdm = $this->dbAdm;

        $columns = Array();
        $columns[0] = "*";

        $conditionArr = Array();
        $conditionArr['a_id'] = $aid;

        $order = Array();
        $order['col'] = "ms_crtime";
        $order['order'] = "desc";

        $limit = Array();
        $limit['offset'] = ($nowPage -1) * 10;
        $limit['amount'] = 10;
        //$dbAdm->selectData($tablename, $columns, $conditionArr, $order, $limit);
        $dbAdm->sqlSet("select ms.*, m.m_user from Message ms inner join Member m on ms.m_id = m.m_id where ms.a_id = $aid order by ms_crtime desc limit ". $limit['offset']. ", ". $limit['amount']);
        $dbAdm->execSQL();
        return $dbAdm->getAll();
    }
}
?>
