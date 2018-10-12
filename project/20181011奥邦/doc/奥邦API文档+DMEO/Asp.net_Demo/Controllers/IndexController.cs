using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Web;
using System.Web.Mvc;
using System.Web.Security;

namespace AobangPay_Demo.Controllers
{
    public class IndexController : Controller
    {
        /// <summary>
        /// 请求奥邦支付
        /// </summary>
        /// <returns></returns>
        public ActionResult Index()
        {
            string gateWay = "https://www.aobangapi.com/pay";
            string trx_key = "7udoz4wv67fsht3ryg1brzesuj2grjue";
            string serect_key = "109b6f64bed6e252437e9de376ed2e7f";

            Dictionary<string, string> dict = new Dictionary<string, string>();
            dict.Add("trx_key", trx_key);
            dict.Add("ord_amount", "10");
            dict.Add("request_id", "TS2018011421063523561");
            dict.Add("product_type", "20303");//支付宝扫码
            dict.Add("request_time", DateTime.Now.ToString("yyyyMMddHHmmss"));
            dict.Add("goods_name", "iphone");
            dict.Add("request_ip", GetIP());

            string returnURL = "http://www.baidu.com";
            string callBackURL = "http://www.baidu.com";

            dict.Add("return_url", returnURL);
            dict.Add("callback_url", callBackURL);

            if (dict["product_type"] == "50103" || dict["product_type"] == "40103") 
            {
                //网关支付的时候填写 银行编码，快捷支付的时候填写 银行卡号
                dict.Add("bank_code", "XXXXXXXXXXX");
            }

            string signStr = prepareSign(dict, serect_key);
            string sign = Sign(signStr);

            dict.Add("sign", sign);

            string html = this.BuildForm(dict, gateWay, "post", "utf8");

            Response.Write(html);

            return View();
        }

        /// <summary>
        /// 构建Form请求  6505
        /// </summary>
        /// <returns></returns>
        public string BuildForm(Dictionary<string, string> dict, string gateway, string method, string charset)
        {
            StringBuilder html = new StringBuilder();
            html.Append("<html>");
            html.Append("   <head>");
            html.Append("       <meta charset=\"" + charset + "\">");
            html.Append("   </head>");
            html.Append("   <body>");
            html.Append("       <form id='paysubmit' name='paysubmit' action='" + gateway + "' method='" + method + "'>");

            foreach (string key in dict.Keys)
            {
                html.Append("       <input type=\"hidden\" name=\"" + key + "\" value=\"" + dict[key] + "\" />\n");
            }
            html.Append("       </form>Loading......");
            html.Append("       <script>document.forms['paysubmit'].submit();</script>");

            html.Append("   </body>");
            html.Append("</html>");

            return html.ToString();
        }

        /// <summary>
        /// 准备签名字符串
        /// </summary>
        /// <param name="dict"></param>
        /// <returns></returns>
        public string prepareSign(Dictionary<string, string> dict,string secrect_key)
        {
            Dictionary<string, string> dictASC = dict.OrderBy(p => p.Key).ToDictionary(p => p.Key, o => o.Value);

            string result = "";
            List<string> list = new List<string>();
            foreach (string key in dictASC.Keys)
            {
                list.Add(key + "=" + dictASC[key]);
            }

            result = string.Join("&", list.ToArray());

            return result + "&secret_key=" + secrect_key;
        }

        /// <summary>
        /// 签名
        /// </summary>
        /// <param name="data"></param>
        /// <returns></returns>
        public string Sign(string data)
        {
            return md532(data).ToUpper();
        }

        /// <summary>
        /// MD5加密
        /// </summary>
        /// <param name="str">加密字符</param>
        /// <param name="code">加密位数16/32</param>
        /// <returns></returns>
        public static string md532(string str)
        {
            string strEncrypt = string.Empty;

            strEncrypt = FormsAuthentication.HashPasswordForStoringInConfigFile(str, "MD5");

            return strEncrypt;
        }

        /// <summary>
        /// 获取IP地址
        /// </summary>
        /// <returns></returns>
        public  string GetIP()
        {
            string userIP;

            // 如果使用代理，获取真实IP 
            if (Request.ServerVariables["HTTP_X_FORWARDED_FOR"] != "")
                userIP = Request.ServerVariables["REMOTE_ADDR"];
            else
                userIP = Request.ServerVariables["HTTP_X_FORWARDED_FOR"];
            if (userIP == null || userIP == "")
                userIP = Request.UserHostAddress;
            return userIP;
        }
    }
}
