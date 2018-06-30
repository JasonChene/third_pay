<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%@page trimDirectiveWhitespaces="true"%>
<%

String ordernumber ="20170134240000" ;//订单号

String subject= "商品1";  //商品名称

String paymoney= "100";  //交易金额(单位：元)

String attach= "描述";   //交易描述

%>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>收银台</title>
</head>
<body>
<div id="header">
<div class="head margin">
<div class="log"></div>
</div>
</div>
<form name='form1' method="post" action="pay.jsp" target="_blank">
<input name="ordernumber" type="hidden"  value="<%=ordernumber%>">
<input name="paymoney" type="hidden" value="<%=paymoney%>">
<input name="attach" type="hidden" value="<%=attach%>">
 <table>
            <tr>
                <td>订单号
                </td>
                <td><%=ordernumber%> </td>
            </tr>
            <tr>
                <td>支付金额
                </td>
                <td><%=paymoney%>元 </td>
            </tr>
            <tr>
                <td>支付方式
                </td>
                <td>
                    <select name="appid" id="appid">
			<option value="1">支付宝扫码</option>
                        <option value="2">微信扫码</option>
                        <option value="3">QQ钱包扫码</option>
                        <option value="ICBC">工商银行</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                </td>
                <td> <input type="button" id="btn_pay" value="提交支付"  onclick="return pay();" /> </td>
            </tr>
        </table>
    </form>
</body>
</html>