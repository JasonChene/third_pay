<%@ page language="java" contentType="text/html; charset=UTF-8"
    pageEncoding="UTF-8"%>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Insert title here</title>
</head>
<body>
    <div>
        <h2>客户端支付订单确认</h2>
        <ul class="fix">
            <li>
                <label>订单号：<font color="red">*</font></label> <%= request.getParameter("p4_orderno") %>
            </li>
            <li><label>金额：<font color="red">*</font></label> <%= request.getParameter("p3_paymoney") %></li>
        </ul>
        <form id="appForm" name="appForm"
            action="http://pay.095pay.com/api/order/pay" method="post">
            <input type="hidden" name="p1_mchtid" value="<%= request.getParameter("p1_mchtid") %>" />
            <input type="hidden" name="p2_paytype" value="<%= request.getParameter("p2_paytype") %>" />
            <input type="hidden" name="p3_paymoney" value="<%= request.getParameter("p3_paymoney") %>" />
            <input type="hidden" name="p4_orderno" value="<%= request.getParameter("p4_orderno") %>" />
            <input type="hidden" name="p5_callbackurl" value="<%= request.getParameter("p5_callbackurl") %>" />
            <input type="hidden" name="p6_notifyurl" value="<%= request.getParameter("p6_notifyurl") %>" />
            <input type="hidden" name="p7_version" value="<%= request.getParameter("p7_version") %>" />
            <input type="hidden" name="p8_signtype" value="<%= request.getParameter("p8_signtype") %>" />
            <input type="hidden" name="p9_attach" value="<%= request.getParameter("p9_attach") %>" />
            <input type="hidden" name="p10_appname" value="<%= request.getParameter("p10_appname") %>" />
            <input type="hidden" name="p11_isshow" value="<%= request.getParameter("p11_isshow") %>" />
            <input type="hidden" name="p12_orderip" value="<%= request.getParameter("p12_orderip") %>" />
            <input type="hidden" name="sign" value="<%= request.getAttribute("sign") %>" />
            <input type="submit" value="确认付款">
        </form>
    </div>
</body>
</html>