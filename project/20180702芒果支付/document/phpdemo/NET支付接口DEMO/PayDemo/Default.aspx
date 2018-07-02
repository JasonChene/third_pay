<%@ Page Language="C#" AutoEventWireup="true" CodeBehind="Default.aspx.cs" Inherits="PayDemo._Default" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" >
<head runat="server">
    <title>无标题页</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>
    <form id="form1" runat="server">
    <div>
    支付类型：<asp:RadioButtonList ID="type_code" runat="server">
    <asp:ListItem Value="wxbs" Selected="True">微信被扫</asp:ListItem>
    <asp:ListItem Value="zfbbs">支付宝被扫</asp:ListItem>
    <asp:ListItem Value="qqbs">QQ钱包被扫</asp:ListItem>
    <asp:ListItem Value="qqh5">QQ钱包h5</asp:ListItem>
    <asp:ListItem Value="gateway">网关</asp:ListItem>
    <asp:ListItem Value="sms">短信</asp:ListItem>
        </asp:RadioButtonList>
        <br />
        主题：<asp:TextBox ID="subject" runat="server" Text="测试"></asp:TextBox>
        <br />
        交易金额：<asp:TextBox ID="amount" runat="server" Text="0.1"></asp:TextBox>
        <br />
        商户订单号：<asp:TextBox ID="down_sn" runat="server" ></asp:TextBox>
        <br />
        银行卡类型：
        <asp:RadioButtonList ID="card_type" runat="server">
    <asp:ListItem >对私借记卡</asp:ListItem>
    <asp:ListItem>对私贷记卡</asp:ListItem>
    <asp:ListItem>对公借记卡</asp:ListItem>
        </asp:RadioButtonList>
        <br />
        银行代号：<asp:TextBox ID="bank_segment" runat="server" Text=""></asp:TextBox>
        <br />
        用户类型：
        <asp:RadioButtonList ID="user_type" runat="server">
    <asp:ListItem>个人</asp:ListItem>
    <asp:ListItem>企业</asp:ListItem>
        </asp:RadioButtonList>
        渠道：<asp:RadioButtonList ID="agent_type" runat="server">
    <asp:ListItem>PC端</asp:ListItem>
    <asp:ListItem>手机端</asp:ListItem>
        </asp:RadioButtonList>
        <br />
        手机号码：<asp:TextBox ID="mobile" runat="server" ></asp:TextBox>
        <br />
        姓名：<asp:TextBox ID="account_name" runat="server" ></asp:TextBox>
        <br />
        证件号码：<asp:TextBox ID="id_card_no" runat="server" ></asp:TextBox>
        <br />
        银行卡号：<asp:TextBox ID="account_no" runat="server" ></asp:TextBox>
        <br />
        notify url：<asp:TextBox ID="notify_url" runat="server" >http://www.163.com</asp:TextBox>
        <br />
        return url：<asp:TextBox ID="return_url" runat="server" ></asp:TextBox>
        <br />
        <asp:Button ID="Button1" runat="server" Text="提 交" OnClick="Button1_Click" />
    </div>
    </form>
</body>
</html>
