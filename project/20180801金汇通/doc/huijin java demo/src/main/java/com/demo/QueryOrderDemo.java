package com.demo;

import com.alibaba.fastjson.JSONObject;
import com.demo.bean.CreateOrderRequestBean;
import com.demo.bean.CreateOrderResponseBean;
import com.demo.bean.QueryOrderRequestBean;
import com.demo.bean.QueryOrderResponseBean;
import com.demo.util.Md5Signature;
import com.demo.util.SignUtils;
import com.demo.util.StringUtil;
import com.squareup.okhttp.*;

import java.io.IOException;

/**
 * @author kogome
 */
public class QueryOrderDemo {
    public static final MediaType JSON = MediaType.parse("application/json; charset=utf-8");
    private final String SUCCESS = "100";

    String mchNo = "982103227019296768";
    String appkey = "ECAB327DA33D4B3AB198C3BCB7557A19";
    /**
     * 订单查询
     * @throws IOException
     */
    public void queryOrder() throws IOException {
        QueryOrderRequestBean requestBean = new QueryOrderRequestBean();
        requestBean.setDate(System.currentTimeMillis() + "");
        requestBean.setMerchantNo(mchNo);
        requestBean.setTradeNo("1525679448674");
        requestBean.setVersion("1.0");
        requestBean.setOperationCode("order.query");
        requestBean.setNonceStr(System.currentTimeMillis() +"");

        requestBean.setSign(SignUtils.createSign(appkey, requestBean));

        String json =    JSONObject.toJSONString(requestBean);

        System.out.println(json);

        OkHttpClient client = new OkHttpClient();
        RequestBody body = RequestBody.create(JSON,json);
        Request request = new Request
                .Builder()
                .url("http://pay.xmyexing.com/api/payment/queryOrder")
                .post(body)
                .build();
        Response response = client.newCall(request).execute();

        if (response.isSuccessful()) {
             this.check(response.body().string());
        } else {
            throw new IOException("Unexpected code " + response);
        }

    }


    public static void main(String[] args) {
        try {
            new QueryOrderDemo().queryOrder();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }


    /**
     * 处理响应信息
     * @param response
     */
    private void check(String response){
        QueryOrderResponseBean responseBean = JSONObject.parseObject(response,QueryOrderResponseBean.class);
        System.out.println("返回json:" + JSONObject.toJSON(responseBean));

        boolean checkSign = SignUtils.checkSign(appkey, responseBean, responseBean.getSign());
        System.out.println(checkSign);
        // 响应的不是 100 ，失败不处理业务
    }

}
