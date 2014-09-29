<?php
session_start();
$db_uid = $_SESSION["db_uid"];
$mysql = new SaeMysql();
$sql = "update sync_user set flag = 2 where db_uid = '".$db_uid."'";
 $mysql->runSql($sql);
 if( $mysql->errno() != 0 )
 {
     die( "Error:" . $mysql->errmsg() );
 }
 $mysql->closeDb();
 //echo $db_uid;
 header("Location:main.php?douban_uid=".$db_uid);
?>