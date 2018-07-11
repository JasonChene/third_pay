using System;
using System.Collections.Generic;
using System.Linq;
using System.Net;
using System.Text;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using ZFPayWeb.Common;

namespace ZFPayWeb
{
    public partial class Query : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {
            if (!IsPostBack)
            {
                txtpartner.Text = Config.P1_MCHTID;
                txtKey.Text = Config.SIGNKEY;
            }
        }

        protected void btnSub_Click(object sender, EventArgs e)
        {
            string mchtid = txtpartner.Text;
            string signType = ddlsignType.SelectedValue;
            string orderno = txtordernumber.Text.Trim();
            string version = txtversion.Text.Trim();
            string checkcode = txtKey.Text.Trim();
            StringBuilder strsb = new StringBuilder();
            strsb.Append("p1_mchtid=" + mchtid)
                .Append("&p2_signtype=" + signType)
                .Append("&p3_orderno=" + orderno)
                .Append("&p4_version=" + version);
            //.Append(checkcode);
            string signstr = strsb.ToString() + checkcode;
            string msign = MD5Encrypt.MD5(signstr, false).ToLower();
            string urlparams = strsb.ToString() + "&sign=" + msign;
            string strResult = new HttpUtil().DoPost(txtUrl.Text, urlparams, Encoding.UTF8);

            Response.Write(strResult);
        }
    }
}