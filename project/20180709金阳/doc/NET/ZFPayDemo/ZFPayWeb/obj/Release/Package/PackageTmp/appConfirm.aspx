<%@ Page Language="C#" AutoEventWireup="true" CodeBehind="appConfirm.aspx.cs" Inherits="ZFPayWeb.appConfirm" %>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title></title>
</head>
<body>

        <div>
            <h2>客户端支付订单确认</h2>
            <ul class="fix">
                <li>
                    <label>订单号：<font color="red">*</font></label>
                    <%=p4_orderno %>
                </li>
                <li>
                    <label>金额：<font color="red">*</font></label>
                    <%=p3_paymoney %>
                </li>
            </ul>
            <%=sHtmlText %>
        </div>
 
</body>
</html>
