<?php
#预设时间在上海
date_default_timezone_set('PRC');
if (function_exists("date_default_timezone_set")) {
  date_default_timezone_set("Asia/Shanghai");
}

function write_log($str, $pay_name, $file_name)
{ #输出LOG日志
  if (!file_exists('../../' . $file_name) || $file_name == '') {
    file_put_contents("bank_parameter.txt", $str);
    $f_ex = "N";
  } else {
    file_put_contents('../../' . $file_name . "/bank_parameter.txt", $str);
    $f_ex = 'Y';
  }
  $record_str = unicodeDecode(json_encode($record_arr));
  $record_str = date('Y-m-d H:i:s') . ' {"pay_name":"' . $pay_name . '","file_name":"' . $file_name . '","file_exists":"' . $f_ex . '"}' . "\r\n";
  file_put_contents("record.log", $record_str, FILE_APPEND);
}

function unicodeDecode($unicode_str)
{
  $json = '{"str":"' . $unicode_str . '"}';
  $arr = json_decode($json, true);
  // if (empty($arr)) return '';
  return $arr['str'];
}

//获取客户端IP
function getClientIp()
{
  $ip = $_SERVER['REMOTE_ADDR'];

  if (isset($_SERVER['HTTP_X_REAL_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_REAL_FORWARDED_FOR'];
  } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
  } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
  }

  return $ip;
}

#接收资料
#request方法
$SQL = str_replace('<br>', '', $_REQUEST['SQL']);
$pay_name = $_REQUEST['pay_name'];
$file_name = $_REQUEST['file_name'];

write_log($SQL, $pay_name, $file_name);

echo '正在为您跳转中，请稍候......';
header('Location:' . './Bank_coder.php');
exit();
?>
<html>
  <head>
    <title>跳转......</title>
    <meta http-equiv="content-Type" content="text/html; charset=utf-8" />
  </head>
  <body>
  <form action="./Bank_coder_log.php" method="post">
						<textarea type="text" style="display: none;" id="SQL" name="SQL" value="" /></textarea>
						<textarea type="text" style="display: none;" id="sand_file_name" name="file_name" value="" /></textarea>
						<textarea type="text" style="display: none;" id="sand_pay_name" name="pay_name" value="" /></textarea>
						<input class="submit" type="submit" id="submitSQL" onclick="submit_SQL()" value="送出 →" style="width:585; height:30px;display: none; position: absolute; bottom: 5px;"
						/>
					</form>
    <script language="javascript">
      document.getElementById("frm1").submit();
    </script>
  </body>
</html>