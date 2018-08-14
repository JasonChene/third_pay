using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.IO; 
using System.Security.Cryptography;
using System.Text;
namespace saomo_demo_cs.Class
{
    public class DES3Util2
    {
        public static string Encrypt3DES(string a_strString, string a_strKey, string a_strIV)
        {
            System.Security.Cryptography.TripleDESCryptoServiceProvider des = new System.Security.Cryptography.TripleDESCryptoServiceProvider();
            byte[] inputByteArray = System.Text.Encoding.UTF8.GetBytes(a_strString);
            des.Key = System.Text.Encoding.UTF8.GetBytes(a_strKey);
            des.IV = System.Text.Encoding.UTF8.GetBytes(a_strIV);
            des.Mode = System.Security.Cryptography.CipherMode.CBC;
            des.Padding = System.Security.Cryptography.PaddingMode.PKCS7;
            System.IO.MemoryStream ms = new System.IO.MemoryStream();
            System.Security.Cryptography.CryptoStream cs = new System.Security.Cryptography.CryptoStream(ms, des.CreateEncryptor(), System.Security.Cryptography.CryptoStreamMode.Write);
            System.IO.StreamWriter swEncrypt = new System.IO.StreamWriter(cs);
            swEncrypt.WriteLine(a_strString);
            swEncrypt.Close();
           
            //把内存流转换成字节数组，内存流现在已经是密文了
            byte[] bytesCipher = ms.ToArray();
            //内存流关闭
           
            string base64String= System.Convert.ToBase64String(bytesCipher);
            //string by = "";
            //foreach (byte b in bytesCipher)
            //{
            //    by += b.ToString() + " ";
            //}
            //SbeLogger.info("【3DESBytes】" + by);
            //byte[] FromBase64String = Convert.FromBase64String(base64String);
            //ms = new MemoryStream(FromBase64String);
            //cs = new CryptoStream(ms, des.CreateDecryptor(), CryptoStreamMode.Read);
            //StreamReader sr = new StreamReader(cs);
            ////输出解密后的内容
            //string DecryptString = sr.ReadLine();
           
            //加密流关闭
            cs.Close();
            des.Clear();
            ms.Close();
           

            return base64String;
        }


        public static string Decrypt3DES(string a_strString, string a_strKey, string a_strIV)
        {
            TripleDESCryptoServiceProvider des = new TripleDESCryptoServiceProvider();
            byte[] inputByteArray = Encoding.UTF8.GetBytes(a_strString);
            des.Key = System.Text.Encoding.UTF8.GetBytes(a_strKey);
            des.IV = System.Text.Encoding.UTF8.GetBytes(a_strIV);
            des.Mode = CipherMode.CBC;
            des.Padding = PaddingMode.PKCS7;
            MemoryStream ms = new MemoryStream();
            CryptoStream cs = new CryptoStream(ms, des.CreateDecryptor(), CryptoStreamMode.Read);            
            byte[] FromBase64String = Convert.FromBase64String(a_strString);
            ms = new MemoryStream(FromBase64String);
            cs = new CryptoStream(ms, des.CreateDecryptor(), CryptoStreamMode.Read);
            StreamReader sr = new StreamReader(cs);
            //输出解密后的内容
            string DecryptString = sr.ReadLine();
            //加密流关闭
            cs.Close();
            des.Clear();
            ms.Close();
            sr.Close();
            return DecryptString;
            
        }
        
       // #endregion



    }
}