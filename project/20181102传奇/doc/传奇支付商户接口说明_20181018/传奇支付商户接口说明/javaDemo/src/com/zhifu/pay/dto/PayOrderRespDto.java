package com.zhifu.pay.dto;


public class PayOrderRespDto extends AbstractResponseDTO {

	/**
	 * 
	 */
	private static final long serialVersionUID = -8955473175466511896L;


	private String payNo;          //平台订单号
	
	private String orderNo;     //订单号
	
	private String jumpUrl;        //跳转URL

	private String realAmt;     //实际支付金额
	
	public String getPayNo() {
		return payNo;
	}

	public void setPayNo(String payNo) {
		this.payNo = payNo;
	}

	public String getOrderNo() {
		return orderNo;
	}

	public void setOrderNo(String orderNo) {
		this.orderNo = orderNo;
	}

	public String getJumpUrl() {
		return jumpUrl;
	}

	public void setJumpUrl(String jumpUrl) {
		this.jumpUrl = jumpUrl;
	}

	public String getRealAmt() {
		return realAmt;
	}

	public void setRealAmt(String realAmt) {
		this.realAmt = realAmt;
	}
	
	
	
	
}
