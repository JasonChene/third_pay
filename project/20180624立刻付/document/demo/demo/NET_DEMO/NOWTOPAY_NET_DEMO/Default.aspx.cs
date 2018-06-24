using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using System.Text;
using System.Security.Cryptography;

namespace NOWTOPAY_NET_DEMO
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

            string apiurl = "https://gateway.nowtopay.com/NowtoPay.html";
            string partner = "16962";
            string key = "a7307538dab143fcaa7edb741a31629d";
            string ordernumber = txtOrderNo.Text;
            string banktype = Request.Form["banktype"];//
            string attach = "buy";
            string paymoney = txtMoney.Text;
            string callbackurl = "https://xxx/pay/callback.aspx";
            string hrefbackurl = "https://xxx";
            string isshow = "1";
            string signSource = string.Format("partner={0}&banktype={1}&paymoney={2}&ordernumber={3}&callbackurl={4}{5}", partner, banktype, paymoney, ordernumber, callbackurl, key);
            string sign = NOWTOPAYCommon.MD5(signSource, false).ToLower();
            string postUrl = apiurl + "?partner=" + partner;
            StringBuilder postData = new StringBuilder(postUrl);
            postData.Append("&banktype=" + banktype);
            postData.Append("&paymoney=" + paymoney);
            postData.Append("&ordernumber=" + ordernumber);
            postData.Append("&callbackurl=" + callbackurl);
            postData.Append("&hrefbackurl=" + hrefbackurl);
            postData.Append("&attach=" + attach);
            postData.Append("&isshow=" + isshow);
            postData.Append("&sign=" + sign);
            Response.Redirect(postData.ToString());
        }
 
    }
}