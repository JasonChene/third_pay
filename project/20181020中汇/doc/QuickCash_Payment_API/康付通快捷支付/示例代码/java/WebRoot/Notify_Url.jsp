<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
<%@ page import="java.io.*" %>
<%@ page import="com.itrus.util.sign.*" %>

<%
//////////////////////////////////// 接收参数 request parameters //////////////////////////////////////

		// dinpay_public_key，康付通公钥 
		String dinpay_public_key = "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDTE8YBexrEmn8oEqsASVgkZEUo/WTqKZlmr0MYDyIVgcNfvXJPUR9kD46RAT11UYKK681UI0IWcfi/uB+bL00bVzuW7x5YdT5zdDuca/i3H3MIbWMcAHXAqPQt38Z0yWoXoCJp0IZ975vBVSe/a70M7uh1aLSapQFKyUCO2i3hGwIDAQAB";
	

		// 接收康付通返回的参数(To receive the parameter)
		request.setCharacterEncoding("UTF-8");
		String interface_version = (String) request.getParameter("interface_version");
		String merchant_code = (String) request.getParameter("merchant_code");
		String notify_type = (String) request.getParameter("notify_type");
		String notify_id = (String) request.getParameter("notify_id");
		String sign_type = (String) request.getParameter("sign_type");
		String dinpaySign= (String) request.getParameter("sign");
		String order_no = (String) request.getParameter("order_no");
		String order_time = (String) request.getParameter("order_time");
		String order_amount = (String) request.getParameter("order_amount");
		String extra_return_param = (String) request.getParameter("extra_return_param");
		String trade_no = (String) request.getParameter("trade_no");
		String trade_time= (String) request.getParameter("trade_time");
		String trade_status = (String) request.getParameter("trade_status");
		String bank_seq_no= (String) request.getParameter("bank_seq_no");
		
		System.out.println(	"interface_version = " + interface_version + "\n" + 
							"merchant_code = " + merchant_code + "\n" +
							"notify_type = " + notify_type + "\n" +
							"notify_id = " + notify_id + "\n" +
							"sign_type = " + sign_type + "\n" +
							"dinpaySign = " + dinpaySign + "\n" +
							"order_no = " + order_no + "\n" +
							"order_time = " + order_time + "\n" +
							"order_amount = " + order_amount + "\n" +
							"extra_return_param = " + extra_return_param + "\n" +
							"trade_no = " + trade_no + "\n" +
							"trade_time = " + trade_time + "\n" +
							"trade_status = " + trade_status + "\n" +
							"bank_seq_no = " + bank_seq_no + "\n" 	); 		

		/** 数据签名
		签名规则定义如下：
		（1）参数列表中，除去sign_type、sign两个参数外，其它所有非空的参数都要参与签名，值为空的参数不用参与签名；
		（2）签名顺序按照参数名a到z的顺序排序，若遇到相同首字母，则看第二个字母，以此类推，组成规则如下：
		参数名1=参数值1&参数名2=参数值2&……&参数名n=参数值n
		*/

		/** Data signature
		The definition of signature rule is as follows : 
		（1） In the list of parameters, except the two parameters of sign_type and sign, all the other parameters that are not in blank shall be signed, the parameter with value as blank doesn’t need to be signed; 
		（2） The sequence of signature shall be in the sequence of parameter name from a to z, in case of same first letter, then in accordance with the second letter, so on so forth, the composition rule is as follows : 
		Parameter name 1 = parameter value 1& parameter name 2 = parameter value 2& ......& parameter name N = parameter value N 
		*/
	 
	 	StringBuilder signStr = new StringBuilder();
	 	if(null != bank_seq_no && !bank_seq_no.equals("")) {
	 		signStr.append("bank_seq_no=").append(bank_seq_no).append("&");
	 	}
	 	if(null != extra_return_param && !extra_return_param.equals("")) {
	 		signStr.append("extra_return_param=").append(extra_return_param).append("&");
	 	}
	 	signStr.append("interface_version=").append(interface_version).append("&");
	 	signStr.append("merchant_code=").append(merchant_code).append("&"); 	
	 	signStr.append("notify_id=").append(notify_id).append("&");	 	
	 	signStr.append("notify_type=").append(notify_type).append("&"); 	
	 	signStr.append("order_amount=").append(order_amount).append("&");
	 	signStr.append("order_no=").append(order_no).append("&");
	 	signStr.append("order_time=").append(order_time).append("&");
	 	signStr.append("trade_no=").append(trade_no).append("&");	
	 	signStr.append("trade_status=").append(trade_status).append("&");
		signStr.append("trade_time=").append(trade_time);

	 		
		String signInfo =signStr.toString();
		System.out.println("康付通返回的签名字符串：" + signInfo.length() + " -->" + signInfo);		
		System.out.println("康付通返回的签名：" + dinpaySign.length() + " -->" + dinpaySign);								
		boolean result = false;
		if("RSA-S".equals(sign_type)) {			
					
			// 验签  signInfo签名字符串，RSA_S_PublicKey康付通公钥，dinpaySign康付通返回的签名数据
			result=RSAWithSoftware.validateSignByPublicKey(signInfo, dinpay_public_key, dinpaySign);
		}else{
			
			String merPfxPath = "D:/108008008666.pfx";  // 商家的pfx证书文件位置(公私钥合一)
			String pfxPass = "87654321";			  // 商家的pfx证书密码
			RSAWithHardware mh = new RSAWithHardware();						
			mh.initSigner(merPfxPath, pfxPass);		  
			// 验签   merchantId为商户号，signInfo签名字符串，dinpaySign康付通返回的签名数据
			result = mh.validateSignByPubKey(merchant_code, signInfo, dinpaySign);
		}
	
		PrintWriter pw = response.getWriter();
		if(result){
			pw.print("SUCCESS");      		// 验签成功，响应SUCCESS (response to SUCCESS!)
			System.out.println("验签结果result的值：" + result + " -->SUCCESS");
		}else{
			pw.print("Signature Error");    // 验签失败，业务结束  (End of the business)
			System.out.println("验签结果result的值：" + result + " -->Signature Error");
		}
        System.out.println("---------------------------------------------------------------------------------------------------------------------------------------------"); 		
%>
