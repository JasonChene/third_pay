
<?php
$req = json_decode(file_get_contents('php://input'),1);
$key = json_decode($req['key'], true);
$req = json_decode($req['data'], true);

echo 'header("content-Type: text/html; charset=UTF-8");'."\n";
echo '<?php'."\n";
if ($key == 1) {
    echo 'include_once("../../../database/mysql.config.php");'."\n";
}else {
    echo 'include_once("../../../database/mysql.php");'."\n";
}

echo 'include_once("../moneyfunc.php");'."\n"."\n"."\n";
echo '#'.$req['name']."\n";
echo 'write_log("notify")'."\n";
if ($req['method'] == 'POST') {
    echo "write_log(POST方法);"."\n";
    echo 'foreach ($_POST as $key => $value) {'."\n";
    echo '$data[$key] = $value;'."\n";
    echo 'write_log($key."=".$value);'."\n";
    echo '}'."\n\n";
}elseif ($req['method'] == 'GET') {
    echo "write_log(GET方法);"."\n";
    echo 'foreach ($_GET as $key => $value) {'."\n";
    echo '$data[$key] = $value;'."\n";
    echo 'write_log($key."=".$value);'."\n";
    echo '}'."\n\n";
}elseif ($req['method'] == 'REQUEST') {
    echo "write_log(REQUEST方法);"."\n";
    echo 'foreach ($_REQUEST as $key => $value) {'."\n";
    echo '$data[$key] = $value;'."\n";
    echo 'write_log($key."=".$value);'."\n";
    echo '}'."\n\n";
}elseif ($req['method'] == 'FILE_JSON') {
    echo "write_log(input方法);";
    echo '$input_data=file_get_contents("php://input");'."\n";
    echo 'write_log($input_data);'."\n";
    echo '$res=json_decode($input_data,1);//json回传资料'."\n";
    echo 'foreach ($res as $key => $value) {'."\n";
    echo '$data[$key] = $value;'."\n";
    echo 'write_log($key."=".$value);'."\n";
    echo '}'."\n\n";
}elseif ($req['method'] == 'FILE_XML') {
    echo "write_log(input方法);";
    echo '$input_data=file_get_contents("php://input");'."\n";
    echo 'write_log($input_data);'."\n";
    echo '$xml=(array)simplexml_load_string($input_data) or die("Error: Cannot create object");'."\n";
    echo '$res=json_decode(json_encode($xml),1);//XML回传资料'."\n";
    echo 'foreach ($res as $key => $value) {'."\n";
    echo '$data[$key] = $value;'."\n";
    echo 'write_log($key."=".$value);'."\n";
    echo '}'."\n\n";
}elseif ($req['method'] == 'FILE_XMLCDATA') {
    echo "write_log(input方法);";
    echo '$input_data=file_get_contents("php://input");'."\n";
    echo 'write_log($input_data);'."\n";
    echo '$xml=(array)simplexml_load_string($input_data,\'SimpleXMLElement\',LIBXML_NOCDATA) or die("Error: Cannot create object");'."\n";
    echo '$res=json_decode(json_encode($xml),1);//XML回传资料'."\n";
    echo 'foreach ($res as $key => $value) {'."\n";
    echo '$data[$key] = $value;'."\n";
    echo 'write_log($key."=".$value);'."\n";
    echo '}'."\n\n";
}

echo '#设定固定参数'."\n";;
echo '$order_no = $data["'.$req['orderNumber'].'"]; //订单号'."\n";
if ($req['amount_unit'] == 1) {
    echo '$mymoney = number_format($data["pay_amoumt"], 2, ".", ""); //订单金额'."\n";
}elseif ($req['amount_unit'] == 2) {
    echo '$mymoney = number_format($data["pay_amoumt"]/100, 2, ".", ""); //订单金额'."\n";
}
echo '$success_msg = $data["'.$req['successKey'].'"];//成功讯息'."\n";
echo '$success_code = "'.$req['successValue'].'";//文档上的成功讯息'."\n";
echo '$sign = $data["'.$req['signKey'].'"];//签名'."\n";
echo '$echo_msg = "'.$req['msg'].'";//回调讯息'."\n\n";

echo '#根据订单号读取资料库'."\n";
echo '$params = array(":m_order" => $order_no);'."\n";
echo '$sql = "select operator from k_money where m_order=:m_order";'."\n";
if ($key == 1) {
	echo '$stmt = $mydata1_db->prepare($sql);'."\n";
}else {
	echo '$stmt = $mysqlLink->sqlLink("read1")->prepare($sql);//现数据库的连接方式'."\n";
}
echo '$stmt->execute($params);'."\n";
echo '$row = $stmt->fetch();'."\n\n";

echo '#获取该订单的支付名称'."\n";
echo '$pay_type = substr($row["operator"], 0, strripos($row["operator"], "_"));'."\n";
echo '$params = array(":pay_type" => $pay_type);'."\n";
echo '$sql = "select * from pay_set where pay_type=:pay_type";'."\n";
if ($key == 1) {
	echo '$stmt = $mydata1_db->prepare($sql);'."\n";
}else {
	echo '$stmt = $mysqlLink->sqlLink("read1")->prepare($sql);//现数据库的连接方式'."\n";
}
echo '$stmt->execute($params);'."\n";
echo '$payInfo = $stmt->fetch();'."\n";
echo '$pay_mid = $payInfo["mer_id"];'."\n";
echo '$pay_mkey = $payInfo["mer_key"];'."\n";
echo '$pay_account = $payInfo["mer_account"];'."\n";
echo 'if ($pay_mid == "" || $pay_mkey == "") {'."\n";
echo 'echo "非法提交参数";'."\n";
echo 'exit;'."\n";
echo '}'."\n\n\n";


#第三方传值参数设置
function echo_arr($key_name,$array){
    $text =  '"'.$key_name.'" => array('."\n";
    if ($key_name == 'str_arr') {
      foreach ($array as $obj) {
        foreach ($obj as $arr_key => $arr_value) {
          if (!is_array($arr_value)) {
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
        if (!is_array($arr_value)) {
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

echo '#验签方式'."\n";
echo '$data = array('."\n";
foreach ($req['params'] as $arr_key => $arr_value) {
  if (!is_array($arr_value)) {
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

#验签方式2
$signtext = "";
$signtext .= 'order_no='.$data['order_no'].'&';
$signtext .= 'pay_amoumt='.$data['pay_amoumt'].'&';
$signtext .= 'is_success='.$data['is_success'];
//write_log("signtext=".$signtext);
$mysign = md5($signtext);//签名
//write_log("mysign=".$mysign);

#到账判断
if ($success_msg == $success_code) {
  if ( $mysign == $sign) {
		$result_insert = update_online_money($order_no, $mymoney);
		if ($result_insert == -1) {
			echo ("会员信息不存在，无法入账");
			write_log("会员信息不存在，无法入账");
			exit;
		}else if($result_insert == 0){
			echo ($echo_msg);
			write_log($echo_msg.'at 0');
			exit;
		}else if($result_insert == -2){
			echo ("数据库操作失败");
			write_log("数据库操作失败");
			exit;
		}else if($result_insert == 1){
			echo ($echo_msg);
			write_log($echo_msg.'at 1');
			exit;
		} else {
			echo ("支付失败");
			write_log("支付失败");
			exit;
		}
	}else{
		echo ('签名不正确！');
		write_log("签名不正确！");
		exit;
	}
}else{
	echo ("交易失败");
	write_log("交易失败");
	exit;
}

?>
