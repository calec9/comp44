using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Net;
using System.IO;

namespace HTTPWriter
{
    class Program
    {
        static string input;
        static void send(string input)
        {
            HttpWebRequest request = (HttpWebRequest)WebRequest.Create("http://www.charlesnet.info/eyespy/submit.php");
            string data = "URL=" + input;
            byte[] postData = Encoding.UTF8.GetBytes(data);

            request.Method = "POST";
            request.ContentType = "application/x-www-form-urlencoded";
            request.ContentLength = postData.Length;

            using (var stream = request.GetRequestStream())
            {
                stream.Write(postData, 0, postData.Length);
            }

            var responce = (HttpWebResponse)request.GetResponse();
            var responceString = new StreamReader(responce.GetResponseStream()).ReadToEnd();

            Console.WriteLine("Sent " + postData.Length + " bytes.");
            Console.WriteLine("\tResponce: " + responceString);
        }
    }
}
