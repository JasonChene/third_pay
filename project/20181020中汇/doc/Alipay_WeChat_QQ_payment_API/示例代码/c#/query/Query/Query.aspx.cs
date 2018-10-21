using System;
using System.Web;
using System.Text;
using System.IO;
using System.Xml;
using System.Xml.XPath;
using System.Xml.Linq;
using System.Text.RegularExpressions;
using DinpayRSAAPI.COM.Dinpay.RsaUtils;



namespace testOrderQuery
{
    public partial class getXmlData : System.Web.UI.Page
    {      
        protected void Page_Load(object sender, EventArgs e)
        {
            try
            {
				/////////////////////////////////接收表单提交参数//////////////////////////////////////
				////////////////////////To receive the parameter form HTML form//////////////////////

                string merchant_code = Request.Form["merchant_code"].ToString().Trim();

                string service_type = Request.Form["service_type"].ToString().Trim();

                string sign_type = Request.Form["sign_type"].ToString().Trim();

                string interface_version = Request.Form["interface_version"].ToString().Trim();

                string order_no = Request.Form["order_no"].ToString().Trim();
   

				/////////////////////////////   数据签名  /////////////////////////////////
				////////////////////////////  Data signature  ////////////////////////////
                string signStr = "interface_version=" + interface_version + "&merchant_code=" + merchant_code + "&order_no=" + order_no + "&service_type=" + service_type ;

                if (sign_type == "RSA-S") //RSA-S签名方法
                {
                    //商家私钥
                    string merchant_private_key = "MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBALIgluJzrKhOw/+sKlUZW+GFISjeXCqNz45rhEd4pRhg92ZDwyJxsIWVMUggCJLjSAke2wmVOiYJB/V9rNwlCzal5BGCSD0y8VckUb8LMv5wnNxr3wjrXf6IbZWsgNOwZg1mo+Cji5LCwoKvYvbZNK33Nb9MwbBh1PHUVP8AsfM3AgMBAAECgYEAr6oyAtse39Dlu+OWz9u1X/+BhyNa82Bs20Au8KkK77LY6NJUw0gpVGOgeUeWDP31kYELdDTlZpMrdS9eZLBnj/QofFTx7GSeod+vV13cgA6rc0yzjTp25Dm7Xzihf15R5JiNIFzlSYC2TLz+HcJoprxY6Pf6I/1qBjZuoC67eEECQQDjDhEI7s010aXXYQy3xwC/RUDosnfMARqRCpYFCYmoyMiUZ7+ohIvWkkCcwHx7VNKnXfmF0ezdXNT2TCKfXj6hAkEAyNXFKkCPtbg+GFqUlxlfta1s7FJuC1b8ZyaA1ygqUK5PJUoEKR9UcDg0uCKx4Zofpm46WCHx8w8M0+Abss8a1wJAA5JqFDDli44zxLKjJ5T63wdw4PhFyDDQQS3gdE3VG5GlDiifrEABjyuX1p90leAcvENPNJq71jOqqgFCni02YQJAQ8q09SA54lNA0qOwyJhOEFtsCxGAB9/i70a18uqh7f4IxUOIyADFVeQDF6zOcqK90EYg96Ltsuf/on1hnCgAnQJBANGvRflfL1Xvelv2jb446Gnq83IwQ6WJvO8z7/awfMmDsC88MI2bE0xcWJ2QPZZEVJkgCmwOXc26G+z0eei/z/U=";
                    //私钥转换成C#专用私钥
                    merchant_private_key = testOrderQuery.HttpHelp.RSAPrivateKeyJava2DotNet(merchant_private_key);
                    //签名
                    string signData = testOrderQuery.HttpHelp.RSASign(signStr, merchant_private_key);
                    //将signData进行UrlEncode编码
                    signData = HttpUtility.UrlEncode(signData);
                    //组装字符串
                    string para = signStr + "&sign_type=" + sign_type + "&sign=" + signData;
                    //用HttpPost方式提交
                    string _xml = HttpHelp.HttpPost("https://query.vsdpay.com/query", para);
                    //将返回的xml中的参数提取出来
                    var el = XElement.Load(new StringReader(_xml));
                    //提取参数
                    var is_success1 = el.XPathSelectElement("/response/is_success");
                    var merchantcode1 = el.XPathSelectElement("/response/trade/merchant_code");
                    var orderno1 = el.XPathSelectElement("/response/trade/order_no");
                    var ordertime1 = el.XPathSelectElement("/response/trade/order_time");
                    var orderamount1 = el.XPathSelectElement("/response/trade/order_amount");
                    var trade_no1 = el.XPathSelectElement("/response/trade/trade_no");
                    var trade_time1 = el.XPathSelectElement("/response/trade/trade_time");
                    var zhihfsign1 = el.XPathSelectElement("/response/sign");
                    var trade_status1 = el.XPathSelectElement("/response/trade/trade_status");
                    //去掉首尾的标签并转换成string
                    string is_success = Regex.Match(is_success1.ToString(), "(?<=>).*?(?=<)").Value; //不参与验签
                    if (is_success == "F")
                    {
                        Response.Write("查询失败:" + _xml + "<br/>");
                        Response.End();
                    }
                    string merchantcode = Regex.Match(merchantcode1.ToString(), "(?<=>).*?(?=<)").Value;
                    string orderno = Regex.Match(orderno1.ToString(), "(?<=>).*?(?=<)").Value;
                    string ordertime = Regex.Match(ordertime1.ToString(), "(?<=>).*?(?=<)").Value;
                    string orderamount = Regex.Match(orderamount1.ToString(), "(?<=>).*?(?=<)").Value;
                    string trade_no = Regex.Match(trade_no1.ToString(), "(?<=>).*?(?=<)").Value;
                    string trade_time = Regex.Match(trade_time1.ToString(), "(?<=>).*?(?=<)").Value;
                    string trade_status = Regex.Match(trade_status1.ToString(), "(?<=>).*?(?=<)").Value;
                    string zhihfsign = Regex.Match(zhihfsign1.ToString(), "(?<=>).*?(?=<)").Value;
                    //组装字符串
                    string signsrc = "merchant_code=" + merchantcode + "&order_amount=" + orderamount + "&order_no=" + orderno + "&order_time=" + ordertime + "&trade_no=" + trade_no + "&trade_status=" + trade_status + "&trade_time=" + trade_time;
                    //使用公钥对数据验签
                    string dinpayPubKey = "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC9R4Md8mcLZoSMQUuDLD7f1Rau7x+yfAsvmzPWyc98uI/ZwBbVuS3lGZk+YXy1Kwk+UywDr8vy3o3siymxW8XBzYFYR6CNWl6CEwfa1PwwoyefGH+7P/SVz9XZ+wJR/3fQ8JurscZmVQHrYUOqcCMUPyohzN2FTCz8oWbF3uQ1NwIDAQAB";
                    //将公钥转换成C#专用格式
                    dinpayPubKey = testOrderQuery.HttpHelp.RSAPublicKeyJava2DotNet(dinpayPubKey);
                    //验签
                    bool validateResult = testOrderQuery.HttpHelp.ValidateRsaSign(signsrc, dinpayPubKey, zhihfsign);
                    if (validateResult == false)
                    {
                        Response.Write("验签失败");
                        Response.End();
                    }
                    Response.Write("验签成功");
                }
                else  //RSA签名方法
                {
                    RSAWithHardware rsa = new RSAWithHardware();
                    string merPubKeyDir = "D:/123001002003.pfx";   //证书路径
                    string password = "87654321";                //证书密码
                    RSAWithHardware rsaWithH = new RSAWithHardware();
                    rsaWithH.Init(merPubKeyDir, password, "D:/dinpayRSAKeyVersion");//初始化。设置dinpayRSAKeyVersion的路径与证书路径一致即可
                    string signData = rsaWithH.Sign(signStr);    //签名
                    signData = HttpUtility.UrlEncode(signData);  //将signData进行UrlEncode编码
                    //组装字符串
                    string para = signStr + "&sign_type=" + sign_type + "&sign=" + signData;
                    //用HttpPost方式提交
                    string _xml = HttpHelp.HttpPost("https://query.vsdpay.com/query", para);
                    //将返回的xml中的参数提取出来
                    var el = XElement.Load(new StringReader(_xml));
                    //提取参数
                    var is_success1 = el.XPathSelectElement("/response/is_success");
                    var merchantcode1 = el.XPathSelectElement("/response/trade/merchant_code");
                    var orderno1 = el.XPathSelectElement("/response/trade/order_no");
                    var ordertime1 = el.XPathSelectElement("/response/trade/order_time");
                    var orderamount1 = el.XPathSelectElement("/response/trade/order_amount");
                    var trade_no1 = el.XPathSelectElement("/response/trade/trade_no");
                    var trade_time1 = el.XPathSelectElement("/response/trade/trade_time");
                    var zhihfsign1 = el.XPathSelectElement("/response/sign");
                    var trade_status1 = el.XPathSelectElement("/response/trade/trade_status");
                    //去掉首尾的标签并转换成string
                    string is_success = Regex.Match(is_success1.ToString(), "(?<=>).*?(?=<)").Value; //不参与验签
                    if (is_success == "F")
                    {
                        Response.Write("查询失败:" + _xml + "<br/>");
                        Response.End();
                    }
                    string merchantcode = Regex.Match(merchantcode1.ToString(), "(?<=>).*?(?=<)").Value;
                    string orderno = Regex.Match(orderno1.ToString(), "(?<=>).*?(?=<)").Value;
                    string ordertime = Regex.Match(ordertime1.ToString(), "(?<=>).*?(?=<)").Value;
                    string orderamount = Regex.Match(orderamount1.ToString(), "(?<=>).*?(?=<)").Value;
                    string trade_no = Regex.Match(trade_no1.ToString(), "(?<=>).*?(?=<)").Value;
                    string trade_time = Regex.Match(trade_time1.ToString(), "(?<=>).*?(?=<)").Value;
                    string trade_status = Regex.Match(trade_status1.ToString(), "(?<=>).*?(?=<)").Value;
                    string zhihfsign = Regex.Match(zhihfsign1.ToString(), "(?<=>).*?(?=<)").Value;
                    //组装字符串
                    string signsrc = "merchant_code=" + merchantcode + "&order_amount=" + orderamount + "&order_no=" + orderno + "&order_time=" + ordertime + "&trade_no=" + trade_no + "&trade_status=" + trade_status + "&trade_time=" + trade_time;
                    bool result = rsaWithH.VerifySign("123001002003", signsrc, zhihfsign);
                    if (result == false)
                    {
                        Response.Write("验签失败");
                        Response.End();
                    }
                    Response.Write("验签成功");
                }


            }
            finally
            {
            }
        }  
    }
}