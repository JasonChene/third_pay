<?php
$config1 = array(
    'config' => 'E:/xampp/apache/conf/openssl.cnf',//修改为你本地目录
);
$config2 = array(
    'private_key_bits' => 1024,
    'private_key_type' => OPENSSL_KEYTYPE_RSA,
);
$privateKey = openssl_pkey_new($config1 + $config2);
openssl_pkey_export($privateKey, $privkey, null, $config1);
$pubkey=openssl_pkey_get_details($privateKey);
$pubkey=$pubkey["key"];
$key = array('privKey'=>$privkey,'pubKey'=>$pubkey);
print_r($key);
