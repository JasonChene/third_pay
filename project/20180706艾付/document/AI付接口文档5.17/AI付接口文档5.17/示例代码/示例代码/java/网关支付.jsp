<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
<%@ page import="com.payment.struct.util.Md5,com.payment.struct.util.Tools" %>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>网关支付</title>
    <style type="text/css">
        .center {
            padding-left: 600px;
        }
    </style>
</head>
<body>
<%
    String version = "v1";                                  //接口版本
		String merchant_no = "144710001674";                  //商户号
    String order_no = Tools.getSysTime();                   //商户订单号
    String goods_name = "充值";                               //商品名称
    String order_amount = "1";                              //订单金额
    String backend_url = "";                                //支付结果异步通知地址
    String frontend_url = "";                               //支付结果同步通知地址
    String reserve = "";                                    //商户保留信息
    String pay_mode = "01";                                 //支付模式
    String bank_code = "ABC";                               //银行编号
    String card_type = "0";                                 //允许支付的银行卡类型
    goods_name = Tools.base64Encoder(goods_name, "utf-8");  //Base64编码
		String key = "8359aaa5-ad06-11e7-9f73-71f8d998ba5e";  //商户接口秘钥

    //MD5签名
    String src = "version=" + version + "&merchant_no=" + merchant_no + "&order_no="
            + order_no + "&goods_name=" + goods_name + "&order_amount=" + order_amount
            + "&backend_url=" + backend_url + "&frontend_url="
            + frontend_url + "&reserve=" + reserve
            + "&pay_mode=" + pay_mode + "&bank_code=" + bank_code + "&card_type="
            + card_type;
    src += "&key=" + key;
    String sign = Md5.encodeUtf8(src);

    //接口地址
		String url = "https://pay.all-inpay.com/gateway/pay.jsp";
%>
<form action="<%=url%>" method="get" id="form">
    <div class="center">接口版本：<input name="version" value="<%=version%>"/></div>
    <div class="center">商户号：<input name="merchant_no" value="<%=merchant_no%>"/></div>
    <div class="center">商户订单号：<input name="order_no" value="<%=order_no%>"/></div>
    <div class="center">商品名称：<input name="goods_name" value="<%=goods_name%>"/></div>
    <div class="center">订单金额：<input name="order_amount" value="<%=order_amount%>"/></div>
    <div class="center">异步通知地址：<input name="backend_url" value="<%=backend_url%>"/></div>
    <div class="center">同步通知地址：<input name="frontend_url" value="<%=frontend_url%>"/></div>
    <div class="center">保留信息：<input name="reserve" value="<%=reserve%>"/></div>
    <div class="center">支付模式：<input name="pay_mode" value="<%=pay_mode%>"/></div>
    <div class="center">银行编号：<input name="bank_code" value="<%=bank_code%>"/></div>
    <div class="center">银行卡类型：<input name="card_type" value="<%=card_type%>"/></div>
    <div class="center">MD5签名：<input name="sign" value="<%=sign%>"/></div>
    <div class="center"><input type="submit" value="提交"></div>
</form>
</body>
</html>
