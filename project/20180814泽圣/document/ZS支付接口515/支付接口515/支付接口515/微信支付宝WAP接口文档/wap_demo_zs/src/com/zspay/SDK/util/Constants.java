package com.zspay.SDK.util;

/**
 * 常量
 * 
 * @author xiongyuanming
 * @version pre.1.0
 * @see
 * @since 2014年7月22日
 */
public interface Constants {
	/** 设置spring mvc数据格式与字符集编码 */
	String PRODUCES = "application/json;charset=UTF-8";
	
	byte[] B8 = { 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00 };
	String F = "F";
	String D = "D";
	String A = "A";
	String Y = "Y";
	/** 日志标记 */
	String LOG_FLAG = "log_flag";

	/** 编码 */
	String ENCODE = "UTF-8";

	/** packet */
	String PACKET = "PACKET";

	String EQUAL = "=";

	/** 支付卡(转出卡) */
	String CARDNO = "cardNo";
	/** 转入卡 */
	String INTOCARDNO = "intoCardNo";
	/** 收款账户名称 */
	String INTOCARDNAME = "intoCardName";
	String SN = "sn";
	String PINRANDOM = "pinRandom";
	String TRACKRANDOM = "trackRandom";
	/** 加密参数 */
	String ZERO = "0";
	String DOUBLE_ZERO = "00";
	String ONE = "1";
	String TWO = "2";
	String YES = "YES";
	String NO = "NO";

	/** 商户号 */
	String MERCHANTCODE = "merchantCode";
	/** 商户用户体系的用户Id */
	String OUTUSERID = "outUserId";
	String PAYERID = "payerId";
	String NONCESTR = "nonceStr";

	/** 订单创建时间 */
	String ORDERCREATETIME = "orderCreateTime";
	/** 最后支付时间 */
	String LASTPAYTIME = "lastPayTime";
	String TOTALAMOUNT = "totalAmount";
	String CURRENCY = "currency";
	String GOODSNAME = "goodsName";
	String GOODSEXPLAIN = "goodsExplain";
	/** pos支付 */
	String TOKEN = "token";
	String PACKCHIPER = "packChiper";
	String CONTROLTYPE = "controlType";
	String TRANSTYPE = "transType";
	String INSTRUCTCODE = "instructCode";
	String TRANSTIME = "transTime";
	/** 卡类型 */
	String CARDTYPE = "cardType";
	String PRODUCTCODE = "productCode";
	String PAYCHANNEL = "payChannel";
	String PINCHIPER = "pinChiper";
	String TRACKINFO = "trackInfo";
	String ICDATA = "icData";
	String AMOUNT = "amount";
	String FEE = "fee";

	String OUTORDERID = "outOrderId";
	String PAYCARDNO = "payCardNo";

	/** 验证商户 */
	String SIGN_SRC = "sign_src";
	String SIGN = "sign";
	String PAYKEY = "payKey";
	String TWK = "twk";
	String WK = "wk";
	String MERCHANTNAME = "merchantName";

	String PAYNOTIFYURL = "payNotifyUrl";
	String PIN = "pin";
	String TRACK1 = "track1";
	String TRACK2 = "track2";
	String TRACK3 = "track3";
	String ICNUMBER = "icNumber";
	String EXPIRE = "expire";
	String DCDATA = "dcData";

	/** 错误码定义 */
	String CODE = "code";
	String MSG = "msg";
	String DATA = "data";
	/** 令牌失效时间 */
	String TOKEN_BOLISH = "TOKEN_BOLISH";
	/** 系统错误提示配置 */
	String SYSERROR = "SYSERROR";
	/** 令牌对象KEY */
	String TOKEN_OBJ = "TOKEN_OBJ";
	/** 产品对象 */
	String PRODUCT_OBJ = "PRODUCT_OBJ";
	String RETURNINSTRUCTCODE = "returnInstructCode";
	String RETURNTRANSTIME = "returnTransTime";
	String ORIINSTRUCTCODE = "oriInstructCode";
	String REPLYCODE = "replyCode";
	/** 卡号输入方式 */
	String CARDNOINPUTTYPE = "cardNoInputType";
	/** 证件类型 */
	String IDTYPE = "idType";
	/** 证件号 */
	String IDNO = "idNo";
	/** 持卡人姓名 */
	String HOLDER = "holder";
	/** 电话号码 */
	String PHONE = "phone";
	/** 成员类型 */
	String MEMBERTYPE = "memberType";
	/** 成员ID */
	String MEMBERID = "memberId";
	/** 合同编号 */
	String CONTRACTNO = "contractNo";
	/** 备注 */
	String REMARK = "remark";
	/** 页码 */
	String PAGENO = "pageNo";
	/** 每页记录数 */
	String PAGESIZE = "pageSize";
	Integer TEN = 10;
	Integer NUM_1 = 1;
	Integer NUM_0 = 0;
	/** 列表 */
	String LIST = "list";
	/** 总记录数 */
	String TOTALSIZE = "totalSize";
	/** cvn2数据 */
	String CVN2 = "cvn2";
	/** 发卡机构名 */
	String ISSUERNAME = "issuerName";
	/** 代扣类型01-普通,02-实名 */
	String WITHHOLDTYPE = "withholdType";
	/** 订单ID */
	String ORDERID = "orderId";
	/** 转账 */
	String RESULT = "result";
	/** 状态 */
	String STATE = "state";
	/** 姓名 */
	String NAME = "name";
	/** 加密模式1-非设备,2-设备 */
	String DECRYPTMODEL = "decryptModel";
	/** 银行代码 */
	String BANKCODE = "bankCode";
	/** 转入银行卡名称 */
	String BANKNAME = "bankName";
	/** 开始日期 */
	String STARTDATE = "startDate";
	/** 结束日期 */
	String ENDDATE = "endDate";
	/** 转入卡银行名称 */
	String INTOBANKNAME = "intoBankName";
	/** 转账完成时间 */
	String FINISHTIME = "finishTime";
	/** 转入卡类型 */
	String INTOCARDTYPE = "intoCardType";
	/** 卡类型-中文 */
	String CARDTYPECN = "cardTypeCN";
	/** 银行LOGO URL */
	String BANKLOGO = "bankLogo";
	/** 元素位图 */
	String ELEMENTSMAP = "elementsMap";
	/** 短信验证码 */
	String SMSCODE = "smsCode";
	/** 是否支持借记卡 */
	String ISSUPPORTDEBIT = "isSupportDebit";
	/** 是否支持贷记卡 */
	String ISSUPPORTCREDIT = "isSupportCredit";
	/** 支付类型 */
	String PAYTYPE = "payType";
	/** 是否绑定支付银行卡 */
	String ISBIND = "isBind";
	/** 设备主密钥 */
	String DWK = "dwk";
	/** 银行卡介质（磁条，ic卡） */
	String CARDMEDIUM = "cardMedium";
	/** 订单类型 */
	String ORDERTYPE = "orderType";
	/** 原始交易订单号 */
	String ORIORDERID = "oriOrderId";
	/** 订单状态 */
	String ORDERSTATE = "orderState";
	/** 订单状态中文 */
	String ORDERSTATECN = "orderStateCN";
	/** 地址 */
	String URL = "url";
	/** logo地址1 */
	String LOGOURL1 = "logoUrl1";
	/** logo地址2 */
	String LOGOURL2 = "logoUrl2";
	/** 余额 */
	String BALANCE = "balance";
	/** 签名图片目录 配置 */
	String SIGN_DIR = "SIGN_DIR";
	/** 签名图片url 配置 */
	String SIGN_URL = "SIGN_URL";
	/** 是否带有刷卡设备 */
	String ISDEVICE = "isDevice";
	/** 创建时间 */
	String CREATETIME = "createTime";
	/** 脚本 */
	String SCRIPT = "script";
	/** 通知类型 */
	String NOTICETYPE = "noticeType";
	/** 输入模式 */
	String INPUTMODE = "inputMode";
	/** 可用余额 */
	String AVAILABLEBALANCE = "availableBalance";
	/** 冻结余额 */
	String FROZENBALANCE = "frozenBalance";
	/** 昵称 */
	String NICK = "nick";
	/** 密码 */
	String PASSWORD = "password";
	/** 支付渠道（支付编码） */
	String TRADEWAY = "tradeWay";
	/** 实名认证状态 */
	String REALNAMEAUTHSTATE = "realNameAuthState";
	/** 银行支付地址 */
	String BANKURL = "bankUrl";
	/** 商户取货地址 */
	String MERURL = "merUrl";
	/** 请求参数 */
	String PARAMS = "params";
	/** 自动跳转 */
	String AUTOJUMP = "autoJump";
	/** 跳转等到时间 */
	String WAITTIME = "waitTime";
	/** 通知地址 */
	String NOTICEURL = "noticeUrl";
	/** 是否在商户端选择银行 */
	String BANKINPUT = "bankInput";
	/** 支付银行卡类型 */
	String BANKCARDTYPE = "bankCardType";
	/** 圈存成功时间 */
	String LOADSUCCTIME = "loadSuccTime";
	/** 圈存状态 */
	String LOADSTATE = "loadState";
	/** 圈存银行卡卡号 */
	String BANKCARDNO = "bankCardNo";
	/** 申请人 */
	String APPLICANT = "applicant";
}
