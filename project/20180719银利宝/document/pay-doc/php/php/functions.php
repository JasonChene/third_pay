<?php

include "./rsa.php";


/**
 * Md5签名
 * @param $params
 * @param $signKey
 * @return string
 */
function getMd5Sign($params,$signKey)//签名方式
{
  ksort($params);
  $data = "";
  foreach ($params as $key => $value) {
      if($value == '' || $value == null){
          continue;
      }
    $data .= $key . '=' . $value .'&';
  }
  $sign = md5($data. 'key=' .$signKey);
  return $sign;
}

/**
 * RSA 签名校验
 * @param $params
 * @param $platformPublicKey
 */
function verifyRsaSign($params, $platformPublicKey){
    ksort($params);
    $data = "";
    foreach ($params as $key => $value) {
        if($value == '' || $value == null || $key == 'sign'){
            continue;
        }
        $data .= $key . '=' . $value .'&';
    }

    $data = preg_replace("/&$/", '',$data);
    $plaintext = md5($data);
    return rsa::verifySign($plaintext, $params['sign'], $platformPublicKey);
}

?>