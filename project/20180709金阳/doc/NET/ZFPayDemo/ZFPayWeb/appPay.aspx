<%@ Page Language="C#" AutoEventWireup="true" CodeBehind="appPay.aspx.cs" Inherits="ZFPayWeb.appPay" %>
<%@ Import Namespace="ZFPayWeb.Common" %>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title></title>
</head>
<body>
    <form action="./appConfirm.aspx" id="payForm" name="payForm" method="post">
        <div>
            <table class="style1">
                <tr>
                    <td class="style2">商户ID:</td>
                    <td class="style3">
                        <input type="text" name="p1_mchtid" value="<%=Config.P1_MCHTID %>" />

                    </td>
                </tr>
                <tr>
                    <td class="style2">支付类型:</td>
                    <td class="style3">
                        <select name="p2_paytype" id="p2_paytype">
                            <option value="QQPAYWAP" selected="selected">QQ支付WAP</option>
                            <option value="QQPAY">QQ支付</option>
                            <option value="WEIXINWAP">微信手机WAP</option>
                            <option value="WEIXIN">微信</option>
                            <option value="ALIPAYWAP">支付宝手机WAP</option>
                            <option value="ALIPAY">支付宝</option>
                            <option value="JDPAYWAP">京东支付手机WAP</option>
                            <option value="JDPAY">京东支付</option>
                            <option value="BAIDUPAY">百度钱包</option>
                            <option value="BAIDUPAYWAP">百度钱包WAP</option>
                            <option value="UNIONPAY">银联钱包</option>
                            <option value="UNIONPAYWAP">银联钱包WAP</option>
                            <option value="ICBC">工商银行</option>
                            <option value="ABC">农业银行</option>
                            <option value="CCB">建设银行</option>
                            <option value="BOC">中国银行</option>
                            <option value="CMB">招商银行</option>
                            <option value="BCCB">北京银行</option>
                            <option value="BOCO">交通银行</option>
                            <option value="CIB">兴业银行</option>
                            <option value="NJCB">南京银行</option>
                            <option value="CMBC">民生银行</option>
                            <option value="CEB">光大银行</option>
                            <option value="PINGANBANK">平安银行</option>
                            <option value="CBHB">渤海银行</option>
                            <option value="HKBEA">东亚银行</option>
                            <option value="NBCB">宁波银行</option>
                            <option value="CTTIC">中信银行</option>
                            <option value="GDB">广发银行</option>
                            <option value="SHB">上海银行</option>
                            <option value="SHB">上海银行</option>
                            <option value="SPDB">上海浦东发展银行</option>
                            <option value="PSBS">中国邮政</option>
                            <option value="HXB">华夏银行</option>
                            <option value="BJRCB">北京农村商业银行</option>
                            <option value="SRCB">上海农商银行</option>
                            <option value="SDB">深圳发展银行</option>
                            <option value="CZB">浙江稠州商业银行</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="style2">加密类型:</td>
                    <td class="style3">
                        <select name="p8_signtype" id="p8_signtype">
                            <option value="1" selected="selected">MD5</option>

                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="style2">订单金额:</td>
                    <td class="style3">
                        <input type="text" name="p3_paymoney" value="1.10" />
                    </td>
                </tr>
                <tr>
                    <td class="style2">版本号：</td>
                    <td class="style3">
                        <input type="text" name="p7_version" value="v2.8" />
                    </td>
                </tr>
                <tr>
                    <td class="style2">订单号码:</td>
                    <td class="style3">
                        <input type="text" name="p4_orderno" value="<%=DateTime.Now.ToString("yyyyMMddHHmmss") %>" />

                    </td>
                </tr>

                <tr>
                    <td class="style2">异步通知地址:</td>
                    <td class="style3">

                        <input type="text" name="p5_callbackurl" value="http://<%=System.Web.HttpContext.Current.Request.Url.Authority %>/PayCallback.aspx" />
                    </td>
                </tr>
                <tr>
                    <td class="style2">同步跳转地址:</td>
                    <td class="style3">
                        <input type="text" name="p6_notifyurl" value="http://<%=System.Web.HttpContext.Current.Request.Url.Authority %>/jump.aspx" />
                    </td>
                </tr>
                <tr>
                    <td class="style2">是否显示收银台:</td>
                    <td class="style3">
                        <select name="p11_isshow" id="p11_isshow">
                            <option value="0" selected="selected">不显示PC收银台</option>
                            <option value="1">显示PC收银台</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td class="style2">商户用户请求IP&nbsp;</td>
                    <td class="style3">
                        <input type="text" name="p12_orderip" value="127.0.0.1" />
                        &nbsp;
                    </td>
                </tr>

                <tr>
                    <td class="style2">备注信息:</td>
                    <td class="style3">
                        <input type="text" name="p9_attach" value="attach" />
                    </td>
                </tr>

                <tr>
                    <td class="style2">分成标识：</td>
                    <td class="style3">
                        <input type="text" name="p10_appname" value="appname" />
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <td class="style2">&nbsp;</td>
                    <td class="style3">
                        <input type="submit" value="提交" />
                    </td>
                </tr>
                <tr>
                    <td class="style2">&nbsp;</td>
                    <td class="style3">&nbsp;</td>
                </tr>
                <tr>
                    <td class="style2">&nbsp;</td>
                    <td class="style3">&nbsp;</td>
                </tr>
            </table>
        </div>
    </form>
</body>
</html>
