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
<%@ page import="java.text.SimpleDateFormat" %>
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
                log.warn("ready call[" + addr + "],send content[" + debugStr + "]");
                OutputStream out = urlCon.getOutputStream();
                out.write(content.getBytes());
                out.flush();
                out.close();
            } else {
                log.warn("ready call[" + addr + "] for " + method + ",and params=[" + debugStr + "]");
            }


            int state = urlCon.getResponseCode();
            if (state != 200) {
                log.warn("callservice visit addr[" + addr + "] fail:http rsp code[" + state + "]");
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

            log.warn("call[" + addr + "],send content[" + content + "],recv[" + resultString + "]");
            return resultString;
        } catch (Throwable e) {
            if (RuntimeException.class.isInstance(e)) throw (RuntimeException) e;
            log.warn("callservice visit addr[" + addr + "] fail", e);
            throw new RuntimeException(e.toString());
        } finally {
            if (urlCon != null) {
                urlCon.disconnect();
            }
        }
    }




    /**
     * 网银支付测试
     * @return
     */
    public Map<String,Object> payTest(String orderPrice){
        Map<String , Object> paramMap = new HashMap<String , Object>();
        String orderPriceStr = orderPrice; // 订单金额 , 单位:元
        paramMap.put("orderPrice",orderPriceStr);
        paramMap.put("payWayCode", "ZITOPAY");//支付方式编码
        paramMap.put("payTypeCode", "ZITOPAY_BANK_SCAN");//支付类型编码


        paramMap.put("orderNo","T2017113022033456799");//订单号

        Date orderDate = new Date();//订单日期
        String orderDateStr = new SimpleDateFormat("yyyyMMdd").format(orderDate);// 订单日期
        paramMap.put("orderDate",orderDateStr);

        Date orderTime = new Date();//订单时间
        String orderTimeStr =  new SimpleDateFormat("yyyyMMddHHmmss").format(orderTime);// 订单时间
        paramMap.put("orderTime",orderTimeStr);

        paramMap.put("payKey", "530f0f39500f454c8d67dedebcae66e8");
        paramMap.put("productName","测试产品");
        paramMap.put("orderIp","127.0.0.1");

        String orderPeriodStr = "5"; // 订单有效期
        paramMap.put("orderPeriod",orderPeriodStr);
        String returnUrl = "http://127.0.0.1/test/pageReturn.jsp"; // 页面通知返回url
        paramMap.put("returnUrl",returnUrl);
        String notifyUrl = "http://127.0.0.1/test/notify.jsp"; // 后台消息通知Url
        paramMap.put("notifyUrl",notifyUrl);
        String remark = "测试"; // 支付备注
        paramMap.put("remark",remark);

        ////////////扩展字段,选填,原值返回///////////
        String field1 = "扩展字段1"; // 扩展字段1
        paramMap.put("field1",field1);//对于网银，这个字段可以指定具体的银行代码
        String field2 = "扩展字段2"; // 扩展字段2
        paramMap.put("field2",field2);
        String field3 = "扩展字段3"; // 扩展字段3
        paramMap.put("field3",field3);
        String field4 = "扩展字段4"; // 扩展字段4
        paramMap.put("field4",field4);
        String field5 = "扩展字段5"; // 扩展字段5
        paramMap.put("field5",field5);

        /////签名及生成请求API的方法///
        String sign = getSign(paramMap, "密匙");
        paramMap.put("sign",sign);


        String addr="http://api.quanyinzf.com:8050/rb-pay-web-gateway/scanPay/initPayIntf";
        StringBuffer debug=new StringBuffer();
        String content=toParams(paramMap,debug);
        String resultStr=httpCall(addr,debug.toString(),content,"POST");
        Map<String,Object>resultMap=JSON.parseObject(resultStr,Map.class);

        return resultMap;
    }


    /**
     * 查询支付结果测试
     * @param orderNo
     * @return
     */
    public Map<String,Object> payQueryTest(String orderNo){
        String addr="http://api.quanyinzf.com:8050/rb-pay-web-gateway/scanPay/orderQuery";
        Map<String, Object> paramMap = new HashMap<String,Object>();
        paramMap.put("payKey", "530f0f39500f454c8d67dedebcae66e8");
        paramMap.put("orderNo",orderNo);
        String sign=getSign(paramMap,"密匙");
        paramMap.put("sign", sign);
        StringBuffer debug=new StringBuffer();
        String content=toParams(paramMap,debug);
        String resultStr=httpCall(addr,debug.toString(),content,"POST");
        Map<String,Object>resultMap=JSON.parseObject(resultStr,Map.class);
        return resultMap;
    }
%>
<%
    Map<String,Object> result=payTest("10");//支付测试
    if("success".equals(result.get("result"))){
        out.print("支付需要跳转到此地址:"+result.get("code_url")+"<br>");//页面需要跳转到此地址进行支付
    }
    else{
        out.print("支付出错"+result.get("msg")+"<br>");//页面需要跳转到此地址进行支付
    }

    result=payQueryTest("T2017113022033456799");//支付结果查询
    if("success".equals(result.get("result"))){
        if("payed".equals(result.get("pay_result"))){
            out.print("支付单已经支付<br>");//可以作为支付成功的依据
        }
        else if("nopay".equals(result.get("pay_result"))){
            out.print("支付单未支付<br>");//可以作为未支付的依据
        }
        else if("nofind".equals(result.get("pay_result"))){
            out.print("支付单未找到<br>");//未防止网络出现意外，此结果需要人工核对，不能作为是否支付的依据
        }
    }
    else{
        out.print("查询出错:"+result.get("msg")+"<br>");//这个不能作为是否支付的依据，只是借口出错，需要再次发起查询
    }
%>