package com.ibuy.demo;

import java.util.Map;
import java.util.TreeMap;

import com.alibaba.fastjson.JSON;
import com.alibaba.fastjson.JSONObject;
import com.ibuy.utils.MerchantApiUtil;

/**
 * 专门用于验签使用的demo，使用时候不需要排序，只需要将待签名的参数做成JSON形式即可
 * 
 * */
public class SignCheck {

	public static void main(String[] args) {
		
		Map<String, Object> paramMap = new TreeMap<String, Object>();
		String paySecret = "ce6287d8fc7c45b4aed2c3d64a822c86";
		
		/*JSON字符串不需要按照顺序排列参数，因为排序工作已经封装，实际上制作签名还是需要排序，这里只是提供检验
			注意不要将sign字段和paySecret字段也放入JSON
		*/
		String text = "{'orderTime':'20170808151421',"
				+ "'trxNo':'TES77772017080810002065',"
				+ "'outTradeNo':'1502176461184',"
				+ "'successTime':'20170808151443',"
				+ "'tradeStatus':'SUCCESS',"
				+ "'orderPrice':'0.01',"
				+ "'payKey':'0784faa976d9461e9663163bd4f49953',"
				+ "'remark':'remark','productName':"
				+ "'testproduct',"
				+ "'productType':'10000103'"
				+ "}";

		//转换成JSON对象，并set入Map中，内部会进行排序
		JSONObject jsonObject = JSON.parseObject(text);
		for(Map.Entry<String, Object> me :jsonObject.entrySet()){
			paramMap.put(me.getKey(), me.getValue());
		}
		//制作签名
		String sign = MerchantApiUtil.getSign(paramMap, paySecret);
		System.out.println(sign);
	}
}
