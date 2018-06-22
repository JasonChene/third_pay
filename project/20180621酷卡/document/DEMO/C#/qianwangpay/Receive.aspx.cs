using System;
using System.Configuration;
using System.Web.Security;

public partial class Receive : System.Web.UI.Page
{
    protected void Page_Load(object sender, EventArgs e)
    {
        String key = ConfigurationManager.AppSettings["ekakey"];//配置文件密钥
        //返回参数
        String orderid = Request["orderid"];//返回订单号
        String opstate = Request["opstate"];//返回处理结果
        String ovalue = Request["ovalue"];//返回实际充值金额
        String sign = Request["sign"];//返回签名
        String ekaorderID = Request["ekaorderid"];//录入时产生流水号。
        String ekatime = Request["ekatime"];//处理时间。
        String attach = Request["attach"];//上行附加信息
        String msg = Request["msg"];//返回订单处理消息

        String param = String.Format("orderid={0}&opstate={1}&ovalue={2}{3}", orderid, opstate, ovalue, key);//组织参数
        //比对签名是否有效
        if (sign.Equals(FormsAuthentication.HashPasswordForStoringInConfigFile(param, "MD5").ToLower()))
        {
            //执行操作方法//异步返回opstate=0，表示充值已经成功了。
            if (opstate.Equals("0"))
            {
                //操作流程成功的情况，//这里填写您业务
				
				
				
			//以下是点卡才会返回的参数，网银可以忽视它。	
            } else if (opstate.Equals("-1"))
                {
                    Response.Write("提交参数错误.");
                }
                else if (opstate.Equals("-2"))
                {
                    Response.Write("签名错误。");
                }
                else if (opstate.Equals("-3"))
                {
                    Response.Write("卡密为重复提交");
                }else if (opstate.Equals("-4"))
                {
                     Response.Write("卡密不符合定义的卡号密码面值规则");
                } else if (opstate.Equals("-999"))
                {
                     Response.Write("接口在维护.");
                }else if (opstate.Equals("2"))
                {
                     Response.Write("不支持该类卡或者该面值的卡");
                }else if (opstate.Equals("3"))
                {
                     Response.Write("验证签名失败");
                }else if (opstate.Equals("4"))
                {
                     Response.Write("订单内容重复");
                }else if (opstate.Equals("5"))
                {
                     Response.Write("该卡密已经有被使用的记录");
                }else if (opstate.Equals("6"))
                {
                     Response.Write("订单号已经存在");
                }else if (opstate.Equals("7"))
                {
                     Response.Write("数据非法");
                }else if (opstate.Equals("8"))
                {
                     Response.Write("非法用户");
                }else if (opstate.Equals("9"))
                {
                     Response.Write("暂时停止该类卡或者该面值的卡交易");
                }else if (opstate.Equals("10"))
                {
                     Response.Write("充值卡无效");
                }else if (opstate.Equals("11"))
                {
                     Response.Write("支付成功,实际面值与订单金额不符");
                }else if (opstate.Equals("12"))
                {
                     Response.Write("处理失败，卡密未使用");
                }else if (opstate.Equals("13"))
                {
                     Response.Write("系统繁忙");
                }else if (opstate.Equals("14"))
                {
                     Response.Write("不存在该笔订单");
                }else if (opstate.Equals("15"))
                {
                     Response.Write("未知请求");
                }else if (opstate.Equals("16"))
                {
                     Response.Write("密码错误");
                }else if (opstate.Equals("17"))
                {
                     Response.Write("匹配订单失败");
                }else if (opstate.Equals("18"))
                {
                     Response.Write("余额不足");
                }else if (opstate.Equals("19"))
                {
                     Response.Write("运营商维护");
                }else if (opstate.Equals("20"))
                {
                     Response.Write("提交次数过多");
                }else if (opstate.Equals("99"))
                {
                     Response.Write("充值失败，请重试");
                }else if (opstate.Equals("333"))
                {
                     Response.Write("提交失败，原因未知");
                }
            
        }
        else
        { 
            //签名无效
        }
    }
}
