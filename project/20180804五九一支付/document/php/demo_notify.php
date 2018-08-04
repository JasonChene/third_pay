<?php
 header("Content-type:text/html;charset=utf-8");
 $key = "4c76d1cdc4db6dfdeb461c9e79029096";          						//商户密钥
 $total_amount=$_request["total_amount"];        			//订单金额
 $out_trade_no=$_request["out_trade_no"];        			//订单号
 $trade_status=$_request["trade_status"];        			//订单状态：成功返回 SUCCESS，失败返回：Fail
 $trade_no=$_request["trade_no"];        					//支付系统订单号
 $extra_return_param=$_request["extra_return_param"];       //备注信息，
 $trade_time=$_request["trade_time"];        				//订单完成时间
 $sign=$_request["sign"];        							//591返回签名数据
 $param="out_trade_no=".$out_trade_no."&total_amount=".$total_amount."&trade_status=".$trade_status;  //拼接$param
 $paramMd5=md5($param.$key);          						//md5后加密之后的$param

if($sign==$paramMd5){
 	if($trade_status== "SUCCESS"){      
        //可在此处增加操作数据库语句，实现自动下发，也可在其他文件导入该php，写入数据库
 		echo "SUCCESS";
 	}
 	else {
		 echo "订单处理失败";
 	} 	
 }else{
 	echo "签名无效，视为无效数据!";
 }
?>