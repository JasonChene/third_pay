<?php
header("Content-type:text/html; charset=utf-8");
include_once "../../../database/mysql.php";
include_once "../moneyfunc.php";

$json_data = file_get_contents("php://input");
$json = json_decode($json_data, 1);

$version = $json['version']; // 版本号
$merId = $json['merId']; // 商戶號
$merOrderNo = $json['merOrderNo']; // 商户订单号
$payNo = $json['payNo']; // 平台订单号
$payStatus = $json['payStatus']; // 支付状态
$payDate = $json['payDate'];
$payTime = $json['payTime'];
$orderTitle = $json['orderTitle']; // 订单标题
$orderDesc = $json['orderDesc']; // 订单描述
$orderAmt = $json['orderAmt']; // 訂單支付金額,小數點兩位
$realAmt = $json['realAmt']; // 实际支付金额
$sign = $json['sign'];

//取得订单
$params = array(':m_order' => $merOrderNo);
$sql = "select operator from k_money where m_order=:m_order";
$stmt = $mysqlLink->sqlLink('read1')->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();

//获取该订单的支付名称
$pay_type = substr($row['operator'], 0, strripos($row['operator'], "_"));
$params = array(':pay_type' => $pay_type);
$sql = "select * from pay_set where pay_type=:pay_type";
$stmt = $mysqlLink->sqlLink('read1')->prepare($sql);
$stmt->execute($params);

$payInfo = $stmt->fetch();
$pay_mid = $payInfo['mer_id'];
$pay_mkey = $payInfo['mer_key'];
$pay_account = $payInfo['mer_account'];
if ("" == $pay_mid || "" == $pay_mkey) {
    echo "非法提交参数";
    exit;
}

$parms = array(
    'version' => $version,
    'merId' => $merId,
    'merOrderNo' => $merOrderNo,
    'payNo' => $payNo,
    'payStatus' => $payStatus,
    'payDate' => $payDate,
    'payTime' => $payTime,
    'orderTitle' => $orderTitle,
    'orderDesc' => $orderDesc,
    'orderAmt' => $orderAmt,
    'realAmt' => $realAmt
);
$signText = '';
ksort($parms);
foreach ($parms as $key => $val) {
  $signText .= $key . "=" . $val . "&";
}
$signText .= "key=" . $pay_mkey;
$sign2 = MD5($signText);

//if(notify回傳成功)
if ($payStatus == 'S') {
    if ($sign == $sign2) {
        $mymoney = number_format($orderAmt, 2, '.', '');
        $result_insert = update_online_money($merOrderNo, $mymoney);
        if ( $result_insert == '-1') {
            echo ("会员信息不存在，无法入账");
        } elseif ($result_insert == '0') {
            echo "success";
        } elseif ($result_insert == '-2') {
            echo ("数据库操作失败");
        } elseif ($result_insert == '1') {
            echo "success";
        }else {
            echo ("支付失败");
        }
    } else {
        echo '签名不正确！';
        exit;
    }
}
