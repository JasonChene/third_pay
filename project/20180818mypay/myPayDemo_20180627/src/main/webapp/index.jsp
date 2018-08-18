<%@ page import="java.text.DecimalFormat" %>
<%@ page import="java.text.SimpleDateFormat" %>
<%@ page import="java.util.Date" %>
<%@ page language="java" contentType="text/html; charset=UTF-8"
         pageEncoding="UTF-8" %>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>mypay Demo</title>
    <style type="text/css">
        input[type='text'] {
            width: 300px;
        }

        #submit {
            width: 60px;
        }

        tr td:first-child {
            text-align: right;
            font-size: larger;
        }

        tr td:nth-child(3) {
            text-align: left;
        }

    </style>
    <script src="./static/jquery-1.12.1.min.js"></script>
</head>
<body>

<div id="app">
    <%

        SimpleDateFormat tradeDateFormat = new SimpleDateFormat("yyyyMMddHHmmss");

        // 接口版本
        String version = "V1.0";
        // 商户账号
        String merId = "IIH001";
        //机构号
        String orgId = "IIH";
        // 支付類型(3. 微信)
        String payType = "3";
        // 商戶订单号
        String merchantNo = "cu" + new Date().getTime(); //唯一訂單號
        // 支付裝置
        String terminalClient = "pc";
        // 交易日期
        String tradeDate = tradeDateFormat.format(new Date());
        // 订单金额
        double money = 0.10d;
        DecimalFormat df = new DecimalFormat("0.00");
        String amount = "" + df.format(money);
        //扩展字段
        String extra = "";
        // 客户端IP
        String clientIp = "127.0.0.1";
        // 银行代码
        String bankId = "";
        // 支付结果通知地址
        String notifyUrl = "http://x.x.x.x/myPayDemo/notifyUrl";

        // ----------------------------------

        // ----------------------------------
        request.setAttribute("version", version);
        request.setAttribute("merId", merId);
        request.setAttribute("orgId", orgId);
        request.setAttribute("payType", payType);
        request.setAttribute("merchantNo", merchantNo);
        request.setAttribute("terminalClient", terminalClient);
        request.setAttribute("tradeDate", tradeDate);
        request.setAttribute("extra", extra);
        request.setAttribute("clientIp", clientIp);
        request.setAttribute("bankId", bankId);
        request.setAttribute("notifyUrl", notifyUrl);


    %>

    <form id="form1" method="post" action="http://testapi.mypay2.com/rd/apiOrder/sendOrder.zv">


        <table>
            <tr>
                <td>接口版本:</td>
                <td><input type="text" name="version" id="version" value="<%=version%>">
                </td>
                <td>固定值：V1.0</td>
            </tr>
            <tr>
                <td>商户账号:</td>
                <td><input type="text" name="merId" id="merId" value="<%=merId%>"></td>
                <td>商户在支付平台的唯一标识</td>
            </tr>
            <tr>

                <td>机构号:</td>
                <td><input type="text" name="orgId" id="orgId" value="<%=orgId%>"></td>
                <td>商户在支付平台的唯一机构号</td>
            </tr>
            <tr>
                <td>支付類型:</td>
                <td><input type="text" name="payType" id="payType" value="<%=payType%>">
                </td>
                <td>1；支付宝
                    2: 支付寶app
                    3. 微信
                    4. 微信app
                </td>
            </tr>
            <tr>
                <td>商戶订单号:</td>
                <td>
                    <input type="text" name="merchantNo" id="merchantNo" value="<%=merchantNo%>"
                           style="width: 60%">
                    <input type="button" id="getMerchantNo" value="重新生成商戶订单号"/>
                </td>
                <td>商戶系统产生的唯一订单号，此订单号不可重复</td>
            </tr>
            <tr>
                <td>支付裝置:</td>
                <td><input type="text" name="terminalClient" id="terminalClient" value="<%=terminalClient%>"
                >
                </td>
                <td>電腦:"pc"
                    手機:"wap"
                </td>
            </tr>
            <tr>
                <td>交易日期:</td>
                <td><input type="text" name="tradeDate" id="tradeDate" value="<%=tradeDate%>"
                ></td>
                <td>订单日期：yyyyMMddHHmm</td>
            </tr>
            <tr>
                <td>订单金额:</td>
                <td><input type="text" name="amount" id="amount" value="<%=amount%>"></td>
                <td>订单金额:2.00(必须填写两位小数)</td>
            </tr>
            <tr>
                <td>扩展字段:</td>
                <td><input type="text" name="extra" id="extra" value="test"></td>
                <td>原样返回</td>
            </tr>
            <tr>
                <td>客户端IP:</td>
                <td><input type="text" name="clientIp" id="clientIp" value="<%=clientIp%>"
                ></td>
                <td>付款人IP 地址，商户传递获取到的客户端IP</td>
            </tr>
            <tr>
                <td>银行代码:</td>
                <td><input type="text" name="bankId" id="bankId" value=""></td>
                <td>参考银行代码表</td>
            </tr>
            <tr>
                <td>支付结果通知地址:</td>
                <td><input type="text" name="notifyUrl" id="notifyUrl"
                           value="<%= notifyUrl%>"></td>
                <td>回调地址</td>
            </tr>
            <tr>
                <td>签名:</td>
                <td><input type="text" name="sign" id="sign" value=""></td>
                <td>詳見簽名規則</td>
            </tr>
            <tr>
                <td>签名方式:</td>
                <td>
                    <input type="text" name="signType" id="signType" value="MD5">

                </td>
                <td>
                    签名方式(MD5/RSA)
                </td>
            </tr>
            <tr>
                <td><input type="button" id="getMd5" value="生成md5"/></td>
                <td><input type="submit" id="submit" name="submit" value="提交订单" style="width: 40%"/></td>
            </tr>

        </table>

    </form>


    <script type="text/javascript">

        $(document).ready(function () {
            $("#getMd5").click(function (e) {
                e.preventDefault();

                var formData = $("#form1").serialize();
                console.log(formData);
                $.ajax({
                    url: "md5Sign",
                    type: "POST",
                    dataType: 'json',
                    data: formData,
                    success: function (result) {
                        console.log(result);
                        $("#sign").val(result.sign);
                    }
                });

            });

            $("#getMerchantNo").click(function (e) {
                e.preventDefault();
                var d = "cu" + new Date().getTime(); //唯一訂單號
                $("#merchantNo").val(d);
                $("#getMd5").trigger('click');


            });

        });


    </script>


</div>
</body>
</html>




