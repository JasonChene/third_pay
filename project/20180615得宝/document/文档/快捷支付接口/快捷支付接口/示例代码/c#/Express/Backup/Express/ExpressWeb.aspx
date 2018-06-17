<%@ Page Language="C#" AutoEventWireup="True" CodeBehind="ExpressPay.aspx.cs" Inherits="CSS._Default" ResponseEncoding="utf-8"%>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head id="Head1" runat="server">
    <title></title>
</head>
<body>
    <form action="https://api.yuanruic.com/gateway/api/express" id="dinpayForm" name="dinpayForm" method="POST" runat="server">

            <input type="hidden" name="sign" id="sign"  runat="server" />

            <input type="hidden" name="interface_version" id="interface_version" runat="server" />

            <input type="hidden" name="input_charset" id="input_charset" runat="server" />

            <input type="hidden" name="service_type" id="service_type" runat="server" />

            <input type="hidden" name="sign_type" id="sign_type" runat="server" />

            <input type="hidden" name="merchant_code" id="merchant_code" runat="server" />

            <input type="hidden" name="order_no" id="order_no" runat="server" />

            <input type="hidden" name="order_amount" id="order_amount" runat="server" />

            <input type="hidden" name="order_time" id="order_time" runat="server" />

            <input type="hidden" name="notify_url" id="notify_url" runat="server" />

            <input type="hidden" name="card_type" id="card_type" runat="server" />

            <input type="hidden" name="mobile" id="mobile" runat="server"  />

            <input type="hidden" name="bank_code" id="bank_code" runat="server" />

            <input type="hidden" name="product_name" id="product_name" runat="server" />

            <input type="hidden" name="encrypt_info" id="encrypt_info"  runat="server" />

            <script type="text/javascript">
                document.getElementById("dinpayForm").submit();
            </script>
    </form>
</body>
</html>
