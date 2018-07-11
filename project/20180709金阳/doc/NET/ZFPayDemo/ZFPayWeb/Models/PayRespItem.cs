namespace ZFPayWeb.Models
{
    /// <summary>
    /// 支付订单提交
    /// </summary>
    public class PayRespItem
    {
        /// <summary>
        /// 商户ID
        /// </summary>
        public int r1_mchtid { get; set; }

        /// <summary>
        /// 系统平台订单号码
        /// </summary>
        public string r2_systemorderno { get; set; }

        /// <summary>
        /// 商户的平台订单号
        /// </summary>
        public string r3_orderno { get; set; }

        /// <summary>
        /// 订单金额，以元为单位，最小金额为0.01
        /// </summary>
        public decimal r4_amount { get; set; }

        /// <summary>
        /// 版本号 v3.1
        /// </summary>
        public string r5_version { get; set; }

        /// <summary>
        /// 二维码信息
        /// </summary>
        public string r6_qrcode { get; set; }

        /// <summary>
        /// 支付类型
        /// </summary>
        public string r7_paytype { get; set; }

        /// <summary>
        /// 签名
        /// </summary>
        public string sign { get; set; }
    }
}