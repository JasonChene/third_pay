using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using API.cmbn;
using API.utils;

public partial class Card_Callback : System.Web.UI.Page
{
    protected void Page_Load(object sender, EventArgs e)
    {
        if (!IsPostBack)
        {
            SZX.logURL(Request.RawUrl);
            // 校验返回数据包
            SZXCallbackResult result = SZX.VerifyCallback(FormatQueryString.GetQueryString("r0_Cmd"), FormatQueryString.GetQueryString("r1_Code"), FormatQueryString.GetQueryString("p1_MerId"), FormatQueryString.GetQueryString("p2_Order"), FormatQueryString.GetQueryString("p3_Amt"), FormatQueryString.GetQueryString("p4_FrpId"), FormatQueryString.GetQueryString("p5_CardNo"), FormatQueryString.GetQueryString("p6_confirmAmount"), FormatQueryString.GetQueryString("p7_realAmount"), FormatQueryString.GetQueryString("p8_cardStatus"), FormatQueryString.GetQueryString("p9_MP"), FormatQueryString.GetQueryString("pb_BalanceAmt"), FormatQueryString.GetQueryString("pc_BalanceAct"), FormatQueryString.GetQueryString("hmac"));



            if (string.IsNullOrEmpty(result.ErrMsg))
            {
                // 使用应答机制时 必须回写success
                Response.Write("SUCCESS");
                //在接收到支付结果通知后，判断是否进行过业务逻辑处理，不要重复进行业务逻辑处理
                Logic(result);
            }
            else
            {
                HmacError(result);
            }
        }
    }
    protected void Logic(SZXCallbackResult result)
    {
        if (result.R1_Code == "1")
        {
            Response.Write("<BR>非银行卡支付成功");
            Response.Write("<BR>商户订单号:" + result.P2_Order);
            Response.Write("<BR>实际扣款金额(商户收到该返回数据后,一定用自己数据库中存储的金额与该金额进行比较):" + result.P3_Amt);
        }
        else
        {
            Response.Write("交易失败!");
        }
    }
    protected void HmacError(SZXCallbackResult result)
    {
        Response.Write("交易签名无效!");
        Response.Write("<BR>API-HMAC:" + result.Hmac);
        Response.Write("<BR>LocalHost:" + result.ErrMsg);
    }
}