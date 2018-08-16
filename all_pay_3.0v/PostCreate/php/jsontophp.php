<?php
$req = json_decode(file_get_contents('php://input'),1);
$key = json_decode($req['key'], true);
$req = json_decode($req['data'], true);


function tranjsonstr($code)
{
  $code2 = str_replace("\"{", "{", $code);//有&换成aabbcc
    $code3 = str_replace("}\"", "}", $code2);//有&换成aabbcc
  return $code3;
}

function fix_payment($payment){
    if(strstr($payment,"qq")){
      $payment2['type']='qq';
    }elseif(strstr($payment,"wx")){
      $payment2['type']='wx';
    }elseif(strstr($payment,"zfb")){
      $payment2['type']='zfb';
    }elseif(strstr($payment,"jd")){
      $payment2['type']='jd';
    }elseif(strstr($payment,"ylkj")){
      $payment2['type']='ylkj';
    }elseif(strstr($payment,"wy")){
      $payment2['type']='wy';
    }elseif(strstr($payment,"yl")){
      $payment2['type']='yl';
    }else{
      $payment2['type']= $payment;
    }
    return $payment2;
  }


function payType_bankname($payment){
    $pay_type_pb=array();
    if(strstr($payment,"wy")){
      $pay_type_pb['payType'] = "_wy";
      $pay_type_pb['bankname'] = "->网银在线充值";
    }elseif(strstr($payment,"qq")){
      $pay_type_pb['payType'] = "_qq";
      $pay_type_pb['bankname'] = "->QQ钱包在线充值";
    }elseif(strstr($payment,"wx")){
      $pay_type_pb['payType'] = "_wx";
      $pay_type_pb['bankname'] = "->微信在线充值";
    }elseif(strstr($payment,"zfb")){
      $pay_type_pb['payType'] = "_zfb";
      $pay_type_pb['bankname'] = "->支付宝在线充值";
    }elseif(strstr($payment,"jd")){
      $pay_type_pb['payType'] = "_jd";
      $pay_type_pb['bankname'] = "->京东钱包在线充值";
    }elseif(strstr($payment,"ylkj")){
      $pay_type_pb['payType'] = "_ylkj";
      $pay_type_pb['bankname'] = "->银联快捷在线充值";
    }elseif(strstr($payment,"yl")){
      $pay_type_pb['payType'] = "_yl";
      $pay_type_pb['bankname'] = "->银联钱包在线充值";
    }elseif(strstr($payment,"bd")){
      $pay_type_pb['payType'] = "_bd";
      $pay_type_pb['bankname'] = "->百度钱包在线充值";
    }else{
      $pay_type_pb['payType'] = "_xx";
      $pay_type_pb['bankname'] = "->在线充值";
    }
    return $pay_type_pb;
  }





echo '<?php'."\n";
echo 'header("Content-type:text/html; charset=utf-8");'."\n";
echo '#第三方名稱 : '.$req['third_name']."\n";
$payment = fix_payment($req['payment']);
echo '#支付方式 : '. $payment['type'] .";\n";

echo 'include_once("./addsign.php");'."\n";
echo 'include_once("../moneyfunc.php");'."\n";
if ($key == 1) {
  echo 'include_once("../../../database/mysql.config.php");'."\n\n\n";
}else {
  echo 'include_once("../../../database/mysql.php");'."\n\n\n";
}
echo '$S_Name = $_REQUEST[\'S_Name\'];'."\n";
echo '$top_uid = $_REQUEST[\'top_uid\'];'."\n";
echo '$pay_type =$_REQUEST[\'pay_type\'];'."\n";


// #跳转qrcode.php网址调试
// echo '#跳转qrcode.php网址调试'."\n";
// echo 'function QRcodeUrl($code){'."\n";
// echo '  if(strstr($code,"&")){'."\n";
// echo '    $code2=str_replace("&", "aabbcc", $code);//有&换成aabbcc'."\n";
// echo '  }else{'."\n";
// echo '    $code2=$code;'."\n";
// echo '  }'."\n";
// echo '  return $code2;'."\n";
// echo '}'."\n\n\n";


#获取第三方资料(非必要不更动)
echo '#获取第三方资料(非必要不更动)'."\n";
echo '$params = array(\':pay_type\' => $pay_type);'."\n";
echo '$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";'."\n";
if ($key == 1) {
  echo '$stmt = $mydata1_db->prepare($sql);'."\n";
}else {
  echo '$stmt = $mysqlLink->sqlLink("read1")->prepare($sql);'."\n";
}
echo '$stmt->execute($params);'."\n";
echo '$row = $stmt->fetch();'."\n";
echo '$pay_mid = $row[\'mer_id\'];'."\n";
echo '$pay_mkey = $row[\'mer_key\'];'."\n";
echo '$pay_account = $row[\'mer_account\'];'."\n";
echo '$return_url = $row[\'pay_domain\'] . $row[\'wx_returnUrl\'];//同步'."\n";
echo '$merchant_url = $row[\'pay_domain\'] . $row[\'wx_synUrl\'];//异步'."\n";
echo 'if ($pay_mid == "" || $pay_mkey == "") {'."\n";
echo '  echo "非法提交参数";'."\n";
echo '  exit;'."\n";
echo '}'."\n\n\n";

#固定参数设置
echo '#固定参数设置'."\n";
echo '$form_url = \''.$req['form_url']."';\n";
echo '$bank_code = $_REQUEST[\'bank_code\'];'."\n";
echo '$order_no = getOrderNo();'."\n";
echo '$notify_url = $merchant_url;'."\n";
echo '$client_ip = getClientIp();'."\n";
echo '$pr_key = $pay_mkey;//私钥'."\n";
echo '$pu_key = $pay_account;//公钥'."\n";
echo '$order_time = date("YmdHis");'."\n\n\n";
echo '$mymoney = number_format($_REQUEST[\'MOAmount\'], 2, \'.\', \'\');'."\n";
if($req['amount_unit'] == '2'){
  if($req['decimal']=="2"){
    echo '$MOAmount = number_format($_REQUEST[\'MOAmount\']*100, 2, \'.\', \'\');'."\n";
    //订单支付金额,小数点两位
  }elseif($req['decimal']=="0"){
    echo '$MOAmount = number_format($_REQUEST[\'MOAmount\']*100, 0, \'.\', \'\');'."\n";
  }
}else{
  if($req['decimal']=="2"){
    echo '$MOAmount = number_format($_REQUEST[\'MOAmount\'], 2, \'.\', \'\');'."\n";
  //订单支付金额,小数点两位
  }elseif($req['decimal']=="0"){
    echo '$MOAmount = number_format($_REQUEST[\'MOAmount\'], 0, \'.\', \'\');'."\n";
  }
}



#第三方传值参数设置
function echo_arr($key_name,$array){
  $text =  '"'.$key_name.'" => array('."\n";
  if ($key_name == 'str_arr') {
    foreach ($array as $obj) {
      foreach ($obj as $arr_key => $arr_value) {
        if (!is_array($arr_value) && !is_numeric($arr_value)) {
          $arr_value = (is_null(json_decode($arr_value,1))) ? $arr_value:json_decode($arr_value,1);
        }
        if (is_array($arr_value)) {
          $arr_value = echo_arr($arr_key,$arr_value);
          $text .= $arr_value;
        } else {
          if (substr($arr_value,0,1) == '$') {
            $text .= '"'.$arr_key.'" => '.$arr_value.','."\n";
          } else {
            $text .= '"'.$arr_key.'" => "'.$arr_value.'",'."\n";
          }
        }
      }
    }
  }else {
    foreach ($array as $arr_key => $arr_value) {
      if (!is_array($arr_value) && !is_numeric($arr_value)) {
        $arr_value = (is_null(json_decode($arr_value,1))) ? $arr_value:json_decode($arr_value,1);
      }
      if (is_array($arr_value)) {
        $arr_value = echo_arr($arr_key,$arr_value);
        $text .= $arr_value;
      } else {
        if (substr($arr_value,0,1) == '$') {
          $text .= '"'.$arr_key.'" => '.$arr_value.','."\n";
        } else {
          $text .= '"'.$arr_key.'" => "'.$arr_value.'",'."\n";
        }
      }
    }
  }
  $text .= '),'."\n";
  return $text;
}

echo '#第三方传值参数设置'."\n";
echo '$data = array('."\n";
foreach ($req['params'] as $arr_key => $arr_value) {
  if (!is_array($arr_value) && !is_numeric($arr_value)) {
    $arr_value = tranjsonstr($arr_value);
    $arr_value = (is_null(json_decode($arr_value,1))) ? $arr_value:json_decode($arr_value,1);
  }
  if (!is_array($arr_value)) {
    if (substr($arr_value,0,1) == '$') {
      echo '"'.$arr_key.'" => '.$arr_value.','."\n";
    } else {
      echo '"'.$arr_key.'" => \''.$arr_value.'\','."\n";
    }
  } else {
    echo echo_arr($arr_key,$arr_value);
  }
}
echo ');'."\n";




#变更参数设定
echo '#变更参数设定'."\n";
$pay_type_pb = payType_bankname($req['payment']);
echo '$payType = $pay_type."'.$pay_type_pb['payType'].'";'."\n";
echo '$bankname = $pay_type."'.$pay_type_pb['bankname'].'";'."\n";


#新增至资料库，確認訂單有無重複， function在 moneyfunc.php裡(非必要不更动)
echo '#新增至资料库，確認訂單有無重複， function在 moneyfunc.php裡(非必要不更动)'."\n";
echo '$result_insert = insert_online_order($S_Name , $order_no , $mymoney,$bankname,$payType,$top_uid);'."\n";
echo 'if ($result_insert == -1){'."\n";
echo '  echo "会员信息不存在，无法支付，请重新登录网站进行支付！";'."\n";
echo '  exit;'."\n";
echo '} else if ($result_insert == -2){'."\n";
echo '  echo "订单号已存在，请返回支付页面重新支付";'."\n";
echo '  exit;'."\n";
echo '}'."\n\n\n";


#签名排列，可自行组字串或使用http_build_query($array)
echo '#签名排列，可自行组字串或使用http_build_query($array)'."\n";
if (strstr($req['postmethod'],"HEADER")){
    echo 'foreach ($data as $arr_key => $arr_value) {'."\n";
    echo '  if (is_array($arr_value)) {'."\n";
    echo '    $data[$arr_key] = sign_text($arr_value);'."\n";
    echo '  }'."\n";
    echo '}'."\n";
    echo '$form_data = $data;'."\n";
    echo '$jumpurl = $form_url;'."\n";
}else{
    echo 'foreach ($data as $arr_key => $arr_value) {'."\n";
    echo '  if (is_array($arr_value)) {'."\n";
    echo '    $data[$arr_key] = sign_text($arr_value);'."\n";
    echo '  }'."\n";
    echo '}'."\n";
    if ($req['req_structure'] == 'JSON') {
      echo '$data_json = json_encode($data,JSON_UNESCAPED_SLASHES);'."\n";
    }
    #curl获取响应值
    echo '#curl获取响应值'."\n";
    if ($req['req_structure'] == 'JSON') {
      echo '$res = curl_post($form_url,$data_json,"'.$req["req_structure"].'-'.$req["postmethod"].'");'."\n";
    }else {
      echo '$res = curl_post($form_url,http_build_query($data),"'.$req["postmethod"].'");'."\n";
    }
    if($req['res_structure']=="JSON"){
        echo '$res = json_decode($res,1);'."\n";
    }elseif($req['res_structure']=="XML"){
        echo '$xml=(array)simplexml_load_string($data) or die("Error: Cannot create object");'."\n";
        echo '$res=json_decode(json_encode($xml),1);//XML回传资料'."\n";
    }elseif($req['res_structure']=="xmlCDATA"){
        echo '$xml=(array)simplexml_load_string($data,\'SimpleXMLElement\',LIBXML_NOCDATA) or die("Error: Cannot create object");'."\n";
        echo '$res=json_decode(json_encode($xml),1);//XMLCDATA回传资料'."\n";
    }
    #跳转qrcode
    echo '#跳转qrcode'."\n";
    $response_key = '$url = $res';
    for ($i=0; $i < intval($req['response_key'][0]); $i++) {
      $response_key .= '[\''.$req['response_key'][1+$i].'\']';
    }
    $response_key .= ';';
    echo $response_key."\n";
    echo 'if ($res[\''.$req['Success_key'].'\'] == \''.$req['Success_value'].'\') {'."\n";
    if (strstr($req['payment'],"bs")) {
      echo '    $qrurl = QRcodeUrl($url);'."\n";
      echo '    $jumpurl = \'../qrcode/qrcode.php?type='.$payment['type'].'&code=\' . $qrurl;'."\n";
    }else {
      echo '    $jumpurl = $url;'."\n";
    }
    echo '}else{'."\n";
    echo '  echo "错误码：".$res[\''.$req['Error_No'].'\']."错误讯息：".$res[\''.$req['Error_Msg'].'\'];'."\n";
    echo '  exit();'."\n";
    echo '}'."\n";
}

  


echo '?>'."\n";


echo '<html>'."\n";
echo '  <head>'."\n";
echo '      <title>跳转......</title>'."\n";
echo '      <meta http-equiv="content-Type" content="text/html; charset=utf-8" />'."\n";
echo '  </head>'."\n";
echo '  <body>'."\n";
echo '      <form name="dinpayForm" method="post" id="frm1" action="<?php echo $jumpurl?>" target="_self">'."\n";
echo '          <p>正在为您跳转中，请稍候......</p>'."\n";
echo '          <?php'."\n";
echo '          if(isset($form_data)){'."\n";
echo '              foreach ($data as $arr_key => $arr_value) {'."\n";
echo '          ?>'."\n";
echo '              <input type="hidden" name="<?php echo $arr_key; ?>" value="<?php echo $arr_value; ?>" />'."\n";
echo '          <?php }} ?>'."\n";
echo '      </form>'."\n";
echo '      <script language="javascript">'."\n";
echo '          document.getElementById("frm1").submit();'."\n";
echo '      </script>'."\n";
echo '   </body>'."\n";
echo '</html>'."\n";

?>