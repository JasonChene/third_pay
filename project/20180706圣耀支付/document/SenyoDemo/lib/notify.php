<?php
require_once 'common.php';
$data = array();
$data = file_get_contents('PHP://input');
$data = json_decode($data);
$Sign = lib::VerifyCrypt(lib::myksort($data), base64_decode($data->Sign), $Senyo_Public_Key);
if(!$Sign) {
    print_r("验签失败");
    exit;
}
$file = "log.log";
$content = date("Y-m-d H:i:s", time()) . " => Merchants:" . $data->Merchants . "->BusinessOrders:" . $data->BusinessOrders . "->OrderTime:" . $data->OrderTime . "->Amount:" . $data->Amount . "->SenyoOrder:" . $data->SenyoOrder . "->PayTime:" . $data->PayTime . "->OrderStatus:" . $data->OrderStatus . ";\r\n";
file_put_contents($file, $content,FILE_APPEND);
print_r("SUCCESS");