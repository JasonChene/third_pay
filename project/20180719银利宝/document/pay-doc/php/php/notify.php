<?php

    include "./functions.php";

    // 平台 RSA公钥
    $public_key_content = file_get_contents(__DIR__. "/rsa_public_key.pem");
    $platformPublicKey = openssl_get_publickey($public_key_content);

    $params = $_POST;

    if(verifyRsaSign($params, $platformPublicKey)){
        if($params['result_code'] == 'SUCCESS'){
            // TODO 通知成功 业务处理
            echo "SUCCESS";
        }
    }
    else{
        echo "签名校验失败";
    }