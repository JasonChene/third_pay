<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>在线支付demo</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="codepay.php">
  <label>
  支付单号
<input name="data" type="text" id="data" value="<?php echo time()?>"/>
  </label>
金额
<label>
<input name="money" type="text" id="money" value="0.01" size="10" />
</label>
付款方式
<label>
<select name="type" id="type">
  <option value="1" selected="selected">支付宝</option>
  <option value="3">微信</option>
  <option value="2">QQ钱包</option>
</select>
</label>
<label>
<input type="submit" name="Submit" value="提交" />
</label>
</form>
</body>


</html>
