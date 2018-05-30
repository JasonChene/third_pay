<!-- #include file="merchantCommon.asp" -->

<%
'***********************************************
'* @Description 支付系统产品通用支付接口范例
'* @Version 1.0
'***********************************************
%>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>支付系统商户接口范例-支付</title>
    <link href="css/mobaopay.css" type="text/css" rel="stylesheet" />
</head>
<body>
    <table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" style="border: solid 1px #107929">
        <tr>
            <td>
                <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
                    
                    <tr>
                        <td height="30" colspan="2" bgcolor="#6BBE18">
                            <span style="color: #FFFFFF"><a href="index.html"><a href="index.html">感谢您使用支付系统平台</a></span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" bgcolor="#CEE7BD">
                            支付系统订单支付请求接口演示：
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <form method="post" action="req.asp" targe="_blank">
                            <table>
                                <tr>
                                    <td align="left" width="30%">
                                        &nbsp;&nbsp;订单号
                                    </td>
                                    <td align="left">
                                        &nbsp;&nbsp;<input size="50" type="text" name="orderNo" id="orderNo" value="<%= generatOrderNo()%>" />
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left" width="30%">
                                        &nbsp;&nbsp;交易日期
                                    </td>
                                    <td align="left">
                                        &nbsp;&nbsp;<input size="50" type="text" name="tradeDate" id="tradeDate" value="<%= getTradeDate%>" />
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
                                        &nbsp;&nbsp;<input size="50" type="text" name="tradeSummary" id="tradeSummary" value="支付测试" />
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left" width="30%">
                                        &nbsp;&nbsp;银行代码
                                    </td>
                                    <td align="left">
                                        &nbsp;&nbsp;<input size="50" type="text" name="bankCode" id="bankCode" value="" />
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
