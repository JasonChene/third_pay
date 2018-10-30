public class PayDto {

    /**
     * 商户订单编号  必须唯一，否则平台会提示订单号已存在
     */
    private String orderid;

    /**
     * 订单名称
     */
    private String ordername;

    //支付金额
    private String paymoney;


    //订单所属id 一般是商户平台发起充值的用户id
    private String orderuid;

    //支付类型  11:支付宝
    private String paytype;

    //通知url 充值成功后，将会给该字段进行通知支付状态
    //回调受到网络限制，不一定能完全稳定，以防万一请将主动查询接口也对接进去
    private String notifyurl;

    //支付成功客户前台跳转页面
    private String returnurl;

    //订单说明
    private String orderinfo;

    //appid
    private String appid;
    /**
     * 如果值为Y 那就是测试
     * 测试时，将页面保存到ng的路径里面，然后返回请求连接
     */
    private String isTest;

    public String getIsTest() {
        return isTest;
    }

    public void setIsTest(String isTest) {
        this.isTest = isTest;
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
