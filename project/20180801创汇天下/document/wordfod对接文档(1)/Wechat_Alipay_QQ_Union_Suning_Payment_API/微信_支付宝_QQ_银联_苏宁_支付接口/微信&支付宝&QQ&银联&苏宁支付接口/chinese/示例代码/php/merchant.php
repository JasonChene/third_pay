<?php
/**
1）merchant_private_key，商户私钥;merchant_public_key,商户公钥；商户需要按照《密钥对获取工具说明》操作并获取商户私钥，商户公钥。
2）demo提供的merchant_private_key、merchant_public_key是测试商户号1111110166的商户私钥和商户公钥，请商家自行获取并且替换；
3）使用商户私钥加密时需要调用到openssl_sign函数,需要在php_ini文件里打开php_openssl插件
4）php的商户私钥在格式上要求换行，如下所示；
*/
		$merchant_private_key='-----BEGIN PRIVATE KEY-----
MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBAKn93SEpidIRKTYR
qvjb6qW6iS8eorY/5nPwBE2xk7tdulDsWCZzLtt9oSCDZj6QTFXjXQZKO03cyvVP
S5gZ1MIeW0ARkfcwdrJ6EawonKS909lIflCfCKCC9oCqHIhFXFmK9AU6UjcTwE5n
wwb3K689Ng+6SEVjHMendtV3OTjDAgMBAAECgYBxx+QtADqpiqcE88p2i+yBRVvx
WBYc2qSL0Ylv3348mT3OUIOoKMyiSXKB6rGTCs6tZmOrhCAxu6l1jL/SbOfEd33T
SUmMSTAyLhq3Uc1kRa9D8u7hJHqHRJeG5NNU/rJy5t9ncBI9ktEKpWKQpix1WfqS
sfeO+TKUfMNWOlDmIQJBANEqg7UrJ68n2rFpN281HDsVR12IQnBKyFtDqBZ33bWX
R+yAXRexwLUvPZYaBuBEp9KcIBee9g0J6IY8W84Z5GsCQQDQDdsHOhAd01KhANnG
z7FkIbac9vEohbovzlMeOPV7wXbsZR+ZrqJXzhbuvU8sjCGDItf5KRCtT+rjIofG
JNMJAkEAos1WinK2hqycma3tic9q08nyLCjcnY53eCGm+SX/GVJQlxIqY0DlX6EP
bH+Bjpmhjloa2IfPt8JYi/L6+eZJVQJANCfVCXm/wopQQ3ZAIbu9H3noGm85Q0xK
wWM6qO/kcjKsilRLWK5TmilazFx+tY8nc4VPmPF3ccr/+hKU8NIYaQJBAL+bKSa+
9N3aR1OnCfBf7Tf5hvCVCR7gKoo5llOH3yo+pNLBDdI4TDDueSoK0UD8t1nodrgZ
Mc/sbch+9zWswQA=
-----END PRIVATE KEY-----';

	//merchant_public_key,商户公钥，按照说明文档上传此密钥到商家后台，位置为"支付设置"->"公钥管理"->"设置商户公钥"，代码中不使用到此变量
	//demo提供的merchant_public_key已经上传到测试商家号后台
	$merchant_public_key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC3//sR2tXw0wrC2DySx8vNGlqt
3Y7ldU9+LBLI6e1KS5lfc5jlTGF7KBTSkCHBM3ouEHWqp1ZJ85iJe59aF5gIB2kl
Bd6h4wrbbHA2XE1sq21ykja/Gqx7/IRia3zQfxGv/qEkyGOx+XALVoOlZqDwh76o
2n1vP1D+tD3amHsK7QIDAQAB
-----END PUBLIC KEY-----';
	
/**
1)zhihpay_public_key，公钥，每个商家对应一个固定的公钥（不是使用工具生成的密钥merchant_public_key，不要混淆），
即为商家后台"公钥管理"->"公钥"里的绿色字符串内容,复制出来之后调成4行（换行位置任意，前面三行对齐），
并加上注释"-----BEGIN PUBLIC KEY-----"和"-----END PUBLIC KEY-----"
2)demo提供的zhihpay_public_key是测试商户号1111110166的公钥，请自行复制对应商户号的公钥进行调整和替换。
3）使用公钥验证时需要调用openssl_verify函数进行验证,需要在php_ini文件里打开php_openssl插件
*/
			$zhihpay_public_key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCp/d0hKYnSESk2Ear42+qluokv
HqK2P+Zz8ARNsZO7XbpQ7Fgmcy7bfaEgg2Y+kExV410GSjtN3Mr1T0uYGdTCHltA
EZH3MHayehGsKJykvdPZSH5QnwiggvaAqhyIRVxZivQFOlI3E8BOZ8MG9yuvPTYP
ukhFYxzHp3bVdzk4wwIDAQAB
-----END PUBLIC KEY-----'; 


	



?>