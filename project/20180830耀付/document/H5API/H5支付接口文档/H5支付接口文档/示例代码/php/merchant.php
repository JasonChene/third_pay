<?php
/**
1）merchant_private_key，商户私钥;merchant_public_key,商户公钥；商户需要按照《密钥对获取工具说明》操作并获取商户私钥，商户公钥。
2）demo提供的merchant_private_key、merchant_public_key是测试商户号666007008010的商户私钥和商户公钥，请商家自行获取并且替换；
3）使用商户私钥加密时需要调用到openssl_sign函数,需要在php_ini文件里打开php_openssl插件
4）php的商户私钥在格式上要求换行，如下所示；
*/
		$merchant_private_key='-----BEGIN PRIVATE KEY-----
MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBAM1kayU03wSU+TuYD
v0A3G99MMjUnzhqBE0HUi8xSKviBuB2Gv/ZfrHeK8mdvhn7WwTQzNHBS/2hzJjQkk
VkzL+EDeI+1OGIfcWTtlm1wCGoyhjE35Eff01HGgwTWmO144W4mqjmCzOaobl0qmH
HUYQTKXFPV6mRWJ7CaFf3v7y/AgMBAAECgYBkXOVeUO+JNaJz1GG+j2UntWzZNcx3
rJZdbW5jURnJo7Dojc2zp3uZPo72/fWejIx1VfI/rMyNKzrmkURoVFEXguWqr6xRl
ZFqn2RaweHbsKfbp8BL1iUw8Z//nCUn3M6lmCkldKXJ2iwYppsLRV3pdt1OHV6tVN
LvhPnmlj8nQQJBAP/dsd7/ab3GpbedESHVmP3awf48T+le/BlGGHNCEvDf2o2zx49
EvSoc/Lo54nd9GvXR+dHsseSxHbowcoIZ7G0CQQDNf/TbNI3312swVO7+vjwId+fz
PqEm5b6L5NM7hjeOigM9M8UGng6U7HHh/wgJupVUzERvT2HvSkqLsT+x2DpbAkBy/
yHldugAilqK1sYPbd/QIFTWPicwXSdy+IUesFCw//tLesSzSJK4bcTMsh1t1MWcPB
5K0lX10gDpYMLmZF5VAkEAgk0XIgMx3avPAIdqP0a6ZBg7j+XvYu2cI7IFKiIRiiU
CpsTzsh14W3+NOmJuY1TWqT0YS4gHLiZqHCdYntjfLwJBANbxHhlQMXgHOxh+zX0B
kDIjk3FQW8Z0Rm1kiK9SbZVqEILlotdJltqWnZx1cEKj+2Zdyx4IfQu30CgOuIBcl
a0=
-----END PRIVATE KEY-----';

	//merchant_public_key,商户公钥，按照说明文档上传此密钥到商家后台，位置为"支付设置"->"公钥管理"->"设置商户公钥"，代码中不使用到此变量
	//demo提供的merchant_public_key已经上传到测试商家号后台
	$merchant_public_key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDNZGslNN8ElPk7mA79ANxvfTDI1
J84agRNB1IvMUir4gbgdhr/2X6x3ivJnb4Z+1sE0MzRwUv9ocyY0JJFZMy/hA3iPt
ThiH3Fk7ZZtcAhqMoYxN+RH39NRxoME1pjteOFuJqo5gszmqG5dKphx1GEEylxT1e
pkViewmhX97+8vwIDAQAB
-----END PUBLIC KEY-----';
	
/**
1)wjjjwsz_public_key，平台公钥，每个商家对应一个固定的平台公钥（不是使用工具生成的密钥merchant_public_key，不要混淆），
即为支付汇商家后台"公钥管理"->"平台公钥"里的绿色字符串内容,复制出来之后调成4行（换行位置任意，前面三行对齐），
并加上注释"-----BEGIN PUBLIC KEY-----"和"-----END PUBLIC KEY-----"
2)demo提供的wjjjwsz_public_key是测试商户号666007008010的平台公钥，请自行复制对应商户号的平台公钥进行调整和替换。
3）使用公钥验证时需要调用openssl_verify函数进行验证,需要在php_ini文件里打开php_openssl插件
*/
	$wjjjwsz_public_key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCc7Lq3TKE0JHunYkzN4yu
FKxDU24zDRf76Fhr/dAldQac0yIb+NfhAHn3+M/waJZrjd5ARlI+hnPK/SB
ejL2GH+lduu7ZEzfAXPjhaUWClT/Wqn4BRta94sYAcNvqgVLBzxQUhkGFTG
4u73ZapB3rCoZBa0OK5zRFSc2juaYPukQIDAQAB
-----END PUBLIC KEY-----';





	



?>