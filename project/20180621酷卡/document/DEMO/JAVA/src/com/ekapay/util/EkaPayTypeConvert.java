package com.ekapay.util;

/*
 * 点卡、银行支付类型编码同中文说明之间的转换
 */
public class EkaPayTypeConvert {
	
	public static String chn[] = {
		"",
		"QQ卡",
		"盛大卡",
		"骏网卡",
		"亿卡通",
		"完美一卡通",
		"搜狐一卡通",
		"征途游戏卡",
		"久游一卡通",
		"网易一卡通",
		"魔兽卡",
		"联华卡",
		"电信充值卡",
		"神州行充值卡",
		"联通充值卡",
		"金山一卡通",
		"光宇一卡通",
		"神州行浙江卡",
		"神州行江苏卡",
		"神州行辽宁卡",
		"神州行福建卡"
	};
	/*
	 * 卡类型编码转换为中文说明
	 * 输入：卡类型编码值
	 * 输出：对应输入的卡类型中文说明
	 *      当输入卡类型不满足条件时，输出为空
	 */
	public static String cardTypeToChn(String type){
		if (type == null || type.length() == 0) {
			return "";
		}
		int intType = Integer.valueOf(type).intValue();
		
		if( intType > chn.length -1 || intType < 1){
			return "";
		}
		return chn[intType];
	}
	
	public static String opstateValueToChn(String opstate){
		String strResult = "";
		if(opstate == null || opstate.length() == 0){
			strResult = "卡提交失败,原因为网络不通";
		}else{
			if(opstate.equals("opstate=0")){//同步返回opstate=0不表示支付成功了，只表示提交成功，只有异步返回opstate=0才表示支付成功
				strResult = "卡提交成功，请等待支付结果";
			}else if(opstate.equals("opstate=-1")){
				strResult = "提交参数错误";
			}else if(opstate.equals("opstate=-2")){
				strResult = "签名错误";
			}else if(opstate.equals("opstate=-3")){
				strResult = "卡密为重复提交";
			}else if(opstate.equals("opstate=-4")){
				strResult = "卡密不符合定义的卡号密码面值规则";
			}else if(opstate.equals("opstate=-999")){
				strResult = "接口在维护";
			}else if(opstate.equals("opstate=2")){
				strResult = "不支持该类卡或者该面值的卡";
			}else if(opstate.equals("opstate=3")){
				strResult = "验证签名失败";
			}else if(opstate.equals("opstate=4")){
				strResult = "订单内容重复";
			}else if(opstate.equals("opstate=5")){
				strResult = "该卡密已经有被使用的记录";
			}else if(opstate.equals("opstate=6")){
				strResult = "订单号已经存在";
			}else if(opstate.equals("opstate=7")){
				strResult = "数据非法";
			}else if(opstate.equals("opstate=8")){
				strResult = "非法用户";
			}else if(opstate.equals("opstate=9")){
				strResult = "暂时停止该类卡或者该面值的卡交易";
			}else if(opstate.equals("opstate=10")){
				strResult = "充值卡无效";
			}else if(opstate.equals("opstate=11")){
				strResult = "支付成功,实际面值与订单金额不符";
			}else if(opstate.equals("opstate=12")){
				strResult = "处理失败，卡密未使用";
			}else if(opstate.equals("opstate=13")){
				strResult = "系统繁忙";
			}else if(opstate.equals("opstate=14")){
				strResult = "不存在该笔订单";
			}else if(opstate.equals("opstate=15")){
				strResult = "未知请求";
			}else if(opstate.equals("opstate=16")){
				strResult = "密码错误";
			}else if(opstate.equals("opstate=17")){
				strResult = "匹配订单失败";
			}else if(opstate.equals("opstate=18")){
				strResult = "余额不足";
			}else if(opstate.equals("opstate=19")){
				strResult = "运营商维护";
			}else if(opstate.equals("opstate=20")){
				strResult = "提交次数过多";
			}else if(opstate.equals("opstate=99")){
				strResult = "充值失败，请重试";
			}else if(opstate.equals("opstate=33")){
				strResult = "提交失败，原因未知";
			}else{
				strResult = "无效充值";
			}
		}
		return strResult;
	}
}
