<?php
header("Content-type:text/html;charset=utf-8");

//私钥 字符串
$private_key_str="MIICeQIBADANBgkqhkiG9w0BAQEFAASCAmMwggJfAgEAAoGBAK12pDMEYn7H7vs6ndR14Z72QPWO5fZSUnWkn/vGzx036FNDlAUpF7Hrp6/RjpXO/I0RpUqizr30qsfoMscyH3Gde3g0C77xvWua3nw12scdCvBAUdnwxot3a9thGTJ+cFNzCXdjb6KCoABB3mLE30Abq/wJKeR18u6eFq8zTRWlAgMBAAECgYEAnCql5XBbJHzngLLjCTYfrDdTgD/odPzkVBmkFs6EBHAi72N1zbeTJ5FbZISrc9/nqzFpuGoe6xFs95Dqtj8/4lO/dkbo9nKJeE+5ZkEcjQxy7T6R4Zb7uLfJDnGHOpd8wdQ2gZj1oYHhO0T92t1yHXgqHAgoqeM82YjqX+FpAoECQQDeheiyVlGrJiDYXLluDXkfQhlbGvh/BK93AvVQfrhoe7Si4BoQsZ0ldV91iTZZ24KotrWyrSvjkTqjfs++NHlRAkEAx49JEYJ7qCVOn/Zaqd6DBCmoVUpRZCmPEUe22xS6OCX9mFqg5gggUaOtOT/9700s2g8xsUdV81NNrcdlpCSCFQJBALaR04uOOs9bJxOdcB4VO80jpD4VcNB1/ah9atwyPAatiFUh3QDZWP+Ev19lxowPB7J4xbblUd8SJrRBtRxvXSECQQCP+4TXulJn9krtRT0S9PWIqlnG9/ETmJyd9iUrmp/lZRu8sh8G+XQAE10nQlGAOCmVR4MuCm8sOb2BFa3zKVglAkEApI0hIa2tmHqYqImOlDylWBew+L9s00kQ40I7i10yxwjzECjRTDbibMAxA7gOV7XKZDPC+wivdSqkmTBjff20bg==";



//支付 公钥字符串
$public_key_str="MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCsISXAP7X0ryCi8H5pAEcmA85Soseu2caG6CGydt9xcmEiNKH9bJ1ma5NvLu3cABBAy8eHWvIsmLP7GTZc8yi5uwaiP9mKKAzpQrVJIhviyuqkQocSzvve45RF/5LuudofS4n4w/dzGrFq4jPUYDZOu8Plgf78CrgoEpZkTja5NQIDAQAB";

//代付 公钥字符串
$remit_public_key_str="MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDMpd/ba9iqahM2DP++IRu5u2E5KbaZ14tSgXSk9032hwcbZnJux3JJWUnQXOOfn1JhULRNQOFofKvfCv8lD7xQhWOyN8AnHK27JOwyqIeWvsk4ZefR6N+KPyizKYB0EihaKNzD3jex+IxM4q9BpxOAiOgucHNGgOJ4tbGXlxkX9QIDAQAB";



$private_key = "-----BEGIN PRIVATE KEY-----\r\n";
foreach (str_split($private_key_str,64) as $str){
	$private_key .= $str . "\r\n";
}
$private_key .="-----END PRIVATE KEY-----";



$public_key = "-----BEGIN PUBLIC KEY-----\r\n";
foreach (str_split($public_key_str,64) as $str){
	$public_key .= $str . "\r\n";
}
$public_key .="-----END PUBLIC KEY-----";


$remit_public_key = "-----BEGIN PUBLIC KEY-----\r\n";
foreach (str_split($remit_public_key_str,64) as $str){
    $remit_public_key .=  $str . "\r\n";
}
$remit_public_key .= "-----END PUBLIC KEY-----";