using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using System.Text;

namespace com.mobaopay.merchant
{
    public partial class refundOrd : System.Web.UI.Page
    {
        public string strTable = "";
        protected void Page_Load(object sender, EventArgs e)
        {
            try
            {
                // 组织请求数据并签名
                Dictionary<string, string> queryData = new Dictionary<string, string>();
                queryData.Add("apiName", MobaopayConfig.Mobaopay_apiname_refund);
                queryData.Add("apiVersion", MobaopayConfig.Mobaopay_api_version);
                queryData.Add("platformID", MobaopayConfig.Platform_id);
                queryData.Add("merchNo", MobaopayConfig.Merchant_acc);
                queryData.Add("orderNo", Request.Form["orderNo"]);
                queryData.Add("tradeDate", Request.Form["tradeDate"]);
                queryData.Add("amt", Request.Form["amt"]);
                queryData.Add("tradeSummary", Request.Form["tradeSummary"]);

                string requestStr = MobaopayMerchant.Instance.generateRefundRequest(queryData);
                string signMsg = MobaopaySignUtil.Instance.sign(requestStr);
                requestStr = requestStr + "&signMsg=" + signMsg;

                // 发送数据并打印回复
                string result = MobaopayMerchant.Instance.transact(requestStr, MobaopayConfig.Mobaopay_gateway);
                MobaopayRefundEntity entity = new MobaopayRefundEntity();
                entity.Parse(result);

                // 生成页面字符串
                StringBuilder sb = new StringBuilder();
                sb.Append("<tr><td align=\"left\" width=\"30%\">&nbsp;&nbsp;响应码</td><td align=\"left\">&nbsp;&nbsp;" + entity.RespCode + "</td></tr>");
                sb.Append("<tr><td align=\"left\" width=\"30%\">&nbsp;&nbsp;响应描述</td><td align=\"left\">&nbsp;&nbsp;" + entity.RespDesc + "</td></tr>");
                strTable = sb.ToString();
            }
            catch (Exception ex)
            {
                strTable = ex.Message;
            }
        }
    }
}