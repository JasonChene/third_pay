<%@ page language="java" contentType="text/html; charset=UTF-8"
	pageEncoding="UTF-8"%>
<%@ taglib prefix="c" uri="http://java.sun.com/jsp/jstl/core"%>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>订单查询</title>
</head>
<body>
<%
    Object msg = request.getAttribute("msg");
%>
<c:if test="${ msg != null }">
<span><%= msg %></span>
</c:if>
	<form method="post" action="./submitQuery" id="form1">
		<div class="aspNetHidden">
			<input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE"
				value="qlts5EDM2GtXnZHxYsYolRbYPb9xPtDwemnOOain8k9m4eFZZ/HyPr7pfdD9DkA1IuAqMORooyJElMTG8qqOVEOrC2AIK0TvVbAGMzoRhHY=" />
		</div>

		<div class="aspNetHidden">
			<input type="hidden" name="__VIEWSTATEGENERATOR"
				id="__VIEWSTATEGENERATOR" value="EDD8C9AE" /> <input type="hidden"
				name="__EVENTVALIDATION" id="__EVENTVALIDATION"
				value="96iO9ZeFeHCLRqOxL3MtYryIhKlQNkDqRUuLQf/Zi7yHWcPiygiHxkt+0XsKVZ6p2FLTfr7lwF31AR6aCeO3GHZ4fp5+bwNTp1caH+SL77Jf+XfFfWmGW1P21GNEekSmASNrTJ/cqqrdmOQE3DV06f2a49gpY1d4QuOguNtVRlnPjP3MNJViA9AGjnJSAXDtz0CL99EbrotyKNT2APbw3Zak+VHA9v+czzxlzLwvKO5uYOUSiRGDJINo6WWWXJvh" />
		</div>
		<div>
			<table class="style1">
				<tr>
					<td class="style2">提交地址:</td>
					<td class="style3"><input name="txtUrl" type="text"
						value="http://query.095pay.com/zfapi/order/singlequery"
						id="txtUrl" style="width: 400px;" /></td>
				</tr>
				<tr>
					<td class="style2">商户ID:</td>
					<td class="style3"><input name="p1_mchtid" type="text"
						value="10080" id="p1_mchtid" style="width: 214px;" /></td>
				</tr>
				<tr>
					<td class="style2">商户KEY:</td>
					<td class="style3"><input name="safetyKey" type="text"
						value="<%=request.getAttribute("safetyKey")%>" id="txtKey"
						style="width: 403px;" /></td>
				</tr>
				<tr>
					<td class="style2">加密类型:</td>
					<td class="style3"><select name="p2_signtype" id="p2_signtype">
							<option value="1">MD5</option>
					</select></td>
				</tr>
				<tr>
					<td class="style2">版本号：</td>
					<td class="style3"><input name="p4_version" type="text"
						value="v2.8" id="p4_version" /></td>
				</tr>
				<tr>
					<td class="style2">订单号码:</td>
					<td class="style3"><input name="p3_orderno" type="text"
						id="p3_orderno" style="width: 254px;" /></td>
				</tr>
				<tr>
					<td class="style2">&nbsp;</td>
					<td class="style3"><input type="submit" name="btnSub"
						value="提交支付" id="btnSub" /></td>
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