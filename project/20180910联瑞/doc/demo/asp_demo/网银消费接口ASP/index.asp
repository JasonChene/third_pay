<title>联瑞网银充值</title>
<style>
*{ font-family:Arial, Helvetica, sans-serif;
font-size:12px}
.STYLE1 {font-size: 12px}
</style>
<div style="text-align:center">
  <h2>请选择充值方式</h2>
</div>

<form name="form1" action="sendbank.asp" method="post" target="_blank">
  <table width="498" border="0" align="center" cellpadding="0" cellspacing="0" style="border:#99CC00 solid 2px">
    <tr>
      <td height="407" colspan="2" align="center" bordercolor="#00CCFF"><table width="68%" border="0" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC">
      
	  <tr>
          <td width="32%" height="25" align="left" bgcolor="#FFFFFF"><input type="radio" name="bankid" value="992"  />
支付宝</td>
          <td width="32%" align="left" bgcolor="#FFFFFF"><input type="radio" name="bankid" value="1004" />
微信</td>
          </tr>
        <tr>
          <td width="32%" height="25" align="left" bgcolor="#FFFFFF"><input type="radio" name="bankid" value="964"  />
中国农业银行</td>
          <td width="32%" align="left" bgcolor="#FFFFFF"><input type="radio" name="bankid" value="968" />
浙商银行</td>
          </tr>
        <tr>
          <td height="25" align="left" bgcolor="#FFFFFF"><input type="radio" name="bankid" value="965" />
            中国建设银行</td>
          <td align="left" bgcolor="#FFFFFF"><input name="bankid" type="radio" value="967" checked="checked" />
            中国工商银行</td>
          </tr>
        <tr>
          <td height="25" align="left" bgcolor="#FFFFFF"><input type="radio" name="bankid" value="969" />
            浙江稠州商业银行</td>
          <td align="left" bgcolor="#FFFFFF"><input type="radio" name="bankid" value="970" />
            招商银行</td>
          </tr>
        <tr>
          <td height="25" align="left" bgcolor="#FFFFFF"><input type="radio" name="bankid" value="972" />
            兴业银行</td>
          <td align="left" bgcolor="#FFFFFF"><input type="radio" name="bankid" value="973"  />
            顺德农村信用合作社</td>
          </tr>
        <tr>
          <td height="25" align="left" bgcolor="#FFFFFF"><input type="radio" name="bankid" value="975" />
            上海银行</td>
          <td align="left" bgcolor="#FFFFFF"><input type="radio" name="bankid" value="976" />
            上海农村商业银行</td>
          </tr>
        <tr>
          <td height="25" align="left" bgcolor="#FFFFFF"><input type="radio" name="bankid" value="978" />
            平安银行</td>
          <td align="left" bgcolor="#FFFFFF"><input type="radio" name="bankid" value="980" />
            民生银行</td>
          </tr>
        <tr>
          <td height="25" align="left" bgcolor="#FFFFFF"><input type="radio" name="bankid" value="982" />
            华夏银行</td>
          <td align="left" bgcolor="#FFFFFF"><input type="radio" name="bankid" value="983" />
            杭州银行</td>
          </tr>
        <tr>
          <td height="25" align="left" bgcolor="#FFFFFF"><input type="radio" name="bankid" value="985" />
            广东发展银行</td>
          <td align="left" bgcolor="#FFFFFF"><input type="radio" name="bankid" value="986" />
            光大银行</td>
          </tr>
        <tr>
          <td height="25" align="left" bgcolor="#FFFFFF"><input type="radio" name="bankid" value="988" />
            渤海银行</td>
          <td align="left" bgcolor="#FFFFFF"><input type="radio" name="bankid" value="989" />
            北京银行</td>
          </tr>
        <tr>
          <td height="25" align="left" bgcolor="#FFFFFF"><input type="radio" name="bankid" value="979" />
            南京银行</td>
          <td align="left" bgcolor="#FFFFFF"><input name="bankid" type="radio"  value="962" />
中信银行</td>
          </tr>
        <tr>
          <td height="25" align="left" bgcolor="#FFFFFF"><input type="radio" name="bankid" value="963"  />
中国银行</td>
          <td align="left" bgcolor="#FFFFFF"><input type="radio" name="bankid" value="991"  />
北京农村商业银行</td>
          </tr>
        <tr>
          <td height="25" align="left" bgcolor="#FFFFFF"><input type="radio" name="bankid" value="987"  />
东亚银行</td>
          <td align="left" bgcolor="#FFFFFF"><input type="radio" name="bankid" value="984"  />
广州市农村信用社</td>
          </tr>
        <tr>
          <td height="25" align="left" bgcolor="#FFFFFF"><input type="radio" name="bankid" value="981"  />
交通银行</td>
          <td align="left" bgcolor="#FFFFFF"><input type="radio" name="bankid" value="977"  />
浦东发展银行</td>
          </tr>
        <tr>
          <td height="25" align="left" bgcolor="#FFFFFF"><input type="radio" name="bankid" value="974" />
深圳发展银行</td>
          <td align="left" bgcolor="#FFFFFF"><input type="radio" name="bankid" value="971" />
邮政储蓄</td>
          </tr>

            <tr>
          <td height="25" align="left" bgcolor="#FFFFFF"><input type="radio" name="bankid" value="2097" />
网银WAP</td>
          <td align="left" bgcolor="#FFFFFF"><input type="radio" name="bankid" value="2098" />
支付宝WAP</td>
          </tr>
           <tr>
          <td height="25" align="left" bgcolor="#FFFFFF"><input type="radio" name="bankid" value="2099" />
微信WAP</td>
          <td align="left" bgcolor="#FFFFFF"></td>
          </tr>

      </table></td>
    </tr>
    <tr>
      <td width="111" height="27" align="center">填写金额</td><td width="383">

      <input name="Price2" type="text" size="10" maxlength="10" value="100" />

        元</td>
    </tr>
    <tr>
      <td height="36" colspan="2" align="center"><input type="submit" name="submit2" value="确认付款" onclick="return checkMoney()" />  <a href="../testcard/index.asp"  target="_blank"/>点卡充值</a></td>
    </tr>
  </table>
  <input type="hidden" name="orderid" value="<%=num%>">
</form>
<script language="javascript">
function checkMoney(){
	if(document.form1.Price2.value ==""){
		alert('请输入充值的金额');
		return false
	}
}
</script>