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
                    string merchant_private_key = "MIICdQIBADANBgkqhkiG9w0BAQEFAASCAl8wggJbAgEAAoGBAL8LxUg+Q4t96O4Y4hS/ZMDbZw8u9PIHGFJLjQyllfbM49ZsJw1hH3UD8imtzde7qE9D8zejQh5ZFqEKN5+rlFi8mTLZRVpkbiJ5HtuEjHRTCYtytG6uK8uFnQEmH2VudFIZ85NJA895eNizU0ms88upxpRy1I/8bsYzbjqzDKMhAgMBAAECgYBJleQQNoNXyFCe3RC/wxSwwBGLJKAOVTNGB3m1xFXl8PdVEOVd3un57WIqMZrWnJ5woZCd/pEqFVCFCOVx5+nENYDZAwPQ4F/lqwfifEPta0wsMHb17eicVIOitCRDk3O1nvFjv4kBQnP67UITMhCDaJWnTSOYiZ3rZTpA5HlLAQJBAOpzG09ZtSnLUvzKVBIGTV+GK5eGRvKDDTYtCvok7DWKq3iwpy2yBopKypkHIh4IsCWYBVUT3nni/Mh17AO0qNECQQDQm1JUehfOEG3A5AkApuRR3O2tc3pd4DwfoYpzqyE/kp3PdzRYSDamVOSU0Oaz03PwPPYF+3TCufsZZVjErelRAkBDtXCKrx657kWOSiSTfAx2bPpD7Xyp5x02qzWDXox1PhIdbe8qLELlR4pRPZUl1V6BzPClTHKxAtP8VMoPm+oxAkAtvyIa7Htz8R5ggqGGxxKi8TQeKYjYNWh5908Jdqnf6yM4cAfGpG93on5ONFGjdeei83twbGh6m5Z5R0RkPU9BAkAmFai5BykjZH0DJ/z2Du97J1X4btwTT3Ywjmd+OHpQ9weCkHPHd1IHj0YMt3cONBSOwZ+3B9zCjwy0x8POMb0e";

                    merchant_private_key = testOrder.HttpHelp.RSAPrivateKeyJava2DotNet(merchant_private_key);
                     //signature
                    string signData = testOrder.HttpHelp.RSASign(signStr, merchant_private_key);
                    
                     signData = HttpUtility.UrlEncode(signData);
                     
                     //Array data
                     string para = signStr + "&sign_type=" + sign_type + "&sign=" + signData;
                     //post data
                     string _xml = testOrder.HttpHelp.HttpPost("https://api.islpay.hk/gateway/api/scanpay", para);

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
