第三方名称：新衫德  
档案位置：php@60.245.31.1/xinshande

商户号:商户提供的商户号  
商户密钥 KEY：pfx 私钥转译
商户交易帐号：cer 公钥转译

支持 payment：网银,银联钱包扫码,银联钱包反扫,银联快捷,微信扫码,微信 h5,京东扫码,QQ 扫码,支付宝扫码

现有渠道：网银,银联钱包扫码,银联钱包反扫,银联快捷,微信扫码,微信 h5,京东扫码,QQ 扫码,支付宝扫码
维护渠道：
测试到账：网银

備註:
pfx 档请上https://www.chinassl.net/ssltools/convert-ssl.html转成pem
然后取第一个-----BEGIN PRIVATE KEY-----私钥,放到'商户密钥 KEY'
cer 档请使用指令 openssl x509 -inform der -in sand.cer -out sand.pem
打开 sand.pem 里面的公钥放到'商户交易帐号'

20180717
第三方名称：新衫德  
档案位置：php@60.245.31.1/xinshande

修改档案：post.php  
银联钱包,网银修改为 D0

20181022
金额单位错误，应该是商户报备没有填对异步通知地址
同步通知回来的单位是元
