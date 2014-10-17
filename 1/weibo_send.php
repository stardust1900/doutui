<?php
require_once('config.php');
include_once( 'saetv2.ex.class.php' );
sae_debug("call weibo_send");
if(isset($_POST['content']) && isset($_POST['wb_access_token'])) {
	$content = $_POST['content'];
	$wb_access_token = $_POST['wb_access_token'];
	$weibo = new SaeTClientV2(WB_AKEY,WB_SKEY,$wb_access_token);
	sae_debug("content:".$content."	".$wb_access_token);
	if(isset($_POST['img'])) {
		$img = $_POST['img'];
		$ret = $weibo->upload($content,$img);
		sae_debug("img:".$ret); 
	}else{
		$ret = $weibo->update($content);
		sae_debug($ret);
	}
}else{
	sae_debug("bad request");
}

?>


