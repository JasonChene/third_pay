using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using System.Text;
using System.Security.Cryptography;

namespace JRAPI_NET_DEMO
{
    public partial class Default : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {
            if (!this.IsPostBack) {
                txtordernumber.Text = DateTime.Now.ToString("yyyyMMddHHmmssfff");
            }
        }

        protected void btnSub_Click(object sender, EventArgs e)
        {
            string apiurl = txtUrl.Text;
            string partner = txtpartner.Text;
            string key = txtKey.Text;
            string ordernumber = txtordernumber.Text;
            string banktype = txtbanktype.Text;
            string attach = txtattach.Text;
            string paymoney = txtpaymoney.Text;
            string callbackurl = txtcallbackurl.Text;
            string hrefbackurl = txthrefbackurl.Text;
            string isshow = txtisShow.Text;
            string signSource = string.Format("partner={0}&banktype={1}&paymoney={2}&ordernumber={3}&callbackurl={4}{5}", partner, banktype, paymoney, ordernumber, callbackurl, key);
            string sign = JRAPICommon.MD5(signSource, false).ToLower();
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