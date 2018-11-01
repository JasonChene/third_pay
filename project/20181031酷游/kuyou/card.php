<?php
$posta = array(
	"S_Name" => $_REQUEST['S_Name'],
	"top_uid" => $_REQUEST['top_uid'],
	"MOAmount" => $_REQUEST['MOAmount'],
	"pay_type" => $_REQUEST['pay_type']
);
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
</head>
<body>
		<form action="<?php echo './post.php' ?>" method="get">
			<?php foreach ($posta as $arr_key => $arr_value) { ?>			
			<input type="hidden" name="<?php echo $arr_key; ?>" value="<?php echo $arr_value; ?>" />
			<?php 
	} ?>


		<div style="margin-left:2%;color:#f00">输入银行卡号</div><br/>
		<input type="text" style="width:96%;height:35px;margin-left:2%;" name="cerdNo" value=""/><br /><br />
		<div align="center">
			<input type="submit" value="送出" style="width:210px; height:50px; border-radius: 15px;background-color:#FE6714; border:0px #FE6714 solid; cursor: pointer;  color:white;  font-size:16px;" />
		</div>
		</form>
</body>
</html>