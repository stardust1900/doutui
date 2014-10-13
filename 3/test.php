<?php
require_once('codes.php');
$title = "推荐关于性格内向者的10个误解,献给奋战在一线的程序员 - J2ME手机游戏开发站";

$title = iconv("UTF-8","GB2312",$title);
echo $title;
$title_code = get_encode_str($title);

echo $title_code;
?>