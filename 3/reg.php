<?php
$email = $_POST['email'];
$passwd = $_POST['passwd'];
$invitecode = $_POST['invitecode'];

$mysql = new SaeMysql();
$sql = "select * from t_invitecode where flag =0 and invite_code='$invitecode'";

$rs = $mysql->getLine($sql);
if($rs){
	
  $mailSql = "select * from t_user where user_mail = '$email'";
	$m = $mysql->getLine($mailSql);
	if($m){
		echo $email."�Ѿ�ע���ˣ�";
  }else{
		$addSql = "insert into t_user (user_mail,passwd) values('$email'".",'$passwd')";
	  $mysql->runSql($addSql);
	  $updateSql = "update t_invitecode set flag = 1 where invite_code='$invitecode'";
	  //echo $updateSql;
	  $mysql->runSql($updateSql);
	  if( $mysql->errno() != 0 ){
	     die( "Error:" . $mysql->errmsg() );
	  }
		echo "��ϲ��ע��ɹ��ˣ���<a href='index.html'>��¼</a>";
	}
}else{
	echo "�����������ѱ�ʹ�ã������ԣ�";
}

$mysql->closeDB();
//echo $email."\n".$passwd."\n".$invitecode;
?>