package demo;

import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.HashMap;
import java.util.Map;

public class Test {

	public static void main(String[] args) throws Exception {
		String partner = "";//�̻���
		String key = "";//�̻���Կ
		Map<String,String> map = new HashMap<String,String>();
		map.put("payType", "syt");//�ӿ�����(syt:����̨)
		map.put("partner", partner);//�̻���
		map.put("orderId", new SimpleDateFormat("yyyyMMddHHmmssSSS").format(new Date()));//�̻�������
		map.put("orderAmount", "1");//�̻��������(�����С0.01)
		map.put("version", "1.0");//�ӿڰ汾(1.0)
		map.put("signType", "MD5");//ǩ����ʽ(MD5)
		map.put("payMethod", "22");//֧����ʽ:11��΢�� 22��֧����33:QQ֧��  ֮ǰ��֧������22����PC����23�����ֻ�����24
		map.put("notifyUrl", "http://www.baidu.com");//�첽�ص���ַ
		//��ǩ���ַ���
		String param = YsfUtil.getSignStr(map, key);
		//ǩ��
		String sign = YsfUtil.md5UTF8(param).toUpperCase();
		map.put("sign", sign);
		//��װ����url,�û�ʹ��app����������򿪸����Ӽ��ɻ���֧����app֧��
		String reqUrl = YsfUtil.getReqUrl(map,"http://qr.sytpay.cn/api/v1/create.php");
		System.out.println(reqUrl);
		
	}
}
