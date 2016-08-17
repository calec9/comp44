using System;
using System.Collections.Generic;
using System.Linq;
using System.Windows.Forms;
using System.Drawing;
using System.Net;
using System.Net.Sockets;
using System.Threading;
using System.Text;

namespace Eyespy_Core_v1
{
    public class Systray : Form
    {
        private static Socket sock; // socket used to create the raw socket
        private static NotifyIcon trayIcon; // system tray icon component
        private static ContextMenu trayMenu; // system tray icon meny
        private static int urlcount; // how many URL's have been processed since launch
        private static int repeatURLCount; // how many repeated URL's have been blocked
        private static int blockedURLCount; // how many URL's have been blocked (too long or invalid format)
        
        static byte[] bResponceBytes = new byte[4096]; // variable serving as buffer for incomming bytes of data

        static Stack stack = new Stack(15); // instance of the Stack class (see Stack class definition) parameter (15) is size of stack
        static HTTPReader reader = new HTTPReader(); // instance of the HTTPWriter class (see class definition) 
        static HTTPWriter writer = new HTTPWriter(); // instance of the HTTPReader class (see class definition)

        /// <summary>
        /// The main entry point for the application.
        /// </summary>
        [STAThread]
        static void Main()
        {
            Application.Run(new Systray()); // run 
        }

        static void OnClientRecieve(IAsyncResult ar)
        {
            // so as to not overwrite our acceptor socket, we create a new socket based on the (asynchronus) 
            // information pulled from the IASyncResult variable. 
            Socket s = (Socket)ar.AsyncState;
            // if the EndRecieve() is succsesful, it will return the amount of bytes written to the recieve buffer.
            int count = s.EndReceive(ar); // how many bytes 
            if (count >= 40) // we'll only assume it's a valid HTTP header if the byte count is greater than 40. as the domain name is located around byte 40
            {
                try
                {
                    // the data we recieve is recieved as an array of bytes, so will need converting and writing 
                    // to a new variable as a string
                    string data = Encoding.UTF8.GetString(bResponceBytes, 40, count - 40);
                    if (data.StartsWith("GET")) // making sure the HTML we've received IS valid as the header should be a GET
                    {
                        string URL = reader.getURL(data); // use the object created above to get the URL via the function 
                        if ((URL != "") && (URL.Length <= 64)) // logic AND to make sure both conditions evaluate to true (and hence verify URL validity)
                        {
                            if (stack.isMemorized(URL) == false) // using the stack data structure to only submit this URL if it doesn't exist within the current stack frame. 
                            {
                                string URLParam = "http://" + URL; // the protocol should be pasted to the prefix of the URL so HTMLAgilityPack can retrieve the keywords
                                URLParam = URLParam.Remove(URLParam.Length - 2); // C# adds two escape characters at the end of each link: \n\t - these need to be removed as they invalidate the URL. 
                                string keywords = reader.getMetadata(URLParam);   // retrieve metadata                        
                                writer.submitToHQ(URL, keywords); // submit the URL to the submit.php file
                                stack.push(URL); // push the link to the top of the stack
                                urlcount++; // increase the number of URL's processed by one. 
                            }
                            else
                                repeatURLCount++; // describe the stack frame
                        }
                        else
                        {
                            //MessageBox.Show("No URL input.");
                        }
                    }
                    else blockedURLCount++;
                }
                catch (Exception e)
                {

                }
            }
            s.BeginReceive(bResponceBytes, 0, bResponceBytes.Length, SocketFlags.None, new AsyncCallback(OnClientRecieve), s); // continue socket reception asynchonously 
        }
        /// <summary>
        /// Constructor for the class - will instantiate the system tray icon.
        /// </summary>
        public Systray()
        {
            trayMenu = new ContextMenu();
            trayMenu.MenuItems.Add("About", About);

            trayIcon = new NotifyIcon();
            trayIcon.Text = "Eyespy Core v1";
            trayIcon.Icon = new Icon(SystemIcons.Application, 40, 40);

            trayIcon.ContextMenu = trayMenu;
            trayIcon.Visible = true;

            byte[] input = new byte[] { 1 };
            byte[] buffer = new byte[4096];

            try
            {
                // create and define the socket.
                sock = new Socket(AddressFamily.InterNetwork, SocketType.Raw, ProtocolType.IP);
                // create a new network endpoint to recieve all data packets.
                // an endpoint being the endpoint on the "line" which is our connections stream
                sock.Bind(new IPEndPoint(IPAddress.Parse("172.6.14.216"), 80)); 
                sock.IOControl(IOControlCode.ReceiveAll, input, null);
                // define what we're going to do when we've recieved data (in this case, pass on to the OnClientRecieve method)
                // which we will do asynchonously, so we create a new thread for the new "connection". 
                // allowing us to handle multiple connections at once

                sock.BeginReceive(bResponceBytes, 0, bResponceBytes.Length, SocketFlags.None, new AsyncCallback(OnClientRecieve), sock);
                ManualResetEvent reset = new ManualResetEvent(false);
                reset.WaitOne();
            }
            catch (Exception e)
            {
                MessageBox.Show(e.Message, "Diagnostics");
                Application.Exit();
            }
        }


        /// <summary>
        /// launched procedrue to load the icon
        /// </summary>
        /// <param name="e"></param>
        protected override void OnLoad(EventArgs e)
        {
            Visible = false;
            ShowInTaskbar = false;

            base.OnLoad(e);
        }

        /// <summary>
        /// "About" menu item onload event to display detailed info 
        /// </summary>
        /// <param name="sender"></param>
        /// <param name="e"></param>
        protected void About(object sender, EventArgs e)
        {
            MessageBox.Show("URL count: " + urlcount + "\nStack frame count: " + stack.stackFrameCount
                            + "\nRepeat URL count: " + repeatURLCount, "Eyespy Core"); 
        }
        
        /// <summary>
        /// distroy the icon (on exit)
        /// </summary>
        /// <param name="disp">true by default</param>
        protected override void Dispose(bool disp)
        {
            if (disp)
                trayIcon.Dispose();
            base.Dispose(disp);
        }

        /// <summary>
        /// overriden constructor for the application class to launch an dcreate the system tray icon
        /// </summary>
        private void InitializeComponent()
        {
            this.SuspendLayout();
            // 
            // Systray
            // 
            this.ClientSize = new System.Drawing.Size(274, 231);
            this.Name = "Systray";
            this.Load += new System.EventHandler(this.Systray_Load);
            this.ResumeLayout(false);

        }

        private void Systray_Load(object sender, EventArgs e)
        {

        }
    }
}
