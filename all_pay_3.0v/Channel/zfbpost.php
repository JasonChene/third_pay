<?php
header("Content-type:text/html; charset=utf-8");
include_once("../moneyfunc.php");
if(_is_mobile()){
  $form_url = './pay/zfbh5post.php';
}elseif(strstr($_REQUEST['pay_type'], "条码")){
  $form_url = './pay/zfbfspost.php';
}else{
  $form_url = './pay/zfbbspost.php';
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