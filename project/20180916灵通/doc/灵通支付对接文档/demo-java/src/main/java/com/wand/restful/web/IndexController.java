package com.wand.restful.web;

import java.math.BigDecimal;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.RequestMapping;

import com.wand.restful.configuration.CryptUtils;

@Controller
@RequestMapping
public class IndexController {

	@RequestMapping({ "index", "" })
	public String main(HttpServletRequest request, HttpServletResponse response) {
		return "pc/index";
	}
	
	@RequestMapping("test_form")
	public String test_form(HttpServletRequest request, HttpServletResponse response) {
		buildForm(request);
		return "pc/test_form";
	}
	
	@RequestMapping("callback")
	public String callback(HttpServletRequest request, HttpServletResponse response,BigDecimal money,BigDecimal payAmount,String merchantOrderNo,String orderNo) {

		StringBuilder buf = new StringBuilder(64);
		buf.append(orderNo);
		buf.append("&");
		buf.append(merchantOrderNo);
		buf.append("&");
		buf.append(money.toPlainString());
		buf.append("&");
		buf.append(payAmount.toPlainString());
		buf.append("&");
		buf.append("9AF6D9E349707FC20139B433D314AD6C");
		
		/**进行支付结果业务处理***/
		
		return "OK";
	}

	private void buildForm(HttpServletRequest request) {

		long timestamp = System.currentTimeMillis();
		int merchantId = 6001024;
		String orderNo = String.valueOf(timestamp);
		String secret = "9AF6D9E349707FC20139B433D314AD6C";
		double money = 0.01D;
		String notifyURL = "";
		String returnURL = "http://www.baidu.com/";
        String paytype="WX";

		StringBuilder source = new StringBuilder(64);
		source.append(money);
		source.append("&");
		source.append(merchantId);
		source.append("&");
		source.append(notifyURL);
		source.append("&");
		source.append(returnURL);
		source.append("&");
		source.append(orderNo);
		source.append("&");
		source.append(secret);

		request.setAttribute("merchantId", String.valueOf(merchantId));
		request.setAttribute("timestamp", orderNo);
		request.setAttribute("money", String.valueOf(money));
		request.setAttribute("notifyURL", "");
		request.setAttribute("returnURL", "");
		request.setAttribute("goodsName", "笔记本");
        request.setAttribute("paytype", paytype);
		request.setAttribute("merchantOrderId", orderNo);
		request.setAttribute("sign", CryptUtils.md5(source.toString()));

	}
	
}
