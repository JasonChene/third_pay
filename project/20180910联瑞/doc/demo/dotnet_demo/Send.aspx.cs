using System;
using System.Configuration;
using System.IO;
using System.Net;
using System.Text;
using System.Web.Security;

namespace dotnet_demo
{
    public partial class Send : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {
            gotopay(DateTime.Now.ToString("yyyyMMddHHmmssfff"), 
                "http://" + Request.Url.Host + ":" + Request.Url.Port + "/Receive.aspx");
        }

        /// <summary>
        /// 联瑞支付
        /// </summary>
        /// <param name="orderid">订单号</param>
        /// <param name="callBackurl">返回地址</param>
        private void gotopay(String orderid, String callBackurl)
        {
            //商户信息
            String userid = ConfigurationManager.AppSettings["userid"]; //商户ID
            String key = ConfigurationManager.AppSettings["apikey"]; //商户密钥
            String gateway = ConfigurationManager.AppSettings["gateway"];

            //组织接口发送。
            if (String.IsNullOrEmpty(Request.Form["card"]))
            {
                //银行提交获取信息
                String bank_Type = Request.Form["rtype"];//银行类型
                String bank_gameAccount = Request.Form["txtUserName"];//充值账号
                String bank_payMoney = Request.Form["PayMoney"];//充值金额

                //银行卡支付
                String param = String.Format("parter={0}&type={1}&value={2}&orderid={3}&callbackurl={4}", userid, bank_Type, bank_payMoney, orderid, callBackurl);
                String PostUrl = String.Format("{0}/chargebank.aspx?{1}&sign={2}", gateway
                    , param
                    , FormsAuthentication.HashPasswordForStoringInConfigFile(param + key, "MD5").ToLower());

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
                String param = String.Format("type={0}&parter={1}&cardno={2}&cardpwd={3}&value={4}&restrict={5}&orderid={6}&callbackurl={7}", card_type, userid, card_No, card_pwd, card_payMoney, restrict, orderid, callBackurl);
                String PostUrl = String.Format("{0}/cardReceive.ashx?{1}&attach={2}&sign={3}", gateway
                    , param, attach, FormsAuthentication.HashPasswordForStoringInConfigFile(param + key, "MD5").ToLower());

                Response.Redirect(PostUrl);//转发URL地址

                //Post(PostUrl);
            }
        }

        private void Post(string postUrl)
        {
            HttpWebRequest httpWebRequest = (HttpWebRequest)WebRequest.Create(postUrl);
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
                    Response.Write("已经记录该卡，正在等待被使用.");
                }
                if (useResult.Trim() == "opstate=-1")
                {
                    Response.Write("请求参数无效。");
                }
                if (useResult.Trim() == "opstate=-2")
                {
                    Response.Write("签名错误。");
                }
                if (useResult.Trim() == "opstate=-3")
                {
                    Response.Write("提交的卡密为重复提交，系统不进行消耗并进入下行流程。");
                }
                if (useResult.Trim() == "opstate=-4")
                {
                    Response.Write("提交的卡密不符合联瑞定义的卡号密码面值规则。");
                    //提交的卡密不符合联瑞定义的卡号密码面值规则。
                }
                if (useResult.Trim() == "opstate=-999")
                {
                    Response.Write("接口维护中。");
                    ////这里把定单状态接口维护中。
                }
            }
        }
    }
}
