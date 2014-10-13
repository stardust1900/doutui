<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
 $ivc = $_GET['ivc'];
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>注册</title>
<script language="javascript">
function trim(strInput){
  if (strInput != null)
  return strInput.replace(/(^\s*)|(\s*$)/g, "");
  else
  return "";
}
function checkForm() {
	document.form1.email.value = trim(document.form1.email.value);
	document.form1.passwd.value = trim(document.form1.passwd.value);
	document.form1.invitecode.value = trim(document.form1.invitecode.value);
    if (document.form1.email.value.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/) == -1){
		alert("电邮格式不对！");
		document.form1.email.focus();
		return false;
	}
	if(document.form1.passwd.value == ""){
		alert("请输入密码！");
		return false;
	}
	if(document.form1.invitecode.value == ""){
		alert("请输入邀请码！");
		return false;
	}
}
</script>
</head>

<body>
<form id="form1" name="form1" method="post" action="reg.php" onsubmit="return checkForm();">
<table width="602" height="78" border="1">
  <tr>
    <td><div align="right">电邮：</div></td>
    <td>
        <input name="email" type="text" size="50" />
    
    </td>
  </tr>
  <tr>
    <td><div align="right">密码：</div></td>
    <td><label>
      <input name="passwd" type="password" size="50" />
    </label></td>
  </tr>
  <tr>
    <td><div align="right">邀请码：</div></td>
    <td>
      <input name="invitecode" type="text" size="50" value="<?php echo $ivc;?>"/>
    </td>
  </tr>
  <tr>
    <td colspan="2">
        <div align="center">
          <input type="submit" name="Submit" value="注 册" /> 
          <label>  
          <input type="button" name="Submit2" value="取 消" onclick="javascript:window.location = 'index.html'"/>
          </label>
        </div></td>
  </tr>
</table>
</form>

</body>
</html>
