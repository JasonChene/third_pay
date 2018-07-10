<?php
$_POST=[     'Method'=>     'paymentreport',     'Data'     =>     'RrQfLr6iKCyJYZCwSW5_f6fjoYPrS6SLWrhxLBJR9JpC4u4jYqVkRFdESYeVaupqgmEa2RazfEZuDoXXBjUH9tMiaKiMHt-2dRF9QnC9ntwHRmzFo6coZYYxn4p0F6qogurJ9NwEEGshav0ePjBLiHvoOVN30bg5i6Yjefik0CmnS4r1JcteQHN0QdYVhDAkuOmxklKPOke-y1mWk_1xADPXD5A6WP6rlWZuiTiiOituP0g0JCTDT3HDKddpS3tl',     'Sign'     =>     '3527f791727f7afc2b6627c482d9bfd0',     'Appid'     =>     '0HAK0YMK0M12420XB',];

header("Content-type:text/html;charset=utf-8");
$key    = "Qg7YADiW3534534rEe";
$method = $_POST['Method'];
$data   = $_POST['Data'];
$sign   = $_POST['Sign'];
$appid  = $_POST['Appid'];
$mySign = strtolower(md5($data . $key));
if ($mySign != $sign) {    exit(json_encode(['message' => '验证签名失败', 'response' => '01']));}
$aes_data =base64_decode( str_replace('-','+',str_replace('_', '/',  $data)));
$input = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $aes_data, MCRYPT_MODE_CBC, $key);
$result   = json_decode(rtrim($input, "\0"),TRUE);
if ($method == 'paymentreport') {    
$ordernumber  = $result['ordernumber']; //商户订单号    
$amount       = $result['amount']; //交易金额    
$payorderid   = $result['payorderid']; //交易流水号    
$busin= $resuesstime lt['businesstime']; //交易时间yyyy-MM-dd hh:mm:ss    
$respcode     = $result['respcode']; //交易状态 1-待支付 2-支付完成 3-已关闭 4-交易撤销    
$extraparams  = $result['extraparams']; //扩展内容 原样返回    
$respmsg      = $result['respmsg']; //状态说明    
//这边写你支付完成的业务逻辑    
//处理成功返回    
exit(json_encode(['message' => 'success', 'response' => '00']));
} 
else {    
exit(json_encode(['message' => '未识别的Method', 'response' => '01']));
}