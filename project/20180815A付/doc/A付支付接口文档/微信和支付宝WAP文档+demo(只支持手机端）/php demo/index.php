<?php  
header("Content-type:text/html;charset=utf-8");?><!doctype html>
<html>

<?php  

  function getIP() { 
  if (getenv('HTTP_CLIENT_IP')) { 
    $ip = getenv('HTTP_CLIENT_IP'); 
  } 
  elseif (getenv('HTTP_X_FORWARDED_FOR')) { 
    $ip = getenv('HTTP_X_FORWARDED_FOR'); 
  } 
  elseif (getenv('HTTP_X_FORWARDED')) { 
    $ip = getenv('HTTP_X_FORWARDED'); 
  } 
  elseif (getenv('HTTP_FORWARDED_FOR')) { 
    $ip = getenv('HTTP_FORWARDED_FOR'); 
  } 
  elseif (getenv('HTTP_FORWARDED')) { 
    $ip = getenv('HTTP_FORWARDED'); 
  } 
  else { 
    $ip = $_SERVER['REMOTE_ADDR']; 
  } 
  return $ip; 
  		
  }
  $ip=getIP();  //本地调试可能会导致无法获取ip，一般服务器上配置无该问题
  	
  date_default_timezone_set('PRC');
  $payno=date('YmdHis');
?>

<head>
<meta charset="utf-8">
<title>微信支付宝WAP支付demo</title>
</head>

<body>

<div style="margin: 10px auto; width: 600px; border: 2px dotted #ccc; padding: 10px; line-height: 2">
<h2>微信支付宝wap</h2>
<form method="post" action="index_pay.php">

<table width="100%" border="1" cellspacing="1" cellpadding="0">
  <tbody>
  	<tr>
      <td>payKey</td>
      <td><input type="text" placeholder="" name="payKey" value="a0637c44f78b4026abfe9e918bdee530"></td>
    </tr>
    <tr>
      <td>paySecret</td>
      <td><input type="text" placeholder="" name="paySecret" value="cf3f8406623442fd8b4174c23e7ac521"></td>
    </tr>
    <tr>
      <td>金额</td>
      <td><input type="text" placeholder="金额" name="orderPrice" value="3.03"></td>
    </tr>
    <tr>
      <td>订单号</td>
      <td><input type="text" placeholder="商户支付订单号String" name="outTradeNo" value="p<?=$payno?>"></td>
    </tr>
    <tr>
      <td>产品类型</td>
      <td><input type="text" placeholder="产品类型" name="productType" value="10000201"></td>
    </tr>
    <tr>
      <td>下单时间</td>
       <td><input type="text" placeholder="下单时间" name="orderTime" value="<?=$payno?>"></td>
    </tr>
    <tr>
      <td>支付产品名称</td>
       <td><input type="text" placeholder="产品名称" name="productName" value="test"></td>
    </tr>
    <tr>
      <td>下单IP</td>
       <td><input type="text" placeholder="下单IP" name="orderIp" value="<?=$ip?>"></td>
    </tr>
    <tr>
      <td>页面通知地址</td>
       <td><input type="text" placeholder="页面通知地址" name="returnUrl" value="http://www.baidu.com"></td>
    </tr>
    <tr>
      <td>后台异步通知</td>
       <td><input type="text" placeholder="后台异步通知" name="notifyUrl" value="http://localhost/no.php"></td>
    </tr>
    <tr>
      <td>子商户支付Key</td>
       <td><input type="text" placeholder="子商户支付Key" name="subPayKey" value=""></td>
    </tr>
    <tr>
      <td>备注</td>
       <td><input type="text" placeholder="备注" name="remark" value="ok"></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
       <td><input type="submit"></td>
    </tr>
  </tbody>
</table>
</form>
</div>
</body>
</html>
