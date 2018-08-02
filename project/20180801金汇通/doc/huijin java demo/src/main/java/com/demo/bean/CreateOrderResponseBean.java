package com.demo.bean;

import com.demo.util.IgnoreSign;

import java.util.HashMap;
import java.util.Map;

/**
 * @author kogome
 */
public class CreateOrderResponseBean {
    private String code;
    private String msg;
    private String date;
    private String tradeNo;
    private String platformOrderId;
    private String amount;
    private String status;
    private String message;
    private String payCode;
    @IgnoreSign
    private String sign;

    public String getCode() {
        return code;
    }

    public void setCode(String code) {
        this.code = code;
    }

    public String getMsg() {
        return msg;
    }

    public void setMsg(String msg) {
        this.msg = msg;
    }

    public String getDate() {
        return date;
    }

    public void setDate(String date) {
        this.date = date;
    }

    public String getTradeNo() {
        return tradeNo;
    }

    public void setTradeNo(String tradeNo) {
        this.tradeNo = tradeNo;
    }

    public String getPlatformOrderId() {
        return platformOrderId;
    }

    public void setPlatformOrderId(String platformOrderId) {
        this.platformOrderId = platformOrderId;
    }

    public String getAmount() {
        return amount;
    }

    public void setAmount(String amount) {
        this.amount = amount;
    }

    public String getStatus() {
        return status;
    }

    public void setStatus(String status) {
        this.status = status;
    }

    public String getMessage() {
        return message;
    }

    public void setMessage(String message) {
        this.message = message;
    }

    public String getPayCode() {
        return payCode;
    }

    public void setPayCode(String payCode) {
        this.payCode = payCode;
    }

    public String getSign() {
        return sign;
    }

    public void setSign(String sign) {
        this.sign = sign;
    }

    public Map toMap() {
        Map<String, Object> params = new HashMap<>(12);
        params.put("tradeNo",this.tradeNo);
        params.put("code",this.code);
        params.put("msg",this.msg);
        params.put("platformOrderId",this.platformOrderId);
        params.put("date",this.date);
        params.put("amount",this.amount);
        params.put("status",this.status);
        params.put("message",this.message);
        params.put("payCode",this.payCode);
        return params;
    }
}
