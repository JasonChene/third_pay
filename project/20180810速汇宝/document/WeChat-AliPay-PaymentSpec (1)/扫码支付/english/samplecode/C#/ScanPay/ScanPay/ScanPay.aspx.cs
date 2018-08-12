using System;
using System.Web;
using System.IO;
using System.Xml;
using System.Xml.XPath;
using System.Xml.Linq;
using ThoughtWorks.QRCode.Codec;
using ThoughtWorks.QRCode.Codec.Data;
using ThoughtWorks.QRCode.Codec.Util;
using System.Drawing;
using System.Text.RegularExpressions;
using DinpayRSAAPI.COM.Dinpay.RsaUtils;

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

                if (sign_type == "RSA-S")//RSA-S Sign
                {
                    //merchant private key
                    string merPriKey = "MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBALf/+xHa1fDTCsLYPJLHy80aWq3djuV1T34sEsjp7UpLmV9zmOVMYXsoFNKQIcEzei4QdaqnVknzmIl7n1oXmAgHaSUF3qHjCttscDZcTWyrbXKSNr8arHv8hGJrfNB/Ea/+oSTIY7H5cAtWg6VmoPCHvqjafW8/UP60PdqYewrtAgMBAAECgYEAofXhsyK0RKoPg9jA4NabLuuuu/IU8ScklMQIuO8oHsiStXFUOSnVeImcYofaHmzIdDmqyU9IZgnUz9eQOcYg3BotUdUPcGgoqAqDVtmftqjmldP6F6urFpXBazqBrrfJVIgLyNw4PGK6/EmdQxBEtqqgXppRv/ZVZzZPkwObEuECQQDenAam9eAuJYveHtAthkusutsVG5E3gJiXhRhoAqiSQC9mXLTgaWV7zJyA5zYPMvh6IviX/7H+Bqp14lT9wctFAkEA05ljSYShWTCFThtJxJ2d8zq6xCjBgETAdhiH85O/VrdKpwITV/6psByUKp42IdqMJwOaBgnnct8iDK/TAJLniQJABdo+RodyVGRCUB2pRXkhZjInbl+iKr5jxKAIKzveqLGtTViknL3IoD+Z4b2yayXg6H0g4gYj7NTKCH1h1KYSrQJBALbgbcg/YbeU0NF1kibk1ns9+ebJFpvGT9SBVRZ2TjsjBNkcWR2HEp8LxB6lSEGwActCOJ8Zdjh4kpQGbcWkMYkCQAXBTFiyyImO+sfCccVuDSsWS+9jrc5KadHGIvhfoRjIj2VuUKzJ+mXbmXuXnOYmsAefjnMCI6gGtaqkzl527tw=";
                     
                     merPriKey = testOrder.HttpHelp.RSAPrivateKeyJava2DotNet(merPriKey);
                     //signature
                     string signData = testOrder.HttpHelp.RSASign(signStr, merPriKey);
                    
                     signData = HttpUtility.UrlEncode(signData);
                     //Array data
                     string para = signStr + "&sign_type=" + sign_type + "&sign=" + signData;
                     //post data
                     string _xml = testOrder.HttpHelp.HttpPost("https://api.xhbill.com/gateway/api/scanpay", para);

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

                     //generate qrcode
                    My.Utility.QRCodeHandler qr = new My.Utility.QRCodeHandler();
                    string path = AppDomain.CurrentDomain.SetupInformation.ApplicationBase + @"/qrcode/";  //path  
                     string qrString = qrcode;                                                            //qrcode  
                     string logoFilePath = path + "my.jpg";                                              //Logo
                     string filePath = path + "myCode.jpg";                                             //qrcode name  
                     qr.CreateQRCode(qrString, "Byte", 6, 6, "H", filePath, true, logoFilePath);       //generate

                     
                }
                else  //RSA Sign
                {
                    RSAWithHardware rsa = new RSAWithHardware();
                    string merPubKeyDir = "D:/1111110166.pfx";   //certificate path
                    string password = "87654321";                //certificate password
                    RSAWithHardware rsaWithH = new RSAWithHardware();
                    rsaWithH.Init(merPubKeyDir, password, "D:/dinpayRSAKeyVersion");//make the Keyversion path same with the certificate
                    string signData = rsaWithH.Sign(signStr);    //signature
                    signData = HttpUtility.UrlEncode(signData);

                    //Array data
                    string para = signStr + "&sign_type=" + sign_type + "&sign=" + signData;
                    //post data
                    string _xml = testOrder.HttpHelp.HttpPost("https://api.xhbill.com/gateway/api/weixin", para);

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

                    //generate qrcode
                    My.Utility.QRCodeHandler qr = new My.Utility.QRCodeHandler();
                    string path = AppDomain.CurrentDomain.SetupInformation.ApplicationBase + @"/qrcode/";  //path  
                    string qrString = qrcode;                                                            //qrcode  
                    string logoFilePath = path + "my.jpg";                                              //Logo
                    string filePath = path + "myCode.jpg";                                             //qrcode name  
                    qr.CreateQRCode(qrString, "Byte", 6, 6, "H", filePath, true, logoFilePath);       //generate

                }
            }
			finally{
            }
        }
    }
}

namespace My.Utility
{
    /// <summary>  
    /// 二维码处理类  
    /// </summary>  
    public class QRCodeHandler
    {
        /// <summary>  
        /// 创建二维码  
        /// </summary>  
        /// <param name="QRString">二维码字符串</param>  
        /// <param name="QRCodeEncodeMode">二维码编码(Byte、AlphaNumeric、Numeric)</param>  
        /// <param name="QRCodeScale">二维码尺寸(Version为0时，1：26x26，每加1宽和高各加25</param>  
        /// <param name="QRCodeVersion">二维码密集度0-40</param>  
        /// <param name="QRCodeErrorCorrect">二维码纠错能力(L：7% M：15% Q：25% H：30%)</param>  
        /// <param name="filePath">保存路径</param>  
        /// <param name="hasLogo">是否有logo(logo尺寸50x50，QRCodeScale>=5，QRCodeErrorCorrect为H级)</param>  
        /// <param name="logoFilePath">logo路径</param>  
        /// <returns></returns>  
        public bool CreateQRCode(string QRString, string QRCodeEncodeMode, short QRCodeScale, int QRCodeVersion, string QRCodeErrorCorrect, string filePath, bool hasLogo, string logoFilePath)
        {
            bool result = true;

            QRCodeEncoder qrCodeEncoder = new QRCodeEncoder();

            switch (QRCodeEncodeMode)
            {
                case "Byte":
                    qrCodeEncoder.QRCodeEncodeMode = QRCodeEncoder.ENCODE_MODE.BYTE;
                    break;
                case "AlphaNumeric":
                    qrCodeEncoder.QRCodeEncodeMode = QRCodeEncoder.ENCODE_MODE.ALPHA_NUMERIC;
                    break;
                case "Numeric":
                    qrCodeEncoder.QRCodeEncodeMode = QRCodeEncoder.ENCODE_MODE.NUMERIC;
                    break;
                default:
                    qrCodeEncoder.QRCodeEncodeMode = QRCodeEncoder.ENCODE_MODE.BYTE;
                    break;
            }

            qrCodeEncoder.QRCodeScale = QRCodeScale;
            qrCodeEncoder.QRCodeVersion = QRCodeVersion;

            switch (QRCodeErrorCorrect)
            {
                case "L":
                    qrCodeEncoder.QRCodeErrorCorrect = QRCodeEncoder.ERROR_CORRECTION.L;
                    break;
                case "M":
                    qrCodeEncoder.QRCodeErrorCorrect = QRCodeEncoder.ERROR_CORRECTION.M;
                    break;
                case "Q":
                    qrCodeEncoder.QRCodeErrorCorrect = QRCodeEncoder.ERROR_CORRECTION.Q;
                    break;
                case "H":
                    qrCodeEncoder.QRCodeErrorCorrect = QRCodeEncoder.ERROR_CORRECTION.H;
                    break;
                default:
                    qrCodeEncoder.QRCodeErrorCorrect = QRCodeEncoder.ERROR_CORRECTION.H;
                    break;
            }

            try
            {
                Image image = qrCodeEncoder.Encode(QRString, System.Text.Encoding.UTF8);
                System.IO.FileStream fs = new System.IO.FileStream(filePath, System.IO.FileMode.OpenOrCreate, System.IO.FileAccess.Write);
                image.Save(fs, System.Drawing.Imaging.ImageFormat.Jpeg);
                fs.Close();

                if (hasLogo)
                {
                    Image copyImage = System.Drawing.Image.FromFile(logoFilePath);
                    Graphics g = Graphics.FromImage(image);
                    int x = image.Width / 2 - copyImage.Width / 2;
                    int y = image.Height / 2 - copyImage.Height / 2;
                    g.DrawImage(copyImage, new Rectangle(x, y, copyImage.Width, copyImage.Height), 0, 0, copyImage.Width, copyImage.Height, GraphicsUnit.Pixel);
                    g.Dispose();

                    image.Save(filePath);
                    copyImage.Dispose();
                }
                image.Dispose();

            }
            catch (Exception ex)
            {
                result = false;
            }
            return result;
        }
    }
}