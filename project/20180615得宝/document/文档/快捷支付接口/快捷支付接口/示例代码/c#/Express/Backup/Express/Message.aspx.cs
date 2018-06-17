using System;
using System.Web;
using System.IO;
using System.Xml;
using System.Xml.XPath;
using System.Xml.Linq;
using System.Text.RegularExpressions;
using DinpayRSAAPI.COM.Dinpay.RsaUtils;

namespace CSharpTest
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
            string order_no = Request.Form["order_no"].ToString().Trim();
            string order_amount = Request.Form["order_amount"].ToString().Trim();
            string sms_type = Request.Form["sms_type"].ToString().Trim();
            string send_type = Request.Form["send_type"].ToString().Trim();
            string merchant_sign_id = Request.Form["merchant_sign_id"].ToString().Trim();
            string card_type = Request.Form["card_type"].ToString().Trim();
            string mobile = Request.Form["mobile"].ToString().Trim();
            string bank_code = Request.Form["bank_code"].ToString().Trim();

            string card_no = Request.Form["card_no"].ToString().Trim();
            string card_name = Request.Form["card_name"].ToString().Trim();
            string id_no = Request.Form["id_no"].ToString().Trim();
            string card_cvv2 = Request.Form["card_cvv2"].ToString().Trim();
            string card_exp_date = Request.Form["card_exp_date"].ToString().Trim();
            string encrypt_info = card_no + "|" + card_name + "|" + id_no; //组装敏感数据
            ////使用中鼎融公钥对卡号和卡密加密【中鼎融公钥需从商家后台-公钥管理中取出】//////////
            string encryption_key = "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDTE8YBexrEmn8oEqsASVgkZEUo/WTqKZlmr0MYDyIVgcNfvXJPUR9kD46RAT11UYKK681UI0IWcfi/uB+bL00bVzuW7x5YdT5zdDuca/i3H3MIbWMcAHXAqPQt38Z0yWoXoCJp0IZ975vBVSe/a70M7uh1aLSapQFKyUCO2i3hGwIDAQAB";
            //////////将公钥转换成C#专用格式///////////
            encryption_key = testOrder.HttpHelp.RSAPublicKeyJava2DotNet(encryption_key);
            //加密后的卡号密码
            string encrypt_info_result = testOrder.HttpHelp.RSAEncrypt(encrypt_info, encryption_key);
            ////////////////组装签名/////////////////
            string signStr = "";
            if (bank_code != "")
            {
                signStr = signStr + "bank_code=" + bank_code;
            }
            if (card_type != "")
            {
                signStr = signStr + "&card_type=" + card_type;
            }
            if (bank_code != "")
            {
                signStr = signStr + "&encrypt_info=" + encrypt_info + "&";
            }
            signStr = signStr + "input_charset=" + input_charset + "&interface_version=" + interface_version + "&merchant_code=" + merchant_code;
            if (merchant_sign_id != "")
            {
                signStr = signStr + "&merchant_sign_id=" + merchant_sign_id;
            }
            signStr = signStr + "&mobile=" + mobile + "&order_amount=" + order_amount + "&order_no=" + order_no + "&send_type=" + send_type + "&service_type=" + service_type + "&sms_type=" + sms_type;

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
                //将加密后的卡号卡密进行UrlEncode编码
                encrypt_info_result = HttpUtility.UrlEncode(encrypt_info_result);

                //组装字符串
                string para = signStr + "&sign_type=" + sign_type + "&sign=" + signData;
                //将字符串发送到Dinpay网关
                string _xml = testOrder.HttpHelp.HttpPost("https://api.yuanruic.com/gateway/api/express", para);

                //将同步返回的xml中的参数提取出来
                var el = XElement.Load(new StringReader(_xml));
                //将XML中的参数逐个提取出来
                var is_success1 = el.XPathSelectElement("/response/is_success");
                var merchant_code1 = el.XPathSelectElement("/response/merchant_code");
                var order_no1 = el.XPathSelectElement("/response/order_no");
                var sms_trade_no1 = el.XPathSelectElement("/response/sms_trade_no");
                var dinpaysign1 = el.XPathSelectElement("/response/sign");
                //去掉首尾的标签并转换成string
                string is_success = Regex.Match(is_success1.ToString(), "(?<=>).*?(?=<)").Value;
                if (is_success == "F")
                {
                    Response.Write("获取失败");
                    Response.End();
                }
                string merchant_code2 = Regex.Match(merchant_code1.ToString(), "(?<=>).*?(?=<)").Value;
                string order_no2 = Regex.Match(order_no1.ToString(), "(?<=>).*?(?=<)").Value;
                string sms_trade_no2 = Regex.Match(sms_trade_no1.ToString(), "(?<=>).*?(?=<)").Value;
                string dinpaysign = Regex.Match(dinpaysign1.ToString(), "(?<=>).*?(?=<)").Value;
                //组装验签字符串
                string signsrc = "is_success=" + is_success + "&merchant_code=" + merchant_code2 + "&order_no=" + order_no2 + "&sms_trade_no=" + sms_trade_no2;
                //使用中鼎融公钥对返回的数据验签
                bool validateResult = testOrder.HttpHelp.ValidateRsaSign(signsrc, encryption_key, dinpaysign);

                if (validateResult == false)
                {
                    Response.Write("验签失败");
                    Response.End();
                }
                Response.Write("短信验证码流水号:" + sms_trade_no2 + "<br/>");
                Response.Write("验签结果:" + validateResult + "<br/>");
                //Response.End()
            }

            else //RSA签名方法
            {
                RSAWithHardware rsa = new RSAWithHardware();
                string merPubKeyDir = "D:/108008008666.pfx";   //证书路径
                string password = "87654321";                //证书密码
                RSAWithHardware rsaWithH = new RSAWithHardware();
                rsaWithH.Init(merPubKeyDir, password, "D:/dinpayRSAKeyVersion");//初始化(version路径需跟证书一致，证书会自动生成version)
                string signData = rsaWithH.Sign(signStr);    //签名
                signData = HttpUtility.UrlEncode(signData);  //将signData进行UrlEncode编码
                //将加密后的卡号卡密进行UrlEncode编码
                encrypt_info_result = HttpUtility.UrlEncode(encrypt_info_result);

                //组装字符串
                string para = signStr + "&sign_type=" + sign_type + "&sign=" + signData;
                //将字符串发送到Dinpay网关
                string _xml = testOrder.HttpHelp.HttpPost("https://api.yuanruic.com/gateway/api/express", para);

                //将同步返回的xml中的参数提取出来
                var el = XElement.Load(new StringReader(_xml));
                //将XML中的参数逐个提取出来
                var is_success1 = el.XPathSelectElement("/response/is_success");
                var merchant_code1 = el.XPathSelectElement("/response/merchant_code");
                var order_no1 = el.XPathSelectElement("/response/order_no");
                var sms_trade_no1 = el.XPathSelectElement("/response/sms_trade_no");
                var dinpaysign1 = el.XPathSelectElement("/response/sign");
                //去掉首尾的标签并转换成string
                string is_success = Regex.Match(is_success1.ToString(), "(?<=>).*?(?=<)").Value;
                if (is_success == "F")
                {
                    Response.Write("获取失败");
                    Response.End();
                }
                string merchant_code2 = Regex.Match(merchant_code1.ToString(), "(?<=>).*?(?=<)").Value;
                string order_no2 = Regex.Match(order_no1.ToString(), "(?<=>).*?(?=<)").Value;
                string sms_trade_no2 = Regex.Match(sms_trade_no1.ToString(), "(?<=>).*?(?=<)").Value;
                string dinpaysign = Regex.Match(dinpaysign1.ToString(), "(?<=>).*?(?=<)").Value;
                //组装验签字符串
                string signsrc = "is_success=" + is_success + "&merchant_code=" + merchant_code2 + "&order_no=" + order_no2 + "&sms_trade_no=" + sms_trade_no2;
                //RSA验签
                bool result = rsaWithH.VerifySign("108008008666", signsrc, dinpaysign);
                if (result == false)
                {
                    Response.Write("验签失败");
                    Response.End();
                }
                Response.Write("短信验证码流水号:" + sms_trade_no2 + "<br/>");
                Response.Write("验签结果:" + result + "<br/>");
                //Response.End()
            }

            }
			finally{
            }
        }
    }
}