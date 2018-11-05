import httpclient.SimpleHttpUtils;
import net.sf.json.JSONObject;
import org.apache.commons.lang3.StringUtils;

import java.util.HashMap;
import java.util.Map;

public class PayUtil {

//    public static  final String getEncryptionUrl = "http://www.jings.wang/api/pay/getEncryption";
//    public static  final String getPayJsonUrl = "http://www.jings.wang/api/pay/getPayJson";
//    public static  final String queryPayStatusUrl = "http://www.jings.wang/api/pay/queryPayStatus";
//    public static  final String rsaVerify = "http://www.jings.wang/api/pay/rsaVerify";
//    public static  final String getEncryptionUrl = "http://www.gtyhw.com/api/pay/getEncryption";
//    public static  final String getPayJsonUrl = "http://www.gtyhw.com/api/pay/getPayJson";
//    public static  final String queryPayStatusUrl = "http://www.gtyhw.com/api/pay/queryPayStatus";
////
    public static  final String getEncryptionUrl = "http://127.0.0.1:3001/pay/getEncryption";
    public static  final String getPayJsonUrl = "http://127.0.0.1:3001/pay/getPayJson";
    public static  final String queryPayStatusUrl = "http://127.0.0.1:3001/pay/queryPayStatus";
    public static  final String rsaVerify = "http://127.0.0.1:3001/pay/rsaVerify";
    public static JSONObject rsaVerify(String url,String appid,String signStr,String sign){
        Map<String,Object> map = new HashMap<String,Object>();
        map.put("appid",appid);
        map.put("signStr",signStr);
        map.put("sign",sign);
        String result = SimpleHttpUtils.httpPost(url,map,30000);
        if(StringUtils.isNotBlank(result)){
            System.out.println(result);
            return JSONObject.fromObject(result);
        }else{
            System.out.println("返回值为空!");
            return null;
        }
    }
    public static JSONObject post1(String isEncryption,String params,String appid,String url){
        Map<String,Object> map = new HashMap<String,Object>();
        map.put("appid",appid);
        map.put("params",params);
        if(StringUtils.isNotBlank(isEncryption)){
            map.put("isEncryption",isEncryption);
        }
        /**
         * 10秒超时
         */
//        String result = HttpUtil.sendPost(url,map);
        String result = SimpleHttpUtils.httpPost(url,map,30000);
        if(StringUtils.isNotBlank(result)){
            return JSONObject.fromObject(result);
        }else{
            System.out.println("返回值为空!");
            return null;
        }
    }
    public static JSONObject post(String params,String appid,String url){
        Map<String,Object> map = new HashMap<String,Object>();
        map.put("appid",appid);
        map.put("params",params);
        /**
         * 10秒超时
         */
//        String result = HttpUtil.sendPost(url,map);
        String result = SimpleHttpUtils.httpPost(url,map,30000);
        if(StringUtils.isNotBlank(result)){
            return JSONObject.fromObject(result);
        }else{
            System.out.println("返回值为空!");
            return null;
        }
    }
}
