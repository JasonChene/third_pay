package util;

import java.security.KeyFactory;
import java.security.KeyPair;
import java.security.KeyPairGenerator;
import java.security.NoSuchAlgorithmException;
import java.security.SecureRandom;
import java.security.interfaces.RSAPrivateKey;
import java.security.interfaces.RSAPublicKey;
import java.security.spec.PKCS8EncodedKeySpec;
import java.security.spec.X509EncodedKeySpec;
import java.util.HashMap;
import java.util.Map;

import util.EncException;
import util.MD5;
import util.PayEncrypt;
import util.PaySign;
import util.RSAUtil;
import util.BASE64Util;
import util.StringUtil;


/**
 * Created with IntelliJ IDEA. User: kevin Date: 13-7-7 Time: 上午11:46
 * Email:lishu5566@gmail.com
 */
public class MerEncryptManager {
//	public static final String privateKey = "MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBALfF3i/oPISjG19JOUCXY2U9w8tDjxw+fIahiWTcE2JwQmXA4ri2jLJbG4foMNLVVgJv/2pbknfGkqJSA3OsFnWB8f11Cr5u0Eosi4SzHgYegUWvS27FyS4M0JL9+ABCrGgbLBUnf9LD3qW0OayApsLk1dSbelciBJ6hil08SOtrAgMBAAECgYB/onhvk388q7/eDRArcTmCXkR3DwP9HNUF+HlhSIxaCRfEbhPJMFhFo4taeAQ42hxzcu/VIaZ3c73x6L4m/3VUl854WgXiqKSgullmCFtm7fPlnjmnlY2NMnoL0S0CnuACQTvjIMVmwJiULxYRBCVhTxqxsJWPhnwBCZKe23t48QJBAOX5D7Y6c8JZJAHF3fmvSs7Ipp767oOf+7GRyZFQJ3o+eipnnK4rM7EfX9ebHCp3MQoT/1Do7NPpZDPE9/XYf9kCQQDMkkSZJYSg/BOhLd3sHbTxV1uLLNasmXm3j4nYrpjaO3kvU1BXT92tUTIlHWbeIi0AwqZcXKAOV0xT3hsygT7jAkA4EjjVenz89tUDpaXQmf/IWT3e51m+OASbL+uQhZWKha8tpaObB6eL2RV6MTR12ifXyDZpGNGdfXtT8ANxKr9JAkB9Sg/tY8cI+ZnkGz1RwRfyv7f3UyzfZNfhDm40YSqIbehYjcQk1WtFHPeDN7Cq12+Miapt4uS8I8dBjkRF+FZVAkEAmNa8YeJvT/JvdMpkZmfJJ0UlsNFLPZl5q7e88+utDbkRzDb4/jZzvY49U3qk4RNOVbfs27ywU11cIuzdxM5ldg==";
	// public static final String publicKey =
	// "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDW0bfUQVUouffhqN6GX9GtGcSBVpPe/MJAtZOln/+jhCejzAcgNPVtcJY3agag6LW/CPcGwsD01U9dY/zkf6cAFU0az7AvMV90M7gGWioIUwEjvdGu7qOfCLFKBBcQ3Umt4fyuHLspxB1cxwcUf1HvJ1ngzpvkeybp+8XC9qbrzQIDAQAB";
	public static final String publicKey = "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAnYHSR5ry4MtQeN8wbIGIH9TpYeHfdkZ/G2xOXLERzwzv+8HN7YK9RYlmHooL6Efr2BCUkymPPxq/68LwP2c5v9C0yUynA1SFmAR4bgjF2ZIxenEVO7FJr2YDSy43SzQ8nhtYlOpdc2ZMOrxVykzITAO72zmcVcjF9W4TUXJYmzSs5/KGBi3xdPQM/csuN/N5WpmxZn1t7PTKIxg7O+qnH97t9Ri7lF4ZktMSisG6FZwR786AEh5Go+CNUp1E/fbrA/sD2rDdrXhonai6vp63RFH4oUQfXq7ztHQTie3ao7nle4tdnVe0NL5uVSE9v8h1OARotfTL9sMBIP32MZO6UwIDAQAB";
	private String pingKey = null;
	private String workKey = null;
	private String mobKey = null;

	public MerEncryptManager() {

	}

	/**
	 * 初始化加密管理者， 1.生成加密秘钥 2.生成验签秘钥
	 * 
	 * @throws Exception
	 */
	public void initEncrypt() throws Exception {
		pingKey = StringUtil.getRandomString(24);
		workKey = StringUtil.getRandomString(24);
		setMobKey(pingKey, workKey);
	}

	/**
	 * 返回加密秘钥
	 * 
	 * @return
	 */
	public String getPingKey() {
		return pingKey;
	}

	/**
	 * 返回验签秘钥
	 * 
	 * @return
	 */
	public String getWorkKey() {
		return workKey;
	}

	/**
	 * 默默的设置RAS加密
	 * 
	 * @param pingKey
	 * @param workKey
	 * @throws Exception
	 */
	private void setMobKey(String pingKey, String workKey) throws Exception {
		mobKey = encryptKey(pingKey + workKey);
	}

	/**
	 * 返回加密的秘钥保护字段
	 * 
	 * @return
	 */
	public String getMobKey() {
		return mobKey;
	}

	/**
	 * 先Md5再Base64加密 用于密码加密
	 */
	public String encryptByMd5AndBASE64(String pwd) {
		try {
			byte messageDigest[] = MD5.MD5Bytes(pwd);
			return BASE64Util.encryptBASE64(messageDigest).trim();
		} catch (NoSuchAlgorithmException e) {
			e.printStackTrace();
			return null;
		} catch (Exception e) {
			e.printStackTrace();
			return null;
		}
	}

	/**
	 * 公钥加密
	 * 
	 * @param desKey
	 * @return
	 * @throws Exception
	 */

	
	
	/**
	 * 公钥加密
	 * 
	 * @param desKey
	 * @return
	 * @throws Exception
	 */
	public String encryptKey(String desKey) {
		String enStr = "";
		try {
			System.out.println(desKey);
			// 构造X509EncodedKeySpec对象
			byte[] keyBytes = BASE64Util.decryptBASE64(publicKey);
			X509EncodedKeySpec keySpec = new X509EncodedKeySpec(keyBytes);
			// KEY_ALGORITHM 指定的加密算法
			KeyFactory keyFactor = KeyFactory.getInstance("RSA");
			RSAPublicKey rsaPublicKey = (RSAPublicKey) keyFactor
					.generatePublic(keySpec);
			byte[] wl9ebankSignBin = desKey.getBytes();
			byte[] plainSignBin = RSAUtil
					.encrypt(rsaPublicKey, wl9ebankSignBin);
			enStr = BASE64Util.encryptBASE64(plainSignBin);
			System.out.println(enStr);
			//decryptKey(enStr);
			
		} catch (Exception e) {
			e.printStackTrace();
			return null;
		}
		return enStr;
	}
	
	public String decryptKey(String desKey,String privateKeyStr) throws Exception{
		//System.out.println("解密");
		//解
//		String privateKeyStr ="MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCdgdJHmvLgy1B43zBsgYgf1Olh4d92Rn8bbE5csRHPDO/7wc3tgr1FiWYeigvoR+vYEJSTKY8/Gr/rwvA/Zzm/0LTJTKcDVIWYBHhuCMXZkjF6cRU7sUmvZgNLLjdLNDyeG1iU6l1zZkw6vFXKTMhMA7vbOZxVyMX1bhNRclibNKzn8oYGLfF09Az9yy4383lambFmfW3s9MojGDs76qcf3u31GLuUXhmS0xKKwboVnBHvzoASHkaj4I1SnUT99usD+wPasN2teGidqLq+nrdEUfihRB9ervO0dBOJ7dqjueV7i12dV7Q0vm5VIT2/yHU4BGi19Mv2wwEg/fYxk7pTAgMBAAECggEBAI7hZJr2UdtgNGjhtnDH9G9D2k6j8u29N7LtxgxJyKj1yIGuJpuNnRsGcC/F9NVK4QRIzi7NZZfOMUmytfQeNQHvVwDA20SnGOV1MLZImQosl1vGV4el5P0K4nQBwcEj6tVWYz37eLillQM3M+/nRU/Hl1NgaJRdJE7nAvlAbGalrgnb1FMtpCunsyStSclv1HNgJCcM4trWowO/xyfd+qlThBbIsrGGAbz6ozx8tD1F5dRt1kgWX+jPgECpcagwNhE0retneFYNU+uX1wtPQkwjdnLiqnaLEl6H//fbsDcQagzzcmOIKB3bb+ISQTRmjKGuGrJMrtF9xeNqq4mmdUECgYEA1tqLd+75HGMnF8uxP8oPeBw8oxKxyMREJUGjdu9m+0uJ5PeEFYsB3NZtYrAgW/3ODvG8Q0Hr0APVTl1IeeJPcLs5dqofFBmQfIA0hDLNmhZHEigHorHQSJXl46yKSYAvCvyPOERqdfB+7ZqpJ+7Ancmn6MGLCGvWT6Fo8yYx6OECgYEAu6vLDvBcipYSFX5v+SO/AfLsRx1rFhhpNQDK0dR/1icGbi1ypDogt7i49yCkmA6bO+4gl0aEM39BeezGwIiuGoYqfWYl8FNoJOquoXgvJs1dIXcXY4SxH6BC3PO8DmqTrjGSmBdbHBf1MJpsOId60VQS7LudTpSdcETtPk/RhbMCgYEAxHjRNPlo/9aD9zSjf6us/a8EnDMKNIeVsWQWsTo3N/FZxfG57WFScLn9CVP6I2Uyu2O/PtnXZD89Hg4bqqmS74mmAKeNB/pgsBkk7QbwEMnyb93/LX3g859vvAUZ6CC55BgUd+XIXNVxnKHjscqYhTHUw/nIxAioz+TQbhktZsECgYAJ3uxMOdo0M9Z5qO/Mw9OND7DbwLFcNSZ7cjI4vRuIMP9Glj9cYe5Mm3unC+F8WfBSQ6EVM9FKQBw6hHijscyuenYLqG7AaDKnFmze80pPmSeX1gK16knCpxQ9ONigTXy9AcB7HWdeX+g2iTi99GpCTlC8gNWyR2DMgeOUjnAvvwKBgE5mJN2SIBUg/YgXJh0x+TxobxLKtfxSMLdjn0YTNgyFImiLtXXCriE1z9f+lHcKQajdy700SJm3lZCalujaVdoRJfNb6bUCar2qFTKh+Wotlrr11Db94vkHWyVGJulsDECsf74ezGOoyJMMNFsZ1wxsir+51p5MIiw1YGdktwXk";
		byte[] keyBytes = BASE64Util.decryptBASE64(privateKeyStr); 
	    PKCS8EncodedKeySpec spec = new PKCS8EncodedKeySpec(keyBytes);  
	    KeyFactory keyFactory = KeyFactory.getInstance(KEY_ALGORITHM);  
	    RSAPrivateKey  privateKey = (RSAPrivateKey) keyFactory.generatePrivate(spec); 
		String deStr = this.decrypt(desKey, privateKey);
		//System.out.println("mobKey"+deStr);
		//System.out.println("pingKey"+deStr.substring(0,24));
		//System.out.println("workKey"+deStr.substring(24));
		pingKey=deStr.substring(0,24);
		workKey=deStr.substring(24);
		return deStr.substring(0,24);
	}
	
	
	public static String encrypt(byte[] wl9ebankSignBin,RSAPublicKey rsaPublicKey) throws Exception{ 
		byte[] plainSignBin = RSAUtil
				.encrypt(rsaPublicKey, wl9ebankSignBin);
		String  enStr = BASE64Util.encryptBASE64(plainSignBin);
		 return enStr;
	}
	public static String decrypt(String key,RSAPrivateKey privateKey) throws Exception{ 
		byte[] plainSignBin =BASE64Util.decryptBASE64(key);
		byte[] plainSignBin2 = RSAUtil.decrypt(privateKey, plainSignBin);
		String enStr = new String(plainSignBin2);
		return enStr;
	}
	
	
	private static final String KEY_ALGORITHM = "RSA";    
	private static final String PUBLIC_KEY ="publicKey";  
	private static final String PRIVATE_KEY ="privateKey";   
	       public static void main(String[] args) throws Exception{  
	    Map<String,String> keyMap = genKey();
//	    Map<String,String> keyMap = new HashMap<String,String>();  //genKey();
//	    keyMap.put(PUBLIC_KEY, "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAnYHSR5ry4MtQeN8wbIGIH9TpYeHfdkZ/G2xOXLERzwzv+8HN7YK9RYlmHooL6Efr2BCUkymPPxq/68LwP2c5v9C0yUynA1SFmAR4bgjF2ZIxenEVO7FJr2YDSy43SzQ8nhtYlOpdc2ZMOrxVykzITAO72zmcVcjF9W4TUXJYmzSs5/KGBi3xdPQM/csuN/N5WpmxZn1t7PTKIxg7O+qnH97t9Ri7lF4ZktMSisG6FZwR786AEh5Go+CNUp1E/fbrA/sD2rDdrXhonai6vp63RFH4oUQfXq7ztHQTie3ao7nle4tdnVe0NL5uVSE9v8h1OARotfTL9sMBIP32MZO6UwIDAQAB");  
//	    keyMap.put(PRIVATE_KEY, "MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCdgdJHmvLgy1B43zBsgYgf1Olh4d92Rn8bbE5csRHPDO/7wc3tgr1FiWYeigvoR+vYEJSTKY8/Gr/rwvA/Zzm/0LTJTKcDVIWYBHhuCMXZkjF6cRU7sUmvZgNLLjdLNDyeG1iU6l1zZkw6vFXKTMhMA7vbOZxVyMX1bhNRclibNKzn8oYGLfF09Az9yy4383lambFmfW3s9MojGDs76qcf3u31GLuUXhmS0xKKwboVnBHvzoASHkaj4I1SnUT99usD+wPasN2teGidqLq+nrdEUfihRB9ervO0dBOJ7dqjueV7i12dV7Q0vm5VIT2/yHU4BGi19Mv2wwEg/fYxk7pTAgMBAAECggEBAI7hZJr2UdtgNGjhtnDH9G9D2k6j8u29N7LtxgxJyKj1yIGuJpuNnRsGcC/F9NVK4QRIzi7NZZfOMUmytfQeNQHvVwDA20SnGOV1MLZImQosl1vGV4el5P0K4nQBwcEj6tVWYz37eLillQM3M+/nRU/Hl1NgaJRdJE7nAvlAbGalrgnb1FMtpCunsyStSclv1HNgJCcM4trWowO/xyfd+qlThBbIsrGGAbz6ozx8tD1F5dRt1kgWX+jPgECpcagwNhE0retneFYNU+uX1wtPQkwjdnLiqnaLEl6H//fbsDcQagzzcmOIKB3bb+ISQTRmjKGuGrJMrtF9xeNqq4mmdUECgYEA1tqLd+75HGMnF8uxP8oPeBw8oxKxyMREJUGjdu9m+0uJ5PeEFYsB3NZtYrAgW/3ODvG8Q0Hr0APVTl1IeeJPcLs5dqofFBmQfIA0hDLNmhZHEigHorHQSJXl46yKSYAvCvyPOERqdfB+7ZqpJ+7Ancmn6MGLCGvWT6Fo8yYx6OECgYEAu6vLDvBcipYSFX5v+SO/AfLsRx1rFhhpNQDK0dR/1icGbi1ypDogt7i49yCkmA6bO+4gl0aEM39BeezGwIiuGoYqfWYl8FNoJOquoXgvJs1dIXcXY4SxH6BC3PO8DmqTrjGSmBdbHBf1MJpsOId60VQS7LudTpSdcETtPk/RhbMCgYEAxHjRNPlo/9aD9zSjf6us/a8EnDMKNIeVsWQWsTo3N/FZxfG57WFScLn9CVP6I2Uyu2O/PtnXZD89Hg4bqqmS74mmAKeNB/pgsBkk7QbwEMnyb93/LX3g859vvAUZ6CC55BgUd+XIXNVxnKHjscqYhTHUw/nIxAioz+TQbhktZsECgYAJ3uxMOdo0M9Z5qO/Mw9OND7DbwLFcNSZ7cjI4vRuIMP9Glj9cYe5Mm3unC+F8WfBSQ6EVM9FKQBw6hHijscyuenYLqG7AaDKnFmze80pPmSeX1gK16knCpxQ9ONigTXy9AcB7HWdeX+g2iTi99GpCTlC8gNWyR2DMgeOUjnAvvwKBgE5mJN2SIBUg/YgXJh0x+TxobxLKtfxSMLdjn0YTNgyFImiLtXXCriE1z9f+lHcKQajdy700SJm3lZCalujaVdoRJfNb6bUCar2qFTKh+Wotlrr11Db94vkHWyVGJulsDECsf74ezGOoyJMMNFsZ1wxsir+51p5MIiw1YGdktwXk");  
	    System.out.println(keyMap.get(PUBLIC_KEY));
	    System.out.println(keyMap.get(PRIVATE_KEY));
	    RSAPublicKey publicKey = getPublicKey(keyMap.get(PUBLIC_KEY));  
	    RSAPrivateKey privateKey = getPrivateKey(keyMap.get(PRIVATE_KEY));  
	    String info ="我 S 中文";  
	    //加密  
	    String  bytes = encrypt(info.getBytes("utf-8"),publicKey);  
	    //解密  
	    bytes = decrypt(bytes, privateKey);  
	    System.out.println(bytes.toString());  
	    System.out.println(PayEncrypt.decryptMode("5mVxVpD6x8Rt6MPBBgB1IWZk", "jzdph7aUlxA="));
	}  
	  
	public static Map<String,String> genKey() throws Exception{  
	    Map<String,String> keyMap = new HashMap<String,String>();  
	    KeyPairGenerator keygen = KeyPairGenerator.getInstance(KEY_ALGORITHM);  
	    SecureRandom random = new SecureRandom();  
	    // random.setSeed(keyInfo.getBytes());  
	    // 初始加密，512位已被破解，用1024位,最好用2048位  
	    keygen.initialize(2048, random);  
	    // 取得密钥对  
	    KeyPair kp = keygen.generateKeyPair();  
	    RSAPrivateKey privateKey = (RSAPrivateKey)kp.getPrivate();  
	        String privateKeyString = BASE64Util.encryptBASE64(privateKey.getEncoded());  
	    RSAPublicKey publicKey = (RSAPublicKey)kp.getPublic();   
	    String publicKeyString = BASE64Util.encryptBASE64(publicKey.getEncoded());  
	    keyMap.put(PUBLIC_KEY, publicKeyString);  
	    keyMap.put(PRIVATE_KEY, privateKeyString);  
	    return keyMap;  
	}  
	  
	public static RSAPublicKey getPublicKey(String publicKey) throws Exception{  
		byte[] keyBytes = BASE64Util.decryptBASE64(publicKey); 
	    X509EncodedKeySpec spec = new X509EncodedKeySpec(keyBytes);  
	    KeyFactory keyFactory = KeyFactory.getInstance(KEY_ALGORITHM);  
	    return (RSAPublicKey) keyFactory.generatePublic(spec);  
	}  
	  
	public static RSAPrivateKey getPrivateKey(String privateKey) throws Exception{  
		byte[] keyBytes = BASE64Util.decryptBASE64(privateKey); 
	    PKCS8EncodedKeySpec spec = new PKCS8EncodedKeySpec(keyBytes);  
	    KeyFactory keyFactory = KeyFactory.getInstance(KEY_ALGORITHM);  
	    return (RSAPrivateKey) keyFactory.generatePrivate(spec);  
	}  
	
	
	
	
	
	

	/**
	 * 移动解密验签
	 * 
	 * @param paramNames
	 * @param map
	 * @return
	 * @throws Exception
	 */
	public boolean verifyResSign(String[] paramNames, Map<String, String> map) {
		try {

			String signHex = map.get("sign");
			if (StringUtil.isEmpty(signHex)) {
				return false;
			}
			// 拼接签名原串
			StringBuffer sb = new StringBuffer();
			for (int i = 0; i < paramNames.length; i++) {
				String v = map.get(paramNames[i]);
				if (null != v) {
					sb.append(v);
				}
			}
			// 计算摘要
			String calSignHex = PaySign.md5(sb.toString());
			sb = null;
			// 解密sign
			String decSign = PayEncrypt.decryptMode(getWorkKey(), signHex);
			// 比较
			if (!calSignHex.equalsIgnoreCase(decSign)) {
				return false;
			}
		} catch (Exception e) {
			e.printStackTrace();
			return false;
		}
		return true;
	}

	/*
	 * 移动加密签名
	 * 
	 * @param paramNames
	 * @param map
	 * @return
	 * @throws Exception
	 */
	public String getReqSign(String[] paramNames, Map<String, String> map) {
		String sign = "";
		try {
			StringBuffer sb = new StringBuffer();
			for (int i = 0; i < paramNames.length; i++) {
				String v = map.get(paramNames[i].trim());
				if (null != v) {
					sb.append(v);
				}
			}
			sign = PayEncrypt.encryptMode(getWorkKey(),
					PaySign.md5(sb.toString()));
			sb = null;
		} catch (Exception e) {
			e.printStackTrace();
			return null;
		}
		return sign;
	}

	/**
	 * 字段加密
	 * 
	 * @param str
	 * @return
	 */
	public String getEncryptDES(String str) {
		try {
			return PayEncrypt.encryptMode(getPingKey(), str);
		} catch (EncException e) {
			e.printStackTrace();
			return null;
		}
	}

	/**
	 * 字段解密
	 * 
	 * @param str
	 * @return
	 */
	public String getDecryptDES(String str) {
		try {
			return PayEncrypt.decryptMode(getPingKey(), str);
		} catch (EncException e) {
			e.printStackTrace();
			return null;
		}
	}
	
	
}
