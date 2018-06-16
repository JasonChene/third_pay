using System;
using System.Web;
using System.IO;
using System.Xml;
using System.Xml.XPath;
using System.Xml.Linq;
using System.Text.RegularExpressions;
using DinpayRSAAPI.COM.Dinpay.RsaUtils;

namespace CS
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
                string order_time = Request.Form["order_time"].ToString().Trim();
                string notify_url = Request.Form["notify_url"].ToString().Trim();
                string merchant_sign_id = Request.Form["merchant_sign_id"].ToString().Trim();
                string card_type = Request.Form["card_type"].ToString().Trim();
                string mobile = Request.Form["mobile"].ToString().Trim();
                string sms_trade_no = Request.Form["sms_trade_no"].ToString().Trim();
                string sms_code = Request.Form["sms_code"].ToString().Trim();
                string bank_code = Request.Form["bank_code"].ToString().Trim();
                string product_name = Request.Form["product_name"].ToString().Trim();
                string product_code = Request.Form["product_code"].ToString().Trim();
                string product_num = Request.Form["product_num"].ToString().Trim();
                string product_desc = Request.Form["product_desc"].ToString().Trim();

                string card_no = Request.Form["card_no"].ToString().Trim();
                string card_name = Request.Form["card_name"].ToString().Trim();
                string id_no = Request.Form["id_no"].ToString().Trim();
                string card_cvv2 = Request.Form["card_cvv2"].ToString().Trim();
                string card_exp_date = Request.Form["card_exp_date"].ToString().Trim();
                string encrypt_info = card_no + "|" + card_name + "|" + id_no; //组装敏感数据
                ////使用公钥对卡号和卡密加密【公钥需从商家后台-公钥管理中取出】//////////
                string encryption_key = "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCblMocFL3j45rvS03WwoIQGCUNzaQoClC6uoKq62eXoSrAmYTZKZELphGU6daTFZrWfIj7/6bCL+f00EshMsP6OMNOw4f5I/R/DVnfTiWicckl1Qi8gPCIXpxvY1e90rRvQT2HfpZUStTifdAUmxm9V8cACTlCFiEo9TgDCmOBFwIDAQAB";

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
                signStr = signStr  + "input_charset=" + input_charset + "&interface_version=" + interface_version + "&merchant_code=" + merchant_code;
                if (merchant_sign_id != "")
                {
                    signStr = signStr + "&merchant_sign_id=" + merchant_sign_id;
                }
                signStr = signStr + "&mobile=" + mobile + "&notify_url=" + notify_url + "&order_amount=" + order_amount + "&order_no=" + order_no + "&order_time=" + order_time + "&product_name=" + product_name + "&service_type=" + service_type + "&sms_code=" + sms_code + "&sms_trade_no=" + sms_trade_no;

                if (sign_type == "RSA-S")//RSA-S签名方法
                {
                    //商家私钥
                    string merchant_private_key = "MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBALf/+xHa1fDTCsLYPJLHy80aWq3djuV1T34sEsjp7UpLmV9zmOVMYXsoFNKQIcEzei4QdaqnVknzmIl7n1oXmAgHaSUF3qHjCttscDZcTWyrbXKSNr8arHv8hGJrfNB/Ea/+oSTIY7H5cAtWg6VmoPCHvqjafW8/UP60PdqYewrtAgMBAAECgYEAofXhsyK0RKoPg9jA4NabLuuuu/IU8ScklMQIuO8oHsiStXFUOSnVeImcYofaHmzIdDmqyU9IZgnUz9eQOcYg3BotUdUPcGgoqAqDVtmftqjmldP6F6urFpXBazqBrrfJVIgLyNw4PGK6/EmdQxBEtqqgXppRv/ZVZzZPkwObEuECQQDenAam9eAuJYveHtAthkusutsVG5E3gJiXhRhoAqiSQC9mXLTgaWV7zJyA5zYPMvh6IviX/7H+Bqp14lT9wctFAkEA05ljSYShWTCFThtJxJ2d8zq6xCjBgETAdhiH85O/VrdKpwITV/6psByUKp42IdqMJwOaBgnnct8iDK/TAJLniQJABdo+RodyVGRCUB2pRXkhZjInbl+iKr5jxKAIKzveqLGtTViknL3IoD+Z4b2yayXg6H0g4gYj7NTKCH1h1KYSrQJBALbgbcg/YbeU0NF1kibk1ns9+ebJFpvGT9SBVRZ2TjsjBNkcWR2HEp8LxB6lSEGwActCOJ8Zdjh4kpQGbcWkMYkCQAXBTFiyyImO+sfCccVuDSsWS+9jrc5KadHGIvhfoRjIj2VuUKzJ+mXbmXuXnOYmsAefjnMCI6gGtaqkzl527tw=";


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
                    var trade_no1 = el.XPathSelectElement("/response/trade_no");
                    var order_no1 = el.XPathSelectElement("/response/order_no");
                    var merchant_sign_id1 = el.XPathSelectElement("/response/merchant_sign_id");
                    var trade_status1 = el.XPathSelectElement("/response/trade_status");
                    var trade_time1 = el.XPathSelectElement("/response/trade_time");
                    var dinpaysign1 = el.XPathSelectElement("/response/sign");
                    //去掉首尾的标签并转换成string
                    string is_success = Regex.Match(is_success1.ToString(), "(?<=>).*?(?=<)").Value;
                    if (is_success == "F")
                    {
                        Response.Write("获取失败");
                        Response.Write(_xml);
                        Response.End();
                    }
                    string merchant_code2 = Regex.Match(merchant_code1.ToString(), "(?<=>).*?(?=<)").Value;
                    string trade_no2 = Regex.Match(trade_no1.ToString(), "(?<=>).*?(?=<)").Value;
                    string order_no2 = Regex.Match(order_no1.ToString(), "(?<=>).*?(?=<)").Value;
                    string merchant_sign_id2 = Regex.Match(merchant_sign_id1.ToString(), "(?<=>).*?(?=<)").Value;
                    string trade_status2 = Regex.Match(trade_status1.ToString(), "(?<=>).*?(?=<)").Value;
                    string trade_time2 = Regex.Match(trade_time1.ToString(), "(?<=>).*?(?=<)").Value;
                    string dinpaysign = Regex.Match(dinpaysign1.ToString(), "(?<=>).*?(?=<)").Value;
                    //组装验签字符串
                    string signsrc = "is_success=" + is_success + "&merchant_code=" + merchant_code2 + "&merchant_sign_id=" + merchant_sign_id2 + "&order_no=" + order_no2 + "&trade_no=" + trade_no2 + "&trade_status=" + trade_status2 + "&trade_time=" + trade_time2;
                    //使用公钥对返回的数据验签
                    bool validateResult = testOrder.HttpHelp.ValidateRsaSign(signsrc, encryption_key, dinpaysign);

                    if (validateResult == false)
                    {
                        Response.Write("验签失败");
                        Response.End();
                    }
                    Response.Write("结果:" + signsrc + "<br/>");
                    Response.Write("验签结果:" + validateResult + "<br/>");
                    //Response.End()
                }
                else //RSA签名方式
                { 
                RSAWithHardware rsa = new RSAWithHardware();
                string merPubKeyDir = "D:/200001001888.pfx";   //证书路径
                string password = "200001001888";                //证书密码
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
                var trade_no1 = el.XPathSelectElement("/response/trade_no");
                var order_no1 = el.XPathSelectElement("/response/order_no");
                var merchant_sign_id1 = el.XPathSelectElement("/response/merchant_sign_id");
                var trade_status1 = el.XPathSelectElement("/response/trade_status");
                var trade_time1 = el.XPathSelectElement("/response/trade_time");
                var dinpaysign1 = el.XPathSelectElement("/response/sign");
                //去掉首尾的标签并转换成string
                string is_success = Regex.Match(is_success1.ToString(), "(?<=>).*?(?=<)").Value;
                if (is_success == "F")
                {
                    Response.Write("获取失败");
                    Response.End();
                }
                string merchant_code2 = Regex.Match(merchant_code1.ToString(), "(?<=>).*?(?=<)").Value;
                string trade_no2 = Regex.Match(trade_no1.ToString(), "(?<=>).*?(?=<)").Value;
                string order_no2 = Regex.Match(order_no1.ToString(), "(?<=>).*?(?=<)").Value;
                string merchant_sign_id2 = Regex.Match(merchant_sign_id1.ToString(), "(?<=>).*?(?=<)").Value;
                string trade_status2 = Regex.Match(trade_status1.ToString(), "(?<=>).*?(?=<)").Value;
                string trade_time2 = Regex.Match(trade_time1.ToString(), "(?<=>).*?(?=<)").Value;
                string dinpaysign = Regex.Match(dinpaysign1.ToString(), "(?<=>).*?(?=<)").Value;
                //组装验签字符串
                string signsrc = "is_success=" + is_success + "&merchant_code=" + merchant_code2 + "&merchant_sign_id=" + merchant_sign_id2 + "&order_no=" + order_no2 + "&trade_no=" + trade_no2 + "&trade_status=" + trade_status2 + "&trade_time=" + trade_time2;
                //RSA验签
                bool result = rsaWithH.VerifySign("200001001888", signsrc, dinpaysign);
                if (result == false)
                {
                    Response.Write("验签失败");
                    Response.End();
                }
                Response.Write("结果:" + signsrc + "<br/>");
                Response.Write("验签结果:" + result + "<br/>");
                //Response.End()
                }



            }
            finally
            {
            }
        }
    }
}