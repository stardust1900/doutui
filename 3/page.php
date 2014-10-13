<?php
require_once('lib/ext_page.class.php');
header("Content-Type: text/html; charset=UTF-8");
$user_id = $_GET['user_id'];
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>豆推</title>
<style type="text/css">
<!--
a img {border:none;}
#Layer1 {
	position:absolute;
	left:19px;
	top:10px;
	width:642px;
	height:56px;
	z-index:1;
}
#Layer2 {
	position:absolute;
	left:17px;
	top:100px;
	width:90%;
	height:800px;
	z-index:2;
}
#Layer3 {
	position:absolute;
	left:700px;
	top:80px;
	width:176px;
	height:1198px;
	z-index:3;
}
.comment {
list-style:none;
float:right;
background:#66CCCC
}
-->
</style>
</head>

<body>
  
<table>
<tr>
<td>
<a href="page.php?user_id=<? echo $user_id?>">全部</a>
<a href="page.php?cat=url&user_id=<? echo $user_id?>">网址</a>
<a href="page.php?cat=entry&user_id=<? echo $user_id?>">九点文章</a>
<a href="page.php?cat=video&user_id=<? echo $user_id?>">视频</a>
<a href="page.php?cat=doulist&user_id=<? echo $user_id?>">豆列</a>
<a href="page.php?cat=topic&user_id=<? echo $user_id?>">小组话题</a>
<a href="page.php?cat=photo_album&user_id=<? echo $user_id?>">相册</a> 
<a href="page.php?cat=movie&user_id=<? echo $user_id?>">电影</a>
<a href="page.php?cat=note&user_id=<? echo $user_id?>">日记</a>
<a href="page.php?cat=photo&user_id=<? echo $user_id?>">照片</a>
<a href="page.php?cat=event&user_id=<? echo $user_id?>">活动</a>
</td>
</tr>
<tr>
<td>
 <form id="form1" name="form1" method="get" action="search.php">
    <input name="key" type="text" size="80" />
	<input name="user_id" type="hidden" value="<?echo $user_id?>"/>
    <input type="submit" name="Submit" value="搜 索" />
  </form>
</td>
</tr>
<?php
    $nowindex = 0;
	if(!empty($_GET['PB_page'])){
		$nowindex = ($_GET['PB_page']-1)*10+1;
	}
	
	$where = "";
	$cat = $_GET['cat'];
	if($cat){
		$where = " where category = '".$cat."'";
	}
	$mysql = new SaeMysql();
	$dbUidSql = "select db_uid from t_user where user_id = $user_id";
	$dbUid = $mysql->getLine($dbUidSql);
	$table = "rec".$dbUid['db_uid'];
	$count = "select count(*) as num from $table".$where;
	$sql = "select t.title,t.content,t.comment from (select * from ".$table.$where." order by published desc)t limit $nowindex,10";
	//echo $sql;
	$myrow = $mysql->getLine($count);
	$num=$myrow["num"];
	$rs=$mysql->getData($sql);
	foreach($rs as $data){
?>
<tr>
<td>
<?php
echo $data['content'];
?>
</td>
<tr>
<td align="right">
<?php
echo $data['comment'];
?>
</td>
</tr>
<table>
<?php
}
	echo "<br/>";
	$page=new page(array('total'=>$num,'perpage'=>10,'url'=>'page.php?cat='.$cat.'&user_id='.$user_id));
    echo $page->show();
	$mysql->closeDb();
?>

</body>
</html>
