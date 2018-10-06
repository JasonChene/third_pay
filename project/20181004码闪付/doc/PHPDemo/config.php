<?php
header("Content-type:text/html;charset=utf8");

$merNo="Mer1530098623429x97";//商户号

//支付地址
$url="http://api.65011688.com/api/ScanCodePay";
//支付查询地址
$queryUrl="http://api.65011688.com/api/payQuery";

$signKey="029f6e19fc70db20011fc377";//md5加密字符串

$public_key_str="MIGfMA0GCSqGSIawefgEBAQUAA4GNADCBiQKBgQCaBI8eEnNWgJyImJgSb6lFQRc5y8BEC/iG5qrKTnnDEO/CtTNavgp2XiUbc4oWusnLFZhUJpq9erQARPvW8dXjsMppsAeGNjeKT644NB9f9KkUvW6dlQFzrLK8Kk7xv2ES3VC74ttIUOAws2nDjE3ZS0Aq9iwGl/YDUZ3/aJtH1wIDAQAB";


$private_key_str="MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBAIVCUrFIgFzkyv3dEfgGyz7kLvnyd50/paHVHsnXiWZw+S42Y2xw7KTYPLoZ/b2ZxWa60HQ/C4DGhSIlus8Nv2QCF/DIhZyjx2jQRVmtZkGdxF1+O4MuKZbSr4WC27zCtjhpZbog+KERcRPHCpwhKuSmTNlXYwveqGbjlrRtwmqPAgMBAAECgYEAgXHMe9OOhS3mFTWil8iZs6zaCQfDs2c5EHvCXZTWLkF9e6tvZs5GwVYfnzNSLNPrUgAUL2dyWBP/DQ2Taj/Tce9DAxAhite9SA5rZ37KSAX1awecve5CpNXjzV0CIsHjq0S0l1aRu3x53wzCmZn7G84UdizQBA8LFs2pKvmbrgECQQD2jTyrWBuI0bWaRXvoBTaHnOh0IJocM9yPc/bHKxlHd/OMMboDOhPaa/np0IIwycAP0oZ1I0UwzAf5nYTgoW+PAkEAil2kwpRWTSxZr5kEkl6p160jzeh8S+JnpJLd9BtZSMhBWRNpYpjFcqyqn9nsJF882PkVCPtFDWLfLrCBmEHVAQJBANFNzXdYcOeRwC2bVzj9U/GEjwImuy2+CTvvIkyqledKOYOGo3ch0sDWcW7BCAULAPVtr5+5tX9bdlm+E5Pu+JkCQGXgeTWDft+nrglqw0D6Tles9dXgPdSpyOIV+TKJCH5cZDEv58Je9dJva9ny3LoTHXwsOd65E6i9idKYx3LR1gECQBjlD0W/6XnwPGmSRSGBINJ1swOxr4Y8twG/X0aNcxjVRHd7ROk79Mnx2WLi96p8ZdeRZHt9NHZ4qGJmfc7ZxVk=";



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