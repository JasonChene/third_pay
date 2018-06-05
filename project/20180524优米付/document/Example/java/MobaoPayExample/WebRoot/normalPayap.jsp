<%@page language="java" pageEncoding="UTF-8"%>
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
	<script type="text/javascript">
        function onChecked(obj) {
            document.getElementById(obj).checked = true;
        }
    </script>
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
                            支付系统订单支付请求接口演示：
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <form method="post" action="payap.jsp" target="_blank">
                            <table>
								<tr>
									<td align="left" width="30%">
										&nbsp;&nbsp;订单号
									</td>
									<td align="left">
										&nbsp;&nbsp;<input size="50" type="text" name="orderNo" id="orderNo" 
										value="<% out.println(UtilDate.getOrderNum()); %>" />
									</td>
								</tr>
								<tr>
									<td align="left" width="30%">
										&nbsp;&nbsp;交易日期
									</td>
									<td align="left">
										&nbsp;&nbsp;<input size="50" type="text" name="tradeDate" id="tradeDate" 
										value="<% out.println(UtilDate.getDate()); %>" />
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
                                        &nbsp;&nbsp;选择银行
                                    </td>
                                    <td align="left">
                                        <ul class="clearfix">
                                            <li>
                                       
                                                <input name="bankCode" value="ICBC" type="radio" id="ICBC" />
                                                <img src="images/gsyh.gif" alt="工商银行" onclick="onChecked('ICBC');" />
                                            </li>
                                            <li>
                                                <input name="bankCode" value="CMB" type="radio" id="CMB" /><img
                                                    src="images/zsyh.gif" alt="招商银行" onclick="onChecked('CMB');" />
                                                <input name="bankCode" value="CCB" type="radio" id="CCB" /><img
                                                    src="images/jsyh.gif" alt="建设银行" onclick="onChecked('CCB');" /></li>
                                            <li>
                                                <input name="bankCode" value="COMM" type="radio" id="COMM" /><img
                                                    src="images/jtyh.gif" alt="交通银行" onclick="onChecked('COMM');" />
                                                <input name="bankCode" value="ABC" type="radio" id="ABC" /><img
                                                    src="images/nyyh.gif" alt="农业银行" onclick="onChecked('ABC');" /></li>
                                            <li>
                                                <input name="bankCode" value="BOC" type="radio" id="BOC" /><img
                                                    src="images/zgyh.gif" alt="中国银行" onclick="onChecked('BOC');" />
                                                <input name="bankCode" value="CIB" type="radio" id="CIB" /><img
                                                    src="images/xyyh.gif" alt="兴业银行" onclick="onChecked('CIB');" /></li>
                                            <li>
                                                <input name="bankCode" value="SPDB" type="radio" id="SPDB" /><img
                                                    src="images/pdfzyh.gif" alt="浦发银行" onclick="onChecked('SPDB');" />
                                                <input name="bankCode" value="CMBC" type="radio" id="CMBC" /><img
                                                    src="images/msyh.gif" alt="民生银行" onclick="onChecked('CMBC');" /></li>
                                            <li>
                                                <input name="bankCode" value="CNCB" type="radio" id="CNCB" /><img
                                                    src="images/zxyh.gif" alt="中信银行" onclick="onChecked('CNCB');" />
                                                <input name="bankCode" value="CEB" type="radio" id="CEB" /><img
                                                    src="images/gdyh.gif" alt="光大银行" onclick="onChecked('CEB');" /></li>
                                            <li>
                                                <input name="bankCode" value="HXB" type="radio" id="HXB" /><img
                                                    src="images/hxyh.gif" alt="华夏银行" onclick="onChecked('HXB');" />
                                                <input name="bankCode" value="PSBC" type="radio" id="PSBC" /><img
                                                    src="images/yzcxyh.gif" alt="邮政储蓄银行" onclick="onChecked('PSBC');" /></li>
                                            <li>
                                                <input name="bankCode" value="CGB" type="radio" id="CGB" /><img
                                                    src="images/gdfzyh.gif" alt="广发银行" onclick="onChecked('CGB');" />
                                                <input name="bankCode" value="PAB" type="radio" id="PAB" /><img
                                                    src="images/payh.gif" alt="平安银行" onclick="onChecked('PAB');" /></li>
                                        </ul>
                                    </td>
                                </tr>
								
						      <tr>
									<td align="left" width="30%">
										&nbsp;&nbsp;<strong>选择支付方式 </font></strong>
									</td>
									<td align="left">  
							 <li><select id="choosePayType" name="choosePayType"
							style="height: 25px; width: 125px;">
							<option value="">所有</option>
							<option value="5">微信扫码</option>	
							<option value="1">网银支付</option>
						    </select></li>
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
