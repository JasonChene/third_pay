package com.lianruipaypay.util;

/*
 * ����֧����������֧�����ͱ���ͬ����˵��֮���ת��
 */
public class lianruipaypayTypeConvert {
	
	public static String chn[] = {
		"",
		"QQ��",
		"ʢ��",
		"������",
		"����ͨ",
		"����һ��ͨ",
		"�Ѻ�һ��ͨ",
		"��;��Ϸ��",
		"����һ��ͨ",
		"����һ��ͨ",
		"ħ�޿�",
		"������",
		"���ų�ֵ��",
		"�����г�ֵ��",
		"��ͨ��ֵ��",
		"��ɽһ��ͨ",
		"����һ��ͨ",
		"�������㽭��",
		"�����н��տ�",
		"������������",
		"�����и�����"
	};
	/*
	 * �����ͱ���ת��Ϊ����˵��
	 * ���룺�����ͱ���ֵ
	 * �������Ӧ����Ŀ���������˵��
	 *      �����뿨���Ͳ���������ʱ�����Ϊ��
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
			strResult = "���ύʧ��,ԭ��Ϊ���粻ͨ";
		}else{
			if(opstate.equals("opstate=0")){
				strResult = "���ύ�ɹ�����ȴ�֧�����";
			}else if(opstate.equals("opstate=-1")){
				strResult = "���ύʧ��,ԭ��Ϊ�ύ��������";
			}else if(opstate.equals("opstate=-2")){
				strResult = "���ύʧ��,ԭ��Ϊ�ύǩ������";
			}else{
				strResult = "���ύʧ��,ԭ��δ֪,��֪ͨ�̼Ҽ��";
			}
		}
		return strResult;
	}
}
