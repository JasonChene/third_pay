<?php
header("Content-type: text/html; charset=utf-8");
require_once 'src/Provider.php';
$rsa = new \Ryanc\RSA\Provider('rsa.config.php');
$sign = $_POST['sign'];
$result = $rsa->verify($_POST, $sign);
if($result){
  //这里可以添加你成功之后的业务
  //echo "ok";   //验签成功.
}
printf("success");
