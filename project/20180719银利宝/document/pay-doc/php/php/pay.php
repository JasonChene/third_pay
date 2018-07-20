<?php

    include "./functions.php";

    // 平台 RSA公钥
    $public_key_content = file_get_contents(__DIR__. "/rsa_public_key.pem");
    $platformPublicKey = openssl_get_publickey($public_key_content);


    $apiKey = "34747aa775086242d1ec9bd9c7c74cf5";
    $url = "https://api.tonghejinxian.com/pay/unifiedorder";

    $ch = curl_init($url);
    $timeout = 6000;
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER,0 );
    curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

	//本地测试 不验证证书
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //不验证证书
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //不验证证书




    $data = array(
        'trade_type' => 'GATEWAY',//支付方式
        'mch_id' => '1525399982',// 商户号
        'nonce' => time(),// 随机字符串
        'timestamp' => time(),//时间戳
        'subject' => '购买眼镜',
        'detail' => '海俪恩太阳眼镜',
        'out_trade_no' => 'GO' + time(),
        'total_fee'=> '10000',
        'spbill_create_ip' => '225.225.225.225',
        'timeout'=> '30',
        'notify_url'=>'https://api.tonghejinxian.com/pay/test/notify_url'
    );
    $data['sign'] = getMd5Sign($data,$apiKey);


    $data_string =json_encode($data);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json; charset=utf-8",
            "Content-Length: " . strlen($data_string))
    );
    $ret = json_decode(curl_exec($ch), true);
    curl_close($ch);

    if(verifyRsaSign($ret, $platformPublicKey)){
        echo $ret['pay_url'];
    }
    else{
        echo "签名校验失败";
    }

?>