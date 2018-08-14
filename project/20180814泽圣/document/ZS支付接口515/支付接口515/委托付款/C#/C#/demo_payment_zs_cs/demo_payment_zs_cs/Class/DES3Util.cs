using System;
using System.Collections.Generic;
using System.Text;
using System.Security.Cryptography;
using System.IO;

namespace saomo_demo_cs.Class
{
   
    public class DES3Util
    {

        //构造一个对称算法
        private SymmetricAlgorithm mCSP = new TripleDESCryptoServiceProvider();
        #region 加密解密函数
        /// <summary>
        /// 字符串的加密
        /// </summary>
        /// <param name="Value">要加密的字符串</param>
        /// <param name="sKey">密钥，必须32位</param>
        /// <param name="sIV">向量，必须是12个字符</param>
        /// <returns>加密后的字符串</returns>
        public string EncryptString(string Value, string sKey, string sIV)
        {
            try
            {
                ICryptoTransform ct;
                MemoryStream ms;
                CryptoStream cs;
                byte[] byt;
                mCSP.Key = Convert.FromBase64String(sKey);
                mCSP.IV = Convert.FromBase64String(sIV);
                //指定加密的运算模式
                mCSP.Mode = System.Security.Cryptography.CipherMode.ECB;
                //获取或设置加密算法的填充模式
                mCSP.Padding = System.Security.Cryptography.PaddingMode.PKCS7;
                ct = mCSP.CreateEncryptor(mCSP.Key, mCSP.IV);//创建加密对象
                byt = Encoding.UTF8.GetBytes(Value);
                ms = new MemoryStream();
                cs = new CryptoStream(ms, ct, CryptoStreamMode.Write);
                cs.Write(byt, 0, byt.Length);
                cs.FlushFinalBlock();
                cs.Close();
                return Convert.ToBase64String(ms.ToArray());
            }
            catch (Exception ex)
            {
                //MessageBox.Show(ex.Message, "出现异常", MessageBoxButtons.OK, MessageBoxIcon.Warning);
                return ("Error in Encrypting " + ex.Message);
            }
        }
        /// <summary>
        /// 解密字符串
        /// </summary>
        /// <param name="Value">加密后的字符串</param>
        /// <param name="sKey">密钥，必须32位</param>
        /// <param name="sIV">向量，必须是12个字符</param>
        /// <returns>解密后的字符串</returns>
        public string DecryptString(string Value, string sKey, string sIV)
        {
            try
            {
                ICryptoTransform ct;//加密转换运算
                MemoryStream ms;//内存流
                CryptoStream cs;//数据流连接到数据加密转换的流
                byte[] byt;
                //将3DES的密钥转换成byte
                mCSP.Key = Convert.FromBase64String(sKey);
                //将3DES的向量转换成byte
                mCSP.IV = Convert.FromBase64String(sIV);
                mCSP.Mode = System.Security.Cryptography.CipherMode.ECB;
                mCSP.Padding = System.Security.Cryptography.PaddingMode.PKCS7;
                ct = mCSP.CreateDecryptor(mCSP.Key, mCSP.IV);//创建对称解密对象
                byt = Convert.FromBase64String(Value);
                ms = new MemoryStream();
                cs = new CryptoStream(ms, ct, CryptoStreamMode.Write);
                cs.Write(byt, 0, byt.Length);
                cs.FlushFinalBlock();
                cs.Close();
                return Encoding.UTF8.GetString(ms.ToArray());
            }
            catch (Exception ex)
            {
                //MessageBox.Show(ex.Message, "出现异常", MessageBoxButtons.OK, MessageBoxIcon.Warning);
                return ("Error in Decrypting " + ex.Message);
            }
        }
        private string DESEncrypt(string strInput, string strKey)
        {
            try
            {
                byte[] bytesData = Encoding.UTF8.GetBytes(strInput); //加密明文.
                byte[] bytesVector = ASCIIEncoding.ASCII.GetBytes("12345678");    //加密向量.
                byte[] bytesKey = Encoding.UTF8.GetBytes(strKey);    //密匙.
                DESCryptoServiceProvider encoder = new DESCryptoServiceProvider();
                //用using可以确保流的关闭.
                using (MemoryStream mStream = new MemoryStream())
                {
                    CryptoStream cStream = new CryptoStream(mStream, encoder.CreateEncryptor(bytesKey, bytesVector), CryptoStreamMode.Write);
                    cStream.Write(bytesData, 0, bytesData.Length);
                    cStream.FlushFinalBlock();
                    return Convert.ToBase64String(mStream.ToArray());
                    //Converts an array of 8-bit unsigned integers to its equivalent string representation that is encoded with base-64 digits.
                }
            }
            catch (System.Exception ex)
            {
                return ex.Message;
            }
        }

        //解密方法.
        private string DESDecrypt(string strInput, string strKey)
        {
            try
            {
                byte[] bytesData = Convert.FromBase64String(strInput);  //加密了的字符串.
                // Converts the specified string, which encodes binary data as base-64 digits, to an equivalent 8-bit unsigned integer array.
                byte[] bytesKey = Encoding.UTF8.GetBytes(strKey);
                byte[] bytesVector = ASCIIEncoding.ASCII.GetBytes("12345678");    //加密向量.
                DESCryptoServiceProvider decoder = new DESCryptoServiceProvider();
                using (MemoryStream mStream = new MemoryStream())
                {
                    CryptoStream cStream = new CryptoStream(mStream, decoder.CreateDecryptor(bytesKey, bytesVector), CryptoStreamMode.Write);
                    cStream.Write(bytesData, 0, bytesData.Length);
                    cStream.FlushFinalBlock();
                    return Encoding.UTF8.GetString(mStream.ToArray());
                    //When overridden in a derived class, decodes all the bytes in the specified byte array into a string.
                }
            }
            catch (System.Exception ex)
            {
                return ex.Message;
            }
        }
        #endregion
    }
}