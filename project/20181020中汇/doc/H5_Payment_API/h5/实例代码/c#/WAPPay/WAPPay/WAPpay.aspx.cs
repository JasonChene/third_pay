using System;
using System.Web;
using System.IO;
using System.Xml;
using System.Xml.XPath;
using System.Xml.Linq;
using System.Drawing;
using System.Text.RegularExpressions;

namespace CSharpTestPay
{
    public partial class _Default : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {
            try
            {
                
				////////////////////////To receive the parameter form HTML form//////////////////////

                string interface_version = Request.Form["interface_version"].ToString().Trim();
                string service_type = Request.Form["service_type"].ToString().Trim();
                string sign_type = Request.Form["sign_type"].ToString().Trim();
                string merchant_code = Request.Form["merchant_code"].ToString().Trim();
                string order_no = Request.Form["order_no"].ToString().Trim();
                string order_time = Request.Form["order_time"].ToString().Trim();
                string order_amount = Request.Form["order_amount"].ToString().Trim();
                string product_name = Request.Form["product_name"].ToString().Trim();
                string product_code = Request.Form["product_code"].ToString().Trim();
                string product_num = Request.Form["product_num"].ToString().Trim();
                string product_desc = Request.Form["product_desc"].ToString().Trim();
                string extra_return_param = Request.Form["extra_return_param"].ToString().Trim();
                string extend_param = Request.Form["extend_param"].ToString().Trim();
                string notify_url = Request.Form["notify_url"].ToString().Trim();
                string client_ip = Request.Form["client_ip"].ToString().Trim();

                ////////////////Array data//////////////////
                string signStr = "";
                if (client_ip != "")
                {
                    signStr = signStr + "client_ip=" + client_ip + "&";
                }
                if (extend_param != "")
                {
                    signStr = signStr + "extend_param=" + extend_param + "&";
                }
                if (extra_return_param != "")
                {
                    signStr = signStr + "extra_return_param=" + extra_return_param + "&";
                }
                if (interface_version != "")
                {
                    signStr = signStr + "interface_version=" + interface_version + "&";
                }
                if (merchant_code != "")
                {
                    signStr = signStr + "merchant_code=" + merchant_code + "&";
                }
                if (notify_url != "")
                {
                    signStr = signStr + "notify_url=" + notify_url + "&";
                }
                if (order_amount != "")
                {
                    signStr = signStr + "order_amount=" + order_amount + "&";
                }
                if (order_no != "")
                {
                    signStr = signStr + "order_no=" + order_no + "&";
                }
                if (order_time != "")
                {
                    signStr = signStr + "order_time=" + order_time + "&";
                }
                if (product_code != "")
                {
                    signStr = signStr + "product_code=" + product_code + "&";
                }
                if (product_desc != "")
                {
                    signStr = signStr + "product_desc=" + product_desc + "&";
                }
                if (product_name != "")
                {
                    signStr = signStr + "product_name=" + product_name + "&";
                }
                if (product_num != "")
                {
                    signStr = signStr + "product_num=" + product_num + "&";
                }
                if (service_type != "")
                {
                    signStr = signStr + "service_type=" + service_type;
                }

                    //商户私钥
                string merchant_private_key = "MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAKMlMURBS6O9AExD0copKwyEPqeX9pUPB7we1QVT/InrEm4QeZrq1R7VMfIi7tBIHESWGP9DAYB6INp5aUKPQlvnqfxFDvj4zNpKwm5qFpehHR6J/lf4rLwQycV14Gg2M/Sx9AmZyIf014maXu9u9zApn35e927UzdAG7VKAluvHAgMBAAECgYAVn2JON5E83FnjwcFWV+p6uDRNphhqXRmbV0LId/8qkFta9xgG7kTc10jNXM/mDwigFnytqEXUr1sXWawXxED5EydmXn2IfELzGmyUC1KI+wtdmKndcTcDREowz8OZ8mcsFhH0M36k/MRwX8ng7RUacpzy+tQ1Vrm/fcofvYKlUQJBAM+ZLdo4f0fbQjrU9j7qjiAw3cRjzuvmXCoG7omR1fxY4LKVMEgYXrVDxN34DdYIaceHtJvBwrlBSr9AA+QEaI0CQQDJLsPAXtioh/uFiHtp33VEq6FDw0N2ZEZ1IVsFZ87wjbov4aQMdhXufDczheymm3CgPTsA1OEDWWiWUk8nJEKjAkATfzRiOUoi6oG22sdhs0+z0EMTrbgCSblALTR78RliwMohm4dUTg2fAoVbv281OccNeT5KHpF2Kp6lhZKX+J4FAkEAh+d/dUSdN7wkvWAlfq/lmC4ZEp2lxSSYNCgERPKLaRVU4WOcXo6m4iQnZjbiVupKevTiFv23w3tym5mevuJP4QJAGObgIly7ypM/2Fx4fBHMq6trOjlhYAg4ztrn36k56jyEbmyHHLCirO9kl3IdWEmqgaht/vRQr0ZcPhGNCxaWhg==";
                    //转换私钥格式
                    merchant_private_key = testOrder.HttpHelp.RSAPrivateKeyJava2DotNet(merchant_private_key);
                     //签名
                    string signData = testOrder.HttpHelp.RSASign(signStr, merchant_private_key);
                    //对signData做UrlEncode处理
                     signData = HttpUtility.UrlEncode(signData);
                     
                     //组装字符串
                     string para = signStr + "&sign_type=" + sign_type + "&sign=" + signData;
                     //提交
                     string _xml = testOrder.HttpHelp.HttpPost("https://api.vsdpay.com/gateway/api/h5apipay", para);
                     //_xml = HttpUtility.HtmlEncode(_xml);
                     //Response.Write("错误:" + _xml + "<br/>");
                     //Response.End();
                     //获取返回的XML
                     var el = XElement.Load(new StringReader(_xml));
                     //获取支付链接
                     var payURL1 = el.XPathSelectElement("/response/payURL");
                     if (payURL1 == null)
                     {
                         Response.Write("错误:" + _xml + "<br/>");
                         Response.End();
                     }
                     string payURL = Regex.Match(payURL1.ToString(), "(?<=>).*?(?=<)").Value;
                     payURL = HttpUtility.UrlDecode(payURL);
                     Response.Redirect(payURL, true); 
   
            }
			finally{
            }
        }
    }
}

