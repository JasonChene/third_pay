<?php
$data = array();
foreach ($_REQUEST as $key => $value) {
  $data[$key] = $value;
}
if ($data['file'] == 'wx') {
    $file_name = './wxpost.php';
}elseif ($data['file'] == 'qq') {
    $file_name = './qqpost.php';
}elseif ($data['file'] == 'zfb') {
    $file_name = './zfbpost.php';
}elseif ($data['file'] == 'wxqqjdbd') {
    $file_name = './wxqqjdbdpost.php';
}elseif ($data['file'] == 'yl') {
    $file_name = './post.php';
}

?>
<center><h1>反扫</h1></center>
<html>
  <head>
      <meta http-equiv="content-Type" content="text/html; charset=utf-8" />
  </head>
  <body>
      <form name="dinpayForm" method="get" id="frm" action="<?php echo $file_name; ?>" target="_blank">
        <center><h3>打开钱包里的条码支付，请输入授权码</h3></center>
        <center><input type="text" name="authCode"></center>
      <?php foreach ($data as $arr_key => $arr_value) { ?>
        <input type="hidden" name="<?php echo $arr_key; ?>" value="<?php echo $arr_value; ?>" />
      <?php } ?>
        <center><input type="submit" name="submit" value="送出" /></center>
      </form>
   </body>
</html>
