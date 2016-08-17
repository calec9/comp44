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
        static string kwords;
        static void Main(string[] args)
        {
            do
            {
                Console.Write("URL: ");
                input = Console.ReadLine();
                Console.WriteLine();
                Console.WriteLine("Keywords: ");
                kwords = Console.ReadLine();
                send(input, kwords);
            } while (input != "quit");
        }

        static void send(string input, string kw)
        {
            HttpWebRequest request = (HttpWebRequest)WebRequest.Create("http://www.charlesnet.info/eyespy/submit.php");
            string data = "URL=" + input + "&keywords=" + kw;
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
