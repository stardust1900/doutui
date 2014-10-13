<?php
session_start();
if($_SESSION["db_uid"]){
	 header("Location:main.php?douban_uid=".$_SESSION["db_uid"]);
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
 <body>
</html>
<?php
}
?>