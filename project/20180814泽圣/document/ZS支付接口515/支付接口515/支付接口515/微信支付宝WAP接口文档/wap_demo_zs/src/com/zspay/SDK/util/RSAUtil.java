package com.zspay.SDK.util;

import java.io.ByteArrayOutputStream;
import java.io.IOException;
import java.security.InvalidKeyException;
import java.security.Key;
import java.security.KeyFactory;
import java.security.KeyPair;
import java.security.KeyPairGenerator;
import java.security.NoSuchAlgorithmException;
import java.security.Provider;
import java.security.SecureRandom;
import java.security.interfaces.RSAPrivateKey;
import java.security.interfaces.RSAPublicKey;
import java.security.spec.InvalidKeySpecException;
import java.security.spec.PKCS8EncodedKeySpec;
import java.security.spec.X509EncodedKeySpec;

import javax.crypto.BadPaddingException;
import javax.crypto.Cipher;
import javax.crypto.IllegalBlockSizeException;
import javax.crypto.NoSuchPaddingException;
import javax.crypto.ShortBufferException;

import org.apache.commons.codec.binary.Base64;

/**
 * 
 * RSA加解密工具类
 * 
 * @author xiongyuanming
 * @version premas.1.0
 * @see
 * @since 2014年7月11日
 */
public final class RSAUtil {
	private RSAUtil() {
		
	}
//	private Logger LOGGER = Logger.getLogger(RSAUtil.class);
	/**
	 * 密钥长度
	 */
	public static final int KEY_SIZE = 1024;

//	private static KeyFactory keyFactory = null;

	private static Provider provider = null;

	private final static String ALGORITHM = "RSA/ECB/PKCS1Padding";

	private final static String RSA = "RSA";

	static {
//		try {
			provider = new org.bouncycastle.jce.provider.BouncyCastleProvider();
//			keyFactory = KeyFactory.getInstance(RSA, provider);
//		} catch (NoSuchAlgorithmException e) {
//			LOGGER.error("初始化内存数据库", e);
//		}
	}

	/**
	 * 获得公私钥
	 * 
	 * @return
	 * @throws NoSuchAlgorithmException 
	 */
	public static String[] generateRSAKeys() throws NoSuchAlgorithmException {
		KeyPairGenerator keyPairGen = (KeyPairGenerator) KeyPairGenerator.getInstance(RSA, provider);
		keyPairGen.initialize(KEY_SIZE, new SecureRandom());
		KeyPair keyPair = keyPairGen.genKeyPair();
		
		RSAPublicKey pubKey = (RSAPublicKey) keyPair.getPublic();
		String pubkey = Base64.encodeBase64String(pubKey.getEncoded());

		RSAPrivateKey priKey = (RSAPrivateKey) keyPair.getPrivate();
		String prikey = Base64.encodeBase64String(priKey.getEncoded());
		return new String[] { pubkey, prikey };
	}

	/**
	 * 用公钥pub对data做RSA加密
	 * 
	 * @param pub
	 *            - base64字符串
	 * @param data
	 *            - 普通字符串
	 * @return
	 * @throws PreException
	 */
	public static String encryptByPub(String pub, String data) throws Exception {
		RSAPublicKey rsaKey = createRSAPublicKey(pub);
		byte[] b = encrypt(rsaKey, data.getBytes(Constants.ENCODE));
		return StringUtil.byte2hex(b);
	}

	/**
	 * 用私钥pri对data解密
	 * 
	 * @param pri
	 *            - base64字符串
	 * @param data
	 *            - hex字符串
	 * @return
	 * @throws PreException
	 */
	public static String decryptByPri(String pri, String data, boolean isHex) throws Exception {
		RSAPrivateKey rsaKey = createRSAPrivateKey(pri);
		byte[] b = decrypt(rsaKey, StringUtil.hex2byte(data));
		return ((isHex) ? (StringUtil.byte2hex(b)) : (new String(b, Constants.ENCODE)));
	}

	/**
	 * 用私钥pri对字符串data加密
	 * 
	 * @param pri
	 *            - base64字符串
	 * @param data
	 *            - 普通字符串
	 * @return
	 * @throws PreException
	 */
	public static String encryptByPri(String pri, String data) throws Exception {
		RSAPrivateKey rsaKey = createRSAPrivateKey(pri);
		byte[] b = encrypt(rsaKey, data.getBytes(Constants.ENCODE));
		return StringUtil.byte2hex(b);
	}

	/**
	 * 用公钥pub对data解密
	 * 
	 * @param pub
	 *            - base64字符串
	 * @param data
	 *            - hex字符串
	 * @return
	 * @throws PreException
	 */
	public static String decryptByPub(String pub, String data) throws Exception {
		RSAPublicKey rsaKey = createRSAPublicKey(pub);
		byte[] b = decrypt(rsaKey, StringUtil.hex2byte(data));
		return new String(b, Constants.ENCODE);
	}

	/**
	 * 创建公钥对象
	 * 
	 * @param pubKey
	 *            - base64字符串
	 * @return
	 * @throws NoSuchAlgorithmException
	 * @throws InvalidKeySpecException
	 * @throws Exception
	 */
	public static RSAPublicKey createRSAPublicKey(String pubKey) throws NoSuchAlgorithmException,
			InvalidKeySpecException {
		byte[] keyBytes = Base64.decodeBase64(pubKey);
		StringUtil.byte2hex(keyBytes);
		X509EncodedKeySpec keySpec = new X509EncodedKeySpec(keyBytes);
		KeyFactory keyFactory = KeyFactory.getInstance(RSA);
		return (RSAPublicKey) keyFactory.generatePublic(keySpec);
	}

	/**
	 * 创建公钥对象
	 * 
	 * @param pubKey
	 *            - base64字符串
	 * @return
	 * @throws NoSuchAlgorithmException
	 * @throws InvalidKeySpecException
	 */
	public static RSAPrivateKey createRSAPrivateKey(String priKey) throws NoSuchAlgorithmException,
			InvalidKeySpecException {
		byte[] keyBytes = Base64.decodeBase64(priKey);
		PKCS8EncodedKeySpec keySpec = new PKCS8EncodedKeySpec(keyBytes);
		KeyFactory keyFactory = KeyFactory.getInstance(RSA);
		return (RSAPrivateKey) keyFactory.generatePrivate(keySpec);
	}

	/**
	 * 加密
	 * 
	 * @param key
	 * @param data
	 * @return
	 * @throws NoSuchPaddingException
	 * @throws NoSuchAlgorithmException
	 * @throws InvalidKeyException
	 * @throws BadPaddingException
	 * @throws IllegalBlockSizeException
	 * @throws ShortBufferException
	 */
	public static byte[] encrypt(Key key, byte[] data) throws NoSuchAlgorithmException,
			NoSuchPaddingException, InvalidKeyException, ShortBufferException, IllegalBlockSizeException,
			BadPaddingException {
		Cipher cipher = Cipher.getInstance(ALGORITHM, provider);
		cipher.init(Cipher.ENCRYPT_MODE, key);
		int blockSize = cipher.getBlockSize();
		int outputSize = cipher.getOutputSize(data.length);
		int leavedSize = data.length % blockSize;
		int blocksSize = leavedSize != 0 ? data.length / blockSize + 1 : data.length / blockSize;
		byte[] raw = new byte[outputSize * blocksSize];
		int i = 0;
		while (data.length - i * blockSize > 0) {
			if (data.length - i * blockSize > blockSize) {
				cipher.doFinal(data, i * blockSize, blockSize, raw, i * outputSize);
			} else {
				cipher.doFinal(data, i * blockSize, data.length - i * blockSize, raw, i * outputSize);
			}
			i++;
		}
		return raw;
	}

	/**
	 * 解密
	 * 
	 * @param key
	 * @param raw
	 * @return
	 * @throws NoSuchPaddingException
	 * @throws NoSuchAlgorithmException
	 * @throws InvalidKeyException
	 * @throws IOException
	 * @throws BadPaddingException
	 * @throws IllegalBlockSizeException
	 */
	public static byte[] decrypt(Key key, byte[] raw) throws NoSuchAlgorithmException,
			NoSuchPaddingException, InvalidKeyException, IllegalBlockSizeException, BadPaddingException,
			IOException {
		Cipher cipher = Cipher.getInstance(ALGORITHM, provider);
		cipher.init(Cipher.DECRYPT_MODE, key);
		int blockSize = cipher.getBlockSize();
		ByteArrayOutputStream bout = new ByteArrayOutputStream(64);
		int j = 0;
		while (raw.length - j * blockSize > 0) {
			bout.write(cipher.doFinal(raw, j * blockSize, blockSize));
			j++;
		}
		return bout.toByteArray();

	}
}
