<?php
require_once('sdk/DoubanOAuth.php');
require_once('config.php');
include_once( 'saetv2.ex.class.php' );

header("Content-Type: text/html; charset=utf-8");
$mysql = new SaeMysql();

$sql = "select * from doutui_user where flag=1 limit 10";
$records = $mysql->getData($sql);
//var_dump($records);
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
        //var_dump($statuses);
        foreach ($statuses as $status) {
             if(($now - strtotime($status['created_at'])) > 3600){
                break;
              }
            if(isset($status['reshared_status'])){
                $reshared = $status['reshared_status'];
                $ret = push2weibo($reshared,$wb_access_token);
            }else{
                $ret = push2weibo($status,$wb_access_token);
                //var_dump($ret);
                //break;
            }
        }
    }
 }
$mysql->closeDb();

function push2weibo($status,$wb_access_token){
    $weibo = new SaeTClientV2(WB_AKEY,WB_SKEY,$wb_access_token);
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
    }else{
        $ret = $weibo->update($content." ".$surl);
    }
    return $ret;
}
?>