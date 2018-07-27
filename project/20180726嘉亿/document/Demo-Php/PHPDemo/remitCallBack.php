<?php
header("Content-type:text/html;charset=utf-8");
include("util.php");
//秘钥
$key="810479A90CB5231C908603BC7E8C0D6A";

//解密前原文：
$data='cB5954oTZiqC079o1JGBiM/zNtRoRRfjUyfNDb/Jhfh08nLjiowHJptA92sEYzp1HaDduWMpTqJjhOpVUciIPLPZmCZKda5CDjI2cf7fsxtRbEMQn1eJaHKBf4EUPjMVCI5mnqSXj7IYKMcnbg6P08DbejeWXmCzksRir69Br5lVGTPwk4VYScBisSLo5CqoSfoedVNA4Z45y5g1Vy/LnAYjIA/kL4s41Ekd+0P6FOUID5wyy45aPwFEH0ct47ZjdJEhYjx7JrAUzkxCx2A0aOoiz714nvteZZkhPBPwpOHqpa5e0QRvn8MabOR2x0dtT+9IuNMncdIR1K6Zu/nxCQ==';

//秘钥 原字符串
$private_key_str = 'MIICeAIBADANBgkqhkiG9w0BAQEFAASCAmIwggJeAgEAAoGBAKbOsdpPhtp40Eaccp4iYrVnSf27mfXg3/FMilNQBDWiUnc0xA0XVjSURrjToh4vRZK3Z0KONDFxyWwDyl9A8LARJE82YMEbrR53lh01NYkLlYDv4A2EcWz6L8wX3Ni6la1t+wNukV7N7LDtkrcIdtJfckfkvAiDjKok8LM2ZFVbAgMBAAECgYEAimMi1G5z/56JlHtI7/6xC7Skgxf39DBbOhJ5FANnaqO/bNxe5kl9IGP/hGk3r3kibEUyKtpVXMv9Alrmsz3qL6E0LHjbcAcftiUDadZUTuzBC1prNTgJY96GrzUHuA9B7gohWffakwOaAQO0jCBX5bWxZkL2imic9xgJH6Dh9/kCQQDQXxvJXymdhGhkbnkHrWnLq8uqQcB50tbjItPMXxtpCUH4HHH/sPGiiTdA6XIqSHpRTFklWRPL9efcS8oFvqs9AkEAzO90nQSjfepY6UHHP0bnqX56iYsvw3fU+v2hl1f97pLNjga9WOoHCMIdpBDUtbUj6Wh3lMkznac/xGJpQOlsdwJBAK+qHrDLazgCMkfI4nvYdEJiGJb2S5/oYYSnDctTurX4OgdDY8/dijguWch9heOjqorzRIw55niiXM/ZjPz+2zkCQQCVOw2UxWr0ZvElsiOQHWbWkuZSQEugsCMkPgssdQPRdY8/jALXu1sx8oC5FxR92RD0h4EElFsEw2R48hypCxFTAkBExpZcns1D5Db58Wswa6JqOA1xeM681rCEvoc3AnwWrIJrzXjVVMwA0EnuBPpEvTYdJLIiPPSP4DBPUSwCFYft';

// 拼接秘钥；
$private_key = "-----BEGIN PRIVATE KEY-----\r\n";
foreach (str_split($private_key_str,64) as $str){
	$private_key .= $str . "\r\n";
}
$private_key .="-----END PRIVATE KEY-----";

//解密
$data = decode($data,$private_key);

//效验 sign
$rows = callback_to_array($data, $key);

if ($rows['remitResult'] == '00'){
	echo ("回调成功,以下是回调数据</br>");
	var_dump($rows);
}else{
	echo "错误代码：" . $rows['remitResult'];
}

