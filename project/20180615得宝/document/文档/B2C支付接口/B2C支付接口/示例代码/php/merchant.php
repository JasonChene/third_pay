<?php
/**
1）merchant_private_key，商户私钥;merchant_public_key,商户公钥；商户需要按照《密钥对获取工具说明》操作并获取商户私钥，商户公钥。
2）demo提供的merchant_private_key、merchant_public_key是测试商户号800003004321的商户私钥和商户公钥，请商家自行获取并且替换；
3）使用商户私钥加密时需要调用到openssl_sign函数,需要在php_ini文件里打开php_openssl插件
4）php的商户私钥在格式上要求换行，如下所示；
*/
	$merchant_private_key='-----BEGIN PRIVATE KEY-----
MIICeAIBADANBgkqhkiG9w0BAQEFAASCAmIwggJeAgEAAoGBANilQBp9xg6KdgB+
6jVnIFnMl34jR3sGguvDjAgTLTqYd/FhkSbNl24rbRKDjZ4jqDKxFFUbFlqMd0Yu
SzhE+Utb1jNcBROTyIn/2O0cCmN0tPFaSgL/ywYhXSjT1FlAWuFbBV+bggj8CLDU
pTGm31BofJA/qmg9Kn/wW2aF8QjNAgMBAAECgYEAyALgiNSfeqM4SELjxcPc6Srq
ngjCIIGlczbI3FegBR3odlBmatWaPZsYCuSrZVl0GsDDjcMBQz21jHSG+38qS0WT
xWrMgw/k88ygbfDXWEZQd1v8Em7CDIFN5rZ7InS2GZsDDl5HhBHFKp6eoGug+Xo7
Z5O8GokYaGKCdOuVcUECQQD9NRF05NTp0BzGxfVkcWmJeYI23vH+No8nPed4OZSA
2gNtpz7mZ2NE7lw05skznf4bVxWTanlynorYD32fejfdAkEA2wjzKggsxfiy4FkP
pq9Q04FooQt+W8efD4EOWuYMOVNoOAJYmjzE8YY2XUkaEZ80NHnCJcEZ/UtSX4Oq
L/f9sQJBANcazTCr8cCL/tZSd8yTmF+krR1mOtiGiwiAS3LUH7dy/jSaPxJHRIrb
n9OFN+o0zxl02qx4aKIZ08QHLOZdUrUCQQDagA4a8va/Mv42QYIUdLV7mI+of8+4
fOWW0NZiJTUyhprjrKt4iYCps4pN+tuvkpLAemoLwZtMi7QLpkvC+G+xAkAtOkom
2PugToM5QiM4MS7puinFV89SEsVQuGvHyyJ/iH8O6igd5XrH1AR7kL879onPTvAR
Tz+Ai7bHq3mUD3Wr
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
2)demo提供的dinpay_public_key是测试商户号1118004517的公钥，请自行复制对应商户号的公钥进行调整和替换。
3）使用公钥验证时需要调用openssl_verify函数进行验证,需要在php_ini文件里打开php_openssl插件
*/
		$dinpay_public_key ='-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDLYAY1pTXcMwKKagE
gKxv0Wtq0EwcKxQdQ6X+duKWWY1ti+YMSKx5RwUNPSqm9u59GkbFCHS
cOw+pZvHnDl47uaOV0NcxJU9CuGQ4UkuN5zJS/g/Fi4IFtQpLJPM5pJ
GNvnBkJzfqAD8DGOPoAc0TypMq2/HEVRMWU6PYBIiFa+QIDAQAB
-----END PUBLIC KEY-----'; 	
	



?>