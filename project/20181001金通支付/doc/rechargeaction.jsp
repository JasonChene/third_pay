<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%@ taglib prefix="spring" uri="http://www.springframework.org/tags"%>
<%@ taglib prefix="c" uri="http://java.sun.com/jsp/jstl/core"%>
<%@ taglib prefix="fn" uri="http://java.sun.com/jsp/jstl/functions"%>
<%@ taglib prefix="fmt" uri="http://java.sun.com/jsp/jstl/fmt"%>
<!DOCTYPE HTML>
<html>
<head>
<title>充值</title>
    <script type="text/javascript" src="js/qrcode.min.js"></script>
</head>
<body>
    <input type="hidden" value="${retCode}" id="retCode" name="retCode">
    <input type="hidden" value="${tradeType}" id="tradeType" name="tradeType">
    <input type="hidden" value="${info}" id="info" name="info">
  <c:if test="${retCode == '1' }">提示：${info} 
  </c:if>
  <c:if test="${retCode == '0' }">
	<c:choose> 
	<c:when test="${tradeType == '41' || tradeType == '51' || tradeType == '61'}">  
		<div id="qrcode" style="display: none">
		${info}
		</div>
	</c:when>
	<c:otherwise>
		<div id="qrcode" align="center"></div>
		
	</c:otherwise> 
	</c:choose> 
  </c:if>
</body>
	<script>
	window.onload = function(){  
		var retCode = document.getElementById("retCode").value;  
		var tradeType = document.getElementById("tradeType").value;  
		var info = document.getElementById("info").value;  
		if(retCode !="1" && (tradeType != '41' || tradeType != '51' || tradeType != '61'))
		{
			var qrcode = new QRCode(document.getElementById('qrcode'), {
				width: 256,
				height: 256,
				});
			qrcode.makeCode(info);
         }
	}
	</script>
</html>