package util.jiprovince;

import java.io.ByteArrayOutputStream;
import java.security.KeyFactory;
import java.security.NoSuchAlgorithmException;
import java.security.PrivateKey;
import java.security.PublicKey;
import java.security.Signature;
import java.security.interfaces.RSAPrivateKey;
import java.security.interfaces.RSAPublicKey;
import java.security.spec.InvalidKeySpecException;
import java.security.spec.PKCS8EncodedKeySpec;
import java.security.spec.X509EncodedKeySpec;

import javax.crypto.Cipher;

import org.springframework.util.Base64Utils;



/**
 * 
 * @author zj
 * 
 * @date:2016年4月8日上午11:51:36
 */
public final class MyRSAUtils {
	
	private static String RSA = "RSA";
	
	private static final int MAX_ENCRYPT_BLOCK = 117;

//	private static final int MAX_DECRYPT_BLOCK = 256;
	
	private static final int MAX_DECRYPT_BLOCK = 128;
	
	public static final String  MD5_SIGN_ALGORITHM = "MD5withRSA";
	
	public static final String  SHA1_SIGN_ALGORITHM = "SHA1withRSA";

	/**
	 * RSA签名
	 * @param privateKey:私钥
	 * @param plainText:待签名明文串
	 * @param algorithm:签名算法,默认MD5withRSA
	 * @return
	 */
	public static String sign(String  privateKey, String plainText, String algorithm) {
		try {
			byte[] keyBytes = Base64Utils.decodeFromString(privateKey);
			PKCS8EncodedKeySpec priPKCS8 = new PKCS8EncodedKeySpec(keyBytes);
			KeyFactory keyf = KeyFactory.getInstance(RSA);
			PrivateKey prikey = keyf.generatePrivate(priPKCS8);
			Signature signet = java.security.Signature.getInstance(algorithm);
			signet.initSign(prikey);
			signet.update(plainText.getBytes("UTF-8"));
			return Base64Utils.encodeToString(signet.sign());
		} catch (java.lang.Exception e) {
			System.out.println("签名失败");
			e.printStackTrace();
		}
		return null;
	}
	
	/**
	 * RSA公钥验证
	 * @param publickey:公钥
	 * @param hexSigned：签名信息
	 * @param plainText：待签名明文
	 * @param algorithm:签名算法,默认MD5withRSA
	 * @return
	 */
	public static boolean verifySignature(String publickey, String hexSigned, String plainText,String algorithm) {
		try {
			PublicKey publicKey = loadPublicKey(publickey);
			Signature signetCheck = Signature.getInstance(algorithm);
			signetCheck.initVerify(publicKey);
			signetCheck.update(plainText.getBytes("UTF-8"));
			if (signetCheck.verify(Base64Utils.decodeFromString(hexSigned))) {
				return true;
			}
			return false;
		} catch (Exception e) {
			e.printStackTrace();
			return false;
		}
	}
	
	/**
	 * 公钥加密
	 * @param data
	 * @param publicKeyStr
	 * @return
	 */
	public static byte[] encryptDataByPublicKey(byte[] data, String publicKeyStr) {
		try {
			PublicKey publicKey = loadPublicKey(publicKeyStr);
			return encryptDataByPublicKey(data, publicKey);
		} catch (Exception e) {
			e.printStackTrace();
		}
		return null;
	}

	public static byte[] encryptDataByPublicKey(byte[] data, PublicKey publicKey) {
		try {
			Cipher cipher = Cipher.getInstance(publicKey.getAlgorithm());
			cipher.init(Cipher.ENCRYPT_MODE, publicKey);

			int inputLen = data.length;
			ByteArrayOutputStream out = new ByteArrayOutputStream();
			int offSet = 0;
			byte[] cache;
			int i = 0;
			while (inputLen - offSet > 0) {
				if (inputLen - offSet > MAX_ENCRYPT_BLOCK) {
					cache = cipher.doFinal(data, offSet, MAX_ENCRYPT_BLOCK);
				} else {
					cache = cipher.doFinal(data, offSet, inputLen - offSet);
				}
				out.write(cache, 0, cache.length);
				i++;
				offSet = i * MAX_ENCRYPT_BLOCK;
			}
			byte[] encryptedData = out.toByteArray();
			out.close();
			return encryptedData;
		} catch (Exception e) {
			e.printStackTrace();
			return null;
		}
	}
	
	/**
	 * 私钥解密
	 * @param encryptedData
	 * @param privateKeyStr
	 * @return
	 */
	public static byte[] decryptDataByPrivateKey(byte[] encryptedData, String privateKeyStr) {
		try {
			PrivateKey privateKey = loadPrivateKey(privateKeyStr);
			return decryptDataByPrivateKey(encryptedData, privateKey);
		} catch (Exception e) {
			e.printStackTrace();
		}
		return null;
	}

	public static byte[] decryptDataByPrivateKey(byte[] encryptedData, PrivateKey privateKey) {
		try {
			Cipher cipher = Cipher.getInstance(privateKey.getAlgorithm());
			cipher.init(Cipher.DECRYPT_MODE, privateKey);

			int inputLen = encryptedData.length;
			ByteArrayOutputStream out = new ByteArrayOutputStream();
			int offSet = 0;
			byte[] cache;
			int i = 0;
			while (inputLen - offSet > 0) {
				if (inputLen - offSet > MAX_DECRYPT_BLOCK) {
					cache = cipher.doFinal(encryptedData, offSet,
							MAX_DECRYPT_BLOCK);
				} else {
					cache = cipher.doFinal(encryptedData, offSet, inputLen
							- offSet);
				}
				out.write(cache, 0, cache.length);
				i++;
				offSet = i * MAX_DECRYPT_BLOCK;
			}
			byte[] decryptedData = out.toByteArray();
			out.close();
			return decryptedData;
		} catch (Exception e) {
			return null;
		}
	}
	
	//===============================================================================================
	
	/**
	 * 私钥加密
	 * @param data
	 * @param privateKeyStr
	 * @return
	 */
	public static byte[] encryptDataByPrivateKey(byte[] data, String privateKeyStr) {
		try {
			PrivateKey privateKey = loadPrivateKey(privateKeyStr);
			return encryptDataByPrivateKey(data, privateKey);
		} catch (Exception e) {
			e.printStackTrace();
		}
		return null;
	}

	public static byte[] encryptDataByPrivateKey(byte[] data, PrivateKey privateKey) {
		try {
			Cipher cipher = Cipher.getInstance(privateKey.getAlgorithm());
			cipher.init(Cipher.ENCRYPT_MODE, privateKey);

			int inputLen = data.length;
			ByteArrayOutputStream out = new ByteArrayOutputStream();
			int offSet = 0;
			byte[] cache;
			int i = 0;
			while (inputLen - offSet > 0) {
				if (inputLen - offSet > MAX_ENCRYPT_BLOCK) {
					cache = cipher.doFinal(data, offSet, MAX_ENCRYPT_BLOCK);
				} else {
					cache = cipher.doFinal(data, offSet, inputLen - offSet);
				}
				out.write(cache, 0, cache.length);
				i++;
				offSet = i * MAX_ENCRYPT_BLOCK;
			}
			byte[] encryptedData = out.toByteArray();
			out.close();
			return encryptedData;
		} catch (Exception e) {
			e.printStackTrace();
			return null;
		}
	}
	
	/**
	 * 私钥解密
	 * @param encryptedData
	 * @param privateKeyStr
	 * @return
	 */
	public static byte[] decryptDataByPublicKey(byte[] encryptedData, String publicKeyStr) {
		try {
			PublicKey publicKey = loadPublicKey(publicKeyStr);
			return decryptDataByPublicKey(encryptedData, publicKey);
		} catch (Exception e) {
			e.printStackTrace();
		}
		return null;
	}

	public static byte[] decryptDataByPublicKey(byte[] encryptedData, PublicKey publicKey) {
		try {
			Cipher cipher = Cipher.getInstance(publicKey.getAlgorithm());
			cipher.init(Cipher.DECRYPT_MODE, publicKey);

			int inputLen = encryptedData.length;
			ByteArrayOutputStream out = new ByteArrayOutputStream();
			int offSet = 0;
			byte[] cache;
			int i = 0;
			while (inputLen - offSet > 0) {
				if (inputLen - offSet > MAX_DECRYPT_BLOCK) {
					cache = cipher.doFinal(encryptedData, offSet,MAX_DECRYPT_BLOCK);
				} else {
					cache = cipher.doFinal(encryptedData, offSet, inputLen- offSet);
				}
				out.write(cache, 0, cache.length);
				i++;
				offSet = i * MAX_DECRYPT_BLOCK;
			}
			byte[] decryptedData = out.toByteArray();
			out.close();
			return decryptedData;
		} catch (Exception e) {
			e.printStackTrace();
			return null;
		}
	}
	

	public static PublicKey loadPublicKey(String publicKeyStr) throws Exception {
		try {
			byte[] buffer = Base64Utils.decodeFromString(publicKeyStr);
			KeyFactory keyFactory = KeyFactory.getInstance(RSA);
			X509EncodedKeySpec keySpec = new X509EncodedKeySpec(buffer);
			return (RSAPublicKey) keyFactory.generatePublic(keySpec);
		} catch (NoSuchAlgorithmException e) {
			throw new Exception("无此算法");
		} catch (InvalidKeySpecException e) {
			throw new Exception("公钥非法");
		} catch (NullPointerException e) {
			throw new Exception("公钥数据为空");
		}
	}

	public static PrivateKey loadPrivateKey(String privateKeyStr)
			throws Exception {
		try {
			byte[] buffer = Base64Utils.decodeFromString(privateKeyStr);
			// X509EncodedKeySpec keySpec = new X509EncodedKeySpec(buffer);
			PKCS8EncodedKeySpec keySpec = new PKCS8EncodedKeySpec(buffer);
			KeyFactory keyFactory = KeyFactory.getInstance(RSA);
			return (RSAPrivateKey) keyFactory.generatePrivate(keySpec);
		} catch (NoSuchAlgorithmException e) {
			throw new Exception("无此算法");
		} catch (InvalidKeySpecException e) {
			throw new Exception("私钥非法");
		} catch (NullPointerException e) {
			throw new Exception("私钥数据为空");
		}
	}
	
	
	public static void main(String[] args) {
		String testPrivateKey = "MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBAJyGJjJcmOdlt3hKk0ybUzK8YWScAESag/XtLKy1sWmfSpGE0xBUL3Mnc032d6v2Sw/IBRpWWsG8YJugdB51ZpaE04k5WKmU8lalq7sgKpjRqf9r47H6JtYupSiEnWBsD/5GIloLxF1H9sve4nlO1FALjneqOiX5l7dxLtYMYkJbAgMBAAECgYB2h8wTJTYRPWDjsi27vIATkr56Lvyw9wXpTTMgkvMH1j3k1t3ypp3MKVrS9ksHqRqMl97tY+mUfq/2ClsE2cjWDahwBE+OITOBw67YxccGTH3vjxdSG0GBg7Y5wGF5whFGczFWfHWsn+KnPbWv5Ab0r6xFKj/BBzaoPhrasPa4GQJBAO1X1teYwBmMFkQA5SkMD7W9MZ2GysHbJrsfXa3XxMDJ0BwgcH85qTA6z4kViCu4c8oZxgyYCtqS6AWmPd1CiZ8CQQCo0/OO1Hxymz9ucPPUGHwKXsVE3qX0Vl6MOhjxQitcO9XXeeaMRIEMd5tg3DpcPh+ELvm0grYUwBLpginEWcXFAkAyca3m6r9BbsW4tFwASmD4ZnqqGWD0rMhhkvwNG8PkmcASik5+q9mqt4UuhJYJfM2XUb0HJjWAWSLZOIkDgsXPAkEAiH47pplCOAIuCWNGro5REq1GPgToFSAS9rot2jgOCFbT7469h2AHGYZF18yVXmhXYK398iPPg/06DRHQJNATpQJBAK9pVJ0eR21AaNcU71vdrxKoVG9tkBS1o31eKOz/Eh6OfFUEIKmHGxQY/B2DYPUPZ3MuggREZIwyehbudM7h2h4=";
//		String testPublicKey = "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEApZVmx6x8Q3rdj6WP0T9xB+1OHPjdGKM7F/0+N4Xax+C7jOE/CvmlJQhqan0QMWXZR42l22cnDwP2UJevWBqFgws3NHW/2XJiDwkdlRoQ78TkJsnEKAkcevWfZQSM2387oGDoB8eQ8gpfaTt7HiqRk3F5fWh9c3pteosyr5+L4x23gYQ9Db867PEavGVHsf64fhKSbeK7gGJtoIgtR/uYOwT/Pp3WfEv2bWmyMuKqaXuGJNLawYEJYJFrceWf8J2uAHqinI04PStQwiaZprUmvbck/fmYLEw2sJh7exLgBU+1V2I6eQHS6k1sWaGBBOa96+0SCPNG9cz3bdkCT1CaPwIDAQAB";
		String sign = sign(testPrivateKey, "account=18020262310&cardNo=6217001820025001292&cardType=1&certNo=350123198911181532&certType=00&mchntName=新藍海&orgId=000020&password=123456qq&pmsBankNo=105100000017&realName=林快", "MD5withRSA");
		System.out.println(sign);
//		System.out.println(verifySignature(testPublicKey, sign, "hello",MyRSAUtils.MD5_SIGN_ALGORITHM));
	}
}
