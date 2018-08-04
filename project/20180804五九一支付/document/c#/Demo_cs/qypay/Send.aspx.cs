using System;
using System.Configuration;
using System.Web.Security;

public partial class Send : System.Web.UI.Page
{
    protected void Page_Load(object sender, EventArgs e)
    {
        string app_id = ConfigurationManager.AppSettings["myid"];//商户id
        string key = ConfigurationManager.AppSettings["mykey"];//商户id
		string $interface_version="V2.0"; 						//接口版本，默认值"V2.0"
        string trade_type = Request["ptrade_type"]; //通道类型
        int total_amount = Convert.ToInt32(Request["payMoney"])*100;//支付金额 单位为分 需要乘以100
        string out_trade_no = Guid.NewGuid().ToString().Substring(0, 20).Replace("-", "");//订单id
        string notify_url = ConfigurationManager.AppSettings["notify_url"];//异步回调地址
        string return_url = ConfigurationManager.AppSettings["return_url"];//同步回调地址
        string extra_return_param = Server.UrlEncode("xxxxx");//备注信息 有中文需要编码
        string client_ip = "127.0.0.1";// 下单Ip 程序获取
        string posturl = ConfigurationManager.AppSettings["posturl"];//post 请求地址
        //签名 app_id={0}&trade_type={1}&total_amount={2}&out_trade_no={3}&return_url={4}key
        string sign = string.Format("app_id={0}&notify_url={1}&out_trade_no={2}&total_amount={3}&trade_type={4}", app_id, notify_url, out_trade_no, total_amount, trade_type);
        sign = FormsAuthentication.HashPasswordForStoringInConfigFile(sign + key, "MD5").ToLower();//签名进行MD5加密 加密后需要转小写
        string PostUrl = string.Format(posturl + "?app_id={0}&trade_type={1}&total_amount={2}&out_trade_no={3}&return_url={4}&notify_url={5}&extra_return_param={6}&client_ip={7}&sign={8}&interface_version={9}", 
            app_id, trade_type, total_amount, out_trade_no, return_url, notify_url,extra_return_param, client_ip, sign,interface_version);
        Response.Redirect(PostUrl);//执行跳转，一定要这样编码，否则可能出现乱码！！！！
    }
}
