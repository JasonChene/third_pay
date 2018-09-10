package com.lianruipaypay.util;

/*
 * 联瑞支付卡、银行支付类型编码同中文说明之间的转换
 */
public class lianruipaypayTypeConvert {
	
	public static String chn[] = {
		"",
		"QQ卡",
		"盛大卡",
		"骏网卡",
		"联瑞通",
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
			if(opstate.equals("opstate=0")){
				strResult = "卡提交成功，请等待支付结果";
			}else if(opstate.equals("opstate=-1")){
				strResult = "卡提交失败,原因为提交参数错误";
			}else if(opstate.equals("opstate=-2")){
				strResult = "卡提交失败,原因为提交签名错误";
			}else{
				strResult = "卡提交失败,原因未知,请通知商家检查";
			}
		}
		return strResult;
	}
}
