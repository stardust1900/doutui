<?php
header("Content-Type: text/html; charset=utf-8");
$mysql = new SaeMysql();
if(empty($_GET['remainder'])){
	$sql = "select db_uid from sync_user where flag = 1 and (id MOD 10) = 0";
}else{
	$sql = "select db_uid from sync_user where flag = 1 and (id MOD 10) = ".$_GET['remainder'];
}
echo $sql;
$uids = $mysql->getData($sql);
 $queue = new SaeTaskQueue('doutui_queue');//´Ë´¦µÄtest¶ÓÁÐÐèÒªÔÚÔÚÏß¹ÜÀíÆ½Ì¨ÊÂÏÈ½¨ºÃ
foreach($uids as $uid){
	echo $uid['db_uid']."\n";
	  //Ìí¼ÓÈÎÎñ
 $queue->addTask("http://doutui.sinaapp.com/sync_queue.php", "db_uid=".$uid['db_uid']);
}
$mysql->closeDb();

 //½«ÈÎÎñÍÆÈë¶ÓÁÐ
 $ret = $queue->push();
 var_dump($ret);

if ($ret === false)
    var_dump($queue->errno(), $queue->errmsg());

?>