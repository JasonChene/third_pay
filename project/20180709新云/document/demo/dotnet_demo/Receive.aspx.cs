using System;
using System.Configuration;
using System.Web.Security;

public partial class Receive : System.Web.UI.Page
{
    protected void Page_Load(object sender, EventArgs e)
    {
        String key = ConfigurationManager.AppSettings["apikey"];//配置文件密钥
        //返回参数
        String orderid = Request["orderid"];//返回订单号
        String opstate = Request["opstate"];//返回处理结果
        String ovalue = Request["ovalue"];//返回实际充值金额
        String sign = Request["sign"];//返回签名
        String ekaorderID = Request["sysorderid"];//新云支付录入时产生流水号。
        String ekatime = Request["systime"];//新云支付处理时间。
        String attach = Request["attach"];//上行附加信息
        String msg = Request["msg"];//新云支付返回订单处理消息

        String param = String.Format("orderid={0}&opstate={1}&ovalue={2}{3}", orderid, opstate, ovalue, key);//组织参数
        //比对签名是否有效
        if (sign.Equals(FormsAuthentication.HashPasswordForStoringInConfigFile(param, "MD5").ToLower()))
        {
            //执行操作方法
            if (opstate.Equals("0") || opstate.Equals("-3"))
            {
                //操作流程成功的情况
            }
            else if (opstate.Equals("-1"))
            {
                //卡号密码错误
            }
            else if (opstate.Equals("-2"))
            {
                //卡实际面值和提交时面值不符，卡内实际面值未使用
            }
            else if (opstate.Equals("-4"))
            {
                //卡在提交之前已经被使用
            }
            else if (opstate.Equals("-5"))
            {
                //失败，原因请查看msg
            }
        }
        else
        { 
            //签名无效
        }

        Response.Write("opstate=0");
    }
}
