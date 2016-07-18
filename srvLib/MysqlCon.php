<?php

/*
 *  File Name :
 *	MysqlCon.php
 *  Describe :
 *	1.Mysql library implement by pdo 
 *	2.insert data can use key-value
 *	3.select data key-value can check value is string
 *	  update data key-value can check value is string
 *	  delete data key-value can check value is string
 *	4.mysql_real_escape_string import escape() method
 *  Version : 
 *	1.7
 *  Start Date :
 *	1 2015.09.24
 *	2 2016.01.13
 *	3 2016.01.20
 *	4 2016.03.04
 *  Author :
 *	Lanker
 */

class MysqlCon {
    protected $con;
    protected $sql;
    protected $res;

    public function __construct($webSite, $user, $passwd, $db) {
	$dbconStat = "mysql:host=$webSite;dbname=$db";
	$this->con = new PDO($dbconStat, $user, $passwd);
    }

    public function sqlSet($sql) {
	$this->sql = $sql;
    }

    public function echoSQL() {
	return $this->sql;
    }

    public function insertData($tableName, $data) {
        $sql = "insert into $tableName ( ";
        foreach($data as $key => $value) 
            $sql .= $key. ", ";
	$sql = substr($sql, 0, strrpos($sql, ',', -1));
	$sql .= " ) values( ";
	foreach($data as $value) {
            if(is_string($value)) 
                $value = "'". $value. "'";
	    $sql .= $value. ', ';
        }
	$sql = substr($sql, 0, strrpos($sql, ',', -1));
	$sql .= ")";

	$this->sql = $sql;
    }

    public function execSQL() {
	$res = $this->con->query($this->sql);
	$this->res = $res;
	if($res)
	    return $res;
	else
	    throw new Exception($this->errorMsg());
    }

    public function getAll() {
	return $this->res->fetchAll();
    }

    public function errorMsg() {
	return $this->con->errorInfo()[2];
    }

    public function deleteData($tableName, $conditionArr) {
	$sql = "delete from $tableName where ";
	foreach($conditionArr as $col => $data) {
            if(is_string($data))
                $sql .= "$col = '$data' && ";
            else
                $sql .= "$col = $data && ";
        }
	$sql = substr($sql, 0, strrpos($sql, '&&', -1));
	$this->sql = $sql;

    }

    public function selectData($tableName, $columns, $conditionArr = null, $orderBy = null, $limit = null) {
	$sql = "select ";
	foreach($columns as $col) {
	    $sql .= $col. ', ';
	}
	$sql = substr($sql, 0, strrpos($sql, ',', -1));
	$sql .= " from $tableName ";

	if($conditionArr != null) {
	    $sql .= "where ";
	    foreach($conditionArr as $col => $data) {
                if(is_string($data))
                    $sql .= "$col = '$data' && ";
                else
                    $sql .= "$col = $data && ";
            }
	    $sql = substr($sql, 0, strrpos($sql, '&&', -1));
	}

	if($orderBy != null)
	    $sql .= " order by ". $orderBy['col']. " ". $orderBy['order']. " ";
	if($limit != null)
	    $sql .= " limit ". $limit['offset']. ",". $limit['amount']. " ";

	$this->sql = $sql;
	//echo $sql;
    }

    public function updateData($tableName, $colDatas, $conditionArr) {
	$sql = "update $tableName set ";

	foreach($colDatas as $col => $data) {
            if(is_string($data))
                $sql .= "$col = '$data', ";
            else
                $sql .= "$col = $data, ";
        }
	$sql = substr($sql, 0, strrpos($sql, ',', -1));

	$sql .= " where ";
	foreach($conditionArr as $col => $data) {
            if(is_string($data))
                $sql .= "$col = '$data' && ";
            else
                $sql .= "$col = $data && ";
        }
	$sql = substr($sql, 0, strrpos($sql, '&&', -1));

	$this->sql = $sql;
    }

    public function escape($str) {
        return mysql_real_escape_string($str);
    }

    public function __destruct() {
	$this->con = NULL;
    }
}

?>

