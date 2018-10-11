<?php

$userkey = 'bf7d65683d5720034a58eeb395c71bce2ea8f383';
$data = $_REQUEST;
ksort($data);
$string = '';
foreach ($data as $key => $val) {
	if($key != 'remark' && $key != 'sign'){
		$string .= $key.'='.$val.'&';
		}
}
$mysign=md5($string.$userkey);
$sign = $_REQUEST['sign'];

if($sign==$mysign){
    if($status=='1'){
        echo 'success';
    } else {
        echo 'fail';
    }
} else {
    echo 'signerr';
}
?>
