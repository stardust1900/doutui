<?
require("lib/lib_splitword_full.php");
function get_encode_str($str){
	$code="";
	$sp = new SplitWord();
	$dd = explode(" ",$sp->SplitRMM($str));
	$i=0;
	foreach($dd as $key=>$var){
		if(strlen($var)>2){ //UTF8编码的设置为3 过滤单字不保存
			$code.=base64_encode($var)." ";
			$i++;
		}
		if($i>=50) break;
	}
	return $code;
}
//$str = "这里是你要分词的内容，一般不要超过1024kb会好一点，否则会很慢！";
//$result = get_encode_str($str);
//echo $result;
?>