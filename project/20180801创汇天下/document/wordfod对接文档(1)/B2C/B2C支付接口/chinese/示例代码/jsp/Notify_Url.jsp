<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
<%@ page import="java.io.*" %>
<%@ page import="com.itrus.util.sign.*" %>

<%
////////////////////////////////////  异步通知验签  ////////////////////////////////////

		// 接收返回的参数
		request.setCharacterEncoding("UTF-8");
		String interface_version = (String) request.getParameter("interface_version");
		String merchant_code = (String) request.getParameter("merchant_code");
		String notify_type = (String) request.getParameter("notify_type");
		String notify_id = (String) request.getParameter("notify_id");
		String sign_type = (String) request.getParameter("sign_type");
		String zhihpaySign = (String) request.getParameter("sign");
		String order_no = (String) request.getParameter("order_no");
		String order_time = (String) request.getParameter("order_time");
		String order_amount = (String) request.getParameter("order_amount");
		String extra_return_param = (String) request.getParameter("extra_return_param");
		String trade_no = (String) request.getParameter("trade_no");
		String trade_time = (String) request.getParameter("trade_time");
		String trade_status = (String) request.getParameter("trade_status");
		String bank_seq_no = (String) request.getParameter("bank_seq_no");
		String orginal_money= request.getParameter("orginal_money");
		
		System.out.println(	"interface_version = " + interface_version + "\n" + 
							"merchant_code = " + merchant_code + "\n" +
							"notify_type = " + notify_type + "\n" +
							"notify_id = " + notify_id + "\n" +
							"sign_type = " + sign_type + "\n" +
							"zhihpaySign = " + zhihpaySign + "\n" +
							"order_no = " + order_no + "\n" +
							"order_time = " + order_time + "\n" +
							"order_amount = " + order_amount + "\n" +
							"extra_return_param = " + extra_return_param + "\n" +
							"trade_no = " + trade_no + "\n" +
							"trade_time = " + trade_time + "\n" +
							"trade_status = " + trade_status + "\n" +
							"bank_seq_no = " + bank_seq_no + "\n"   +
                            "orginal_money = " + orginal_money + "\n" ); 	 		

		/** 数据签名
		签名规则定义如下：
		（1）参数列表中，除去sign_type、sign两个参数外，其它所有非空的参数都要参与签名，值为空的参数不用参与签名；
		（2）签名参数排序按照参数名a到z的顺序排序，若遇到相同首字母，则看第二个字母，以此类推，组成规则如下：
		参数名1=参数值1&参数名2=参数值2&……&参数名n=参数值n		*/
	 
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
		signStr.append("orginal_money=").append(orginal_money).append("&");
	 	signStr.append("trade_no=").append(trade_no).append("&");	
	 	signStr.append("trade_status=").append(trade_status).append("&");
		signStr.append("trade_time=").append(trade_time);

	 		
		String signInfo = signStr.toString();
		System.out.println("返回的签名参数排序：" + signInfo.length() + " -->" + signInfo);		
		System.out.println("返回的签名：" + zhihpaySign.length() + " -->" + zhihpaySign);								
		boolean result = false;
		
		if("RSA-S".equals(sign_type)){ // sign_type = "RSA-S"			
			
			/**
			1)zhihpay_public_key，公钥，每个商家对应一个固定的公钥（不是使用工具生成的商户公钥merchant_public_key，不要混淆），
			     即为商家后台"支付管理"->"公钥管理"->"公钥"里的绿色字符串内容
			2)demo提供的zhihpay_public_key是测试商户号1111110166的公钥，请自行复制对应商户号的公钥进行调整和替换	*/		
						
			String zhihpay_public_key = "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCE7K9XR0JVo2r7kcwHToD/mCOteNqmZsyjgtqClw2qONUjWPVze2svDIdkUCOUyfWl0vPRoujDpHmfWsdKDCIh0fvJ6em/+uQSvw2pmEI5v5OhSA9IW+768lJsrQ3HxjBNHt14F33ZFnaB69a+VicjoxHFCi+f/mASjFKDlrPb6QIDAQAB";
			result = RSAWithSoftware.validateSignByPublicKey(signInfo, zhihpay_public_key, zhihpaySign);	// 验签   signInfo返回的签名参数排序， zhihpay_public_key公钥， zhihpaySign返回的签名
		}
				
		if("RSA".equals(sign_type)){ // 数字证书加密方式  sign_type = "RSA"
			
			// 请在商家后台"支付管理"->"证书下载"处申请和下载pfx数字证书，一般要1~3个工作日才能获取到，1111110166.pfx是测试商户号1111110166的数字证书
			String webRootPath = request.getSession().getServletContext().getRealPath("/");
			String merPfxPath = webRootPath + "pfx/1111110166.pfx"; 									// 商家的pfx证书文件路径
			String merPfxPass = "1111110166";			  												// 商家的pfx证书密码,初始密码是商户号
			RSAWithHardware mh = new RSAWithHardware();						
			mh.initSigner(merPfxPath, merPfxPass);		  							
			result = mh.validateSignByPubKey(merchant_code, signInfo, zhihpaySign);						// 验签    merchant_code为商户号， signInfo返回的签名参数排序， zhihpaySign返回的签名
		}
	
		PrintWriter pw = response.getWriter();
		if(result){
			pw.write("SUCCESS");      																	// 验签成功，响应SUCCESS 
			System.out.println("验签结果result的值：" + result + " -->SUCCESS");
		}else{
			pw.write("Signature Error");    															// 验签失败，业务结束  
			System.out.println("验签结果result的值：" + result + " -->Signature Error");
		}
		pw.flush();
		pw.close();
		
        System.out.println("---------------------------------------------------------------------------------------------------------------------------------------------"); 		

%>
