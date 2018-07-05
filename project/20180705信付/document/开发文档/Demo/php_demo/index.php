<?php
require_once("lib/class.cardpay.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
    <title>支付演示</title>
    <link rel="stylesheet" type="text/css" href="style/css.css"/>
    <script language="javascript" src="js/jquery.js"></script>
    <script language="javascript" src="js/pay.js"></script>
  </head>
  <body>
    <center>

      <?php
$html	 = '<form method="post" action="pay_go.php"  name="pay" id="pay">';
$html	.= '<div class="pay_base_info">';
$html	.= '<table class="form">';
$html	.= '<tbody>';
$html	.= '<tr class="title">';
$html	.= '	<td colspan="2"><br/>phpeka demo 1.0 <br/>接口版本:v2.1<br/>网银支付版本:v2.0</td>';
$html	.= '</tr>';
$html	.= '<tr>';
$html	.= '	<td class="label">充值账户:</td>';
$html	.= '	<td class="content">';
$html	.= '		<input type="text" name="account" id="account" value="myaccount"/>';
$html	.= '	</td>';
$html	.= '</tr>';
$html	.= '<tr>';
$html	.= '	<td class="label">确认账号:</td>';
$html	.= '	<td class="content">';
$html	.= '		<input type="text" name="account_confirm" id="account_confirm"  value="myaccount"/>';
$html	.= '	</td>';
$html	.= '</tr>';
$html	.= '<tr>';
$html	.= '	<td class="label">支付金额:</td>';
$html	.= '	<td class="content">';
$html	.= '		<input type="text" name="amount" id="amount"  value="20"/>';
$html	.= '	</td>';
$html	.= '</tr>';
$html	.= '<tr>';
$html	.= '	<td class="label">支付方式:</td>';
$html	.= '	<td class="content">';
$html	.= '		<input type="radio" name="payType" id="payType_bank" class="payType" value="bank" checked="checked"><label for="payType_bank">网银支付</label>';
$html	.= '		<input type="radio" name="payType" id="payType_card" class="payType" value="card" ><label for="payType_card">卡类支付(单卡)</label>';
$html	.= '	</td>';
$html	.= '</tr>';

//卡类型(单卡)
$html	.= '<tr class="payTypeCard">';
$html	.= '	<td colspan="2">';
foreach($lib_cardtype as $card){
	$cardTypeRadioId	= 'cardType_' . $card['code'];
	$html	.= '<span class="cardType">';
	$html	.= '<input type="radio"  class="cardType" name="cardType" id="'.$cardTypeRadioId.'" value="'.$card['code'].'">';
	$html	.= '<label for="'. $cardTypeRadioId .'">'.$card['name'].'</label>';
	$html	.= '</span>';
}
$html	.= '	</td>';
$html	.= '</tr>';
$html	.= '<tr class="payTypeCard">';
$html	.= '	<td class="label">卡号:</td>';
$html	.= '	<td class="content">';
$html	.= '		<input type="text" name="card_number" id="card_number"  value="xxxxx"/>';
$html	.= '	</td>';
$html	.= '</tr>';
$html	.= '<tr class="payTypeCard">';
$html	.= '	<td class="label">卡密:</td>';
$html	.= '	<td class="content">';
$html	.= '		<input type="text" name="card_password" id="card_password"  value="xxxxx"/>';
$html	.= '	</td>';
$html	.= '</tr>';

//网银类型
$html	.= '<tr class="payTypeBank">';
$html	.= '	<td colspan="2">';
$html	.= '	<div class="bankTypeDiv">';
foreach($lib_banktype as $bank){
	$bankTypeRadioId	= 'bankType_' . $bank['code'];
	$html	.= '<span class="bankType">';
	$html	.= '<input type="radio"  class="bankType" name="bankType" id="'.$bankTypeRadioId.'" value="'.$bank['code'].'">';
	$html	.= '<label for="'. $bankTypeRadioId .'">'.$bank['name'].'</label>';
	$html	.= '</span>';
}
$html	.= '	<div>';
$html	.= '	</td>';

$html	.= '</tr>';

$html	.= '<tr class="foot">';
$html	.= '	<td colspan="2"><input type="submit" value="确认支付" id="submit" name="submit" /></td>';
$html	.= '</tr>';
$html	.= '</tbody>';
$html	.= '</table>';
$html	.= '</form>';
echo($html);
?>
    </center>
  </body>
</html>
