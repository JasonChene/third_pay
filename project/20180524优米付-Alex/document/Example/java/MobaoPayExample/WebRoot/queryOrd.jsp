<%@page language="java" import="java.util.*" pageEncoding="UTF-8"%>
<%@page import="com.mobo360.merchant.api.*"%>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>支付系统商户接口范例-查询</title>
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
								支付系统订单查询回复结果展示：
							</td>
						</tr>
						<tr>
							<td>
								<table width="100%">
									<%
										try {
											// 签名初始化
											Mobo360SignUtil.init();
											// 准备输入数据
											Map<String, String> requestMap = new HashMap<String, String>();
											request.setCharacterEncoding("UTF-8");
											requestMap.put("apiName", Mobo360Config.MOBAOPAY_APINAME_QUERY);
											requestMap.put("apiVersion", Mobo360Config.MOBAOPAY_API_VERSION);
											requestMap.put("platformID", Mobo360Config.PLATFORM_ID);
											requestMap.put("merchNo", Mobo360Config.MERCHANT_ACC);
											requestMap.put("orderNo", request.getParameter("orderNo"));
											requestMap.put("tradeDate", request.getParameter("tradeDate"));
											requestMap.put("amt", request.getParameter("amt"));
											String paramsStr = Mobo360Merchant.generateQueryRequest(requestMap);
											String signStr = Mobo360SignUtil.signData(paramsStr);
											paramsStr = paramsStr + "&signMsg=" + signStr;
											System.out.println("(订单查询  签名后数据)"+paramsStr);
											// 发送请求并接收返回
											String responseMsg = Mobo360Merchant.transact(paramsStr,
													Mobo360Config.MOBAOPAY_GETWAY);

											// 解析返回数据
											QueryResponseEntity entity = new QueryResponseEntity();
											entity.parse(responseMsg);

											StringBuffer sbHtml = new StringBuffer();
											sbHtml
													.append("<tr><td align=\"left\" width=\"30%\">&nbsp;&nbsp;响应码</td><td align=\"left\">&nbsp;&nbsp;"
															+ entity.getRespCode() + "</td></tr>");
											sbHtml
													.append("<tr><td align=\"left\" width=\"30%\">&nbsp;&nbsp;响应描述</td><td align=\"left\">&nbsp;&nbsp;"
															+ entity.getRespDesc() + "</td></tr>");
											sbHtml
													.append("<tr><td align=\"left\" width=\"30%\">&nbsp;&nbsp;支付平台交易日期</td><td align=\"left\">&nbsp;&nbsp;"
															+ entity.getAccDate() + "</td></tr>");
											sbHtml
													.append("<tr><td align=\"left\" width=\"30%\">&nbsp;&nbsp;支付平台订单号</td><td align=\"left\">&nbsp;&nbsp;"
															+ entity.getAccNo() + "</td></tr>");
											sbHtml
													.append("<tr><td align=\"left\" width=\"30%\">&nbsp;&nbsp;支付订单状态</td><td align=\"left\">&nbsp;&nbsp;"
															+ entity.getStatus() + "</td></tr>");
											sbHtml
													.append("<tr><td align=\"left\" width=\"30%\">&nbsp;&nbsp;商户订单号</td><td align=\"left\">&nbsp;&nbsp;"
															+ entity.getOrderNo() + "</td></tr>");
											//sbHtml.append("<tr><td align=\"left\" width=\"30%\">&nbsp;&nbsp;签名信息</td><td align=\"left\">&nbsp;&nbsp;" + entity.getSignMsg() + "</td></tr>");
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
