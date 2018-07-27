<?php
function sign_text($array){
  $signtext = "";
  foreach ($array['str_arr'] as $arr_key => $arr_value) {
    if (is_array($arr_value)) {
      $arr_value_str = sign_text($arr_value);
      if ($array['havekey']) {
        $signtext .= $arr_key.$array['mid_conn'].$arr_value_str. $array['last_conn'] ;
      }else {
        $signtext .= $arr_value_str. $array['last_conn'] ;
      }
    } else {
      if ($array['havekey']) {
        $signtext .= $arr_key.$array['mid_conn'].$arr_value. $array['last_conn'] ;
      }else {
        $signtext .= $arr_value. $array['last_conn'] ;
      }
    }
  }
  $len = strlen($array['last_conn']);
  $signtext = substr($signtext,0,-$len) . $array['key_str'] . $array['key'];
  $encrypt_len = count($array['encrypt']);

  for ($i=0; $i < $encrypt_len; $i++) {
      $signtext = addsign($array['encrypt'][$i],$signtext,$array['key']);
  }
  return $signtext;
}

function addsign($encrypt,$signtext,$key=null){ //AES還沒加
    //判断编码类型:md5、sha、base64、RSA、urlencode、upper、lower
    $sign='';
    if($encrypt == 'MD5'){
      $sign = md5($signtext);
    }elseif ($encrypt == 'SHA') {
      $sign = sha1($signtext);
    }elseif ($encrypt == 'base64') {
      $sign = base64_encode($signtext);
    }elseif ($encrypt == 'base64d') {
      $sign = base64_decode($signtext);
    }elseif ($encrypt=='urlencode') {
      $sign = urlencode($signtext);
    }elseif ($encrypt=='urldecode') {
      $sign = urldecode($signtext);
    }elseif ($encrypt == 'upper') {
      $sign=mb_strtoupper($signtext);
    }elseif ($encrypt == 'lower') {
      $sign=mb_strtolower($signtext);
    }elseif ($encrypt == 'RSApr') {
      $pay_mkey = openssl_get_privatekey($key);//打開私钥
      if ($pay_mkey == false) {
        die("open privatekey error");
      }
      $pri=openssl_private_encrypt($signtext,$sign_info,$pay_mkey);//私钥加密
      if($pri){
        $sign = base64_encode($sign_info);
      }else {
        die("privatekey encrypt error");
      }
    }elseif ($encrypt == 'RSApu') {
      $pay_mkey = openssl_get_publickey($key);//打開公钥
      if ($pay_mkey == false) {
      	die("open publickey error");
      }
      $pub=openssl_public_encrypt($signtext,$sign_info,$pay_mkey);//公钥加密
      if($prb){
        $sign = base64_encode($sign_info);
      }else {
        die("publickey encrypt error");
      }
    }elseif ($encrypt == 'RSAprd') {
      $pay_mkey = openssl_get_privatekey($key);//打開私钥
      if ($pay_mkey == false) {
      	die("open privatekey error");
      }
      $pri=openssl_private_decrypt(base64_decode($signtext),$sign,$pay_mkey);//私钥解密
      if($pri){
        $sign = base64_encode($sign_info);
      }else {
        die("privatekey decrypt error");
      }
    }elseif ($encrypt == 'RSApud') {
      $pay_mkey = openssl_get_publickey($key);//打開公钥
      if ($pay_mkey == false) {
      	die("open publickey error");
      }
      $pub=openssl_public_decrypt(base64_decode($signtext),$sign,$pay_mkey);//公钥解密
      if($pub){
        $sign = base64_encode($sign_info);
      }else {
        die("publickey decrypt error");
      }
    }
    return $sign;
}


function pkcs5_pad ($text, $blocksize) {
  $pad = $blocksize - (strlen($text) % $blocksize);
  return $text . str_repeat(chr($pad), $pad);
}


?>
