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
                string signStr = "interface_version=" + interface_version + "&merchant_code=" + merchant_code + "&order_no=" + order_no + "&service_type=" + service_type;

                if (sign_type == "RSA-S") //RSA-S签名方法
                {
                    //商家私钥
                    string merchant_private_key = "MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBALf/+xHa1fDTCsLYPJLHy80aWq3djuV1T34sEsjp7UpLmV9zmOVMYXsoFNKQIcEzei4QdaqnVknzmIl7n1oXmAgHaSUF3qHjCttscDZcTWyrbXKSNr8arHv8hGJrfNB/Ea/+oSTIY7H5cAtWg6VmoPCHvqjafW8/UP60PdqYewrtAgMBAAECgYEAofXhsyK0RKoPg9jA4NabLuuuu/IU8ScklMQIuO8oHsiStXFUOSnVeImcYofaHmzIdDmqyU9IZgnUz9eQOcYg3BotUdUPcGgoqAqDVtmftqjmldP6F6urFpXBazqBrrfJVIgLyNw4PGK6/EmdQxBEtqqgXppRv/ZVZzZPkwObEuECQQDenAam9eAuJYveHtAthkusutsVG5E3gJiXhRhoAqiSQC9mXLTgaWV7zJyA5zYPMvh6IviX/7H+Bqp14lT9wctFAkEA05ljSYShWTCFThtJxJ2d8zq6xCjBgETAdhiH85O/VrdKpwITV/6psByUKp42IdqMJwOaBgnnct8iDK/TAJLniQJABdo+RodyVGRCUB2pRXkhZjInbl+iKr5jxKAIKzveqLGtTViknL3IoD+Z4b2yayXg6H0g4gYj7NTKCH1h1KYSrQJBALbgbcg/YbeU0NF1kibk1ns9+ebJFpvGT9SBVRZ2TjsjBNkcWR2HEp8LxB6lSEGwActCOJ8Zdjh4kpQGbcWkMYkCQAXBTFiyyImO+sfCccVuDSsWS+9jrc5KadHGIvhfoRjIj2VuUKzJ+mXbmXuXnOYmsAefjnMCI6gGtaqkzl527tw=";


                    //私钥转换成C#专用私钥
                    merchant_private_key = testOrderQuery.HttpHelp.RSAPrivateKeyJava2DotNet(merchant_private_key);
                    //签名
                    string signData = testOrderQuery.HttpHelp.RSASign(signStr, merchant_private_key);
                    //将signData进行UrlEncode编码
                    signData = HttpUtility.UrlEncode(signData);
                    //组装字符串
                    string para = signStr + "&sign_type=" + sign_type + "&sign=" + signData;
                    //用HttpPost方式提交
                    string _xml = HttpHelp.HttpPost("https://query.yuanruic.com/query/dcard", para);
                    //将返回的xml中的参数提取出来
                    var el = XElement.Load(new StringReader(_xml));
                    //提取参数
                    var is_success1 = el.XPathSelectElement("/is_success");
                    var merchantcode1 = el.XPathSelectElement("/merchant_code");
                    var orderno1 = el.XPathSelectElement("/order_no");
                    var ordertime1 = el.XPathSelectElement("/order_time");
                    var orderamount1 = el.XPathSelectElement("/order_amount");
                    var trade_no1 = el.XPathSelectElement("/trade_no");
                    var card_no1 = el.XPathSelectElement("/card_no");
                    var card_code1 = el.XPathSelectElement("/card_code");
                    var pay_date1 = el.XPathSelectElement("/pay_date");
                    var card_amount1 = el.XPathSelectElement("/trade/card_amount");
                    var card_actual_amount1 = el.XPathSelectElement("/card_actual_amount");
                    var dinpaysign1 = el.XPathSelectElement("/sign");
                    var trade_status1 = el.XPathSelectElement("/trade_status");
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
                    string card_no = Regex.Match(card_no1.ToString(), "(?<=>).*?(?=<)").Value;
                    string card_code = Regex.Match(card_code1.ToString(), "(?<=>).*?(?=<)").Value;
                    string pay_date = Regex.Match(pay_date1.ToString(), "(?<=>).*?(?=<)").Value;
                    string card_amount = Regex.Match(card_amount1.ToString(), "(?<=>).*?(?=<)").Value;
                    string card_actual_amount = Regex.Match(card_actual_amount1.ToString(), "(?<=>).*?(?=<)").Value;
                    string trade_status = Regex.Match(trade_status1.ToString(), "(?<=>).*?(?=<)").Value;
                    string dinpaysign = Regex.Match(dinpaysign1.ToString(), "(?<=>).*?(?=<)").Value;
                    //组装字符串
                    string signsrc = "card_actual_amount=" + card_actual_amount + "&card_amount=" + card_amount + "&card_code=" + card_code + "&card_no=" + card_no + "&merchant_code=" + merchantcode + "&order_amount=" + orderamount + "&order_no=" + orderno + "&order_time=" + ordertime + "&pay_date=" + pay_date + "&trade_no=" + trade_no + "&trade_status=" + trade_status ;
                    //使用公钥对数据验签
                    string dinpay_public_key = "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCBxRoYbPf95kv1l4av8Ggc/adZp22GVjZ+Al1UBUM2nxqaluPfQ8gnjcGNNEOAA+iWDkjrh9PVKgXSCRel3VbDiDtz0vrMRmhQ0W5CsBATBFrAlG4oh13FB4wTEP3qd1HST5nEdTBhdp06y8M16A59N5UY+s3oTwkF5KIJjuGdnQIDAQAB";		


                    //将公钥转换成C#专用格式
                    dinpay_public_key = testOrderQuery.HttpHelp.RSAPublicKeyJava2DotNet(dinpay_public_key);
                    //验签
                    bool validateResult = testOrderQuery.HttpHelp.ValidateRsaSign(signsrc, dinpay_public_key, dinpaysign);
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
                    string merPubKeyDir = "D:/200001001888.pfx";   //证书路径
                    string password = "200001001888";                //证书密码
                    RSAWithHardware rsaWithH = new RSAWithHardware();
                    rsaWithH.Init(merPubKeyDir, password, "D:/dinpayRSAKeyVersion");
                    string signData = rsaWithH.Sign(signStr);    //签名
                    signData = HttpUtility.UrlEncode(signData);  //将signData进行UrlEncode编码
                    //组装字符串
                    string para = signStr + "&sign_type=" + sign_type + "&sign=" + signData;
                    //用HttpPost方式提交
                    string _xml = HttpHelp.HttpPost("https://query.yuanruic.com/query/dcard", para);
                    //将返回的xml中的参数提取出来
                    var el = XElement.Load(new StringReader(_xml));
                    //提取参数
                    var is_success1 = el.XPathSelectElement("/is_success");
                    var merchantcode1 = el.XPathSelectElement("/merchant_code");
                    var orderno1 = el.XPathSelectElement("/order_no");
                    var ordertime1 = el.XPathSelectElement("/order_time");
                    var orderamount1 = el.XPathSelectElement("/order_amount");
                    var trade_no1 = el.XPathSelectElement("/trade_no");
                    var card_no1 = el.XPathSelectElement("/card_no");
                    var card_code1 = el.XPathSelectElement("/card_code");
                    var pay_date1 = el.XPathSelectElement("/pay_date");
                    var card_amount1 = el.XPathSelectElement("/trade/card_amount");
                    var card_actual_amount1 = el.XPathSelectElement("/card_actual_amount");
                    var dinpaysign1 = el.XPathSelectElement("/sign");
                    var trade_status1 = el.XPathSelectElement("/trade_status");
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
                    string card_no = Regex.Match(card_no1.ToString(), "(?<=>).*?(?=<)").Value;
                    string card_code = Regex.Match(card_code1.ToString(), "(?<=>).*?(?=<)").Value;
                    string pay_date = Regex.Match(pay_date1.ToString(), "(?<=>).*?(?=<)").Value;
                    string card_amount = Regex.Match(card_amount1.ToString(), "(?<=>).*?(?=<)").Value;
                    string card_actual_amount = Regex.Match(card_actual_amount1.ToString(), "(?<=>).*?(?=<)").Value;
                    string trade_status = Regex.Match(trade_status1.ToString(), "(?<=>).*?(?=<)").Value;
                    string dinpaysign = Regex.Match(dinpaysign1.ToString(), "(?<=>).*?(?=<)").Value;
                    //组装字符串
                    string signsrc = "card_actual_amount=" + card_actual_amount + "&card_amount=" + card_amount + "&card_code=" + card_code + "&card_no=" + card_no + "&merchant_code=" + merchantcode + "&order_amount=" + orderamount + "&order_no=" + orderno + "&order_time=" + ordertime + "&pay_date=" + pay_date + "&trade_no=" + trade_no + "&trade_status=" + trade_status;
                    //验签
                    bool result = rsaWithH.VerifySign("200001001888", signsrc, dinpaysign);
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