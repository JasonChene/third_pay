using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using Demo.Class;
namespace merchant_demo
{
    public partial class PayDo1 : System.Web.UI.Page
    {
        public string QueryParam = string.Empty;
        public string keyValue = string.Empty;
        public string key = string.Empty;
        public string sign = string.Empty;
        public string signValue = string.Empty;
        protected void Page_Load(object sender, EventArgs e)
        {
             if (!IsPostBack)
            {
                RSAOperate Rdaop = new RSAOperate();
                QueryParam = ProperConst.payUrl;
                signValue = Rdaop.GetUrlParamString(Request.Form, MD5.GetPayRSAParamSort());
                sign = MD5.md5Sign(signValue + ProperConst.Key);
            }
        }
    }
}