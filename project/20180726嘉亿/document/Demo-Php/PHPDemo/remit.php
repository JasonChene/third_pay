<?php
header("Content-type:text/html;charset=utf-8");
include("util.php");
include("config.php");

$data['version']="V3.1.0.0";//版本号，固定值：V3.1.0.0	8	是
$data['merNo']="TEST60000028";//商户号	16	是
$data['orderNum']= date('YmdHis') .rand(10000,99999);//订单号	20	是
$data['amount']="100";//金额，（单位：分）	14	是
$data['bankCode']="ICBC";//银行代码，参考附录3.4	20	是
$data['bankAccountName']="张三";//开户名	128	是
$data['bankAccountNo']="62122************547";//银行卡号	128	是
$data['callBackUrl']="http://your.callback.com";//结果通知地址	128	是
$data['charset']="UTF-8";//客户端系统编码格式	10	是
$key="D0FF35B1867D5667C92688D91C0AEC70";

$data['sign']=create_sign($data,$key);//签名（字母大写）	32	是

//转成json字符串
$json = json_encode_ex($data);

//加密
$dataStr =encode_pay($json,$remit_public_key);

//请求原文
$param = 'data=' . urlencode($dataStr) . '&merchNo=' . $data['merNo'] . '&version='.$data['version'];

//代付请求地址
$remit_url="http://120.79.87.165:8070/api/remit.action";

//发起请求
$result = wx_post($remit_url,$param);


//效验 sign;
$rows = json_to_array($result,$key);

if ($rows['stateCode'] == '00'){
	echo "代付创建成功,以下是订单数据</br>";
	var_dump($rows);
}else{
	echo "错误代码：" . $rows['stateCode'] . ' 错误描述:' . $rows['msg'];

}