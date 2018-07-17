<!DOCTYPE html>
<html>
<head>
<title>在线充值</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, user-scalable=no, maximum-scale=1.0"/>
    <meta content="yes" name="apple-mobile-web-app-capable"/>
    <meta content="black" name="apple-mobile-web-app-status-bar-style"/>
    <meta content="telephone=no" name="format-detection"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta charset="utf-8">
</head>
<body>
<div id="loadingPicBlock" style="max-width: 720px;margin:0 auto;" class="pay">
    <form name="form1" method="post" action="pay.php" target="_blank">
  <table width="100%" height="247" border="1">
    <tr>
      <td>订单号</td>
      <td><input type="text" name="out_trade_no" id="out_trade_no" value="<?php echo time();?>"></td>
    </tr>
     <tr>
      <td>商品名称</td>
      <td><input type="text" name="title" id="title" value="测试"></td>
    </tr>
    <tr>
      <td>金额</td>
      <td><input type="text" name="money" id="money" value="0.01"></td>
    </tr>
    <tr>
      <td>支付方式</td>
      <td><p>
        <label>
          <input type="radio" name="paytype" value="1" id="paytype1" checked>
          支付宝</label>
        <br>
        <label>
          <input type="radio" name="paytype" value="2" id="paytype2">
          微信</label>
          <br>
        <label>
          <input type="radio" name="paytype" value="3" id="paytype3">
          银联</label>
          <br>
        <label>
          <input type="radio" name="paytype" value="4" id="paytype4">
          QQ钱包</label>
        <br>
		<label>
          <input type="radio" name="paytype" value="5" id="paytype5">
          京东扫码</label>
        <br>
		<label>
          <input type="radio" name="paytype" value="6" id="paytype6">
          网银支付</label>
        <br>
      </p></td>
    </tr>
	<tr id="bankcode">
                <td>银行编号：</td>
                <td>
                    <select name="bankcode">
				
                        <option value="ICBC">中国工商银行</option>
                        <option value="ABC">中国农业银行</option>
                        <option value="BOCSH">中国银行</option>
                        <option value="CCB">建设银行</option>
                        <option value="CMB">招商银行</option>
                        <option value="SPDB">浦发银行</option>
                        <option value="GDB">广发银行</option>
                        <option value="BOCOM">交通银行</option>
                        <option value="PSBC">邮政储蓄银行</option>
                        <option value="CNCB">中信银行</option>
                        <option value="CMBC">民生银行</option>
                        <option value="CEB">光大银行</option>
                        <option value="HXB">华夏银行</option>
                        <option value="CIB">兴业银行</option>
                        <option value="BOS">上海银行</option>
                        <option value="SRCB">上海农商</option>
                        <option value="PAB">平安银行</option>
                        <option value="BCCB">北京银行</option>
                    </select>
                </td>
    </tr>
    <tr>
      <td>回调URL</td>
      <td><input type="text" name="notify_url" id="notify_url" value="http://<?php echo $_SERVER['HTTP_HOST'];?>/demo/notify.php"></td>
    </tr>
    <tr>
      <td>跳转URL</td>
      <td><input type="text" name="return_url" id="return_url"  value="http://<?php echo $_SERVER['HTTP_HOST'];?>/demo/return.php"></td>
    </tr>
    <tr>
      <td colspan="2" align="center"><input type="submit" name="button" id="button" value="提交"></td>
    </tr>
  </table>

  </form>

</div>
</body>
</html>

