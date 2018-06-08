package util;

import java.security.SecureRandom;
import java.text.DecimalFormat;
import java.text.ParseException;
import java.text.ParsePosition;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;
import java.util.Random;
import java.util.regex.Pattern;

/**
 * 字符串相关方法
 *
 */
public class StringUtil {

	/**
	 * 将以逗号分隔的字符串转换成字符串数组
	 * @param valStr
	 * @return String[]
	 */
	public static String[] StrList(String valStr){
	    int i = 0;
	    String TempStr = valStr;
	    String[] returnStr = new String[valStr.length() + 1 - TempStr.replace(",", "").length()];
	    valStr = valStr + ",";
	    while (valStr.indexOf(',') > 0)
	    {
	        returnStr[i] = valStr.substring(0, valStr.indexOf(','));
	        valStr = valStr.substring(valStr.indexOf(',')+1 , valStr.length());
	        
	        i++;
	    }
	    return returnStr;
	}
	private final static Pattern URL = Pattern.compile("[a-zA-z]+://[^\\s]*");// 网址
    private final static Pattern IP_ADDRESS = Pattern
            .compile("((2[0-4]\\d|25[0-5]|[01]?\\d\\d?)\\.){3}(2[0-4]\\d|25[0-5]|[01]?\\d\\d?)");
    private final static Pattern QQ_NUMBER = Pattern.compile("[1-9]\\d{4,}");
    private final static Pattern HTML_TAG = Pattern
            .compile("<(.*)(.*)>.*<\\/\\1>|<(.*) \\/>");
    /*
     * 密码(由数字/大写字母/小写字母/标点符号组成，四种都必有，8位以上)
     */
    private final static Pattern PASS_WORD = Pattern
            .compile("(?=^.{8,}$)(?=.*\\d)(?=.*\\W+)(?=.*[A-Z])(?=.*[a-z])(?!.*\\n).*$");
    /*
     * 日期(年-月-日)
     */
    public final static Pattern DATE_TYPE_1 = Pattern
            .compile("(\\d{4}|\\d{2})-((0?([1-9]))|(1[1|2]))-((0?[1-9])|([12]([1-9]))|(3[0|1]))");
    /*
     * 日期(月/日/年)
     */
    public final static Pattern DATE_TYPE_2 = Pattern
            .compile("((0?[1-9]{1})|(1[1|2]))/(0?[1-9]|([12][1-9])|(3[0|1]))/(\\d{4}|\\d{2})");
    /*
     * 时间(小时:分钟, 24小时制)
     */
    public final static Pattern TIME = Pattern
            .compile("((1|0?)[0-9]|2[0-3]):([0-5][0-9])");
    /*
     * 汉字字符
     */
    public final static Pattern CHINESE = Pattern.compile("[\\u4e00-\\u9fa5]");
    /*
     * 中文及全角标点符号(字符)
     */
    public final static Pattern CHINESE_PUNCTUATION = Pattern
            .compile("[\\u3000-\\u301e\\ufe10-\\ufe19\\ufe30-\\ufe44\\ufe50-\\ufe6b\\uff01-\\uffee]");

    private final static Pattern EMAILER = Pattern
            .compile("\\w+([-+.]\\w+)*@\\w+([-.]\\w+)*\\.\\w+([-.]\\w+)*"); // 邮箱验证正则表达式
    private final static Pattern PHONE_NUMBER = Pattern
            .compile("((\\d{11})|^((\\d{7,8})|(\\d{4}|\\d{3})-(\\d{7,8})|(\\d{4}|\\d{3})-(\\d{7,8})-(\\d{4}|\\d{3}|\\d{2}|\\d{1})|(\\d{7,8})-(\\d{4}|\\d{3}|\\d{2}|\\d{1}))$)");// 电话号码正则表达式

    private static final Pattern loginPassword = Pattern
            .compile("((\\d+[a-zA-Z]+)|([a-zA-Z]+\\d+))+"); // 登录密码校验

    private static final Pattern payPassword = Pattern
            .compile("(?:0(?![01])|1(?![012])|2(?![123])|3(?![234])|4(?![345])|5(?![456])|6(?![567])|7(?![678])|8(?![789])|9(?![89])){6}"); // 支付密码校验
    //手机号码正则表达式
    private static final Pattern moble = Pattern.compile("^1[3|4|5|8|9]\\d{9}$");

    /**
     * 判断是不是一个合法的电子邮件地址
     *
     * @param email
     * @return
     */
    public static boolean isEmail(String email) {
        if (email == null || email.trim().length() == 0)
            return false;
        return EMAILER.matcher(email).matches();
    }

    /**
     * 判断是不是电话号码
     *
     * @param phoneNumber
     * @return
     */
    public static boolean isPhoneNumber(String phoneNumber) {
        if (phoneNumber == null || phoneNumber.trim().length() == 0)
            return false;
        return PHONE_NUMBER.matcher(phoneNumber).matches();
    }

    /**
     * 判断是不是手机号码
     *
     * @param mobleNumber
     * @return
     */
    public static boolean isMobleNumber(String mobleNumber) {
        if (mobleNumber == null || mobleNumber.trim().length() == 0)
            return false;
        return moble.matcher(mobleNumber).matches();
    }

    /**
     * 判断是不是URL地址
     *
     * @param url
     * @return
     */
    public static boolean isURL(String url) {
        if (url == null || url.trim().length() == 0) {
            return false;
        }
        return URL.matcher(url).matches();
    }

    /**
     * 判断是不是IP地址
     *
     * @param IP
     * @return
     */
    public static boolean isIPAddress(String IP) {
        if (IP == null || IP.trim().length() == 0) {
            return false;
        }
        return IP_ADDRESS.matcher(IP).matches();
    }

    /**
     * 判断是不是QQ号码
     *
     * @param qq
     * @return
     */
    public static boolean isQQNumber(String qq) {
        if (qq == null || qq.trim().length() == 0) {
            return false;
        }
        return QQ_NUMBER.matcher(qq).matches();
    }

    /**
     * 判断是不是Html标签
     *
     * @param html
     * @return
     */
    public static boolean isHtmlTag(String html) {
        if (html == null || html.trim().length() == 0) {
            return false;
        }
        return HTML_TAG.matcher(html).matches();
    }

    /**
     * 密码(由数字/大写字母/小写字母/标点符号组成，四种都必有，8位以上)
     *
     * @param pwd
     * @return
     */
    public static boolean isPassWord(String pwd) {
        if (pwd == null || pwd.trim().length() == 0) {
            return false;
        }
        return PASS_WORD.matcher(pwd).matches();
    }

    /**
     * 判断字符串是否为空字符串
     *
     * @param str
     * @return
     */
    public static boolean isEmpty(String str) {
        if (str == null)
            return true;
        if (str.trim().length() == 0)
            return true;
        return false;
    }

    /**
     * 将String类型的年月转换成日期
     *
     * @param ts
     * @return
     */
    public static Date getStringToDateFormat(String ts) {
        SimpleDateFormat formatter = new SimpleDateFormat("yyyy-MM-dd");
        try {
            return formatter.parse(ts);
        } catch (ParseException e) {
            e.printStackTrace();
        }
        return null;
    }

    /**
     * 校验登录密码
     *
     * @param pwd
     * @return
     */
    public static boolean isLoginPassword(String pwd) {
        if (pwd == null || pwd.trim().length() == 0)
            return false;
        return loginPassword.matcher(pwd).matches();
    }

    /**
     * 校验支付密码
     *
     * @param ppwd
     * @return
     */
    public static boolean isPayPassword(String ppwd) {
        if (ppwd == null || ppwd.trim().length() == 0) {
            return false;
        }
        return payPassword.matcher(ppwd).matches();
    }

    /**
     * 随机获取字符串长度
     *
     * @param length 字符串长度
     * @return
     */
    public static String getRandomString(int length) {
        String base = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        Random random = new SecureRandom();
        StringBuffer sb = new StringBuffer();
        for (int i = 0; i < length; i++) {
            int num = random.nextInt(base.length());
            sb.append(base.charAt(num));
        }
        return sb.toString();
    }

    public static String byteArrayToHexString(byte[] b) {
        StringBuffer sb = new StringBuffer(b.length * 2);
        for (int i = 0; i < b.length; i++) {
            int v = b[i] & 0xff;
            if (v < 16) {
                sb.append('0');
            }
            sb.append(Integer.toHexString(v));
        }
        return sb.toString().toUpperCase();
    }

    /**
     * 将分转换为元
     *
     * @param amountLong
     * @return
     */
    public static String parseAmountLong2Str(Long amountLong) {
        if (amountLong == null) {
            return "0.00";
        }
        DecimalFormat df = new DecimalFormat("0.00");
        double d = amountLong / 100d;
        String s = df.format(d);
        return s;
    }

    /**
     * 日期转换为自定义格式输出
     */
    public static String dateToString(Date date, String formatType) {
        if (date == null) {
            return null;
        }
        if (formatType == null || "".equals(formatType)) {
            return null;
        }
        String dateStr = "";
        try {
            SimpleDateFormat sdf = new SimpleDateFormat(formatType);
            dateStr = sdf.format(date);
            return dateStr;
        } catch (Exception e) {
            return null;
        }
    }


    /**
     * 身份证验证
     *
     * @param idCard
     * @return
     */
    public static boolean checkIdCard(String idCard) {
        if (idCard.length() != 18) {
            return false;
        }
        String curYear = "" + Calendar.getInstance().get(Calendar.YEAR);
        int y3 = Integer.valueOf(curYear.substring(2, 3));
        int y4 = Integer.valueOf(curYear.substring(3, 4));
        // 43 0103 1973 0 9 30 051 9
        return idCard.matches("^(1[1-5]|2[1-3]|3[1-7]|4[1-6]|5[0-4]|6[1-5]|71|8[1-2])\\d{4}(19\\d{2}|20([0-" + (y3 - 1) + "][0-9]|" + y3 + "[0-" + y4
                + "]))(((0[1-9]|1[0-2])(0[1-9]|[1-2][0-9]|3[0-1])))\\d{3}([0-9]|x|X)$");
    }
    private static int ID_LENGTH = 17;
    public static boolean vIDNumByCode(String idNum) {

        // 系数列表
        int[] ratioArr = {7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2};

        // 校验码列表
        char[] checkCodeList = {'1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'};

        // 获取身份证号字符数组
        char[] cIds = idNum.toCharArray();

        // 获取最后一位（身份证校验码）
        char oCode = cIds[ID_LENGTH];
        int[] iIds = new int[ID_LENGTH];
        int idSum = 0;// 身份证号第1-17位与系数之积的和
        int residue = 0;// 余数(用加出来和除以11，看余数是多少？)

        for (int i = 0; i < ID_LENGTH; i++) {
            iIds[i] = cIds[i] - '0';
            idSum += iIds[i] * ratioArr[i];
        }

        residue = idSum % 11;// 取得余数

        return Character.toUpperCase(oCode) == checkCodeList[residue];
    }
    
    public static boolean vId(String idNum) {
        return vIDNumByCode(idNum) && checkIdCard(idNum);
    }

    public static Date stringToDate(String dateString) {
        ParsePosition position = new ParsePosition(0);
        SimpleDateFormat simpleDateFormat = new SimpleDateFormat("HH:mm");
        Date dateValue = simpleDateFormat.parse(dateString, position);
        return dateValue;
    }

    /**
     * 将元字符串转换为分
     *
     * @param amountStr
     * @return
     */
    public static long parseAmountStr2Long(String amountStr) {
        if (amountStr == null || "".equals(amountStr)) {
            return 0L;
        }
        double amount = Double.parseDouble(amountStr);
        Double db = amount * 100;
        DecimalFormat df = new DecimalFormat("#");
        String s = df.format(db);
        return Long.parseLong(s);
    }

    /**
     * 将yyyy-MM-dd HH:mm:ss
     *
     * @param ts yyyy-MM-dd HH:mm:ss
     * @return HH:mm
     */
    public static String getStringToTimeFormat(String ts) {
        if (ts == null || "".equals(ts)) {
            return null;
        }
        String dateStr = "";
        ParsePosition position = new ParsePosition(0);
        SimpleDateFormat simpleDateFormat = new SimpleDateFormat("yyyy-MM-dd HH:mm");
        Date dateValue = simpleDateFormat.parse(ts, position);
        SimpleDateFormat formatters = new SimpleDateFormat("HH:mm");
        try {
            dateStr = formatters.format(dateValue);
            return dateStr;
        } catch (Exception e) {
            e.printStackTrace();
        }
        return null;
    }

    /**
     * @param str
     * @return
     */
    public static String getGameName(String str) {
        String temp = str;
        char[] chars = temp.toCharArray();
        StringBuilder stringBuilder = new StringBuilder();
        for (int i = 0; i < chars.length; i++) {
            if ((chars[i] >= 19968 && chars[i] <= 40869)) {//|| (chars[i] >= 97 && chars[i] <= 122) || (chars[i] >= 65 && chars[i] <= 90)) {
                stringBuilder.append(chars[i]);
            }
        }
        temp = new String(stringBuilder);
        return temp;
    }

    /**
     * 生成随机字符串
     * @param length 随机字符串的长度
     * @return 字符串
     */
    public static String generateString(int length){
        StringBuilder sb = new StringBuilder();
        Random random = new Random();
        for (int i = 0; i < length;i++){
            sb.append("1234567890".charAt(random.nextInt("1234567890".length())));
        }
        return sb.toString();
    }
    /**
     * 替换固定长度的字符串
     * @param str
     * @param start
     * @param length
     * @return
     */
    public static String replaceChar(String str,int start,int length){
    	StringBuffer sb = new StringBuffer();
    	sb.append(str.substring(0, start));
    	for(int i=0;i<length;i++){
    		sb.append('*');
    	}
    	sb.append(str.substring(start+length));
    	return sb.toString();
    }
    
    /**
     * 校验银行卡
     *  数字校验算法 
	 *  模10“隔位乘2 加”校验数算法： 计算步骤如下： 
	 *	步骤1：从右边第1个数字开始每隔一位乘以2。 
	 *	步骤2：把在步骤1中获得的乘积的各位数字与原号码中未乘2的各位数字相加。 
	 *	步骤3：把步骤2得到的总和从该值的下一个以零结尾的数中减去[得数是总和个位数字的“10”的补数]。如果在步骤2得到的总和是以0结尾的数（30，40等等） ，则校验数字是0。
     * @param cardNo
     * @return
     */
    public static boolean verifyBankCard(String cardNo){
    	char bit = getBankCardCheckCode(cardNo.substring(0, cardNo.length() - 1));
		return cardNo.charAt(cardNo.length() - 1) == bit;
    }
    
    /**
	 * 从不含校验位的银行卡卡号采用 Luhm 校验算法获得校验位
	 * 
	 * @param nonCheckCodeCardId
	 * @return
	 */
	public static char getBankCardCheckCode(String nonCheckCodeCardId) {
		if (nonCheckCodeCardId == null || !nonCheckCodeCardId.matches("\\d+")) {
			throw new IllegalArgumentException("Bank card code must be number!");
		}
		
		char[] chs = nonCheckCodeCardId.trim().toCharArray();
		int luhmSum = 0;
		for (int i = chs.length - 1, j = 0; i >= 0; i--, j++) {
			int k = chs[i] - '0';
			if (j % 2 == 0) {
				k *= 2;
				k = k / 10 + k % 10;
			}
			luhmSum += k;
		}
		return (luhmSum % 10 == 0) ? '0' : (char) ((10 - luhmSum % 10) + '0');
	}
	
	//判断有效时间：yyyyMMddHHmmss
    public static boolean isLegalDate(String dateString) {  
    String regeb = "^(?:(?!0000)[0-9]{4}(?:(?:0[1-9]|1[0-2])(?:0[1-9]|1[0-9]|2[0-8])|(?:0[13-9]|1[0-2])(?:29|30)|(?:0[13578]|1[02])-31)|(?:[0-9]{2}(?:0[48]|[2468][048]|[13579][26])|(?:0[48]|[2468][048]|[13579][26])00)0229)((0[0-9])|(1[0-9])|(2[0-3]))[0-5][0-9][0-5][0-9]{1}";  
    		return dateString.length()==14;//dateString.matches(regeb);  
    } 
    
    public static void main(String[] args) {
    	System.out.print(isLegalDate("20170331103752."));//  20170331103752   //20170331001206
	}
	
	
	
	
}
