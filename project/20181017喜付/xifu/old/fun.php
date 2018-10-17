<?php
date_default_timezone_set("Asia/Shanghai");


function http_request($url, $post = '', $timeout = 5)
{
	if (empty($url)) {
		return;
	}
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);


	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

	curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}


function makeSign($param, $md5Key)
{
	ksort($param);
	$strRet = "";
	foreach ($param as $key => $value) {
		if ($value === "") {
			continue;
		}
		if ($key == "md5value") {
			continue;
		}
		$strRet = $strRet . $value;
	}
	$strRet = $strRet . $md5Key;
	logData("待签名串" . $strRet);
	$md5Val = strtoupper(sha1($strRet));
	return $md5Val;
}


function logData($aData)
{
	$fp = fopen('demo.log', 'a+b');
	fwrite($fp, date('Y-m-d H:i:s') . "\t\n");
	fwrite($fp, print_r($aData, true) . "\n");
	fclose($fp);
}


function renderJson($jsonstr)
{
	header('Content-Type: application/json;charset=utf-8');
	header('Content-Length: ' . strlen($jsonstr));
	echo $jsonstr;
	die();
}

?>