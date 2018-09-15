using System;
using System.Text;
using System.IO;
using System.Security.Cryptography;

namespace API.Utils
{
    /// <summary>
    /// DES 的摘要说明。
    /// </summary>
    public abstract class DES
    {
        public DES()
        {

        }

        /// <summary>
        /// 编码
        /// </summary>
        /// <param name="a_strString"></param>
        /// <param name="a_strKey"></param>
        /// <returns>string</returns>
        public static string Encrypt3DESSZX(string a_strString, string a_strKey)
        {
            // 计算24位key
            if (a_strKey.Length < 24)
            {
                string newKey = a_strKey;
                for (int i = 0; i < 24 / a_strKey.Length; i++)
                {
                    newKey += a_strKey;
                }
                a_strKey = newKey;
            }

            a_strKey = a_strKey.Substring(0, 24);

            TripleDESCryptoServiceProvider DES = new TripleDESCryptoServiceProvider();

            DES.Key = ASCIIEncoding.ASCII.GetBytes(a_strKey);
            DES.Mode = CipherMode.ECB;

            ICryptoTransform DESEncrypt = DES.CreateEncryptor();

            byte[] Buffer = ASCIIEncoding.ASCII.GetBytes(a_strString);
            return Convert.ToBase64String(DESEncrypt.TransformFinalBlock(Buffer, 0, Buffer.Length));
        }

        /// <summary>
        /// 编码
        /// </summary>
        /// <param name="a_strString"></param>
        /// <param name="a_strKey"></param>
        /// <returns>string</returns>
        public static string Encrypt3DESJW(string a_strString, string a_strKey)
        {
            // 计算24位key
            if (a_strKey.Length < 24)
            {
                a_strKey += "000000000000000000000000";
            }

            a_strKey = a_strKey.Substring(0, 24);

            TripleDESCryptoServiceProvider DES = new TripleDESCryptoServiceProvider();

            DES.Key = ASCIIEncoding.ASCII.GetBytes(a_strKey);
            DES.Mode = CipherMode.ECB;

            ICryptoTransform DESEncrypt = DES.CreateEncryptor();

            byte[] Buffer = ASCIIEncoding.ASCII.GetBytes(a_strString);
            return Convert.ToBase64String(DESEncrypt.TransformFinalBlock(Buffer, 0, Buffer.Length));
        }

        /// <summary>
        /// 解码
        /// </summary>
        /// <param name="a_strString"></param>
        /// <param name="a_strKey"></param>
        /// <returns>string</returns>
        public static string Decrypt3DES(string a_strString, string a_strKey)
        {
            TripleDESCryptoServiceProvider DES = new TripleDESCryptoServiceProvider();

            DES.Key = ASCIIEncoding.ASCII.GetBytes(a_strKey);
            DES.Mode = CipherMode.ECB;
            DES.Padding = System.Security.Cryptography.PaddingMode.PKCS7;

            ICryptoTransform DESDecrypt = DES.CreateDecryptor();

            string result = "";
            try
            {
                byte[] Buffer = Convert.FromBase64String(a_strString);
                result = ASCIIEncoding.ASCII.GetString(DESDecrypt.TransformFinalBlock(Buffer, 0, Buffer.Length));
            }
            catch
            {

            }
            return result;
        }


    }
}
