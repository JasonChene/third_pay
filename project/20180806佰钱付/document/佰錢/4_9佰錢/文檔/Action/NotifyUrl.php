<?php
require_once '../Config/init.php';

$Succeed         =     $_POST["Succeed"];     
$MD5info         =     $_POST["MD5info"];
$Result          =     $_POST["Result"];
$MerRemark       =     $_POST['MerRemark'];		//自定义信息返回


$DataContentParms =array();
$DataContentParms["MerNo"] = $_POST['MerNo'];
$DataContentParms["BillNo"] = $_POST["BillNo"];
$DataContentParms["Amount"] =  $_POST["Amount"];
$DataContentParms["Succeed"] =  $_POST["Succeed"];
$md5sign = Util::GetMd5str($DataContentParms,$key);


if ($MD5info == $md5sign) {
		if ($Succeed == '88') {
			//此处加入业务处理过程
            exit("SUCCESS");//更新订单状态为其他状态
		}
        
 }  else {
		//验证失败
		echo "ERROR".$Result.$Succeed;
                
 }
          
          
?>

