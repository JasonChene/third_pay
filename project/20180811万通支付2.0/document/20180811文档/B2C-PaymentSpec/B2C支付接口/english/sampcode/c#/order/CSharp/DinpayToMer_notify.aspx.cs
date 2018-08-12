using System;
using System.Web;
using System.Text;
using System.Xml;
using DinpayRSAAPI.COM.Dinpay.RsaUtils;


namespace CSharp
{
    public partial class DinpayToMer_notify : System.Web.UI.Page
    {
        protected void Pag_Load(object sender, EventArgs e)
        {
            
            string merchant_code = Request.Form["merchant_code"].ToString().Trim();
            string notify_type = Request.Form["notify_type"].ToString().Trim();
            string notify_id = Request.Form["notify_id"].ToString().Trim();
            string interface_version = Request.Form["interface_version"].ToString().Trim();
            string sign_type = Request.Form["sign_type"].ToString().Trim();
            string dinpaysign = Request.Form["sign"].ToString().Trim();
            string order_no = Request.Form["order_no"].ToString().Trim();
            string order_time = Request.Form["order_time"].ToString().Trim();
            string order_amount = Request.Form["order_amount"].ToString().Trim();
            string extra_return_param = Request.Form["extra_return_param"];
            string trade_no = Request.Form["trade_no"].ToString().Trim();
            string trade_time = Request.Form["trade_time"].ToString().Trim();
            string trade_status = Request.Form["trade_status"].ToString().Trim();
            string bank_seq_no = Request.Form["bank_seq_no"];

           
            string signStr = "";

            if (null != bank_seq_no && bank_seq_no != "")
            {
                signStr = signStr + "bank_seq_no=" + bank_seq_no.ToString().Trim() + "&";
            }

            if (null != extra_return_param && extra_return_param != "")
            {
                signStr = signStr + "extra_return_param=" + extra_return_param + "&";
            }
            signStr = signStr + "interface_version=V3.0" + "&";
            signStr = signStr + "merchant_code=" + merchant_code + "&";


            if (null != notify_id && notify_id != "")
            {
                signStr = signStr + "notify_id=" + notify_id + "&notify_type=" + notify_type + "&";
            }

            signStr = signStr + "order_amount=" + order_amount + "&";
            signStr = signStr + "order_no=" + order_no + "&";
            signStr = signStr + "order_time=" + order_time + "&";
            signStr = signStr + "trade_no=" + trade_no + "&";
            signStr = signStr + "trade_status=" + trade_status + "&";

            if (null != trade_time && trade_time != "")
            {
                signStr = signStr + "trade_time=" + trade_time;
            }

            if (sign_type == "RSA-S") //for sign_type = "RSA-S"
            {
               //dinpay_public_key,copy it form Dinpay merchant system,find it on "Payment Management"->"Public Key Management"->"Dinpay Public Key"
			   
			   // this dinpay_public_key is for merchant ID 1111110166
                string dinpay_public_key = "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCWOq5aHSTvdxGPDKZWSl6wrPpn" +
                    "MHW+8lOgVU71jB2vFGuA6dwa/RpJKnz9zmoGryZlgUmfHANnN0uztkgwb+5mpgme" +
                    "gBbNLuGqqHBpQHo2EsiAhgvgO3VRmWC8DARpzNxknsJTBhkUvZdy4GyrjnUrvsAR" +
                    "g4VrFzKDWL0Yu3gunQIDAQAB";
                
                dinpay_public_key = testOrder.HttpHelp.RSAPublicKeyJava2DotNet(dinpay_public_key);
               
                bool result = testOrder.HttpHelp.ValidateRsaSign(signStr, dinpay_public_key, dinpaysign);
                if (result == true)
                {
                    
                    Response.Write("SUCCESS");
                }
                else
                {
                    
                    Response.Write("verify failed");
                }

            }
            else //for sign_type = "RSA"
            {
                string merPubKeyDir = "D:/1111110166.pfx";//get the pfx cetification on Dinpay mechant system,"Payment Management"->"Download Cetification"
                string password = "87654321";
                RSAWithHardware rsaWithH = new RSAWithHardware();
                rsaWithH.Init(merPubKeyDir, password, "D:/dinpayRSAKeyVersion");
                bool result = rsaWithH.VerifySign("1111110166", signStr, dinpaysign);
                if (result == true)
                {
                    
                    Response.Write("SUCCESS");
                }
                else
                {
                    
                    Response.Write("verify failed");
                }

            }

        }
    }
}