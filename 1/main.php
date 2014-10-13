<?php
session_start();
require_once('config.php');
require_once('sdk/DoubanOAuth.php');
include_once('saetv2.ex.class.php' );
header("Content-Type: text/html; charset=utf-8");
if(!isset($_SESSION['db_uid'])){
    header("Location:connect.php");
}else{

$db_uid = $_SESSION['db_uid'];
$mysql = new SaeMysql();
$sql = "select * from doutui_user where db_uid = '".$db_uid."'";
$record = $mysql->getLine($sql);
    if ($mysql->errno() != 0) {
                die("Error:" . $mysql->errmsg());
    }

  if($record){
      $douban = new DoubanOAuth(array(
        'key' => KEY,
        'secret' => SECRET,
        'redirect_url' => REDIRECT,
         'access_token' =>$record['db_access_token'],
      ));
      $people = $douban->get('v2/user/~me');
      echo "欢迎你 "."<img src='".$people['avatar']."'>".$people['name']."! <a href='logout.php'>退出</a><br/> <br/>";
      
    if($record["flag"]==1){
			echo '已同步豆瓣广播到新浪微博  <a href="cancel.php">取消</a>';
		}else if($record["flag"]==0){
			$o = new SaeTOAuthV2(WB_AKEY,WB_SKEY);
      $aurl = $o->getAuthorizeURL(WB_CALLBACK_URL);
			
			echo '同步豆瓣广播到新浪微博<a href="'.$aurl.'"><img src="images/sync_sina.png" align="middle" style="border:none"/></a>';
		}
      
  }else{
    header("Location:connect.php");
  }
  $mysql->closeDb();
}
?>