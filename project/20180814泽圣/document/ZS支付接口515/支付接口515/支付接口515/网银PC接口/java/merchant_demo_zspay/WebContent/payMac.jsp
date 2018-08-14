<%@ page language="java" contentType="text/html; charset=UTF-8"
    pageEncoding="UTF-8"%>
<%@ taglib prefix="c" uri="http://java.sun.com/jsp/jstl/core"%>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>支付</title>
</head>
<body>
	  <form action="${payUrl }" method="post" id="form1">
		<c:forEach var="params" items="${paramMap }">
			<input type="hidden" name="${params.key }" value="${params.value }"/>
		</c:forEach> 
		
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