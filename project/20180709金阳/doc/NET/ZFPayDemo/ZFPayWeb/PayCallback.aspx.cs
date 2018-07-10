using System;
using ZFPayWeb.Common;

namespace ZFPayWeb
{
    /// <summary>
    /// 异步通知商户
    /// </summary>
    public partial class PayCallback : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {
            string partner = Config.P1_MCHTID;//商户ID
            string Key = Config.SIGNKEY;//商户KEY
            int orderstatus = Convert.ToInt32(Request["orderstatus"]);
            string ordernumber = Request["ordernumber"];
            string paymoney = Request["paymoney"];
            string sign = Request["sign"];
            //string attach = Request["attach"];
            string signSource = string.Format("partner={0}&ordernumber={1}&orderstatus={2}&paymoney={3}{4}", partner, ordernumber, orderstatus, paymoney, Key);
            if (sign.ToUpper() == MD5Encrypt.MD5(signSource, false).ToUpper())//签名正确
            {
                //此处作逻辑处理
                                            
                Response.Write("ok");      //ok代表http响应第三方接收通知信息成功
            }
            Response.End();
        }
    }
}