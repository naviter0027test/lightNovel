<?php
echo strrpos($_SERVER['SCRIPT_NAME'], '/', -1);
echo "<br />";
echo $_SERVER['SCRIPT_NAME'];
echo "<br />";
echo substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], '/', -1));
?>
