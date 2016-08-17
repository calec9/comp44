using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace Eyespy_Core
{
    class HTTPReader
    {
        public static string getURL(string data)
        {
            return getBetween(data, "Host:", "Connection:");
        }

        public static string getMetadata(string data)
        {
            return getBetween(data, "a", "b");
        }

        public static string getBetween(string strSource, string strStart, string strEnd)
        {
            int Start, End;
            if (strSource.Contains(strStart) && strSource.Contains(strEnd))
            {
                Start = strSource.IndexOf(strStart, 0) + strStart.Length;
                End = strSource.IndexOf(strEnd, Start);
                return strSource.Substring(Start, End - Start);
            }
            else
            {
                return "";
            }
        }
    }
}
