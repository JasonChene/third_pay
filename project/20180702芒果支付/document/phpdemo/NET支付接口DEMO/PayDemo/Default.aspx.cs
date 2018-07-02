using System;
using System.Data;
using System.Configuration;
using System.Collections;
using System.Web;
using System.Web.Security;
using System.Web.UI;
using System.Web.UI.WebControls;
using System.Web.UI.WebControls.WebParts;
using System.Web.UI.HtmlControls;

using System.Collections.Generic;
using System.Net;
using System.Text;
using System.IO;
using System.Security.Cryptography;

using Org.BouncyCastle.Crypto.Parameters;
using Org.BouncyCastle.Security;
namespace PayDemo
{
    public partial class _Default : System.Web.UI.Page
    {
        string member_code = "2017090631"; ///商户号必填
        string member_secret = "c8be6f707ca0964ec7a7ef8ee9da013c"; ///商户密钥必填
        
        protected void Page_Load(object sender, EventArgs e)
        {
            if (!IsPostBack)
            {
                ///测试生成商户订单号
                down_sn.Text = DateTime.Now.ToString("yyyyMMddHHmmssfff");
            }
            
        }

        protected void Button1_Click(object sender, EventArgs e)
        {
            string sign = "";
            string json = "{";
            ///注意接收顺序只能按首字母从a至z排序，适应低版.net需要，所以这里不做高版本排序功能，json同理，高版可用Newtonsoft.Json.dll等第三方工具方便序列化。
            if (account_name.Text.Trim().Length > 0)
            {
                if (sign.Length > 0)
                    sign += "&account_name=" + account_name.Text.Trim();
                else
                    sign = "account_name=" + account_name.Text.Trim();
                json += "\"account_name\":\"" + account_name.Text.Trim() + "\"";
            }

            if (account_no.Text.Trim().Length > 0)
            {
                if (sign.Length > 0)
                    sign += "&account_no=" + account_no.Text.Trim();
                else
                    sign = "account_no=" + account_no.Text.Trim();

                if (json.Length > 1)
                    json += ",\"account_no\":\"" + account_no.Text.Trim() + "\"";
                else
                    json += "\"account_no\":\"" + account_no.Text.Trim() + "\"";
            }

            if (agent_type.SelectedIndex > -1)
            {
                if (sign.Length > 0)
                    sign += "&agent_type=" + agent_type.SelectedItem.Text;
                else
                    sign = "agent_type=" + agent_type.SelectedItem.Text;

                if (json.Length > 1)
                    json += ",\"agent_type\":\"" + agent_type.SelectedItem.Text + "\"";
                else
                    json += "\"agent_type\":\"" + agent_type.SelectedItem.Text + "\"";
                
            }

            ///价格为必填项，所以此项后不需要再判断
            if (amount.Text.Trim().Length > 0)
            {
                if (sign.Length > 0)
                    sign += "&amount=" + amount.Text.Trim();
                else
                    sign = "amount=" + amount.Text.Trim();

                if (json.Length > 1)
                    json += ",\"amount\":\"" + amount.Text.Trim() + "\"";
                else
                    json += "\"amount\":\"" + amount.Text.Trim() + "\"";
                
            }

            if (bank_segment.Text.Trim().Length > 0)
            {
                sign += "&bank_segment=" + bank_segment.Text.Trim();
                json += ",\"bank_segment\":\"" + bank_segment.Text.Trim() + "\"";
            }

            if (card_type.SelectedIndex > -1)
            {
                sign += "&card_type=" + card_type.SelectedItem.Text;
                json += ",\"card_type\":\"" + card_type.SelectedItem.Text + "\"";
            }

            if (down_sn.Text.Trim().Length > 0)
            {
                sign += "&down_sn=" + down_sn.Text.Trim();
                json += ",\"down_sn\":\"" + down_sn.Text.Trim() + "\"";
            }

            if (id_card_no.Text.Trim().Length > 0)
            {
                sign += "&id_card_no=" + id_card_no.Text.Trim();
                json += ",\"id_card_no\":\"" + id_card_no.Text.Trim() + "\"";
            }

            if (mobile.Text.Trim().Length > 0)
            {
                sign += "&mobile=" + mobile.Text.Trim();
                json += ",\"mobile\":\"" + mobile.Text.Trim() + "\"";
            }

            if (notify_url.Text.Trim().Length > 0)
            {
                sign += "&notify_url=" + notify_url.Text.Trim();
                json += ",\"notify_url\":\"" + notify_url.Text.Trim() + "\"";
            }

            if (return_url.Text.Trim().Length > 0)
            {
                sign += "&return_url=" + return_url.Text.Trim();
                json += ",\"return_url\":\"" + return_url.Text.Trim() + "\"";
            }

            if (subject.Text.Trim().Length > 0)
            {
                sign += "&subject=" + subject.Text.Trim();
                json += ",\"subject\":\"" + subject.Text.Trim() + "\"";
            }

            if (type_code.SelectedIndex> -1)
            {
                sign += "&type_code=" + type_code.SelectedItem.Value;
                json += ",\"type_code\":\"" + type_code.SelectedItem.Value + "\"";
            }

            if (user_type.SelectedIndex > -1)
            {
                sign += "&user_type=" + user_type.SelectedItem.Text;
                json += ",\"user_type\":\"" + user_type.SelectedItem.Text + "\"";
            }

            //加入key
            sign += "&key=" + member_secret;
            new Log().WriteLog(sign);
            ///sign用MD5加密，注意编码
            sign = System.Web.Security.FormsAuthentication.HashPasswordForStoringInConfigFile(sign, "MD5").ToLower();

            ///sign加入json
            json += ",\"sign\":\"" + sign.ToLower() + "\"";
            json += "}";

            new Log().WriteLog(sign.ToLower());
            new Log().WriteLog(json);

            string private_key = "-----BEGIN RSA PRIVATE KEY-----MIICXQIBAAKBgQDFvIutSJwCioynORSYtHi25nDcGjE69vosGSR1xuSCNYEvOTe+vViIp/y9MRz/kRJzSJ2qDN6jYfhJ1quMGBF1zMLYX+8P6dCpqbdMb1Q6DPfmrHh3Q2WYHZfVEYw80kZK0Ia83seeP70rqWsVmvFMj/VyjyTG6MXB71CbPaXpIQIDAQABAoGBAJrw3937bEvNduM9pfb2SzS+IydE2tiiWGufk+M54ypodH6lbhnm0m3ae/9fcaKMcZGmD+0Ewpvxk5WAQdG3tk1fxqU0eWJnb7UwhQsFTNTCVrHEC3YR7CDE2Nf06ftK9cGPT2ppczqOLQJmim8b6wmYGJXuJBWYBCi4RvoeWoV1AkEA75iMOAN+s6gKMIjkhOhPaAw3KwLGmIfuPyQ7r8UVO05ZC0mHhvp6jJ1IhdgK1hBa1xPXDJm8LfIys/W4w4NSmwJBANNGUZ++gesMLgf+TAb/NMWYgjPPhWZax/0znID64k/hdshcIeG9rPZmlACwgDONXxRpb7olmzo8pZPxGpKjgPMCQQCUVX9KFm/Uzk4wqi/AUQsIQbfN+xP0zwMjOE1zjrKMWv8py8YKPPyuHZoOsyhSSyHSUkgrVup4D6BwZcqT0LPNAkAqGH6293xnWtvpF9AKLNtU65g1volz5W6nkpsCT/q7s865CTJvCh3mUnX21TQWEXr6VC/AxTFynxbWrVmAEjflAkA+hLINdGh8PVH3otNeC6UD3FytKJkTtoD2eoJtEU53lKuUCsXQVsgfUGoCWhp+mLQXXte7ZwzxhIlqlEKgadWD-----END RSA PRIVATE KEY-----";
            string public_key = "-----BEGIN PUBLIC KEY-----MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDFEuequdBR+ZaEGz5H/bGAbC05+yPec8dRU05ibkTa6iEu93unhe23kN/ZZwDu28DO3Qq2NudrWsB3oftiQqBPThJ670pCLy/0XenHhNBTBRj5AaDE8Ni9v4GF/5u6gAzxxPtYfl2Kfa2Um1kb9Ww0o18ZCFJdriNvX2+Sm0KnsQIDAQAB-----END PUBLIC KEY-----";

            //去头去脚
            private_key = private_key.Replace("-----BEGIN RSA PRIVATE KEY-----", "").Replace("-----END RSA PRIVATE KEY-----", "");
            public_key = public_key.Replace("-----BEGIN PUBLIC KEY-----", "").Replace("-----END PUBLIC KEY-----", "");


            //使用RSA加密
            string temp = RsaEncrypt(json, ConvertToXmlPublicJavaKey(public_key));
            new Log().WriteLog(temp);

            //POST给服务器进行支付
            Dictionary<string, string> dic = new Dictionary<string, string>();
            dic.Add("member_code", member_code);
            dic.Add("cipher_data", temp);
            temp = Post("http://www.magopay.net/api/trans/pay", dic);
            new Log().WriteLog(temp);

            ///正常返回格式{"code":"0000","msg":"成功.","order_sn":"20170914003845353","down_sn":"20170914003845874","code_url":"weixin:\/\/wxpay\/bizpayurl?pr=FeVXnin","sign":"bc50a1d7a7e6c5824af22a7cb4ba221f"}
            ///微信码为code_url
        }

        /// <summary>  
        /// 指定Post地址使用Get 方式获取全部字符串  
        /// </summary>  
        /// <param name="url">请求后台地址</param>  
        /// <returns></returns>  
        private string Post(string url, Dictionary<string, string> dic)
        {
            string result = "";
            HttpWebRequest req = (HttpWebRequest)WebRequest.Create(url);
            req.Method = "POST";
            req.ContentType = "application/x-www-form-urlencoded";
            #region 添加Post 参数
            StringBuilder builder = new StringBuilder();
            int i = 0;
            foreach (var item in dic)
            {
                if (i > 0)
                    builder.Append("&");
                builder.AppendFormat("{0}={1}", item.Key, item.Value);
                i++;
            }
            byte[] data = Encoding.UTF8.GetBytes(builder.ToString());
            req.ContentLength = data.Length;
            using (Stream reqStream = req.GetRequestStream())
            {
                reqStream.Write(data, 0, data.Length);
                reqStream.Close();
            }
            #endregion
            HttpWebResponse resp = (HttpWebResponse)req.GetResponse();
            Stream stream = resp.GetResponseStream();
            //获取响应内容  
            using (StreamReader reader = new StreamReader(stream, Encoding.UTF8))
            {
                result = reader.ReadToEnd();
            }
            return result;
        }

        public string RsaEncrypt(string rawInput, string publicKey)
        {
            if (string.IsNullOrEmpty(rawInput))
            {
                return string.Empty;
            }

            if (string.IsNullOrWhiteSpace(publicKey))
            {
                throw new ArgumentException("Invalid Public Key");
            }

            using (var rsaProvider = new RSACryptoServiceProvider())
            {
                var inputBytes = Encoding.UTF8.GetBytes(rawInput);//有含义的字符串转化为字节流
                rsaProvider.FromXmlString(publicKey);//载入公钥
                int bufferSize = (rsaProvider.KeySize / 8) - 11;//单块最大长度
                var buffer = new byte[bufferSize];
                using (MemoryStream inputStream = new MemoryStream(inputBytes),
                     outputStream = new MemoryStream())
                {
                    while (true)
                    { //分段加密
                        int readSize = inputStream.Read(buffer, 0, bufferSize);
                        if (readSize <= 0)
                        {
                            break;
                        }

                        var temp = new byte[readSize];
                        Array.Copy(buffer, 0, temp, 0, readSize);
                        var encryptedBytes = rsaProvider.Encrypt(temp, false);
                        outputStream.Write(encryptedBytes, 0, encryptedBytes.Length);
                    }
                    return Convert.ToBase64String(outputStream.ToArray());//转化为字节流方便传输
                }
            }
        }

        /// <summary>
        /// 把java的公钥转换成.net的xml格式
        /// </summary>
        /// <param name="privateKey">java提供的第三方公钥</param>
        /// <returns></returns>
        public static string ConvertToXmlPublicJavaKey( string publicJavaKey)
        {
            RsaKeyParameters publicKeyParam = (RsaKeyParameters)PublicKeyFactory.CreateKey(Convert.FromBase64String(publicJavaKey));
            string xmlpublicKey = string.Format("<RSAKeyValue><Modulus>{0}</Modulus><Exponent>{1}</Exponent></RSAKeyValue>",
              Convert.ToBase64String(publicKeyParam.Modulus.ToByteArrayUnsigned()),
              Convert.ToBase64String(publicKeyParam.Exponent.ToByteArrayUnsigned()));
            return xmlpublicKey;
        }


    }
}
