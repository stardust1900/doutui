<?php
$mysql = new SaeMysql();
$sql = "select * from t_invitecode where flag =0";
$rs=$mysql->getData($sql);
?>
<html>
 <head>
  <title> ÑûÇëÂë </title>
  <meta name="Generator" content="EditPlus">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
 </head>

 <body>
  <form name="form1" action="generate_codes.php" method="post">
  <input type = "submit" name="generate_codes" value="Éú³ÉÑûÇëÂë"/>
  </form>
  <br/>
  <table>
  <?php
	foreach($rs as $data){
  ?>
  <tr>
  <td>
  <?php echo "http://3.doutui.sinaapp.com/register.php?ivc=".$data['invite_code']; ?>
  </td>
  </tr>
  <?php
	}

   $mysql->closeDb();
  ?>
  </table>
 </body>
</html>
