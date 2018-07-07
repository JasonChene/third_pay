<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
<%@ page import="com.payment.struct.util.Md5" %>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>查询支付订单</title>
    <style type="text/css">
        .center {
            padding-left: 600px;
        }
    </style>
</head>
<body>
<%
		String merchant_no = "144710001674";                    //商户号
    String order_no = "20171017112428";                     //商户订单号
		String key = "8359aaa5-ad06-11e7-9f73-71f8d998ba5e";  //商户接口秘钥

    //MD5签名
    String src = "merchant_no=" + merchant_no + "&order_no=" + order_no + "&key=" + key;
    String sign = Md5.encodeUtf8(src);

    //接口地址
    String url = "https://pay.all-inpay.com/gateway/queryOrder.jsp";
%>
<form action="<%=url%>" method="post" id="form">
    <div class="center">商户号：<input name="merchant_no" value="<%=merchant_no%>"/></div>
    <div class="center">商户订单号：<input name="order_no" value="<%=order_no%>"/></div>
    <div class="center">MD5签名：<input name="sign" value="<%=sign%>"/></div>
    <div class="center"><input type="submit" value="提交"/></div>
</form>
</body>
</html>
