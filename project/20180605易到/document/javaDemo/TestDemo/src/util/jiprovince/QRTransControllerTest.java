package util.jiprovince;

import com.alibaba.fastjson.JSON;

import java.util.*;

/**
 * Created by TEST on 2017/4/22.
 */
public class QRTransControllerTest {
    //测试55
    public static final String TEST_PRIK = "MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBALlc62utJsnErA7R2BQhwyny8JH6Q8AyPuLIVL/KQOsrp4o5aSR/Be/qhsLcPRdJeyqmjLW++rh3pvSRvFIC7NFaJwR51B8U6OcAzFPrRuASLmLmG0xusj5wjKtI4m7FnPV8hqtEiT4tfyeF5gg02czDKcQYrgYC/RsArsw/ysMZAgMBAAECgYEAjxwkTl/CwKhtRovQzco7SZndDncv86VXU/PjKqfWczqjAV7NxHWledOR88PiDqaWxxkLLg6it+T5K32Q7aiAeNk5RACAgczm+K8p9O2/j04QmG38cFV81ElVqWlHe/SGZg/3FX5fyEhOkaBR+hSCGaQelKe9QVbv1x0uzFXo+QECQQDeF8MHx+s0JZVmXdXBqUl3oVQnnClrLCM8WMSPFclgmfdZMnnFVWWZprMJ5gcAKkU5TNChHCktx4fcD14SHUhxAkEA1amdS+/gy0bGx8LqPBch0o9fUY+681kML8AqsLAS7rV9DvlMHM8rQlz7zQt/cpzmT880nx19KsT13mXDyAk5KQJAG11cJ3pHjb5PwTQwoVMFfVsAbnz8UXs3wDjDx0mM7X0rD+97N4hFI4B5sO+Jz0hmDSBc2G0K2dwq7j9qfNrk0QJAAWiM0ONT6AMfbFGsmZjNcEXhqvf3k4MSwX3Syjde6JPprx+VkNsMvfM+9asNvAOswPnsrt/S42VI+Z5SHA9zgQJBANvpMKJwVjL1Jcu+7JBeZobclsgt6L+ZKasmxzvGBHpg+e83mK8rVfqvo6dPrB4Pb8MlN6J840TwsJ45FS+qTtk=";

    //钱包系统注册接口
    public void testRemoteMapRegist() throws Exception {

        //1 在map中组数据
        Map reqMap = new HashMap();

        reqMap.put("pmsBankNo", "308100005027");//银联号
        reqMap.put("certNo", "410329196912074515");//证件号
        reqMap.put("mobile", "18600069850");//开户时绑定手机号码
        reqMap.put("password", "ZSF13366660062");//密码
        reqMap.put("cardNo", "110915120810903");//结算卡号
        reqMap.put("orgId", "00000055");//机构号
        reqMap.put("realName","何先");//开户姓名
        reqMap.put("account", "18600069950");//账户号(11位手机号)
        reqMap.put("mchntName", "云餐饮管理有限公司");//商户名称
        reqMap.put("consumFeeType", 0);//QR消费手续费类型 0表示费率
        reqMap.put("consumFeeRate", 0.003);//QR消费手续费费率
        reqMap.put("drawingFeeType", 2);//QR提款手续费类型 0表示费率，2表示增值费
        reqMap.put("drawingFeeRate", "0.009");//QR提款手续费费率 提款手续费类型为0 的时候必填
        reqMap.put("drawingFee", 200);//QR提款手续费增值费 提款手续费类型为2的时候必填
        reqMap.put("drawingAdd", 0.0);//QR商户提款提款附加费
        reqMap.put("qT0ConsumeRate", "0.0038");//商户快捷T0手续费
        reqMap.put("qT1ConsumeRate", "0.0038");//商户快捷T1手续费


        //2 进行排序
        Map<String, Object> sPara = paraFilter(reqMap);
        // 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
        String prestr = createLinkString(sPara);
        //输出待签名字符串
        System.out.println("待签名字符串: " + prestr);
        //计算签名并添加到map中
        reqMap.put("signature", MyRSAUtils.sign(TEST_PRIK ,prestr, "MD5withRSA"));
        String url = "http://127.0.0.1:8080/paypro-api/v1.0/paypro/mchnt/regist";

        //打印完整json
        System.out.println("请求报文; " + JSON.toJSONString(reqMap));
        //发送http POST 请求
        HttpResponse result = HttpClientHelper.doHttp(url, HttpClientHelper.POST, "utf-8", JSON.toJSONString(reqMap), "60000", "application/json;charset=UTF-8");
        //输出http返回报文
        System.out.println("响应报文: " + (result != null ? result.getRspStr() : ""));

        //返回验证
        Map<String, String> parseMap = null;
        parseMap = JSON.parseObject(result.getRspStr(), Map.class);
        String sign = parseMap.get("signature");
        parseMap.remove("signature");
        String signStr = SignatureUtil.hex(parseMap);
        System.out.println("signStr " +signStr);
        boolean flag = MyRSAUtils.verifySignature(Constant.PUBLIC_KEY, sign, signStr, MyRSAUtils.MD5_SIGN_ALGORITHM);
        System.out.println("flag " + flag);
    }


    
    public void testOrderMerRegistQuery() throws Exception {
        //1 在map中组数据
        Map reqMap = new HashMap();
        reqMap.put("account", "15313653526");
        reqMap.put("orgId", "00000055");
        //2 进行排序
        Map<String, Object> sPara = paraFilter(reqMap);
        String prestr = createLinkString(sPara);
        System.out.println("待签名字符串: " + prestr);
        reqMap.put("signature", MyRSAUtils.sign(TEST_PRIK , prestr, "MD5withRSA"));
        //输出签名字符串
        String url = "http://127.0.0.1:8080/paypro-api/v1.0/paypro/mchnt/query";
        HttpResponse result = HttpClientHelper.doHttp(url, HttpClientHelper.POST, "utf-8", JSON.toJSONString(reqMap), "60000", "application/json;charset=UTF-8");
        //输出http返回报文
        System.out.println("响应报文: " + (result != null ? result.getRspStr() : ""));

        //返回验证

        Map<String, String> parseMap = null;
        parseMap = JSON.parseObject(result.getRspStr(), Map.class);
        String sign = parseMap.get("signature");
        parseMap.remove("signature");
        String signStr = SignatureUtil.hex(parseMap);
        System.out.println("signStr " +signStr);
        boolean flag = MyRSAUtils.verifySignature(Constant.PUBLIC_KEY, sign, signStr, MyRSAUtils.MD5_SIGN_ALGORITHM);
        System.out.println("flag " + flag);
    }

//二维码订单支付

    
    public void testOrderPayQuery() throws Exception {
        //1 在map中组数据
        Map reqMap = new HashMap();

        reqMap.put("orgId", "00000055");
        reqMap.put("source", "1");//必填，0:微信,1:支付宝 2:手Q
        reqMap.put("settleAmt", 0);//必填，清算金额，不能大于收款本金减去平台手续费
        reqMap.put("subject", "测试商户"); //商品标题
        reqMap.put("account", "18600069850"); //账户 必填，11为手机号码
        reqMap.put("amount", 100); //订单总金额  单位 分
        reqMap.put("notifyUrl", "http://127.0.0.1:8080/testNotify");//回调通知地址
        reqMap.put("tranTp", "0");//交易类型 必填，0：T0，1：T1
        reqMap.put("orderTp", "0");  //支付方式  必填，0：非固定码，1：固定码
        reqMap.put("orgOrderNo", System.currentTimeMillis()+""); //必填


        //2 进行排序
        Map<String, Object> sPara = paraFilter(reqMap);
        //拼接报文
        String prestr = createLinkString(sPara);

        System.out.println("待签名字符串: " + prestr);
        reqMap.put("signature", (MyRSAUtils.sign(TEST_PRIK, prestr, "MD5withRSA")));

        String url = "http://127.0.0.1:8080/paypro-api/v1.0/paypro/order/qrpay";
        //打印完整json
        System.out.println("请求报文; " + JSON.toJSONString(reqMap));
        HttpResponse result = HttpClientHelper.doHttp(url, HttpClientHelper.POST, "utf-8", JSON.toJSONString(reqMap), "60000", "application/json;charset=UTF-8");
        //输出http返回报文
        System.out.println("响应报文: " + (result != null ? result.getRspStr() : ""));

        //返回验证

        Map<String, String> parseMap = null;
        parseMap = JSON.parseObject(result.getRspStr(), Map.class);
        String sign = parseMap.get("signature");
        parseMap.remove("signature");
        String signStr = SignatureUtil.hex(parseMap);
        System.out.println("signStr " +signStr);
        boolean flag = MyRSAUtils.verifySignature(Constant.PUBLIC_KEY, sign, signStr, MyRSAUtils.MD5_SIGN_ALGORITHM);
        System.out.println("flag " + flag);

    }

    
//支付结果查询

    public void testOrderPayResultQuery() throws Exception {
        //1 在map中组数据
        Map reqMap = new HashMap();

        reqMap.put("orgId", "00000055");
        reqMap.put("orgOrderNo","1492997155927");
        //2 进行排序
        Map<String, Object> sPara = paraFilter(reqMap);
        //拼接报文
        String prestr = createLinkString(sPara);
        System.out.println("待签名字符串: " + prestr);
        reqMap.put("signature", (MyRSAUtils.sign(TEST_PRIK, prestr, "MD5withRSA")));

        String url = "http://127.0.0.1:8080/paypro-api/v1.0/paypro/order/query";

        //打印完整json
        System.out.println("请求报文; " + JSON.toJSONString(reqMap));
        HttpResponse result = HttpClientHelper.doHttp(url, HttpClientHelper.POST, "utf-8", JSON.toJSONString(reqMap), "60000", "application/json;charset=UTF-8");
        //输出http返回报文
        System.out.println("响应报文: " + (result != null ? result.getRspStr() : ""));
        //返回验证
        Map<String, String> parseMap = null;
        parseMap = JSON.parseObject(result.getRspStr(), Map.class);
        String sign = parseMap.get("signature");
        parseMap.remove("signature");
        String signStr = SignatureUtil.hex(parseMap);
        System.out.println("signStr " +signStr);
        boolean flag = MyRSAUtils.verifySignature(Constant.PUBLIC_KEY, sign, signStr, MyRSAUtils.MD5_SIGN_ALGORITHM);
        System.out.println("flag " + flag);

    }


    private static Map<String, Object> paraFilter(Map<String, Object> sArray) {
        Map<String, Object> result = new HashMap<String, Object>();
        if (sArray == null || sArray.size() <= 0) {
            return result;
        }

        for (String key : sArray.keySet()) {
            Object value = sArray.get(key);
            if (value == null || value.equals("")) {
                continue;
            }
            result.put(key, value);
        }
        return result;
    }

    public static String createLinkString(Map<String, Object> params) {
        List<String> keys = new ArrayList<String>(params.keySet());
        Collections.sort(keys);
        String prestr = "";
        for (int i = 0; i < keys.size(); i++) {
            String key = keys.get(i);
            Object value = params.get(key);
            if (i == keys.size() - 1) {// 拼接时，不包括最后一个&字符
                prestr = prestr + key + "=" + value;
            } else {
                prestr = prestr + key + "=" + value + "&";
            }
        }
        return prestr;
    }



}

