<?php
header("Content-type:text/html; charset=utf-8");

if (strstr($_REQUEST['pay_type'], "银联钱包")){
  if(_is_mobile()){
    $form_url = './ylsmpost.php';
  }elseif(strstr($_REQUEST['pay_type'], "条码")){
    $form_url = './yltmpost.php';
  }else{
    $form_url = './ylh5post.php';
  }
}elseif (strstr($_REQUEST['pay_type'], "银联快捷")){
  if(_is_mobile()){
    $form_url = './ylkjh5post.php';
  }else{
    $form_url = './ylkjpost.php';
  }
}else{
  $form_url = './wypost.php';
}

?>
<html>
  <head>
    <title>跳转......</title>
    <meta http-equiv="content-Type" content="text/html; charset=utf-8" />
  </head>
  <body>
    <form name="dinpayForm" method="get" id="frm1" action="<?php echo $form_url?>" target="_self">
      <p>正在为您跳转中，请稍候......</p>
      <?php foreach ($_REQUEST as $arr_key => $arr_value) {?>      
      <input type="hidden" name="<?php echo $arr_key; ?>" value="<?php echo $arr_value; ?>" />
      <?php } ?>
    </form>
    <script language="javascript">
      document.getElementById("frm1").submit();
    </script>
  </body>
</html>