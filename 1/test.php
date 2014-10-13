<?php
require_once('saet.ex.class.php');

$mysql = new SaeMysql();
$sql = "select * from sync_user  limit 10";
$record = $mysql->getData($sql);
var_dump($record);

if ($mysql->errno() != 0) {
     die("Error:" . $mysql->errmsg());
}
$mysql->closeDb();
?>