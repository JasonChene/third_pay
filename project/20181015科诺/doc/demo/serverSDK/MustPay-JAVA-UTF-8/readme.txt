───────
 代码文件结构
───────

MustPay-JAVA-UTF-8
  │
  ├src┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈类文件夹
  │  │
  │  ├com.mustpay.config
  │  │  │
  │  │  └MustpayConfig.java┈┈┈┈┈基础配置类文件
  │  │
  │  ├com.mustpay.util
  │  │  │
  │  │  ├MustpayCore.java┈┈┈┈┈┈科诺支付接口公用函数类文件
  │  │  │
  │  │  ├MustpayNotify.java┈┈┈┈┈科诺支付通知处理类文件
  │  │  │
  │  │  ├MustpayRequest.java┈┈┈┈┈请求科诺支付平台类文件
  │  │  │
  │  │  └UtilDate.java┈┈┈┈┈┈┈科诺支付自定义订单类文件
  │  │
  │  ├com.mustpay.sign
  │     │
  │     ├RSA.java ┈┈┈┈┈┈┈┈┈RSA签名类文件
  │     │
  │     └Base64.java┈┈┈┈┈┈┈┈RSA密钥转换
  │
  ├WebRoot┈┈┈┈┈┈┈┈┈┈┈┈┈┈页面文件夹
  │  │
  │  ├selPayType.jsp┈┈┈┈┈┈┈┈┈预下单成功，选择支付方式页面
  │  │
  │  ├index.jsp┈┈┈┈┈┈┈┈┈┈┈科诺支付调试入口页面
  │  │
  │  ├notify_url.jsp ┈┈┈┈┈┈┈┈服务器异步通知页面文件
  │  │
  │  └return_url.jsp ┈┈┈┈┈┈┈┈页面跳转同步通知文件
  │  │
  │  └WEB-INF
  │   	  │
  │      └lib（如果JAVA项目中包含这些架包，则不需要导入）
  │
  └readme.txt ┈┈┈┈┈┈┈┈┈使用说明文本


──────────
 出现问题，求助方法
──────────

如果在集成科诺支付接口时，有疑问或出现问题，可使发送您的联系方式到我们的邮箱support@kenuolife.com
我们会有专门的技术支持人员为您处理




