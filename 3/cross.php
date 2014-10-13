<?php
    $user_id = $_GET['user_id'];
    $sql = "select * from t_user where user_id = $user_id";
    $mysql = new SaeMysql();
    $rs = $mysql->getLine($sql);
		if($rs['bind']==0){
      header("Location:setting.php?user_id=$user_id");
    }else{
      header("Location:page.php?user_id=$user_id");
    }
	$mysql->closeDb();
?>
