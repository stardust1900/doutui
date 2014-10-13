<?php
include_once( 'config.php' );
include_once( 'saet.ex.class.php' );
header("Content-Type: text/html; charset=utf-8");
$mysql = new SaeMysql();
$sql = "select * from sync_user where flag = 1";
$users = $mysql->getData($sql);
$now = time();
foreach($users as $user){
	//echo $user["db_uid"];
	//echo $user["sina_oauth_token"]."\n";
	//echo $user["sina_oauth_token_secret"]."\n";
    $c = new SaeTClient( WB_AKEY , WB_SKEY , $user["sina_oauth_token"] , $user["sina_oauth_token_secret"] );

	//$ms  = $c->home_timeline();
	//foreach( $ms as $item ){
		//echo $item['text'];
	//}
	//$c->update("豆推测试====================");


	$url = 'http://api.douban.com/people/'.$user["db_uid"].'/recommendations?apikey=01410e396047de1803c32aac20d79a48';
	//echo $url;
    
	// 初始化一个 cURL 对象  
    $curl = curl_init();
    // 设置你需要抓取的URL  
    curl_setopt($curl, CURLOPT_URL, $url);
   // 设置header  
    curl_setopt($curl, CURLOPT_HEADER, false);  
   // 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。  
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  
   // 运行cURL，请求网页  
   $data = curl_exec($curl);  
  // 关闭URL请求  
   curl_close($curl); 
  // echo $data;
  // var_dump($data);
  //用下划线替换节点中的冒号
  $data = preg_replace('/([<<\/?])([1-z0-9]+):([1-z0-9]+)/i','$1$2_$3',$data);
  $recs = simplexml_load_string($data);
 // var_dump($recs);
  $people = $recs->title;
  $count = 0;
  foreach($recs->entry as $entry){
	    //echo $entry->title,'<br/>';
		//echo $entry->published,'<br/>';
		//echo $now - strtotime($entry->published),'<br/>';
		if(($now - strtotime($entry->published))<=3600){
			$text = $people." : ".$entry->content;
			//echo $text;
			$test = str_replace('<a href="','',$text);
			$test = str_replace('">',' |',$test);
			$test = str_replace('</a>','',$test);
			if(!empty($entry->db_attribute[1])){
				$test = $test."『".$entry->db_attribute[1]."』";
			}
			$c->update($test);
			//sleep(0.1);
			$count++;
		}
		//echo $entry->content,'<br/>';
  }
  echo $count;
}
$mysql->closeDb();
?>