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
                    string merchant_private_key = "MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBAOD/K22eGec3qNQmk9LwsjpbJDJE9JYfsTJJQGJhfWsKbcZ9UISKXZxuhSCVaf2z9/pEln5RoE7GNwOrYv8R00P8nRJONHNPaLcf0Y8+c6DBWGVewZKojUzn18uAEGGW5XMjLs5/OU//opRB4ieeSmBJ4jp954XfR4Z57bjOpe/3AgMBAAECgYEArCr2K2JQxfp0aSq/8SkX6Mm3T/QuCPZlXGprJx0coJ0RVVKtG07ZxQtZOY671VQyjEKRukVx2vWYQWmTTkVwl+U71fh1mmiu00Y3odNoERc02ZN0zJmrSuhbcuEv6F8kBATunB55wOZ3jlbkXD9h+KUyePBOkrPb+81LhJ6kZXkCQQD18nQ1U2m9laS8ROJmZ1LuecQ4maaHW3xFxHoM9sS1YcpB3peQuXBrKa483zYADIJV2NYstc0QXMMZIXleKFFzAkEA6jF+xx4q+p/lhH8M3rHucHmkgFce90Jh1eHTdx5czizl3LiOYZ5D7cNL8x7piJDMmzkVz8+OidXm0wf5aT82bQJAP9TSJjjk26hn3dj+7Vbppi0CKTJvjvfGdBD/IDg3a1/a72eG7K/EJnvl1bSUvkSA2yjwxR/V/eYlWHNgnXhXUwJBANA6h+3FfhNvXmSrjqbncAljrwdJ70eMJ29DpoFQZtYPB6Z0FmzniqB6OCqIPr7leHc/j4xBkQwvO1hBy9pvkRUCQEVOGouGVeiXL/MuupUdbdBSV4nkYb9hrqE11gzbLu4A+OCpV8Xwdqu5SqX9Js1mQ6vQwTHu63vyfpxxl7oN9Jw=";
                    //私钥转换成C#专用私钥
                    merchant_private_key = testOrderQuery.HttpHelp.RSAPrivateKeyJava2DotNet(merchant_private_key);
                    //签名
                    string signData = testOrderQuery.HttpHelp.RSASign(signStr, merchant_private_key);
                    //将signData进行UrlEncode编码
                    signData = HttpUtility.UrlEncode(signData);
                    //组装字符串
                    string para = signStr + "&sign_type=" + sign_type + "&sign=" + signData;
                    //用HttpPost方式提交
                    string _xml = HttpHelp.HttpPost("https://query.zdfmf.com/query", para);
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
                    string dinpayPubKey = "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCZDDIp0i3Ai7hskWs03M8n9QXrz3FX+KXak/zhy+rO7IBCE2p7mqHgMzj2KTqPfeQyAJoF6WvNy9MT9Vf/MA1mcYpYp9vVXRlLZ9oCFGUHtL2VJJHPzKlit46OGhyU7z/jtWwDuRXqcO76+VPi0/1XOvsyR5lTDKB3LVQA4cPD3wIDAQAB";
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
                    string merPubKeyDir = "D:/800004007888.pfx";   //证书路径
                    string password = "87654321";                //证书密码
                    RSAWithHardware rsaWithH = new RSAWithHardware();
                    rsaWithH.Init(merPubKeyDir, password, "D:/dinpayRSAKeyVersion");//初始化。设置dinpayRSAKeyVersion的路径与证书路径一致即可
                    string signData = rsaWithH.Sign(signStr);    //签名
                    signData = HttpUtility.UrlEncode(signData);  //将signData进行UrlEncode编码
                    //组装字符串
                    string para = signStr + "&sign_type=" + sign_type + "&sign=" + signData;
                    //用HttpPost方式提交
                    string _xml = HttpHelp.HttpPost("https://query.zdfmf.com/query", para);
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
                    bool result = rsaWithH.VerifySign("800004007888", signsrc, zhihfsign);
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