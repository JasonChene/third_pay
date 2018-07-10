package com.suncity.pay.utils;

import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;

import org.apache.log4j.Logger;

public class MD5Utils
{
    private static final Logger LOG = Logger.getLogger(MD5Utils.class);

    private final static String[] strDigits =
    { "0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "a", "b", "c", "d", "e", "f" };

    public static String enCode(String strObj)
    {
        String resultString = null;
        try
        {
            resultString = new String(strObj);
            MessageDigest md = MessageDigest.getInstance("MD5");
            resultString = byteToString(md.digest(strObj.getBytes()));
        }
        catch (NoSuchAlgorithmException e)
        {
            LOG.error("MD5出现异常,加密失败.", e);
        }
        return resultString;
    }

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

    private static String byteToString(byte[] bByte)
    {
        StringBuffer sBuffer = new StringBuffer();
        for (int i = 0; i < bByte.length; i++)
        {
            sBuffer.append(byteToArrayString(bByte[i]));
        }
        return sBuffer.toString().toLowerCase();
        // return sBuffer.toString().toUpperCase();
    }
}