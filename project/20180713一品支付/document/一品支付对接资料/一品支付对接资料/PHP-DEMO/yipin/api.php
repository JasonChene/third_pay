<?php
require_once 'order.php';
$api = new Pay();
$response = $api->order();
$data = json_decode($response, 1);
print_r($data);
exit;
header("Location: {$data['data']}");
