<?php  

include 'buffer_manager.class.php';

class HTML_Tags extends buffer_manager {
  # a typical usage pattern:
  function WriteTo($name,$data) {
    $this->enter($name);
    $this->out($data);
    $this->leave();
  }
  # generic named HTML tag, content is a separate buffer
  function NamedTag($name,$tag=false,$attrs='',$data=NULL) {
    if(!$tag) $tag = $name;
    if($attrs) $attrs = " $attrs";
    $this->out("<$tag$attrs>");
    $this->enter($name);
    if(!is_null($data))
      $this->out($data);
    $this->leave();
    $this->out("</$tag>");
  }
  # a named image template, src attribute can be written later
  function NamedImage($name,$src='',$alt='',$extra='') {
    $this->out('<img src="');
    $this->enter($name);
    if($src) $this->out($src);
    $this->leave();
    if($extra) $extra = " $extra";
    $this->out('" alt="'.$alt.'"'.$extra.' />');
  }

}

$bm = new HTML_Tags('document');

$bm->out('<html><head>');

$bm->enter('head');                 # create buffer 'head'
$bm->NamedTag('title');
$bm->NamedTag('style','','type="text/css"');
$bm->leave();                       # leave 'head'

$bm->out('</head><body>');

$bm->enter('body');                 # create buffer 'body'

$NameOfPage = 'Hello world';
$bm->out("<h1>$NameOfPage</h1>");   # writing in 'body'...
$bm->WriteTo('title',$NameOfPage);  # ...and to the title in head

$bm->NamedImage('ProfilePic',
  'http://files.phpclasses.org/picture/user/',
  'PHP Classes profile picture',
  'width="90" height="117" style="float:left;padding:.5em"');

$bm->NamedTag('Source','div','class="Source"','<p>Source:</p>');
$bm->WriteTo('style', 'div.Source {
  width:50%;
  border:solid 1px black;
  float:right;
  padding:.5em;
}');

$bm->out('<p>This is a small example of how you can use the '.
         'buffer manager to navigate in a tree of buffers.</p>');
$bm->out('<p>Check the source to the right.</p>');
$bm->out('<p>The HTML_Tags class extends the buffer_manager class, '.
         'adding a few methods relevant to HTML. ');
$bm->out('<p>Note that these are just examples of how to '.
         'use buffers as templates for HTML, use your imagination!</p>');

$bm->WriteTo('style', 'p {font-family:Verdana, sans-serif;}');

$bm->WriteTo('Source',highlight_file(__FILE__,true));

$bm->leave();                       # leave 'body'

$bm->out('</body></html>');

$bm->WriteTo('ProfilePic','930196.jpg');

echo (string) $bm->current();  

?>