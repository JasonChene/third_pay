<?php
$req = json_decode(file_get_contents('php://input'),1);
$req = json_decode($req['data'],1);
function fix_payment($platform){
  $platformstr = '';
  foreach ($platform as $value) {
    $platformstr .= $value.',';
  }
  $paytypestr=substr($platformstr,0,-1);
  return $platformstr;
}

#str_cut
function cutstr($reqstr){
    $strarr=array();
    $str=substr($reqstr,0,-3);
    $strarr2 = explode(">_<", $str);
    $length=count($strarr2);
    for ($i=0; $i < $length ; $i++) {
        $strarr3=explode("^_^", $strarr2[$i]);
        $type = $strarr3[0];
        $strarr[$type] = $strarr3[1];
    }
  return $strarr;
}

function posttype($str){
  $restr = '';
  if (strstr($str,'wxbs')) {
    $restr = '($scan == "wx" && !_is_mobile()) ||';
  }elseif (strstr($str,'wxh5')) {
    $restr = '($scan == "wx" && _is_mobile()) ||';
  }elseif (strstr($str,'wxfs')) {
    $restr = '($scan == "wxfs" ||';
  }elseif (strstr($str,'jdbs')) {
    $restr = '($scan == "jd" && !_is_mobile()) ||';
  }elseif (strstr($str,'jdh5')) {
    $restr = '($scan == "jd" && _is_mobile()) ||';
  }elseif (strstr($str,'jdfs')) {
    $restr = '($scan == "jdfs" ||';
  }elseif (strstr($str,'bdbs')) {
    $restr = '($scan == "bd" && !_is_mobile()) ||';
  }elseif (strstr($str,'bdh5')) {
    $restr = '($scan == "bd" && _is_mobile()) ||';
  }elseif (strstr($str,'bdfs')) {
    $restr = '$scan == "bdfs" ';
  }elseif (strstr($str,'qqbs')) {
    $restr = '($scan == "qq" && !_is_mobile()) ||';
  }elseif (strstr($str,'qqh5')) {
    $restr = '($scan == "qq" && _is_mobile()) ||';
  }elseif (strstr($str,'qqfs')) {
    $restr = '$scan == "qqfs" ';
  }elseif (strstr($str,'zfbbs')) {
    $restr = '($scan == "zfb" && !_is_mobile()) ||';
  }elseif (strstr($str,'zfbh5')) {
    $restr = '($scan == "zfb" && _is_mobile()) ||';
  }elseif (strstr($str,'zfbfs')) {
    $restr = '$scan == "zfbfs" ';
  }elseif (strstr($str,'wylk')) {
    $restr = '($scan == "wy" && !_is_mobile()) ||';
  }elseif (strstr($str,'wyh5')) {
    $restr = '($scan == "wy" && _is_mobile()) ||';
  }elseif (strstr($str,'ylbs')) {
    $restr = '($scan == "yl" && !_is_mobile()) ||';
  }elseif (strstr($str,'ylh5')) {
    $restr = '($scan == "yl" && _is_mobile()) ||';
  }elseif (strstr($str,'ylkj')) {
    $restr = '($scan == "ylkj" && !_is_mobile()) ||';
  }elseif (strstr($str,'ylkjh5')) {
    $restr = '($scan == "ylkj" && _is_mobile()) ||';
  }
  return $restr;
}

echo '<?php'."\n";
echo '#第三方名稱 : '.$req['third_name']."\n";
$platform = fix_payment($req['platform']);    #渠道字串
echo '#支付渠道 :'.$platform."\n";
#汇入档案及基础设定
echo 'include_once("./addsign.php");'."\n";
echo 'include_once("../moneyfunc.php");'."\n";
echo 'include_once("../../../database/mysql.config.php");'."\n\n\n";
echo '$S_Name = $_REQUEST[\'S_Name\'];'."\n";
echo '$top_uid = $_REQUEST[\'top_uid\'];'."\n";
echo '$pay_type =$_REQUEST[\'pay_type\'];'."\n";



#获取第三方资料(非必要不更动)
echo '#获取第三方资料(非必要不更动)'."\n";
echo '$params = array(\':pay_type\' => $pay_type);'."\n";
echo '$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";'."\n";
echo '$stmt = $mydata1_db->prepare($sql);'."\n";
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
//echo '$form_url = \''.$req['form_url']."';\n";
echo '$bank_code = $_REQUEST[\'bank_code\'];'."\n";
echo '$order_no = getOrderNo();'."\n";
echo '$notify_url = $merchant_url;'."\n";
echo '$client_ip = getClientIp();'."\n";
echo '$pr_key = $pay_mkey;//私钥'."\n";
echo '$pu_key = $pay_account;//公钥'."\n";
echo '$order_time = date("YmdHis");'."\n\n\n";
echo '$mymoney = number_format($_REQUEST[\'MOAmount\'], 2, \'.\', \'\');'."\n";
echo '$paytype = "";'."\n";//通道
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




#第三方传值参数设置，将传值参数转成array
function echo_arr($key_name,$array){
  $text =  '"'.$key_name.'" => array('."\n";
  foreach ($array as $arr_key => $arr_value) {
    if (!is_array($arr_value)) {
      (is_null(json_decode($arr_value,1))) ? $arr_value = $arr_value:$arr_value = json_decode($arr_value,1);
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
  $text .= '),'."\n";
  return $text;
}

echo '#第三方传值参数设置'."\n";
echo '$data = array('."\n";
foreach ($req['params'] as $arr_key => $arr_value) {
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
$typekey = array_search('$paytype',$req['params']);

echo '#变更参数设定'."\n";
$form_urlarr = cutstr($req['form_url']);
$paytypearr = cutstr($req['paytype']);
if (strstr($platform, "jd")) {
echo 'if (strstr($_REQUEST["pay_type"], "京东钱包")) {'."\n";
echo '  $scan = "jd";'."\n";
echo '  $payType = $pay_type."->京东钱包在线充值";'."\n";
echo '  $bankname = $pay_type."_jd";'."\n";
echo '  $data["sign"]["str_arr"]["'.$typekey.'"] = "'.$paytypearr['jdbs'].'";'."\n";
echo '  $data["'.$typekey.'"] = "'.$paytypearr['jdbs'].'";'."\n";
  if(strstr($platform, "jdh5")){
echo '  if(_is_mobile()){'."\n";
echo '    $data["sign"]["str_arr"]["'.$typekey.'"] = "'.$paytypearr['jdh5'].'";'."\n";
echo '    $data["'.$typekey.'"] = "'.$paytypearr['jdh5'].'";'."\n";
echo '  }'."\n";
  }
echo '}'."\n";
}
if (strstr($platform, "bd")) {
  if(strstr($platform, "jd")){
    echo 'elseif';
  }else{
    echo 'if';
  }
echo '(strstr($_REQUEST["pay_type"], "百度钱包")) {'."\n";
echo '  $scan = "bd";'."\n";
echo '  $payType = $pay_type."->百度钱包在线充值";'."\n";
echo '  $bankname = $pay_type."_bd";'."\n";
echo '  $data["sign"]["str_arr"]["'.$typekey.'"] = "'.$paytypearr['bdbs'].'";'."\n";
echo '  $data["'.$typekey.'"] = "'.$paytypearr['bdbs'].'";'."\n";
  if(strstr($platform, "bdh5")){
echo '  if(_is_mobile()){'."\n";
echo '    $data["sign"]["str_arr"]["'.$typekey.'"] = "'.$paytypearr['bdh5'].'";'."\n";
echo '    $data["'.$typekey.'"] = "'.$paytypearr['bdh5'].'";'."\n";
echo '  }'."\n";
  }
echo '}'."\n";
}
if (strstr($platform, "qq")){
  if( strstr($platform, "jd") || strstr($platform, "bd") ){
    echo 'elseif';
  }else{
    echo 'if';
  }
echo '(strstr($_REQUEST["pay_type"], "QQ钱包")) {'."\n";
echo '  $scan = "qq";'."\n";
echo '  $payType = $pay_type."->QQ钱包在线充值";'."\n";
echo '  $bankname = $pay_type."_qq";'."\n";
echo '  $data["sign"]["str_arr"]["'.$typekey.'"] = "'.$paytypearr['qqbs'].'";'."\n";
echo '  $data["'.$typekey.'"] = "'.$paytypearr['qqbs'].'";'."\n";
  if(strstr($platform, "qqh5")){
echo '  if(_is_mobile()){'."\n";
echo '    $data["sign"]["str_arr"]["'.$typekey.'"] = "'.$paytypearr['qqh5'].'";'."\n";
echo '    $data["'.$typekey.'"] = "'.$paytypearr['qqh5'].'";'."\n";
echo '  }'."\n";
  }
echo '}'."\n";
}
if (strstr($platform, "wx")){
  if( strstr($platform, "jd") || strstr($platform, "bd") ||  strstr($platform, "qq")){
    echo 'else{'."\n";
  }
echo '  $scan = "wx";'."\n";
echo '  $payType = $pay_type."->微信在线充值";'."\n";
echo '  $bankname = $pay_type."_wx";'."\n";
echo '  $data["sign"]["str_arr"]["'.$typekey.'"] = "'.$paytypearr['wxbs'].'";'."\n";
echo '  $data["'.$typekey.'"] = "'.$paytypearr['wxbs'].'";'."\n";
  if(strstr($platform, "wxh5")){
echo '  if(_is_mobile()){'."\n";
echo '    $data["sign"]["str_arr"]["'.$typekey.'"] = "'.$paytypearr['wxh5'].'";'."\n";
echo '    $data["'.$typekey.'"] = "'.$paytypearr['wxh5'].'";'."\n";
echo '  }'."\n";
  }
  if( strstr($platform, "jd") || strstr($platform, "bd") ||  strstr($platform, "qq")){
      echo '}'."\n";
  }
}
if(strstr($platform, "ylbs") || strstr($platform, "ylh5")){
echo 'if (strstr($_REQUEST["pay_type"], "银联钱包")) {'."\n";
echo '  $scan = "yl";'."\n";
echo '  $payType = $pay_type."->银联钱包在线充值";'."\n";
echo '  $bankname = $pay_type."_yl";'."\n";
echo '  $data["sign"]["str_arr"]["'.$typekey.'"] = "'.$paytypearr['ylbs'].'";'."\n";
echo '  $data["'.$typekey.'"] = "'.$paytypearr['ylbs'].'";'."\n";
  if(strstr($platform, "ylh5")){
echo '  if(_is_mobile()){'."\n";
echo '    $data["sign"]["str_arr"]["'.$typekey.'"] = "'.$paytypearr['ylh5'].'";'."\n";
echo '    $data["'.$typekey.'"] = "'.$paytypearr['ylh5'].'";'."\n";
echo '  }'."\n";
  }
echo '}'."\n";
}
if (strstr($platform, "ylkj") || strstr($platform, "ylkjh5")){
  if( strstr($platform, "ylbs") || strstr($platform, "ylh5") ){
    echo 'elseif';
  }else{
    echo 'if';
  }
echo '(strstr($_REQUEST["pay_type"], "银联快捷")) {'."\n";
echo '  $scan = "ylkj";'."\n";
echo '  $payType = $pay_type."->银联快捷在线充值";'."\n";
echo '  $bankname = $pay_type."_ylkj";'."\n";
echo '  $data["sign"]["str_arr"]["'.$typekey.'"] = "'.$paytypearr['ylkj'].'";'."\n";
echo '  $data["'.$typekey.'"] = "'.$paytypearr['ylkj'].'";'."\n";
  if(strstr($platform, "ylkjh5")){
echo '  if(_is_mobile()){'."\n";
echo '    $data["sign"]["str_arr"]["'.$typekey.'"] = "'.$paytypearr['ylkjh5'].'";'."\n";
echo '    $data["'.$typekey.'"] = "'.$paytypearr['ylkjh5'].'";'."\n";
echo '  }'."\n";
  }
echo '}'."\n";
}
if (strstr($platform, "wy")){
  if( strstr($platform, "wy") || strstr($platform, "yl") ){
    echo 'else';
  }else{
    echo 'if';
  }
echo '{'."\n";
echo '  $scan = "wy";'."\n";
echo '  $payType = $pay_type."->网银在线充值";'."\n";
echo '  $bankname = $pay_type."_wy";'."\n";
echo '  $data["sign"]["str_arr"]["'.$typekey.'"] = "'.$paytypearr['wylk'].'";'."\n";
echo '  $data["'.$typekey.'"] = "'.$paytypearr['wylk'].'";'."\n";
  if(strstr($platform, "wyh5")){
echo '  if(_is_mobile()){'."\n";
echo '    $data["sign"]["str_arr"]["'.$typekey.'"] = "'.$paytypearr['wyh5'].'";'."\n";
echo '    $data["'.$typekey.'"] = "'.$paytypearr['wyh5'].'";'."\n";
echo '  }'."\n";
  }
echo '}'."\n";
}



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
echo 'foreach ($data as $arr_key => $arr_value) {'."\n";
echo '  if (is_array($arr_value)) {'."\n";
echo '    $data[$arr_key] = sign_text($arr_value);'."\n";
echo '  }'."\n";
echo '}'."\n";




#curl获取响应值
$typepostarr = cutstr($req['postmethod']);
$postcurl='';
$getcurl='';
$headerpost='';
$headerget='';
foreach ($typepostarr as $key => $value) {
  if (strstr($value, "CURL-POST")) {
      $postcurl .= posttype($key);
  }elseif (strstr($value, "CURL-GET")) {
      $getcurl .= posttype($key);
  }elseif (strstr($value, "HEADER-POST")) {
      $headerpost .= posttype($key);
  }elseif (strstr($value, "HEADER-GET")) {
      $headerget .= posttype($key);
  }
}

$postcurl=substr($postcurl,0,-2);
$getcurl=substr($getcurl,0,-2);
$headerpost=substr($headerpost,0,-2);
$headerget=substr($headerget,0,-2);


#抓取响应值方式
function res_structure($res_structure){
    if($res_structure=="JSON"){
        echo '  $row = json_decode($res,1);'."\n";
    }elseif($res_structure=="XML"){
        echo '  $xml=(array)simplexml_load_string($res) or die("Error: Cannot create object");'."\n";
        echo '  $row=json_decode(json_encode($xml),1);//XML回传资料'."\n";
    }elseif($res_structure=="xmlCDATA"){
        echo '  $xml=(array)simplexml_load_string($res,\'SimpleXMLElement\',LIBXML_NOCDATA) or die("Error: Cannot create object");'."\n";
        echo '  $row=json_decode(json_encode($xml),1);//XMLCDATA回传资料'."\n";
    }
}

####################################################################################################
echo '#curl获取响应值'."\n";
if (!empty($postcurl) || !empty($getcurl)) {
    if ($req['req_structure'] == 'JSON') {
        if (!empty($postcurl)){
            echo 'if('.$postcurl.'){'."\n";
            echo '  $res = curl_post($form_url,json_encode($data),"CURL-POST");'."\n";
            res_structure($req['res_structure']);
        }
        if (!empty($getcurl)) {
            echo 'if('.$getcurl.'){'."\n";
            echo '  $res = curl_post($form_url,json_encode($data),"CURL-GET");'."\n";
            res_structure($req['res_structure']);
        }
    }else {
        if (!empty($postcurl)){
            echo 'if('.$postcurl.'){'."\n";
            echo '  $res = curl_post($form_url,http_build_query($data),"CURL-POST");'."\n";
            res_structure($req['res_structure']);
        }
        if (!empty($getcurl)) {
            echo 'if('.$getcurl.'){'."\n";
            echo '  $res = curl_post($form_url,http_build_query($data),"CURL-GET");'."\n";
            res_structure($req['res_structure']);
        }
    }



    #响应值层数切割
    function response_url($first_key,$second_key,$response_level){
        $first_key = cutstr($first_key);
        $second_key = cutstr($second_key);
        $response_level = cutstr($response_level);
        $response_url = array();
        foreach ($response_level as $res_level_key => $res_level_val) {
            $response_url[$res_level_key][0] = $res_level_val;
            foreach ($first_key as $first_key_key => $first_key_val) {
                if ($first_key_key == $res_level_key) {
                    $response_url[$res_level_key][1] = $first_key_val;
                }
            }
            foreach ($second_key as $second_key_key => $second_key_val) {
                if ($second_key_key == $res_level_key) {
                    $response_url[$res_level_key][2] = $second_key_val;
                }
            }
        }
        return $response_url;
    }
    #响应值层数切割 转换成echo
    function response_url_echo($res_echo_str,$response_url){
        foreach ($response_url as $arr_key => $arr_val) {
            $res_echo_str .= '  if ('.substr(posttype($arr_key),0,-2).') {'."\n";
            if ($arr_val[0] == '0') {
                $res_echo_str .= '      $jumpurl = $row;'."\n";
                $res_echo_str .= '  }'."\n";
            }elseif ($arr_val[0] == '1') {
                $res_echo_str .= '      $jumpurl = $row';
                $res_echo_str .= '[\''.$arr_val[1].'\']';
                $res_echo_str .= ';'."\n";
                $res_echo_str .= '  }'."\n";
            }else {
                $res_echo_str .= '      $jumpurl = $row';
                $res_echo_str .= '[\''.$arr_val[1].'\']';
                $res_echo_str .= '[\''.$arr_val[2].'\']';
                $res_echo_str .= ';'."\n";
                $res_echo_str .= '  }'."\n";
            }
        }
    }
    #响应值层数切割 转换成echo
    function response_url_echo($res_echo_str,$response_url){
        foreach ($response_url as $arr_key => $arr_val) {
            $res_echo_str .= '  if ('.substr(posttype($arr_key),0,-2).') {'."\n";
            if ($arr_val[0] == '0') {
                $res_echo_str .= '      $jumpurl = $row;'."\n";
            }elseif ($arr_val[0] == '1') {
                $res_echo_str .= '      $jumpurl = $row';
                $res_echo_str .= '[\''.$arr_val[1].'\']';
                $res_echo_str .= ';'."\n";
            }else {
                $res_echo_str .= '      $jumpurl = $row';
                $res_echo_str .= '[\''.$arr_val[1].'\']';
                $res_echo_str .= '[\''.$arr_val[2].'\']';
                $res_echo_str .= ';'."\n";
            }
            if (strstr(substr(posttype($arr_key),0,-2),'_is_mobile()')) {
                $res_echo_str .= '      $jumpurl = \'../qrcode/qrcode.php?type='.'.$scan.'.'&code=\' . QRcodeUrl($jumpurl);'."\n";
            }
            $res_echo_str .= '  }'."\n";
        }
        return $res_echo_str;
    }
    #跳转qrcode
    echo '#跳转qrcode'."\n";
    echo 'if ($row[\''.$req['Success_key'].'\'] == \''.$req['Success_value'].'\') {'."\n";
    $response_url = response_url($req['first_key'],$req['second_key'],$req['response_level']);
    $res_echo_str = '';
    $res_echo_str = response_url_echo($res_echo_str,$response_url);
    echo $res_echo_str;
    echo '  }else{'."\n";
    echo '    echo "错误码：".$row[\''.$req['Error_No'].'\']."错误讯息：".$row[\''.$req['Error_Msg'].'\'];'."\n";
    echo '    echo "<pre>";'."\n";
    echo '    var_dump("请求报文：");'."\n";
    echo '    var_dump($data);'."\n";
    echo '    var_dump("响应报文：");'."\n";
    echo '    var_dump($res);'."\n";
    echo '    exit();'."\n";
    echo '  }'."\n";
    echo '}'."\n";
}
####################################################################################################
if (!empty($headerpost) || !empty($headerget)) {
    echo 'if('.$headerpost.$headerget.'){'."\n\n";
    echo '  $form_data=$data;'."\n";
    echo '  $jumpurl=$form_url;'."\n";
    echo '}'."\n";
}




#html跳转
echo '?>'."\n";
echo '<html>'."\n";
echo '  <head>'."\n";
echo '      <title>跳转......</title>'."\n";
echo '      <meta http-equiv="content-Type" content="text/html; charset=utf-8" />'."\n";
echo '  </head>'."\n";
echo '  <body>'."\n";
if (!empty($postcurl) || !empty($headerpost)) {
    echo '<?php if('.$postcurl.$headerpost.'){ ?>'."\n\n";
    echo '      <form name="dinpayForm" method="post" id="frm1" action="<?php echo $jumpurl?>" target="_self">'."\n";
    echo '<?php } ?>'."\n";
}elseif (!empty($getcurl) || !empty($headerget)) {
    echo '<?php if('.$getcurl.$headerget.'){ ?>'."\n\n";
    echo '      <form name="dinpayForm" method="get" id="frm1" action="<?php echo $jumpurl?>" target="_self">'."\n";
    echo '<?php } ?>'."\n";
}
echo '          <p>正在为您跳转中，请稍候......</p>'."\n";
echo '          <?php'."\n";
echo '          if(isset($form_data)){'."\n";
echo '              foreach ($form_data as $arr_key => $arr_value) {'."\n";
echo '          ?>'."\n";
echo '              <input type="hidden" name="<?php echo $arr_key; ?>" value="<?php echo $arr_value; ?>">'."\n";
echo '          <?php }} ?>'."\n";
echo '      </form>'."\n";
echo '      <script language="javascript">'."\n";
echo '          document.getElementById("frm1").submit();'."\n";
echo '      </script>'."\n";
echo '   </body>'."\n";
echo '</html>'."\n";






 ?>
