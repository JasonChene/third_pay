using System;
using System.Web;
using System.Text;
using System.Xml;
using DinpayRSAAPI.COM.Dinpay.RsaUtils;

/**
*功能：智付异步后台通知页面
*场景：当订单支付完毕后智付服务器主动将该笔订单的支付成功数据发送至此页面
*版本：3.0
*日期：2016-03-20
*说明：
*以下代码仅为了方便商户安装接口而提供的样例具体说明以文档为准，商户可以根据自己网站的需要，按照技术文档编写。
**/
namespace CSharp
{
    public partial class DinpayToMer_notify : System.Web.UI.Page
    {
        protected void Pag_Load(object sender, EventArgs e)
        {
            //获取智付反馈信息
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

            /**
             *签名顺序按照参数名a到z的顺序排序，若遇到相同首字母，则看第二个字母，以此类推，
            *参数名1=参数值1&参数名2=参数值2&……&参数名n=参数值n
            **/
            //组织订单信息
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

            if (sign_type == "RSA-S") //RSA-S的验签方法
            {
                //使用智付公钥对返回的数据验签
                string dinpay_public_key = "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCWOq5aHSTvdxGPDKZWSl6wrPpn" +
                    "MHW+8lOgVU71jB2vFGuA6dwa/RpJKnz9zmoGryZlgUmfHANnN0uztkgwb+5mpgme" +
                    "gBbNLuGqqHBpQHo2EsiAhgvgO3VRmWC8DARpzNxknsJTBhkUvZdy4GyrjnUrvsAR" +
                    "g4VrFzKDWL0Yu3gunQIDAQAB";
                //将智付公钥转换成C#专用格式
                dinpay_public_key = testOrder.HttpHelp.RSAPublicKeyJava2DotNet(dinpay_public_key);
                //验签
                bool result = testOrder.HttpHelp.ValidateRsaSign(signStr, dinpay_public_key, dinpaysign);
                if (result == true)
                {
                    //如果验签结果为true，则对订单进行更新
                    //订单更新完之后打印SUCCESS
                    Response.Write("SUCCESS");
                }
                else
                {
                    //验签失败
                    Response.Write("验签失败");
                }

            }
            else //RSA验签方法
            {
                string merPubKeyDir = "D:/1111110166.pfx";
                string password = "87654321";
                RSAWithHardware rsaWithH = new RSAWithHardware();
                rsaWithH.Init(merPubKeyDir, password, "D:/dinpayRSAKeyVersion");
                bool result = rsaWithH.VerifySign("1111110166", signStr, dinpaysign);
                if (result == true)
                {
                    //如果验签结果为true，则对订单进行更新
                    //订单更新完之后必须打印SUCCESS来响应智付服务器以示商户已经正常收到智付服务器发送的异步数据通知，否则智付服务器将会在之后的时间内若干次发送同一笔订单的异步数据！！
                    Response.Write("SUCCESS");
                }
                else
                {
                    //验签失败
                    Response.Write("验签失败");
                }

            }

        }
    }
}