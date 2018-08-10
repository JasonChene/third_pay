<?php
/**
1）merchant_private_key，商户私钥;merchant_public_key,商户公钥；商户需要按照《密钥对获取工具说明》操作并获取商户私钥，商户公钥。
2）demo提供的merchant_private_key、merchant_public_key是测试商户号3990010088的商户私钥和商户公钥，请商家自行获取并且替换；
3）使用商户私钥加密时需要调用到openssl_sign函数,需要在php_ini文件里打开php_openssl插件
4）php的商户私钥在格式上要求换行，如下所示；
*/
	$merchant_private_key='-----BEGIN PRIVATE KEY-----
MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBALQM/1rCVab7Vbqy
dQfPvDNhdE8TWg7BiUakgrJrT5D8kwapoKXe4IWwCpqH0t2BuInPJ3y8TfPWzrXs
s1iUrOHIzhZ98iD56bVVwIetUbDQfCJReuLRF8U3k9JjaNCxovsD/dy8IY1SwU+M
zLHuwwiObVYJO6xr2xh+nZ7HHIMnAgMBAAECgYB7JUihsrkSdGS2RMh2h0aZfdYn
H31BCm3xuyMJFQxeheGpdOtDh6TYywb1mmBG0Cp/VwgS8FgAprLLCxG/TDDRNtpJ
eQ84ROdn0uQVGRPZ1TaA/Jb20W8Zwvcaw4JLFxfRwYaCtcIWU4wO6E31U6OGi6yE
QEzw2+NpgTOiuj2fgQJBANvip+qDUH3Mhasp4/FF8H6nY/Cg9t+xe4cDKw/IcmRi
IvaKu9ZU+bmprlUP5C81na3i+6xbaet5Df6LPBE2sX8CQQDRn3KrO7nA4QgKSUls
CaIFb40PtNkGbcNCLGxTgZTY9MP8rhcBGB/JsVD2JRTVHPZfZ08v3V/rxntrTJ4e
kjJZAkEAsQYHHbxeXwj7TFPLWYFvHuhwg1dzqZ/fzVB1qJ09yyde8pG6q9F5w0mi
OoUytfc6XLXy3E40NgkdhSJfV5RqzQJAVBLcXdslhDt9CwsbvI4cgoucCvmgtZhe
YPMKO2/UcDOzS2vTZCf1z+IjM6XpthPYqKj5tJvYNes0YvOm02IVeQJAaCiYtvd/
g/WYj2uNUzPYh5sdmZ9OzFAL5j+UG5ZGbj1KocVbXLyvt3+5Qx6LguWzmVODjN+j
yrKszPZYh9YIeQ==
-----END PRIVATE KEY-----';

//merchant_public_key,商户公钥，按照说明文档上传此密钥到商家后台，位置为"支付设置"->"公钥管理"->"设置商户公钥"，代码中不使用到此变量
	$merchant_public_key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC0DP9awlWm+1W6snUHz7wzYXRPE1
oOwYlGpIKya0+Q/JMGqaCl3uCFsAqah9LdgbiJzyd8vE3z1s617LNYlKzhyM4WffIg
+em1VcCHrVGw0HwiUXri0RfFN5PSY2jQsaL7A/3cvCGNUsFPjMyx7sMIjm1WCTusa9
sYfp2exxyDJwIDAQAB
-----END PUBLIC KEY-----';
	
/**
1)dinpay_public_key，公钥，每个商家对应一个固定的公钥（不是使用工具生成的密钥merchant_public_key，不要混淆），
即为商家后台"公钥管理"->"公钥"里的绿色字符串内容,复制出来之后调成4行（换行位置任意，前面三行对齐），
并加上注释"-----BEGIN PUBLIC KEY-----"和"-----END PUBLIC KEY-----"
2)demo提供的dinpay_public_key是测试商户号3990010088的公钥，请自行复制对应商户号的公钥进行调整和替换。
3）使用公钥验证时需要调用openssl_verify函数进行验证,需要在php_ini文件里打开php_openssl插件
*/
	$dinpay_public_key ='-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQChHFJ/Io1WjlyCHSh
FLlHkDTDOMKQ9gZOYPEPP9s7OftYg/TEp/d56Rlk5rxkhm9kibLL4Ux
ztf1yPzlEZAUccm1HUxgB0j1asqHPEg0vsR5a+OH9tEawhl8ZB55Ynw
zoQ/sSE8BeRKfmeN22DTYiZNTAAhvxkB/Jc5Mygov/7twIDAQAB 
-----END PUBLIC KEY-----'; 	

?>