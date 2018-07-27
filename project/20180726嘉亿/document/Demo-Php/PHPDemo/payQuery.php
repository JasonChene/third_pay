<?php
header("Content-type:text/html;charset=utf-8");
include("util.php");
include("config.php");

$key="D0FF35B1867D5667C92688D91C0AEC70";
$data['merNo']="TEST60000028";//商户号
$data['netway']="E_BANK_ICBC";//支付网关	16	是
$data['orderNum']="2018050515330168441";//订单号	20	是
$data['amount']="100"	;//金额（单位：分）	14	是
$data['goodsName']="goodsName";//商品名称（拼音）	20	是
$data['payDate']="2018-05-05";//	交易日期（格式：yyyy-MM-dd）

$data['sign'] = create_sign($data,$key);//生成签名；


$json = json_encode_ex($data);//转成json 字符串

//加密
$dataStr = encode_pay($json,$public_key);

//请求字符串
$param = 'data=' . urlencode($dataStr) . '&merchNo=' . $data['merNo'] . '&version=V3.1.0.0';
var_dump($param);

$query_reqUrl="http://120.79.87.165:8070/api/queryPayResult.action";//支付查询地址

$result = wx_post($query_reqUrl,$param);

$rows = json_to_array($result,$key);



if ($rows['stateCode'] == '00'){
echo ("订单查询成功,以下是订单数据</br>");
	var_dump($rows); 	#支付状态 00:支付成功 01:支付失败 03:签名错误 04:其他错误 05:未知06:初始 50:网络异常 99:未支付

}else{
	echo ("错误代码：" . $rows['stateCode'] . ' 错误描述:' . $rows['msg']);
}


