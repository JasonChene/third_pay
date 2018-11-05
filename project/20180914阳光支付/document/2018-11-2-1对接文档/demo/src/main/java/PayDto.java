import org.apache.commons.lang3.StringUtils;

public class PayDto {
    private String orderid;

    private String ordername;

    private String paymoney;


    private String orderuid;

    private String paytype;

    private String notifyurl;

    private String returnurl;

    private String orderinfo;

    private String appid;
    /**
     * 非必填，默认为 N 不验签
     * Y：验签
     */
    private String isSign;

    //签名字符串
    private String signStr;

    //签名
    private String sign;

    private String signType;
    //是否加密
    private String isEncryption;

    //支付码返回模式  URL ：到我们支付页面   payCode:封装好支付宝阿里协议的支付码，可用来生成二维吗，也可以直接丢进webView或者浏览器直接跳转支付宝
    private String payCodeType;

    public String getPayCodeType() {
        return payCodeType;
    }

    public void setPayCodeType(String payCodeType) {
        this.payCodeType = payCodeType;
    }

    public String getIsEncryption() {
        return isEncryption;
    }

    public void setIsEncryption(String isEncryption) {
        this.isEncryption = isEncryption;
    }

    public String getSignType() {
        return signType;
    }

    public void setSignType(String signType) {
        this.signType = signType;
    }


    public String getSignStr() {
        return signStr;
    }

    public void setSignStr(String signStr) {
        this.signStr = signStr;
    }

    public String getSign() {
        return sign;
    }

    public void setSign(String sign) {
        this.sign = sign;
    }

    public String getIsSign() {
        return isSign;
    }

    public void setIsSign(String isSign) {
        this.isSign = isSign;
    }

    public String getOrderid() {
        return orderid;
    }

    public void setOrderid(String orderid) {
        this.orderid = orderid;
    }

    public String getOrdername() {
        return ordername;
    }

    public void setOrdername(String ordername) {
        this.ordername = ordername;
    }

    public String getPaymoney() {
        return paymoney;
    }

    public void setPaymoney(String paymoney) {
        this.paymoney = paymoney;
    }

    public String getOrderuid() {
        return orderuid;
    }

    public void setOrderuid(String orderuid) {
        this.orderuid = orderuid;
    }

    public String getPaytype() {
        return paytype;
    }

    public void setPaytype(String paytype) {
        this.paytype = paytype;
    }

    public String getNotifyurl() {
        return notifyurl;
    }

    public void setNotifyurl(String notifyurl) {
        this.notifyurl = notifyurl;
    }

    public String getReturnurl() {
        return returnurl;
    }

    public void setReturnurl(String returnurl) {
        this.returnurl = returnurl;
    }

    public String getOrderinfo() {
        return orderinfo;
    }

    public void setOrderinfo(String orderinfo) {
        this.orderinfo = orderinfo;
    }

    public String getAppid() {
        return appid;
    }

    public void setAppid(String appid) {
        this.appid = appid;
    }
}
