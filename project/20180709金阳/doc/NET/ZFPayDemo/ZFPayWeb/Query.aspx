<%@ Page Language="C#" AutoEventWireup="true" CodeBehind="Query.aspx.cs" Inherits="ZFPayWeb.Query" %>
<%@ Import Namespace="ZFPayWeb.Common" %>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>订单查询</title>
</head>
<body>
    <form id="form1" runat="server">
    <div>
      <table class="style1">
                <tr>
                    <td class="style2">提交地址:</td>
                    <td class="style3">
                      <asp:TextBox ID="txtUrl" runat="server" Width="400px" Text="http://query.095pay.com/zfapi/order/singlequery"></asp:TextBox>
                    </td>
                </tr>
                <tr>
                    <td class="style2">商户ID:</td>
                    <td class="style3">
                        <asp:TextBox ID="txtpartner" runat="server" Width="214px"></asp:TextBox>
                    </td>
                </tr>
                <tr>
                    <td class="style2">商户KEY:</td>
                    <td class="style3">
                        <asp:TextBox ID="txtKey" runat="server" Width="403px"></asp:TextBox>
                    </td>
                </tr>
                <tr>
                    <td class="style2">加密类型:</td>
                    <td class="style3">
                        <asp:DropDownList ID="ddlsignType" runat="server">
                            <asp:ListItem Value="1">MD5</asp:ListItem>
                        </asp:DropDownList>
                    </td>
                </tr>
                <tr>
                    <td class="style2">版本号：</td>
                    <td class="style3">
                        <asp:TextBox ID="txtversion" runat="server">v2.8</asp:TextBox>
                    </td>
                </tr>
                <tr>
                    <td class="style2">订单号码:</td>
                    <td class="style3">
                        <asp:TextBox ID="txtordernumber" runat="server" Width="254px"></asp:TextBox>
                    </td>
                </tr>
                <tr>
                    <td class="style2">&nbsp;</td>
                    <td class="style3">
                        <asp:Button ID="btnSub" runat="server" OnClick="btnSub_Click" Text="提交支付" />
                    </td>
                </tr>
                <tr>
                    <td class="style2">&nbsp;</td>
                    <td class="style3">&nbsp;</td>
                </tr>
                <tr>
                    <td class="style2">&nbsp;</td>
                    <td class="style3">&nbsp;</td>
                </tr>
            </table>
    </div>
    </form>
</body>
</html>
