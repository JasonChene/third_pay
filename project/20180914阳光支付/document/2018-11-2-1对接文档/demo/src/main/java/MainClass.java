import RSA.RSAUtils;
import net.sf.json.JSONObject;
import org.apache.commons.lang3.StringUtils;

import javax.servlet.http.HttpServletRequest;
import java.util.Random;
import java.util.UUID;

public class MainClass {
    public static final String appid = "04fc2ee6a86f482f8da228dc50941765";
    public static final String rsaPrivateKey = "MIICeQIBADANBgkqhkiG9w0BAQEFAASCAmMwggJfAgEAAoGBAMI3to/W7xeiI3isTa2MnSfSgY6BmSRXnyQXyy3PCcQ5/FLRJhevLQw39G64N3x5ZBzcyMPzLHSQcfT4nmNB9+EyJNyJ3DIU6w4E2fRfq6WzZ8ynaw2GdyX12nnBimTzbWbPAX2FBwoIy/pRzumq1fjV93fXqj8F/Tk+TlPZjRWRAgMBAAECgYEAt0wVhJN5e3iOuakEdrKq75aAp5owilgX3dyG6Wjo8sU3GoJBzUCK52k3y/cYhuWaUpMc97JdVnWs12J9OXhdFApmtZvl1xNvkKodVPyutogiZg/EUOQ7e5j5iMpV834d7QT3c2ShF5W/y/xu9WGGbrCEqqoBAksgW69EXwt6oAECQQDva6dO4MDsoTJrAU0Plur+0A5N4SLJmDOc8KtS7+G9rcnz5CiTQGWfY0Ot+x37SZtRnHHmF/BI3xWv6GQbRzARAkEAz6q4wRXRxVdM81Z/LHAaax1r3E1N0jFpO0Ylf616TX/+MO/m0OC7qSJgu1L8NVXUgb4P/5hy0BzV/py1pm0NgQJBANA8mOtBDmuBlKF/IzBA+ikgeqCABUrDx3hj1w8utu/L/Q/UzPmuE/U6V/41oJEUJzQnVuViwsoOxgUWoiMbmpECQQC+RU74YtSLunxf7J2jCqe6AwADnrSx5OHlFFVhUYJgpegx0G0sbuyigEQe/l7lQ1ns8kbYSkbQFcugeNcawIqBAkEA2P76vphQHkx9+gfgfCz0mp1pT0MxHUjVStxw7ehIPP0MllWMJimzoQ6pEmkqUzW4rqhAdOU8jsUh6qVJTrlSEQ==";
    public static final String rsaPublicKey = "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDCN7aP1u8XoiN4rE2tjJ0n0oGOgZkkV58kF8stzwnEOfxS0SYXry0MN/RuuDd8eWQc3MjD8yx0kHH0+J5jQffhMiTcidwyFOsOBNn0X6uls2fMp2sNhncl9dp5wYpk821mzwF9hQcKCMv6Uc7pqtX41fd316o/Bf05Pk5T2Y0VkQIDAQAB";
    static double money = 0.1;
    public static int k = 0;



    public void callback(HttpServletRequest req) {
        String orderNo = req.getParameter("orderNo");//平台单号
        String orderid = req.getParameter("orderid");//商户单号
        String orderuid = req.getParameter("orderuid");//客户id
        String paymoney = req.getParameter("paymoney");//订单金额
        String realprice = req.getParameter("realprice");//实际付款金额
        String payStatus = req.getParameter("payStatus");//支付状态
        String createTime = req.getParameter("createTime");//创建时间
    }

    /**
     * 主动查询接口
     *
     * @throws IllegalAccessException
     */
    public static void queeryPayStatus() throws IllegalAccessException {
        //加密接口
        JSONObject objgetEncryption = PayUtil.post("orderNo=YGPAYS-0c29670d02f54734820f1a094fff3d85", appid, PayUtil.getEncryptionUrl);

        System.out.println("加密返回值:" + objgetEncryption.toString());
        //加密成功
        if (objgetEncryption.containsKey("status") && "OK".equals(objgetEncryption.getString("status"))) {
            String ciphertext = objgetEncryption.getString("data");
            JSONObject payObjectJson = PayUtil.post(ciphertext, appid, PayUtil.queryPayStatusUrl);

            if (payObjectJson.containsKey("status") && "OK".equals(payObjectJson.getString("status"))) {

                System.out.println("=============主动查询成功====================");
                System.out.println();
                JSONObject payData = payObjectJson.getJSONObject("data");

                System.out.println("orderNo:" + payData.getString("orderNo"));
                System.out.println("orderuid:" + payData.getString("orderuid"));
                System.out.println("orderid:" + payData.getString("orderid"));

                /**
                 * 支付状态：
                 * 0：待支付
                 * 1：支付成功
                 * 2：支付失败
                 */
                System.out.println("payStatus:" + payData.getString("payStatus"));

                System.out.println("createTime:" + payData.getString("createTime"));
                System.out.println("paymoney:" + payData.getString("paymoney"));
                //实际付款金额  只有支付成功这个字段才有值
                System.out.println("realprice:" + payData.getString("realprice"));
            }

        }

    }

    /**
     * 支付接口
     *
     * @throws IllegalAccessException
     */
    public static void getPayJSONNoEncryptionParam() throws Exception {
        PayDto payDto = new PayDto();
        payDto.setAppid(appid);
        payDto.setNotifyurl("http://baidu.com");
        //建议加上自己的独特前缀，避免和其他平台传过来的商户编号重复
        payDto.setOrderid("SH-" + UUID.randomUUID().toString().toLowerCase());
        payDto.setOrderinfo("订单说明");
//        payDto.setOrdername("订单名称");
        payDto.setOrderuid("客户id");
        Random dom = new Random();

        int zs = dom.nextInt(10000);
        int xs = dom.nextInt(10);
        payDto.setPaymoney(zs  + "." + xs);
        payDto.setPaytype("11");
        payDto.setReturnurl("https://baidu.com");
        payDto.setPayCodeType("payCode");
//        payDto.setSignStr(UUID.randomUUID().toString());
//        payDto.setSign(RSAUtils.sign(payDto.getSignStr().getBytes(),MainClass.rsaPrivateKey));
        //启动验签模式 参数传Y (大写)  默认不启动,或者传N(大写)
        payDto.setIsSign("Y");
        /**
         * 启动验签时，默认使用32位MD5小写
         * 也可以使用RSA
         * 参数枚举：
         * RSA
         * MD5
         */
        //MD5验签
        payDto.setSignType("MD5");

        if(StringUtils.isNotBlank(payDto.getSignType())){
            if("MD5".equals(payDto.getSignType())){
                String signStr = "appid=" + payDto.getAppid() + "&orderid=" + payDto.getOrderid();
                String sign = MD5Util.encryption(signStr);
                System.out.println("MD5签名字符串:" + signStr);
                System.out.println("MD5签名：" + sign);
                payDto.setSign(sign);
            }
        }
//        payDto.setSignType("RSA");
//        if ("RSA".equals(payDto.getSignType())) {
//            payDto.setSignStr(UUID.randomUUID().toString());
//            payDto.setSign(RSAUtils.sign(payDto.getSignStr().getBytes(), rsaPrivateKey));
//        }
        String param = ReflectUtil.getEncryptionParam(payDto);

        JSONObject payObjectJson = PayUtil.post1("N",param, appid, PayUtil.getPayJsonUrl);
        if (payObjectJson == null) {
            System.out.println("未拿到支付码，需要重新获取!");
            return;
        }
        System.out.println("支付接口返回值:" + payObjectJson.toString());
        if (payObjectJson.containsKey("status") && "OK".equals(payObjectJson.getString("status"))) {

            System.out.println("=============支付接口请求成功====================");
            JSONObject payData = payObjectJson.getJSONObject("data");

            System.out.println("orderNo:" + payData.getString("orderNo"));
            System.out.println("orderuid:" + payData.getString("orderuid"));
            System.out.println("paymoney:" + payData.getString("paymoney"));
            System.out.println("createTime:" + payData.getString("createTime"));
            /**
             * type:
             * JSON 支付码  1）使用二维码工具生成二维码放到网站，让客户扫码  每个码只能用一次，多次扫码会导致重复下单
             *              2）可以自己封装 支付宝协议，通过 webView 跳转支付宝，或者浏览器跳转支付宝
             */
            System.out.println("type:" + payData.getString("type"));
            System.out.println("payCode:" + payData.getString("payCode"));
            if("payCode".equals(payData.getString("type"))){
                k++;
            }
        }
    }

    /**
     * 加密支付
     *
     * @throws Exception
     */
    public static void getPayJSON() throws Exception {
        PayDto payDto = new PayDto();
        payDto.setAppid(appid);
        payDto.setNotifyurl("http://baidu.com");
        //建议加上自己的独特前缀，避免和其他平台传过来的商户编号重复
        payDto.setOrderid("SH-" + UUID.randomUUID().toString().toLowerCase());
        payDto.setOrderinfo("订单说明");
//        payDto.setOrdername("订单名称");
        payDto.setOrderuid("客户id");
        Random dom = new Random();
        payDto.setPaymoney(money + "");
        payDto.setPaytype("11");
        payDto.setReturnurl("https://baidu.com");

//        payDto.setSignStr(UUID.randomUUID().toString());
//        payDto.setSign(RSAUtils.sign(payDto.getSignStr().getBytes(),MainClass.rsaPrivateKey));
        //启动验签模式 参数传Y (大写)  默认不启动,或者传N(大写)
        payDto.setIsSign("Y");
        /**
         * 启动验签时，默认使用32位MD5小写
         * 也可以使用RSA
         * 参数枚举：
         * RSA
         * MD5
         */
        //MD5验签
//        payDto.setSignType("MD5");
//
//        if(StringUtils.isNotBlank(payDto.getSignType())){
//            if("MD5".equals(payDto.getSignType())){
//                String signStr = "appid=" + payDto.getAppid() + "&orderid=" + payDto.getOrderid();
//                String sign = MD5Util.encryption(signStr);
//                System.out.println("MD5签名字符串:" + signStr);
//                System.out.println("MD5签名：" + sign);
//                payDto.setSign(sign);
//            }
//        }
        payDto.setSignType("RSA");
        if ("RSA".equals(payDto.getSignType())) {
            payDto.setSignStr(UUID.randomUUID().toString());
            payDto.setSign(RSAUtils.sign(payDto.getSignStr().getBytes(), rsaPrivateKey));
        }
        String param = ReflectUtil.getEncryptionParam(payDto);

        //加密接口
//        String pa = "1810231741030000477562044&orderuid=142&paymoney=10.00&paytype=11&notifyurl=http://test-pay.baifu-tech.net/notify-4-332&returnurl=http://test-pay.baifu-tech.net/return-4-332";
        JSONObject objgetEncryption = PayUtil.post(param, appid, PayUtil.getEncryptionUrl);

        System.out.println("待加密参数:" + param);
        System.out.println("加密返回值:" + objgetEncryption.toString());
        //加密成功
        if (objgetEncryption.containsKey("status") && "OK".equals(objgetEncryption.getString("status"))) {
            String ciphertext = objgetEncryption.getString("data");
            JSONObject payObjectJson = PayUtil.post(ciphertext, appid, PayUtil.getPayJsonUrl);
            if (payObjectJson == null) {
                System.out.println("未拿到支付码，需要重新获取!");
                return;
            }
            System.out.println("支付接口返回值:" + payObjectJson.toString());
            if (payObjectJson.containsKey("status") && "OK".equals(payObjectJson.getString("status"))) {

                System.out.println("=============支付接口请求成功====================");
                System.out.println();
                JSONObject payData = payObjectJson.getJSONObject("data");

                System.out.println("orderNo:" + payData.getString("orderNo"));
                System.out.println("orderuid:" + payData.getString("orderuid"));
                System.out.println("paymoney:" + payData.getString("paymoney"));
                System.out.println("createTime:" + payData.getString("createTime"));
                /**
                 * type:
                 * JSON 支付码  1）使用二维码工具生成二维码放到网站，让客户扫码  每个码只能用一次，多次扫码会导致重复下单
                 *              2）可以自己封装 支付宝协议，通过 webView 跳转支付宝，或者浏览器跳转支付宝
                 */
                System.out.println("type:" + payData.getString("type"));
                System.out.println("payCode:" + payData.getString("payCode"));
                k++;
            }

        }

    }

    public static void main(String[] args) throws Exception {
        //验签测试接口
//        String signStr = UUID.randomUUID().toString();
//        PayUtil.rsaVerify(PayUtil.rsaVerify,appid,signStr,RSAUtils.sign(signStr.getBytes(),rsaPrivateKey));
        MainClass mainClass = new MainClass();
//        mainClass.getPayJSON();//加密模式


        for(int i=0;i<100;i++){
            mainClass.getPayJSONNoEncryptionParam();//不加密请求支付
            System.out.println("第：" + i + "次取码，成功获取：" + k + "次");
            Thread.sleep(3000);
        }
    }
}
