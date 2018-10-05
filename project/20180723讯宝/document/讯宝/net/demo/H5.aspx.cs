using System;
using System.Collections.Generic;
using System.Linq;
using System.Net;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;

namespace demo
{
    public partial class H5 : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {
            /*
             * H5支付，对网关发起支付请求，网关会返回一个支付链接，在手机中访问该链接，即可唤醒对应支付APP
             */

            string payUrl = "http://gateway.xunbaopay9.com/chargebank.aspx";
            string md5Key = "be8c2fadfb764e169f5a59b4315d0889";
            string parter = "1275";

            Dictionary<string, string> dic = new Dictionary<string, string>();
            dic.Add("parter", parter);
            dic.Add("type", "931");
            dic.Add("value", "10.00");
            dic.Add("orderid", Guid.NewGuid().ToString().Replace("-", "").Substring(0, 30));
            dic.Add("callbackurl", "http://www.xunbaopay9.com");
            string sign = Utility.Md5Encrypt(Utility.CreateSign(dic) + md5Key);

            dic.Add("hrefbackurl", "http://www.xunbaopay9.com");
            dic.Add("payerIp", "0.0.0.0");
            dic.Add("attach", "");
            dic.Add("agent", "");
            dic.Add("sign", sign);


            using (var client = new WebClient())
            {
                var responseString = client.DownloadString(payUrl + "?" + Utility.CreateSign(dic));
            }

        }
    }
}