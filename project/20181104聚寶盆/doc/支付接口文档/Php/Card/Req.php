<?php
include_once("config.php");

$keyvalue = $key;//�û�key
$parter = $id;//�û�ID

$orderid =  date("Ymdhis");//�û������ţ�����Ψһ��
$price =  $_POST["Price2"];//�������
$cardtype =  $_POST["cardtype"];//������ID�����ĵ���
$cardno =  $_POST["cardno"];//����
$cardpwd =  $_POST["cardpwd"];//����
$callbackurl =  $callback_url;//ͬ�����շ���URL����
$attach = "123";//��ע �������� urlencode����

$sign = "parter=".$parter."&cardtype=".$cardtype."&cardno=".$cardno."&cardpwd=".$cardpwd."&orderid=".$orderid."&callbackurl=".$callbackurl."&restrict=0"."&price=".$price;
$signs = md5($sign.$key);//ǩ������ 32λСд����ϼ�����֤��
$url=$submiturl."?".$sign."&attach=".$attach."&sign=".$signs;
$result =file_get_contents($url); //�ύ

if($result=="0")
{
	echo "�����ύ�ɹ�!";
}
else
{
	echo $result;
}
?>