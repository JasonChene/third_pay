<?php
require_once 'common.php';

$url = 'http://www.senyopay.io/Api/Pay';

$Data = array();
!empty($_POST['Merchants']) && $Data['Merchants'] = $_POST['Merchants'];
!empty($_POST['Amount']) && $Data['Amount'] =  str_replace('.', '', $_POST['Amount']);//金额去除符号取纯数值
!empty($_POST['Description']) && $Data['Description'] = $_POST['Description'];
!empty($_POST['BusinessOrders']) && $Data['BusinessOrders'] = $_POST['BusinessOrders'];
!empty($_POST['OrderTime']) && $Data['OrderTime'] = (int) $_POST['OrderTime'];//将时间戳转为整数型
!empty($_POST['SubmitIP']) && $Data['SubmitIP'] = $_POST['SubmitIP'];
!empty($_POST['NotifyUrl']) && $Data['NotifyUrl'] = $_POST['NotifyUrl'];
!empty($_POST['TypeService']) && $Data['TypeService'] = $_POST['TypeService'];
!empty($_POST['PostService']) && $Data['PostService'] = $_POST['PostService'];
!empty($_POST['CardCode']) && $Data['CardCode'] = $_POST['CardCode'];
!empty($_POST['ReturnUrl']) && $Data['ReturnUrl'] = $_POST['ReturnUrl'];
$Data['Sign'] = lib::SignCrypt($Data, $Merchants_private_Key);

//改为json格式递交
$Data = json_encode($Data);

$result = lib::HTTP_CURL_DATA($url, $Data);
$Data = json_decode($Data);
$result = json_decode($result);

if($result->Code != 1) {
    print_r($result->Msg);
    exit;
}
$Sign = lib::VerifyCrypt(lib::myksort($result), base64_decode($result->Sign), $Senyo_Public_Key);
if(!$Sign) {
    print_r("数据校验不通过");
    exit;
}
if($Data->PostService == 'Scan') {
    $result = QRcode::png($result->Data);
} else {
    $result = $result->Data;
}
print_r($result);