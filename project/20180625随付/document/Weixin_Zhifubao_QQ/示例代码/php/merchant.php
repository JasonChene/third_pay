<?php
/**
1）merchant_private_key，商户私钥;merchant_public_key,商户公钥；商户需要按照《密钥对获取工具说明》操作并获取商户私钥，商户公钥。
2）demo提供的merchant_private_key、merchant_public_key是测试商户号100100101102的商户私钥和商户公钥，请商家自行获取并且替换；
3）使用商户私钥加密时需要调用到openssl_sign函数,需要在php_ini文件里打开php_openssl插件
4）php的商户私钥在格式上要求换行，如下所示；
*/
	$merchant_private_key='-----BEGIN PRIVATE KEY-----
MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAOlji/yfZS3DKefg
XJandiPPlheoJeOPgiqdy7WO5kYDxpYIbcmJXp1sIxC0MqY5pyWIOyBlW/NHWDfR
yTIYvFm6vxx9m/TeTYKRXHPNdCefXNeV5XoCB7s7gJoeqR1qwgBE0a2Sw4slMrKL
4LSv0J0o3po9SgYhP06m3az9dZzDAgMBAAECgYEAgLGdvX1/Y06cyvnS1FgfMvwC
vaTAz8yQcEN20vSrUaw/Uvfu4hTXkWLRqyJSU4qy+mU2hVSe8XVD1fPeR7uQD7TW
qmXCaIC12DqWFvA2Eio/x4nIixj5t4PN4lbUVBHH/OnHypRWFNcQemR4OWcYCWi5
/cYshEaOk9XQnUQKvcECQQD7ZBDA+d8IXM7jtlNNEp+ySw87iz8pKhBoVt3XrAkG
czuM9A+ZY+ReViHVxuxqd5AgxSPj1hoQeFR0BnPEVKgzAkEA7ar8kvzp9sPMDujN
9QSr+yq73CTlRr8W5q0vMCxsJagie6znvtsAzka3EkI0Srix5fHjRWrWWknblDat
5CnpMQJAGfYLhOJJFiP/eaSqlZsGwvLdb9jBfQ7LAvt1jYBGrlPYIoZR1hVq0BM1
C06vu479Y2T4f97scib7EcBY1D2p4QJALvdmOEngllI1BhE9ehj1P4o/W46y/FsQ
1H8O3y84I+zo+5W8qjuvGhRhYytQGCANj8pQRkyJlYgY614u2PLrUQJAAv9neGYM
eFdZnanlsdg6EepWAzvT2tv45WF74BQKJzZF+IFtE1XD85QcqkOJ3o8WgVwXK6/B
Cn5vNahImz+Ohg==
-----END PRIVATE KEY-----';

	//merchant_public_key,商户公钥，按照说明文档上传此密钥到支付商家后台，位置为"支付设置"->"公钥管理"->"设置商户公钥"，代码中不使用到此变量
	$merchant_public_key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDYpUAafcYOinYAfuo1ZyBZzJd+
I0d7BoLrw4wIEy06mHfxYZEmzZduK20Sg42eI6gysRRVGxZajHdGLks4RPlLW9Yz
XAUTk8iJ/9jtHApjdLTxWkoC/8sGIV0o09RZQFrhWwVfm4II/Aiw1KUxpt9QaHyQ
P6poPSp/8FtmhfEIzQIDAQAB
	-----END PUBLIC KEY-----';
	
/**
1)dinpay_public_key，支付公钥，每个商家对应一个固定的支付公钥（不是使用工具生成的密钥merchant_public_key，不要混淆），
即为支付商家后台"公钥管理"->"支付公钥"里的绿色字符串内容,复制出来之后调成4行（换行位置任意，前面三行对齐），
并加上注释"-----BEGIN PUBLIC KEY-----"和"-----END PUBLIC KEY-----"
2)demo提供的dinpay_public_key是测试商户号1118004517的支付公钥，请自行复制对应商户号的支付公钥进行调整和替换。
3）使用支付公钥验证时需要调用openssl_verify函数进行验证,需要在php_ini文件里打开php_openssl插件
*/
		$dinpay_public_key ='-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCP2DxiRIhY5JCpeQD
QO9oJJ5iyZ/HviNzTY23N6XEp+P4tacdftFsgwh1DoR4Qa8MI5Lrmb2
WTBlFsybLqM9LYLeBvODokFdAdoi+VOjkESJ4Y8XygiP4Sz7osOwB3M
kvLVHAK8e2O8U09Sj2MI034snHvnhCpU3ye3BqkzetLkQIDAQAB
-----END PUBLIC KEY-----'; 	
	



?>