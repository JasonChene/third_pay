<?php
$merchant_code 		= $_POST["merchant_code"]; //商户编号
$merchant_order_no 	= $_POST["merchant_order_no"]; //商户订单号
$request_amount		= $_POST["merchant_amount"]; //实际支付金额，犹豫部分网关为了避免屏蔽，在支付时进行了小数位的加减，所以实际到账已这个金额为准
$request_amount_orig= $_POST["merchant_amount_orig"]; //请求支付金额（实际到账金额，以此金额为准）
$merchant_sign		= $_POST["merchant_sign"]; //加密验证
$merchant_md5		='E2660F5DA277B74909914EC82604FABE1'; //商户MD5,从本地配置文件获取或写死

//签名正确
if ($merchant_sign == base64_encode(md5('merchant_code='.$merchant_code.'&merchant_order_no='.$merchant_order_no.'&merchant_amount='.$request_amount.'&merchant_amount_orig='.$request_amount_orig.'&merchant_md5='.$merchant_md5)))
{
    exit('ok');
}

exit('fail');
