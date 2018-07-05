using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;

namespace JRAPI_NET_DEMO
{
    public partial class callback : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {
            string partner = "10000";//商户ID
            string Key = "1234567890";//商户KEY
            int orderstatus = Convert.ToInt32(Request["orderstatus"]);
            string ordernumber = Request["ordernumber"];
            string paymoney = Request["paymoney"];
            string sign = Request["sign"];
            string attach = Request["attach"];
            string signSource = string.Format("partner={0}&ordernumber={1}&orderstatus={2}&paymoney={3}{4}", partner, ordernumber, orderstatus, paymoney, Key);
            if (sign.ToUpper() == JRAPICommon.MD5(signSource, false).ToUpper())//签名正确
            {
                    //此处作逻辑处理
            }
            Response.Write("ok");
            Response.End();
        }
    }
}