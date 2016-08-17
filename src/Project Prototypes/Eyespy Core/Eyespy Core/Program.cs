using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Net;
using System.Net.Sockets;
using System.Threading;

namespace Eyespy_Core
{
    class Program
    {
        private static Socket sock;
        static byte[] arrResponseBytes = new byte[1024 * 64];
        static void Main(string[] args)
        {
            byte[] input = new byte[] { 1 };
            byte[] buffer = new byte[4096];
            try
            {
                Console.WriteLine("Creating and binding socket..");
                // create and define the socket.
                sock  = new Socket(AddressFamily.InterNetwork, SocketType.Raw, ProtocolType.IP);
                // create a new network endpoint to recieve all data packets.
                // an endpoint being the endpoint on the "line" which is our connections stream
                sock.Bind(new IPEndPoint(IPAddress.Parse("172.16.96.230"), 80)); 
                sock.IOControl(IOControlCode.ReceiveAll, input, null);
                // define what we're going to do when we've recieved data (in this case, pass on to the OnClientRecieve method)
                // which we will do asynchonously, so we create a new thread for the new "connection". 
                // allowing us to handle multiple connections at once
                sock.BeginReceive(arrResponseBytes, 0, arrResponseBytes.Length, SocketFlags.None, new AsyncCallback(OnClientRecieve), sock);
                ManualResetEvent reset = new ManualResetEvent(false);
                reset.WaitOne();
            }
            catch (SocketException se)
            {
                Console.Write(se.Message);
            }
            Console.ReadKey();
        }

        static void OnClientRecieve(IAsyncResult ar)
        {
            //Console.WriteLine("OnClientRecieve()");
            // so as to not overwrite our acceptor socket, we create a new socket based on the (asynchronus) 
            // information pulled from the IASyncResult variable. 
            Socket s = (Socket)ar.AsyncState;
            // if the EndRecieve() is succsesful, it will return the amount of bytes written to the recieve buffer.
            int count = s.EndReceive(ar);
            if (count >= 40)
            {
                try
                {
                    //Console.WriteLine("reached try..");
                    string strn = Encoding.UTF8.GetString(arrResponseBytes, 40, count - 40);
                    string bin = BitConverter.ToString(arrResponseBytes, 40, count - 40).Replace("-", " ");
                   // Console.WriteLine(strn); 
                    if (strn.StartsWith("GET"))
                        Console.WriteLine(HTTPReader.getURL(strn));
                    //Thread.Sleep(1000);
                }
                catch { }
            }
            s.BeginReceive(arrResponseBytes, 0, arrResponseBytes.Length, SocketFlags.None, new AsyncCallback(OnClientRecieve), s);
        }
    }
}
