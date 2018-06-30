<%@ Page Language="C#" AutoEventWireup="true" CodeBehind="Default.aspx.cs" Inherits="NET_DEMO.Default" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>收银台</title>
</head>
     
 
<body>

    <form id="form1" runat="server">
        <table>
            <tr>
                <td>订单号
                </td>
                <td><asp:TextBox ID="txtOrderNo" runat="server"></asp:TextBox></td>
            </tr>
            <tr>
                <td>支付金额
                </td>
                <td><asp:TextBox ID="txtMoney" runat="server" Text="0.01"></asp:TextBox>元 </td>
            </tr>
            <tr>
                <td>支付方式
                </td>
                <td>
                    <select name="paytype" id="paytype">
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
                <td> <asp:Button ID="Button1" runat="server" Text="立即支付" OnClick="btnSub_Click" /> </td>
            </tr>
        </table>
    </form>
</body>
</html>
