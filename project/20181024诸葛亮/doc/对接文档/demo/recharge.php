<?php
function sign($money, $record, $sdk) {
    $sign = md5(Number_format($money, 2, '.','') . trim($record) . $sdk);
    return $sign;
}
$money = $_REQUEST['money'];//���
$sdk = $_REQUEST['sdk'];//֧����ʽ�Լ���Ӧ��key
$record = $_REQUEST['record'];//������
$refer = $_REQUEST['refer'];//ͬ��֪ͨ��ַ
$notify_url = "http://www.1qcz.com/demo/notify.php"; //�첽֪ͨ
$url = "http://www.1qcz.com/pay?sdk=" . $sdk . "&record=" . $record . "&money=" . $money . "&refer=".$refer."&notify_url=".$notify_url."&sign=" . sign($money, $record, $sdk);

if($money > 0){
header('Location:'.$url);
}

?>