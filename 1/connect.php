<?php
session_start();
  require_once('sdk/DoubanOAuth.php');
  require_once('config.php');
header("Content-Type: text/html; charset=utf-8");
if(isset($_SESSION['db_uid'])){
	header("Location:main.php");
}
  $douban = new DoubanOAuth(array(
    'key' => KEY,
    'secret' => SECRET,
    'redirect_url' => REDIRECT,
  ));

  $url = $douban->getAuthorizeURL(SCOPE, STATE);

  // echo '<a href="' . $url . '">使用豆瓣帐号登录</a>';
?>
<html>
<head>
<STYLE TYPE="text/css">
	 a img {border:none;}
    .login{
		width:105px;
		height:27px;
		margin-top:100px;
		margin-left:500px;
	}
.container {
  margin-right: auto;
  margin-left: auto;
  padding-right: 100px;
  padding-left: 100px;
  *zoom: 1;
}
    </STYLE>
</head>
<title>豆推</title>
<body>
<div class="container">
<center><h2>同步豆瓣广播到新浪微博</h2></center>
 <div class="login">
 	<a href="<?=$url?>"><img src="images/douban_button.gif" /></a>
 </div>
<hr>
 <table style="width:100%"><tr>
		<td>created by<A href="http://weibo.com/halfg0d">@王半山人</A></td>
		<td style="text-align:right;color:silver">豆推</td>
	</tr>
 </table>
	<a href="http://sae.sina.com.cn" target="_blank">
		<img src="http://static.sae.sina.com.cn/image/poweredby/poweredby.png" title="Powered by Sina App Engine" />
	</a>
	</div>
 <body>
</html>
