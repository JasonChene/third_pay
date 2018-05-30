using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using System.Text;

namespace com.mobaopay.merchant
{
    public partial class queryOrd : System.Web.UI.Page
    {
        public string strTable = "";
        protected void Page_Load(object sender, EventArgs e)
        {
            try
            {
                // 组织请求数据并签名
                Dictionary<string, string> queryData = new Dictionary<string, string>();
                queryData.Add("apiName", MobaopayConfig.Mobaopay_apiname_query);
                queryData.Add("apiVersion", MobaopayConfig.Mobaopay_api_version);
                queryData.Add("platformID", MobaopayConfig.Platform_id);
                queryData.Add("merchNo", MobaopayConfig.Merchant_acc);
                queryData.Add("orderNo", Request.Form["orderNo"]);
                queryData.Add("tradeDate", Request.Form["tradeDate"]);
                queryData.Add("amt", Request.Form["amt"]);

                string requestStr = MobaopayMerchant.Instance.generateQueryRequest(queryData);
                string signStr = MobaopaySignUtil.Instance.sign(requestStr);
                requestStr = requestStr + "&signMsg=" + signStr;

                // 发送数据并打印回复
                string result = MobaopayMerchant.Instance.transact(requestStr, MobaopayConfig.Mobaopay_gateway);
                MobaopayQueryEntity entity = new MobaopayQueryEntity();
                entity.Parse(result);

                // 生成页面字符串
                StringBuilder sb = new StringBuilder();
                sb.Append("<tr><td align=\"left\" width=\"30%\">&nbsp;&nbsp;响应码</td><td align=\"left\">&nbsp;&nbsp;" + entity.RespCode + "</td></tr>");
                sb.Append("<tr><td align=\"left\" width=\"30%\">&nbsp;&nbsp;响应描述</td><td align=\"left\">&nbsp;&nbsp;" + entity.RespDesc + "</td></tr>");
                sb.Append("<tr><td align=\"left\" width=\"30%\">&nbsp;&nbsp;支付平台订单日期</td><td align=\"left\">&nbsp;&nbsp;" + entity.AccDate + "</td></tr>");
                sb.Append("<tr><td align=\"left\" width=\"30%\">&nbsp;&nbsp;支付平台订单号</td><td align=\"left\">&nbsp;&nbsp;" + entity.AccNo + "</td></tr>");
                //sb.Append("<tr><td align=\"left\" width=\"30%\">&nbsp;&nbsp;商户订单号</td><td align=\"left\">&nbsp;&nbsp;" + entity.OrderNo + "</td></tr>");
                sb.Append("<tr><td align=\"left\" width=\"30%\">&nbsp;&nbsp;支付状态</td><td align=\"left\">&nbsp;&nbsp;" + entity.Status + "</td></tr>");
                //sb.Append("<tr><td align=\"left\" width=\"30%\">&nbsp;&nbsp;响应描述</td><td align=\"left\">&nbsp;&nbsp;" + entity.SignMsg + "</td></tr>");
                strTable = sb.ToString();
            }
            catch (Exception ex)
            {
                strTable = ex.Message;
            }
        }
    }
}