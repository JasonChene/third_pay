<?php
// include_once('./mysql.config.php');
// header("Content-type:text/html; charset=utf-8");

function write_log($str, $file_name)
{ #输出LOG日志
  if (!file_exists('../../' . $file_name) || $file_name == '') {
    file_put_contents("bank_parameter.txt", $str);
  } else {
    file_put_contents('../../' . $file_name . "/bank_parameter.txt", $str);
  }
    // file_put_contents("bank_parameter.txt", $str, FILE_APPEND);
}

#接收资料
#request方法=
$SQL = str_replace('<br>', '', $_REQUEST['SQL']);
$file_name = $_REQUEST['file_name'];

// exit();
write_log($SQL, $file_name);

// foreach ($_REQUEST as $key => $value) {
//     $value = str_replace('<br>', '', $value);
//     write_log($value);
// }

// $params = array(':pay_name_type' => $pay_name . $pay_type, ':pay_name' => $pay_name);
// $sql = "INSERT INTO `7k111data1_db`.`bank_code` (`id`, `pay_name`, `bank_name`, `bank_code`) VALUES ('', '000', '交通银行', 'BOCO');";
// $stmt = $mydata1_db->prepare($sql);
// $stmt->execute($params);
// $updata = $stmt->rowCount();
// if ($updata >= 1) {
//     return true;
// } else {
//     return false;
// }

// $result_insert = insert_online_order($_REQUEST['S_Name'], $order_no, $mymoney, $bankname, $payType, $top_uid);


echo '正在为您跳转中，请稍候......';
// header('Location:' . './130.php?msg=bank_parameter.txt');
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
        <input class="submit" type="submit" id="submitSQL" onclick="submit_SQL()" value="送出" style="width:294px; height:30px;display: none; position: absolute; bottom: 10px;"
        />
    </form>
    <script language="javascript">
      document.getElementById("frm1").submit();
    </script>
  </body>
</html>