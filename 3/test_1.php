<?php
require_once('codes_1.php');
header("Content-Type: text/html; charset=utf-8");
$title = "推荐2011年的课堂（假如社会媒体是一所中学）";
$title_code = get_encode_str($title);

echo $title." : ".$title_code."\n || ";
echo get_encode_str('推荐');
?>