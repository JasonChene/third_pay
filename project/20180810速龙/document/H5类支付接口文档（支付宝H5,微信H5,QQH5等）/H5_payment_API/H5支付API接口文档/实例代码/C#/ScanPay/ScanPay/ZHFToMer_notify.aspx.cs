﻿using System;
using System.Web;
using System.Text;
using System.Xml;
using DinpayRSAAPI.COM.Dinpay.RsaUtils;


/**
*功能：速龙异步后台通知页面
*场景：当订单支付完毕后速龙服务器主动将该笔订单的支付成功数据发送至此页面
*版本：3.1
*日期：2017-12-29
*说明：
*以下代码仅为了方便商户安装接口而提供的样例具体说明以文档为准，商户可以根据自己网站的需要，按照技术文档编写。
**/
namespace CSharp
{
    public partial class ZHFToMer_notify : System.Web.UI.Page
    {

        protected void Page_Load(object sender, EventArgs e)
        {
            //获取速龙反馈信息
            string merchant_code = Request.Form["merchant_code"];
            string notify_type = Request.Form["notify_type"];
            string notify_id = Request.Form["notify_id"].ToString().Trim();
            string interface_version = Request.Form["interface_version"];
            string sign_type = Request.Form["sign_type"];
            string zhihfsign = Request.Form["sign"];
            string order_no = Request.Form["order_no"];
            string order_time = Request.Form["order_time"];
            string order_amount = Request.Form["order_amount"];
            string extra_return_param = Request.Form["extra_return_param"];
            string trade_no = Request.Form["trade_no"];
            string trade_time = Request.Form["trade_time"];
            string trade_status = Request.Form["trade_status"];
            string bank_seq_no = Request.Form["bank_seq_no"];

            /**
             *签名顺序按照参数名a到z的顺序排序，若遇到相同首字母，则看第二个字母，以此类推，
            *参数名1=参数值1&参数名2=参数值2&……&参数名n=参数值n
            **/
            //组织订单信息
            string signStr = "";

            if (bank_seq_no != "")
                {
                    signStr = signStr + "bank_seq_no=" + bank_seq_no + "&";
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
            if (notify_id != "")
                {
                    signStr = signStr + "notify_id=" + notify_id + "&";
                }
            if (notify_type != "")
                {
                    signStr = signStr + "notify_type=" + notify_type + "&";
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
            if (trade_no != "")
                {
                    signStr = signStr + "trade_no=" + trade_no + "&";
                }
                if (trade_status != "")
                {
                    signStr = signStr + "trade_status=" + trade_status + "&";
                }
                if (trade_time != "")
                {
                    signStr = signStr + "trade_time=" + trade_time;
                }
                if (sign_type == "RSA-S") //RSA-S的验签方法
                {
                    //使用速龙公钥对返回的数据验签
                    string zhihf_public_key = "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDLYAY1pTXcMwKKagEgKxv0Wtq0EwcKxQdQ6X+duKWWY1ti+YMSKx5RwUNPSqm9u59GkbFCHScOw+pZvHnDl47uaOV0NcxJU9CuGQ4UkuN5zJS/g/Fi4IFtQpLJPM5pJGNvnBkJzfqAD8DGOPoAc0TypMq2/HEVRMWU6PYBIiFa+QIDAQAB";
                    //将速龙公钥转换成C#专用格式
                    zhihf_public_key = testOrder.HttpHelp.RSAPublicKeyJava2DotNet(zhihf_public_key);
                    //验签
                    bool result = testOrder.HttpHelp.ValidateRsaSign(signStr, zhihf_public_key, zhihfsign);
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
                    bool result = rsaWithH.VerifySign("1111110166", signStr, zhihfsign);
                    if (result == true)
                    {
                        //如果验签结果为true，则对订单进行更新
                        //订单更新完之后必须打印SUCCESS来响应智付服务器以示商户已经正常收到速龙服务器发送的异步数据通知，否则速龙服务器将会在之后的时间内若干次发送同一笔订单的异步数据！！
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