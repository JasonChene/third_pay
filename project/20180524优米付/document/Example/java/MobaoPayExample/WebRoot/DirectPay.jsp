<%@ page language="java" import="java.util.*" pageEncoding="UTF-8"%>
<%@ page import="com.mobo360.merchant.api.*"%>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>支付系统商户接口范例-直联委托结算</title>
		<!--
	<link rel="stylesheet" type="text/css" href="styles.css">
	<link href="css/mobaopay.css" type="text/css" rel="stylesheet" />
	-->
	</head>
	<body>
		<table width="50%" border="0" align="center" cellpadding="0"
			cellspacing="0" style="border: solid 1px #107929">
			<tr>
				<td>
					<table width="100%" border="0" align="center" cellpadding="5"
						cellspacing="1">
						
						<tr>
							<td height="30" colspan="2" bgcolor="#6BBE18">
								<span style="color: #FFFFFF"><a href="index.jsp">感谢您使用支付系统平台</a>
								</span>
							</td>
						</tr>
						<tr>
							<td colspan="2" bgcolor="#CEE7BD">
								支付系统订单直联委托结算结果展示：
							</td>
						</tr>
						<tr>
							<td>
								<table width="100%">
									<%
										try {
											// 签名初始化
											Mobo360SignUtil.init();
											/*
											
											*/

											// 组织输入数据
											Map<String, String> requestMap = new HashMap<String, String>();
											request.setCharacterEncoding("utf-8");
											requestMap.put("apiName", Mobo360Config.MOBAOPAY_APINAME_DS);
											requestMap.put("apiVersion", Mobo360Config.MOBAOPAY_API_VERSION);
											requestMap.put("platformID", Mobo360Config.PLATFORM_ID);
											requestMap.put("merchNo", Mobo360Config.MERCHANT_ACC);
											requestMap.put("orderNo", request.getParameter("orderNo"));
											requestMap.put("tradeDate", request.getParameter("tradeDate"));
											requestMap.put("Amt", request.getParameter("Amt"));
											requestMap.put("merchUrl", Mobo360Config.MERCHANT_NOTIFY_URL);
											requestMap.put("merchParam", request.getParameter("merchParam"));
											requestMap.put("bankAccName", request.getParameter("bankAccName"));
											requestMap.put("bankName", request.getParameter("bankName"));
											requestMap.put("bankCode", request.getParameter("bankCode"));
											requestMap.put("bankAccNo", request.getParameter("bankAccNo"));
											requestMap.put("tradeSummary", request.getParameter("tradeSummary"));
								
											
											String paramsStr = Mobo360Merchant.generateDirectPay(requestMap);
											String signStr = Mobo360SignUtil.signData(paramsStr);
											paramsStr = paramsStr + "&signMsg=" + signStr;
											System.out.println("(直联委托结算  签名后数据)"+paramsStr);
											// 发起请求并获取返回数据
											String responseMsg = Mobo360Merchant.transact(paramsStr,
													Mobo360Config.MOBAOPAY_GETWAY);

											// 处理返回数据
											CustRealPayResponseEntity entity = new CustRealPayResponseEntity();
											entity.parseDS(responseMsg);
											StringBuffer sbHtml = new StringBuffer();
											sbHtml
													.append("<tr><td align=\"left\" width=\"30%\">&nbsp;&nbsp;响应码</td><td align=\"left\">&nbsp;&nbsp;"
															+ entity.getRespCode() + "</td></tr>");
											sbHtml
													.append("<tr><td align=\"left\" width=\"30%\">&nbsp;&nbsp;响应描述</td><td align=\"left\">&nbsp;&nbsp;"
															+ entity.getRespDesc() + "</td></tr>");
											if ("00".equals(entity.getRespCode())) {
												sbHtml.append("<tr><td align=\"left\" width=\"30%\">&nbsp;&nbsp;支付订单号</td><td align=\"left\">&nbsp;&nbsp;"
															+ entity.getBatchNo() + "</td></tr>");
												sbHtml.append("<tr><td align=\"left\" width=\"30%\">&nbsp;&nbsp;支付日期</td><td align=\"left\">&nbsp;&nbsp;"
													+ entity.getAccDate() + "</td></tr>");
											}
											out.println(sbHtml.toString());
										} catch (Exception e) {
											out.println(e.getMessage());
										}
									%>
								</table>
							</td>
						</tr>
						<tr>
							<td height="5" bgcolor="#6BBE18" colspan="2">
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</body>
</html>
