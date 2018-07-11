using System.Configuration;

namespace ZFPayWeb.Common
{
    public static class Config
    {
        /// <summary>
        /// 第三方的商户ID
        /// </summary>
        public static string P1_MCHTID = ConfigurationManager.AppSettings["P1_MCHTID"];
        
        /// <summary>
        /// 第三方的商户ID对应的MD5KEY的密钥
        /// </summary>
        public static string SIGNKEY = ConfigurationManager.AppSettings["SIGNKEY"];

        /// <summary>
        /// 支付入款提交的URL
        /// </summary>
        public static string PAYURL = ConfigurationManager.AppSettings["PAYURL"];

    }
}