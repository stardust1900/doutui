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
 $queue = new SaeTaskQueue('doutui_queue');//此处的test队列需要在在线管理平台事先建好
foreach($uids as $uid){
	echo $uid['db_uid']."\n";
	  //添加任务
 $queue->addTask("http://doutui.sinaapp.com/sync_queue.php", "db_uid=".$uid['db_uid']);
}
$mysql->closeDb();

 //将任务推入队列
 $ret = $queue->push();
 var_dump($ret);

if ($ret === false)
    var_dump($queue->errno(), $queue->errmsg());

?>