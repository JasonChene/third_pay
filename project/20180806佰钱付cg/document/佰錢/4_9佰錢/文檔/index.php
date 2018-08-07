<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>API测试</title>
    <style type="text/css">
        <!--
        .STYLE1 {
            font-family: "微软雅黑";
            font-size: x-large;
        }
        -->
    </style>
</head>

<body marginheight="0" marginwidth="0">
<form action="Action/PayAction.php" method="post">
    <table width="40%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td height="93" colspan="2" align="center"><span class="STYLE1">API测试DEMO</span></td>
        </tr>
        <tr>
            <td width="50%" height="34" align="right">支付方式：</td>
            <td width="50%">
                <select name="PaymentType" id="PaymentType">
                    <option value="">请选择</option>
                    <option value="WXSM">微信扫码</option>
                    <option value="WXH5">微信H5</option>
                    <option value="ALIPAYSM">支付宝扫码</option>
                    <option value="ALIPAYH5">支付宝H5</option>
                    <option value="QQSM">QQ扫码</option>
                    <option value="BSM">银联扫码</option>
                    <option value="JDSM">京东扫码</option>
                    <option value="ICBC">中国工商银行</option>
                    <option value="CMBCHINA">招商银行</option>
                    <option value="ABC">中国农业银行</option>
                    <option value="CCB">中国建设银行</option>
                    <option value="BCCB">北京银行</option>
                    <option value="CMBC">中国民生银行</option>
                    <option value="CEB">中国光大银行</option>
                    <option value="BOC">中国银行</option>
                    <option value="PINGANBANK">平安银行</option>
                    <option value="ECITIC">中信银行</option>
                    <option value="GDB">广东发展银行</option>
                    <option value="POST">邮政储蓄</option>
                    <option value="HXB">华夏银行</option>
                    <option value="BOCO">交通银行</option>
                    <option value="CIB">兴业银行</option>
                    <option value="SPDB">上海浦东发展银行</option>
                    <option value="SDB">深圳发展银行</option>
                    <option value="CBHB">渤海银行</option>
                    <option value="HKBEA">东亚银行</option>
                    <option value="HZBANK">杭州银行</option>
                    <option value="NJCB">南京银行</option>
                    <option value="SHB">上海银行</option>
                    <option value="CZ">浙商银行</option>
                    <option value="KJZF">快捷支付</option>
                </select>
            </td>
        </tr>
        <tr>
            <td height="36" align="right">支付金额：</td>
            <td><input name="total_fee" type="text" id="total_fee" value=""/></td>
        </tr>
        <tr>
            <td height="36" align="right">银行卡号：</td>
            <td><input name="acc_no" type="text" id="acc_no" value=""/></td>
        </tr>
        <tr>
            <td colspan="2" align="center" style="color: red">快捷支付必填</td>
        </tr>
        <tr>
            <td height="36" align="right">是否APP：</td>
            <td>
                <select name="isApp" id="isApp">
                    <option value="">否</option>
                    <option value="app">是</option>
                </select>(只支持扫码)
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center"><label><input type="submit" name="Submit" value="提交"/></label></td>
        </tr>
    </table>
</form>
</body>
</html>
