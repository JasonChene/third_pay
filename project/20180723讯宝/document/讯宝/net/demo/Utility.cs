using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Net;
using System.Security.Cryptography;
using System.Text;

namespace demo
{
    public class Utility
    {
        public static string CreateSign(Dictionary<string, string> prams)
        {
            StringBuilder sb = new StringBuilder();
            foreach (KeyValuePair<string, string> kv in prams)
            {
                if (string.IsNullOrEmpty(kv.Value))
                    continue;
                sb.Append(string.Format("{0}={1}&", kv.Key, kv.Value));
            }

            if (sb.Length > 0)
                sb = sb.Remove(sb.Length - 1, 1);
            return sb.ToString();
        }

        public static string CreateSignValue(Dictionary<string, string> prams)
        {
            StringBuilder sb = new StringBuilder();
            foreach (KeyValuePair<string, string> kv in prams)
            {
                if (string.IsNullOrEmpty(kv.Value) || kv.Key == "sign")
                    continue;
                sb.Append(string.Format("{0}={1}&", kv.Key, kv.Value));
            }

            if (sb.Length > 0)
                sb = sb.Remove(sb.Length - 1, 1);
            return sb.ToString();
        }

        public static string Md5Encrypt(string strToBeEncrypt)
        {
            string retStr;
            MD5CryptoServiceProvider m5 = new MD5CryptoServiceProvider();

            //创建md5对象
            byte[] inputBye;
            byte[] outputBye;

            //使用GB2312编码方式把字符串转化为字节数组．
            try
            {
                inputBye = Encoding.GetEncoding("utf-8").GetBytes(strToBeEncrypt);
            }
            catch (Exception ex)
            {
                inputBye = Encoding.GetEncoding("GB2312").GetBytes(strToBeEncrypt);
            }
            outputBye = m5.ComputeHash(inputBye);

            retStr = System.BitConverter.ToString(outputBye);
            retStr = retStr.Replace("-", "").ToLower();
            return retStr;
        }


    }
}
