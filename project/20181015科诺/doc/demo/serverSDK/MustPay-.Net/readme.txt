
───────
 代码文件结构
───────
MustPay-CSHARP-UTF-8
│
│  Mustpay_Notify.aspx     ┈服务器异步通知页面文件
│  Mustpay_Notify.aspx.cs  ┈服务器异步通知后台代码
│  Mustpay_Return.aspx     ┈页面跳转同步通知页面文件
│  Mustpay_Return.aspx.cs  ┈页面跳转同步通知后台代码
│  Index.aspx       ┈┈┈┈┈┈┈MustPay调试入口页面
│  Index.aspx.cs    ┈┈┈┈┈┈┈MustPay调试入口后台代码
│  selPayType.aspx   ┈┈┈┈┈┈预下单成功，选择支付方式页面
│  selPayType.aspx.cs┈┈┈┈┈┈预下单成功，选择支付方式后台代码
│  readme.txt      ┈使用说明文本
├─App_Code
│      MustpayCore.cs     ┈┈┈┈┈┈┈┈MustPay接口公用函数类文件
│      MustpayNotify.cs   ┈┈┈┈┈┈┈┈MustPay通知处理类文件
│      RSA.cs            ┈┈┈┈┈┈┈┈RSA类库
│      
│      
├─bin   
│      Newtonsoft.Json.dll	┈┈┈┈JSON dll类库
│      
├─config
│      ConvertJson.cs ┈┈┈┈JSON 转换类库
│      PayConfig.cs   ┈┈┈┈┈基础配置类文件
│      
├─log
│      log.txt        ┈┈┈┈┈日志文件
│      
│      
└─Util               ┈┈┈┈┈公用类库（调用涉及的方法见selPayType.aspx.cs）

──────────
 出现问题，求助方法
──────────

如果在集成MustPay接口时，有疑问或出现问题，可使发送您的联系方式到我们的邮箱support@mustpay.com.cn
我们会有专门的技术支持人员为您处理

