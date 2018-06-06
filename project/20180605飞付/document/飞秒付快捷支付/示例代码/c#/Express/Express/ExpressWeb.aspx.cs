using System;
using System.Web;
using System.IO;
using System.Xml;
using System.Xml.XPath;
using System.Xml.Linq;
using System.Text.RegularExpressions;
using DinpayRSAAPI.COM.Dinpay.RsaUtils;

namespace CSS
{
    public partial class _Default : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {
            try
            {
                /////////////////////////////////接收表单提交参数//////////////////////////////////////
                ////////////////////////To receive the parameter form HTML form//////////////////////

                string interface_version1 = Request.Form["interface_version"].ToString().Trim();
                string input_charset1 = Request.Form["input_charset"].ToString().Trim();
                string service_type1 = Request.Form["service_type"].ToString().Trim();
                string sign_type1 = Request.Form["sign_type"].ToString().Trim();
                string merchant_code1 = Request.Form["merchant_code"].ToString().Trim();
                string order_no1 = Request.Form["order_no"].ToString().Trim();
                string order_amount1 = Request.Form["order_amount"].ToString().Trim();
                string order_time1 = Request.Form["order_time"].ToString().Trim();
                string notify_url1 = Request.Form["notify_url"].ToString().Trim();
                string card_type1 = Request.Form["card_type"].ToString().Trim();
                string mobile1 = Request.Form["mobile"].ToString().Trim();
                string bank_code1 = Request.Form["bank_code"].ToString().Trim();
                string product_name1 = Request.Form["product_name"].ToString().Trim();
                string product_code1 = Request.Form["product_code"].ToString().Trim();
                string product_num1 = Request.Form["product_num"].ToString().Trim();
                string product_desc1 = Request.Form["product_desc"].ToString().Trim();

                string card_no = Request.Form["card_no"].ToString().Trim();
                string card_name = Request.Form["card_name"].ToString().Trim();
                string id_no = Request.Form["id_no"].ToString().Trim();
                string card_cvv2 = Request.Form["card_cvv2"].ToString().Trim();
                string card_exp_date = Request.Form["card_exp_date"].ToString().Trim();
                string encrypt_info1 = card_no + "|" + card_name + "|" + id_no; //组装敏感数据
                ////使用公钥对卡号和卡密加密【公钥需从商家后台-公钥管理中取出】//////////
                string encryption_key = "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCbT0QjejG8pj8y/HfmzL7j9gHFKTwSg68ZiOad4ErxkSi/N77HnG+scCXVnkvvBwPTFI35AStGQnv3E/7XeCO8swvVR8J4w/k5JfQwcHnSk64DD0L+0KH6cRN27WZYdL9n4r+jW6OXILnbw0t1YM0/y6cOCc4R8yyd1hTCdgP3LwIDAQAB";


                //////////将公钥转换成C#专用格式///////////
                encryption_key = testOrder.HttpHelp.RSAPublicKeyJava2DotNet(encryption_key);
                //加密后的卡号密码
                string encrypt_info2 = testOrder.HttpHelp.RSAEncrypt(encrypt_info1, encryption_key);
                ////////////////组装签名/////////////////

                string signStr = "bank_code=" + bank_code1 + "&card_type=" + card_type1 + "&encrypt_info=" + encrypt_info2 + "&input_charset=" + input_charset1 + "&interface_version=" + interface_version1 + "&merchant_code=" + merchant_code1 + "&mobile=" + mobile1 + "&notify_url=" + notify_url1 + "&order_amount=" + order_amount1 + "&order_no=" + order_no1 + "&order_time=" + order_time1 + "&product_name=" + product_name1 + "&service_type=" + service_type1;


                if (sign_type1 == "RSA-S")//RSA-S签名方法
                {
                    //商家私钥
                    string merchant_private_key = "MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBAOD/K22eGec3qNQmk9LwsjpbJDJE9JYfsTJJQGJhfWsKbcZ9UISKXZxuhSCVaf2z9/pEln5RoE7GNwOrYv8R00P8nRJONHNPaLcf0Y8+c6DBWGVewZKojUzn18uAEGGW5XMjLs5/OU//opRB4ieeSmBJ4jp954XfR4Z57bjOpe/3AgMBAAECgYEArCr2K2JQxfp0aSq/8SkX6Mm3T/QuCPZlXGprJx0coJ0RVVKtG07ZxQtZOY671VQyjEKRukVx2vWYQWmTTkVwl+U71fh1mmiu00Y3odNoERc02ZN0zJmrSuhbcuEv6F8kBATunB55wOZ3jlbkXD9h+KUyePBOkrPb+81LhJ6kZXkCQQD18nQ1U2m9laS8ROJmZ1LuecQ4maaHW3xFxHoM9sS1YcpB3peQuXBrKa483zYADIJV2NYstc0QXMMZIXleKFFzAkEA6jF+xx4q+p/lhH8M3rHucHmkgFce90Jh1eHTdx5czizl3LiOYZ5D7cNL8x7piJDMmzkVz8+OidXm0wf5aT82bQJAP9TSJjjk26hn3dj+7Vbppi0CKTJvjvfGdBD/IDg3a1/a72eG7K/EJnvl1bSUvkSA2yjwxR/V/eYlWHNgnXhXUwJBANA6h+3FfhNvXmSrjqbncAljrwdJ70eMJ29DpoFQZtYPB6Z0FmzniqB6OCqIPr7leHc/j4xBkQwvO1hBy9pvkRUCQEVOGouGVeiXL/MuupUdbdBSV4nkYb9hrqE11gzbLu4A+OCpV8Xwdqu5SqX9Js1mQ6vQwTHu63vyfpxxl7oN9Jw=";


                    //私钥转换成C#专用私钥
                    merchant_private_key = testOrder.HttpHelp.RSAPrivateKeyJava2DotNet(merchant_private_key);
                    //签名
                    string signData = testOrder.HttpHelp.RSASign(signStr, merchant_private_key);
                    sign.Value = signData;
                }
                else  //RSA签名方法
                {
                    RSAWithHardware rsa = new RSAWithHardware();
                    string merPubKeyDir = "D:/800004007888.pfx";   //证书路径
                    string password = "800004007888";                //证书密码
                    RSAWithHardware rsaWithH = new RSAWithHardware();
                    rsaWithH.Init(merPubKeyDir, password, "D:/dinpayRSAKeyVersion");//初始化
                    string signData = rsaWithH.Sign(signStr);    //签名
                    sign.Value = signData;
                }
                
                interface_version.Value = interface_version1;
                input_charset.Value = input_charset1;
                service_type.Value = service_type1;
                sign_type.Value = sign_type1;
                merchant_code.Value = merchant_code1;
                order_no.Value = order_no1;
                order_amount.Value = order_amount1;
                order_time.Value = order_time1;
                notify_url.Value = notify_url1;
                card_type.Value = card_type1;
                mobile.Value = mobile1;
                bank_code.Value = bank_code1;
                product_name.Value = product_name1;
                encrypt_info.Value = encrypt_info2;

            }
            finally
            {
            }
        }
    }
}