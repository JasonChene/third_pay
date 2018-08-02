package com.demo;

import com.alibaba.fastjson.JSON;
import com.alibaba.fastjson.JSONObject;
import com.alibaba.fastjson.serializer.SerializerFeature;
import com.demo.bean.CreateOrderRequestBean;
import com.demo.bean.CreateOrderResponseBean;
import com.demo.util.Md5Signature;
import com.demo.util.SignUtils;
import com.demo.util.StringUtil;
import com.squareup.okhttp.*;

import java.io.IOException;
import java.util.Random;

/**
 * @author kogome
 */
public class CreateOrderDemo {
    public static final MediaType JSON = MediaType.parse("application/json; charset=utf-8");
    private final String SUCCESS = "100";

    private final  String mchNo = "32432432432";
    private final  String appKey = "ECAB327GG33D4B3AB198C3BCB7557A19";

    public static void main(String[] args) {
        try {
            new CreateOrderDemo().createOrder();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    /**
     * 下单
     * @throws IOException
     */
    public void createOrder() throws IOException {
        CreateOrderRequestBean requestBean = new CreateOrderRequestBean();
        requestBean.setAmount(100);
        requestBean.setSubject("测试t001-" + new Random().nextInt());
        requestBean.setBody("测试商品"+ new Random().nextInt());
        requestBean.setPaymentType("WEIXIN_QRCODE");
        requestBean.setSpbillCreateIp("127.0.0.1");
        requestBean.setFrontUrl("http://www.qq.com");
        requestBean.setNotifyUrl("http://www.qq.com");
        requestBean.setTradeNo(System.currentTimeMillis()+"");
        requestBean.setMerchantNo(mchNo);
        requestBean.setOperationCode("order.createOrder");
        requestBean.setDate(System.currentTimeMillis() + "");
        requestBean.setVersion("1.0");

        String sign = SignUtils.createSign(appKey, requestBean);
        requestBean.setSign(sign);

        String json = requestBean.toString();

        System.out.println(com.alibaba.fastjson.JSON.toJSONString(requestBean, SerializerFeature.PrettyFormat));

        OkHttpClient client = new OkHttpClient();
        RequestBody body = RequestBody.create(JSON,json);
        Request request = new Request
                .Builder()
                .url("http://pay.xmyexing.com/api/payment/createOrder")
                .post(body)
                .build();
        Response response = client.newCall(request).execute();
        if (response.isSuccessful()) {
             this.check(response.body().string());
        } else {
            throw new IOException("Unexpected code " + response);
        }

    }



    /**
     * 处理响应信息
     * @param response
     */
    private void check(String response){
        CreateOrderResponseBean responseBean = JSONObject.parseObject(response,CreateOrderResponseBean.class);
/*
        CreateOrderResponseBean responseBean = new CreateOrderResponseBean();
        responseBean.setAmount("100");
        responseBean.setCode("100");
        responseBean.setMessage("成功");
        responseBean.setDate("1524894941152");
        responseBean.setMsg("请求成功");
        responseBean.setMerchantNo("1524893933368");
        responseBean.setPlatformOrderId("990107252834697216");
        responseBean.setOrderStatus("2");
        responseBean.setOrderStatusDesc("weixin://wxpay/bizpayurl?pr=guvU4Ax");

        String text = StringUtil.sort(responseBean.toMap(),this.appKey);
        responseBean.setSign(sign(text,this.appKey));
*/
        System.out.println("返回:" + JSONObject.toJSONString(responseBean));
        // 响应的不是 100 ，失败不处理业务
        if (! SUCCESS.equals(responseBean.getCode())){
            return;
        }
        boolean checkSign = SignUtils.checkSign(appKey, responseBean, responseBean.getSign());

        System.out.println(checkSign);
    }

}
