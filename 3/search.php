<?php
require("codes.php");
header("Content-Type: text/html; charset=UTF-8");
$word = $_GET['key'];
echo "  ".$word."   ";
$word_gb = iconv("UTF-8","GB2312",$word);
$word_code = get_encode_str($word_gb);
$user_id = $_GET['user_id'];

$mysql = new SaeMysql();
$dbUidSql = "select db_uid from t_user where user_id = $user_id";
$dbUid = $mysql->getLine($dbUidSql);
$table = "rec".$dbUid['db_uid'];

$sql = "select * from ".$table." where match (title_code,comment_code) against ('$word_code')";
$datas =  $mysql->getData($sql);
if( $mysql->errno() != 0 ){
		die( "Error:".$mysql->errmsg());
	}
$mysql->closeDb();
foreach($datas as $data){
	echo $data['content']."===".$data['comment']."<br/>";
}
echo "<a href='page.php?user_id=$user_id'>返回</a>";
?>