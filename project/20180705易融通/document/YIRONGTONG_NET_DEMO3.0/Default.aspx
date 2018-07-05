<%@ Page Language="C#" AutoEventWireup="true" CodeBehind="Default.aspx.cs" Inherits="JRAPI_NET_DEMO.Default" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <title></title>
    <style type="text/css">
        .style1
        {
            width: 681px;
        }
        .style2
        {
            width: 158px;
            text-align: right;
        }
        .style3
        {
            width: 455px;
        }
    </style>
</head>
<body>
    <form id="form1" runat="server">
    <div>
    
        <table class="style1">
            <tr>
                <td class="style2">
                    提交地址:</td>
                <td class="style3">
                    <asp:TextBox ID="txtUrl" runat="server" Width="400px" Text="http://gateway.forvlove.com/online/gateway"></asp:TextBox>
                </td>
            </tr>
            <tr>
                <td class="style2">
                    版本号:</td>
                <td class="style3">
                    <asp:TextBox ID="txtversion" runat="server" Width="400px" Text="3.0"></asp:TextBox>
                </td>
            </tr>
            <tr>
                <td class="style2">
                    接口名称:</td>
                <td class="style3">
                    <asp:TextBox ID="txtmethod" runat="server" Width="400px" Text="Gt.online.interface"></asp:TextBox>
                </td>
            </tr>
            <tr>
                <td class="style2">
                    商户ID:</td>
                <td class="style3">
                    <asp:TextBox ID="txtpartner" runat="server" Width="214px">16960</asp:TextBox>
                </td>
            </tr>            
            <tr>
                <td class="style2">
                    银行类型:</td>
                <td class="style3">
                    <asp:TextBox ID="txtbanktype" runat="server">ICBC</asp:TextBox>
                </td>
            </tr>
            <tr>
                <td class="style2">
                    订单金额:</td>
                <td class="style3">
                    <asp:TextBox ID="txtpaymoney" runat="server">2.00</asp:TextBox>
                </td>
            </tr>
            <tr>
                <td class="style2">
                    订单号码:</td>
                <td class="style3">
                    <asp:TextBox ID="txtordernumber" runat="server"></asp:TextBox>
                </td>
            </tr>

            <tr>
                <td class="style2">
                    异步通知地址:</td>
                <td class="style3">
                    <asp:TextBox ID="txtcallbackurl" runat="server" Width="362px">http://www.baidu.com/callback.aspx</asp:TextBox>
                </td>
            </tr>
            <tr>
                <td class="style2">
                    同步跳转地址:</td>
                <td class="style3">
                    <asp:TextBox ID="txthrefbackurl" runat="server" Width="362px">http://www.baidu.com/jump.aspx</asp:TextBox>
                </td>
            </tr>
            <tr>
                <td class="style2">
                    商品名称:</td>
                <td class="style3">
                    <asp:TextBox ID="txtgoodsname" runat="server" Width="362px"></asp:TextBox>
                </td>
            </tr>
            <tr>
                <td class="style2">
                    备注信息:</td>
                <td class="style3">
                    <asp:TextBox ID="txtattach" runat="server">mygod</asp:TextBox>
                </td>
            </tr>
             <tr>
                <td class="style2">
                    是否显示收银台:</td>
                <td class="style3">
                    <asp:TextBox ID="txtisShow" runat="server" Text="1"></asp:TextBox>
                </td>
            </tr>
            <tr>
                <td class="style2">
                    商户KEY:</td>
                <td class="style3">
                    <asp:TextBox ID="txtKey" runat="server" Width="403px">270bbae38500459a90b7b2f49a9aa6ba</asp:TextBox>
                </td>
            </tr>
            
            <tr>
                <td class="style2">
                    &nbsp;</td>
                <td class="style3">
                    <asp:Button ID="btnSub" runat="server" onclick="btnSub_Click" Text="提交支付" />
                </td>
            </tr>
            <tr>
                <td class="style2">
                    &nbsp;</td>
                <td class="style3">
                    &nbsp;</td>
            </tr>
            <tr>
                <td class="style2">
                    &nbsp;</td>
                <td class="style3">
                    &nbsp;</td>
            </tr>
        </table>
    
    </div>
    </form>
</body>
</html>
