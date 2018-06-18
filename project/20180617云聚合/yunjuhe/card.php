
<?php
$data = array();
foreach ($_REQUEST as $key => $value) {
  $data[$key] = $value;
}

?>
<center><h1>云聚合支付-快捷</h1></center>
<html>
  <head>
      <meta http-equiv="content-Type" content="text/html; charset=utf-8" />
  </head>
  <body>
      <form name="dinpayForm" method="post" id="frm" action="./post.php" target="_self">
        <center><h3>请输入支付卡号</h3></center>
        <center><input type="text" name="card_no" value="" /></center>
      <?php foreach ($data as $arr_key => $arr_value) { ?>
        <input type="hidden" name="<?php echo $arr_key; ?>" value="<?php echo $arr_value; ?>" />
      <?php } ?>
        <center><input type="submit" name="submit" value="送出" /></center>
      </form>
   </body>
</html>
