<title>����֧���㿨���ѽӿ�ʾ������</title>
<style>
*{ font-family:Arial, Helvetica, sans-serif;
font-size:12px}
.STYLE1 {font-size: 14px}
.s1{
color:#666}
</style>
<div style="text-align:center">
  <h2>��ѡ���ֵ������</h2>
</div>
<form name="form1" action="send.asp" method="post" onsubmit="return CheckNull()">

<table width="566" border="0" align="center" cellpadding="0" cellspacing="0" style="border:#99CC00 solid 2px">

  <tr>
    <td height="295" colspan="2" align="center" bordercolor="#00CCFF"><table width="99%" border="0" cellpadding="3" cellspacing="1" bgcolor="#CCCCCC">
      <tr>
        <td width="28%" height="25" align="left" bgcolor="#FFFFFF"><input name="rad" type="radio" onclick="checkmob(this.value)" value="3" checked="checked" />
������</td>
        <td width="72%" align="left" bgcolor="#E8E8E8" class="s1">֧����ֵ��5��6��10��15��30��50��100Ԫ</td>
      </tr>
      <tr>
        <td height="25" align="left" bgcolor="#FFFFFF"><input type="radio" name="rad" value="6" onclick="checkmob(this.value)"/>
          �Ѻ�һ��ͨ</td>
        <td align="left" bgcolor="#E8E8E8" class="s1">֧����ֵ��5��10��15��30��40��100Ԫ</td>
      </tr>
      <tr>
        <td height="25" align="left" bgcolor="#FFFFFF"><input type="radio" name="rad" value="8" onclick="checkmob(this.value)"/>
          ����һ��ͨ</td>
        <td align="left" bgcolor="#E8E8E8" class="s1">֧����ֵ��5��10��15��25��30��50Ԫ</td>
      </tr>
      <tr>
        <td height="25" align="left" bgcolor="#FFFFFF"><input type="radio" name="rad" value="5" onclick="checkmob(this.value)"/>
          ������ֵ��</td>
        <td align="left" bgcolor="#E8E8E8" class="s1">֧����ֵ��15��30��50��100Ԫ</td>
      </tr>
      <tr>
        <td height="25" align="left" bgcolor="#FFFFFF"><input type="radio" name="rad" value="12" onclick="checkmob(this.value)" />
���ų�ֵ��</td>
        <td align="left" bgcolor="#E8E8E8" class="s1">֧����ֵ��50��100Ԫ</td>
      </tr>
      <tr>
        <td height="25" align="left" bgcolor="#FFFFFF"><input type="radio" name="rad" value="2" onclick="checkmob(this.value)" />
ʢ��</td>
        <td align="left" bgcolor="#E8E8E8" class="s1">֧����ֵ��5��10��30��45��50��100��1000Ԫ</td>
      </tr>
      <tr>
        <td height="25" align="left" bgcolor="#FFFFFF"><input type="radio" name="rad" value="7"onclick="checkmob(this.value)" />
��;��Ϸ��</td>
        <td align="left" bgcolor="#E8E8E8" class="s1">֧����ֵ��10��20��30��50��60��100��300Ԫ</td>
      </tr>
      <tr>
        <td height="25" align="left" bgcolor="#FFFFFF"><input type="radio" name="rad" value="9" onclick="checkmob(this.value)"/>
����һ��ͨ</td>
        <td align="left" bgcolor="#E8E8E8" class="s1">֧����ֵ��10��30Ԫ</td>
      </tr>
      <tr>
        <td height="25" align="left" bgcolor="#FFFFFF"><input type="radio" name="rad" value="13" onclick="checkmob(this.value)" />
�����г�ֵ��</td>
        <td align="left" bgcolor="#E8E8E8" class="s1">֧����ֵ��30��50��100��200��1000Ԫ</td>
      </tr>
      <tr>
        <td height="25" align="left" bgcolor="#FFFFFF"><input type="radio" name="rad" value="14" />
��ͨ��ֵ��</td>
        <td align="left" bgcolor="#E8E8E8" class="s1">֧����ֵ�� 20��30��50��100��300��500Ԫ</td>
      </tr>
    </table></td>
    </tr>
  <tr>
    <td width="158" height="27" align="center">�� �</td>
    <td width="404"><div style="display:none" id="d1"><select name="restrict">
            <option value="0" selected>ȫ��ͨ��</option>
            <option value="9">�Ĵ�ʡ</option>
            <option value="10">������</option>
            <option value="11">����ʡ</option>
            <option value="12">����ʡ</option>
            <option value="13">����������</option>
            <option value="14">������</option>
            <option value="15">�����</option>
            <option value="16">�ӱ�ʡ</option>
            <option value="17">ɽ��ʡ</option>
            <option value="18">���ɹ�������</option>
            <option value="19">����ʡ</option>
            <option value="20">����ʡ</option>
            <option value="21">������ʡ</option>
            <option value="22">�Ϻ���</option>
            <option value="23">����ʡ</option>
            <option value="24">�㽭ʡ</option>
            <option value="25">����ʡ</option>
            <option value="26">����ʡ</option>
            <option value="27">����ʡ</option>
            <option value="28">����ʡ</option>
            <option value="29">�㶫ʡ</option>
            <option value="30">����ʡ</option>
            <option value="31">����ʡ</option>
            <option value="32">����׳��������</option>
            <option value="33">����ʡ</option>
            <option value="34">����ʡ</option>
            <option value="35">����ʡ</option>
            <option value="36">ɽ��ʡ</option>
            <option value="37">�ຣʡ</option>
            <option value="38">���Ļ���������</option>
            <option value="39">ά��������</option>
            <option value="40">����ر�������</option>
          </select>  (�����г�ֵ����ѡ��ʹ�õ���)</div>
<input name="Price" type="text" size="5" maxlength="5">
Ԫ</td>
  </tr>
  <tr>
    <td height="27" align="center">�� �ţ�</td>
    <td height="27" align="left"><input type="text" name="cardNo"></td>
  </tr>
  <tr>
    <td height="27" align="center">�� �ܣ�</td>
    <td height="27" align="left"><input type="text" name="cardPwd"></td>
  </tr>
  <tr>
    <td height="36" colspan="2" align="center"><input type="submit" name="myBotton" value="ȷ�ϳ�ֵ"  /></td>
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
		alert('�������ֵ���');
		return false;
	}
	if(document.form1.cardNo.value==""){
		alert('�������ֵ����');
		return false;
	}
	if(document.form1.cardNo.value==""){
		alert('�������ֵ����');
		return false;
	}
	document.form1.myBotton.disabled ='disabled';
}
</script>