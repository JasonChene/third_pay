using System;
using System.Collections.Generic;

using System.Text;

namespace saomo_demo_cs.Class
{
    public class MainTest
    {
        static void Main(string[] args)
        {
            DES3Util helper = new DES3Util();
            //加密
            string oldValue = "13800138000";
            //加密后结果
            //密钥,必须32位
            string sKey = "qJzGEh6hESZDVJeCnFPGuxzaiB7NLQM5";
            //向量，必须是12个字符
            string sIV = "andyliu1234=";
            //print
            string newValue = helper.EncryptString(oldValue, sKey, sIV);
            Console.WriteLine("加密后:" + newValue);
            //解密
            string desValue = helper.DecryptString(newValue, sKey, sIV);
            //
            Console.WriteLine("解密后:" + desValue);
            Console.ReadLine();
        }
    }
}