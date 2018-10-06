<?php
header("Content-type:text/html;charset=utf8");
include('util.php');
include('config.php');

//支付回调报文

$resultStr='{"orderNo":"2018080517240683745","data":"VFnU993TP6z6TYeRva24C8r+7pZ8z+4Ax8I1EV3npBWEBkRlc5y5J5jYA/5p/pj/6hrWdSptVp+byBpprxgEjFKQ/rZeqljeM+qg0afgW9UQGs//dUHiVXOZ8ewodqkE9THXN7zBpchm8vyFEdJCuPjCimLDMFi6bDZS8tb/TK5bBZt+awfdQwUgdvroCfcrEz0H31hvmhcyLsn7dcuGV3aBVnNBcAH7zK5KQbXWHGOxMXP97QiRyRNoGSFf276WlcmL0SDhm/tSpWIifw9IpmC/3Y1/6Ett2zLTeCh75cCYJp78vQcw6ZYv5JkOaI7j6XrM/pyB/jjW6kvORMvpVA==","merNo":"Mer1530098602429x97","sign":"1D653E3B9EB0BA5F1B7A6D7B183F718A"}';


$result=json_decode($resultStr,true);
$sign=$result['sign'];//签名
$data=$result["data"];
//解密
$resultJson=decode($data,$private_key);
//验签
$resultData=jsonToQuery($resultJson,$signKey,$sign);
if($resultData['payStateCode']=="00"){
	echo "未支付";
}else if($resultData['payStateCode']=="10"){
	echo "支付成功";
}else if($resultData['payStateCode']=="20"){
	echo "支付失败";
}else if($resultData['payStateCode']=="30"){
	echo "支付中";
}else{
	echo $resultData['payStateCode'].",错误信息：".$resultData['$resultData'];
}

