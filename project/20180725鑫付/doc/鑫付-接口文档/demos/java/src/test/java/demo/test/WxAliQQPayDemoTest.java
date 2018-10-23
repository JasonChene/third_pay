package demo.test;

import java.text.SimpleDateFormat;
import java.util.Date;

import org.apache.commons.lang3.StringUtils;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.testng.annotations.Test;

import com.alibaba.fastjson.JSON;
import com.alibaba.fastjson.JSONObject;

import demo.WxAliQQPayDemo;
import demo.model.WxAliQQPayReqModel;

/**
 * 微信支付宝QQ
 */
public class WxAliQQPayDemoTest {

    public static Logger logger = LoggerFactory.getLogger(WxAliQQPayDemoTest.class);

    public WxAliQQPayReqModel getTestData() {

        String key = "";
        String src_code = "";
        String mch_id = "";

        String total_fee = "101"; // 单位是分
        String goods_name = "XXXX"; // 订单名称
        String out_trade_no = "122121212"; // 订单号
        String time_start = new SimpleDateFormat("yyyyMMddHHmmss").format(new Date());
        String finish_url = "http://www.abc.com"; // 完成页跳转url

        return new WxAliQQPayReqModel(src_code, key, mch_id, total_fee, goods_name, out_trade_no, time_start, finish_url);
    }

    /**
     * 微信或者支付宝支付支付
     */
    @Test
    public void testPayDemo() {
        WxAliQQPayDemo wxAliPayDemo = new WxAliQQPayDemo();
        WxAliQQPayReqModel reqModel = getTestData();

        // 定义支付类型
        reqModel.setTrade_type("60104");

        String ret = wxAliPayDemo.payv2(reqModel);

        System.out.println("response: " + ret);

        String pay_params = StringUtils.EMPTY;
        try {
            JSONObject retjson = null;
            try {
                retjson = JSON.parseObject(ret);
            } catch (Exception e) {
                logger.debug("result is not json。");
            }
            if (retjson != null) {
                pay_params = retjson.getString("pay_params");
            }
        } catch (Exception e) {
            logger.error(e.getMessage(), e);
        }

        System.out.println("pay_params: " + pay_params);
    }
}
