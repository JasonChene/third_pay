<?php

include 'MoneyCommon.php';

$p0_Cmd							= $_REQUEST['p0_Cmd'];
$p1_MerId						= $_REQUEST['p1_MerId'];
$p2_Cur					= $_REQUEST['p2_Cur'];

$sign = getReqHmacString($p0_Cmd,$p1_MerId,$p2_Cur);

?>
<html>
<head>
<title>To API Page</title>
</head>
<body onLoad="document.API.submit();">
<form name='API' action='<?php echo $reqURL_onLine; ?>' method='post'>
<input type='text' name='p0_Cmd'					value='<?php echo $p0_Cmd; ?>'>
<input type='text' name='p1_MerId'				value='<?php echo $p1_MerId; ?>'>
<input type='text' name='p2_Cur'				value='<?php echo $p2_Cur; ?>'>
<input type='text' name='sign'					value='<?php echo $sign; ?>'>
<input type="submit" value="SUBMIT">
</form>
</body>
</html>
