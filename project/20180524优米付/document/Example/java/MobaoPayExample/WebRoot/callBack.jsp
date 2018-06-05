<%@ page language="java" pageEncoding="UTF-8"%>
<%@page import="com.mobo360.merchant.api.*"%>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>支付系统商户接口范例-支付</title>
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
								支付系统订单支付请求接口演示：
							</td>
						</tr>
						<tr>
							<td>
								<table>
									<%
										try {
											// 签名初始化
											Mobo360SignUtil.init();

											// 获取请求参数，并将数据组织成前面验证源字符串
											request.setCharacterEncoding("utf-8");
											String apiName = request.getParameter("apiName");
											String notifyTime = request.getParameter("notifyTime");
											String tradeAmt = request.getParameter("tradeAmt");
											String merchNo = request.getParameter("merchNo");
											String merchParam = request.getParameter("merchParam");
											String orderNo = request.getParameter("orderNo");
											String tradeDate = request.getParameter("tradeDate");
											String accNo = request.getParameter("accNo");
											String accDate = request.getParameter("accDate");
											String orderStatus = request.getParameter("orderStatus");
											String signMsg = request.getParameter("signMsg");
											signMsg.replaceAll(" ", "\\+");

											String srcMsg = String
													.format(
															"apiName=%s&notifyTime=%s&tradeAmt=%s&merchNo=%s&merchParam=%s&orderNo=%s&tradeDate=%s&accNo=%s&accDate=%s&orderStatus=%s",
															apiName, notifyTime, tradeAmt, merchNo,
															merchParam, orderNo, tradeDate, accNo, accDate,
															orderStatus);

											// 验证签名
											boolean verifyRst = Mobo360SignUtil.verifyData(signMsg, srcMsg);
											String verifyStatus = "未验证";
											if (verifyRst) {
												verifyStatus = "验签通过";
												/**
												 * 验证通过后，请在这里加上商户自己的业务逻辑处理代码.
												 * 比如：
												 * 1、根据商户订单号取出订单数据
												 * 2、根据订单状态判断该订单是否已处理（因为通知会收到多次），避免重复处理
												 * 3、比对一下订单数据和通知数据是否一致，例如金额等
												 * 4、接下来修改订单状态为已支付或待发货
												 * 5、...
												 */

												// 判断通知类型，若为后台通知需要回写"SUCCESS"给支付系统表明已收到支付通知
												// 否则支付系统将按一定的时间策略在接下来的24小时内多次发送支付通知。
												if (request.getParameter("notifyType").equals("1")) {
													// 回写‘SUCCESS’方式一： 重定向到一个专门用于处理回写‘SUCCESS’的页面，这样可以保证输出内容中只有'SUCCESS'这个字符串。
													response.setContentType("text/html; charset=UTF-8");
													response.sendRedirect("notify.jsp");
													// 回写‘SUCCESS’方式二： 直接让当前输出流中包含‘SUCCESS’字符串。两种方式都可以，但建议采用第一种。
													// out.println("SUCCESS");
												}
											} else
												verifyStatus = "验签失败";

											// 把获得的这些数据显示到页面上，这里只是为了演示，实际应用中应该不需要把这些数据显示到页面上。
											StringBuffer sbHtml = new StringBuffer();
											sbHtml
													.append("<tr><td align=\"left\" width=\"30%\">接口名称</td><td align=\"left\">&nbsp;&nbsp;"
															+ apiName + "</td></tr>");
											sbHtml
													.append("<tr><td align=\"left\" width=\"30%\">通知时间</td><td align=\"left\">&nbsp;&nbsp;"
															+ notifyTime + "</td></tr>");
											sbHtml
													.append("<tr><td align=\"left\" width=\"30%\">支付金额</td><td align=\"left\">&nbsp;&nbsp;"
															+ tradeAmt + "</td></tr>");
											sbHtml
													.append("<tr><td align=\"left\" width=\"30%\">商户号</td><td align=\"left\">&nbsp;&nbsp;"
															+ merchNo + "</td></tr>");
											sbHtml
													.append("<tr><td align=\"left\" width=\"30%\">商户参数</td><td align=\"left\">&nbsp;&nbsp;"
															+ merchParam + "</td></tr>");
											sbHtml
													.append("<tr><td align=\"left\" width=\"30%\">商户订单号</td><td align=\"left\">&nbsp;&nbsp;"
															+ orderNo + "</td></tr>");
											sbHtml
													.append("<tr><td align=\"left\" width=\"30%\">商户交易日期</td><td align=\"left\">&nbsp;&nbsp;"
															+ tradeDate + "</td></tr>");
											sbHtml
													.append("<tr><td align=\"left\" width=\"30%\">支付平台订单号</td><td align=\"left\">&nbsp;&nbsp;"
															+ accNo + "</td></tr>");
											sbHtml
													.append("<tr><td align=\"left\" width=\"30%\">支付平台订单日期</td><td align=\"left\">&nbsp;&nbsp;"
															+ accDate + "</td></tr>");
											sbHtml
													.append("<tr><td align=\"left\" width=\"30%\">订单状态</td><td align=\"left\">&nbsp;&nbsp;"
															+ orderStatus + "</td></tr>");
											sbHtml
													.append("<tr><td align=\"left\" width=\"30%\">验签结果描述</td><td align=\"left\">&nbsp;&nbsp;"
															+ verifyStatus + "</td></tr>");
											out.print(sbHtml.toString());
										} catch (Exception ex) {
											out.print(ex.getMessage());
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

