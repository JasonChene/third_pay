<?php
include 'config.php';
/*
 * 订单查询页面
 */
?>

<!DOCTYPE html>
<html>
    <head>
        <title>订单查询</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="Public/css/common.css" rel="stylesheet" type="text/css"/>        
        <script src="Public/js/jquery.min.js" type="text/javascript"></script>
        <script>
            $(function () {
                $("#btnSubmit").click(function () {
                    if ($("#orderNo").val() &&
                            $("#userId").val() &&
                            $("#key").val())
                    {
                        document.form.submit();
                    } else
                    {
                        alert("*必填项，请正确填写！");
                        return false;
                    }
                    return false;
                });
            });
        </script>       
    </head>
    <body>
        <form name='form' method="post" action="query_action.php" target="_blank">
            <h1>订单查询</h1>                      
            <table width="100%" border="0" align="center">
                <colgroup>
                    <col width="27%" align="left" />
                    <col width="25%" align="left"/>
                    <col width="48%" align="left"/>                
                </colgroup>
                <tr>
                    <td align="center" colspan="3">
                        <a href="pay.php" target="_blank">支付</a>&nbsp;&nbsp;查询
                    </td>
                </tr>
                <tr>
                    <td>商户号(userId)<span>*</span></td>
                    <td><input type="text" name="userId" id="userId" value="" /></td>
                    <td>提供给商户的ID</td>
                </tr>
                <tr>
                    <td>商户订单号(orderNo)<span>*</span></td>
                    <td><input type="text" name="orderNo" id="orderNo" value="" /></td>
                    <td>商户订单号<span>(要保证唯一)</span>最长50字符</td>
                </tr>
                <tr>
                    <td>密钥(key)<span>*</span></td>
                    <td><input type="text" name="key" id="key" value="<?php echo SIGN_KEY; ?>" /></td>                    
                    <td>签名密钥，使用订单支付类型对应的密钥</td>
                </tr>
                <tr>
                    <td align="center" colspan="3">                            
                        <input type="button" id='btnSubmit' value="查 询"  style="width:80px;height: 30px;" />
                    </td>
                </tr>
            </table>            
        </form>
    </body>
</html>

