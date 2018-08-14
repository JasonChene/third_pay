package com.zspay.SDK.util;

import java.io.UnsupportedEncodingException;
import java.util.Random;

/**
 * 
 * 字符串工具类
 * 

 * @since 2014年7月11日
 */
public final class StringUtil {
	private StringUtil() {
		
	}
	public static final String[] LETTERS = { "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n",
			"o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
			"H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z" };

	public static final String[] NUMS = { "0", "1", "2", "3", "4", "5", "6", "7", "8", "9" };

	public static final String[] LETTERNUMS = { "0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "a", "b", "c",
			"d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
			"w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O",
			"P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z" };
	public static final String[] NUMSLETTER_A_F = { "0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "A", "B",
			"C", "D", "E", "F" };

	/**
	 * 反格式化byte[压缩字符串]s的长度必须是偶数
	 * 
	 * @param s
	 * @return
	 * @throws UnsupportedEncodingException
	 */
	public static byte[] hex2byte(String s) throws UnsupportedEncodingException {
		byte[] src = s.toLowerCase().getBytes(Constants.ENCODE);
		byte[] ret = new byte[src.length / 2];
		for (int i = 0; i < src.length; i += 2) {
			byte hi = src[i];
			byte low = src[i + 1];
			hi = (byte) ((hi >= 'a' && hi <= 'f') ? 0x0a + (hi - 'a') : hi - '0');
			low = (byte) ((low >= 'a' && low <= 'f') ? 0x0a + (low - 'a') : low - '0');
			ret[i / 2] = (byte) (hi << 4 | low);
		}
		System.out.println(Thread.currentThread().getName()); 
		return ret;
	}

	/**
	 * 格式化byte
	 * 
	 * @param b
	 * @return
	 */
	public static String byte2hex(byte[] b) {
		char[] digit = { '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F' };
		char[] out = new char[b.length * 2];
		for (int i = 0; i < b.length; i++) {
			byte c = b[i];
			out[i * 2] = digit[(c >>> 4) & 0X0F];
			out[i * 2 + 1] = digit[c & 0X0F];
		}

		return new String(out);
	}

	public static String getRandomNumAndLetterAF(int len) {
		String s = "";
		s.toCharArray();
		return getRandom(len, NUMSLETTER_A_F);
	}

	public static String getRandomLetter(int len) {
		return getRandom(len, LETTERS);
	}

	public static String getRandomNum(int len) {
		return getRandom(len, NUMS);
	}

	public static String getRandomLetterAndNum(int len) {
		return getRandom(len, LETTERNUMS);
	}

	public static String getRandom(int len, String[] arr) {
		String s = "";
		if (len <= 0 || arr == null || arr.length < 0) {
			return s;
		}
		Random ra = new Random();
		int arrLen = arr.length;
		for (int i = 0; i < len; i++) {
			s += arr[ra.nextInt(arrLen)];
		}
		return s;
	}

	public static boolean isEmpty(String str) {
		return str == null || str.isEmpty();
	}

	public static String null2String(String str) {
		return ((str == null) ? ("") : (str.trim()));
	}

	/**
	 * 所有字段是否为空
	 * 
	 * @param field
	 * @return 为空则返回false
	 */
	public static boolean isNotEmpty(String... field) {
		if (field == null || field.length < 1) {
			return false;
		}
		for (String f : field) {
			if (isEmpty(f)) {
				return false;
			}
		}

		return true;
	}
	public static void main(String[] args) throws UnsupportedEncodingException {
		String s="9F";
		byte []b=StringUtil.hex2byte(s);	
		System.out.println(b);
		System.out.println("++++++++++++++");
		System.out.println(StringUtil.byte2hex(b));
		System.out.println(Thread.currentThread().getName()); 
	}
}
