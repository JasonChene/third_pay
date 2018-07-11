package com.suncity.pay;

import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;

import org.apache.log4j.Logger;

public class Test
{
    /**
     * 日志记录对象
     */
    private static final Logger LOG = Logger.getLogger(Test.class);

    private final static String[] strDigits =
    { "0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "a", "b", "c", "d", "e", "f" };

    // return Hexadecimal
    private static String byteToArrayString(byte bByte)
    {
        int iRet = bByte;
        if (iRet < 0)
        {
            iRet += 256;
        }
        int iD1 = iRet / 16;
        int iD2 = iRet % 16;
        return strDigits[iD1] + strDigits[iD2];
    }

    /**
     * 转换字节数组为16进制字串
     * @param bByte 字节数组
     * @return
     * @author Terry
     */
    private static String byteToString(byte[] bByte)
    {
        StringBuffer sBuffer = new StringBuffer();
        for (int i = 0; i < bByte.length; i++)
        {
            sBuffer.append(byteToArrayString(bByte[i]));
        }
        return sBuffer.toString().toUpperCase();
    }

    public static String GetMD5Code(String strObj)
    {
        String resultString = null;
        try
        {
            resultString = new String(strObj);
            MessageDigest md = MessageDigest.getInstance("MD5");
            // md.digest() 该函数返回值为存放哈希值结果的byte数组
            resultString = byteToString(md.digest(strObj.getBytes()));
        }
        catch (NoSuchAlgorithmException e)
        {
            LOG.error("MD5出现异常,加密失败.", e);
        }
        return resultString;
    }

    public static void main(String[] args)
    {
        System.out.println(Test.GetMD5Code("aaaa"));
    }
}