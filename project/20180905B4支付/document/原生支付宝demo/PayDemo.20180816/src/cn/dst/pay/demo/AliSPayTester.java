package cn.dst.pay.demo;

import cn.dst.pay.utils.DateUtil;

import org.apache.commons.lang3.RandomStringUtils;

import com.alibaba.fastjson.JSONObject;

import java.util.Calendar;
import java.util.HashMap;
import java.util.Map;

/**
 * 支付宝扫码支付
 */
public class AliSPayTester extends BaseTester {

	public static void main(String args[]) {
		try {
			Map<String, String> params = new HashMap<>();
			Calendar calendar = Calendar.getInstance();
			//String tradeNo = String.format("%s%s", DateUtil.dateToStr(calendar.getTime(), DateUtil.YMdhmsS_noSpli), RandomStringUtils.randomNumeric(15));
			//System.out.println("订单号:::" + tradeNo);

			// 业务参数
			params.put("tradeNo", "180818080107576826");
			params.put("totalAmount", "20");
			params.put("notifyUrl", "http://pay.tpay.com/index.php/pay/Yiyipay_callback");
			params.put("subject", "pay");
			params.put("body", "honor");
			JSONObject jsonObject = JSONObject.parseObject(post("/pay/aliSPay.do", params));
			System.out.println(jsonObject);
		} catch (Exception e) {
			e.printStackTrace();
		}
	}
}
