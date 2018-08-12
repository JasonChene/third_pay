using System;

namespace API.cmbn
{
	/// <summary>
    /// SZXResult 【提交返回】（5个参数）。
    /// p0_Cmd,r1_Code,r6_Order,rq_ReturnMsg,hma 
	/// </summary>
	[Serializable]
    public class SZXResult
    {
        private string r0_Cmd;
        private string r1_Code;
        private string r6_Order;
        private string rq_ReturnMsg;

        private string hmac;
        private string reqUrl;
        private string reqResult;

        public SZXResult(string r0_Cmd, string r1_Code, string r6_Order, string rq_ReturnMsg, string hmac, string reqUrl, string reqResult)
        {/*p0_Cmd  r1_Code r6_Order  rq_ReturnMsg  hmac */

            this.r0_Cmd = r0_Cmd;
            this.r1_Code = r1_Code;
            this.r6_Order = r6_Order;
            this.rq_ReturnMsg = rq_ReturnMsg;
            this.hmac = hmac;
            this.reqUrl = reqUrl;
            this.reqResult = reqResult;
        }
        public string R0_Cmd
        {
            get { return r0_Cmd; }
        }
        public string R1_Code
        {
            get { return r1_Code; }
        }
        public string R6_Order
        {
            get { return r6_Order; }
        }
        public string Rq_ReturnMsg
        {
            get { return rq_ReturnMsg; }
        }
        //--
        public string Hmac
        {
            get { return hmac; }
        }
        public string ReqUrl
        {
            get { return reqUrl; }
        }
        public string ReqResult
        {
            get { return reqResult; }
        }
    }


    public class SZXResultTest
    {

        private string r0_Cmd;
        private string r1_Code;
        private string r2_TrxId;
        private string r6_Order;
        private string rq_ReturnMsg;

        private string hmac;
        private string reqUrl;
        private string reqResult;

        public SZXResultTest(string r0_Cmd, string r1_Code, string r2_TrxId, string r6_Order, string rq_ReturnMsg, string hmac, string reqUrl, string reqResult, string reqPara)
        {
            this.r0_Cmd = r0_Cmd;
            this.r1_Code = r1_Code;
            this.r2_TrxId = r2_TrxId;
            this.r6_Order = r6_Order;
            this.rq_ReturnMsg = rq_ReturnMsg;

            this.hmac = hmac;
            this.reqUrl = reqUrl;
            this.reqResult = reqResult;

        }

        public string R0_Cmd
        {
            get { return r0_Cmd; }
        }
        public string R1_Code
        {
            get { return r1_Code; }
        }
        public string R2_TrxId
        {
            get { return r2_TrxId; }
        }
        public string R6_Order
        {
            get { return r6_Order; }
        }
        public string Rq_ReturnMsg
        {
            get { return rq_ReturnMsg; }
        }
        //--
        public string Hmac
        {
            get { return hmac; }
        }
        public string ReqUrl
        {
            get { return reqUrl; }
        }
        public string ReqResult
        {
            get { return reqResult; }
        }
    }
}
