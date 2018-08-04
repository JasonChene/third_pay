using System;
using System.Configuration;
using System.Web.Security;

public partial class Receive : System.Web.UI.Page
{
    /// <summary>
    /// 获取提交过来的参数 POST
    /// </summary>
    /// <param name="name">参数名</param>
    /// <returns></returns>
    private string GetRequestForm(string name)
    {
        string val = System.Web.HttpContext.Current.Request.QueryString.Get(name);
        if (string.IsNullOrEmpty(val))
        {
            val = System.Web.HttpContext.Current.Request.Form.Get(name);
        }
        return val;
    }

    protected void Page_Load(object sender, EventArgs e)
    {
        string key = "你的API接口密钥";//API接口密钥

        //通知返回结果 参数 。  
        string out_trade_no = GetRequestForm("out_trade_no");//商户订单号 out_trade_no Y 上行过程中商户系统传入的out_trade_no
        string trade_status = GetRequestForm("trade_status");//订单结果 trade_status Y   支付成功:SUCCESS，失败返回：Fail
        string total_amount = GetRequestForm("total_amount");//订单金额    total_amount Y   支付金额 单位分（人民币）
        string trade_no = GetRequestForm("trade_no");//收款助手订单号 trade_no  N 此次交易中收款助手系统内的订单ID
        string trade_time = GetRequestForm("trade_time");//订单时间 trade_time N 此次订单过程中591收款助手系统内的订单结束时间 年-月-日 时：分：秒 如：2016-10-30 10:30:30
        string extra_return_param = GetRequestForm("extra_return_param");//备注信息 extra_return_param  N 备注信息，上行中extra_return_param原样返回
        string sign = GetRequestForm("sign");// MD5签名   sign N   32位小写MD5签名值，GB2312编码

		
		
		
		
		
        String param = String.Format("out_trade_no={0}&total_amount={1}&trade_status={2}{3}", out_trade_no, total_amount, trade_status,  key);//签名参数
        if (sign.Equals(FormsAuthentication.HashPasswordForStoringInConfigFile(param, "MD5").ToLower()))
        {
            if (trade_status.Equals("SUCCESS"))
            {
                // 这里放成功的业务逻辑
                Response.Write("SUCCESS");
            }
            else
            {
                Response.Write("支付失败！");
            }
        }
        else
        {
            Response.Write("签名失败");
        }
    }
}
