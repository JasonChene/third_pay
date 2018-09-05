package cn.dst.pay.demo;

import cn.dst.pay.utils.DateUtil;

import org.apache.commons.lang3.RandomStringUtils;

import com.alibaba.fastjson.JSONObject;

import java.util.Calendar;
import java.util.HashMap;
import java.util.Map;

/**
 * 支付宝H5支付
 */
public class AliH5PayTester extends BaseTester {

	public static void main(String args[]) {
		try {
			Map<String, String> params = new HashMap<>();
			Calendar calendar = Calendar.getInstance();
			String tradeNo = String.format("%s%s", DateUtil.dateToStr(calendar.getTime(), DateUtil.YMdhmsS_noSpli), RandomStringUtils.randomNumeric(15));
			System.out.println("订单号:::" + tradeNo);

			// 业务参数
			params.put("tradeNo", tradeNo);
			params.put("totalAmount", "0.10");
			params.put("notifyUrl", "http://baidu.com");
			params.put("subject", "手机网页支付消费");
			params.put("body", "购买商品3件共xx元");
			JSONObject jsonObject = JSONObject.parseObject(post("/pay/aliH5Pay.do", params));
			System.out.println(jsonObject);
		} catch (Exception e) {
			e.printStackTrace();
		}
	}
}
