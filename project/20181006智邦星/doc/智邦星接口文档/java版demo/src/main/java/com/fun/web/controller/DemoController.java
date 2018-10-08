package com.fun.web.controller;

import java.util.Map;
import java.util.Map.Entry;
import java.util.TreeMap;
import java.util.concurrent.TimeUnit;

import javax.servlet.http.HttpServletRequest;

import org.apache.commons.codec.digest.DigestUtils;
import org.apache.commons.lang3.StringUtils;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.core.env.Environment;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.ResponseBody;
import org.springframework.web.servlet.ModelAndView;

import com.alibaba.fastjson.JSON;
import com.alibaba.fastjson.JSONObject;

import okhttp3.FormBody;
import okhttp3.FormBody.Builder;
import okhttp3.OkHttpClient;
import okhttp3.Request;
import okhttp3.Response;

@Controller
public class DemoController {
	private static final OkHttpClient client = new OkHttpClient.Builder()//
			.connectTimeout(60, TimeUnit.SECONDS)//
			.readTimeout(120, TimeUnit.SECONDS)//
			.writeTimeout(120, TimeUnit.SECONDS)//
			.retryOnConnectionFailure(false).build();

	private @Autowired Environment environment;

	/**
	 * 入口
	 * 
	 * @param mav
	 * @param request
	 * @return
	 * @throws Exception
	 */
	@RequestMapping({ "/", "/index.html" })
	public ModelAndView index(ModelAndView mav, HttpServletRequest request) throws Exception {
		mav.addObject("merchantCode", environment.getProperty("conf.merchant.code"));
		mav.addObject("merchantKey", environment.getProperty("conf.merchant.key"));
		mav.addObject("payUrl", environment.getProperty("conf.pay.url"));
		mav.addObject("queryUrl", environment.getProperty("conf.query.url"));
		mav.setViewName("index");
		return mav;
	}

	/**
	 * 支付提交页面
	 * 
	 * @param mav
	 * @param request
	 * @return
	 * @throws Exception
	 */
	@RequestMapping({ "/pay.html" })
	public ModelAndView pay(ModelAndView mav, HttpServletRequest request) throws Exception {
		String amount = request.getParameter("amount");
		String merchant = request.getParameter("merchant");
		String notifyUrl = request.getParameter("notifyUrl");
		String orderNo = request.getParameter("orderNo");
		String payType = request.getParameter("payType");
		String remark = request.getParameter("remark");
		String returnUrl = request.getParameter("returnUrl");
		String currentTime = request.getParameter("currentTime");
		String key = request.getParameter("key");
		String payUrl = request.getParameter("payUrl");

		StringBuffer sb = new StringBuffer();
		sb.append("amount=" + amount);
		sb.append("&currentTime=" + currentTime);
		sb.append("&merchant=" + merchant);
		sb.append("&notifyUrl=" + notifyUrl);
		sb.append("&orderNo=" + orderNo);
		sb.append("&payType=" + payType);
		if (StringUtils.isNotBlank(remark)) {
			sb.append("&remark=" + remark);
		}
		sb.append("&returnUrl=" + returnUrl);
		String _sign = DigestUtils.md5Hex(sb.toString() + "#" + key);

		mav.addObject("amount", amount);
		mav.addObject("merchant", merchant);
		mav.addObject("notifyUrl", notifyUrl);
		mav.addObject("orderNo", orderNo);
		mav.addObject("payType", payType);
		mav.addObject("remark", remark);
		mav.addObject("returnUrl", returnUrl);
		mav.addObject("currentTime", currentTime);
		mav.addObject("payUrl", payUrl);
		mav.addObject("sign", _sign);
		mav.setViewName("pay");
		return mav;
	}

	/**
	 * 同步回调页面
	 * 
	 * @param mav
	 * @param request
	 * @return
	 * @throws Exception
	 */
	@RequestMapping({ "/call.html" })
	public ModelAndView call(ModelAndView mav, HttpServletRequest request) throws Exception {
		String accFlag = request.getParameter("accFlag");
		String accName = request.getParameter("accName");
		String amount = request.getParameter("amount");
		String createTime = request.getParameter("createTime");
		String currentTime = request.getParameter("currentTime");
		String merchant = request.getParameter("merchant");
		String orderNo = request.getParameter("orderNo");
		String payFlag = request.getParameter("payFlag");
		String payTime = request.getParameter("payTime");
		String payType = request.getParameter("payType");
		String remark = request.getParameter("remark");
		String systemNo = request.getParameter("systemNo");
		String sign = request.getParameter("sign");

		StringBuffer sb = new StringBuffer();
		sb.append("accFlag=" + accFlag);
		sb.append("&accName=" + accName);
		sb.append("&amount=" + amount);
		sb.append("&createTime=" + createTime);
		sb.append("&currentTime=" + currentTime);
		sb.append("&merchant=" + merchant);
		sb.append("&orderNo=" + orderNo);
		sb.append("&payFlag=" + payFlag);
		sb.append("&payTime=" + payTime);
		sb.append("&payType=" + payType);
		if (StringUtils.isNotBlank(remark)) {
			sb.append("&remark=" + remark);
		}
		sb.append("&systemNo=" + systemNo);
		String _sign = DigestUtils.md5Hex(sb.toString() + "#" + environment.getProperty("conf.merchant.key"));

		if (StringUtils.equalsIgnoreCase(_sign, sign)) {// 验签通过
			mav.setViewName("call");
		} else {
			mav.setViewName("error");
			mav.addObject("msg", "签名不正确");
		}
		return mav;
	}

	/**
	 * 异步回调页面
	 * 
	 * @param mav
	 * @param request
	 * @return
	 * @throws Exception
	 */
	@RequestMapping({ "/back.html" })
	@ResponseBody
	public String back(ModelAndView mav, HttpServletRequest request) throws Exception {
		// 接受参数
		String accFlag = request.getParameter("accFlag");
		String accName = request.getParameter("accName");
		String amount = request.getParameter("amount");
		String createTime = request.getParameter("createTime");
		String currentTime = request.getParameter("currentTime");
		String merchant = request.getParameter("merchant");
		String orderNo = request.getParameter("orderNo");
		String payFlag = request.getParameter("payFlag");
		String payTime = request.getParameter("payTime");
		String payType = request.getParameter("payType");
		String remark = request.getParameter("remark");
		String systemNo = request.getParameter("systemNo");
		String sign = request.getParameter("sign");

		// 验证签名
		StringBuffer sb = new StringBuffer();
		sb.append("accFlag=" + accFlag);
		sb.append("&accName=" + accName);
		sb.append("&amount=" + amount);
		sb.append("&createTime=" + createTime);
		sb.append("&currentTime=" + currentTime);
		sb.append("&merchant=" + merchant);
		sb.append("&orderNo=" + orderNo);
		sb.append("&payFlag=" + payFlag);
		sb.append("&payTime=" + payTime);
		sb.append("&payType=" + payType);
		if (StringUtils.isNotBlank(remark)) {
			sb.append("&remark=" + remark);
		}
		sb.append("&systemNo=" + systemNo);
		String _sign = DigestUtils.md5Hex(sb.toString() + "#" + environment.getProperty("conf.merchant.key"));

		if (StringUtils.equalsIgnoreCase(_sign, sign)) {// 验签通过
			// 逻辑处理
			return "SUCCESS";
		} else {
			return "ERROR";
		}
	}

	@RequestMapping({ "/query.html" })
	public ModelAndView query(ModelAndView mav, HttpServletRequest request) throws Exception {
		String merchant = request.getParameter("merchant");
		String orderNo = request.getParameter("orderNo");
		String currentTime = request.getParameter("currentTime");
		String key = request.getParameter("key");
		String createTime = request.getParameter("createTime");
		String queryUrl = request.getParameter("queryUrl");

		Map<String, String> parameters = new TreeMap<String, String>();
		parameters.put("merchant", merchant);
		parameters.put("orderNo", orderNo);
		parameters.put("currentTime", currentTime);
		parameters.put("createTime", createTime);

		StringBuffer sb = new StringBuffer();
		sb.append("createTime=" + createTime);
		sb.append("&currentTime=" + currentTime);
		sb.append("&merchant=" + merchant);
		sb.append("&orderNo=" + orderNo);
		String _sign = DigestUtils.md5Hex(sb.toString() + "#" + key);
		parameters.put("sign", _sign);
		String messStr = this.doPost(queryUrl, parameters);
		if (StringUtils.isNotBlank(messStr)) {
			JSONObject jsonObject = JSON.parseObject(messStr);
			int code = jsonObject.getIntValue("code");
			if (code == 200) {
				JSONObject data = jsonObject.getJSONObject("data");
				String _accFlag = data.getString("accFlag");
				String _accName = data.getString("accName");
				String _amount = data.getString("amount");
				String _createTime = data.getString("createTime");
				String _currentTime = data.getString("currentTime");
				String _merchant = data.getString("merchant");
				String _orderNo = data.getString("orderNo");
				String _payFlag = data.getString("payFlag");
				String _payTime = data.getString("payTime");
				String _payType = data.getString("payType");
				String _remark = data.getString("remark");
				String _systemNo = data.getString("systemNo");

				sb = new StringBuffer();
				sb.append("accFlag=" + _accFlag);
				sb.append("&accName=" + _accName);
				sb.append("&amount=" + _amount);
				sb.append("&createTime=" + _createTime);
				sb.append("&currentTime=" + _currentTime);
				sb.append("&merchant=" + _merchant);
				sb.append("&orderNo=" + _orderNo);
				sb.append("&payFlag=" + _payFlag);
				sb.append("&payTime=" + _payTime);
				sb.append("&payType=" + _payType);
				if (StringUtils.isNotBlank(_remark)) {
					sb.append("&remark=" + _remark);
				}
				sb.append("&systemNo=" + _systemNo);

				String _sign1 = DigestUtils.md5Hex(sb.toString() + "#" + environment.getProperty("conf.merchant.key"));
				String _sign2 = data.getString("sign");
				if (StringUtils.equalsIgnoreCase(_sign1, _sign2)) {
					// 业务处理
					for (Entry<String, Object> entry : data.entrySet()) {
						mav.addObject(entry.getKey(), entry.getValue());
					}
				} else {
					mav.setViewName("error");
					mav.addObject("msg", "签名验证错误");
					return mav;
				}
			} else if (code == 500) {
				mav.setViewName("error");
				mav.addObject("msg", jsonObject.getString("info"));
				return mav;
			}
		}
		mav.setViewName("query");
		return mav;
	}

	public String doPost(String postUrl, Map<String, String> parameters) throws Exception {
		Request request = null;
		Response response = null;
		try {
			Builder builder = new FormBody.Builder();
			for (Entry<String, String> entry : parameters.entrySet()) {
				builder.add(entry.getKey(), entry.getValue());
			}
			request = new Request.Builder().url(postUrl).post(builder.build()).build();
			response = client.newCall(request).execute();
			if (response.isSuccessful()) {
				return response.body().string();
			}
		} catch (Exception e) {
			throw e;
		} finally {
			if (response != null) {
				response.close();
			}
		}
		return StringUtils.EMPTY;
	}
}
