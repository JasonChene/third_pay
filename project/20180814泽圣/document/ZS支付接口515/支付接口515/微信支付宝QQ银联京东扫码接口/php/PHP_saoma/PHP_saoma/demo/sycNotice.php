<?php


/**
 * 
 * 扫码支付-异步通知
 * 
 */
 
$md5Key = "123456ADSEF";
$sign=$_POST["sign"];

//签名数组
$sign_fields1 = Array(
    "merchantCode",
    "transType",
    "instructCode",
    "outOrderId",
    "transTime",
    "totalAmount"
   
);
//获取异步通知数据，并赋值给数组
$map = Array(
    "merchantCode"=>$_POST["merchantCode"],
    "transType"=>$_POST["transType"], 
    "instructCode"=>$_POST["instructCode"],
    "outOrderId"=>$_POST["outOrderId"], 
    "transTime"=>$_POST["transTime"], 
    "totalAmount"=>$_POST["totalAmount"]
    );
    
$sign0 = sign_mac($sign_fields1, $map, $md5Key);
// 将小写字母转成大写字母
$sign1 = strtoupper($sign0);

//验签
if($sign === $sign1) {
		echo "{'code':'00'}";
		// todo  xxxx
		
		
		
	}else {
		echo "{'code':'01'}";
	}












/* 构建签名原文 */
function sign_src($sign_fields, $map, $md5_key)
{
    // 排序-字段顺序
    sort($sign_fields);
    $sign_src = "";
    foreach ($sign_fields as $field) {
        $sign_src .= $field . "=" . $map[$field] . "&";
    }
    $sign_src .= "KEY=" . $md5_key;

    return $sign_src;
}

/**
 * 计算md5签名  返回的是小写的，后面需转大写
 */
function sign_mac($sign_fields, $map, $md5_key)
{
    $sign_src = sign_src($sign_fields, $map, $md5_key);
    return md5($sign_src);
}

?>
