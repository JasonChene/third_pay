<?php
header("Content-type:text/html;charset=utf-8");
include_once('util.php');
include_once('config.php');


$data['merNo']="TEST60000028";//商户号
$key="D0FF35B1867D5667C92688D91C0AEC70";//MD5字符串

//测试环境地址
$url="http://120.79.87.165:8070/api/queryBalance.action";

//生成签名
$data['sign'] = create_sign($data,$key);

//生成json字符串
$json = json_encode_ex($data);

//加密
$dataStr =encode_pay($json,$public_key);

//请求字符串
 $param = 'data=' . urlencode($dataStr) . '&merchNo=' . $data['merNo'] . '&version=V3.1.0.0';

//发起请求
$result = wx_post($url,$param);


//效验 sign
$rows = json_to_array($result,$key);

if($rows['stateCode']=="00"){
	echo "下单成功,返回的结果如下<br/>";
	var_dump($rows);

}else{
	echo "下单失败 ,".$rows['stateCode'] .$rows['msg'];
}