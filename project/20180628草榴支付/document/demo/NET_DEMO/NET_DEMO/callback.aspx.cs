
using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;

namespace NET_DEMO
{
    public partial class callback : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {
            string appid = "102400001";
            string key = "a006e912ceb3eb4d9682d9aa6b47b291";
            int orderstatus = Convert.ToInt32(Request["orderstatus"]);
            string ordernumber = Request["ordernumber"];
            string paymoney = Request["paymoney"];
            string sign = Request["sign"];
            string attach = Request["attach"];
            string signSource = string.Format("appid={0}&ordernumber={1}&orderstatus={2}&paymoney={3}{4}", appid, ordernumber, orderstatus, paymoney, key);
            if (sign == Common.MD5(signSource, false))//签名正确
            {
                    //此处作逻辑处理
            }
            Response.Write("success");
            Response.End();
        }
    }
}