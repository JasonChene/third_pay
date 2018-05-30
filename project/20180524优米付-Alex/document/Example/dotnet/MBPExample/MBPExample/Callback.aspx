<%@ Page Language="C#" AutoEventWireup="true" CodeBehind="Callback.aspx.cs" Inherits="com.mobaopay.merchant.Callback" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>支付系统商户接口范例-支付</title>
	<!--
	<link rel="stylesheet" type="text/css" href="styles.css">
	<link href="Styles/mobaopay.css" type="text/css" rel="stylesheet" />
	-->
</head>
<body runat="server">
    <table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" style="border: solid 1px #107929">
        <tr>
            <td>
                <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
                   
                    <tr>
                        <td height="30" colspan="2" bgcolor="#6BBE18">
                            <span style="color: #FFFFFF"><a href="index.htm">感谢您使用支付系统平台</a></span>
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
                                <tr>
                                    <td align="left" width="30%">
                                        &nbsp;&nbsp;接口名称
                                    </td>
                                    <td align="left">
                                        &nbsp;&nbsp;<input size="50" type="text" name="apiName" id="apiName" value="<%=apiName%>" />&nbsp;
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left" width="30%">
                                        &nbsp;&nbsp;通知时间
                                    </td>
                                    <td align="left">
                                        &nbsp;&nbsp;<input size="50" type="text" name="notifyTime" id="notifyTime" value="<%=notifyTime%>" />&nbsp;
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left" width="30%">
                                        &nbsp;&nbsp;交易金额
                                    </td>
                                    <td align="left">
                                        &nbsp;&nbsp;<input size="50" type="text" name="tradeAmt" id="tradeAmt" value="<%=tradeAmt%>" />
                                    </td>
                                </tr>
								<tr>
									<td align="left" width="30%">
										&nbsp;&nbsp;商户帐号
									</td>
									<td align="left">
										&nbsp;&nbsp;<input size="50" type="text" name="merchNo" id="merchNo" value="<%=merchNo%>" />
									</td>
								</tr>
								<tr>
									<td align="left" width="30%">
										&nbsp;&nbsp;商户参数
									</td>
									<td align="left">
										&nbsp;&nbsp;<input size="50" type="text" name="merchParam" id="merchParam" value="<%=merchParam%>" />
									</td>
								</tr>
								<tr>
									<td align="left" width="30%">
										&nbsp;&nbsp;订单号
									</td>
									<td align="left">
										&nbsp;&nbsp;<input size="50" type="text" name="orderNo" id="orderNo" value="<%=orderNo%>" />
									</td>
								</tr>
								<tr>
									<td align="left" width="30%">
										&nbsp;&nbsp;交易日期
									</td>
									<td align="left">
										&nbsp;&nbsp;<input size="50" type="text" name="tradeDate" id="tradeDate" value="<%=tradeDate%>" />
									</td>
								</tr>
								<tr>
									<td align="left" width="30%">
										&nbsp;&nbsp;支付系统订单号
									</td>
									<td align="left">
										&nbsp;&nbsp;<input size="50" type="text" name="accNo" id="accNo" value="<%=accNo%>" />
									</td>
								</tr>
								<tr>
									<td align="left" width="30%">
										&nbsp;&nbsp;支付系统日期
									</td>
									<td align="left">
										&nbsp;&nbsp;<input size="50" type="text" name="accDate" id="accDate" value="<%=accDate%>" />
									</td>
								</tr>
								<tr>
									<td align="left" width="30%">
										&nbsp;&nbsp;订单状态
									</td>
									<td align="left">
										&nbsp;&nbsp;<input size="50" type="text" name="orderStatus" id="orderStatus" value="<%=orderStatus%>" />
									</td>
								</tr>
								<tr>
									<td align="left" width="30%">
										&nbsp;&nbsp;验签结果状态
									</td>
									<td align="left">
										&nbsp;&nbsp;<input size="50" type="text" name="verifyStatus" id="verifyStatus" value="<%=veryfyDesc%>" />
									</td>
								</tr>
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


