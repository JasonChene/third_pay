<?php
/**
 * Created by PhpStorm.
 * User: 陈远
 * Date: 2018/9/12
 * Time: 16:45
 */
//主动查询订单 最好是以这个为主
require_once 'inc.php';

$sdorderno = $_GET['no'];
$time = time();
$str = "customerid={$userid}&sdorderno={$sdorderno}&reqtime={$time}&{$userkey}";
$sign = md5($str);

$data['customerid'] = $userid;
$data['sdorderno'] = $sdorderno;
$data['reqtime'] = $time;
$data['sign'] = $sign;

$res = post_curls($payurl . 'apiorderquery', $data);
var_dump($res);