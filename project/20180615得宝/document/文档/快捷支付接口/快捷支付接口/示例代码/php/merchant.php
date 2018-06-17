<?php

//merchant_private_key,商户私钥
$merchant_private_key= '-----BEGIN PRIVATE KEY-----
MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBALf/+xHa1fDTCsLYP
JLHy80aWq3djuV1T34sEsjp7UpLmV9zmOVMYXsoFNKQIcEzei4QdaqnVknzmIl7n1o
XmAgHaSUF3qHjCttscDZcTWyrbXKSNr8arHv8hGJrfNB/Ea/+oSTIY7H5cAtWg6Vmo
PCHvqjafW8/UP60PdqYewrtAgMBAAECgYEAofXhsyK0RKoPg9jA4NabLuuuu/IU8Sc
klMQIuO8oHsiStXFUOSnVeImcYofaHmzIdDmqyU9IZgnUz9eQOcYg3BotUdUPcGgoq
AqDVtmftqjmldP6F6urFpXBazqBrrfJVIgLyNw4PGK6/EmdQxBEtqqgXppRv/ZVZzZ
PkwObEuECQQDenAam9eAuJYveHtAthkusutsVG5E3gJiXhRhoAqiSQC9mXLTgaWV7z
JyA5zYPMvh6IviX/7H+Bqp14lT9wctFAkEA05ljSYShWTCFThtJxJ2d8zq6xCjBgET
AdhiH85O/VrdKpwITV/6psByUKp42IdqMJwOaBgnnct8iDK/TAJLniQJABdo+RodyV
GRCUB2pRXkhZjInbl+iKr5jxKAIKzveqLGtTViknL3IoD+Z4b2yayXg6H0g4gYj7NT
KCH1h1KYSrQJBALbgbcg/YbeU0NF1kibk1ns9+ebJFpvGT9SBVRZ2TjsjBNkcWR2HE
p8LxB6lSEGwActCOJ8Zdjh4kpQGbcWkMYkCQAXBTFiyyImO+sfCccVuDSsWS+9jrc5
KadHGIvhfoRjIj2VuUKzJ+mXbmXuXnOYmsAefjnMCI6gGtaqkzl527tw=
-----END PRIVATE KEY-----';


//dinpay_public_key,中鼎融公钥
$dinpay_public_key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCyIJbic6yoTsP/rCpVGVvhhSEo
3lwqjc+Oa4RHeKUYYPdmQ8MicbCFlTFIIAiS40gJHtsJlTomCQf1fazcJQs2peQR
gkg9MvFXJFG/CzL+cJzca98I613+iG2VrIDTsGYNZqPgo4uSwsKCr2L22TSt9zW/
TMGwYdTx1FT/ALHzNwIDAQAB
-----END PUBLIC KEY-----'; 


//encryption_key,加密密钥
$encryption_key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCFdPBBz6YtvFM/7PF1RLcolDDs
dxUKIyxVg3aNNxkZATGJrkaKw1iF/U4JATdTgl1+eYHVvTl26WqNxknpQtRqagNI
I2Yne7R+8+4e07leR0hOWYVevlsHFOcpMTCFQVdODWoeKMIVFSSu8In381hk9DR4
mmby30bNMK9ND+fQnwIDAQAB
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