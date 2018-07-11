using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Web;

namespace ZFPayWeb.Common
{
    public static class FormUtil
    {

        /// <summary>
        /// 
        /// </summary>
        /// <param name="p1_mchtid"></param>
        /// <param name="p2_paytype"></param>
        /// <param name="p3_paymoney"></param>
        /// <param name="p4_orderno"></param>
        /// <param name="p5_callbackurl"></param>
        /// <param name="p6_notifyurl"></param>
        /// <param name="p7_version"></param>
        /// <param name="p8_signtype"></param>
        /// <param name="p9_attach"></param>
        /// <param name="p10_appname"></param>
        /// <param name="p11_isshow"></param>
        /// <param name="p12_orderip"></param>
        /// <param name="sign"></param>
        /// <returns></returns>
        public static string RequestForm(string p1_mchtid, string p2_paytype, string p3_paymoney, string p4_orderno,
            string p5_callbackurl, string p6_notifyurl, string p7_version, string p8_signtype, string p9_attach,
            string p10_appname, string p11_isshow, string p12_orderip,string sign)
        {
            Dictionary<String, String> sPara = new Dictionary<String, String>
            {
                {"p1_mchtid", p1_mchtid},
                {"p2_paytype", p2_paytype},
                {"p3_paymoney", p3_paymoney},
                {"p4_orderno", p4_orderno},
                {"p5_callbackurl", p5_callbackurl},
                {"p6_notifyurl", p6_notifyurl},
                {"p7_version", p7_version},
                {"p8_signtype", p8_signtype},
                {"p9_attach", p9_attach},
                {"p10_appname", p10_appname},
                {"p11_isshow", p11_isshow},
                {"p12_orderip", p12_orderip},
                {"sign", sign}
            };

            StringBuilder sbHtml = new StringBuilder();
            //post方式传递
            sbHtml.Append("<form id=\"appForm\" name=\"appForm\" action=\"").Append(Config.PAYURL).Append("\" method=\"post\">");
            
            String name = "";
            String value = "";

            foreach (string key in sPara.Keys)
            {
                name = key;
                value = sPara[key];
                if (value != null && !"".Equals(value))
                {
                    sbHtml.Append("<input type=\"hidden\" name=\"").Append(name).Append("\" value=\"" + value + "\"/>");
                }
            }
            //submit按钮
            sbHtml.Append("<input type=\"submit\" value=\"确认付款\"></form>");
            //sbHtml.Append("</form>");
            //sbHtml.Append("<script>appForm.submit();</script>");
            return sbHtml.ToString();

        }

    }
}