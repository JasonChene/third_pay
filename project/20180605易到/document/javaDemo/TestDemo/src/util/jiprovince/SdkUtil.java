package util.jiprovince;

import org.apache.log4j.Logger;

import java.io.StringWriter;
import java.math.BigDecimal;
import java.text.SimpleDateFormat;
import java.util.*;


public class SdkUtil {

    private final static Logger logger = Logger.getLogger(SdkUtil.class);

    private final static String FILE = "config";

    private static final char[] bcdLookup = {'0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f'};

    /**
     * 计算手续费
     *
     * @param amount
     * @param feeRate
     * @return
     */
    public static int caclFee(int amount, double feeRate) {
        double fee = amount * feeRate;
        BigDecimal bigDecimal = new BigDecimal(fee);
        int intFee = bigDecimal.setScale(0, BigDecimal.ROUND_HALF_UP).intValue();
        return intFee;
    }

    /**
     * 计算手续费，一鸣，商户笔笔提款手续费
     * <p>
     * 商户提款手续费 =  附加费（DRAWING_ADD） +  提款类型计算的手续费(提款类型： 0 为费率，2为增值费）
     * 0费率类 提款 = order.amount *DRAWING_FEE_RATE
     * 2增值费类 提款 =  DRAWING_FEE
     *
     * @param amount         计算金额 (交易金额-消费手续费 如：交易金额1000元，消费费率0.002，则计算金额为998元)
     * @param drawingAdd     附加费(分)
     * @param drawingFeeType 提款类型： 0 为费率，2为增值费
     * @param drawingFeeRate 提款费率-费率
     * @param drawingFee     提款费率-笔扣金额（分）
     * @return 商户提款手续费（分）  -1 错误
     */
    public static int caclDrawFee(int amount, int drawingAdd, int drawingFeeType, double drawingFeeRate, int drawingFee) {
        int fee = drawingAdd;
        switch (drawingFeeType) {
            case 0:
                int f = Integer.parseInt(Long.toString(Math.round(amount * drawingFeeRate)));
                fee = fee + f;
                break;
            case 2:
                fee += drawingFee;
                break;
            default:
                // 提款类型有误
                return -1;
        }
        return fee;
    }

    /**
     * 计算手续费，一鸣，机构笔笔提款手续费
     * <p>
     * 商户提款手续费 =  附加费（DRAWING_ADD） +  提款类型计算的手续费(提款类型： 0 为费率，2为增值费）
     * 0费率类 提款 = order.amount *DRAWING_FEE_RATE
     * 2增值费类 提款 =  DRAWING_FEE
     *
     * @param amount         计算金额 (交易金额-消费手续费 如：交易金额1000元，消费费率0.002，则计算金额为998元)
     * @param drawingAdd     附加费(分)
     * @param drawingFeeType 提款类型： 0 为费率，2为增值费
     * @param drawingFeeRate 费率
     * @param drawingFee     增值费（分）
     * @return 机构提款手续费（分）  -1 错误
     */
    public static int caclOrgDrawFee(int amount, int drawingAdd, int drawingFeeType, double drawingFeeRate, int drawingFee) {
        int fee = drawingAdd;
        switch (drawingFeeType) {
            case 0:
                int f = Integer.parseInt(Long.toString(Math.round(amount * drawingFeeRate)));
                fee = fee + f;
                break;
            case 1:
                fee += drawingFee;
                break;
            default:
                // 提款类型有误
                return -1;
        }
        return fee;
    }

    /**
     * 格式化手续费
     *
     * @return
     */
    public static int parseFee(double fee) {
        BigDecimal bigDecimal = new BigDecimal(fee);
        int intFee = bigDecimal.setScale(0, BigDecimal.ROUND_HALF_UP).intValue();
        return intFee;
    }

    public static String fen2yuan(Integer amount) {
        try {
            if (!amount.toString().matches("\\-?[0-9]+")) ;
        } catch (Exception e) {
            e.printStackTrace();
        }
        int flag = 0;
        String amString = amount.toString();
        if (amString.charAt(0) == '-') {
            flag = 1;
            amString = amString.substring(1);
        }
        StringBuffer result = new StringBuffer();
        if (amString.length() == 1) {
            result.append("0.0").append(amString);
        } else if (amString.length() == 2) {
            result.append("0.").append(amString);
        } else {
            String intString = amString.substring(0, amString.length() - 2);
            for (int i = 1; i <= intString.length(); i++) {
                result.append(intString.substring(intString.length() - i, intString.length() - i + 1));
            }
            result.reverse().append(".").append(amString.substring(amString.length() - 2));
        }
        if (flag == 1) {
            return "-" + result.toString();
        } else {
            return result.toString();
        }
    }

    public static String getStringValue(String key) {
        ResourceBundle resource = ResourceBundle.getBundle(FILE);
        return resource.getString(key);
    }

    public static Integer getIntValue(String key) {
        return Integer.valueOf(getStringValue(key));
    }


    public static final String bytesToHexStr(byte[] bcd) {
        StringBuffer s = new StringBuffer(bcd.length * 2);

        for (int i = 0; i < bcd.length; i++) {
            s.append(bcdLookup[(bcd[i] >>> 4 & 0xF)]);
            s.append(bcdLookup[(bcd[i] & 0xF)]);
        }

        return s.toString();
    }

    public static final byte[] hexStrToBytes(String s) {
        byte[] bytes = new byte[s.length() / 2];

        for (int i = 0; i < bytes.length; i++) {
            bytes[i] = ((byte) Integer.parseInt(s.substring(2 * i, 2 * i + 2), 16));
        }

        return bytes;
    }

    public static String formatDate(Date date, String pattern) {
        SimpleDateFormat sdf = new SimpleDateFormat(pattern);
        return sdf.format(date);
    }



    public static String substringByByte(String src, final int len) {
        if (src.length() < (len / 2))
            return src;

        try {
            byte[] bs = src.getBytes("GBK");

            //字符长度小于给定长度
            if (bs.length <= len) {
                return src;
            }

            //如果没有双字节字
            if (bs.length == src.length()) {
                return src.substring(0, len);
            }

            //处理双字节情况
            StringBuilder sb = new StringBuilder();
            int size = 0;
            int cnt = 0;
            for (Character ch : src.toCharArray()) {
                cnt = Character.toString(ch).getBytes("GBK").length;
                size += cnt;
                if (size <= len) {
                    sb.append(ch);
                }
            }
            return sb.toString();
        } catch (Exception e) {
            throw new RuntimeException(e);
        }
    }

    public static void main(String[] args) {
        System.out.println(caclFee(550, 0.0036d));
    }

    /**
     * 获取随机字符串
     *
     * @return
     */
    public static String getNonceStr() {
        String chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        StringBuffer sb = new StringBuffer();
        Random random = new Random();
        for (int i = 0; i < 32; i++) {
            sb.append(chars.charAt(random.nextInt(chars.length())));
        }
        return sb.toString();
    }
}
