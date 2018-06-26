using System;
using API.utils;
using System.Configuration;

namespace API.cmbn
{
    /// <summary>
    /// SZX 的摘要说明。
    /// </summary>
    public abstract class SZX
    {
        public SZX()
        {

        }
        // 交易的请求地址
        private static string nodeAuthorizationURL = ConfigurationManager.AppSettings["authorizationCardURL"];

        // 商户编号
        private static string merchantId = ConfigurationManager.AppSettings["merhantId"];

        // 商户密钥
        private static string keyValue = ConfigurationManager.AppSettings["keyValue"];
        #region AnnulCard 非银行卡支付专业版支付请求(通讯)
        /// <summary>
        /// 非银行卡支付支付请求(通讯)
        /// </summary>
        /// <param name="p1_MerId">商户编号</param>
        /// <param name="keyValue">商户密钥</param>
        /// <param name="p2_Order">订单号</param>
        /// <param name="p3_Amt">支付卡金额</param>
        /// <param name="p8_Url">回报Url</param>
        /// <param name="pa_MP">商户扩展参数</param>
        /// <param name="pa7_cardNo">支付卡序列号</param>
        /// <param name="pa8_cardPwd">支付卡密码</param>
        /// <param name="pd_FrpId">银行编码</param>
        /// <param name="pa0_Mode">支付卡支付传递模式</param>
        /// <param name="pr_NeedResponse">应答机制</param>
        /// <returns>SZXResult</returns>
        public static SZXResult AnnulCard(string p2_Order, string p3_Amt,string p4_verifyAmt, string p5_Pid, string p6_Pcat, string p7_Pdesc, string p8_Url,
        string pa_MP, string pa7_cardAmt, string pa8_cardNo, string pa9_cardPwd, string pd_FrpId,
        string pr_NeedResponse, string pz_userId, string pz1_userRegTime)
        {
            string sbOld = "";

            sbOld += "ChargeCardDirect";
            sbOld += merchantId;
            sbOld += p2_Order;
            sbOld += p3_Amt;
            sbOld += p4_verifyAmt;
            sbOld += p5_Pid;
            sbOld += p6_Pcat;
            sbOld += p7_Pdesc;
            sbOld += p8_Url;
            sbOld += pa_MP;
            sbOld += pa7_cardAmt;
            sbOld += pa8_cardNo;
            sbOld += pa9_cardPwd;
            sbOld += pd_FrpId;
            sbOld += pr_NeedResponse;
            sbOld += pz_userId;
            sbOld += pz1_userRegTime;
            string hmac = Digest.HmacSign(sbOld, keyValue);
            logHmac(p2_Order, sbOld, keyValue, hmac);
            string para = "";
            para += "?p0_Cmd=ChargeCardDirect";
            para += "&p1_MerId=" + merchantId;
            para += "&p2_Order=" + p2_Order;
            para += "&p3_Amt=" + p3_Amt;
            para += "&p4_verifyAmt=" + p4_verifyAmt;
            para += "&p5_Pid=" + System.Web.HttpUtility.UrlEncode(p5_Pid, System.Text.Encoding.GetEncoding("gb2312"));
            para += "&p6_Pcat=" + System.Web.HttpUtility.UrlEncode(p6_Pcat, System.Text.Encoding.GetEncoding("gb2312"));
            para += "&p7_Pdesc=" + System.Web.HttpUtility.UrlEncode(p7_Pdesc, System.Text.Encoding.GetEncoding("gb2312"));
            para += "&p8_Url=" + System.Web.HttpUtility.UrlEncode(p8_Url, System.Text.Encoding.GetEncoding("gb2312"));
            para += "&pa_MP=" + System.Web.HttpUtility.UrlEncode(pa_MP, System.Text.Encoding.GetEncoding("gb2312"));
            para += "&pa7_cardAmt=" + System.Web.HttpUtility.UrlEncode(pa7_cardAmt, System.Text.Encoding.GetEncoding("gb2312"));
            para += "&pa8_cardNo=" + System.Web.HttpUtility.UrlEncode(pa8_cardNo, System.Text.Encoding.GetEncoding("gb2312"));
            para += "&pa9_cardPwd=" + System.Web.HttpUtility.UrlEncode(pa9_cardPwd, System.Text.Encoding.GetEncoding("gb2312"));
            para += "&pd_FrpId=" + pd_FrpId;
            para += "&pr_NeedResponse=" + pr_NeedResponse;
            para += "&pz_userId=" + pz_userId;
            para += "&pz1_userRegTime=" + System.Web.HttpUtility.UrlEncode(pz1_userRegTime, System.Text.Encoding.GetEncoding("gb2312"));
            para += "&hmac=" + hmac;

            logURL(nodeAuthorizationURL + para);
            string reqResult = HttpUtils.SendRequest(nodeAuthorizationURL, para);
            logReqResult(reqResult);

            string r0_Cmd = FormatQueryString.GetQueryString("r0_Cmd", reqResult, '\n');
            string r1_Code = FormatQueryString.GetQueryString("r1_Code", reqResult, '\n');
            string r6_Order = FormatQueryString.GetQueryString("r6_Order", reqResult, '\n');
            string rq_ReturnMsg = FormatQueryString.GetQueryString("rq_ReturnMsg", reqResult, '\n');

            hmac = FormatQueryString.GetQueryString("hmac", reqResult, '\n');

            SZXResult result = new SZXResult(r0_Cmd, r1_Code, r6_Order, rq_ReturnMsg, hmac, nodeAuthorizationURL + para, reqResult);

            return result;
        }
        /// <summary>
        /// 接收返回数据
        /// </summary>
        /// <param name="p1_MerId"></param>
        /// <param name="keyValue"></param>
        /// <param name="r0_Cmd"></param>
        /// <param name="r1_Code"></param>
        /// <param name="rb_Order"></param>
        /// <param name="r2_TrxId"></param>
        /// <param name="pa_MP"></param>
        /// <param name="rc_Amt"></param>
        /// <param name="rq_CardNo"></param>
        /// <param name="hmac"></param>
        /// <returns>SZXCallbackResult</returns>     

        //接收返回数据 校验 
        public static SZXCallbackResult VerifyCallback(string r0_Cmd, string r1_Code, string p1_MerId, string p2_Order, string p3_Amt, string p4_FrpId, string p5_CardNo, string p6_confirmAmount, string p7_realAmount, string p8_cardStatus, string p9_MP, string pb_BalanceAmt, string pc_BalanceAct, string hmac)
        {
            string sbOld = "";
            sbOld += r0_Cmd;
            sbOld += r1_Code;
            sbOld += p1_MerId;
            sbOld += p2_Order;
            sbOld += p3_Amt;
            sbOld += p4_FrpId;
            sbOld += p5_CardNo;
            sbOld += p6_confirmAmount;
            sbOld += p7_realAmount;
            sbOld += p8_cardStatus;
            sbOld += p9_MP;
            sbOld += pb_BalanceAmt;
            sbOld += pc_BalanceAct;

            string nhmac = Digest.HmacSign(sbOld, keyValue);
            logHmac(p2_Order, sbOld, keyValue, hmac);
            if (nhmac == hmac)
            {
                return new SZXCallbackResult(r0_Cmd, r1_Code, p1_MerId, p2_Order, p3_Amt, p4_FrpId, p5_CardNo, p6_confirmAmount, p7_realAmount, p8_cardStatus, p9_MP, pb_BalanceAmt, pc_BalanceAct, hmac, "");
            }
            else
            {
                return new SZXCallbackResult(r0_Cmd, r1_Code, p1_MerId, p2_Order, p3_Amt, p4_FrpId, p5_CardNo, p6_confirmAmount, p7_realAmount, p8_cardStatus, p9_MP, pb_BalanceAmt, pc_BalanceAct, hmac, Digest.HmacSign(sbOld, keyValue) + "<br>sbOld:" + sbOld);
            }
        }
        #endregion

  
        //日志
        public static void logHmac(string orderid, string str, string keyValue, string hmac)
        {
            log.logstr("orderid[" + orderid + "]" + "str[" + str + "]" + "keyValue[" + keyValue + "]" + "hmac[" + hmac + "]");
        }
        public static void logReqResult(string reqResult)
        {
            log.logstr("reqResult[" + reqResult + "]");
        }
        public static void logURL(string url)
        {
            log.logstr("url[" + url + "]");
        }
    }
}
