<%@page language="java" pageEncoding="UTF-8"%>
<%@page import="com.mobo360.merchant.api.*"%>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>支付系统商户接口范例-实名无卡代扣</title>
	<!--
	<link rel="stylesheet" type="text/css" href="styles.css">
	<link href="css/mobaopay.css" type="text/css" rel="stylesheet" />
	-->
</head>
<body>
    <table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" style="border: solid 1px #107929">
        <tr>
            <td>
                <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
                  
                    <tr>
                        <td height="30" colspan="2" bgcolor="#6BBE18">
                            <span style="color: #FFFFFF"><a href="index.jsp">感谢您使用支付系统平台</a></span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" bgcolor="#CEE7BD">
                            支付系统实名无卡代扣请求接口演示：
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <form method="post" action="custRealPay.jsp">
                            <table>
                                <tr>
									<td align="left" width="30%">
										&nbsp;&nbsp;订单号
									</td>
									<td align="left">
										&nbsp;&nbsp;<input size="50" type="text" name="orderNo" id="orderNo" value="<% out.println(UtilDate.getOrderNum()); %>" />
									</td>
								</tr>
								<tr>
									<td align="left" width="30%">
										&nbsp;&nbsp;交易日期
									</td>
									<td align="left">
										&nbsp;&nbsp;<input size="8" type="text" name="tradeDate" id="tradeDate" value="<% out.println(UtilDate.getDate()); %>" />
									</td>
								</tr>
								<tr>
									<td align="left" width="30%">
										&nbsp;&nbsp;交易金额
									</td>
									<td align="left">
										&nbsp;&nbsp;<input size="50" type="text" name="amt" id="amt" value="0.5" />
									</td>
								</tr>
								<tr>
									<td align="left" width="30%">
										&nbsp;&nbsp;商户参数
									</td>
									<td align="left">
										&nbsp;&nbsp;<input size="50" type="text" name="merchParam" id="merchParam" value="abcd" />
									</td>
								</tr>
								<tr>
									<td align="left" width="30%">
										&nbsp;&nbsp;交易摘要
									</td>
									<td align="left">
										&nbsp;&nbsp;<input size="50" type="text" name="tradeSummary" id="tradeSummary" value="无卡代扣" />
									</td>
								</tr>
								<tr>
									<td align="left" width="30%">
										&nbsp;&nbsp;持卡人姓名
									</td>
									<td align="left">
										&nbsp;&nbsp;<input size="50" type="text" name="cardName" id="cardName" value="测试" />
									</td>
								</tr>
								<tr>
									<td align="left" width="30%">
										&nbsp;&nbsp;持卡人身份证号码
									</td>
									<td align="left">
										&nbsp;&nbsp;<input size="18" type="text" name="idCardNo" id="idCardNo" value="610000198312173075" />
									</td>
								</tr>
								<tr>
									<td align="left" width="30%">
										&nbsp;&nbsp;银行卡归属银行
									</td>
									<td align="left">
										&nbsp;&nbsp;
										<select name="cardBankCode">
										  <option value ="ICBC">工商银行</option>
										  <option value ="ABC">农业银行</option>
										  <option value="CCB">建设银行</option>
										  <option value="BOC">中国银行</option>
										  <option value="PSBC">中国邮政储蓄银行</option>
										  <option value="CEB">光大银行</option>
										  <option value="CIB">兴业银行</option>
										  <option value="GDB">广州发展银行</option>
										  <option value="CMBC">民生银行</option>
										</select>
									</td>
								</tr>
								<tr>
									<td align="left" width="30%">
										&nbsp;&nbsp;银行卡号
									</td>
									<td align="left">
										&nbsp;&nbsp;<input size="50" type="text" name="cardNo" id="cardNo" value="6226123400009999888" />
									</td>
								</tr>
                                <tr>
                                    <td align="left">
                                        &nbsp;
                                    </td>
                                    <td align="left">
                                        &nbsp;&nbsp;<input type="submit" value="马上支付" />
                                    </td>
                                </tr>
                            </table>
                            </form>
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
