<?php
$conf = [
	'member_code' => '2017090631',    //商户号    商户后台->安全设置->获取API接口信息->商户号
	'member_secret' => 'c8be6f707ca0964ec7a7ef8ee9da013c',  //交易密钥  商户后台->安全设置->获取API接口信息->交易密钥
	'private_path' => 'cert/private_key_test.pem',	//商户私钥   由商户自行生成
	'public_path' => 'cert/public_key_test.pem',   //平台公钥   商户后台->安全设置->获取API接口信息->平台公钥
	'url' => 'http://www.magopay.net',
	'type_codes' => [
		"wxbs" => "微信被扫[wxbs]",
		"wxtxm" => "微信条形码[wxtxm]",
		"wxh5" => "微信H5[wxh5]",
		"zfbbs" => "支付宝被扫[zfbbs]",
		"qqbs" => "QQ钱包被扫[qqbs]",
		"qqh5" => "QQ钱包h5[qqh5]",
		"gateway" => "网关[gateway]",
		"sms" => "短信[sms]",
	],
	'netbank' => [
		'pc' => '主机端[pc]',
		'h5' => '移动端[h5]',
	],
];