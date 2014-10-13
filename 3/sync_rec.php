<?php
require("codes.php");
header("Content-Type: text/html; charset=UTF-8");
$start_index = $_POST['start-index'];
$max_results = $_POST['max-results'];
$db_uid = $_POST['douban_uid'];
//$start_index = 1;
//$max_results = 10;
//$db_uid = "stardust1900";

$url = 'http://api.douban.com/people/'.$db_uid.'/recommendations?apikey=01410e396047de1803c32aac20d79a48&start-index='.$start_index.'&max-results=$max_results';
//echo $url;
    // ��ʼ��һ�� cURL ����  
    $curl = curl_init();
    // ��������Ҫץȡ��URL  
    curl_setopt($curl, CURLOPT_URL, $url);
   // ����header  
    curl_setopt($curl, CURLOPT_HEADER, false);  
   // ����cURL ������Ҫ�������浽�ַ����л����������Ļ�ϡ�  
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  
   // ����cURL��������ҳ  
   $data = curl_exec($curl);
  // �ر�URL����  
   curl_close($curl);

  //���»����滻�ڵ��е�ð��
  $data = preg_replace('/([<<\/?])([1-z0-9]+):([1-z0-9]+)/i','$1$2_$3',$data);
  $recs = simplexml_load_string($data);
 // var_dump($data);
 $people = $recs->title;
 $mysql = new SaeMysql();
 foreach($recs->entry as $entry){
	//db_uid
	$api_id = $entry->id;
	$title = $entry->title;
	$title_gb = iconv("UTF-8","GB2312",$title);
	$url = $entry->link['href'];
    $comment = $entry->db_attribute[1];
	$comment_gb = iconv("UTF-8","GB2312",$comment);
    $published = $entry->published;
	$content = $entry->content;
	$category = $entry->db_attribute[0];
	$title_code = get_encode_str($title_gb);
	$comment_code = get_encode_str($comment_gb);
	$sql = "insert into rec".$db_uid." (db_uid,api_id,title,url,comment,published,content,category,title_code,comment_code)" 
	       ."values ('$db_uid','$api_id','$title','$url','$comment','$published','$content','$category','$title_code','$comment_code')";
	//echo $title." : ".$title_code."\n";
    $mysql->runSql($sql);
	if( $mysql->errno() != 0 ){
		die( "Error:".$mysql->errmsg());
	}
 }

 $mysql->closeDb();
?>