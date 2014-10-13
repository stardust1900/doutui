<?php
header("Content-Type: text/html; charset=UTF-8");
$email = $_POST['email'];
$passwd = $_POST['passwd'];
$mysql = new SaeMysql();
$sql = "select * from t_user where user_mail = '$email'";
$rs = $mysql->getLine($sql);
if($rs){
	if(strcmp($passwd,$rs['passwd'])==0){
		 session_start();
		 $_SESSION["user_id"] = $rs['user_id'];
		 header("Location:cross.php?user_id=".$rs['user_id']);
	}else{
		echo "您输入的邮箱或者密码不对！<a href='index.html'>返回</a>";
	}
}else{
	echo "您输入的邮箱或者密码不对！<a href='index.html'>返回</a>";
}
$mysql->closeDb();
?>