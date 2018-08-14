package com.zspay.SDK.servlet;

import java.io.IOException;
import java.util.Date;


import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import org.apache.log4j.Logger;

import com.alibaba.fastjson.JSONObject;
import com.zspay.SDK.util.DateUtil;
import com.zspay.SDK.util.Security;
import com.zspay.SDK.util.StringUtil;

@SuppressWarnings("serial")
public class PayServlet extends HttpServlet {
	private Logger log = Logger.getLogger(this.getClass());
	public void doPost(HttpServletRequest request, HttpServletResponse response) throws IOException, ServletException {
		Date date = new Date();
		String payUrl="";//正式提交地址，

		
		String md5Key="123456ADSEF";//商户md5key，用于签名和验签
		//签名的字段  6个
		final String[] signFields = { "merchantCode","notifyUrl", "outOrderId", "totalAmount",
				"merchantOrderTime", "randomStr" };
		
		//----------------提交参数-----------------------------
		//----------------必填-非空-------------------------------
		String merchantCode="1000000001";//必填：商户号
		String notifyUrl="http://125.69.76.146:6777//paymentorder/kkpayCallback";//必填：支付平台通知商户服务器的地址
		String randomStr=StringUtil.getRandomNum(20);       //必填：随机生成20位的字符串
		String payWay="01";                                 //必填：00代表微信公众号  01代表支付宝支付
		String outOrderId=request.getParameter("outOrderId");//必填：商户订单号，可以作为商户系统的唯一标识
		String totalAmount=request.getParameter("totalAmount");//必填：交易金额，单位分
		String merchantOrderTime=DateUtil.formatDate2(date);//必填：订单创建时间，格式：yyyyMMddHHmmss
		String sign="";//签名的到的sign值，该值需要转大写！
		//其他字段都是非必填，但是需要传空值过来！！！
		//------------------非必填------------------------------
		String ext="";       //扩展字段，该字段非必填，若填写，则异步回调（notifyUrl）会回传该值，若填写为"",则不会回传该值。
		String goodsName=request.getParameter("goodsName"); //商品名称
		String goodsDescription=request.getParameter("goodsDescription");//商品描述
		String latestPayTime="";//最晚支付时间，若填写则必须按照格式：yyyyMMddHHmmss，若传空值，则系统默认在创建时间+24小时。
		//------------------数据签名-----------------------------
		JSONObject json = new JSONObject();
		json.put("merchantCode",merchantCode);
		json.put("notifyUrl",notifyUrl);
		json.put("outOrderId",outOrderId );
		json.put("totalAmount",totalAmount );		
		json.put("merchantOrderTime",merchantOrderTime );
		json.put("randomStr", randomStr);	
		try {//对数据进行签名
			sign = Security.countSignMd5(md5Key, signFields, json);
			json.put("sign", sign);
		} catch (Exception e) {
			log.error("签名失败", e);
		}
		//--------------传递参数给payMac.jsp---------------------
		request.setAttribute("merchantCode", merchantCode);
		request.setAttribute("goodsName", goodsName);
		request.setAttribute("goodsDescription", goodsDescription);
		request.setAttribute("notifyUrl", notifyUrl);
		request.setAttribute("merchantOrderTime", merchantOrderTime);
		request.setAttribute("latestPayTime", latestPayTime);
		request.setAttribute("outOrderId", outOrderId);
		request.setAttribute("totalAmount", totalAmount);
		request.setAttribute("sign", sign);
		request.setAttribute("randomStr", randomStr);
		request.setAttribute("payWay", payWay);
		request.setAttribute("ext", ext);
		request.setAttribute("payUrl", payUrl);
	
		// 这条语句指明了向客户端发送的内容格式和采用的字符编码．
		response.setContentType("text/html;charset=UTF-8");
		request.getRequestDispatcher("/payMac.jsp").forward(request,response);
	}
	
	
}
