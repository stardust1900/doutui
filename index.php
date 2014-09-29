<?php
session_start();
if($_SESSION["douban_uid"]){
  header("Location:main.php");
}else{
?>
<html>
<head>
<STYLE TYPE="text/css">
    
     .dou{
		 background-image: URL(douban_button.gif);
		 background-position: center;
		 background-repeat: no-repeat;
		 background-attachment: fixed;
	 }
	 a img {border:none;}
    .login{
		width:105px;
		height:27px;
		//background:url(douban_button.gif);
		margin-top:100px;
		margin-left:400px;
	}
    </STYLE>
</head>
<title>¶¹ÍÆ</title>
<body>
 <div class="login"><a href="douban_connect.php"><img src="douban_button.gif" align="middle"/></a></div>
<hr>
 <table style="width:100%"><tr>
		<td>created by<A href="http://twitter.com/stardust1900">@stardust1900</A></td>
		<td style="text-align:right;color:silver">¶¹ÍÆ</td>
	</tr></table>
	<a href="http://sae.sina.com.cn" target="_blank"><img src="http://static.sae.sina.com.cn/image/poweredby/poweredby.png" title="Powered by Sina App Engine" /></a>
 <body>
</html>
<?php
}
?>