package com.zspay.SDK.util;

import java.io.UnsupportedEncodingException;
import java.security.InvalidKeyException;
import java.security.NoSuchAlgorithmException;
import java.security.PublicKey;
import java.security.Signature;
import java.security.SignatureException;
import java.security.interfaces.RSAPrivateKey;
import java.security.spec.InvalidKeySpecException;

/**
 * 
 * RSA签名验签工具类
 * 
 * @author xiongyuanming
 * @version premas.1.0
 * @see
 * @since 2014年7月11日
 */
public final class RSASignUtil {
	private RSASignUtil() {
		
	}
	
	public static final String KEY_ALGORITHM = "RSA";
	public static final String SIGNATURE_ALGORITHM = "MD5withRSA";

	/**
	 * 用私钥因子priEx与模数mod对字符串src做签名
	 * 
	 * @param src
	 *            - 普通字符串
	 * @param pri
	 *            - 私钥base64字符串
	 * @return String - hex字符串
	 * @throws UnsupportedEncodingException
	 * @throws InvalidKeySpecException
	 * @throws NoSuchAlgorithmException
	 * @throws InvalidKeyException
	 * @throws SignatureException
	 */
	public static String sign(String src, String pri) throws UnsupportedEncodingException,
			NoSuchAlgorithmException, InvalidKeySpecException, InvalidKeyException, SignatureException {
		// 将明文数据转为byte数组
		byte[] data = src.getBytes(Constants.ENCODE);
		RSAPrivateKey priKey = RSAUtil.createRSAPrivateKey(pri);
		// 实例化Signature
		Signature signature = Signature.getInstance(SIGNATURE_ALGORITHM);
		// 初始化Signature
		signature.initSign(priKey);
		// 更新
		signature.update(data);
		byte[] signB = signature.sign();
		return StringUtil.byte2hex(signB);
	}

	/**
	 * 校验数字签名
	 * 
	 * @param signSrc
	 *            待校验数据普通字符串
	 * @param sign
	 *            数字签名(hex字符串)
	 * @param pub
	 *            公钥base64字符串
	 * @return
	 * @throws UnsupportedEncodingException
	 * @throws InvalidKeySpecException
	 * @throws NoSuchAlgorithmException
	 * @throws InvalidKeyException
	 * @throws SignatureException
	 */
	public static boolean verify(String signSrc, String sign, String pub)
			throws UnsupportedEncodingException, NoSuchAlgorithmException, InvalidKeySpecException,
			InvalidKeyException, SignatureException {
		// 将明文数据转为byte数组
		byte[] data = signSrc.getBytes(Constants.ENCODE);
		// 将签名数据转换为byte数组
		byte[] signByte = StringUtil.hex2byte(sign);
		// 产生公钥
		PublicKey pubKey = RSAUtil.createRSAPublicKey(pub);
		// 实例化Signature
		Signature signature = Signature.getInstance(SIGNATURE_ALGORITHM);
		// 初始化Signature
		signature.initVerify(pubKey);
		// 更新
		signature.update(data);
		// 验证
		return signature.verify(signByte);
	}
}
