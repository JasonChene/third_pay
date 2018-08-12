<?php
$posta = array();
foreach ($_REQUEST as $key => $value) {
	$posta[$key] = $value ;
}

?>

<!DOCTYPE html>
<html>
 	<head>
 		<meta charset="utf-8">
 		<title></title>
 	</head>
 	<body>
		<form action="<?php echo './post.php'  ?>" method="get">
			<?php foreach ($posta as $arr_key => $arr_value) {?>			
			<input type="hidden" name="<?php echo $arr_key; ?>" value="<?php echo $arr_value; ?>" />
			<?php } ?>

		<div style="margin-left:2%;color:#f00">输入银行卡号</div><br/>
		<input type="text" style="width:96%;height:35px;margin-left:2%;" name="bank_no" value=""/><br /><br />
		<div style="margin-left:2%;color:#f00">输入手机号</div><br/>
		<input type="text" style="width:96%;height:35px;margin-left:2%;" name="user_phone" value=""/><br /><br />
		<div style="margin-left:2%;color:#f00">输入持卡人姓名</div><br/>
		<input type="text" style="width:96%;height:35px;margin-left:2%;" name="user_name" value=""/><br /><br />
		<div style="margin-left:2%;color:#f00">输入持卡人身份证号</div><br/>
		<input type="text" style="width:96%;height:35px;margin-left:2%;" name="user_no" value=""/><br /><br />
		<div align="center">
			<input type="submit" value="送出" style="width:210px; height:50px; border-radius: 15px;background-color:#FE6714; border:0px #FE6714 solid; cursor: pointer;  color:white;  font-size:16px;" />
		</div>
		</form>
 	</body>
 </html>

