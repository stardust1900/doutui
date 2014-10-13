<?php
    $queue = new SaeTaskQueue('doutui_rec_1');//顺序队列
	$i=1;
	 for($i=1;$i<=40;$i++){
		 $temp = ($i-1)*50+1;
		 $queue->addTask("http://3.doutui.sinaapp.com/sync_rec.php","start-index=$temp"."&max-results=50&douban_uid=stardust1900");
	 }
    //将任务推入队列
	$ret = $queue->push();
	var_dump($ret);
	if ($ret === false)
		var_dump($queue->errno(), $queue->errmsg());
	
?>
