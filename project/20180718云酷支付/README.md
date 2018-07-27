第三方名称：云酷支付 
档案位置：php@60.245.62.221/yunkupay 
 
商户号:商户提供的商户号  
商户密钥KEY：秘钥 后台查看  
商户交易帐号：无  
 
支持payment：微信扫码,微信wap,支付宝扫码,支付宝wap  
 
现有渠道：支付宝扫码,支付宝wap  
测试到账：微信扫码(已维护),支付宝扫码,支付宝wap  
  
备注：
微信扫码的return没有参数  

20180727
修改档案：wxpost.php,zfbpost.php,notify_url.php,return_url.php
修改支付宝扫码支付渠道的编号,zfbpc改成zfbsm  
修正异步回调参数错误（商户号前面多一个空格）
新系统档案write1改成read1  
