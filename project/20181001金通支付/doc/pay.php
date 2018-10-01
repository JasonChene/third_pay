<?php
include 'config.php';
/*
 * 发起支付页面
 */
?>

<!DOCTYPE html>
<html>
    <head>
        <title>支付页面</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
        <link href="Public/css/common.css" rel="stylesheet" type="text/css"/>
        <script src="Public/js/jquery.min.js" type="text/javascript"></script>
</head>
<body>
    <form  method="post" action="pay_action.php" target="_blank">
        <h1>支付</h1>
        <table width="100%" border="0" align="center">
            <colgroup>
                 <col width="27%" align="left" />
                 <col width="27%" align="left"/>
                 <col width="46%" align="left"/>                    
            </colgroup>
            <tr>
                <td align="center" colspan="3">
                    支付&nbsp;&nbsp;<a href="query.php" target="_blank">查询</a>
                </td>
            </tr> 
            <tr>
                <td>商户号(userId)<span>*</span></td>
                <td><input type="text" name="userId" id="userId" value="<?php echo USERID; ?>" /></td>
                <td>提供给商户的ID</td>
            </tr>
            <tr>
                <td>商户订单号(orderNo)<span>*</span></td>
                <td><input type="text" name="orderNo" id="orderNo" value="<?php echo date("YmdHis", time()); ?>" /></td>
                <td>商户订单号<span>(要保证唯一)</span>最长50字符</td>
            </tr>
            <tr>
                <td>支付类型(tradeType)<span>*</span></td>
                <td>
                    <select name="tradeType" id="tradeType" style="width:232px;height: 22px;">     
                        <option value="01">微信扫码</option> 
                        <option value="02">微信H5</option> 
                        <option value="03">微信公众号</option>    
                        <option value="11">支付宝PC</option>   
                        <option value="12">支付宝WAP</option>
                        <option value="21">QQ扫码</option>
                        <option value="31">京东扫码</option>
                        <option value="41">网银支付</option>
                        <option value="51">银联WAP</option>
                        <option value="61">银联PC</option>
                        <option value="71">银联扫码</option>
                        <option value="81">网银一码付</option>
                    </select>
                </td>
                <td>支付类型</td>
            </tr>
            <tr>
                <td>支付金额(payAmt)<span>*</span></td>
                <td><input type="text"  name="payAmt" id="payAmt" value="0.11" /></td>
                <td>取值范围（0.01到10000000.00）,单位:元,小数点后保留两位</td>
            </tr> 
            <tr>
                <td>商品名称(goodsName)<span>*</span></td>
                <td><input type="text" name="goodsName" id="goodsName" value="商品名称" /></td>                    
                <td>商品名称,最长50字符（不参加签名）</td>
            </tr>
            <tr>
                <td>异步通知地址(notifyUrl)<span>*</span></td>
                <td><input type="text" name="notifyUrl" id="notifyUrl" value="http://"/></td>
                <td>用户支付成功后会将支付结果发送到该页面,商户做后续的业务处理（该地址要外网可访问）</td>
            </tr>
            <tr>
                <td>同步通知地址(returnUrl)<span>*</span></td>
                <td><input type="text" name="returnUrl" id="returnUrl" value="http://"/></td>
                <td>支付成功后用户点击"返回商户"按钮即跳转到该地址,并GET方式带结果参数,该地址通常不作为逻辑处理,只作为信息展示</td>
            </tr>
            <tr>
                <td>密钥(key)<span>*</span></td>
                <td><input type="text" name="key" id="key" value="<?php echo SIGN_KEY; ?>" /></td>                    
                <td>签名密钥</td>
            </tr> 
            <tr>
                <td align="center" colspan="3">
                    <input type='submit' value="支 付" style="width:80px;height: 30px;" />
                </td>
            </tr>
        </table>            
    </form>
</body>
</html>




