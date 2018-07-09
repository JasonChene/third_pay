package com.yangjian;

import java.util.Date;
import java.util.SortedMap;
import java.util.TreeMap;
import java.util.Map.Entry;

import org.apache.commons.codec.digest.DigestUtils;
import org.apache.http.HttpEntity;
import org.apache.http.client.config.RequestConfig;
import org.apache.http.client.methods.CloseableHttpResponse;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.entity.StringEntity;
import org.apache.http.impl.client.CloseableHttpClient;
import org.apache.http.impl.client.HttpClients;
import org.apache.http.util.EntityUtils;

import com.thinkgem.jeesite.common.mapper.JsonMapper;
import com.thinkgem.jeesite.common.utils.DateUtils;
import com.thinkgem.jeesite.common.utils.StringUtils;

public class WebTransDemo {
	public static final String merCode = "8660592201700007";

	public static final String merKey = "216JT5YWWJ3CT5HU";

	public static void main(String[] args) throws Exception {
		SortedMap<String, String> params = new TreeMap<String, String>();
		String tranTime = DateUtils.formatDate(new Date(), "yyyyMMddHHmmss");
		params.put("merCode", merCode);// 商户号
		params.put("tranNo", tranTime);// demo使用时间戳，但是请使用其他生成序列号方式，以免重复
		params.put("tranType", "00");//扫码(电脑端)：00，网银(电脑端): 01，快捷(电脑端/手机端)：02,H5(手机端)：03
		params.put("collectWay", "ZFBZF");//电脑微信(微信扫码)：WXZF、电脑支付宝(支付宝扫码)：ZFBZF、手机微信(微信H5)：WXH5、手机支付宝(支付宝H5)：ZFBH5、电脑网银：web
		params.put("tranTime", tranTime);
		params.put("tranAmt", "1000");// 金额
		params.put("orderDesc", "orderDesc"); // 订单描述
		params.put("noticeUrl", "http://shydale.com/trans/gateway/testNotice");
		// 签名
		StringBuffer signStr = new StringBuffer();
		for (Entry<String, String> entry : params.entrySet()) {
			if (!entry.getKey().equals("sign")) {
				signStr.append(entry.getKey()).append("=").append(entry.getValue()).append("&");
			}
		}
		String signText = signStr.substring(0, signStr.length() - 1) + merKey;
		String sign = DigestUtils.md5Hex(signText.getBytes("UTF-8"));
		params.put("sign", sign);
		String paramStr = StringUtils.buildSignStr(params, null, false);
		String url = "http://shydale.com/trans/gateway/webTrans";
		String payUrl = url + "?" + paramStr;
		System.out.println("支付地址：" + payUrl);
	}
}
