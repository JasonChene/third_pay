package com.yipin.pay;

import com.alibaba.fastjson.JSONObject;
import okhttp3.FormBody;
import okhttp3.OkHttpClient;
import okhttp3.Request;
import okhttp3.Response;
import org.apache.commons.codec.digest.DigestUtils;

import java.io.IOException;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;
import java.util.Map;
import java.util.UUID;
import java.util.concurrent.Callable;
import java.util.concurrent.ExecutionException;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;
import java.util.concurrent.Future;

public class PayDemo implements Runnable {
    //    修改为你方的appid 和 key
    public static String appId = "5421A9C2F5E5BB426A7A14B5A86EA508";
    public static String key = "2387295305A0A666C11690F2C6FF7B5F";
    public static String host = "http://api.payyp.com";
//    public static String host = "http://127.0.0.1:8080";
    public static String payType = "Ali";
    /**
     * 此处修改为你方的回调地址
     */
    private static String notifyUrl = "http://www.baidu.com/notifyUrl";
    private static String returnUrl = "http://www.baidu.com/returnUrl";

    private static final class DemoTask implements Callable<String> {
        public String call() throws Exception {
            return doCreateOrder();
        }
    }

    public static void main(String[] args) throws ExecutionException, InterruptedException {
        ExecutorService pool = Executors.newFixedThreadPool(4);

        List<Future<String>> futures = new ArrayList<Future<String>>();

        for (int i = 0; i < 1; i++) {
            futures.add(pool.submit(new DemoTask()));
        }

        System.out.println(new Date().toLocaleString());
        for (Future<String> future : futures) {
            String result = future.get();
        }
        System.out.println(new Date().toLocaleString());

        pool.shutdown();
    }

    private static String doCreateOrder() {
        String orderNum = UUID.randomUUID().toString().replace("-", "");
        System.out.println("orderUnm:" + orderNum);
        // 我改了一下这里 你看行不
//        String money = String.valueOf(Integer.valueOf(random.nextInt(5000)/10)*10 + 1);
        String money = "2";
        String signature = generateCipherText(orderNum, money);
        System.out.println("signature:" + signature);

        OkHttpClient okHttpClient = new OkHttpClient();
        FormBody formBody = new FormBody.Builder()
                .add("appId", appId)
                .add("money", money)
                .add("payType", payType)
                .add("orderNumber", orderNum)
                .add("notifyUrl", notifyUrl)
                .add("returnUrl", returnUrl)
                .add("signature", signature)
                .build();
        Request request = new Request.Builder()
                .url(host + "/pay/business/generate")
                .post(formBody)
                .build();
        Response response;
        try {
            response = okHttpClient.newCall(request).execute();
            if (response.body() != null) {
                String responseBody = response.body().string();
                System.out.println(responseBody);
                Map<String, String> resultJSONMap = JSONObject.parseObject(responseBody, Map.class);
                System.out.println(resultJSONMap);
            }
        } catch (IOException e) {
            e.printStackTrace();
        }
        return orderNum;
    }

    //生成密文
    private static String generateCipherText(String orderNum, String money) {
        /**
         * 修改为你方的key
         */

        StringBuilder stringBuilder = new StringBuilder();
        stringBuilder.append(appId).append("&")
                .append(money).append("&")
                .append(payType).append("&")
                .append(orderNum).append("&")
                .append(notifyUrl).append("&")
                .append(returnUrl).append("&")
                .append(key);
        String express = stringBuilder.toString();
        System.out.println("加密串:\n" + express);
        return DigestUtils.md5Hex(express).toUpperCase();
    }

    public void run() {
        doCreateOrder();
    }
}
