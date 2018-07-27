<?php
header("Content-type:text/html;charset=utf-8");
include("util.php");
include("config.php");

$data['merNo']="TEST60000028";//	商户号	16	是
$data['orderNum']="201805051720097321";//	订单号	20	是
$data['amount']="100";//	金额（单位：分）	14	是
$data['remitDate']="2018-05-05";//	交易日期（格式：yyyy-MM-dd）	10	是

$key="D0FF35B1867D5667C92688D91C0AEC70";
//签名
$data['sign']=create_sign($data,$key);


//转成json 字符串
$json = json_encode_ex($data);
//加密
$dataStr =encode_pay($json,$remit_public_key);
//请求字符串
$param = 'data=' . urlencode($dataStr) . '&merchNo=' . $data['merNo']. '&version=V3.1.0.0';

//代付查询地址;
$remitUrl="http://120.79.87.165:8070/api/queryRemitResult.action";

//发起请求
$result = wx_post($remitUrl,$param);

//效验 sign;
$rows = json_to_array($result,$key);
if ($rows['stateCode'] == '00'){
	echo "代付订单查询成功,以下是订单数据</br>";
	var_dump($rows);
}else{
	echo "错误代码：" . $rows['stateCode'] . ' 错误描述:' . $rows['msg'];

}