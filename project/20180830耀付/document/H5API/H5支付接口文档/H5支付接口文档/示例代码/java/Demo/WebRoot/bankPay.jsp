<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
<%@ page import="java.io.*" %>
<%@ page import="com.itrus.util.sign.*" %>

<%
//////////////////////////////////// 请求参数 //////////////////////////////////////
		
	// 接收表单提交参数
		request.setCharacterEncoding("UTF-8");			
		String merchant_code = request.getParameter("merchant_code");	
		String service_type = request.getParameter("service_type");
		String interface_version = request.getParameter("interface_version");		
		String input_charset = request.getParameter("input_charset");				
		String notify_url = request.getParameter("notify_url");
		String sign_type = request.getParameter("sign_type");		
		String order_no = request.getParameter("order_no");		
		String order_time = request.getParameter("order_time");		
		String order_amount = request.getParameter("order_amount");		
		String product_name = request.getParameter("product_name");		
		String return_url = request.getParameter("return_url");
		String bank_code = request.getParameter("bank_code");		
		String redo_flag = request.getParameter("redo_flag");
		String product_code = request.getParameter("product_code");
		String product_num = request.getParameter("product_num");
		String product_desc = request.getParameter("product_desc");
		String pay_type = request.getParameter("pay_type");
		String client_ip = request.getParameter("client_ip");
		String extend_param = request.getParameter("extend_param");
		String extra_return_param = request.getParameter("extra_return_param");
		String show_url = request.getParameter("show_url");		

		/** 数据签名
		签名规则定义如下：
		（1）参数列表中，除去sign_type、sign两个参数外，其它所有非空的参数都要参与签名，值为空的参数不用参与签名；
		（2）签名顺序按照参数名a到z的顺序排序，若遇到相同首字母，则看第二个字母，以此类推，组成规则如下：
		参数名1=参数值1&参数名2=参数值2&……&参数名n=参数值n
		*/

		StringBuffer signSrc= new StringBuffer();	
		if (!"".equals(bank_code)) {
			signSrc.append("bank_code=").append(bank_code).append("&");	
		}
		if (!"".equals(client_ip)) {
			signSrc.append("client_ip=").append(client_ip).append("&");	
		}
		if (!"".equals(extend_param)) {
			signSrc.append("extend_param=").append(extend_param).append("&");	
		}
		if (!"".equals(extra_return_param)) {
			signSrc.append("extra_return_param=").append(extra_return_param).append("&");	
		}
		
		signSrc.append("input_charset=").append(input_charset).append("&");			
		signSrc.append("interface_version=").append(interface_version);
		signSrc.append("&merchant_code=").append(merchant_code);
		signSrc.append("&notify_url=").append(notify_url);					
		signSrc.append("&order_amount=").append(order_amount);
		signSrc.append("&order_no=").append(order_no);		
		signSrc.append("&order_time=").append(order_time);
		
		if (!"".equals(pay_type)) {
			signSrc.append("&pay_type=").append(pay_type);	
		}	
		if (!"".equals(product_code)) {
			signSrc.append("&product_code=").append(product_code);	
		}
		if (!"".equals(product_desc)) {
			signSrc.append("&product_desc=").append(product_desc);	
		}		
		signSrc.append("&product_name=").append(product_name);
		if (!"".equals(product_num)) {
			signSrc.append("&product_num=").append(product_num);	
		}	
		if (!"".equals(redo_flag)) {
			signSrc.append("&redo_flag=").append(redo_flag);	
		}
		if (!"".equals(return_url)) {
			signSrc.append("&return_url=").append(return_url);	
		}	
		
		signSrc.append("&service_type=").append(service_type);
			
		if (!"".equals(show_url)) {
			signSrc.append("&show_url=").append(show_url);	
		}
				
		String signInfo = signSrc.toString();
		String sign="";
		if("RSA-S".equals(sign_type)) {//sign_type = "RSA-S"
		/** 
			1)merchant_private_key，商户私钥，商户按照《密钥对获取工具说明》操作并获取商户私钥。获取商户私钥的同时，也要
   			获取商户公钥（merchant_public_key）并且将商户公钥上传到商家后台"公钥管理"（如何获取和上传请看《密钥对获取工具说明》），
  			不上传商户公钥会导致调试的时候报错“签名错误”。
  			2)demo提供的merchant_private_key是测试商户号666007008010的商户私钥，请自行获取商户私钥并且替换
  		*/
			String merchant_private_key = "MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBAM1kayU03wSU+TuYDv0A3G99MMjUnzhqBE0HUi8xSKviBuB2Gv/ZfrHeK8mdvhn7WwTQzNHBS/2hzJjQkkVkzL+EDeI+1OGIfcWTtlm1wCGoyhjE35Eff01HGgwTWmO144W4mqjmCzOaobl0qmHHUYQTKXFPV6mRWJ7CaFf3v7y/AgMBAAECgYBkXOVeUO+JNaJz1GG+j2UntWzZNcx3rJZdbW5jURnJo7Dojc2zp3uZPo72/fWejIx1VfI/rMyNKzrmkURoVFEXguWqr6xRlZFqn2RaweHbsKfbp8BL1iUw8Z//nCUn3M6lmCkldKXJ2iwYppsLRV3pdt1OHV6tVNLvhPnmlj8nQQJBAP/dsd7/ab3GpbedESHVmP3awf48T+le/BlGGHNCEvDf2o2zx49EvSoc/Lo54nd9GvXR+dHsseSxHbowcoIZ7G0CQQDNf/TbNI3312swVO7+vjwId+fzPqEm5b6L5NM7hjeOigM9M8UGng6U7HHh/wgJupVUzERvT2HvSkqLsT+x2DpbAkBy/yHldugAilqK1sYPbd/QIFTWPicwXSdy+IUesFCw//tLesSzSJK4bcTMsh1t1MWcPB5K0lX10gDpYMLmZF5VAkEAgk0XIgMx3avPAIdqP0a6ZBg7j+XvYu2cI7IFKiIRiiUCpsTzsh14W3+NOmJuY1TWqT0YS4gHLiZqHCdYntjfLwJBANbxHhlQMXgHOxh+zX0BkDIjk3FQW8Z0Rm1kiK9SbZVqEILlotdJltqWnZx1cEKj+2Zdyx4IfQu30CgOuIBcla0=";
			sign = RSAWithSoftware.signByPrivateKey(signInfo,merchant_private_key);
			
			//System.out.println("RSA-S商家发送的签名字符串：" + signInfo.length() + " -->" + signInfo);
			//System.out.println("RSA-S商家发送的签名：" + sign.length() + " -->" + sign + "\n");
		}
		
		if("RSA".equals(sign_type)){//数字证书加密方式 sign_type = "RSA"
			String rootPath=this.getClass().getResource("/").toString();
			//请在商家后台证书下载处申请和下载pfx数字证书，一般要1~3个工作日才能获取到,666007008010.pfx是测试商户号666007008010的数字证书
			String path= rootPath.substring(rootPath.indexOf("/")+1,rootPath.length()-8)+"certification/666007008010.pfx";
			String pfxPass = "666007008010"; //证书密钥，初始密码是商户号
			RSAWithHardware mh = new RSAWithHardware();
			mh.initSigner(path, pfxPass);
			sign = mh.signByPriKey(signInfo);
			//System.out.println("RSA商家发送的签名字符串：" + signInfo.length() + " -->" + signInfo);
			//System.out.println("RSA商家发送的签名：" + sign.length() + " -->" + sign + "\n");
			}
%>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" >
</head>
<body onLoad="document.dinpayForm.submit();">
  <form name="dinpayForm" method="post" action="https://pay.shinespay.com/gateway?input_charset=<%=input_charset%>" >
	<input type="hidden" name="sign" value="<%=sign%>" />
	<input type="hidden" name="merchant_code" value="<%=merchant_code%>" />
	<input type="hidden" name="service_type" value="<%=service_type%>" />	
	<input type="hidden" name="interface_version" value="<%=interface_version%>" />			
	<input type="hidden" name="input_charset" value="<%=input_charset%>" />	
	<input type="hidden" name="notify_url" value="<%=notify_url%>"/>
	<input type="hidden" name="sign_type" value="<%=sign_type%>" />		
	<input type="hidden" name="order_no" value="<%=order_no%>"/>
	<input type="hidden" name="order_time" value="<%=order_time%>" />	
	<input type="hidden" name="order_amount" value="<%=order_amount%>"/>
	<input type="hidden" name="product_name" value="<%=product_name%>" />	
	<input type="hidden" name="return_url" value="<%=return_url%>"/>	
	<input type="hidden" name="bank_code" value="<%=bank_code%>" />	
	<input type="hidden" name="redo_flag" value="<%=redo_flag%>"/>
	<input type="hidden" name="product_code" value="<%=product_code%>"/>
	<input type="hidden" name="product_num" value="<%=product_num%>"/>
	<input type="hidden" name="product_desc" value="<%=product_desc%>"/>
	<input type="hidden" name="pay_type" value="<%=pay_type%>"/>
	<input type="hidden" name="client_ip" value="<%=client_ip%>"/>
	<input type="hidden" name="extend_param" value="<%=extend_param%>"/>
	<input type="hidden" name="extra_return_param" value="<%=extra_return_param%>"/>
	<input type="hidden" name="show_url" value="<%=show_url%>"/>
	<input type="submit" value="立即支付" />
  </form>
</body>
</html>
