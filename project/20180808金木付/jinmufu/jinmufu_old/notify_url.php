<?php
header("Content-type:text/html; charset=utf-8");
include_once "../../../database/mysql.config.php";
include_once "../moneyfunc.php";

$json_data = file_get_contents("php://input");
$json = json_decode($json_data, 1);
$attach = $json['attach']; // 商户自定义备注信息
$errcode = $json['errcode']; // 狀態碼
$orderno = $json['orderno']; // 訂單號
$total_fee = $json['total_fee']; // 訂單金額
$sign = $json['sign']; // 签名

//取得订单
$params = array(':m_order' => $orderno);
$sql = "select operator from k_money where m_order=:m_order";
$stmt = $mydata1_db->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();

//获取该订单的支付名称
$pay_type = substr($row['operator'], 0, strripos($row['operator'], "_"));
$params = array(':pay_type' => $pay_type);
$sql = "select * from pay_set where pay_type=:pay_type";
$stmt = $mydata1_db->prepare($sql);
$stmt->execute($params);

$payInfo = $stmt->fetch();
$pay_mid = $payInfo['mer_id'];
$pay_mkey = $payInfo['mer_key'];
$pay_account = $payInfo['mer_account'];
if ("" == $pay_mid || "" == $pay_mkey) {
    echo "非法提交参数";
    exit;
}

$signtext = '';
$signtext .= $attach;
$signtext .= $errcode;
$signtext .= $orderno;
$signtext .= $total_fee;
$signtext .= $pay_mkey;
$sign2 = strtoupper(md5($signtext));

//if(notify回傳成功)
if ($errcode == 0 || $errcode == "0") {
    if ($sign == $sign2) {
        $mymoney = number_format($total_fee / 100, 2, '.', '');
        $result_insert = update_online_money($orderno, $mymoney);
        if ('-1' == $result_insert) {
            echo ("会员信息不存在，无法入账");
        } elseif ('0' == $result_insert) {
            echo "success";
        } elseif ('-2' == $result_insert) {
            echo ("数据库操作失败");
        } elseif ('1' == $result_insert) {
            echo "success";
        }else {
            echo ("支付失败");
        }
    } else {
        echo '签名不正确！';
        exit;
    }
}
