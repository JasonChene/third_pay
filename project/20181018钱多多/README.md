第三方名称：钱多多
档案位置：php@60.245.62.221\qianduoduo

商户号：商户提供的商户号
商户密钥 KEY：MD5 密钥
商户交易帐号：机构号

支持payment：网银,银联扫码,银联快捷,微信扫码,微信手机,支付宝扫码,支付宝手机,QQ扫码,QQ手机,京东扫码

现有渠道：支付宝扫码,支付宝手机,网银,银联快捷
测试到账：支付宝扫码,网银

20181026  
修改档案：post,qqpost,wxpost,wxqqjdpost,zfbpost,return_url  

测试到帐：支付宝扫码  

备注：  
第三方修改网关  
修正return_url判断  

20181028  
修改档案：post,bank_parameter  

测试：网银,银联钱包可跳转支付页面  

备注：  
网银,银联多传银行参数  
新增bank_parameter档案
网银,银联钱包最低金额限制30元  

20181030  
修改档案：wxpost,wxqqjdpost,bank_parameter  

测试：网银,微信扫码,微信手机可跳转支付页面  

备注：  
第三方修改银行代码  
微信没有H5通道,手机端改接微信扫码通道  
微信扫码响应URL包含符号,改为urlencode再转成二维码  