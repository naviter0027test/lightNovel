<?php
require_once("server/Control.php");
header("Content-Type:text/html; charset=utf-8");
$controller = new Control();
$controller->execInstr();

?>
