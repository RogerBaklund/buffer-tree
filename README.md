Buffer tree
-----------

Two PHP classes for creating and manipulating a hierarchy of output buffers.

The class `buffer` represents a single buffer, it has a name, a list of content 
and an optional separator. The list of content consists of other buffers and data.
The following methods are defined:

    append($data)   -- append data (including buffer or array) to this buffer
    clear()         -- clear this buffer
    replace($data)  -- replace this buffer with new data
    find($name)     -- find a named buffer within this buffer
    dump()          -- make visual output of this buffer (for debuging)
    __toString()    -- output buffer content as a string

The second class `buffer_manager` has methods for managing a stack of buffers.

    current()       -- return current buffer instance
    enter($name)    -- activate named buffer, if it does not exist: create and append to current buffer
    leave()         -- leave current buffer, activate previous active buffer
    out($data)      -- write data (string, buffer or array) to current active buffer
    undo()          -- remove current (newly created) buffer
    find($name)     -- find named buffer within root buffer

There are two demo files provided for each class, showing examples of how these
classes can be used. 

- buffer_demo_1: Creating and manipulating a table of integers, using different separators
- buffer_demo_2: Creating HTML output, using buffers as templates
- buffer_manager_demo_1: Using the buffer manager for better control of HTML output
- buffer_manager_demo_2: More advanced examples of HTML template usage

Note that the first example is very different from the others. The buffer class is 
very generic, it does not need to contain strings as values. However the __toString() 
method will automatically cast each content member to a string. This means you can 
use objects as data items, as long as they can be cast to strings, i.e. they have 
a __toString() method.