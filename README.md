## 第三方项目建立
> 版本建立：20180530   
> 工作项目google云端：[连结](https://drive.google.com/drive/folders/1Our7MFWXDpN-AzDJJf-_K-1fyNoJ8SKe?usp=sharing "工作项目云端硬碟")  
> 远程测试机说明：[连结](https://drive.google.com/file/d/1OfUeWfF0fOuWOwy6_HE3XQ5FVv9lZJvo/view?usp=sharing "远程测试机说明")

------

## 目录
* [测试帐密](#)
* [工作流程表](#pdf)
    * [开发表、时间表](#2)
    * [命名规则](#4)
    * [加密工具、检查表](#10)
    * [日志](#13)
* [已完成第三方](#finish)

------
### 测试帐密

    测试网站：http://7k111.com  
    帐：123jia 密：123123  
    gmail帐号：7kdisanfang@gmail.com 密码：qsc121212  
    QQ邮箱帐号：3232635131@qq.com 密码：ccq2015313  

### **工作流程表：[PDF连结](https://drive.google.com/file/d/1GpSDihV9nMx8t95wnBPZLRlvzztAU2Z8/view?usp=sharing "工作流程表")**
#### **1、接到 API 文档或 DEMO 档，以及商户资料，开始开发第三方**
#### **2、开发表及时间表建立专案资料**
> 开发表：[连结](https://docs.google.com/spreadsheets/d/1l0Unt3LX2LDw9WRLYwj0VtF7OcNGpwc9GzrqG5gjwig/edit?usp=sharing "开发表")  
> 时间表：[连结](https://drive.google.com/drive/folders/14Qe0QBT0RGe4sUbPuMA7UT--NYTiuGZT?usp=sharing "时间表")  

#### **3、开始 Coding，详细阅读第三方 API 文档**
#### **4、第三方档案命名规则。**
> * post.php： `网银、银联钱包、银联快捷`
> * wxpost.php：`微信扫码、微信 h5(wap)、微信反扫、京东扫码、京东 h5(wap)、百度扫码、百度 h5(wap)`
> * zfbpost.php：`支付宝扫码、支付宝 h5(wap)、支付宝反扫`
> * qqpost.php：`QQ 钱包扫码、QQ 钱包 h5(wap)、QQ 钱包反扫`
> * wxqqjdbdpost.php：`wxqqjdbdpost.php:微信扫码、微信 h5(wap)、微信反扫`
>   `京东扫码、京东 h5(wap)、百度扫码、百度 h5(wap)、QQ 钱包扫码、QQ 钱包 h5(wap)`
> * notify_url.php：`异步通知`
> * return_url.php：`同步通知`
> * bank_parameter.txt：`网银银行代码`  

#### **5、按照 common 版本新增修改**
>**Post系列：**  
>>#function：放会调用到的函式  
>>#获取第三方资料：`(非必要不更动)`   
>>#固定参数设置：`$form_url、$top_uid、$order_no、$mymoney`    
>>#第三方参数设置  
>>#变更参数设置  
>>#新增至资料库：`確認訂單有無重複，function在moneyfunc.php里（非必要不更动）`  
>>#签名排列,可自行组字串或使用```http_build_query($array)//php```  
>>#curl 获取响应值    
>>#跳转 qrcode    
>>#html 跳轉      

>**Notify & Return：**   
>>注解必须留着  
>>#接收资料着 log 档  
>>#设定固定参数：
>>`$order_no、$mymoney、$success_msg、$success_code、$sign、$echo_msg`    
>>#根据订单号读取资料库：`(非必要不更动)`    
>>#获取该订单的支付名称：`(非必要不更动)`   
>>#验签方式   
>>#到账判断(非必要不更动)   
>>最后 write_log 必须注解   

#### **6、详细检查第三方要传的参数、顺序、加密方式，不可任意变动**  
#### **7、使用线上工具比对是否跟文档相符**  
#### **8、错误时，解读回调代码，查询 API 文档，比对自己报文，加密字串是否出错**  
#### **9、无法解决，透过 qq 群组在技术群询问，并具体指出错误代码。**
#### **10、比对第三方检查表，检查程式有没有问题**
>线上加解密工具：[连结](http://tool.chacuo.net/cryptrsapubkey "线上加解密工具")  
>第三方检查表：[连结](https://docs.google.com/document/d/1ECe7qOE-a6-1CJwIrE7_DM3nYf1lMF4aL9Ydz3xMg5I/edit?usp=sharing "第三方检查表")  

#### **11、异步无法测试时，试着模拟异步通知**
>* `使用第三方提供的异步报文`
>* 用 Postman 模拟异步通知
>* 依照文档 coding 完整个 notify.php
>* 保留 log 档查看
>* 再用第三方提供的 sign 比对我们的 mysign,确定签名成功

#### **12、Coding 结束，测试是否到账，log 档保留查看**
#### **13、到账后,填写日志**

>`第三方名称：`爱读付练习(idupay)  
>`档案位置：`php@60.245.31.1\idupay  
>  
>`商户号：`商户提供的商户号  
>`商户密钥KEY：`支付秘钥  
>`商户交易帐号：`支付key  
>  
>`支持payment：`网银,银联快捷,银联钱包扫码,银联钱包手机,微信扫码,微信手机,支付宝扫码,支付宝手机,QQ扫码,QQ手机,京东扫码,京东手机    
>  
>`现有渠道：`网银,银联钱包扫码（最低10元）,微信手机（京东包装）,支付宝手机（最低50元）,QQ扫码,QQ手机,京东扫码,京东手机     
>`问题渠道：`银联快捷（支付方式不存在,维护）,银联钱包手机（维护）,支付宝扫码（子商户号未找到,维护）,微信扫码（京东包装,维护）   
>`测试到账：`网银   
>  
>`备注：`支付宝手机（最低50元50 100 200  300 500   600   800  900  1000  1500  2000）   
>  
>`详细问题叙述：`  
>1.微信扫码支付(维护)代码:9997 信息:渠道错误!  
>2.支付宝扫码支付(维护)代码:9997 信息:子商户号失效或未找到。  
>3.银联WAP支付(维护)  
>{ra_Status=101,rb_Code=99999,rc_CodeMsg=系统错误}  
>4.快捷支付(维护)代码信息:系统异常,请稍后再试  
>5.B2C支付銀行未成功:工商、光大、华夏、平安、上海、浦发、兴业  
>6.京东WAP(维护)代码:9998 信息:用户费率为空！  

#### **14、上缴资料，修改为检查。**

-----

### **Finish已完成第三方**
>*  [all_pay_2.0v](http://pay.bb5678.net/alex/third_pay/tree/master/all_pay_2.0v "all_pay_2.0v")   
>*  [20180524优米付-Alex](http://pay.bb5678.net/alex/third_pay/tree/master/project/20180524%E4%BC%98%E7%B1%B3%E4%BB%98-Alex) 
>*  [20180527香蕉支付-Alex](http://pay.bb5678.net/alex/third_pay/tree/master/project/20180527%E9%A6%99%E8%95%89%E6%94%AF%E4%BB%98-Alex) 
>*  [20180528爱读付-Alex](http://pay.bb5678.net/alex/third_pay/tree/master/project/20180528%E7%88%B1%E8%AF%BB%E4%BB%98-Alex) 


------

[回到顶部](#)

