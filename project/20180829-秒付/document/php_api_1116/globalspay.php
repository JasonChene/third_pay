<?php

//返回的页面
header("Content-Type:text/html;Charset=utf-8");
//引入返回处理类payback
include_once 'payback.php';
$payback=new payback();
//支付结果 result;交易成功，订单改为成功状态
$result=$payback->_validation();
echo $result.'<br>';
//订单号
echo '订单号'.$payback->out_order_no;

