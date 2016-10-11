<?php
session_start();
require_once("../Member/Member.php");
$member = new Member();
$_SESSION['mid'] = $member->getOne($_POST['member'])['m_id'];
print_r($_SESSION);
?>
