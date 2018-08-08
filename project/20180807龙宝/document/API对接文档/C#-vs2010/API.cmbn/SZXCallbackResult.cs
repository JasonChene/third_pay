using System;

namespace API.cmbn
{
	/// <summary>
	/// SZXCallbackResult 的实体类
    /// 【支付结果返回】(14个参数)
    /// r0_Cmd,r1_Code,p1_MerId,p2_Order,p3_Amt,p4_FrpId ,p5_CardNo,p6_confirmAmount,p7_realAmount,p8_cardStatus,p9_MP,pb_BalanceAmt，pc_BalanceAct,hmac
	/// </summary>
	public class SZXCallbackResult
	{
		// 定义内部变量
		
        private string r0_Cmd;
        private string r1_Code;
        private string p1_MerId;
        private string p2_Order;
        private string p3_Amt;
        private string p4_FrpId;
        private string p5_CardNo;
        private string p6_confirmAmount;
        private string p7_realAmount;
        private string p8_cardStatus;
        private string p9_MP;
        private string pb_BalanceAmt;
        private string pc_BalanceAct;
        private string hmac;
        private string errMsg;

        public SZXCallbackResult(string r0_Cmd, string r1_Code, string p1_MerId, string p2_Order, string p3_Amt, string p4_FrpId, string p5_CardNo, string p6_confirmAmount, string p7_realAmount, string p8_cardStatus, string p9_MP, string pb_BalanceAmt, string pc_BalanceAct, string hmac,string errMsg)
		{
			this.r0_Cmd = r0_Cmd;
            this.r1_Code = r1_Code;
            this.p1_MerId = p1_MerId;
            this.p2_Order = p2_Order;
            this.p3_Amt = p3_Amt;
            this.p4_FrpId = p4_FrpId;
            this.p5_CardNo = p5_CardNo;
            this.p6_confirmAmount = p6_confirmAmount;
            this.p7_realAmount = p7_realAmount;
            this.p8_cardStatus = p8_cardStatus;
            this.p9_MP = p9_MP;
            this.pb_BalanceAmt = pb_BalanceAmt;
            this.pc_BalanceAct = pc_BalanceAct;
            this.hmac = hmac;
            this.errMsg = errMsg;
		}

		// 公共属性
		public string R0_Cmd
		{
			get{return r0_Cmd;}
		}
		public string R1_Code
		{
			get{return r1_Code;}
		}
		public string P1_MerId
		{
			get{return p1_MerId;}
		}
        public string P2_Order
		{
            get { return p2_Order; }
		}
        public string P3_Amt
		{
            get { return p3_Amt; }
		}
        public string P4_FrpId
        {
            get { return p4_FrpId; }
        }
        public string P5_CardNo
        {
            get { return p5_CardNo; }
        }
        public string P6_confirmAmount
        {
            get { return p6_confirmAmount; }
        }
        public string P7_realAmount
        {
            get { return p7_realAmount; }
        }
        public string P8_cardStatus
        {
            get { return p8_cardStatus; }
        }
        public string P9_MP
        {
            get { return p9_MP; }
        }
        public string Pb_BalanceAmt
        {
            get { return pb_BalanceAmt; }
        }
        public string Pc_BalanceAct
        {
            get { return pc_BalanceAct; }
        }
        public string Hmac
		{
			get{return hmac;}
        }
        public string ErrMsg
        {
            get { return errMsg; }
        }
	}
}
