<?php
header("Content-type:text/html; charset=utf-8");
include_once("../moneyfunc.php");
if(strstr($_REQUEST['pay_type'], "京东钱包")){
  if(strstr($_REQUEST['pay_type'], "反扫")){
    $form_url = './post/jdfspost.php';
  }elseif(_is_mobile()){
    $form_url = './post/jdh5post.php';
  }else{
    $form_url = './post/jdbspost.php';
  }
}elseif(strstr($_REQUEST['pay_type'], "QQ钱包") || strstr($_REQUEST['pay_type'], "qq钱包")){
  if(strstr($_REQUEST['pay_type'], "反扫")){
    $form_url = './post/qqfspost.php';
  }elseif(_is_mobile()){
    $form_url = './post/qqh5post.php';
  }else{
    $form_url = './post/qqbspost.php';
  }
}elseif(strstr($_REQUEST['pay_type'], "百度钱包")){
  if(strstr($_REQUEST['pay_type'], "反扫")){
    $form_url = './post/bdfspost.php';
  }elseif(_is_mobile()){
    $form_url = './post/bdh5post.php';
  }else{
    $form_url = './post/bdbspost.php';
  }
}else{
  if(strstr($_REQUEST['pay_type'], "反扫")){
    $form_url = './post/wxfspost.php';
  }elseif(_is_mobile()){
    $form_url = './post/wxh5post.php';
  }else{
    $form_url = './post/wxbspost.php';
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