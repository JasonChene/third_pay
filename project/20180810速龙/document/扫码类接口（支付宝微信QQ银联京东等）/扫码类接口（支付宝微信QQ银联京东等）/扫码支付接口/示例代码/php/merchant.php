<?php
/**
1）merchant_private_key，商户私钥;merchant_public_key,商户公钥；商户需要按照《密钥对获取工具说明》操作并获取商户私钥，商户公钥。
2）demo提供的merchant_private_key、merchant_public_key是测试商户号800003004321的商户私钥和商户公钥，请商家自行获取并且替换；
3）使用商户私钥加密时需要调用到openssl_sign函数,需要在php_ini文件里打开php_openssl插件
4）php的商户私钥在格式上要求换行，如下所示；
*/
	$merchant_private_key='-----BEGIN PRIVATE KEY-----
MIICdQIBADANBgkqhkiG9w0BAQEFAASCAl8wggJbAgEAAoGBAL8LxUg+Q4t96O4Y
4hS/ZMDbZw8u9PIHGFJLjQyllfbM49ZsJw1hH3UD8imtzde7qE9D8zejQh5ZFqEK
N5+rlFi8mTLZRVpkbiJ5HtuEjHRTCYtytG6uK8uFnQEmH2VudFIZ85NJA895eNiz
U0ms88upxpRy1I/8bsYzbjqzDKMhAgMBAAECgYBJleQQNoNXyFCe3RC/wxSwwBGL
JKAOVTNGB3m1xFXl8PdVEOVd3un57WIqMZrWnJ5woZCd/pEqFVCFCOVx5+nENYDZ
AwPQ4F/lqwfifEPta0wsMHb17eicVIOitCRDk3O1nvFjv4kBQnP67UITMhCDaJWn
TSOYiZ3rZTpA5HlLAQJBAOpzG09ZtSnLUvzKVBIGTV+GK5eGRvKDDTYtCvok7DWK
q3iwpy2yBopKypkHIh4IsCWYBVUT3nni/Mh17AO0qNECQQDQm1JUehfOEG3A5AkA
puRR3O2tc3pd4DwfoYpzqyE/kp3PdzRYSDamVOSU0Oaz03PwPPYF+3TCufsZZVjE
relRAkBDtXCKrx657kWOSiSTfAx2bPpD7Xyp5x02qzWDXox1PhIdbe8qLELlR4pR
PZUl1V6BzPClTHKxAtP8VMoPm+oxAkAtvyIa7Htz8R5ggqGGxxKi8TQeKYjYNWh5
908Jdqnf6yM4cAfGpG93on5ONFGjdeei83twbGh6m5Z5R0RkPU9BAkAmFai5Bykj
ZH0DJ/z2Du97J1X4btwTT3Ywjmd+OHpQ9weCkHPHd1IHj0YMt3cONBSOwZ+3B9zC
jwy0x8POMb0e
-----END PRIVATE KEY-----';

	//merchant_public_key,商户公钥，按照说明文档上传此密钥到商家后台，位置为"支付设置"->"公钥管理"->"设置商户公钥"，代码中不使用到此变量
	$merchant_public_key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDYpUAafcYOinYAfuo1ZyBZzJd+
I0d7BoLrw4wIEy06mHfxYZEmzZduK20Sg42eI6gysRRVGxZajHdGLks4RPlLW9Yz
XAUTk8iJ/9jtHApjdLTxWkoC/8sGIV0o09RZQFrhWwVfm4II/Aiw1KUxpt9QaHyQ
P6poPSp/8FtmhfEIzQIDAQAB
	-----END PUBLIC KEY-----';
	
/**
1)dinpay_public_key，公钥，每个商家对应一个固定的公钥（不是使用工具生成的密钥merchant_public_key，不要混淆），
即为商家后台"公钥管理"->"公钥"里的绿色字符串内容,复制出来之后调成4行（换行位置任意，前面三行对齐），
并加上注释"-----BEGIN PUBLIC KEY-----"和"-----END PUBLIC KEY-----"
2)demo提供的dinpay_public_key是测试商户号199001002003的公钥，请自行复制对应商户号的公钥进行调整和替换。
3）使用公钥验证时需要调用openssl_verify函数进行验证,需要在php_ini文件里打开php_openssl插件
*/
		$dinpay_public_key ='-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCDxfXKy0m1bvAnNly
l03JdqEoKbfVQ/vppSmgtIy8xjrDi9mtrRF9uv4QCtsVX59rPAilY6p
abBPNLfJomsRCbsWqoPtOxAReBGnzoE0vj4UyKZc448bLjXMLpwEuWI
6q/WMg4OCAuFtdY5Xc6cPFhGY/O2h9NMiFulM40KkcZ7QIDAQAB
-----END PUBLIC KEY-----'; 	 	
	



?>