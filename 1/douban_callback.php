<?php
session_start();
require_once('sdk/DoubanOAuth.php');
require_once('config.php');
header("Content-Type: text/html; charset=utf-8");
  $douban = new DoubanOAuth(array(
    'key' => KEY,
    'secret' => SECRET,
    'redirect_url' => REDIRECT,
  ));

  $result = $douban->getAccessToken($_GET['code']);

if(isset($result['access_token'])) {
   $access_token = $result['access_token'];
   $douban_user_id = $result['douban_user_id'];
    
    $mysql = new SaeMysql();
    $sql = "select * from doutui_user where db_uid = '".$douban_user_id."'";
    $record = $mysql -> getLine($sql);
    if ($mysql->errno() != 0) {
       die("Error:" . $mysql->errmsg());
    }
     if($record){
         if($access_token != $record['db_access_token']) {
             $updateSql = "update doutui_user set db_access_token='".$access_token."' where db_uid='".$douban_user_id."'";
              $mysql->runSql($updateSql);
             
             if ($mysql->errno() != 0) {
                die("Error:" . $mysql->errmsg());
             }
         }
     }else{
        $insertSql = "insert into doutui_user (db_uid, db_access_token) values('".$douban_user_id."','".$access_token."')";
        $mysql->runSql($insertSql);
         if ($mysql->errno() != 0) {
                die("Error:" . $mysql->errmsg());
         }
     }
    $mysql->closeDb();
$_SESSION['db_uid'] = $douban_user_id;
header("Location:main.php");
}else{
  $url = $douban->getAuthorizeURL(SCOPE, STATE);
  echo '授权失败,请重试<a href="'.$url.'">返回</a>';
}
