<%
/* *
 功能：科诺支付服务器异步通知页面
 说明：
 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 该代码仅供学习和研究肯做支付接口使用，只是提供一个参考。

 //***********页面功能说明***********
 创建该页面文件时，请留心该页面文件中无任何HTML代码及空格。
 该页面不能在本机电脑测试，请到服务器上做测试。请确保外部可以访问该页面。
 如果没有收到该页面返回的 success 信息，科诺支付会在24小时内按一定的时间策略重发通知
 //********************************
 * */
%>
<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%@ page import="java.util.*"%>
<%@ page import="com.mustpay.util.*"%>
<%@ page import="com.mustpay.config.*"%>
<%
	//获取科诺支付 POST过来反馈信息
	Map<String, Object> params = new HashMap<String, Object>();
	Map<String, String[]> requestParams = request.getParameterMap();
	for (Iterator<String> iter = requestParams.keySet().iterator(); iter.hasNext();) {
		String name = (String) iter.next();
		String[] values = (String[]) requestParams.get(name);
		String valueStr = "";
		for (int i = 0; i < values.length; i++) {
			valueStr = (i == values.length - 1) ? valueStr + values[i] : valueStr + values[i] + ",";
		}
		params.put(name, valueStr);
	}
	String out_trade_no = new String(request.getParameter("out_trade_no").getBytes("ISO-8859-1"),"UTF-8");
	// MustPay交易号 ,也就是预支付ID
	String trade_no = new String(request.getParameter("trade_no").getBytes("ISO-8859-1"),"UTF-8");
	// 支付方式名称
	String pay_name = new String(request.getParameter("pay_name").getBytes("ISO-8859-1"),"UTF-8");

	//获取MustPay的通知返回参数，可参考技术文档中页面跳转同步通知参数列表(以上仅供参考)//

	if(MustpayNotify.verify(params)){//验证成功
		//////////////////////////////////////////////////////////////////////////////////////////
		//请在这里加上商户的业务逻辑程序代码

		//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
		
		//判断该笔订单是否在商户网站中已经做过处理
		//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
		//请务必判断请求时的total_fee、apps_id、mer_id与通知时获取的total_fee、apps_id、mer_id为一致的
		//如果有做过处理，不执行商户的业务程序
				
		//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
			
		out.print("success");	//请不要修改或删除  处理成功后返回success告知MustPay商户已处理成功，MustPay将不会再进行重复通知

		//////////////////////////////////////////////////////////////////////////////////////////
	}else{//验证失败
		out.print("fail");
	}
%>