<?php
include_once('./mysql.config.php');
header("Content-type:text/html; charset=utf-8");
$params = array(':pay_name' => $_GET['pay_name']);
$sql = "select bank_name,bank_code from bank_code where pay_name=:pay_name";
$stmt = $mydata1_db->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetchAll();
foreach ( $row as $arr ) {
    $res .= "<option value=".$arr['bank_code'].">银行：".$arr['bank_name']."&nbsp;&nbsp;&nbsp;代码：".$arr['bank_code']."</option>";
}
echo $res;

?>