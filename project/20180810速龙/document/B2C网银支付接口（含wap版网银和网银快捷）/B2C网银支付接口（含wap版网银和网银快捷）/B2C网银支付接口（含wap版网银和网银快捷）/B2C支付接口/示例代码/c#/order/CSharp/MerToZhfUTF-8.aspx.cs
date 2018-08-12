using System;
using System.Web;
using System.IO;
using System.Xml;
using System.Xml.XPath;
using System.Xml.Linq;

using DinpayRSAAPI.COM.Dinpay.RsaUtils;


namespace CSharpTestPay
{
    public partial class _Default : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {
            try
            {
                /////////////////////////////////接收表单提交参数//////////////////////////////////////
                ////////////////////////To receive the parameter form HTML form//////////////////////

                string input_charset1 = Request.Form["input_charset"].ToString().Trim();
                string interface_version1 = Request.Form["interface_version"].ToString().Trim();
                string merchant_code1 = Request.Form["merchant_code"].ToString().Trim();
                string notify_url1 = Request.Form["notify_url"].ToString().Trim();
                string order_amount1 = Request.Form["order_amount"].ToString().Trim();
                string order_no1 = Request.Form["order_no"].ToString().Trim();
                string order_time1 = Request.Form["order_time"].ToString().Trim();
                string sign_type1 = Request.Form["sign_type"].ToString().Trim();
                string product_code1 = Request.Form["product_code"].ToString().Trim();
                string product_desc1 = Request.Form["product_desc"].ToString().Trim();
                string product_name1 = Request.Form["product_name"].ToString().Trim();
                string product_num1 = Request.Form["product_num"].ToString().Trim();
                string return_url1 = Request.Form["return_url"].ToString().Trim();
                string service_type1 = Request.Form["service_type"].ToString().Trim();
                string show_url1 = Request.Form["show_url"].ToString().Trim();
                string extend_param1 = Request.Form["extend_param"].ToString().Trim();
                string extra_return_param1 = Request.Form["extra_return_param"].ToString().Trim();
                string bank_code1 = Request.Form["bank_code"].ToString().Trim();
                string client_ip1 = Request.Form["client_ip"].ToString().Trim();
                string client_ip_check1 = Request.Form["client_ip_check"].ToString().Trim();
                string redo_flag1 = Request.Form["redo_flag"].ToString().Trim();
                string pay_type1 = Request.Form["pay_type"].ToString().Trim();

                ////////////////组装签名参数//////////////////

                string signSrc= "";

	            //组织订单信息
	            if(bank_code1!="") {
		            signSrc=signSrc+"bank_code="+bank_code1+"&";
	            }
	            if(client_ip1!="") {
		            signSrc=signSrc+"client_ip="+client_ip1+"&";
	            }
                if (client_ip_check1 != "")
                {
                    signSrc = signSrc + "client_ip_check=" + client_ip_check1 + "&";
                }
	            if(extend_param1!="") {
		            signSrc=signSrc+"extend_param="+extend_param1+"&";
	            }
	            if(extra_return_param1!="") {
		            signSrc=signSrc+"extra_return_param="+extra_return_param1+"&";
	            }
	            if (input_charset1!="") {
		            signSrc=signSrc+"input_charset="+input_charset1+"&";
	            }
	            if (interface_version1!="") {
		            signSrc=signSrc+"interface_version="+interface_version1+"&";
	            }
	            if (merchant_code1!="") {
		            signSrc=signSrc+"merchant_code="+merchant_code1+"&";
	            }
	            if(notify_url1!="") {
		            signSrc=signSrc+"notify_url="+notify_url1+"&";
	            }
	            if(order_amount1!="") {
		            signSrc=signSrc+"order_amount="+order_amount1+"&";
	            }
	            if(order_no1!="") {
		            signSrc=signSrc+"order_no="+order_no1+"&";
	            }
	            if(order_time1!="") {
		            signSrc=signSrc+"order_time="+order_time1+"&";
	            }
                if (pay_type1 != "")
                {
                    signSrc = signSrc + "pay_type=" + pay_type1 + "&";
                }
	            if(product_code1!="") {
		            signSrc=signSrc+"product_code="+product_code1+"&";
	            }
	            if(product_desc1!="") {
		            signSrc=signSrc+"product_desc="+product_desc1+"&";
	            }
	            if(product_name1!="") {
		            signSrc=signSrc+"product_name="+product_name1+"&";
	            }
	            if(product_num1!="") {
		            signSrc=signSrc+"product_num="+product_num1+"&";
	            }
                if (redo_flag1 != "")
                {
                    signSrc = signSrc + "redo_flag=" + redo_flag1 + "&";
                }
	            if(return_url1!="") {
		            signSrc=signSrc+"return_url="+return_url1+"&";
	            }
	            if(service_type1!="") {
		            signSrc=signSrc+"service_type="+service_type1;
	            }
	            if(show_url1!="") {
                    signSrc = signSrc + "&show_url=" + show_url1;
	            }

                if (sign_type1 == "RSA-S")//RSA-S签名方法
                {
                    /**  merchant_private_key，商户私钥，商户按照《密钥对获取工具说明》操作并获取商户私钥。获取商户私钥的同时，也要
						获取商户公钥（merchant_public_key）并且将商户公钥上传到速龙支付商家后台"公钥管理"（如何获取和上传请看《密钥对获取工具说明》），
						不上传商户公钥会导致调试的时候报错“签名错误”。
				   */
  	
					//demo提供的merchant_private_key是测试商户号800003004321的商户私钥，请自行获取商户私钥并且替换。
                    
                   string merchant_private_key = "MIICeAIBADANBgkqhkiG9w0BAQEFAASCAmIwggJeAgEAAoGBANilQBp9xg6KdgB+6jVnIFnMl34jR3sGguvDjAgTLTqYd/FhkSbNl24rbRKDjZ4jqDKxFFUbFlqMd0YuSzhE+Utb1jNcBROTyIn/2O0cCmN0tPFaSgL/ywYhXSjT1FlAWuFbBV+bggj8CLDUpTGm31BofJA/qmg9Kn/wW2aF8QjNAgMBAAECgYEAyALgiNSfeqM4SELjxcPc6SrqngjCIIGlczbI3FegBR3odlBmatWaPZsYCuSrZVl0GsDDjcMBQz21jHSG+38qS0WTxWrMgw/k88ygbfDXWEZQd1v8Em7CDIFN5rZ7InS2GZsDDl5HhBHFKp6eoGug+Xo7Z5O8GokYaGKCdOuVcUECQQD9NRF05NTp0BzGxfVkcWmJeYI23vH+No8nPed4OZSA2gNtpz7mZ2NE7lw05skznf4bVxWTanlynorYD32fejfdAkEA2wjzKggsxfiy4FkPpq9Q04FooQt+W8efD4EOWuYMOVNoOAJYmjzE8YY2XUkaEZ80NHnCJcEZ/UtSX4OqL/f9sQJBANcazTCr8cCL/tZSd8yTmF+krR1mOtiGiwiAS3LUH7dy/jSaPxJHRIrbn9OFN+o0zxl02qx4aKIZ08QHLOZdUrUCQQDagA4a8va/Mv42QYIUdLV7mI+of8+4fOWW0NZiJTUyhprjrKt4iYCps4pN+tuvkpLAemoLwZtMi7QLpkvC+G+xAkAtOkom2PugToM5QiM4MS7puinFV89SEsVQuGvHyyJ/iH8O6igd5XrH1AR7kL879onPTvARTz+Ai7bHq3mUD3Wr";
                     //私钥转换成C#专用私钥
                    merchant_private_key = testOrder.HttpHelp.RSAPrivateKeyJava2DotNet(merchant_private_key);
                    //签名
                    string signData = testOrder.HttpHelp.RSASign(signSrc, merchant_private_key);
                    sign.Value = signData;
                }
                else  //RSA签名方法
                {
                    RSAWithHardware rsa = new RSAWithHardware();
                    string merPubKeyDir = "D:/800003004321.pfx";   //证书路径
                    string password = "87654321";                //证书密码
                    RSAWithHardware rsaWithH = new RSAWithHardware();
                    rsaWithH.Init(merPubKeyDir, password, "D:/dinpayRSAKeyVersion");//初始化(version路径需跟证书一致，证书会自动生成version)
                    string signData = rsaWithH.Sign(signSrc);    //签名
                    sign.Value = signData;
                }

               
                merchant_code.Value = merchant_code1;
                bank_code.Value = bank_code1;
                order_no.Value = order_no1;
                order_amount.Value = order_amount1;
                service_type.Value = service_type1;
                input_charset.Value = input_charset1;
                notify_url.Value = notify_url1;
                interface_version.Value = interface_version1;
                sign_type.Value = sign_type1;
                order_time.Value = order_time1;
                product_name.Value = product_name1;
                client_ip.Value = client_ip1;
                client_ip_check.Value = client_ip_check1;
                extend_param.Value = extend_param1;
                extra_return_param.Value = extra_return_param1;
                product_code.Value = product_code1;
                product_desc.Value = product_desc1;
                product_num.Value = product_num1;
                return_url.Value = return_url1;
                show_url.Value = show_url1;
                redo_flag.Value = redo_flag1;
                pay_type.Value = pay_type1;
            }
            finally
            {

            }
        }
    }
}