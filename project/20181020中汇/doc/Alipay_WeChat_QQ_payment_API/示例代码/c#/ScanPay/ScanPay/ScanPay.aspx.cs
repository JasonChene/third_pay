using System;
using System.Web;
using System.IO;
using System.Xml;
using System.Xml.XPath;
using System.Xml.Linq;
using ThoughtWorks.QRCode.Codec;
using System.Drawing;
using System.Text.RegularExpressions;

namespace CSharpTestPay
{
    public partial class _Default : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {
            try
            {
                
				////////////////////////To receive the parameter form HTML form//////////////////////

                string interface_version = Request.Form["interface_version"].ToString().Trim();
                string service_type = Request.Form["service_type"].ToString().Trim();
                string sign_type = Request.Form["sign_type"].ToString().Trim();
                string merchant_code = Request.Form["merchant_code"].ToString().Trim();
                string order_no = Request.Form["order_no"].ToString().Trim();
                string order_time = Request.Form["order_time"].ToString().Trim();
                string order_amount = Request.Form["order_amount"].ToString().Trim();
                string product_name = Request.Form["product_name"].ToString().Trim();
                string product_code = Request.Form["product_code"].ToString().Trim();
                string product_num = Request.Form["product_num"].ToString().Trim();
                string product_desc = Request.Form["product_desc"].ToString().Trim();
                string extra_return_param = Request.Form["extra_return_param"].ToString().Trim();
                string extend_param = Request.Form["extend_param"].ToString().Trim();
                string notify_url = Request.Form["notify_url"].ToString().Trim();
                string client_ip = Request.Form["client_ip"].ToString().Trim();

                ////////////////Array data//////////////////
                string signStr = "";
                if (client_ip != "")
                {
                    signStr = signStr + "client_ip=" + client_ip + "&";
                }
                if (extend_param != "")
                {
                    signStr = signStr + "extend_param=" + extend_param + "&";
                }
                if (extra_return_param != "")
                {
                    signStr = signStr + "extra_return_param=" + extra_return_param + "&";
                }
                if (interface_version != "")
                {
                    signStr = signStr + "interface_version=" + interface_version + "&";
                }
                if (merchant_code != "")
                {
                    signStr = signStr + "merchant_code=" + merchant_code + "&";
                }
                if (notify_url != "")
                {
                    signStr = signStr + "notify_url=" + notify_url + "&";
                }
                if (order_amount != "")
                {
                    signStr = signStr + "order_amount=" + order_amount + "&";
                }
                if (order_no != "")
                {
                    signStr = signStr + "order_no=" + order_no + "&";
                }
                if (order_time != "")
                {
                    signStr = signStr + "order_time=" + order_time + "&";
                }
                if (product_code != "")
                {
                    signStr = signStr + "product_code=" + product_code + "&";
                }
                if (product_desc != "")
                {
                    signStr = signStr + "product_desc=" + product_desc + "&";
                }
                if (product_name != "")
                {
                    signStr = signStr + "product_name=" + product_name + "&";
                }
                if (product_num != "")
                {
                    signStr = signStr + "product_num=" + product_num + "&";
                }
                if (service_type != "")
                {
                    signStr = signStr + "service_type=" + service_type;
                }

                    //merchant private key
                    string merchant_private_key = "MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBALIgluJzrKhOw/+sKlUZW+GFISjeXCqNz45rhEd4pRhg92ZDwyJxsIWVMUggCJLjSAke2wmVOiYJB/V9rNwlCzal5BGCSD0y8VckUb8LMv5wnNxr3wjrXf6IbZWsgNOwZg1mo+Cji5LCwoKvYvbZNK33Nb9MwbBh1PHUVP8AsfM3AgMBAAECgYEAr6oyAtse39Dlu+OWz9u1X/+BhyNa82Bs20Au8KkK77LY6NJUw0gpVGOgeUeWDP31kYELdDTlZpMrdS9eZLBnj/QofFTx7GSeod+vV13cgA6rc0yzjTp25Dm7Xzihf15R5JiNIFzlSYC2TLz+HcJoprxY6Pf6I/1qBjZuoC67eEECQQDjDhEI7s010aXXYQy3xwC/RUDosnfMARqRCpYFCYmoyMiUZ7+ohIvWkkCcwHx7VNKnXfmF0ezdXNT2TCKfXj6hAkEAyNXFKkCPtbg+GFqUlxlfta1s7FJuC1b8ZyaA1ygqUK5PJUoEKR9UcDg0uCKx4Zofpm46WCHx8w8M0+Abss8a1wJAA5JqFDDli44zxLKjJ5T63wdw4PhFyDDQQS3gdE3VG5GlDiifrEABjyuX1p90leAcvENPNJq71jOqqgFCni02YQJAQ8q09SA54lNA0qOwyJhOEFtsCxGAB9/i70a18uqh7f4IxUOIyADFVeQDF6zOcqK90EYg96Ltsuf/on1hnCgAnQJBANGvRflfL1Xvelv2jb446Gnq83IwQ6WJvO8z7/awfMmDsC88MI2bE0xcWJ2QPZZEVJkgCmwOXc26G+z0eei/z/U=";

                    merchant_private_key = testOrder.HttpHelp.RSAPrivateKeyJava2DotNet(merchant_private_key);
                     //signature
                    string signData = testOrder.HttpHelp.RSASign(signStr, merchant_private_key);
                    
                     signData = HttpUtility.UrlEncode(signData);
                     
                     //Array data
                     string para = signStr + "&sign_type=" + sign_type + "&sign=" + signData;
                     //post data
                     string _xml = testOrder.HttpHelp.HttpPost("https://api.vsdpay.com/gateway/api/scanpay", para);

                     //get data from XML
                     var el = XElement.Load(new StringReader(_xml));
                     //get Qrcode
                     var qrcode1 = el.XPathSelectElement("/response/qrcode");
                     if (qrcode1 == null)
                     {
                         Response.Write("msg:" + _xml + "<br/>");
                         Response.End();
                     }
                     string qrcode = Regex.Match(qrcode1.ToString(), "(?<=>).*?(?=<)").Value;   //qrcode
                     qrcode = HttpUtility.HtmlDecode(qrcode);
                     Bitmap bmp = QRCodeUtil.QRCodeHelper.GetQRCodeBmp(qrcode);
                     string str = HttpContext.Current.Request.MapPath(@"qrcode\aa.bmp");
                     bmp.Save(str);

   
            }
			finally{
            }
        }
    }
}

namespace QRCodeUtil
{
    /// <summary>
    /// 二维码生成
    /// </summary>
    public class QRCodeHelper
    {
        #region 根据链接获取二维码
        /// <summary>
        /// 根据链接获取二维码
        /// </summary>
        /// <param name="link">链接</param>
        /// <returns>返回二维码图片</returns>
        public static Bitmap GetQRCodeBmp(string link)
        {
            QRCodeEncoder qrCodeEncoder = new QRCodeEncoder();
            qrCodeEncoder.QRCodeEncodeMode = QRCodeEncoder.ENCODE_MODE.BYTE;
            qrCodeEncoder.QRCodeScale = 4;
            qrCodeEncoder.QRCodeVersion = 0;
            qrCodeEncoder.QRCodeErrorCorrect = QRCodeEncoder.ERROR_CORRECTION.M;
            Bitmap bmp = qrCodeEncoder.Encode(link);

            return bmp;
        }
        #endregion

    }
}
