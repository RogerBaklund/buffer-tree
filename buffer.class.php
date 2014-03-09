<?php 
/* buffer.class.php rb02052011 
   hierarchical output buffer

Version: 1.0
Author: Roger Baklund <roger@baklund.no>
License: LGPL

class buffer
  append($data) -- append data to this buffer (string or buffer)
  clear() -- clear this buffer
  replace($data) -- replace this buffer with new data (string or buffer)
  find($name) -- find a named buffer within this buffer
  dump() -- make visual output of this buffer (for debugging)
  __toString() -- output buffer content as a string

instantiation:

  $buffer = new buffer('buffername');
  $buffer = new buffer('buffername',"\n");  # insert linefeeds between items
    
*/

class buffer {
  
  function __construct($name,$separator="") {
    $this->name = $name;
    $this->separator = $separator;
    $this->clear();
  }
  
  function clear() {
    $this->content = array();
  }

  function append($data) {
    if(is_array($data))
      $this->content = array_merge($this->content,$data);
    else 
      $this->content[] = $data;
  }
    
  function replace($data) {
    if(is_array($data))
      $this->content = $data;
    else 
      $this->content = array($data);
  }
  
  function find($name) {
    if($this->name==$name) 
      return $this;
    foreach($this->content as $c) {
      if(is_a($c,__CLASS__)) {
        $f = $c->find($name);  # ! recursive
        if($f) return $f;
      }
    }
    return false;
  }
  
  function __toString() {
    return implode($this->separator,$this->content);
  }
  
  function dump($ind=0,$html=true,$echo=false,$width=40) {  # for debugging
  	$res = '';
    if(!$ind and $html) $res .= '<pre>';
    if(!$this->content) 
      $res .= str_repeat(' ',$ind).'@'.$this->name.' (empty)'."\n";
    foreach($this->content as $c) {
      if(is_a($c,'buffer'))
        $res .= $c->dump($ind+2,$html,false);
      else 
        $res .= str_repeat(' ',$ind).'@'.$this->name.':'.
             ($html ? htmlentities(substr($c,0,$width)) : substr($c,0,$width)).
             (strlen($c)>$width?'...':'')."\n";
    }
    if(!$ind and $html) $res .= '</pre>';
    if($echo) echo $res;
    else return $res;
  }
  
}
?>