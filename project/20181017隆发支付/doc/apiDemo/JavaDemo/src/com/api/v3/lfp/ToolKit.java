package com.api.v3.lfp;

import java.io.BufferedInputStream;
import java.io.ByteArrayOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;
import java.net.HttpURLConnection;
import java.net.URL;
import java.security.InvalidKeyException;
import java.security.KeyFactory;
import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;
import java.security.PrivateKey;
import java.security.PublicKey;
import java.security.spec.InvalidKeySpecException;
import java.security.spec.PKCS8EncodedKeySpec;
import java.security.spec.X509EncodedKeySpec;
import java.util.Base64;
import java.util.Iterator;
import java.util.Map;
import java.util.Random;

import javax.crypto.BadPaddingException;
import javax.crypto.Cipher;
import javax.crypto.IllegalBlockSizeException;
import javax.crypto.NoSuchPaddingException;

public class ToolKit {
	static final char HEX_DIGITS[] = { '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F' };
	// 支付公钥
	public static final String PAY_PUBLIC_KEY = "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDHuFeMRs5k7DZwi6QPYlPVFRsGv7CytIMyK2XvdXH0YRCYM3OZhXH6l3TCrOrGtJax01N0jyVsqxaaiYqfR06IarJGWYqTY9IXI6mvP1Fd33YNmByw0Mm0VsvGoChKlzMI8GV/Wt0lb3oGdBPhvCVuxpN59QjeqekHmgUzwr9OIwIDAQAB";
	// 代付公钥
	public static final String REMIT_PUBLIC_KEY = "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDMX7BiAroLRgq7U4zVmIjZ0OS7pMETJ3chAeN3LPK2SIcVhtlog/NtZTUgqF7eV0tb5JqIO9SjiFxY9rAHoCQhocTEi3KWcY7xbOlbqRB8sfLPJbFADCRmXaG8AozaURsWU2yut7WF5sWwHYZsJ0/wWea+2FsEZ1vktM8/fS4VRQIDAQAB";
	
	// 商户私钥
	public static final String PRIVATE_KEY = 
			"MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBAKIE9OWSfOqoxKZTsOmaOXV0KdD4" + 
			"4Bhz0JFxv/NaD36xu1Jfeg4MMb6yqPqckmSFE/+BFU+doHTC3VXqqWZbo6pfAL8r7P9oIWGoX+EJ" + 
			"1MyP/m92y15UXFdtA+DyyT1Rw24Gn/M46Vhg8RhsZ7a8/w/63LXGBVVUBaa2QBSidyB7AgMBAAEC" + 
			"gYEAjlnGhdIwGMjRhsWvgX+0jkLS3r1bv4A6Nx/3I6v0Qz+LMvOaPK9wIXm9x/s7EEBwcqQKgloe" + 
			"+ZegenVkAv/vCkDI7tC0M4F3+dB06UM3zzvr9iRqnFnjVkbgF/nSkUMgd0wqfSaGDyA7Koq3cliW" + 
			"GNRSsz69Tjrp9lxfW/XuamkCQQDjQaphUX685dRA4G4jdZW0LxDIUelhD74vExp9GwzAE4BVnT3S" + 
			"dsdELia1osgujZOheoewpyGtw+xmgIs73hfHAkEAtoL6jaP9KiyxX82eOqqEYhftROTgInee9oCB" + 
			"+/CpAFRRj8PXjbnpdKx0DiPuiv3GTPIpqq5zWXdTkuw2Urx5rQJALT+w5gms+32jgnttUqAh+6t3" + 
			"Yvt0RYA19PePyaMuKbx4TBOv8Iz35ipuSLItu58y6u8Tv2e3u/qLwmkL0BLLlwJBALOh0GbOC6OR" + 
			"33Yyk1ScdbVyZUYUmoFyCNQHAWhtoni/hJMiJyrH233JKmSseuD0C1O6WMYz8zYQGwKVWJYwCPUC" + 
			"QAK2NQ3vJppXA3NydlRqsUUDMrjkymO1n9hcYErfxC9ThDk+/uV6tURZawODR0QvroZjcwDA3ilT" + 
			"PKLcDyB74No=";

	//public static int blockSize = 128;
	// 非对称密钥算法
	public static final String KEY_ALGORITHM = "RSA";
	public final static String CHARSET = "UTF-8";

	/**
	 * 获取响应报文
	 * 
	 * @param in
	 * @return
	 */
	private static String getResponseBodyAsString(InputStream in) {
		try {
			BufferedInputStream buf = new BufferedInputStream(in);
			byte[] buffer = new byte[1024];
			StringBuffer data = new StringBuffer();
			int readDataLen;
			while ((readDataLen = buf.read(buffer)) != -1) {
				data.append(new String(buffer, 0, readDataLen, CHARSET));
			}
			System.out.println("响应报文=" + data);
			return data.toString();
		} catch (Exception e) {
			e.printStackTrace();
		}
		return null;
	}

	/**
	 * 提交请求
	 * 
	 * @param url
	 * @param params
	 * @return
	 */
	public static String request(String url, String params) {
		try {
			System.out.println("请求报文:" + params);
			URL urlObj = new URL(url);
			HttpURLConnection conn = (HttpURLConnection) urlObj.openConnection();
			conn.setRequestMethod("POST");
			conn.setDoOutput(true);
			conn.setDoInput(true);
			conn.setUseCaches(false);
			conn.setConnectTimeout(1000 * 5);
			//conn.setRequestProperty("User-Agent","Mozilla/5.0 ( compatible ) ");
			//conn.setRequestProperty("Accept","*/*");
			conn.setRequestProperty("Charset", CHARSET);
			conn.setRequestProperty("Content-Type", "application/x-www-form-urlencoded");
			conn.setRequestProperty("Content-Length", String.valueOf(params.length()));
			OutputStream outStream = conn.getOutputStream();
			outStream.write(params.toString().getBytes(CHARSET));
			outStream.flush();
			outStream.close();
			return getResponseBodyAsString(conn.getInputStream());
		} catch (Exception e) {
			e.printStackTrace();
			return null;
		}
	}

	/**
	 * MD5加密
	 * 
	 * @param s
	 * @param encoding
	 * @return
	 */
	public final static String MD5(String s, String encoding) {
		try {
			byte[] btInput = s.getBytes(encoding);
			MessageDigest mdInst = MessageDigest.getInstance("MD5");
			mdInst.update(btInput);
			byte[] md = mdInst.digest();
			int j = md.length;
			char str[] = new char[j * 2];
			int k = 0;
			for (int i = 0; i < j; i++) {
				byte byte0 = md[i];
				str[k++] = HEX_DIGITS[byte0 >>> 4 & 0xf];
				str[k++] = HEX_DIGITS[byte0 & 0xf];
			}
			return new String(str);
		} catch (Exception e) {
			e.printStackTrace();
			return null;
		}
	}

	/**
	 * 转换成Json格式
	 * 
	 * @param map
	 * @return
	 */
	public static String mapToJson(Map<String, String> map) {
		Iterator<Map.Entry<String, String>> it = map.entrySet().iterator();
		StringBuffer json = new StringBuffer();
		json.append("{");
		while (it.hasNext()) {
			Map.Entry<String, String> entry = it.next();
			String key = entry.getKey();
			String value = entry.getValue();
			json.append("\"").append(key).append("\"");
			json.append(":");
			json.append("\"").append(value).append("\"");
			if (it.hasNext()) {
				json.append(",");
			}
		}
		json.append("}");
		System.out.println("mapToJson=" + json.toString());
		return json.toString();
	}

	/**
	 * 生成随机字符
	 * 
	 * @param num
	 * @return
	 */
	public static String randomStr(int num) {
		char[] randomMetaData = new char[] { 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o',
				'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',
				'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '0', '1', '2', '3', '4',
				'5', '6', '7', '8', '9' };
		Random random = new Random();
		String tNonceStr = "";
		for (int i = 0; i < num; i++) {
			tNonceStr += (randomMetaData[random.nextInt(randomMetaData.length - 1)]);
		}
		return tNonceStr;
	}

	/**
	 * 公钥加密
	 * 
	 * @param data待加密数据
	 * @param key
	 *            密钥
	 * @return byte[] 加密数据
	 */
	public static byte[] encryptByPublicKey(byte[] data, String publicKey) {
		byte[] key = Base64.getDecoder().decode(publicKey);
		// 实例化密钥工厂
		KeyFactory keyFactory;
		try {
			keyFactory = KeyFactory.getInstance(KEY_ALGORITHM);
			// 密钥材料转换
			X509EncodedKeySpec x509KeySpec = new X509EncodedKeySpec(key);
			// 产生公钥
			PublicKey pubKey = keyFactory.generatePublic(x509KeySpec);
			// 数据加密
			Cipher cipher = Cipher.getInstance(keyFactory.getAlgorithm());
			cipher.init(Cipher.ENCRYPT_MODE, pubKey);
			int blockSize = cipher.getOutputSize(data.length) - 11;
			return doFinal(data, cipher,blockSize);

		} catch (NoSuchAlgorithmException e) {
			e.printStackTrace();
		} catch (InvalidKeySpecException e) {
			e.printStackTrace();
		} catch (NoSuchPaddingException e) {
			e.printStackTrace();
		} catch (InvalidKeyException e) {
			e.printStackTrace();
		} catch (IllegalBlockSizeException e) {
			e.printStackTrace();
		} catch (BadPaddingException e) {
			e.printStackTrace();
		} catch (IOException e) {
			e.printStackTrace();
		}
		return null;
	}

	/**
	 * 私钥解密
	 * 
	 * @param data
	 *            待解密数据
	 * @param key
	 *            密钥
	 * @return byte[] 解密数据
	 */
	public static byte[] decryptByPrivateKey(byte[] data, String privateKeyValue) {
		byte[] key = Base64.getDecoder().decode(privateKeyValue);
		try {
			// 取得私钥
			PKCS8EncodedKeySpec pkcs8KeySpec = new PKCS8EncodedKeySpec(key);
			KeyFactory keyFactory = KeyFactory.getInstance(KEY_ALGORITHM);
			// 生成私钥
			PrivateKey privateKey = keyFactory.generatePrivate(pkcs8KeySpec);
			// 数据解密
			Cipher cipher = Cipher.getInstance(keyFactory.getAlgorithm());
			cipher.init(Cipher.DECRYPT_MODE, privateKey);
			int blockSize = cipher.getOutputSize(data.length);
			return doFinal(data, cipher,blockSize);
		} catch (NoSuchAlgorithmException e) {
			e.printStackTrace();
		} catch (InvalidKeySpecException e) {
			e.printStackTrace();
		} catch (NoSuchPaddingException e) {
			e.printStackTrace();
		} catch (InvalidKeyException e) {
			e.printStackTrace();
		} catch (IllegalBlockSizeException e) {
			e.printStackTrace();
		} catch (BadPaddingException e) {
			e.printStackTrace();
		} catch (IOException e) {
			e.printStackTrace();
		}
		return null;
	}

	/**
	 * 加密解密共用核心代码，分段加密解密
	 * 
	 * @param decryptData
	 * @param cipher
	 * @return
	 * @throws BadPaddingException
	 * @throws IllegalBlockSizeException
	 * @throws IOException
	 */
	public static byte[] doFinal(byte[] decryptData, Cipher cipher,int blockSize)
			throws IllegalBlockSizeException, BadPaddingException, IOException {
		int offSet = 0;
		byte[] cache = null;
		int i = 0;
		ByteArrayOutputStream out = new ByteArrayOutputStream();

		while (decryptData.length - offSet > 0) {
			if (decryptData.length - offSet > blockSize) {
				cache = cipher.doFinal(decryptData, offSet, blockSize);
			} else {
				cache = cipher.doFinal(decryptData, offSet, decryptData.length - offSet);
			}
			out.write(cache, 0, cache.length);
			i++;
			offSet = i * blockSize;
		}
		byte[] encryptedData = out.toByteArray();
		out.close();
		return encryptedData;
	}

	public static void main(String[] args) throws Exception {
	}
}
