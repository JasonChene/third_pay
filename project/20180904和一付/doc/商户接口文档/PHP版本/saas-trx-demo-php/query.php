<?php
/*
 * @Description ���ο��Ʒͨ�ýӿڷ��� 
 * @V3.0
 * @Author Svi
 */
include 'zhifuCommon.php';

$trxMerchantNo = $_POST['trxMerchantNo'];
$trxMerchantOrderno = $_POST['trxMerchantOrderno'];

$hmac = getQueryHmacString($trxMerchantNo,$trxMerchantOrderno); 

$_data = array(
	'trxMerchantNo'=>$trxMerchantNo,
	'trxMerchantOrderno'=>$trxMerchantOrderno,
	'hmac'=>$hmac
);
$result = send_post($queryURL_onLine,$_data);
print_r($result);
?>