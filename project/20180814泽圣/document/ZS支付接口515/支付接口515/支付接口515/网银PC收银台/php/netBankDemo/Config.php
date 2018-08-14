<?php
#支付平台-生产测试用商户号
$merchantCode="1000000001";
#签名密钥-与商户号一一对应
#生产 1000000200对应KEY
$md5Key="123456ADSEF";
#生产环境地址
$commonUrl="";
#支付平台支付地址
$orderUrl=$commonUrl."onlinebank/createOrder.do";

#商户平台的服务端异步通知地址
$notifyUrl="";
#商户平台前端支付成功展示界面地址
$merUrl="www.zsagepay.com";
?> 