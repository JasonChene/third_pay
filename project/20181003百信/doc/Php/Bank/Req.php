<?php
include_once("config.php");

$keyvalue = $key;//�û�key
$parter = $id;//�û�ID

$orderid =  date("Ymdhis");//�û������ţ�����Ψһ��
$value =  $_POST["Price2"];//�������
$type =  $_POST["bankid"];//����ID�����ĵ���
$callbackurl =  $callback_url;//�첽���շ���URL����
$hrefbackurl =  $$hrefback_url;//ͬ�����շ���URL����
$attach = "123";//��ע �������� urlencode����
$sign ="value=".$value."&parter=".$parter."&type=".$type."&orderid=".$orderid."&callbackurl=".$callbackurl;
$sign = md5($sign.$key);//ǩ������ 32λСд����ϼ�����֤��
$url=$submiturl."?parter=".$parter."&type=".$type."&value=".$value."&orderid=".$orderid."&callbackurl=".$callbackurl."&attach=".$attach."&hrefbackurl=".$hrefbackurl."&sign=".$sign;
header('Location:'.$url);

?>