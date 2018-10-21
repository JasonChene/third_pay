<?php

//merchant_private_key,商户私钥
$merchant_private_key= '-----BEGIN RSA PRIVATE KEY-----
MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBALIgluJzrKhOw/+s
KlUZW+GFISjeXCqNz45rhEd4pRhg92ZDwyJxsIWVMUggCJLjSAke2wmVOiYJB/V9
rNwlCzal5BGCSD0y8VckUb8LMv5wnNxr3wjrXf6IbZWsgNOwZg1mo+Cji5LCwoKv
YvbZNK33Nb9MwbBh1PHUVP8AsfM3AgMBAAECgYEAr6oyAtse39Dlu+OWz9u1X/+B
hyNa82Bs20Au8KkK77LY6NJUw0gpVGOgeUeWDP31kYELdDTlZpMrdS9eZLBnj/Qo
fFTx7GSeod+vV13cgA6rc0yzjTp25Dm7Xzihf15R5JiNIFzlSYC2TLz+HcJoprxY
6Pf6I/1qBjZuoC67eEECQQDjDhEI7s010aXXYQy3xwC/RUDosnfMARqRCpYFCYmo
yMiUZ7+ohIvWkkCcwHx7VNKnXfmF0ezdXNT2TCKfXj6hAkEAyNXFKkCPtbg+GFqU
lxlfta1s7FJuC1b8ZyaA1ygqUK5PJUoEKR9UcDg0uCKx4Zofpm46WCHx8w8M0+Ab
ss8a1wJAA5JqFDDli44zxLKjJ5T63wdw4PhFyDDQQS3gdE3VG5GlDiifrEABjyuX
1p90leAcvENPNJq71jOqqgFCni02YQJAQ8q09SA54lNA0qOwyJhOEFtsCxGAB9/i
70a18uqh7f4IxUOIyADFVeQDF6zOcqK90EYg96Ltsuf/on1hnCgAnQJBANGvRflf
L1Xvelv2jb446Gnq83IwQ6WJvO8z7/awfMmDsC88MI2bE0xcWJ2QPZZEVJkgCmwO
Xc26G+z0eei/z/U=
-----END RSA PRIVATE KEY-----';


//dinpay_public_key,康付通公钥
$dinpay_public_key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCyIJbic6yoTsP/rCpVGVvhhSEo
3lwqjc+Oa4RHeKUYYPdmQ8MicbCFlTFIIAiS40gJHtsJlTomCQf1fazcJQs2peQR
gkg9MvFXJFG/CzL+cJzca98I613+iG2VrIDTsGYNZqPgo4uSwsKCr2L22TSt9zW/
TMGwYdTx1FT/ALHzNwIDAQAB
-----END PUBLIC KEY-----'; 


//encryption_key,加密密钥
$encryption_key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDTE8YBexrEmn8oEqs
ASVgkZEUo/WTqKZlmr0MYDyIVgcNfvXJPUR9kD46RAT11UYKK681UI0
IWcfi/uB+bL00bVzuW7x5YdT5zdDuca/i3H3MIbWMcAHXAqPQt38Z0y
WoXoCJp0IZ975vBVSe/a70M7uh1aLSapQFKyUCO2i3hGwIDAQAB
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