<?php
include_once("config.php");

$keyvalue = $key;//�û�key
$parter = $id;//�û�ID
		
		
$orderid =  date("Ymdhis");//�û������ţ�����Ψһ��
$value =  $_POST["totalAmount"];//�������
$type =  $_POST["bankCode"];//����ID�����ĵ���
$callbackurl =  $callback_url;//ͬ�����շ���URL����
$hrefbackurl =  $$hrefback_url;//�첽���շ���URL����
$payerIp = $_POST["payerIp"];//�ͻ�IP
$attach = $_POST["attach"];//��ע
$sign = "parter=".$parter."&type=".$type."&value=".$value."&orderid=".$orderid."&callbackurl=".$callbackurl;
		 
$sign = md5($sign.$key);//ǩ������ 32λСд����ϼ�����֤��

$url=$submiturl."?parter=".$parter."&type=".$type."&value=".$value."&orderid=".$orderid."&callbackurl=".$callbackurl."&attach=".$attach."&hrefbackurl=".$hrefbackurl."&payerIp=".$payerIp."&sign=".$sign;

header('Location:'.$url);

?>