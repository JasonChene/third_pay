import net.sf.json.JSONObject;

import javax.servlet.http.HttpServletRequest;
import java.io.UnsupportedEncodingException;
import java.net.URLEncoder;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.Random;
import java.util.UUID;

public class MainClass {
    public static  final String appid = "e1508577bacd4e4e9b85951d28d340a0";
    static double money = 0.1;
    public static int k = 0;


    public void callback(HttpServletRequest req){
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
     * @throws IllegalAccessException
     */
    public static void queeryPayStatus() throws IllegalAccessException {
        //加密接口
        JSONObject objgetEncryption = PayUtil.post("orderNo=16a2e6c07ecf4eff9cf812814a3954d1",appid,PayUtil.getEncryptionUrl);

        System.out.println("加密返回值:" + objgetEncryption.toString());
        //加密成功
        if(objgetEncryption.containsKey("status") && "OK".equals(objgetEncryption.getString("status"))){
            String ciphertext = objgetEncryption.getString("data");
            JSONObject payObjectJson = PayUtil.post(ciphertext,appid,PayUtil.queryPayStatusUrl);

            if(payObjectJson.containsKey("status") && "OK".equals(payObjectJson.getString("status"))){

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
     * @throws IllegalAccessException
     */
    public static void getPayJSON() throws IllegalAccessException {
        PayDto payDto = new PayDto();
        payDto.setAppid(appid);
        payDto.setNotifyurl("http://baidu.com");
        //建议加上自己的独特前缀，避免和其他平台传过来的商户编号重复
        payDto.setOrderid("SH-" + UUID.randomUUID().toString().toLowerCase());
        payDto.setOrderinfo("订单说明");
//        payDto.setOrdername("订单名称");
        payDto.setOrderuid("客户id");
        Random dom = new Random();
        payDto.setPaymoney(money+"");
        payDto.setIsTest("Y");
//        if(dom.nextBoolean()){
//            payDto.setPaymoney((dom.nextInt(90) + 10)+"");
//        }else{
//            String s = dom.nextInt(90)+"";
//            String s1 = (dom.nextInt(100) + 10)+"";
//            payDto.setPaymoney(s+"." + s1);
//        }
        payDto.setPaytype("11");
        payDto.setReturnurl("https://baidu.com");

        String param = ReflectUtil.getEncryptionParam(payDto);

        //加密接口
        String pa = "1810231741030000477562044&orderuid=142&paymoney=10.00&paytype=11&notifyurl=http://test-pay.baifu-tech.net/notify-4-332&returnurl=http://test-pay.baifu-tech.net/return-4-332";
        JSONObject objgetEncryption = PayUtil.post(param,appid,PayUtil.getEncryptionUrl);

        System.out.println("待加密参数:" + param);
        System.out.println("加密返回值:" + objgetEncryption.toString());
        //加密成功
        if(objgetEncryption.containsKey("status") && "OK".equals(objgetEncryption.getString("status"))){
            String ciphertext = objgetEncryption.getString("data");
            JSONObject payObjectJson = PayUtil.post(ciphertext,appid,PayUtil.getPayJsonUrl);
            if(payObjectJson == null){
                System.out.println("未拿到支付码，需要重新获取!");
                return;
            }
            System.out.println("支付接口返回值:" + payObjectJson.toString());
            if(payObjectJson.containsKey("status") && "OK".equals(payObjectJson.getString("status"))){

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
                k ++;
            }

        }

    }

    public static void main(String[] args) throws IllegalAccessException, InterruptedException, UnsupportedEncodingException {
        //支付接口
//        MainClass.getPayJSON();
        MainClass.getPayJSON();
//        String s = "";
//        for(int i=0;i<100;i++){
//            s += i + ",";
//        }
//        s = s.substring(0,s.length()-1);
//        System.out.println(s);
//        MainClass.queeryPayStatus();
        //主动查询接口
    }
}
