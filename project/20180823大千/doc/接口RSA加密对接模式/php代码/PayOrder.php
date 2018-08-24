<?php
$key='964ce079-71b2-4ca4-9aef-a7b23d5ab818';
$privatekey='-----BEGIN RSA PRIVATE KEY-----
MIICeQIBADANBgkqhkiG9w0BAQEFAASCAmMwggJfAgEAAoGBAPM6p826cXEW0Jk+
Wrt87+NbCOqZO8CihUEHjLzsGLNJn12X8XsWkDFhzoyZfZOqK8K+85FeB2Vh0VYC
HNc6chS/04Yb//9Y8t71Myz2QjABn8A9Rdq68LTWgUudiTPA+UCHS1DUC71qtMT8
4bVXV0qB/wFccs+EovgGYYERRkG1AgMBAAECgYEA2PmnPdgnYKnolfvQ9tXiLaBF
GPpvGk4grz0r6FB5TF7N4rErwxECunq0xioaowK4HPc40qHd2SvkkWQ7FCjYIDsn
Mk1oOhxNKn0J3FG0n5Cg1/dFai4eoXHs/nKn3SVZ8YZC1T2cMtN2srectLqNqhB8
aQEe8xmykyUlUpg/qmECQQD9vkwjUotG5oUUrOj6etcB4WcdyyH0FtThKgyoJUDw
gBv6lGGzWyFJEREvp47IgV+FgC7zeP2mL4MhgnD3tNCZAkEA9WRrjOLBNc379XZp
oDsH7rZjobVvhnTrEuRDx/whqZ+vk64EPrEW81XYh647bAbJlFn2jPhY+IUHkrxF
EFT/fQJBAMoLNOULXQtfkqgb5odMONeue0Ul8itB4tBHgzyALW1TFPQ6InGGJsLf
bCfd67uMCFts7fXAaXhibK/KBdm3iEECQQChwVAjzlUN4nnzk9qMhFz2PcPvFGov
d2J9UXpcmRaXeWuDLXIe4Rz/ydaxmWgSDWdTIvoicpIzP31+fBwKZ/0BAkEAy0bh
4weKmYF29//rK0sxmY8RtqkQeFrwWbqx1daa1w0DfWlNSvy47zyW1G5/AdZU6JSp
XxlxdlM/HSDw+v7kcA==
-----END RSA PRIVATE KEY-----';
$publickey='-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDzOqfNunFxFtCZPlq7fO/jWwjq
mTvAooVBB4y87BizSZ9dl/F7FpAxYc6MmX2TqivCvvORXgdlYdFWAhzXOnIUv9OG
G///WPLe9TMs9kIwAZ/APUXauvC01oFLnYkzwPlAh0tQ1Au9arTE/OG1V1dKgf8B
XHLPhKL4BmGBEUZBtQIDAQAB
-----END PUBLIC KEY-----';

require_once('common.php');
$common = new COMMON();

$timestamp = time();
$nonce =$common->RandStr(8);
$arrayData = array(
	'order_trano_in' => time(),
	'order_goods' => '测试',
	'order_price' => 100,
	'order_num' => 1,
	'order_amount' => 100,
	'order_imsi' => '',
	'order_mac' => '',
	'order_brand' => '',
	'order_version' => '',
	'order_extend' => '',
	'order_bank_code' => '',
	'order_openid' => '',
	'order_return_url' => 'http://www.baidu.com',
	'order_notify_url' => 'http://www.baidu.com'
);
	
require_once('rsa.class.php');
$Rsa = new RSA($privatekey,$publickey);

$str = $common->ParameSort($arrayData);

$signature = $Rsa->Sign($timestamp.$nonce.$common->ParameSort($arrayData));

require_once('des.class.php');
$Des = new DES(strtoupper(substr(md5($timestamp.$key.$nonce),0,8)));

$post_data = $Des->encrypt(json_encode($arrayData));

$result = $common->send_post('http://127.0.0.1:8003/h5/PayOrder',$key,$timestamp,$nonce,$signature, $post_data);

echo var_dump($result);



?>