<?php
class RSA {  
    var $PrivateKey;  
	var $PublicKey; //公钥  
	  
	function __construct($PrivateKey,$PublicKey) {   
		$this->PrivateKey = $PrivateKey;  
		$this->PublicKey = $PublicKey; 
	}  
	//rsa 签名
	function Sign($str)
	{
		$private_key = openssl_get_privatekey($this->PrivateKey);
		//var_dump($private_key);
		openssl_sign($str,$sign,$private_key);
		openssl_free_key($private_key);
		$rsaSign = base64_encode($sign);
		return $rsaSign;
	}
	
	//验证签名
	function Verify($data,$sign)
	{
	    $public_key = openssl_get_publickey($this->PublicKey);
	     //var_dump($public_key);
	     $sign=base64_decode($sign);
	     $result=(bool)openssl_verify($data,$sign,$public_key);
	     openssl_free_key($public_key);
	     return $result;
	}
}
?>