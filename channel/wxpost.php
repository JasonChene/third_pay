<?php
header("Content-type:text/html; charset=utf-8");

if(strstr($_REQUEST['pay_type'], "京东钱包")){
  if(strstr($_REQUEST['pay_type'], "条码")){
    $form_url = './jdtmpost.php';
  }elseif(_is_mobile()){
    $form_url = './jdh5post.php';
  }else{
    $form_url = './jdsmpost.php';
  }
}elseif(strstr($_REQUEST['pay_type'], "百度钱包")){
  if(strstr($_REQUEST['pay_type'], "条码")){
    $form_url = './bdtmpost.php';
  }elseif(_is_mobile()){
    $form_url = './bdh5post.php';
  }else{
    $form_url = './bdsmpost.php';
  }
}else{
  if(strstr($_REQUEST['pay_type'], "条码")){
    $form_url = './wxtmpost.php';
  }elseif(_is_mobile()){
    $form_url = './wxh5post.php';
  }else{
    $form_url = './wxsmpost.php';
  }
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