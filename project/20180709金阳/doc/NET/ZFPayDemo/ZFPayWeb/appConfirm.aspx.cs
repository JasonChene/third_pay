using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using ZFPayWeb.Common;

namespace ZFPayWeb
{
    public partial class appConfirm : System.Web.UI.Page
    {
        protected string p4_orderno;
        protected string p3_paymoney;
        protected string sHtmlText;

        protected void Page_Load(object sender, EventArgs e)
        {
            if (!IsPostBack)
            {
                string p1_mchtid = Request["p1_mchtid"];
                string p2_paytype = Request["p2_paytype"];
                p3_paymoney = Request["p3_paymoney"];
                p4_orderno = Request["p4_orderno"];
                string p5_callbackurl = Request["p5_callbackurl"];
                string p6_notifyurl = Request["p6_notifyurl"];
                string p7_version = Request["p7_version"];
                string p8_signtype = Request["p8_signtype"];
                string p9_attach = Request["p9_attach"];
                string p10_appname = Request["p10_appname"];
                string p11_isshow = Request["p11_isshow"];
                string p12_orderip = Request["p12_orderip"]; 
                StringBuilder strsb = new StringBuilder();
                strsb.Append("p1_mchtid=" + p1_mchtid) //用户ID
                    .Append("&p2_paytype=" + p2_paytype) //支付方式
                    .Append("&p3_paymoney=" + p3_paymoney) //支付金额
                    .Append("&p4_orderno=" + p4_orderno) //订单号码
                    .Append("&p5_callbackurl=" + p5_callbackurl) //异步回调通知商户链接
                    .Append("&p6_notifyurl=" + p6_notifyurl) //同步链接
                    .Append("&p7_version=" + p7_version)
                    .Append("&p8_signtype=" + p8_signtype)
                    .Append("&p9_attach=" + p9_attach)
                    .Append("&p10_appname=" + p10_appname)
                    .Append("&p11_isshow=" + p11_isshow)
                    .Append("&p12_orderip=" + p12_orderip);
                string mSign = strsb.ToString() + Config.SIGNKEY;
                string sign = MD5Encrypt.MD5(mSign, false).ToLower();

                sHtmlText=FormUtil.RequestForm(p1_mchtid, p2_paytype, p3_paymoney, p4_orderno, p5_callbackurl, p6_notifyurl, p7_version,
                    p8_signtype, p9_attach, p10_appname, p11_isshow, p12_orderip, sign);
            }

        }
    }
}