<?php
header("Content-type:text/html;charset=utf-8");
include("util.php");

//md5 字符串
$key="D0FF35B1867D5667C92688D91C0AEC70";

//解密前原文
$data='DRMBOIHA9wTQM9x9SWlFNiJjbfU2r8dMTOIhIzLkPC++fGIxgczbYl0LdYBRUpVuiYG7QhFOe62axwmpwRoFx2sCTEm+yMBcqov8mnXtqgSl3rQGgIUQiAHn8cGf9p3cFx22boeQwvO69YpVtCKdNcb7DSUvQfqgeLk9ze4yi2ie8PAslMnkSHYJGHQvfj6aMISh7tlqkRVB8EjfUQYIfsF7MALkrBOZBXo7t85CWmaqfbev78DgZGjLddIwyUAExRD02jpND9LGcG6Zqu0bj2xGnTRiJOJWz/tACH0fW7WbD7fbJYSwyOZKQjdWz0n/yZG2p705kRDSgecxHNb1OQ==';


//秘钥

$private_key_str='MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAMOAiGISeGX7hBWkxaJdxE76FWrywdX5GTa6LYV6r49nmNQ/rRWQ+bgORqrcWvHBbf2EsGRjQJLg5gZzscy/0vsX9Yk6YExmpA9YEGeUZg83P9Lrbi0rPvlZF7fume6Kp0unBllNF24bZqJJSrCiEEsGTxB1PLdkV6T0WuHqjus3AgMBAAECgYAYJF4zJi7CAT49YfyZ4VRloFJWw6WWI82uSW1np0/YuKVRyI86M43y4ahuXwnIAufvP5x8uRj3Slh9gXn6W9HUwoHBdHvTqihcf7Zr2dGtsQXZmwNSdroXB8VZe1n3gpALkbAjnoy9Lj7mbgJfcnE2eQXE2XpDYJlEtJg0kZb+kQJBAPhVz0kkXVTiYcVCqLgjuN9kb6sm4Payhgmjiick9XCaB3ZOOG9/tTNYrc1owebMzobi3pBDHRGV5USSW0KIftkCQQDJiUTE0EC+kBboFjqZJnwXBnuMi9Rpi/vO20rDhTRtWoozcuhA8DTPgVwdNqEcm10MPevCBI1bwGkdC0PS5pCPAkAYxia5u0D8WOE8FpxSUm39Cz4Aqw5CTikFCSWdJhi+NP+Nk9wZc3oWN0CPf2XoqoHn/vYJGkjqGjQXFSq5hnhxAkBEVgU5bxvlAdqii5cHAyOkcxBFkcxOlaamd3kcXvht8/tUgqv6CPj/O2/OgA2VM6ETW5OrT/vWlqGNtZoj0wZFAkEA2JCBYhtP8EG2WA1SE8j8IgLtH4fJGVlOhPKkFs20nOLD2lfod6Rhpz+C6l6M6f0WKCeTXBlEZgG8Wn+vilRRDg==';


//拼接秘钥字符串
$private_key = "-----BEGIN PRIVATE KEY-----\r\n";
foreach (str_split($private_key_str,64) as $str){
	$private_key .= $str . "\r\n";
}
$private_key .="-----END PRIVATE KEY-----";

//解密
$data = decode($data,$private_key);

//效验 sign 签名
$rows = callback_to_array($data, $key);


if($rows['payResult']='00'){
	echo('回调成功<br/>');
	var_dump($rows);
}else{
	echo "错误代码：" . $rows['payResult'] . ' 错误描述:' . $rows['msg'];
}
