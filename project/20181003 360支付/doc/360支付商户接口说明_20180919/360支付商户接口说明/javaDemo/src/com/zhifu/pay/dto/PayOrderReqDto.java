package com.zhifu.pay.dto;

import javax.validation.constraints.NotNull;
import javax.validation.constraints.Size;


public class PayOrderReqDto extends AbstractRequestDTO{

	/**
	 * 
	 */
	private static final long serialVersionUID = 2704217062031752545L;

	@NotNull(message="商户订单号--orderNo不能为空")
	@Size(min=1,max=64,message="商户订单号--orderNo长度有误")
	private String orderNo;   //商户订单号
	
	@NotNull(message="订单金额--orderAmt不能为空")
	@Size(min=1,max=20,message="订单金额--orderAmt长度有误")
	private String orderAmt;     //订单金额  元为单位
	
	@NotNull(message="支付通道--thirdChannel不能为空")
	@Size(min=1,max=20,message="支付通道--thirdChannel长度有误")
	private String thirdChannel;      //支付平台 alipay:支付宝  wxpay:微信支付
	
	@NotNull(message="备用字段1--remark1不能为空")
	@Size(min=1,max=64,message="备用字段1--remark1长度有误")
	private String remark1;   //备用字段1
	
	@NotNull(message="备用字段2--remark2不能为空")
	@Size(min=1,max=128,message="备用字段2--remark2长度有误")
	private String remark2;    //备用字段2
	
	@NotNull(message="后台通知url--notifyUrl不能为空")
	@Size(min=1,max=128,message="后台通知url--notifyUrl长度有误")
	private String notifyUrl;     //后台通知url

	
	@Size(min=1,max=128,message="页面跳转url--callbackUrl长度有误")
	private String callbackUrl;     //后台通知url
	
	@NotNull(message="支付产品--payprod不能为空")
	@Size(min=2,max=2,message="支付产品--payprod长度有误")
	private String payprod;  //支付产品  10 WAP支付  11 扫码支付
	
	public String getOrderNo() {
		return orderNo;
	}

	public void setOrderNo(String merOrderNo) {
		this.orderNo = merOrderNo;
	}

	public String getOrderAmt() {
		return orderAmt;
	}

	public void setOrderAmt(String orderAmt) {
		this.orderAmt = orderAmt;
	}

	public String getThirdChannel() {
		return thirdChannel;
	}

	public void setThirdChannel(String payPlat) {
		this.thirdChannel = payPlat;
	}

	public String getRemark1() {
		return remark1;
	}

	public void setRemark1(String remark1) {
		this.remark1 = remark1;
	}

	public String getRemark2() {
		return remark2;
	}

	public void setRemark2(String remark2) {
		this.remark2 = remark2;
	}

	public String getNotifyUrl() {
		return notifyUrl;
	}

	public void setNotifyUrl(String notifyUrl) {
		this.notifyUrl = notifyUrl;
	}
	
	public String toString(){
		return super.toString();
	}

	public String getCallbackUrl() {
		return callbackUrl;
	}

	public void setCallbackUrl(String callbackUrl) {
		this.callbackUrl = callbackUrl;
	}

	public String getPayprod() {
		return payprod;
	}

	public void setPayprod(String payprod) {
		this.payprod = payprod;
	}
	
}
