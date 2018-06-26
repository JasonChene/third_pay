using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using API.cmbn;

public partial class Card_req : System.Web.UI.Page
{
    private string p2_Order, p3_Amt, p4_verifyAmt, p5_Pid, p6_Pcat, p7_Pdesc, p8_Url, pa8_cardNo, pa9_cardPwd, pa_MP, pa7_cardAmt, pd_FrpId, pr_NeedResponse, pz_userId, pz1_userRegTime;

    protected void Page_Load(object sender, EventArgs e)
    {
        // 设置 Response编码格式为GB2312
        Response.ContentEncoding = System.Text.Encoding.GetEncoding("gb2312");

        p2_Order = Request.Form["p2_Order"];
        p3_Amt = Request.Form["p3_Amt"];
        p4_verifyAmt = Request.Form["p4_verifyAmt"];
        p5_Pid = Request.Form["p5_Pid"];
        p6_Pcat = Request.Form["p6_Pcat"];
        p7_Pdesc = Request.Form["p7_Pdesc"];
        p8_Url = Request.Form["p8_Url"];
        pa_MP = Request.Form["pa_MP"];
        pa7_cardAmt = Request.Form["pa7_cardAmt"];
        pa8_cardNo = Request.Form["pa8_cardNo"];
        pa9_cardPwd = Request.Form["pa9_cardPwd"];
        pd_FrpId = Request.Form["pd_FrpId"];
        pr_NeedResponse = "1";
        pz_userId = Request.Form["pz_userId"];
        pz1_userRegTime = Request.Form["pz1_userRegTime"];

        try
        {
            //非银行卡 正式使用
            SZXResult result = SZX.AnnulCard(p2_Order, p3_Amt, p4_verifyAmt, p5_Pid, p6_Pcat, p7_Pdesc, p8_Url,
            pa_MP, pa7_cardAmt, pa8_cardNo, pa9_cardPwd, pd_FrpId, pr_NeedResponse, pz_userId, pz1_userRegTime);

            if (result.R1_Code == "1")
            {
                Response.Write("非银行卡支付请求提交成功");
                Response.Write("<br>商户订单号:" + result.R6_Order);
                Response.Write("<br><br>" + result.ReqResult);
            }
            else
            {
                Response.Write("非银行卡支付请求提交失败 [" + result.R1_Code + "]" + result.Rq_ReturnMsg);

                if (result.R1_Code == "11")
                {
                    Response.Write("-订单号重复");
                }
                else if (result.R1_Code == "7")
                {
                    Response.Write("-卡密无效");
                }
                else if (result.R1_Code == "61")
                {
                    Response.Write("-账户未开通");
                }

                Response.Write("<br><br>" + result.ReqUrl);
                Response.Write("<br><br>" + result.ReqResult);
            }
        }
        catch (Exception ex)
        {
            Response.Write(ex.ToString());
        }

    }
}