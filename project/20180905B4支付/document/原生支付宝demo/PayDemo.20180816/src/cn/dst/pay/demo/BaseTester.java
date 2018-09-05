package cn.dst.pay.demo;

import cn.dst.pay.signature.HmacSHA1Signature;
import cn.dst.pay.signature.SignatureUtil;
import cn.dst.pay.utils.Constants;
import cn.dst.pay.utils.HttpsUtils;
import cn.dst.pay.utils.PropKit;

import java.io.File;
import java.io.IOException;
import java.io.UnsupportedEncodingException;
import java.security.SignatureException;
import java.util.Calendar;
import java.util.Date;
import java.util.Map;

public abstract class BaseTester {

//    private static final String serverUrl = "http://127.0.0.1"; // 开发环境
    private static final String serverUrl = "http://dst.sssx.red:9988"; // 测试环境

    private static final HmacSHA1Signature hmacSHA1Signature = new HmacSHA1Signature();

    static {
        PropKit.use(new File(System.getProperty("user.home"), "user.properties"));
    }
    
    protected static String get(String apiUri, Map<String, String> params) throws IOException, SignatureException {
    	return HttpsUtils.get(serverUrl + apiUri, null, signParams(params));
    }
    
    protected static String post(String apiUri, Map<String, String> params) throws IOException, SignatureException {
    	return HttpsUtils.post(serverUrl + apiUri, null, signParams(params));
    }
    
    private static Map<String, String> signParams(Map<String, String> params) throws IOException, SignatureException {
    	params.remove("signature");
        // 系统级别参数 merchantId、timestamp、signature
        params.put("merchantId", PropKit.get("merchantId"));
        params.put("timestamp", String.valueOf(new Date().getTime()));
        String content = SignatureUtil.getSignatureContent(params, true);
        String sign = hmacSHA1Signature.sign(content, PropKit.get("secretKey"), Constants.CHARSET_UTF8);
        params.put("signature", sign);
        return params;
    }
    
}
