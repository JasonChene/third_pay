<!--#include file="pay_config.asp"-->

<%

addtime=now()
ordernumber  =  Year(addtime)& Right("00"&Month(addtime),2) & Right("00"&Day(addtime),2) & Right("00"&Hour(addtime),2) & Right("00"&Minute(addtime),2) & Right("00"&Second(addtime), 2)  '订单号

subject  	  = "zhifu"  '商品名称

paymoney	  = "1"  '交易金额'

attach   	      = "zhifu"   '交易描述


%>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>收银台</title>
</head>


<body>
    <div id="header">
        <div class="head margin">
            <div class="log"></div>
        </div>
    </div>
    <form name='form1' method="post" action="pay.asp" target="_blank">
        <input name="ordernumber" type="hidden" value="<%=ordernumber%>">
        <input name="paymoney" type="hidden" value="<%=paymoney%>">
        <input name="attach" type="hidden" value="<%=attach%>">
        <table>
            <tr>
                <td>订单号
                </td>
                <td><%=ordernumber%> </td>
            </tr>
            <tr>
                <td>支付金额
                </td>
                <td><%=paymoney%>元 </td>
            </tr>
            <tr>
                <td>支付方式
                </td>
                <td>
                    <select name="paytype" id="PayMethod">
                        <option value="1">支付宝扫码</option>
                        <option value="2">微信扫码</option>
                        <option value="3">QQ钱包扫码</option>
                        <option value="ICBC">工商银行</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                </td>
                <td> <input type="button" id="btn_pay" value="提交支付"  onclick="return pay();" /> </td>
            </tr>
        </table>
    </form>
</body>
</html>
