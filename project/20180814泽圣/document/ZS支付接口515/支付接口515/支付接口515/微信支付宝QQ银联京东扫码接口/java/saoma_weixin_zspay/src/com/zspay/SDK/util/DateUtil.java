package com.zspay.SDK.util;

import java.text.SimpleDateFormat;
import java.util.Date;

/**
 * 
 * 日期工具类
 * 
 
 
 */
public final class DateUtil {
	private DateUtil() {

	}

	/** 时间格式-yyyy-MM-dd HH:mm:ss */
	public final static String FORMMAT_1 = "yyyy-MM-dd HH:mm:ss";
	/** 时间格式-yyyyMMddHHmmss */
	public final static String FORMMAT_2 = "yyyyMMddHHmmss";
	public final static String FORMMAT_2_1 = "000000";
	public final static String FORMMAT_2_2 = "235959";
	/** 时间格式-yyyy-MM-dd */
	public final static String FORMMAT_3 = "yyyy-MM-dd";

	/**
	 * 将d转化为 yyyy-MM-dd HH:mm:ss时间格式
	 * 
	 * @param d
	 *            Date
	 * @return
	 */
	public static String formatDate1(Date d) {
		return formatDate(d, FORMMAT_1);
	}

	/**
	 * 将d转化为 yyyyMMddHHmmss时间格式
	 * 
	 * @param d
	 *            Date
	 * @return
	 */
	public static String formatDate2(Date d) {
		return formatDate(d, FORMMAT_2);
	}

	/**
	 * 将d转化为 yyyy-MM-dd时间格式
	 * 
	 * @param d
	 * @return
	 */
	public static String formatDate3(Date d) {
		return formatDate(d, FORMMAT_3);
	}

	/**
	 * 将时间延后minute分钟
	 * 
	 * @param d
	 *            Date
	 * @param minute
	 *            int
	 * @return
	 */
	@SuppressWarnings("deprecation")
	public static void delayMinute(Date d, int minute) {
		d.setMinutes(d.getMinutes() + minute);
	}

	/**
	 * 将时间延后day天
	 * 
	 * @param d
	 * @param day
	 */
	@SuppressWarnings("deprecation")
	public static void delayDate(Date d, int day) {
		d.setDate(d.getDate() + day);
	}

	/**
	 * 将时间按照指定格式进行格式化
	 * 
	 * @param d
	 * @param formmatStr
	 * @return
	 */
	public static String formatDate(Date d, String formmatStr) {
		if (d == null) {
			return "";
		}
		SimpleDateFormat sdf = new SimpleDateFormat(formmatStr);
		try {
			return sdf.format(d);
		} catch (Exception e) {
			return "";
		}
	}

	/**
	 * 将formmatStr格式时间串date格式化为Date时间
	 * 
	 * @param date
	 * @param formmatStr
	 * @return
	 */
	public static Date parseDate(String date, String formmatStr) {
		SimpleDateFormat sdf = new SimpleDateFormat(formmatStr);
		try {
			return sdf.parse(date);
		} catch (Exception e) {
			return null;
		}
	}
}
