<?php
header("Content-type: text/html; charset=utf-8");

define('TIME_ZONE', 8); // 设置所在区域 东8区
date_default_timezone_set('PRC');
@ini_set("date.timezone", TIME_ZONE);

// 检测PHP环境
if (version_compare(PHP_VERSION, '5.3.0', '<')) {
    die ('require PHP > 5.3.0 !');
}

function getHttpProtocol() {
    return (!empty ($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
}

// 获取当前支付程序http的url目录
function getPayHttp() {
    $cur_url = getHttpProtocol() . $_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"];
    return dirname($cur_url);
}