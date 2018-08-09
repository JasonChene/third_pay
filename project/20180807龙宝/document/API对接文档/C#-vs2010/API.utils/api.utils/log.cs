using System;
using System.Collections.Generic;
using System.Text;

namespace API
{
    public class log
    {        
        public static void logstr(string str)
        {
            try
            {
                System.IO.StreamWriter sw = new System.IO.StreamWriter(System.Web.HttpContext.Current.Server.MapPath("Card.log"), true);
                sw.BaseStream.Seek(0, System.IO.SeekOrigin.End);
                sw.WriteLine("[" + DateTime.Now.ToString() + "]" + str);
                sw.Flush();
                sw.Close();
            }
            catch (Exception e)
            {
                Console.WriteLine(e);
            }
        }
    }
}
