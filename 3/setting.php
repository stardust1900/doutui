<?php
    header("Content-Type: text/html; charset=UTF-8");
    $user_id = $_GET['user_id'];
		$sql = "select * from t_user where user_id = $user_id";
    $mysql = new SaeMysql();
    $rs = $mysql->getLine($sql);
    if($rs['bind']==0){
      echo  "<a href='douban_connect.php'><img style='border:none' src='douban_button.gif' align='middle'/></a>";
    }else if($rs['bind']==1){
			echo "已经绑定豆瓣帐号！";
			echo "<br/>";
			echo "<a href='page.php?user_id=$user_id'>去首页看一下</a>";
    }
?>