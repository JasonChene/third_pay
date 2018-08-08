<?php
include_once('./mysql.config.php');
header("Content-type:text/html; charset=utf-8");



function update_paytype($pay_name,$pay_type)
{
	global $mydata1_db;
	/**
	 * 因 在线提交页面未包含 common 或config 文件，读取数据库获取在线支付赠送；
	 */
    $params = array(':pay_name' => $pay_name);
	$sql = "select * from pay_set where pay_name=:pay_name";
	$stmt = $mydata1_db->prepare($sql);
	$stmt->execute($params);
	$cou = $stmt->rowCount();
	if ($cou >= 1) {
		$params = array(':pay_name_type' => $pay_name.$pay_type,':pay_name' => $pay_name);
		$sql = "
			update pay_set set
				pay_type = :pay_name_type
			where pay_name=:pay_name";
		$stmt = $mydata1_db->prepare($sql);
		$stmt->execute($params);
		$updata = $stmt->rowCount();
        if ($updata >= 1) {
            return true;
        }else {
            return FALSE;
        }
	} else {
		return FALSE;
	}
}
$domain = $_GET['domain'];
$thrid_name = $_GET['thrid_name'];
$file_name = $_GET['file_name'];

$pay_name = $_GET['pay_name'];

$params = array(':pay_name' => $pay_name);
$sql = "select * from pay_set where pay_name=:pay_name";
$stmt = $mydata1_db->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();
$form_head = $row['pay_domain'];

$params = array(':pay_name' => $pay_name);
$sql = "select * from pay_list where pay_name=:pay_name";
$stmt = $mydata1_db->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();
if(strstr($file_name, 'wx')){
  // $form_body = $row['wx_postUrl'];
  $form_body = substr($row['wx_postUrl'], 0, strripos($row['wx_postUrl'], "/"));
}elseif(strstr($file_name, 'zfb')){
  // $form_body = $row['zfb_postUrl'];
  $form_body = substr($row['zfb_postUrl'], 0, strripos($row['zfb_postUrl'], "/"));
}elseif(strstr($file_name, 'qq')){
  // $form_body = $row['qq_postUrl'];
  $form_body = substr($row['qq_postUrl'], 0, strripos($row['qq_postUrl'], "/"));
}elseif(strstr($file_name, 'post')){
  // $form_body = $row['wy_postUrl'];
  $form_body = substr($row['wy_postUrl'], 0, strripos($row['wy_postUrl'], "/"));
}else{
  echo 'NULL';
  exit;
}
$form_url = $form_head.$form_body."/".$file_name;


// $form_url = "http://$domain/pay/$thrid_name/$file_name";

$data = array(
    'S_Name' => '123jia',
    'gid' => '1',
    'top_uid' => '10470',
    'MOAmount' => $_GET['MOAmount'],
    'pay_type' => $_GET['pay_name'].$_GET['pay_type'],
    'bank_code' => $_GET['bank_code'],
    'SubTran' => '马上充值'
);
echo http_build_query($data);
$updata_true = update_paytype($_GET['pay_name'],$_GET['pay_type']);
// header('Location:' .$form_url.'?'.$data_str);
 ?>
 <html>
   <head>
     <title>跳转......</title>
     <meta http-equiv="content-Type" content="text/html; charset=utf-8" />
   </head>
   <body>
     <form name="dinpayForm" method="get" id="frm1" action="<?php echo $form_url; ?>" target="_self">
       <p>正在为您跳转中，请稍候......</p>
       <?php foreach ($data as $arr_key => $arr_value) {?>
       <input type="hidden" name="<?php echo $arr_key; ?>" value="<?php echo $arr_value; ?>" />
       <?php } ?>
     </form>
     <script language="javascript">
       document.getElementById("frm1").submit();
     </script>
   </body>
 </html>
