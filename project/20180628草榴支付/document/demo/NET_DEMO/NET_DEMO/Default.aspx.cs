using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using System.Text;
using System.Security.Cryptography;

namespace NET_DEMO
{
    public partial class Default : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {
            if (!this.IsPostBack) {
                txtOrderNo.Text = DateTime.Now.ToString("yyyyMMddHHmmssfff");
            }
        }

        protected void btnSub_Click(object sender, EventArgs e)
        {

            string apiurl = "http://pay.caoliupay.com/Pay.html";
            string appid = "10000001";
            string key = "a006e912ceb3eb4d9682d9aa6b47b291";
            string ordernumber = txtOrderNo.Text;
            string paytype = Request.Form["paytype"];
            string attach = "buy";
            string paymoney = txtMoney.Text;
            string callbackurl = "https://xxx/pay/callback.aspx";
            string isshow = "1";
            string signSource = string.Format("appid={0}&paytype={1}&paymoney={2}&ordernumber={3}&callbackurl={4}{5}", appid, paytype, paymoney, ordernumber, callbackurl, key);
            string sign = Common.MD5(signSource, false).ToLower();
            string postUrl = apiurl + "?appid=" + appid;
            StringBuilder postData = new StringBuilder(postUrl);
            postData.Append("&paytype=" + paytype);
            postData.Append("&paymoney=" + paymoney);
            postData.Append("&ordernumber=" + ordernumber);
            postData.Append("&callbackurl=" + callbackurl);
            postData.Append("&attach=" + attach);
            postData.Append("&isshow=" + isshow);
            postData.Append("&sign=" + sign);
            Response.Redirect(postData.ToString());
        }
 
    }
}