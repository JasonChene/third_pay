<?php
function sign_text($array)
{
  $signtext = "";
  foreach ($array['str_arr'] as $arr_key => $arr_value) {
    if (is_array($arr_value)) {
      $arr_value_str = sign_text($arr_value);
      if ($array['havekey']) {
        $signtext .= $arr_key . $array['mid_conn'] . $arr_value_str . $array['last_conn'];
      } else {
        $signtext .= $arr_value_str . $array['last_conn'];
      }
    } else {
      if ($array['havekey']) {
        $signtext .= $arr_key . $array['mid_conn'] . $arr_value . $array['last_conn'];
      } else {
        $signtext .= $arr_value . $array['last_conn'];
      }
    }
  }
  if (strlen($array['last_conn']) == '') {
    $signtext = $signtext . $array['key_str'] . $array['key'];
  } else {
    $len = strlen($array['last_conn']);
    $signtext = substr($signtext, 0, -$len) . $array['key_str'] . $array['key'];
  }
  $encrypt_len = count($array['encrypt']);
  for ($i = 0; $i < $encrypt_len; $i++) {
    $signtext = addsign($array['encrypt'][$i], $signtext, $array['key']);
  }
  return $signtext;
}

function addsign($encrypt, $signtext, $key = null)
{ //AES還沒加
    //判断编码类型:md5、sha、base64、RSA、urlencode、upper、lower

  $sign = '';
  if ($encrypt == 'MD5') {
    $sign = md5($signtext);
  } elseif ($encrypt == 'SHA') {
    $sign = sha1($signtext);
  } elseif ($encrypt == 'JSON') {
    $sign = json_encode($signtext, 320);
  } elseif ($encrypt == 'base64') {
    $sign = base64_encode($signtext);
  } elseif ($encrypt == 'base64d') {
    $sign = base64_decode($signtext);
  } elseif ($encrypt == 'urlencode') {
    $sign = urlencode($signtext);
  } elseif ($encrypt == 'urldecode') {
    $sign = urldecode($signtext);
  } elseif ($encrypt == 'upper') {
    $sign = mb_strtoupper($signtext);
  } elseif ($encrypt == 'lower') {
    $sign = mb_strtolower($signtext);
  } elseif ($encrypt == 'RSApr') {
    $key = chunk_split($key, 64, "\r\n");//转换为pem格式的私钥
    $key = "-----BEGIN PRIVATE KEY-----\r\n" . $key . "-----END PRIVATE KEY-----\r\n";
    $pay_mkey = openssl_get_privatekey($key);//打開私钥
    if ($pay_mkey == false) {
      die("open privatekey error");
    }
    $pri = openssl_private_encrypt($signtext, $sign_info, $pay_mkey);//私钥加密
    if ($pri) {
      $sign = base64_encode($sign_info);
    } else {
      die("privatekey encrypt error");
    }
  } elseif ($encrypt == 'RSApu') {
    $key = chunk_split($key, 64, "\r\n");//转换为pem格式的公钥
    $key = "-----BEGIN PUBLIC KEY-----\r\n" . $key . "-----END PUBLIC KEY-----\r\n";
    $pay_mkey = openssl_get_publickey($key);//打開公钥
    if ($pay_mkey == false) {
      die("open publickey error");
    }
    $pub = openssl_public_encrypt($signtext, $sign_info, $pay_mkey);//公钥加密
    if ($prb) {
      $sign = base64_encode($sign_info);
    } else {
      die("publickey encrypt error");
    }
  } elseif ($encrypt == 'RSAprd') {
    $key = chunk_split($key, 64, "\r\n");//转换为pem格式的私钥
    $key = "-----BEGIN PRIVATE KEY-----\r\n" . $key . "-----END PRIVATE KEY-----\r\n";
    $pay_mkey = openssl_get_privatekey($key);//打開私钥
    if ($pay_mkey == false) {
      die("open privatekey error");
    }
    $pri = openssl_private_decrypt(base64_decode($signtext), $sign, $pay_mkey);//私钥解密
    if ($pri) {
      $sign = base64_encode($sign_info);
    } else {
      die("privatekey decrypt error");
    }
  } elseif ($encrypt == 'RSApud') {
    $key = chunk_split($key, 64, "\r\n");//转换为pem格式的公钥
    $key = "-----BEGIN PUBLIC KEY-----\r\n" . $key . "-----END PUBLIC KEY-----\r\n";
    $pay_mkey = openssl_get_publickey($key);//打開公钥
    if ($pay_mkey == false) {
      die("open publickey error");
    }
    $pub = openssl_public_decrypt(base64_decode($signtext), $sign, $pay_mkey);//公钥解密
    if ($pub) {
      $sign = base64_encode($sign_info);
    } else {
      die("publickey decrypt error");
    }
  }
  return $sign;
}
function toXml($arr)
{
  $xml = "<xml>";
  foreach ($arr as $key => $val) {
    if (is_numeric($val)) {
      $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
    } else {
      $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
    }
  }
  $xml .= "</xml>";
  return $xml;
}
#curl请求设定
function curl_post($url, $data, $str)
{
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  #curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
  if (strstr($str, "POST")) {
    if (strstr($str, "JSON")) {
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data)
      ));
    }
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
  } elseif (strstr($str, "GET")) {
    if (strstr($str, "JSON")) {
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data)
      ));
    }
    curl_setopt($ch, CURLOPT_HTTPGET, true);
    // $post_url=fix_postdata_url($url, $data);
    curl_setopt($ch, CURLOPT_URL, $url . '?' . $data);
  }
  $tmpInfo = curl_exec($ch);
  if (curl_errno($ch)) {
    echo (curl_errno($ch));
    exit;
  }
  curl_close($ch);
  return $tmpInfo;
}

#跳转qrcode.php网址调试
function QRcodeUrl($code)
{
  if (strstr($code, "&")) {
    $code2 = str_replace("&", "aabbcc", $code);//有&换成aabbcc
  } else {
    $code2 = $code;
  }
  return $code2;
}

?>
