<%@ Page Language="C#" AutoEventWireup="True" CodeBehind="Query.aspx.cs" Inherits="testOrderQuery.getXmlData" ResponseEncoding="utf-8"%>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <title>订单查询</title>
</head>
<body>
    <form action="https://query.zdrsz.cn/query/dcard" id="dinpayForm" name="dinpayForm" method="POST" runat="server">
            <input type="hidden" name="sign" id="sign"  runat="server" />

            <input type="hidden" name="merchant_code" id="merchant_code" runat="server" />

            <input type="hidden" name="service_type" id="service_type" runat="server" />

            <input type="hidden" name="sign_type" id="sign_type" runat="server" />

            <input type="hidden" name="interface_version" id="interface_version" runat="server" />

            <input type="hidden" name="order_no" id="order_no" runat="server" />

            <script type="text/javascript">
                document.getElementById("dinpayForm").submit();
            </script>
    </form>
</body>
</html>
</body>
</html>
