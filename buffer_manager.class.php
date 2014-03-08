<?php 
/* buffer_manager.class.php rb02052011 
   manager for hierarchical output buffers 

Version: 1.0
Author: Roger Baklund <roger@baklund.no>
License: LGPL

class buffer_manager
  current() -- return current buffer instance
  enter($name) -- activate named buffer, if it does not exist:
                  create and append to current buffer
  leave() -- leave current buffer, activate previous active buffer
  out($data) -- write data (string or buffer) to current active buffer
  undo() -- remove current (newly created) buffer
  find($name) -- find named buffer within root buffer
  
instantiation:

$bm = new buffer_manager();
$bm = new buffer_manager('document');  # override default buffer name '_default'

note: undo() can only be used with the most recently added buffer, instead of calling leave()

*/

require_once('buffer.class.php');

class buffer_manager {
  
  function __construct($name='_default') {
    $this->buffers = array($name=>new buffer($name));
    $this->buffer_stack = array($name);
  }
  
  function find($name) {
    $b = $this->buffers[$this->buffer_stack[0]];
    return $b->find($name);
  }
  
  function current() {
    $bs = $this->buffer_stack;
    return $this->buffers[$bs[count($bs)-1]];
  }
  
  function enter($name) {
    if(!isset($this->buffers[$name])) {
      $b = new buffer($name);
      $this->buffers[$name] = $b;
      $this->out($b);
    }
    $c = $this->current();
    # if($c->name != $name)    
    # !! Allow entering the same buffer twice, 
    #    so that enter/leave pairs allways match,
    #    to avoid confusing errors when "entering" the current buffer 
    $this->buffer_stack[] = $name;
    return $this->current();
  }
  
  function leave() {
    if(count($this->buffer_stack)>1)
      array_pop($this->buffer_stack);
  }
  
  function undo() {
    $u = $this->current(); # buffer to remove
    $this->leave(); # remove from stack
    $c = $this->current();
    if($u===$c->content[count($c->content)-1]) {
      unset($this->buffers[$u->name]); # remove from buffer array
      unset($c->content[count($c->content)-1]); # remove from current buffer
    }
    else 
      throw new Exception(__CLASS__.': can not undo, current buffer is not last appended');
  }
  
  function out($data) {
    # $data is string, buffer or array containing both/either
    $buffer = $this->current();
    $buffer->append($data);
  }
  
}
?>