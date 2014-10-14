<?php
session_start();
$db_uid = $_SESSION["db_uid"];
$mysql = new SaeMysql();
$sql = "delete from  doutui_user where db_uid = '".$db_uid."'";
 $mysql->runSql($sql);
 if( $mysql->errno() != 0 )
 {
     die( "Error:" . $mysql->errmsg() );
 }
 $mysql->closeDb();
 //echo $db_uid;
 unset($_SESSION['db_uid']);
 header("Location:connect.php");
?>