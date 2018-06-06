using System;
using System.Web;
using System.IO;
using System.Xml;
using System.Xml.XPath;
using System.Xml.Linq;
using System.Text.RegularExpressions;
using DinpayRSAAPI.COM.Dinpay.RsaUtils;

namespace CSharp
{
    public partial class _Default : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {
            try
            {
                /////////////////////////////////接收表单提交参数//////////////////////////////////////
				////////////////////////To receive the parameter form HTML form//////////////////////

                string interface_version = Request.Form["interface_version"].ToString().Trim();
                string input_charset = Request.Form["input_charset"].ToString().Trim();
                string service_type = Request.Form["service_type"].ToString().Trim();
                string sign_type = Request.Form["sign_type"].ToString().Trim();
                string merchant_code = Request.Form["merchant_code"].ToString().Trim();
                string bank_code = Request.Form["bank_code"].ToString().Trim();
                string card_type = Request.Form["card_type"].ToString().Trim();
                string card_no = Request.Form["card_no"].ToString().Trim();
                string mobile = Request.Form["mobile"].ToString().Trim();
                string merchant_sign_id = Request.Form["merchant_sign_id"].ToString().Trim();
                
                ////////////////组装签名/////////////////
                string signStr = "";
                signStr = "bank_code=" + bank_code + "&card_no=" + card_no + "&card_type=" + card_type + "&input_charset=" + input_charset + "&interface_version=" + interface_version + "&merchant_code=" + merchant_code;
                if (merchant_sign_id != "")
                {
                    signStr = signStr + "&merchant_sign_id=" + merchant_sign_id;
                }
                signStr = signStr + "&mobile=" + mobile + "&service_type=" + service_type;


                if (sign_type == "RSA-S")//RSA-S签名方法
                {
                    //商家私钥
                    string merchant_private_key = "MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBALIgluJzrKhOw/+sKlUZW+GFISjeXCqNz45rhEd4pRhg92ZDwyJxsIWVMUggCJLjSAke2wmVOiYJB/V9rNwlCzal5BGCSD0y8VckUb8LMv5wnNxr3wjrXf6IbZWsgNOwZg1mo+Cji5LCwoKvYvbZNK33Nb9MwbBh1PHUVP8AsfM3AgMBAAECgYEAr6oyAtse39Dlu+OWz9u1X/+BhyNa82Bs20Au8KkK77LY6NJUw0gpVGOgeUeWDP31kYELdDTlZpMrdS9eZLBnj/QofFTx7GSeod+vV13cgA6rc0yzjTp25Dm7Xzihf15R5JiNIFzlSYC2TLz+HcJoprxY6Pf6I/1qBjZuoC67eEECQQDjDhEI7s010aXXYQy3xwC/RUDosnfMARqRCpYFCYmoyMiUZ7+ohIvWkkCcwHx7VNKnXfmF0ezdXNT2TCKfXj6hAkEAyNXFKkCPtbg+GFqUlxlfta1s7FJuC1b8ZyaA1ygqUK5PJUoEKR9UcDg0uCKx4Zofpm46WCHx8w8M0+Abss8a1wJAA5JqFDDli44zxLKjJ5T63wdw4PhFyDDQQS3gdE3VG5GlDiifrEABjyuX1p90leAcvENPNJq71jOqqgFCni02YQJAQ8q09SA54lNA0qOwyJhOEFtsCxGAB9/i70a18uqh7f4IxUOIyADFVeQDF6zOcqK90EYg96Ltsuf/on1hnCgAnQJBANGvRflfL1Xvelv2jb446Gnq83IwQ6WJvO8z7/awfMmDsC88MI2bE0xcWJ2QPZZEVJkgCmwOXc26G+z0eei/z/U=";
                    //私钥转换成C#专用私钥
                    merchant_private_key = testOrder.HttpHelp.RSAPrivateKeyJava2DotNet(merchant_private_key);
                    //签名
                    string signData = testOrder.HttpHelp.RSASign(signStr, merchant_private_key);
                    //将signData进行UrlEncode编码
                    signData = HttpUtility.UrlEncode(signData);
                    
                    //组装字符串
                    string para = signStr + "&sign_type=" + sign_type + "&sign=" + signData;
                    //将字符串发送到Dinpay网关
                    string _xml = testOrder.HttpHelp.HttpPost("https://api.zdfmf.com/gateway/api/express", para);

                    //将同步返回的xml中的参数提取出来
                    var el = XElement.Load(new StringReader(_xml));
                    //将XML中的参数逐个提取出来
                    var sign_status1 = el.XPathSelectElement("/response/sign_status");
                    var merchant_sign_id1 = el.XPathSelectElement("/response/merchant_sign_id");
                    //去掉首尾的标签并转换成string
                    string sign_status = Regex.Match(sign_status1.ToString(), "(?<=>).*?(?=<)").Value;
                    string merchant_sign_id2 = Regex.Match(merchant_sign_id1.ToString(), "(?<=>).*?(?=<)").Value;
                    if (sign_status == "0")
                    {
                        Response.Write("已签约");
                        Response.Write(merchant_sign_id2);
                    }
                    if (sign_status == "1")
                    {
                        Response.Write("已解约");
                    }
                    if (sign_status == "2")
                    {
                        var pay_model1 = el.XPathSelectElement("/response/sign_status");
                        string pay_mode = Regex.Match(pay_model1.ToString(), "(?<=>).*?(?=<)").Value;
                        if (pay_mode == "0")
                    {
                        Response.Write("API签约");
                    }
                        else{
                            Response.Write("WEB网页签约");
                        }
                    }

                }
                else  //RSA签名方法
                {
                    RSAWithHardware rsa = new RSAWithHardware();
                    string merPubKeyDir = "D:/108008008666.pfx";   //证书路径
                    string password = "87654321";                //证书密码
                    RSAWithHardware rsaWithH = new RSAWithHardware();
                    rsaWithH.Init(merPubKeyDir, password, "D:/dinpayRSAKeyVersion");//初始化(version路径需跟证书一致，证书会自动生成version)
                    string signData = rsaWithH.Sign(signStr);    //签名
                    signData = HttpUtility.UrlEncode(signData);  //将signData进行UrlEncode编码

                    //组装字符串
                    string para = signStr + "&sign_type=" + sign_type + "&sign=" + signData;
                    //将字符串发送到Dinpay网关
                    string _xml = testOrder.HttpHelp.HttpPost("https://api.zdfmf.com/gateway/api/express", para);

                    //将同步返回的xml中的参数提取出来
                    var el = XElement.Load(new StringReader(_xml));
                    //将XML中的参数逐个提取出来
                    var sign_status1 = el.XPathSelectElement("/response/sign_status");
                    var merchant_sign_id1 = el.XPathSelectElement("/response/merchant_sign_id");
                    //去掉首尾的标签并转换成string
                    string sign_status = Regex.Match(sign_status1.ToString(), "(?<=>).*?(?=<)").Value;
                    string merchant_sign_id2 = Regex.Match(merchant_sign_id1.ToString(), "(?<=>).*?(?=<)").Value;
                    if (sign_status == "0")
                    {
                        Response.Write("已签约");
                        Response.Write(merchant_sign_id2);
                    }
                    if (sign_status == "1")
                    {
                        Response.Write("已解约");
                    }
                    if (sign_status == "2")
                    {
                        var pay_model1 = el.XPathSelectElement("/response/sign_status");
                        string pay_mode = Regex.Match(pay_model1.ToString(), "(?<=>).*?(?=<)").Value;
                        if (pay_mode == "0")
                        {
                            Response.Write("API签约");
                        }
                        else
                        {
                            Response.Write("WEB网页签约");
                        }
                    }

                }
            }
			finally{
            }
        }
    }
}

