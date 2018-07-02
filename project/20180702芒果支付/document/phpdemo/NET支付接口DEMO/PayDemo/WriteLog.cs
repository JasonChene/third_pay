using System;
using System.Collections.Generic;
using System.Web;
using System.IO;

namespace PayDemo
{
    public class Log
    {
        //����վ��Ŀ¼�´�����־Ŀ¼
        private string path = HttpContext.Current.Request.PhysicalApplicationPath + "logs";

        /**
        * ʵ�ʵ�д��־����
        * @param type ��־��¼����
        * @param className ����
        * @param content д������
        */
        public void WriteLog(string content)
        {
            if (!Directory.Exists(path))//�����־Ŀ¼�����ھʹ���
            {
                Directory.CreateDirectory(path);
            }

            string time = DateTime.Now.ToString("yyyy-MM-dd HH:mm:ss");//��ȡ��ǰϵͳʱ��
            string filename = path + "/" + DateTime.Now.ToString("yyyy-MM-dd") + ".log";//�����ڶ���־�ļ�����

            //���������־�ļ�������־�ļ�ĩβ׷�Ӽ�¼
            StreamWriter mySw = File.AppendText(filename);

            //����־�ļ�д������
            string write_content = time + ": " + content;
            mySw.WriteLine(write_content);

            //�ر���־�ļ�
            mySw.Close();
        }
    }
}