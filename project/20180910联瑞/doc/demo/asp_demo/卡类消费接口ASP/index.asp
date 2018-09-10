<title>联瑞支付点卡消费接口示范案例</title>
<style>
*{ font-family:Arial, Helvetica, sans-serif;
font-size:12px}
.STYLE1 {font-size: 14px}
.s1{
color:#666}
</style>
<div style="text-align:center">
  <h2>请选择充值卡类型</h2>
</div>
<form name="form1" action="send.asp" method="post" onsubmit="return CheckNull()">

<table width="566" border="0" align="center" cellpadding="0" cellspacing="0" style="border:#99CC00 solid 2px">

  <tr>
    <td height="295" colspan="2" align="center" bordercolor="#00CCFF"><table width="99%" border="0" cellpadding="3" cellspacing="1" bgcolor="#CCCCCC">
      <tr>
        <td width="28%" height="25" align="left" bgcolor="#FFFFFF"><input name="rad" type="radio" onclick="checkmob(this.value)" value="3" checked="checked" />
骏网卡</td>
        <td width="72%" align="left" bgcolor="#E8E8E8" class="s1">支持面值：5、6、10、15、30、50、100元</td>
      </tr>
      <tr>
        <td height="25" align="left" bgcolor="#FFFFFF"><input type="radio" name="rad" value="6" onclick="checkmob(this.value)"/>
          搜狐一卡通</td>
        <td align="left" bgcolor="#E8E8E8" class="s1">支持面值：5、10、15、30、40、100元</td>
      </tr>
      <tr>
        <td height="25" align="left" bgcolor="#FFFFFF"><input type="radio" name="rad" value="8" onclick="checkmob(this.value)"/>
          久游一卡通</td>
        <td align="left" bgcolor="#E8E8E8" class="s1">支持面值：5、10、15、25、30、50元</td>
      </tr>
      <tr>
        <td height="25" align="left" bgcolor="#FFFFFF"><input type="radio" name="rad" value="5" onclick="checkmob(this.value)"/>
          完美充值卡</td>
        <td align="left" bgcolor="#E8E8E8" class="s1">支持面值：15、30、50、100元</td>
      </tr>
      <tr>
        <td height="25" align="left" bgcolor="#FFFFFF"><input type="radio" name="rad" value="12" onclick="checkmob(this.value)" />
电信充值卡</td>
        <td align="left" bgcolor="#E8E8E8" class="s1">支持面值：50、100元</td>
      </tr>
      <tr>
        <td height="25" align="left" bgcolor="#FFFFFF"><input type="radio" name="rad" value="2" onclick="checkmob(this.value)" />
盛大卡</td>
        <td align="left" bgcolor="#E8E8E8" class="s1">支持面值：5、10、30、45、50、100、1000元</td>
      </tr>
      <tr>
        <td height="25" align="left" bgcolor="#FFFFFF"><input type="radio" name="rad" value="7"onclick="checkmob(this.value)" />
征途游戏卡</td>
        <td align="left" bgcolor="#E8E8E8" class="s1">支持面值：10、20、30、50、60、100、300元</td>
      </tr>
      <tr>
        <td height="25" align="left" bgcolor="#FFFFFF"><input type="radio" name="rad" value="9" onclick="checkmob(this.value)"/>
网易一卡通</td>
        <td align="left" bgcolor="#E8E8E8" class="s1">支持面值：10、30元</td>
      </tr>
      <tr>
        <td height="25" align="left" bgcolor="#FFFFFF"><input type="radio" name="rad" value="13" onclick="checkmob(this.value)" />
神州行充值卡</td>
        <td align="left" bgcolor="#E8E8E8" class="s1">支持面值：30、50、100、200、1000元</td>
      </tr>
      <tr>
        <td height="25" align="left" bgcolor="#FFFFFF"><input type="radio" name="rad" value="14" />
联通充值卡</td>
        <td align="left" bgcolor="#E8E8E8" class="s1">支持面值： 20、30、50、100、300、500元</td>
      </tr>
    </table></td>
    </tr>
  <tr>
    <td width="158" height="27" align="center">金 额：</td>
    <td width="404"><div style="display:none" id="d1"><select name="restrict">
            <option value="0" selected>全国通用</option>
            <option value="9">四川省</option>
            <option value="10">重庆市</option>
            <option value="11">贵州省</option>
            <option value="12">云南省</option>
            <option value="13">西藏自治区</option>
            <option value="14">北京市</option>
            <option value="15">天津市</option>
            <option value="16">河北省</option>
            <option value="17">山西省</option>
            <option value="18">内蒙古自治区</option>
            <option value="19">辽宁省</option>
            <option value="20">吉林省</option>
            <option value="21">黑龙江省</option>
            <option value="22">上海市</option>
            <option value="23">江苏省</option>
            <option value="24">浙江省</option>
            <option value="25">安徽省</option>
            <option value="26">福建省</option>
            <option value="27">江西省</option>
            <option value="28">河南省</option>
            <option value="29">广东省</option>
            <option value="30">湖北省</option>
            <option value="31">湖南省</option>
            <option value="32">广西壮族自治区</option>
            <option value="33">海南省</option>
            <option value="34">陕西省</option>
            <option value="35">甘肃省</option>
            <option value="36">山东省</option>
            <option value="37">青海省</option>
            <option value="38">宁夏回族自治区</option>
            <option value="39">维吾自治区</option>
            <option value="40">香港特别行政区</option>
          </select>  (神州行充值卡请选择使用地区)</div>
<input name="Price" type="text" size="5" maxlength="5">
元</td>
  </tr>
  <tr>
    <td height="27" align="center">卡 号：</td>
    <td height="27" align="left"><input type="text" name="cardNo"></td>
  </tr>
  <tr>
    <td height="27" align="center">卡 密：</td>
    <td height="27" align="left"><input type="text" name="cardPwd"></td>
  </tr>
  <tr>
    <td height="36" colspan="2" align="center"><input type="submit" name="myBotton" value="确认充值"  /></td>
  </tr>
</table>
</form>
<script language="javascript">
function checkmob(obj){
	if(obj==13){document.getElementById("d1").style.display='block';}
	else{document.getElementById("d1").style.display='none';}
}
function CheckNull(){

	if(document.form1.Price.value==""){
		alert('请输入充值金额');
		return false;
	}
	if(document.form1.cardNo.value==""){
		alert('请输入充值卡号');
		return false;
	}
	if(document.form1.cardNo.value==""){
		alert('请输入充值卡密');
		return false;
	}
	document.form1.myBotton.disabled ='disabled';
}
</script>