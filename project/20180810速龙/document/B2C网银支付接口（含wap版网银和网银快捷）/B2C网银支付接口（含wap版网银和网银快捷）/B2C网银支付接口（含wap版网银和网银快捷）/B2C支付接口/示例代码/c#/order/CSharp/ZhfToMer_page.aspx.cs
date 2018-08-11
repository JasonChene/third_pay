using System;
using System.Web;
using System.Text;
using System.Xml;
using DinpayRSAAPI.COM.Dinpay.RsaUtils;

/**
*功能：速龙支付异步后台通知页面
*场景：当订单支付完毕后速龙支付服务器主动将该笔订单的支付成功数据发送至此页面
*版本：3.0
*日期：2017-03-20
*说明：
*以下代码仅为了方便商户安装接口而提供的样例具体说明以文档为准，商户可以根据自己网站的需要，按照技术文档编写。
**/
namespace CSharp
{
    public partial class WebForm1 : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {
            //获取速龙支付反馈信息
            string merchant_code = Request.Form["merchant_code"].ToString().Trim();
            string notify_type = Request.Form["notify_type"].ToString().Trim();
            string notify_id = Request.Form["notify_id"].ToString().Trim();
            string interface_version = Request.Form["interface_version"].ToString().Trim();
            string sign_type = Request.Form["sign_type"].ToString().Trim();
            string zhfsign = Request.Form["sign"].ToString().Trim();
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
            *参数名1=参数值1&参数名2=参数值2&……&参数名n=参数值n&key=key值
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
					/**
					1)zhf_public_key，速龙支付公钥，每个商家对应一个固定的速龙支付公钥（不是使用工具生成的密钥merchant_public_key，不要混淆），
					即为速龙支付商家后台"公钥管理"->"速龙支付公钥"里的绿色字符串内容
					2)demo提供的zhf_public_key是测试商户号800003004321的速龙支付公钥，请自行复制对应商户号的速龙支付公钥进行调整和替换。
					*/

                string zhf_public_key = "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDLYAY1pTXcMwKKagEgKxv0Wtq0EwcKxQdQ6X+duKWWY1ti+YMSKx5RwUNPSqm9u59GkbFCHScOw+pZvHnDl47uaOV0NcxJU9CuGQ4UkuN5zJS/g/Fi4IFtQpLJPM5pJGNvnBkJzfqAD8DGOPoAc0TypMq2/HEVRMWU6PYBIiFa+QIDAQAB";
                //将速龙支付公钥转换成C#专用格式
                zhf_public_key = testOrder.HttpHelp.RSAPublicKeyJava2DotNet(zhf_public_key);
                //验签
                bool result = testOrder.HttpHelp.ValidateRsaSign(signStr, zhf_public_key, zhfsign);
                if (result == true)
                {
                    //注：建议页面同步通知一般只做订单支付成功提示，而不做订单支付状态更新，更新订单支付状态在notify_url指定的地址处理，请做好订单是否重复修改状态的判断，以免虚拟充值重复到账！！
                    Response.Write("验签成功");
                }
                else
                {
                    //验签失败
                    Response.Write("验签失败");
                }

            }
            else //RSA验签方法
            {
                string merPubKeyDir = "D:/800003004321.pfx";
                string password = "87654321";
                RSAWithHardware rsaWithH = new RSAWithHardware();
                rsaWithH.Init(merPubKeyDir, password, "D:/dinpayRSAKeyVersion");
                bool result = rsaWithH.VerifySign("800003004321", signStr, zhfsign);
                if (result == true)
                {
                    //注：建议页面同步通知一般只做订单支付成功提示，而不做订单支付状态更新，更新订单支付状态在notify_url指定的地址处理，请做好订单是否重复修改状态的判断，以免虚拟充值重复到账！！
                    Response.Write("验签成功");
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