<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head id="Head1" >
    <title></title>
   
</head>
<body>
    <form action="pay.php" method="post" id="form1" >
    <div>
    
        <table class="style1">
            <tr>
                <td class="style2">
                    APPID:</td>
                <td class="style3">
                    <input name="txtappid" type="text" ID="txtappid" value="10000001"  Width="214px">
                </td>
            </tr>
            <tr>
                <td class="style2">
                    APPKEY:</td>
                <td class="style3">
                    <input type="text" ID="txtKey" name="txtKey" value="a006e912ceb3eb4d9682d9aa6b47b291"  Width="214px"></td>
            </tr>
            <tr>
                <td class="style2">
                    支付类型:</td>
                <td class="style3">
                    <input name="txtpaytype" type="text" ID="txtpaytype" value="1" >
                </td>
            </tr>
            <tr>
                <td class="style2">
                    订单金额:</td>
                <td class="style3">
                    <input name="txtpaymoney" type="text" ID="txtpaymoney" value="100" >
                </td>
            </tr>
            <tr>
                <td class="style2">
                    订单号码:</td>
                <td class="style3">
                    <input name="txtordernumber" type="text" ID="txtordernumber" value="<?php echo date("YmdHis")?>" >
                </td>
            </tr>
            <tr>
                <td class="style2">
                    异步通知地址:</td>
                <td class="style3">
                    <input name="txtcallbackurl" type="text" ID="txtcallbackurl" value="http://www.xxx.com/callback.php"  Width="362px">
                </td>
            </tr>
            <tr>
                <td class="style2">
                    备注信息:</td>
                <td class="style3">
                    <input name="txtattach" type="text" ID="txtattach" value="mygod" >
                </td>
            </tr>
            <tr>
                <td class="style2">&nbsp;
                    </td>
                <td class="style3">
                    
                    <input type="submit"  value="提交支付"/>
                </td>
            </tr>
            <tr>
                <td class="style2">&nbsp;
                    </td>
                <td class="style3">&nbsp;
                    </td>
            </tr>
            <tr>
                <td class="style2">&nbsp;
                    </td>
                <td class="style3">&nbsp;
                    </td>
            </tr>
        </table>
    
    </div>
    </form>
</body>
</html>
