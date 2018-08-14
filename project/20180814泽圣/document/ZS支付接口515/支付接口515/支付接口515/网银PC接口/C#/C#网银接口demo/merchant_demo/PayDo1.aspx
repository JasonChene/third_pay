<%@ Page Language="C#" AutoEventWireup="true" CodeBehind="PayDo1.aspx.cs" Inherits="merchant_demo.PayDo1" %>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
</head>
<body>
    <form id="form1" method="post" action="<%=QueryParam %>">
    <%
        for (int i = 0; i < Demo.Class.MD5.GetPayParamSort().Length;i++ )
        {
            key = Demo.Class.MD5.GetPayParamSort()[i];
            if (key == "merchantCode")
                keyValue = Demo.Class.ProperConst.merchantCode;
            else
                keyValue = Request.Form[Demo.Class.MD5.GetPayParamSort()[i]];
        %>
        <input type="text" name="<%=key %>" style=" display:none;" value="<%=keyValue %>"/>
       <%     
        }
    %>
    <input type="text" name="sign"  style=" display:none;" value="<%=sign %>"/>
    </form>
    <%
        Response.Write("<script type=\"text/javascript\"> document.getElementById(\"form1\").submit();</script>");
     %>
</body>
</html>
