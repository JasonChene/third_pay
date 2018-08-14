<%@ page language="java" contentType="text/html; charset=UTF-8"
    pageEncoding="UTF-8"%>
<%@ taglib prefix="c" uri="http://java.sun.com/jsp/jstl/core"%>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title></title>
</head>
<body>
	  <form action="<%=request.getAttribute("payUrl")%>" method="post" id="form1">
	
			<input type="hidden" name="merchantCode" value="<%=request.getAttribute("merchantCode") %>" />
			<input type="hidden" name="goodsName" value="<%=request.getAttribute("goodsName") %>" />
			<input type="hidden" name="goodsDescription" value="<%=request.getAttribute("goodsDescription") %>" />
			<input type="hidden" name="notifyUrl" value="<%=request.getAttribute("notifyUrl") %>" />
			<input type="hidden" name="merchantOrderTime" value="<%=request.getAttribute("merchantOrderTime") %>" />
			<input type="hidden" name="latestPayTime" value="<%=request.getAttribute("latestPayTime") %>" />
	    	<input type="hidden" name="outOrderId" value="<%=request.getAttribute("outOrderId") %>" />		
			<input type="hidden"  name="totalAmount" value="<%=request.getAttribute("totalAmount") %>" />
			<input type="hidden"  name="sign" value="<%=request.getAttribute("sign") %>" />
			<input type="hidden"  name="merUrl" value="<%=request.getAttribute("merUrl") %>" />
			<input type="hidden"  name="randomStr" value="<%=request.getAttribute("randomStr") %>" />
			<input type="hidden"  name="payWay" value="<%=request.getAttribute("payWay") %>" />
			<input type="hidden"  name="ext" value="<%=request.getAttribute("ext") %>" />
			
	</form>
	 <script type="text/javascript">
		function sub() {
			document.getElementById("form1").submit();
		}
		// 1000表示1s
	   setTimeout("sub()", 0); 
	</script>
	
</body>
</html>