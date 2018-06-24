<%
        
		Dim ordernumber,orderstatus,paymoney,attach,over,imgurl
		
		ordernumber=request("ordernumber")
		orderstatus=request("orderstatus")
		paymoney=request("paymoney")
		attach=request("attach")
	
	
	if orderstatus=1 then
		'over="支付成功"          '逻辑代码 
		'imgurl="pay-success.png"  
		response.Write "ok" '禁止修改
	    response.End()
	
	elseif orderstatus<>1 then
		'over="支付失败"	
		response.Write "ok" '禁止修改
	    response.End()
	end if
%>