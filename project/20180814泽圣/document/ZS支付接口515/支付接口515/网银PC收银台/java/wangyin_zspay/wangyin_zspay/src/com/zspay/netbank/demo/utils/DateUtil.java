package com.zspay.netbank.demo.utils;

import java.sql.Timestamp;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;
import java.util.GregorianCalendar;

/** 日期处理工具. */
public abstract class DateUtil {

	// 日期格式
	/** 日期格式：yyyy-MM-dd HH:mm:ss:SSS */
	public static String DF_DATETIME_LONG = "yyyy-MM-dd HH:mm:ss:SSS";
	/** 日期格式：yyyy-MM-dd HH:mm:ss */
	public static String DF_DATETIME = "yyyy-MM-dd HH:mm:ss";
	/** 日期格式：yyyy-MM-dd HH:mm */
	public static String DF_DATETIME_SHORT = "yyyy-MM-dd HH:mm";
	/** 日期格式：HH:mm:ss */
	public static String DF_TIME = "HH:mm:ss";
	/** 日期格式：HH:mm */
	public static String DF_TIME_SHORT = "HH:mm";
	/** 日期格式：yyyy-MM-dd */
	public static String DF_DATE = "yyyy-MM-dd";
	public final static String FORMMAT_2 = "yyyyMMddHHmmss";
	// 毫秒数
	/** 一个月的毫秒数 */
	public static long MONTH_MILLIS = 30 * 24 * 60 * 60 * 1000L;
	/** 一星期的毫秒数 */
	public static long WEEK_MILLIS = 7 * 24 * 60 * 60 * 1000L;
	/** 一天的毫秒数 */
	public static long DAY_MILLIS = 24 * 60 * 60 * 1000L;
	/** 一小时的毫秒数 */
	public static long HOUR_MILLIS = 60 * 60 * 1000L;
	/** 一分钟的毫秒数 */
	public static long MIN_MILLIS = 60 * 1000L;

	// 其它
	/** 时区偏移量 */
	public static long TIME_ZONE_OFFSET = 8;

	// ******************************日期转换******************************
	// 字符串转日期

	/** 将日期格式的String解析为Date，默认日期格式为yyyy-MM-dd HH:mm:ss，解析失败返回null. */
	public static Date StringtoDate(String value) {
		if (value == null || "".equals(value.trim())) {
			return null;
		}
		return toDate(value, FORMMAT_2);
	}

	// 字符串转字符串

	/** 将日期格式的String解析为Date，默认日期格式为yyyy-MM-dd HH:mm:ss，解析失败返回null. */
	public static String StringtoString(String value, String format) {
		if (value == null || "".equals(value.trim())) {
			return null;
		}
		Date date;
		try {
			date = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss").parse(value);
		} catch (ParseException e) {
			// TODO Auto-generated catch block
			return null;
		}
		return DateUtil.toStr(format, date);
	}

	// 日期转字符串

	/** 将日期格式化为String，默认格式为yyyy-MM-dd HH:mm:ss，默认日期为当前日期. */
	public static String toStr() {
		return toStr(DF_DATETIME);
	}

	/** 将日期格式化为String，格式由参数format指定，默认日期为当前日期，format值可使用本类常量或自定义. */
	public static String toStr(String format) {
		return toStr(format, new Date());
	}

	/** 将日期格式化为String，默认格式为yyyy-MM-dd HH:mm:ss，日期由参数date指定. */
	public static String toStr(Date date) {
		return toStr(DF_DATETIME, date);
	}

	/** 将日期格式化为String，格式由参数format指定，日期由参数date指定，format值可使用本类常量或自定义. */
	public static String toStr(String format, Date date) {
		return new SimpleDateFormat(format).format(date);
	}

	/** 将日期格式化为String，默认格式为yyyy-MM-dd HH:mm:ss，日期由参数timeMillis指定. */
	public static String toStr(long timeMillis) {
		return toStr(DF_DATETIME, timeMillis);
	}

	/** 将日期格式化为String，格式由参数format指定，日期由参数timeMillis指定，format值可使用本类常量或自定义. */
	public static String toStr(String format, long timeMillis) {
		return new SimpleDateFormat(format).format(timeMillis);
	}

	// 字符串转日期

	/** 将日期格式的String解析为Date，默认日期格式为yyyy-MM-dd HH:mm:ss，解析失败返回null. */
	public static Date toDate(String value) {
		return toDate(value, DF_DATETIME);
	}

	/** 将日期格式的String解析为Date，日期格式由参数format指定，解析失败返回null，format值可使用本类常量或自定义. */
	public static Date toDate(String value, String format) {
		try {
			return new SimpleDateFormat(format).parse(value);
		} catch (ParseException e) {
			return null;
		}
	}

	// 转Timestamp

	/**
	 * 将日期格式的String解析为Timestamp(便于保存到数据库)，默认日期格式为yyyy-MM-dd HH:mm:ss，解析失败返回null.
	 */
	public static Timestamp toTimestamp(String stringDate) {
		return toTimestamp(stringDate, DF_DATETIME);
	}

	/**
	 * 将日期格式的String解析为Timestamp(便于保存到数据库)，日期格式由参数format指定，解析失败返回null，
	 * format值可使用本类常量或自定义.
	 */
	public static Timestamp toTimestamp(String stringDate, String format) {
		Date date = toDate(stringDate, format);
		return toTimestamp(date);
	}

	/** 将Date转换为Timestamp(便于保存到数据库). */
	public static Timestamp toTimestamp(Date date) {
		if (date == null)
			return null;
		return new Timestamp(date.getTime());
	}

	// ******************************日期加减******************************

	/**
	 * 日期加减（年）.
	 * 
	 * @param oldDate
	 *            旧的日期对象
	 * @param year
	 *            年数
	 * @return 新的日期对象
	 */
	public static Date addYear(Date oldDate, int year) {
		Calendar cal = new GregorianCalendar();
		if (oldDate != null)
			cal.setTime(oldDate);
		cal.add(Calendar.YEAR, year);
		return cal.getTime();
	}

	/**
	 * 日期加减（月）.
	 * 
	 * @param oldDate
	 *            旧的日期对象
	 * @param month
	 *            月数
	 * @return 新的日期对象
	 */
	public static Date addMonth(Date oldDate, int month) {
		Calendar cal = new GregorianCalendar();
		if (oldDate != null)
			cal.setTime(oldDate);
		cal.add(Calendar.MONTH, month);
		return cal.getTime();
	}

	/**
	 * 日期加减（周）.
	 * 
	 * @param oldDate
	 *            旧的日期对象
	 * @param week
	 *            周数
	 * @return 新的日期对象
	 */
	public static Date addWeek(Date oldDate, int week) {
		Calendar cal = new GregorianCalendar();
		if (oldDate != null)
			cal.setTime(oldDate);
		cal.add(Calendar.WEEK_OF_YEAR, week);
		return cal.getTime();
	}

	/**
	 * 日期加减（天）.
	 * 
	 * @param oldDate
	 *            旧的日期对象
	 * @param day
	 *            天数
	 * @return 新的日期对象
	 */
	public static Date addDay(Date oldDate, int day) {
		Calendar cal = new GregorianCalendar();
		if (oldDate != null)
			cal.setTime(oldDate);
		cal.add(Calendar.DAY_OF_YEAR, day);
		return cal.getTime();
	}

	/**
	 * 日期加减（小时).
	 * 
	 * @param oldDate
	 *            旧的日期对象
	 * @param hour
	 *            小时数
	 * @return 新的日期对象
	 */
	public static Date addHour(Date oldDate, int hour) {
		Calendar cal = new GregorianCalendar();
		if (oldDate != null)
			cal.setTime(oldDate);
		cal.add(Calendar.HOUR_OF_DAY, hour);
		return cal.getTime();
	}

	/**
	 * 日期加减（分钟).
	 * 
	 * @param oldDate
	 *            旧的日期对象
	 * @param minute
	 *            分钟数
	 * @return 新的日期对象
	 */
	public static Date addMinute(Date oldDate, int minute) {
		Calendar cal = new GregorianCalendar();
		if (oldDate != null)
			cal.setTime(oldDate);
		cal.add(Calendar.MINUTE, minute);
		return cal.getTime();
	}

	/**
	 * 日期加减（秒).
	 * 
	 * @param oldDate
	 *            旧的日期对象
	 * @param second
	 *            秒数
	 * @return 新的日期对象
	 */
	public static Date addSecond(Date oldDate, int second) {
		Calendar cal = new GregorianCalendar();
		if (oldDate != null)
			cal.setTime(oldDate);
		cal.add(Calendar.SECOND, second);
		return cal.getTime();
	}

	// ******************************日期差值计算******************************

	/** 计算date1在date2后多少小时. */
	public static Integer getHour(Date date1, Date date2) {
		return (int) (date1.getTime() - date2.getTime()) / 1000 / 60 / 60;
	}

	/** 计算当前日期在指定日期date后多少小时. */
	public static Integer getHour(Date date) {
		return (int) (System.currentTimeMillis() - date.getTime()) / 1000 / 60 / 60;
	}

	/** 计算date1在date2后多少分钟. */
	public static Integer getMinutes(Date date1, Date date2) {
		return (int) (date1.getTime() - date2.getTime()) / 1000 / 60;
	}

	/** 计算当前日期在指定日期date后多少分钟. */
	public static Integer getMinutes(Date date) {
		return (int) (System.currentTimeMillis() - date.getTime()) / 1000 / 60;
	}

	/** 计算date1在date2后多少秒. */
	public static Integer getSeconds(Date date1, Date date2) {
		return (int) (date1.getTime() - date2.getTime()) / 1000;
	}

	/** 计算当前日期在指定日期date后多少秒. */
	public static Integer getSeconds(Date date) {
		return (int) (System.currentTimeMillis() - date.getTime()) / 1000;
	}

	// ******************************日期比较******************************

	/** 比较一个Date，是否在当前时间之前（之前返回true，否则返回false）. */
	public static boolean before(Date date) {
		Date now = new Date();
		return date.before(now);
	}

	/** 比较一个Date，是否在当前时间之后（之后返回true，否则返回false）. */
	public static boolean after(Date date) {
		Date now = new Date();
		return date.after(now);
	}

	// ******************************日期取整******************************

	/** 取得一天开始的日期(0点0分0秒0毫秒). */
	public static Date getDayStartDate(long timeMillis) {
		long millis = timeMillis - (timeMillis % DateUtil.DAY_MILLIS)
				- DateUtil.TIME_ZONE_OFFSET * HOUR_MILLIS;
		return new Date(millis);
	}

	/** 取得一周开始的日期(0点0分0秒0毫秒). */
	public static Date getWeekStartDate(long timeMillis) {
		Calendar cal = new GregorianCalendar();
		cal.setTimeInMillis(timeMillis);
		cal.add(Calendar.DAY_OF_YEAR, -cal.get(Calendar.DAY_OF_WEEK) + 1);
		long millis = cal.getTimeInMillis()
				- (timeMillis % DateUtil.DAY_MILLIS)
				- DateUtil.TIME_ZONE_OFFSET * HOUR_MILLIS;
		return new Date(millis);
	}

	/** 取得一月开始的日期(0点0分0秒0毫秒). */
	public static Date getMonthStartDate(long timeMillis) {
		Calendar cal = new GregorianCalendar();
		cal.setTimeInMillis(timeMillis);
		cal.add(Calendar.DAY_OF_YEAR, -cal.get(Calendar.DAY_OF_MONTH) + 1);
		long millis = cal.getTimeInMillis()
				- (timeMillis % DateUtil.DAY_MILLIS)
				- DateUtil.TIME_ZONE_OFFSET * HOUR_MILLIS;
		return new Date(millis);
	}

	// ******************************其它******************************

	/** 取得当前小时数. */
	public static long getCurrentHour() {
		return (System.currentTimeMillis() % DateUtil.DAY_MILLIS) / 1000 / 60
				/ 60 + DateUtil.TIME_ZONE_OFFSET;
	}

	/** 取得当前日期是星期几. */
	public static int getWeekDay() {
		Calendar c = new GregorianCalendar();
		return c.get(Calendar.DAY_OF_WEEK) - 1;
	}

	/** 取得第二天的0点的毫秒数. */
	public static long getTomorrowMillis() {
		long millis = System.currentTimeMillis()
				- (System.currentTimeMillis() % DateUtil.DAY_MILLIS)
				+ DAY_MILLIS - DateUtil.TIME_ZONE_OFFSET * HOUR_MILLIS;
		return millis;
	}

	/** 取得登陆时间间隔的描述（如xx天前在线）. */
	public static String getIntervalMemo(Date date) {
		long interval = System.currentTimeMillis() - date.getTime();
		if (interval >= DateUtil.MONTH_MILLIS) {
			return new StringBuilder().append(interval / DateUtil.MONTH_MILLIS)
					.append("个月前在线").toString();
		} else if (interval >= DateUtil.DAY_MILLIS) {
			return new StringBuilder().append(interval / DateUtil.DAY_MILLIS)
					.append("天前在线").toString();
		} else if (interval >= DateUtil.HOUR_MILLIS) {
			return new StringBuilder().append(interval / DateUtil.HOUR_MILLIS)
					.append("小时前在线").toString();
		} else if (interval >= 0) {
			return new StringBuilder().append(interval / DateUtil.MIN_MILLIS)
					.append("分钟前在线").toString();
		} else {
			return "从未登陆";
		}
	}

}