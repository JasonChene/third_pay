<?php
header("Content-type:text/html;charset=utf8");
include('util.php');
include('config.php');
$data["amount"]=1000;//金额
$data["channelCode"]="QQ";//支付类型  QQ或者ZFB
$data["goodsName"]="手机";//商品名称
$data["orderNum"]=date('YmdHis').rand(10000,99999);//订单号
$data["organizationCode"]=$merNo;//商户号
$data["payResultCallBackUrl"]="http://your.callback.php";//回调地址
$data["payViewUrl"]="http://your.return.php";//回显地址
$data["remark"]="测试";//备注 可空 

//生成签名
$sign=createSign($data,$signKey);
var_dump($data["orderNum"]);
//生成 json字符串
$json = jsonEncode($data);
//加密
$dataStr =encodePay($json,$public_key);
$reqParam["data"]=$dataStr;
$reqParam["merNo"]=$merNo;
$reqParam["sign"]=$sign;
//提交
$result=reqPost($url,$reqParam);
//验签
$rows = jsonToArray($result,$signKey);
if($rows['status']=="200"){
	echo "下单成功 响应数据如下：<br/>";
	var_dump($rows);  //将 $rows["data"] 地址做成二维码 
}else{
	echo "下单失败 ,".$rows['status'] .$rows['message'];
}



