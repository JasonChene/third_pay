<?php
header("Content-type:text/html");
include_once('util.php');
include_once('config.php');

$data['orderNum'] =date('YmdHis').rand(10000,99999);//订单号
$data['version'] = 'V3.1.0.0';//版本号
$data['charset'] ='UTF-8';//编码
$data['random'] = (string)rand(1000,9999);//随机数
$data['merNo'] = 'TEST60000028';//商户号
$data['netway'] = 'E_BANK_ICBC'; //支付方式
$data['amount'] = '100';//订单金额
$data['goodsName'] ='goodsName';//商品名称
$data['callBackUrl'] = "http://your.callback.php";//通知地址
$data['callBackViewUrl'] ='http://your.return.php';//回显地址

$key='D0FF35B1867D5667C92688D91C0AEC70';//md5加密字符串

//生成签名
$data['sign'] = create_sign($data,$key);

//生成 json字符串
$json = json_encode_ex($data);

//加密
$dataStr =encode_pay($json,$public_key);

//请求字符串
$param = 'data=' . urlencode($dataStr) . '&merchNo=' . $data['merNo'] . '&version='.$data['version'];


//请求地址
$url="http://120.79.87.165:8070/api/pay.action";
//发起请求
$result = wx_post($url,$param);


//效验 sign
$rows = json_to_array($result,$key);
if($rows['stateCode']==0){
	echo "下单成功,返回的结果如下";
	header('Location: '.$rows['qrcodeUrl'].''); 

}else{
	echo "下单失败 ,".$rows['stateCode'] .$rows['msg'];
}

