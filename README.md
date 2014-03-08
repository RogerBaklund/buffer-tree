Buffer tree
-----------

Two PHP classes for creating and manipulating a hierarchy of output buffers.

The class `buffer` represents a single buffer, it has a name, a list of content 
and an optional separator. The list of content consists of other buffers and data.
The following methods are defined:

    append($data) -- append data to this buffer (string or buffer)
    clear() -- clear this buffer
    replace($data) -- replace this buffer with new data (string or buffer)
    find($name) -- find a named buffer within this buffer
    dump() -- make visual output of this buffer (for debugging)
    __toString() -- output buffer content as a string

The second class `buffer_manager` has methods for managing a stack of buffers.

    current() -- return current buffer instance
    enter($name) -- activate named buffer, if it does not exist:
                  create and append to current buffer
    leave() -- leave current buffer, activate previous active buffer
    out($data) -- write data (string or buffer) to current active buffer
    undo() -- remove current (newly created) buffer
    find($name) -- find named buffer within root buffer

There are two demo files provided for each class, showing examples of how these
classes can be used. 

- buffer_demo_1: Creating and manipulating a data table, using different separators
- buffer_demo_2: Creating and manipulating HTML strings, buffers as templates
- buffer_manager_demo_1: Using the buffer manager for HTML output
- buffer_manager_demo_2: More advanced examples of HTML template usage