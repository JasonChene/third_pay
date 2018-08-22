<?php
  function notify_xxx(){
    $getdata = file_get_contents('php://input');
  }
  function jsonw_unescaped_xxxx(){
    json_encode($data, JSON_UNESCAPED_SLASHES);//避免斜线跳脱
  }
  function _is_json($string){//判断JSON
  return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
  }


  function Ret_Trans_Data($data, $data_structure){
    if(_is_json($data)==true && $data_structure=="json"){
      $array=json_decode($data,1);//JSON回传资料
    }elseif($data_structure=="xml"){
      $xml=(array)simplexml_load_string($data) or die("Error: Cannot create object");
      $array=json_decode(json_encode($xml),1);//XML回传资料
    }elseif($data_structure=="xmlCDATA"){
      $xml=(array)simplexml_load_string($data,'SimpleXMLElement',LIBXML_NOCDATA) or die("Error: Cannot create object");
      $array=json_decode(json_encode($xml),1);//XMLCDATA回传资料
    }
    return $array;
  }

  function fix_postdata_url($url, $data){
    $post_url='';
    if(substr($url,-1) == '?' || substr($url,-1) == '/'){ 
      $post_url=substr($url,0,-1)."?".$data;
    }else{
      $post_url=$url."?".$data;
    }
    return $post_url ;
  }

  function QRcodeUrl($code){
    if(strstr($code,"&")){
      $code2=str_replace("&", "aabbcc", $code);//有&换成aabbcc
    }else{
      $code2=$code;
    }
    return $code2;
  }

  function Jump_to_Url($url,$type){
    if(_is_mobile()){
      header("location:". $url);
    }else{
      if(strstr($url,"&")){
        $qrurl = QRcodeUrl($url);
      }else{
        $qrurl = $url;
      }
      header("location:" . '../qrcode/qrcode.php?type='.$type.'&code=' . $qrurl) ;
    }
  }

  function curl_post($postmethod,$url, $data,$formurl=false)
  { #POST访问
    $ch = curl_init();
    if(_is_json()){
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data))
      ); 
    }
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    if($postmethod == 'post'){
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
      curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
      if(is_array($data)){
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
      }else{
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
      }
    }elseif($postmethod == 'get'){
      curl_setopt($ch, CURLOPT_HTTPGET, true);
      $post_url=fix_postdata_url($url, $data);
      curl_setopt($ch, CURLOPT_URL, $post_url);
    }
    if($formurl){
      $tmpInfo = curl_getinfo($ch,CURLINFO_EFFECTIVE_URL);
    }else{
      $tmpInfo = curl_exec($ch);
    }
    if (curl_errno($ch)) {
      echo(curl_errno($ch));
      exit;
    }
    curl_close($ch);
    return $tmpInfo;
  }

  function json_curl_post($url, $data)
  { #POST访问
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data))
    );
    $tmpInfo = curl_exec($ch);
    if (curl_errno($ch)) {
      echo(curl_errno($ch));
      exit;
    }
    curl_close($ch);
    return $tmpInfo;
  }


  function payType_bankname($payment,$pay_type){
  $pay_type_pb=[];
  if(strstr($payment,"wy")){
  $payType = $pay_type . "_wy";
  $bankname = $pay_type . "->网银在线充值";
  }elseif(strstr($payment,"yl")){
  $payType = $pay_type . "_yl";
  $bankname = $pay_type . "->银联钱包在线充值";
  }elseif(strstr($payment,"qq")){
  $payType = $pay_type . "_qq";
  $bankname = $pay_type . "->QQ钱包在线充值";
  }elseif(strstr($payment,"wx")){
  $payType = $pay_type . "_wx";
  $bankname = $pay_type . "->微信在线充值";
  }elseif(strstr($payment,"zfb")){
  $payType = $pay_type . "_zfb";
  $bankname = $pay_type . "->支付宝在线充值";
  }elseif(strstr($payment,"jd")){
  $payType = $pay_type . "_jd";
  $bankname = $pay_type . "->京东钱包在线充值";
  }elseif(strstr($payment,"ylkj")){
  $payType = $pay_type . "_ylkj";
  $bankname = $pay_type . "->银联快捷在线充值";
  }
  return $pay_type_pb;
  }
  function notify_write_log($array){
  write_log("notify");
  foreach ($_REQUEST as $key11 => $value11) {
    write_log($key11."=".$value11);
  }
}
exit;
?>

 <html>
  <head>
    <title>跳转......</title>
    <meta http-equiv="content-Type" content="text/html; charset=utf-8" />
  </head>
  <body>
    <form name="dinpayForm" method="post" id="frm1" action="<?php echo $form_url?>" target="_self">
    <p>正在为您跳转中，请稍候......</p>
    <?php foreach ($parms as $arr_key => $arr_value) {?>      
      <input type="hidden" name="<?php echo $arr_key; ?>" value="<?php echo $arr_value; ?>" />
    <?php } ?>
    </form>
    <script language="javascript">
      document.getElementById("frm1").submit();
    </script>
   </body>
 </html>
