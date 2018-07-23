<?php

include 'withdrawCommon.php';

$p0_Cmd							= $_REQUEST['p0_Cmd'];
$p1_MerId						= $_REQUEST['p1_MerId'];
$p2_BankCode					= $_REQUEST['p2_BankCode'];
$p3_CardAcType					= $_REQUEST['p3_CardAcType'];
$p4_BankCardNo					= $_REQUEST['p4_BankCardNo'];
$p5_CardHolder					= $_REQUEST['p5_CardHolder'];
$p6_BankName					= $_REQUEST['p6_BankName'];
$p7_BankBranchName				= $_REQUEST['p7_BankBranchName'];
$p8_BankProvince				= $_REQUEST['p8_BankProvince'];
$p9_BankCity					= $_REQUEST['p9_BankCity'];
$p10_PayAmount					= $_REQUEST['p10_PayAmount'];
$p11_OrderID					= $_REQUEST['p11_OrderID'];
$p12_ReturnUrl					= $_REQUEST['p12_ReturnUrl'];
$p13_Cur					= $_REQUEST['p13_Cur'];
$p14_Channel					= $_REQUEST['p14_Channel'];

$sign = getReqHmacString($p0_Cmd,$p1_MerId,$p2_BankCode,$p3_CardAcType,$p4_BankCardNo,$p5_CardHolder,$p6_BankName,$p7_BankBranchName,$p8_BankProvince,$p9_BankCity,$p10_PayAmount,$p11_OrderID,$p12_ReturnUrl,$p13_Cur,$p14_Channel);

?>
<html>
<head>
<title>To API Page</title>
</head>
<body onLoad="document.API.submit();">
<form name='API' action='<?php echo $reqURL_onLine; ?>' method='post'>
<input type='hidden' name='p0_Cmd'					value='<?php echo $p0_Cmd; ?>'>
<input type='hidden' name='p1_MerId'				value='<?php echo $p1_MerId; ?>'>
<input type='hidden' name='p2_BankCode'				value='<?php echo $p2_BankCode; ?>'>
<input type='hidden' name='p3_CardAcType'			value='<?php echo $p3_CardAcType; ?>'>
<input type='hidden' name='p4_BankCardNo'			value='<?php echo $p4_BankCardNo; ?>'>
<input type='hidden' name='p5_CardHolder'			value='<?php echo $p5_CardHolder; ?>'>
<input type='hidden' name='p6_BankName'				value='<?php echo $p6_BankName; ?>'>
<input type='hidden' name='p7_BankBranchName'		value='<?php echo $p7_BankBranchName; ?>'>
<input type='hidden' name='p8_BankProvince'			value='<?php echo $p8_BankProvince; ?>'>
<input type='hidden' name='p9_BankCity'				value='<?php echo $p9_BankCity; ?>'>
<input type='hidden' name='p10_PayAmount'			value='<?php echo $p10_PayAmount; ?>'>
<input type='hidden' name='p11_OrderID'				value='<?php echo $p11_OrderID; ?>'>
<input type='hidden' name='p12_ReturnUrl'			value='<?php echo $p12_ReturnUrl; ?>'>
<input type='hidden' name='p13_Cur'					value='<?php echo $p13_Cur; ?>'>
<input type='hidden' name='p14_Channel'				value='<?php echo $p14_Channel; ?>'>
<input type='hidden' name='sign'					value='<?php echo $sign; ?>'>
</form>
</body>
</html>
