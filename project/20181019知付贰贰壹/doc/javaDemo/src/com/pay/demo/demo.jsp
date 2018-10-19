<%@ page language="java" pageEncoding="utf-8"%>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>发起支付</title>
</head>
<body>


    <form style='display:none;' id='formpay' name='formpay' method='post' action='${data.base_url}'>
        <input name='key' id='key' type='text' value='${data.key}'/>
        <input name='notify_url' id='notify_url' type='text' value='${data.notify_url}'/>
        <input name='order_number' id='order_number' type='text' value='${data.order_number}'/>
        <input name='order_uid' id='order_uid' type='text' value='${data.order_uid}'/>
        <input name='qr_amount' id='qr_amount' type='text' value='${data.qr_amount}'/>
        <input name='return_url' id='return_url' type='text' value='${data.return_url}'/>
        <input name='uid' id='uid' type='text' value='${data.uid}'/>
        <input name='aid' id='aid' type='text' value='${data.aid}'/>
        <input name='type' id='type' type='text' value='${data.type}'/>
    </form>
<script>document.forms['formpay'].submit();</script>
</body>
</html>