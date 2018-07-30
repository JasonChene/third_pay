<%@ page contentType="text/html;charset=UTF-8" language="java" %>
<%@ page import="com.alibaba.fastjson.JSON" %>
<%@ page import="org.apache.commons.lang.StringUtils" %>
<%@ page import="org.slf4j.Logger" %>
<%@ page import="org.slf4j.LoggerFactory" %>
<%@ page import="javax.net.ssl.HttpsURLConnection" %>
<%@ page import="java.io.ByteArrayOutputStream" %>
<%@ page import="java.io.InputStream" %>
<%@ page import="java.io.OutputStream" %>
<%@ page import="java.net.HttpURLConnection" %>
<%@ page import="java.net.URL" %>
<%@ page import="java.net.URLEncoder" %>
<%@ page import="java.security.MessageDigest" %>
<%@ page import="java.util.*" %>
<%@ page import="java.util.regex.Matcher" %>
<%@ page import="java.util.regex.Pattern" %>
<%!
    private static Logger log = LoggerFactory.getLogger("AgentPay");


    private static final String[] hex = { "0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "a", "b", "c", "d", "e", "f" };


    public static String toParams(Map<String,Object>params,StringBuffer debugStr){
        StringBuffer sbResult=new StringBuffer();

        Set<String> names=params.keySet();
        for(String name:names){
            String value=params.get(name).toString();
            try {
                if(sbResult.length()>0){
                    sbResult.append("&");
                    debugStr.append("&");
                }
                sbResult.append(name + "=" + URLEncoder.encode(value, "utf-8"));
                debugStr.append(name + "=" + value);
            }
            catch(Throwable e){
            }
        }
        return sbResult.toString();
    }

    public static String encode(String password) {
        try {
            MessageDigest md5 = MessageDigest.getInstance("MD5");
            byte[] byteArray = md5.digest(password.getBytes("utf-8"));
            String passwordMD5 = byteArrayToHexString(byteArray);
            return passwordMD5;
        } catch (Exception e) {
            log.error(e.toString());
        }
        return password;
    }

    private static String byteArrayToHexString(byte[] byteArray) {
        StringBuffer sb = new StringBuffer();
        for (byte b : byteArray) {
            sb.append(byteToHexChar(b));
        }
        return sb.toString();
    }

    private static Object byteToHexChar(byte b) {
        int n = b;
        if (n < 0) {
            n = 256 + n;
        }
        int d1 = n / 16;
        int d2 = n % 16;
        return hex[d1] + hex[d2];
    }

    public static String  getSign (Map<String , Object> paramMap , String paySecret){
        Map<String, Object> smap = new TreeMap<String, Object>();
        smap.putAll(paramMap);
        StringBuffer stringBuffer = new StringBuffer();
        Set<String>keys=smap.keySet();
        for (String key:keys) {
            Object value = smap.get(key);
            if (value != null && StringUtils.isNotBlank(String.valueOf(value))){
                stringBuffer.append(key).append("=").append(value).append("&");
            }
        }
        stringBuffer.delete(stringBuffer.length() - 1, stringBuffer.length());

        String argPreSign = stringBuffer.append("&paySecret=").append(paySecret).toString();
        log.warn("签名前：" + argPreSign);
        String signStr = encode(argPreSign).toUpperCase();
        log.warn("签名值：" + signStr);
        return signStr;
    }


    public static String httpCall(String addr, String debugStr,
                                   String content, String method) {
        HttpURLConnection urlCon = null;
        try {
            URL url = new URL(addr);
            Object con = url.openConnection();
            if (HttpsURLConnection.class.isInstance(con)) urlCon = (HttpsURLConnection) con;
            else urlCon = (HttpURLConnection) con;

            urlCon.setConnectTimeout(15000);
            urlCon.setReadTimeout(40000);

            urlCon.setDoOutput(true);
            urlCon.setDoInput(true);
            if (StringUtils.isNotBlank(content)) {
                method = "POST";
            }
            urlCon.setRequestMethod(method);
            urlCon.setUseCaches(false);
            urlCon.setInstanceFollowRedirects(true);
            if ("POST".equalsIgnoreCase(method))
                urlCon.setRequestProperty("Content-Type", "application/x-www-form-urlencoded");

            urlCon.connect();


            if (StringUtils.isNotBlank(content)) {
                log.warn("ready agentPay call[" + addr + "],send content[" + debugStr + "]");
                OutputStream out = urlCon.getOutputStream();
                out.write(content.getBytes());
                out.flush();
                out.close();
            } else {
                log.warn("ready agentPay call[" + addr + "] for " + method + ",and params=[" + debugStr + "]");
            }


            int state = urlCon.getResponseCode();
            if (state != 200) {
                log.warn("agentPay callservice visit addr[" + addr + "] fail:http rsp code[" + state + "]");
                throw new RuntimeException("agentPay callservice visit addr[" + addr + "] fail:http rsp code[" + state + "]");
            }
            String charSet = "UTF-8";
            String recvContentType = urlCon.getContentType();
            if (StringUtils.isNotBlank(recvContentType)) {
                Pattern pattern = Pattern.compile("charset=\\S*");
                Matcher matcher = pattern.matcher(recvContentType);
                if (matcher.find()) {
                    charSet = matcher.group().replace("charset=", "");
                }
            }
            InputStream in = urlCon.getInputStream();

            byte[] temp = new byte[1024];
            ByteArrayOutputStream baos = new ByteArrayOutputStream();
            int readBytes = in.read(temp);
            while (readBytes > 0) {
                baos.write(temp, 0, readBytes);
                readBytes = in.read(temp);
            }
            String resultString = new String(baos.toByteArray(), charSet);
            baos.close();
            in.close();
            urlCon.disconnect();
            urlCon = null;

            log.warn("agentPay call[" + addr + "],send content[" + content + "],recv[" + resultString + "]");
            return resultString;
        } catch (Throwable e) {
            if (RuntimeException.class.isInstance(e)) throw (RuntimeException) e;
            log.warn("agentPay callservice visit addr[" + addr + "] fail", e);
            throw new RuntimeException(e.toString());
        } finally {
            if (urlCon != null) {
                urlCon.disconnect();
            }
        }
    }


    /**
     * 代付测试
     * @return
     */
    public Map<String,Object> payTest(String settAmount){
        String addr="http://127.0.0.1:8050/rb-pay-web-merchant/agentPay/pay";
        Map<String, Object> paramMap = new HashMap<String,Object>();
        String orderNo = UUID.randomUUID().toString().replace("-","");
        paramMap.put("payKey", "530f0f39500f454c8d67dedebcae66e8");
        paramMap.put("orderNo",orderNo);
        paramMap.put("bankAccountName", "收款人名");
        paramMap.put("bankAccountNo", "收款账号");
        paramMap.put("bankCode", "收款银行编码，例如工行为ICBC");
        paramMap.put("settAmount", settAmount);
        paramMap.put("signType", "MD5");
        String sign=getSign(paramMap,"密匙");
        paramMap.put("sign", sign);
        StringBuffer debug=new StringBuffer();
        String content=toParams(paramMap,debug);
        String resultStr=httpCall(addr,debug.toString(),content,"POST");
        Map<String,Object>resultMap=JSON.parseObject(resultStr,Map.class);
        return resultMap;
    }


    /**
     * 查询代付结果测试
     * @param orderNo
     * @return
     */
    public Map<String,Object> payQueryTest(String orderNo){
        String addr="http://127.0.0.1:8050/rb-pay-web-merchant/agentPay/query";
        Map<String, Object> paramMap = new HashMap<String,Object>();
        paramMap.put("payKey", "530f0f39500f454c8d67dedebcae66e8");
        paramMap.put("orderNo",orderNo);
        paramMap.put("signType", "MD5");
        String sign=getSign(paramMap,"密匙");
        paramMap.put("sign", sign);
        StringBuffer debug=new StringBuffer();
        String content=toParams(paramMap,debug);
        String resultStr=httpCall(addr,debug.toString(),content,"POST");
        Map<String,Object>resultMap=JSON.parseObject(resultStr,Map.class);
        return resultMap;
    }


    /**
     * 查询余额测试
     * @return
     */
    public Map<String,Object> getBanlanceTest(){
        String addr="http://127.0.0.1:8050/rb-pay-web-merchant/agentPay/banlance";
        Map<String, Object> paramMap = new HashMap<String,Object>();
        paramMap.put("payKey", "530f0f39500f454c8d67dedebcae66e8");
        paramMap.put("signType", "MD5");
        String sign=getSign(paramMap,"密匙");
        paramMap.put("sign", sign);
        StringBuffer debug=new StringBuffer();
        String content=toParams(paramMap,debug);
        String resultStr=httpCall(addr,debug.toString(),content,"POST");
        Map<String,Object>resultMap=JSON.parseObject(resultStr,Map.class);
        return resultMap;
    }

%><%
    out.println(payTest("10")+"<br>");//代付

    out.println(payQueryTest("20180414153828910")+"<br>");//代付结果查询

    out.println(getBanlanceTest()+"<br>");//余额查询

%>

