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
                    string merchant_private_key = "MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAOlji/yfZS3DKefgXJandiPPlheoJeOPgiqdy7WO5kYDxpYIbcmJXp1sIxC0MqY5pyWIOyBlW/NHWDfRyTIYvFm6vxx9m/TeTYKRXHPNdCefXNeV5XoCB7s7gJoeqR1qwgBE0a2Sw4slMrKL4LSv0J0o3po9SgYhP06m3az9dZzDAgMBAAECgYEAgLGdvX1/Y06cyvnS1FgfMvwCvaTAz8yQcEN20vSrUaw/Uvfu4hTXkWLRqyJSU4qy+mU2hVSe8XVD1fPeR7uQD7TWqmXCaIC12DqWFvA2Eio/x4nIixj5t4PN4lbUVBHH/OnHypRWFNcQemR4OWcYCWi5/cYshEaOk9XQnUQKvcECQQD7ZBDA+d8IXM7jtlNNEp+ySw87iz8pKhBoVt3XrAkGczuM9A+ZY+ReViHVxuxqd5AgxSPj1hoQeFR0BnPEVKgzAkEA7ar8kvzp9sPMDujN9QSr+yq73CTlRr8W5q0vMCxsJagie6znvtsAzka3EkI0Srix5fHjRWrWWknblDat5CnpMQJAGfYLhOJJFiP/eaSqlZsGwvLdb9jBfQ7LAvt1jYBGrlPYIoZR1hVq0BM1C06vu479Y2T4f97scib7EcBY1D2p4QJALvdmOEngllI1BhE9ehj1P4o/W46y/FsQ1H8O3y84I+zo+5W8qjuvGhRhYytQGCANj8pQRkyJlYgY614u2PLrUQJAAv9neGYMeFdZnanlsdg6EepWAzvT2tv45WF74BQKJzZF+IFtE1XD85QcqkOJ3o8WgVwXK6/BCn5vNahImz+Ohg==";

                    merchant_private_key = testOrder.HttpHelp.RSAPrivateKeyJava2DotNet(merchant_private_key);
                     //signature
                    string signData = testOrder.HttpHelp.RSASign(signStr, merchant_private_key);
                    
                     signData = HttpUtility.UrlEncode(signData);
                     
                     //Array data
                     string para = signStr + "&sign_type=" + sign_type + "&sign=" + signData;
                     //post data
                     string _xml = testOrder.HttpHelp.HttpPost("https://api.suifupay.com/gateway/api/scanpay", para);

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
