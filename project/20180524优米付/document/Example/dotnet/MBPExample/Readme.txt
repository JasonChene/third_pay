第一步：
将文件
MobaopayConfig.cs
MobaopayMerchant.cs
MobaopayQueryEntity.cs
MobaopayRefundEntity.cs
MobaopaySignUtil.cs
添加到工程中,并在MobaopayConfig.cs中配置商户号、MD5密钥、接口地址等信息

第二步：
参考normalPay.aspx、pay.aspx完成支付接口调用
参考queryOrd.html，queryOrd.aspx完成查询接口调用
参考refundOrd.html，refundOrd.aspx完成退款接口调用

重要函数说明：
一、签名工具类MobaopaySignUtil，以单例模式提供工具服务，提供签名和验签两个函数。
a. public string sign(string sourceData)
输入源字符串，返回签名数据字符串。

b. public bool verifyData(string signData, string srcData)
输入源字符串和签名字符串，返回验证签名结果。

二、商户辅助工具类MobaopayMerchant
a. public string generatePayRequest(Dictionary<string, string> sourceData)
功能：生成支付交易请求字符串，作为签名函数的输入数据。
输入：sourceData -- 以键值对的形式存放的交易请求数据
输出：由交易请求数据拼接而成的字符串，当然也可以自行拼接。

b. public string generateQueryRequest(Dictionary<string, string> sourceData)
功能：生成查询交易请求字符串，作为签名函数的输入数据。
输入：sourceData -- 以键值对的形式存放的交易请求数据
输出：由交易请求数据拼接而成的字符串，当然也可以自行拼接。

c. public string generateRefundRequest(Dictionary<string, string> sourceData)
功能：生成退款交易请求字符串，作为签名函数的输入数据。
输入：sourceData -- 以键值对的形式存放的交易请求数据
输出：由交易请求数据拼接而成的字符串，当然也可以自行拼接。

d. public string transact(string requestStr, string serverUrl)
功能： 想支付系统发起请求，获取返回数据，对返回数据做验签处理
