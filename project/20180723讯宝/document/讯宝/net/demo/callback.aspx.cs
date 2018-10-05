using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;

namespace demo
{
    public partial class callback : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {
            /*
             *网银支付、快捷支付，对网关发起支付请求，网关会跳转到收银台
             */

            string md5Key = "be8c2fadfb764e169f5a59b4315d0889";

            HttpRequest req = HttpContext.Current.Request;

            Dictionary<string, string> dic = new Dictionary<string, string>();
            dic.Add("orderid", req.QueryString["orderid"].ToString());
            dic.Add("opstate", req.QueryString["opstate"].ToString());
            dic.Add("ovalue", req.QueryString["ovalue"].ToString());
            dic.Add("time", req.QueryString["systime"].ToString());
            dic.Add("sysorderid", req.QueryString["sysorderid"].ToString());
            string sign = Utility.Md5Encrypt(Utility.CreateSign(dic) + md5Key);

            if (req.QueryString["sign"].ToString() == sign) {
                Response.Write("opstate=0");
            }
        }
    }
}