<?
require("lib/lib_splitword_full.php");
function get_encode_str($str){
	$code="";
	$sp = new SplitWord();
	$dd = explode(" ",$sp->SplitRMM($str));
	$i=0;
	foreach($dd as $key=>$var){
		if(strlen($var)>2){ //UTF8���������Ϊ3 ���˵��ֲ�����
			$code.=base64_encode($var)." ";
			$i++;
		}
		if($i>=50) break;
	}
	return $code;
}
//$str = "��������Ҫ�ִʵ����ݣ�һ�㲻Ҫ����1024kb���һ�㣬����������";
//$result = get_encode_str($str);
//echo $result;
?>