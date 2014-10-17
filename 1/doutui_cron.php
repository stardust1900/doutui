<?php
require_once('sdk/DoubanOAuth.php');
require_once('config.php');
include_once( 'saetv2.ex.class.php' );

header("Content-Type: text/html; charset=utf-8");
$mysql = new SaeMysql();

$sql = "select * from doutui_user where flag=1 limit 10";
$records = $mysql->getData($sql);
 if($records){
    $now = time();
    foreach($records as $record){
         $douban = new DoubanOAuth(array(
            'key' => KEY,
            'secret' => SECRET,
            'redirect_url' => REDIRECT,
             'access_token' =>$record['db_access_token'],
          ));
        
        $wb_access_token = $record['wb_access_token'];
 		$statuses = $douban->get('shuo/v2/statuses/user_timeline/'.$record['db_uid']);
        if(is_null($statuses)) {
            continue;
        }
        foreach ($statuses as $status) {
             if(($now - strtotime($status['created_at'])) > 3600){
                break;
              }
            if(isset($status['reshared_status'])){
                $reshared = $status['reshared_status'];
                usleep(300000);
                push2weibo($reshared,$wb_access_token);
            }else{
                usleep(300000);
                push2weibo($status,$wb_access_token);
                //var_dump($ret);
                //break;
            }
        }
    }
 }
$mysql->closeDb();

function push2weibo($status,$wb_access_token){
    $weibo = new SaeTClientV2(WB_AKEY,WB_SKEY,$wb_access_token);
    $queue = new SaeTaskQueue('doutui_queue');
    $title = str_replace('[score]','*',$status['title']);
    $title = str_replace('[/score]','星',$title);
    if("" !=$status['attachments'][0]['title']) {
        $content = $status['user']['screen_name'].$title."[". $status['attachments'][0]['title']."]".$status['text'];
    }else{
        $content = $status['user']['screen_name'].$title.$status['text'];
    }
    $content = substr($content,0,120);
    $status_url=DOUBAN_PEOPLE_URL.$status['user']['uid']."/status/".$status['id']."/";
    $result= $weibo->oauth->get('short_url/shorten',array("url_long"=>$status_url));
    $surl=$result['urls'][0]['url_short'];
    
    if(isset($status['attachments'][0]['media'][0]['src']) && "image"==$status['attachments'][0]['media'][0]['type']) {
        $ret = $weibo->upload($content." ".$surl,$status['attachments'][0]['media'][0]['src']);
        // $postData = "content=".$content." ".$surl."&img=".$status['attachments'][0]['media'][0]['src']."&wb_access_token=".$wb_access_token;
        // sae_debug($postData);
        // $ret = $queue->addTask("/weibo_send.php", $postData);
    }else{
        $ret = $weibo->update($content." ".$surl);
        // $postData = "content=".$content." ".$surl."&wb_access_token=".$wb_access_token;
        // sae_debug($postData);
        // $ret = $queue->addTask("/weibo_send.php",$postData);
        // var_dump($ret);
        // $ret = $queue->addTask("/test1.php");
        // var_dump($ret);
        // $ret = $queue->addTask("http://doutui.sinaapp.com/test3.php");
        // var_dump($ret);
        // $array = array();
        // $array[] = array('url'=>'http://doutui.sinaapp.com/test4.php','postdata'=>$postData);
        // var_dump($ret);
        // $queue->addTask($array);

        // var_dump($queue);
        // if ($ret === false){
        //     var_dump($queue->errno(), $queue->errmsg());
        // }
        
    }
   
}

?>