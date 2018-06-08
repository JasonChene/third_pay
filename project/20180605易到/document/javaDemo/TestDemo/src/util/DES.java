package util;
import java.security.Key;
import java.security.Security;
import javax.crypto.Cipher;

public class DES {
	
		/**
		 * @param encryptdata
		 *            要加密的明码
		 * @param encryptkey
		 *            加密的密钥
		 * @return 加密后的暗码
		 * @throws Exception
		 */
		public static String encryptData(String encryptdata, String encryptkey){
			try{
				DESPlus desPlus = new DESPlus(encryptkey);
				return desPlus.encrypt(encryptdata);
			}catch(Exception e){
				e.printStackTrace();
			}
			return "";
		}

		/**
		 * @param decryptdata
		 *            要解密的暗码
		 * @param decryptkey
		 *            解密的密钥
		 * @return 解密后的明码
		 * @throws Exception
		 */
		public static String decryptData(String decryptdata, String decryptkey)
				throws Exception {
			DESPlus desPlus = new DESPlus(decryptkey); 
			return desPlus.decrypt(decryptdata);
		}
		//线上的key 对于实名认证
		static String key1 = "DTDX`GYJX`19PAY`HLWZFYWB`CESHI";
		static String key3="LwU1FKqujPQ5AnmKt8SoYwdlqILzKd9zbOhOLcAnUtJXTRP3lYPwkeZfzl2pgmTG70w8NZsyza7IInGgb5atwBgXW7IQugG6jI45TL75WtJifIMEmeyltAnH9DfunLRE";
		static String mp_bind_card_main = "QUuxNklOEVsk0JGWaxIFLJvDfZzh0mJepKGW2lyD6Ft7BjIaknOLhBTzheVBUFLDdxR27RWvKJT2xsmAHEB9roskXvkhDzfpEsLed71Qp69txE8dhy8VM7GvAJFGi5L1";
		static String key4="yyjn3m8rq8wq4dz67ad446hrj194z4sm6kbjar4dp2z5vg9qxodacov2xqtnbr4ermmo7f0aj2vau0b2rtwfsruuvixuat33baomza3fsoj8s8nfi03rhn40oeehatg0";
		static String key5="lv4ww8jwlrjqr6xmipmqpdup129k62mxla3j2mpdlu9614ji5abhj1mwh8rt62kb7bbzsqjfnkcpkdu9i83ve5zbmsir9xdj5q58agt7fy7jbmeirivovjvlecllul5n";
		public static void main(String[] args) throws Exception
		{
			
			//加密的密钥
			//String decryData = CipherUtil.decryptData("BD140824180915102900",key1);
		String decryData2 = DES.encryptData("湛续辉","0qukbrx6g82dgcww2gaixhqbxyuhy5bx59r3y7svbxzm6n7x655c4e9s1wlr58mped1lr1vhpc4bnqbsi4jvzrfcplqvds5dp9relq00wdznyhx64637qhzz52jthjkt");
			//System.out.println(decryData);
		System.out.println(decryData2);
			
			//解密的李春晓
			String decryData3 = DES.decryptData("6fb82940a9b01763379989edce4eaa48","0qukbrx6g82dgcww2gaixhqbxyuhy5bx59r3y7svbxzm6n7x655c4e9s1wlr58mped1lr1vhpc4bnqbsi4jvzrfcplqvds5dp9relq00wdznyhx64637qhzz52jthjkt");
			//System.out.println(decryData);
			System.out.println(decryData3);
			
		}
		
	}

	class DESPlus {

		private Cipher encryptCipher = null;

		private Cipher decryptCipher = null;

		/**
		 * 将byte数组转换为表示16进制值的字符串， 如：byte[]{8,18}转换为：0813， 和public static byte[]
		 * hexStr2ByteArr(String strIn) 互为可逆的转换过程
		 * 
		 * @param arrB
		 *            需要转换的byte数组
		 * @return 转换后的字符串
		 * @throws Exception
		 *             本方法不处理任何异常，所有异常全部抛出
		 */
		public String byteArr2HexStr(byte[] arrB) throws Exception {
			int iLen = arrB.length;
			// 每个byte用两个字符才能表示，所以字符串的长度是数组长度的两倍
			StringBuffer sb = new StringBuffer(iLen * 2);
			for (int i = 0; i < iLen; i++) {
				int intTmp = arrB[i];
				// 把负数转换为正数
				while (intTmp < 0) {
					intTmp = intTmp + 256;
				}
				// 小于0F的数需要在前面补0
				if (intTmp < 16) {
					sb.append("0");
				}
				sb.append(Integer.toString(intTmp, 16));
			}
			return sb.toString();
		}

		/**
		 * 将表示16进制值的字符串转换为byte数组， 和public static String byteArr2HexStr(byte[] arrB)
		 * 互为可逆的转换过程
		 * 
		 * @param strIn
		 *            需要转换的字符串
		 * @return 转换后的byte数组
		 * @throws Exception
		 *             本方法不处理任何异常，所有异常全部抛出
		 * @author LiGuoQing
		 */
		public byte[] hexStr2ByteArr(String strIn) throws Exception {
			byte[] arrB = strIn.getBytes();
			int iLen = arrB.length;

			// 两个字符表示一个字节，所以字节数组长度是字符串长度除以2
			byte[] arrOut = new byte[iLen / 2];
			for (int i = 0; i < iLen; i = i + 2) {
				String strTmp = new String(arrB, i, 2);
				arrOut[i / 2] = (byte) Integer.parseInt(strTmp, 16);
			}
			return arrOut;
		}

		/**
		 * 指定密钥构造方法
		 * 
		 * @param strKey
		 *            指定的密钥
		 * @throws Exception
		 */
		public DESPlus(String strKey) throws Exception {
			Security.addProvider(new com.sun.crypto.provider.SunJCE());
			Key key = getKey(strKey.getBytes());

			encryptCipher = Cipher.getInstance("DES");
			encryptCipher.init(Cipher.ENCRYPT_MODE, key);

			decryptCipher = Cipher.getInstance("DES");
			decryptCipher.init(Cipher.DECRYPT_MODE, key);
		}

		/**
		 * 加密字节数组
		 * 
		 * @param arrB
		 *            需加密的字节数组
		 * @return 加密后的字节数组
		 * @throws Exception
		 */
		public byte[] encrypt(byte[] arrB) throws Exception {
			return encryptCipher.doFinal(arrB);
		}

		/**
		 * 加密字符串
		 * 
		 * @param strIn
		 *            需加密的字符串
		 * @return 加密后的字符串
		 * @throws Exception
		 */
		public String encrypt(String strIn) throws Exception {
			return byteArr2HexStr(encrypt(strIn.getBytes("UTF-8")));
		}

		/**
		 * 解密字节数组
		 * 
		 * @param arrB
		 *            需解密的字节数组
		 * @return 解密后的字节数组
		 * @throws Exception
		 */
		public byte[] decrypt(byte[] arrB) throws Exception {
			return decryptCipher.doFinal(arrB);
		}

		/**
		 * 解密字符串
		 * 
		 * @param strIn
		 *            需解密的字符串
		 * @return 解密后的字符串
		 * @throws Exception
		 */
		public String decrypt(String strIn) throws Exception {
			return new String(decrypt(hexStr2ByteArr(strIn)),"UTF-8");
		}

		/**
		 * 从指定字符串生成密钥，密钥所需的字节数组长度为8位 不足8位时后面补0，超出8位只取前8位
		 * 
		 * @param arrBTmp
		 *            构成该字符串的字节数组
		 * @return 生成的密钥
		 * @throws java.lang.Exception
		 */
		private Key getKey(byte[] arrBTmp) throws Exception {
			// 创建一个空的8位字节数组（默认值为0）
			byte[] arrB = new byte[8];

			// 将原始字节数组转换为8位
			for (int i = 0; i < arrBTmp.length && i < arrB.length; i++) {
				arrB[i] = arrBTmp[i];
			}

			// 生成密钥
			Key key = new javax.crypto.spec.SecretKeySpec(arrB, "DES");

			return key;
		}
		
	}
