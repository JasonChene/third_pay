using System;
using System.Configuration;
using System.Web.Security;
using System.Net;
using System.IO;
using System.Text;

public partial class Send : System.Web.UI.Page
{
    protected void Page_Load(object sender, EventArgs e)
    {
        Eka365pay("12321321", "http://" + Request.Url.Host + ":" + Request.Url.Port + "/Eka365pay/" + "Receive.aspx");
        //流程工作完成后调用Eka365pay方法，参数为：（String 订单号，String 返回地址--这里默认为：http://"+Request.Url.Host+":"+Request.Url.Port+"/Eka365pay/"+"Receive.aspx）
        //实例：Eka365pay("012345674980123456", "http://" + Request.Url.Host + ":" + Request.Url.Port + "/Eka365pay/" + "Receive.aspx");
    }

    /// <summary>
    /// 在线支付
    /// </summary>
    /// <param name="orderid">订单号</param>
    /// <param name="callBackurl">返回地址</param>
    private void Eka365pay(String orderid, String callBackurl)
    {
        //商户信息
        String shop_id = ConfigurationManager.AppSettings["ekaid"]; //商户ID
        String key = ConfigurationManager.AppSettings["ekakey"]; //商户密钥


        //组织接口发送。
        if (String.IsNullOrEmpty(Request.Form["card"]))
        {
            //银行提交获取信息
            String bank_Type = Request.Form["rtype"];//银行类型
            String bank_gameAccount = Request.Form["txtUserName"];//充值账号
            String bank_payMoney = Request.Form["PayMoney"];//充值金额
            //银行卡支付
            String param = String.Format("parter={0}&type={1}&value={2}&orderid={3}&callbackurl={4}", shop_id, bank_Type, bank_payMoney, orderid, callBackurl);
            String PostUrl = String.Format("http://pay.gwwub.com/paybank.aspx?{0}&sign={1}", param, FormsAuthentication.HashPasswordForStoringInConfigFile(param + key, "MD5").ToLower());
            Response.Redirect(PostUrl);//转发URL地址
        }
        else
        {
            //获取卡类提交信息
            String card_No = Request.Form["cardNo"];//卡号
            String card_pwd = Request.Form["cardPwd"];//卡密
            String card_account = Request.Form["txtUserNameCard"];//充值账号
            String card_type = Request.Form["sel_card"].Split('，')[0];//卡类型
            String card_payMoney = Request.Form["sel_price"];//充值金额
            String restrict = "0";//使用范围
            String attach = "test";//附加内容，下行原样返回
            if (Request.Form["sel_card"].Split(',').Length > 1)
            {
                restrict = Request.Form["sel_card"].Split(',')[1];
            }
            //卡类支付
            String param = String.Format("type={0}&parter={1}&cardno={2}&cardpwd={3}&value={4}&restrict={5}&orderid={6}&callbackurl={7}", card_type, shop_id, card_No, card_pwd, card_payMoney, restrict, orderid, callBackurl);
            String PostUrl = String.Format("http://vip.gwwub.com/payHQ.aspx?{0}&attach={1}&sign={2}", param, attach, FormsAuthentication.HashPasswordForStoringInConfigFile(param + key, "MD5").ToLower());

            HttpWebRequest httpWebRequest = (HttpWebRequest)WebRequest.Create(PostUrl);
            //获取响应结果 此过程大概需要5秒
            HttpWebResponse httpWebResponse = (HttpWebResponse)httpWebRequest.GetResponse();
            //获取响应流
            Stream stream = httpWebResponse.GetResponseStream();
            //用指定的字符编码为指定的流初始化 StreamReader 类的一个新实例。
            using (StreamReader streamReader = new StreamReader(stream, Encoding.UTF8))
            {
                string useResult = streamReader.ReadToEnd();
							
                streamReader.Dispose();
                streamReader.Close();
                httpWebResponse.Close();

                if (useResult.Trim() == "opstate=0")
                {
				//同步返回opstate=0不表示充值成功，他只是表示点卡符合要求并提交成功了，一定异步返回opstate=0才表示充值成功
                    Response.Write("提交成功，请稍后查看下充值情况.");
                }
                if (useResult.Trim() == "opstate=-1")
                {
                    Response.Write("提交参数错误.");
                }
                if (useResult.Trim() == "opstate=-2")
                {
                    Response.Write("签名错误。");
                }
                if (useResult.Trim() == "opstate=-3")
                {
                    Response.Write("卡密为重复提交");
                }if (useResult.Trim() == "opstate=-4")
                {
                     Response.Write("卡密不符合定义的卡号密码面值规则");
                } if (useResult.Trim() == "opstate=-999")
                {
                     Response.Write("接口在维护.");
                }if (useResult.Trim() == "opstate=2")
                {
                     Response.Write("不支持该类卡或者该面值的卡");
                }if (useResult.Trim() == "opstate=3")
                {
                     Response.Write("验证签名失败");
                }if (useResult.Trim() == "opstate=4")
                {
                     Response.Write("订单内容重复");
                }if (useResult.Trim() == "opstate=5")
                {
                     Response.Write("该卡密已经有被使用的记录");
                }if (useResult.Trim() == "opstate=6")
                {
                     Response.Write("订单号已经存在");
                }if (useResult.Trim() == "opstate=7")
                {
                     Response.Write("数据非法");
                }if (useResult.Trim() == "opstate=8")
                {
                     Response.Write("非法用户");
                }if (useResult.Trim() == "opstate=9")
                {
                     Response.Write("暂时停止该类卡或者该面值的卡交易");
                }if (useResult.Trim() == "opstate=10")
                {
                     Response.Write("充值卡无效");
                }if (useResult.Trim() == "opstate=11")
                {
                     Response.Write("支付成功,实际面值与订单金额不符");
                }if (useResult.Trim() == "opstate=12")
                {
                     Response.Write("处理失败，卡密未使用");
                }if (useResult.Trim() == "opstate=13")
                {
                     Response.Write("系统繁忙");
                }if (useResult.Trim() == "opstate=14")
                {
                     Response.Write("不存在该笔订单");
                }if (useResult.Trim() == "opstate=15")
                {
                     Response.Write("未知请求");
                }if (useResult.Trim() == "opstate=16")
                {
                     Response.Write("密码错误");
                }if (useResult.Trim() == "opstate=17")
                {
                     Response.Write("匹配订单失败");
                }if (useResult.Trim() == "opstate=18")
                {
                     Response.Write("余额不足");
                }if (useResult.Trim() == "opstate=19")
                {
                     Response.Write("运营商维护");
                }if (useResult.Trim() == "opstate=20")
                {
                     Response.Write("提交次数过多");
                }if (useResult.Trim() == "opstate=99")
                {
                     Response.Write("充值失败，请重试");
                }if (useResult.Trim() == "opstate=333")
                {
                     Response.Write("提交失败，原因未知");
                }
            }
        }
    }
}
