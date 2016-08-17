using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace Eyespy_Core_v1
{
    public class Stack
    {
        private int stackSize;          // maximum size of the stack before it needs resetting. this variable is assigned in the constructor by the 'size' parameter. 
        private int stackPointer;       // like in a real-world stack, a stack pointed variable is required. this integer hold the index of the last added item. (LIFO principle)

        public int stackFrameCount;     // number of times has the stack reached its maximum size and been reset - used for the 'About' context-menu item. 
        private string[] stackItems;    // the stack itelf, 
            
        public Stack(int size)
        {
            stackSize = size;
            stackPointer = 1;
            stackItems = new string[size];
        } 

        public bool isMemorized(string stackItem)
        {
            int loc = Array.IndexOf(stackItems, stackItem);
            if (loc > -1)
                return true;
            else
            {
                return false;
            }
        }

        public void push(string stackItem)
        {
            if (stackPointer == stackSize)
            {
                stackPointer = 1;
                Array.Clear(stackItems, 1, stackSize);
                push(stackItem);
                stackFrameCount++;
            }
            else
            {
                stackItems[stackPointer] = stackItem;
                stackPointer++;
            }
        }

    }
}
