<?php
$mysql = new SaeMysql();
$user_id = 101;
$time = time();
for($i=0;$i<10;$i++){
	$sql = "insert into t_invitecode (user_id,invite_code,flag) values (101,'".md5($user_id.$time.$i)."',0)";
	//echo $sql."<br/>";
	$mysql->runSql($sql);
	if( $mysql->errno() != 0 ){
		die( "Error:".$mysql->errmsg());
	}
}
    echo "<SCRIPT LANGUAGE='JavaScript'>"; 
	echo "location.href='invite.php'"; 
	echo "</SCRIPT>"; 
?>