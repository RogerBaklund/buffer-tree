Buffer tree
-----------

Two PHP classes for creating and manipulating a hieararchy of output buffers.

The class `buffer` represents a single buffer, it has a name, a list of content 
and an optional separator. The list of content consists of other buffers and data.

The second class `buffer_manager` has methods for managing a stack of buffers.