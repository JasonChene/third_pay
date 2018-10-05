using System;
using System.Collections.Generic;
using System.Linq;
using System.Net;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;

namespace demo
{
    public partial class query : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {
            /*
             * 查询接口
             */

            string payUrl = "http://gateway.xunbaopay9.com/Search.aspx";
            string md5Key = "be8c2fadfb764e169f5a59b4315d0889";
            string parter = "1275";

            Dictionary<string, string> dic = new Dictionary<string, string>();
            dic.Add("orderid", "180627000710900189");
            dic.Add("parter", parter);
            string sign = Utility.Md5Encrypt(Utility.CreateSign(dic) + md5Key);
            dic.Add("sign", sign);

            string url = payUrl + "?" + Utility.CreateSign(dic);
            using (var client = new WebClient())
            {
                var responseString = client.DownloadString(url);
            }
        }
    }
}