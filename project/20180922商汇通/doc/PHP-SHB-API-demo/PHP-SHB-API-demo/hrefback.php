<?php
include_once("config.php");


                $orderid  = trim($_GET['orderid']);
		$opstate        = trim($_GET['opstate']);
		$ovalue         = trim($_GET['ovalue']);
		$attach         = trim($_GET['attach']);
		$sign           = trim($_GET['sign']);
		
		$sign_text	= "orderid=".$orderid."&opstate=".$opstate."&ovalue=".$ovalue;
		$sign_md5 = md5($sign_text.$key);

        if (sign == localsign)
        { 
           if (opstate == "0")
           {
			    //�ɹ��߼������ֽ׶�ֻ���ͳɹ��ĵ���
            } 
        }
      else
	  {
		   //���ܴ���
	  }
       
?>