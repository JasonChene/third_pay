<%@ page language="java" pageEncoding="UTF-8"%>
<%
String path = request.getContextPath();
String basePath = request.getScheme()+"://"+request.getServerName()+":"+request.getServerPort()+path+"/";
%>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <base href="<%=basePath%>">
    
    <title>支付系统商户接口范例-首页</title>
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="cache-control" content="no-cache">
	<meta http-equiv="expires" content="0">    
	<meta http-equiv="keywords" content="keyword1,keyword2,keyword3">
	<meta http-equiv="description" content="This is my page">
	<!--
	<link rel="stylesheet" type="text/css" href="styles.css">
	<link href="css/mobaopay.css" type="text/css" rel="stylesheet" />
	-->
  </head>
  
  <body>
    <table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" style="border: solid 1px #107929">
        <tr>
            <td>
                <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
                  
                    <tr>
                        <td height="30" colspan="2" bgcolor="#6BBE18">
                            <span style="color: #FFFFFF">感谢您使用支付系统平台</span>
                        </td>
                    </tr>
                    <tr>
                        <td width="30%" align="left" bgcolor="#CEE7BD">
                            支付系统产品通用支付接口演示：
                        </td>
                    </tr>
                    <tr>
                        <td height="30" align="left" bgcolor="#FFFFEF">
                            &nbsp;&nbsp;<a href="normalPay.jsp">产品通用WEB支付接口</a>
                        </td>
                    </tr>
                     <tr>
                        <td height="30" align="left" bgcolor="#FFFFEF">
                            &nbsp;&nbsp;<a href="normalPayap.jsp">产品通用WEB支付接口(防钓鱼)</a>
                        </td>
                    </tr>
                      <tr>
                        <td height="30" align="left" bgcolor="#FFFFEF">
                            &nbsp;&nbsp;<a href="DirectSettlement.jsp">直联委托结算</a>
                        </td>
                    </tr>
                    <tr>
                        <td height="30" align="left" bgcolor="#FFFFEF">
                            &nbsp;&nbsp;<a href="queryOrd.html">查询订单</a>
                        </td>
                    </tr>
                    <tr>
                        <td height="30" align="left" bgcolor="#FFFFEF">
                            &nbsp;&nbsp;<a href="refundOrd.html">退款操作</a>
                        </td>
                    </tr>
                    <tr>
                        <td height="30" align="left" bgcolor="#FFFFEF">
                            &nbsp;&nbsp;<a href="custRealPayPage.jsp">实名无卡代扣</a>
                        </td>
                    </tr>
                    <tr>
                        <td height="5" bgcolor="#6BBE18">
                        	&nbsp;&nbsp;
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

  </body>
</html>
