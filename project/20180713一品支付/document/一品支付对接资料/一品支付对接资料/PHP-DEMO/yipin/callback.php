<?php
if(empty($_POST['signature'])){
	exit;
}

require_once 'order.php';
$Pay = new Pay;

$data = array(
	'success'     => $_POST['success'],
	'orderNumber' => $_POST['orderNumber'],
	'money'       => $_POST['money'],
	'payDate'     => $_POST['payDate'],
);

if($_POST['signature'] === $Pay->sign($data)){
	// 业务逻辑
};

echo 'SUCCESS';
