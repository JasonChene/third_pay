<?php
header('Access-Control-Allow-Origin:*');

$rws_post = file_get_contents('php://input');
$arr = json_decode($rws_post);
$signature=$arr->signature;
/*重新填充数组，待签字符串去掉signature*/
$arrayData = array();
foreach ($arr as $key => $value) 
{ 
	if($key!="signature")
	{
	  $arrayData[$key] = $value;
	}
}

require_once('common.php');
$common = new COMMON();

$str = $common->ParameSort($arrayData);//按照字母键asc排序组合后的字符串
$appkey='da45777c-82dc-40fd-bff6-8f140bed466a';
$sign = strtoupper(md5($str.$appkey));//生成签名字符串

if($sign==$signature)
{
   $order_trano_in = $arrayData["order_trano_in"];//商户单号
   $order_number = $arrayData["order_number"]; //平台单号
   $order_pay = $arrayData["order_pay"];//支付类型
   $order_state = $arrayData["order_state"];//支付状态：0.待支付 1.支付成功
   $order_goods = $arrayData["order_goods"];//商品名称
   $order_price = $arrayData["order_price"];//下单金额
   $order_num = $arrayData["order_num"];//下单数量
   $order_amount =$arrayData["order_amount"];//支付金额，个别支付通道由于风控原因实际支付金额和下单金额可能不一致（特别通道商务会告知），以实际支付金额为准
   $order_imsi = $arrayData["order_imsi"];
   $order_mac = $arrayData["order_mac"];
   $order_brand = $arrayData["order_brand"];
   $order_version = $arrayData["order_version"];
   $order_extend = $arrayData["order_extend"];//扩展参数
   $order_time = $arrayData["order_time"]; //支付成功时间

	/* --- 商户业务逻辑 start --- */

	/* --- 商户业务逻辑 end --- */
	
	//成功返回ok
	echo "ok";
}
else
{
   //异常处理返回
   echo "fail";
}
?>