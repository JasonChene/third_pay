<?php
header("Content-type: text/html; charset=utf-8");
date_default_timezone_set('PRC');
error_reporting(E_ALL);
define('HH', chr(10) . chr(10));

define('ID_CODE', '2017111500001425');

define('SEND_URL', 'https://payment.51bftpay.com/sfpay/scanCodePayServlet');
define('SEND_H5_URL', 'https://payment.51bftpay.com/sfpay/h5PayServlet');
define('ORDER_URL', 'https://payment.51bftpay.com/sfpay/queryServlet');

$hzf_private_key = '-----BEGIN PRIVATE KEY-----
MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDQIonAH2lhq2O8
uMOOxCfaDYEJf2r99W/sZihQM8wyKNY0CEkegKze903hzb1zVAYtDlcdGhUEntrr
NZH45iJJoH0LZlIqf7zYQ/JJ4/4Zim5dgAxiYDtD5AOdZfHb4HRSwRO0RrwZQ891
nBi4DQRPSC3XoRd9JV9HiWczr07T7ZzJ+0cgnpJlWKep37I/vlTYjDgtNT/dD9Ie
0/k6R3l2Y1FS1i3CmQhaLhD7dIbFWyxnV9vNPL8aJU3f/xnIJ7RGGCWDN4nyTSjS
2UD2IiFfl8p3xwhNiDL0iWqvf+OSeaDQBnYPP9ht22460CBbZCl6EzODc6HuB7gy
zS714bGlAgMBAAECggEARoxOcMa065wydFeUQaIPeAO3x0ZfN7GnvFNsOrMz9ZSN
9pkQX7xx3CmrRmx8WMkURtVjhcO/eA94WN2RmvI2kQpLGEAYfbtKgWuWhqE3EeUw
P5UVnYrMy9hPuVxCvKq6Awra6PJI869edJjycABJg64Ni9dYpNy0DQVv677yUzbM
UNEyLL8FIA+DDeduts7oNjzOufV+ZmULefglg+s4szSRpnxRZgCjCmZq588ULiji
IxKeKzdMAhHUeZjb7wXoKdszkXohcBgAqjGqPI5WtRrNI1Lsx9srfzTkbqawc+VB
Nj7zjTULe3GfWMTjvUNyOA0qnDRgu2eogKq6pQZLQQKBgQD4uF5cgRYlLUQ5nuZh
SebDMCyAnERaCZ6cqY9mgvaZQ3l06/OeIQ2ir0PHrA3C/owm16gTrBN3PcYH2X82
xYseJ3iQGPqaeJrDeiM+4NL5JcOPKTsdfxOuR7ZPT6o6pjBOUWFmFS4E5PU0Qss9
VH23YsQjbQJ7VEEBJkFtASKJvQKBgQDWOhGZigPOkEd4MOITgpqSIOfsOSWJVV+I
NrIJWJndA2VYWD/iyVNsXOENSggDKzgHA4r7ZDFJmMXPZrY3sR0pFGuR25I0V3/R
lISiG0uR7s/Ntao5jeWUKMxVtycN9gQp9UYPB683fZfCmJKJhxsAlTT8wdeu0er+
loAOknriCQKBgADkUFpOkDDD78mxGyxgInaytoYjiU81V59CMGRytcWo60eTDQWV
IsJhlIGjyUzYstH01gJ0DIHR8+LVUdJ1dOE/zPvfSw7AvfVTe8re7YK9Mu1RfYfG
i+CFG08/fuHE9MljG1FNZCPaWlK8ppPquKSusvtZibe9fpotc/CbH2+VAoGBALNG
40Wnr2nlajoWRovnR2205gHVDSmx7YGzrCiQIitIVT6qk8Q5kXvk+l3Br5TfTULp
Xxzkiy65EAlyPlm6+dlQAnmM0/zDoO8GKDz1NLfKr9LKDcueiRsFTYMKBD/1uyRV
46xeVO7ORvuN7Uv0ac+CDlbb7aCagdtOnZoUUYvpAoGAUfoTvx+rKe5gBFNz/h6D
pCACZ7q+w0LBuRHU82eK1tYxMgY+Hz/aU1LMLKbKUYjsRnpPl/yT6Xq2tmn/58jJ
MgDD7XTUXgQVIH2JOEMHChdY1fHoI+GV2LsbSTDu1UQfZIb1rtcx+ZP1g0qYUPnc
y6h7fxTWwZgNeOz1VN9vJQ8=
-----END PRIVATE KEY-----';

$gwp_public_key = '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAvP2tL25uCasl0o6oZKOZ
9yeagvhJ25UlF3Pn9RyfJ6fYelu9rntjO0NVrXHxhmkPt6zLQxiSjzZrKauIbvD0
dlS5l7q+bJ3ERARhXJjy053dGDgI8UZkHCWtqNDD7uyVyNPbehawfNHHW6R4O8rL
84eylSyXQjJqp7R3hcmd7fVdtljL5rj86YxPvD/1NChYmJ8YEwaZX4T2XEGqNjTv
lGEX3Vpj1/RhRR1VvaGqmJwtFDHSWRCXM5BQUiNoSmuvoCIbcqg3sPlS5Kl7etJG
b/gzrp9zWqPItEyUMaDli2Q2I7Y0Z/bhxUUMW/7J8Hi5a/IqnP2zT/oRmQq12dED
kQIDAQAB
-----END PUBLIC KEY-----';

function array_to_querystr($parm, $enc = false) {
    $ary = array();
    foreach ($parm as $k => $v) {
        $val     = ($enc) ? urlencode($v) : $v;
        $ary[$k] = $k . '=' . $v;
    }
    ksort($ary);
    return implode('&', $ary);
}
function getSendSignature($parm, $merchNo, $key) {
    $data = array();
    foreach ($parm as $k => $v) {
        if ($k == 'sign' || $k == 'signType' || $v == '') {
            continue;
        }
        $data[$k] = $v;
    }
    $sign = array_to_querystr($data);
    return rsaSendSign($sign, $merchNo, $key);
}
function getRecvSignature($parm, $merchNo, $key) {
    $data = array();
    foreach ($parm as $k => $v) {
        if ($k == 'sign' || $k == 'signType' || $v == '') {
            continue;
        }
        $data[$k] = $v;
    }
    $sign = array_to_querystr($data);
    return rsaRecvSign($sign, $parm['sign'], $merchNo, $key);
}
function rsaSendSign($data, $merid, $key) {
    $key = openssl_get_privatekey($key);
    openssl_sign($data, $sign, $key);
    openssl_free_key($key);
    $sign = base64_encode($sign);
    return $sign;
}
function rsaRecvSign($data, $sign, $merid, $key) {
    $key    = openssl_get_publickey($key);
    $verify = openssl_verify($data, base64_decode($sign), $key, OPENSSL_ALGO_SHA1);
    return $verify;
}
function curlPost($url, $data) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 100);
    curl_setopt($ch, CURLOPT_TIMEOUT, 100);
    curl_setopt_array($ch, array(
        CURLOPT_HTTPHEADER     => array(
            "content-type: application/x-www-form-urlencoded",
        ),
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
    ));

    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}
function writeLog($file, $str) {
    mkdirs(dirname($file));
    file_put_contents($file, date('Y-m-d H:i:s') . chr(10) . $str . chr(10) . chr(10), FILE_APPEND);
}
function mkdirs($dir) {
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }
}