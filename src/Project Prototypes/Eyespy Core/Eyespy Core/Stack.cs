using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace Eyespy_Core
{
    class Stack
    {
        private string[] stackItems;
        private int stackSize;
        private int stackPointer;

        Stack()
        {
            int stackSize = 15;
            int stackPointer = 0;
        }

        bool isMemorized(string stackItem)
        {
            int loc = Array.IndexOf(stackItems, stackItem);
            if (loc > -1)
                return true;
            else
                return false;
        }

        void push(string stackItem)
        {
            if (stackPointer == stackSize)
            {
                stackPointer = 0;
                this.push(stackItem);
            }
            else 
                stackItems[stackPointer] = stackItem;
        }

        void pop(string stackItem)
        {
        }
    }
}
