<?php
header("Content-Type: text/html; charset=utf-8");
$mysql = new SaeMysql();
if(empty($_GET['remainder'])){
	$sql = "select db_uid from t_user where bind = 1 and (user_id MOD 4) = 0";
}else{
	$sql = "select db_uid from t_user where bind = 1 and (user_id MOD 4) = ".$_GET['remainder'];
}
echo $sql;
$uids = $mysql->getData($sql);
 $queue = new SaeTaskQueue('doutui_queue');//�˴���test������Ҫ�����߹���ƽ̨���Ƚ���
foreach($uids as $uid){
	echo $uid['db_uid']."\n";
	  //�������
 $queue->addTask("http://doutui.sinaapp.com/sync_queue2.php", "db_uid=".$uid['db_uid']);
}
$mysql->closeDb();

 //�������������
 $ret = $queue->push();
 var_dump($ret);

if ($ret === false)
    var_dump($queue->errno(), $queue->errmsg());

?>