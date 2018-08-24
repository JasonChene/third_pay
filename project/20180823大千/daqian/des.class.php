<?php  
//PHP ��DES �ӽ����㷨��  
class DES {  
	var $key;  
	var $iv; //ƫ����  
	  
	function __construct($key) {  
		//key����Ϊ8λ,����:1234abcd  
		$this->key = $key;  
		$this->iv = $key; //Ĭ����$key ��Ϊ iv  
	}  
	  
	//����  
	function encrypt($str) {  
		$encrypted = openssl_encrypt($str, 'DES-CBC', $this->key, OPENSSL_RAW_DATA, $this->iv);
		return $encrypt_msg = strtoupper(bin2hex($encrypted));
	}  
	  
	//����  
	function decrypt($str) {  
		$strBin = $this->hex2bin( strtolower($str) );  
		$str =  mcrypt_decrypt( MCRYPT_DES, $this->key, $strBin, MCRYPT_MODE_CBC, $this->iv );  
		$str = $this->pkcs5Unpad( $str );  
		return $str;  
	}  
	  
	function hex2bin($hexData) {  
		$binData = "";  
		for($i = 0; $i < strlen ( $hexData ); $i += 2) {  
			$binData .= chr ( hexdec ( substr ( $hexData, $i, 2 ) ) );  
		}  
		return $binData;  
	}  
  
	function pkcs5Pad($text, $blocksize) {  
		$pad = $blocksize - (strlen ( $text ) % $blocksize);  
		return $text . str_repeat ( chr ( $pad ), $pad );  
	}  
	  
	function pkcs5Unpad($text) {  
		$pad = ord ( $text {strlen ( $text ) - 1} );  
		if ($pad > strlen ( $text ))  
			return false;  
		if (strspn ( $text, chr ( $pad ), strlen ( $text ) - $pad ) != $pad)  
			return false;  
		return substr ( $text, 0, - 1 * $pad );  
	}  
}
?>  