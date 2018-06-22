package com.ekapay.util;

/*
 * �㿨������֧�����ͱ���ͬ����˵��֮���ת��
 */
public class EkaPayTypeConvert {
	
	public static String chn[] = {
		"",
		"QQ��",
		"ʢ��",
		"������",
		"�ڿ�ͨ",
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
			if(opstate.equals("opstate=0")){//ͬ������opstate=0����ʾ֧���ɹ��ˣ�ֻ��ʾ�ύ�ɹ���ֻ���첽����opstate=0�ű�ʾ֧���ɹ�
				strResult = "���ύ�ɹ�����ȴ�֧�����";
			}else if(opstate.equals("opstate=-1")){
				strResult = "�ύ��������";
			}else if(opstate.equals("opstate=-2")){
				strResult = "ǩ������";
			}else if(opstate.equals("opstate=-3")){
				strResult = "����Ϊ�ظ��ύ";
			}else if(opstate.equals("opstate=-4")){
				strResult = "���ܲ����϶���Ŀ���������ֵ����";
			}else if(opstate.equals("opstate=-999")){
				strResult = "�ӿ���ά��";
			}else if(opstate.equals("opstate=2")){
				strResult = "��֧�ָ��࿨���߸���ֵ�Ŀ�";
			}else if(opstate.equals("opstate=3")){
				strResult = "��֤ǩ��ʧ��";
			}else if(opstate.equals("opstate=4")){
				strResult = "���������ظ�";
			}else if(opstate.equals("opstate=5")){
				strResult = "�ÿ����Ѿ��б�ʹ�õļ�¼";
			}else if(opstate.equals("opstate=6")){
				strResult = "�������Ѿ�����";
			}else if(opstate.equals("opstate=7")){
				strResult = "���ݷǷ�";
			}else if(opstate.equals("opstate=8")){
				strResult = "�Ƿ��û�";
			}else if(opstate.equals("opstate=9")){
				strResult = "��ʱֹͣ���࿨���߸���ֵ�Ŀ�����";
			}else if(opstate.equals("opstate=10")){
				strResult = "��ֵ����Ч";
			}else if(opstate.equals("opstate=11")){
				strResult = "֧���ɹ�,ʵ����ֵ�붩������";
			}else if(opstate.equals("opstate=12")){
				strResult = "����ʧ�ܣ�����δʹ��";
			}else if(opstate.equals("opstate=13")){
				strResult = "ϵͳ��æ";
			}else if(opstate.equals("opstate=14")){
				strResult = "�����ڸñʶ���";
			}else if(opstate.equals("opstate=15")){
				strResult = "δ֪����";
			}else if(opstate.equals("opstate=16")){
				strResult = "�������";
			}else if(opstate.equals("opstate=17")){
				strResult = "ƥ�䶩��ʧ��";
			}else if(opstate.equals("opstate=18")){
				strResult = "����";
			}else if(opstate.equals("opstate=19")){
				strResult = "��Ӫ��ά��";
			}else if(opstate.equals("opstate=20")){
				strResult = "�ύ��������";
			}else if(opstate.equals("opstate=99")){
				strResult = "��ֵʧ�ܣ�������";
			}else if(opstate.equals("opstate=33")){
				strResult = "�ύʧ�ܣ�ԭ��δ֪";
			}else{
				strResult = "��Ч��ֵ";
			}
		}
		return strResult;
	}
}
