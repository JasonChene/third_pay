using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;

namespace com.mobaopay.merchant
{
    public partial class normalPay : System.Web.UI.Page
    {
        public string orderNo;
        public string tradeDate;
        protected void Page_Load(object sender, EventArgs e)
        {
            // 设置订单号和交易日期
            DateTime now = DateTime.Now;
            orderNo = now.ToString("yyyyMMddhhmmss");
            tradeDate = now.ToString("yyyyMMdd");
        }
    }
}