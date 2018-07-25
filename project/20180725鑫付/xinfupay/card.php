<?php
include_once("../moneyfunc.php");
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
		<?php if(strstr($_REQUEST['pay_type'],'银联快捷') && !_is_mobile()){ ?>
		<div style="margin-left:2%;color:#f00">输入总行名称</div><br/>
		<input type="text" style="width:96%;height:35px;margin-left:2%;" name="bankName" value=""/><br /><br />
		<div style="margin-left:2%;color:#f00">选择卡类型</div><br/>
		<select style="width:96%;height:35px;margin-left:2%;" name="cardType">
			<option value="借记卡">借记卡</option>
			<option value="信用卡">信用卡</option>
		</select>
		<?php } ?>
		<div style="margin-left:2%;color:#f00">输入银行卡号</div><br/>
		<input type="text" style="width:96%;height:35px;margin-left:2%;" name="accoutNo" value=""/><br /><br />
		<?php if(strstr($_REQUEST['pay_type'],'银联快捷') && !_is_mobile()){ ?>
		<div style="margin-left:2%;color:#f00">输入开户人姓名</div><br/>
		<input type="text" style="width:96%;height:35px;margin-left:2%;" name="accountName" value=""/><br /><br />
		<div style="margin-left:2%;color:#f00">输入身份证号</div><br/>
		<input type="text" style="width:96%;height:35px;margin-left:2%;" name="idNumber" value=""/><br /><br />
		<div style="margin-left:2%;color:#f00">输入银行预留手机号</div><br/>
		<input type="text" style="width:96%;height:35px;margin-left:2%;" name="Mobile" value=""/><br /><br />
		<?php } ?>
		<div align="center">
			<input type="submit" value="送出" style="width:210px; height:50px; border-radius: 15px;background-color:#FE6714; border:0px #FE6714 solid; cursor: pointer;  color:white;  font-size:16px;" />
		</div>
		</form>
 	</body>
 </html>
