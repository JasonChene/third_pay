<?php

//merchant_private_key,商户私钥
$merchant_private_key= '-----BEGIN RSA PRIVATE KEY-----
MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBAOD/K22eGec3qNQm
k9LwsjpbJDJE9JYfsTJJQGJhfWsKbcZ9UISKXZxuhSCVaf2z9/pEln5RoE7GNwOr
Yv8R00P8nRJONHNPaLcf0Y8+c6DBWGVewZKojUzn18uAEGGW5XMjLs5/OU//opRB
4ieeSmBJ4jp954XfR4Z57bjOpe/3AgMBAAECgYEArCr2K2JQxfp0aSq/8SkX6Mm3
T/QuCPZlXGprJx0coJ0RVVKtG07ZxQtZOY671VQyjEKRukVx2vWYQWmTTkVwl+U7
1fh1mmiu00Y3odNoERc02ZN0zJmrSuhbcuEv6F8kBATunB55wOZ3jlbkXD9h+KUy
ePBOkrPb+81LhJ6kZXkCQQD18nQ1U2m9laS8ROJmZ1LuecQ4maaHW3xFxHoM9sS1
YcpB3peQuXBrKa483zYADIJV2NYstc0QXMMZIXleKFFzAkEA6jF+xx4q+p/lhH8M
3rHucHmkgFce90Jh1eHTdx5czizl3LiOYZ5D7cNL8x7piJDMmzkVz8+OidXm0wf5
aT82bQJAP9TSJjjk26hn3dj+7Vbppi0CKTJvjvfGdBD/IDg3a1/a72eG7K/EJnvl
1bSUvkSA2yjwxR/V/eYlWHNgnXhXUwJBANA6h+3FfhNvXmSrjqbncAljrwdJ70eM
J29DpoFQZtYPB6Z0FmzniqB6OCqIPr7leHc/j4xBkQwvO1hBy9pvkRUCQEVOGouG
VeiXL/MuupUdbdBSV4nkYb9hrqE11gzbLu4A+OCpV8Xwdqu5SqX9Js1mQ6vQwTHu
63vyfpxxl7oN9Jw="
-----END RSA PRIVATE KEY-----';


//dinpay_public_key,公钥
$dinpay_public_key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCZDDIp0i3Ai7hskWs03M8n9QXrz
3FX+KXak/zhy+rO7IBCE2p7mqHgMzj2KTqPfeQyAJoF6WvNy9MT9Vf/MA1mcYpYp9
vVXRlLZ9oCFGUHtL2VJJHPzKlit46OGhyU7z/jtWwDuRXqcO76+VPi0/1XOvsyR5l
TDKB3LVQA4cPD3wIDAQAB		
-----END PUBLIC KEY-----'; 


//encryption_key,加密密钥
$encryption_key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCbT0QjejG8pj8y/HfmzL7j9gHFK
TwSg68ZiOad4ErxkSi/N77HnG+scCXVnkvvBwPTFI35AStGQnv3E/7XeCO8swvVR8
J4w/k5JfQwcHnSk64DD0L+0KH6cRN27WZYdL9n4r+jW6OXILnbw0t1YM0/y6cOCc4
R8yyd1hTCdgP3LwIDAQAB
-----END PUBLIC KEY-----'; 

function  postCurl($postdata,$url){
		
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postdata));  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response=curl_exec($ch);
		return  $response;
	}
?>