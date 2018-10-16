───────
 代码文件结构
───────

MustPay-PHP-UTF-8
  │
  ├lib┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈类文件夹
  │  │
  │  ├MustpayCore.function.php┈┈┈┈┈┈MustPay接口公用函数文件
  │  │
  │  ├MustpayNotify.class.php┈┈┈┈┈MustPay通知处理类文件
  │  │
  │  ├MustpaySubmit.class.php┈┈┈┈┈请求MustPay平台类文件
  │  │
  │  └MustpayRsa.function.php┈┈┈┈┈┈┈MustPay  RSA签名函数文件
  │
  ├selPayType.php┈┈┈┈┈┈┈┈┈预下单成功，选择支付方式页面
  │
  ├MustpayConfig.php┈┈┈┈┈┈┈┈┈┈┈MustPay配置文件
  │
  ├index.php┈┈┈┈┈┈┈┈┈┈┈MustPay调试入口页面
  │
  ├query.php┈┈┈┈┈┈┈┈┈┈┈MustPay订单查询入口页面
  │
  ├notify_url.php ┈┈┈┈┈┈┈┈服务器异步通知页面文件
  │
  ├return_url.php ┈┈┈┈┈┈┈┈页面跳转同步通知文件
  │
  ├cacert.pem ┈┈┈┈┈┈┈┈安全证书
  │
  ├log.txt ┈┈┈┈┈┈┈┈开启后用作记录日志文档
  │
  └readme.txt ┈┈┈┈┈┈┈┈说明文档



──────────
 出现问题，求助方法
──────────

如果在集成MustPay接口时，有疑问或出现问题，可使发送您的联系方式到我们的邮箱support@mustpay.com.cn
我们会有专门的技术支持人员为您处理




