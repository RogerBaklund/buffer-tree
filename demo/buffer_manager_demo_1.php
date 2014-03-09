<?php
/*

  RUN IN BROWSER!

  current() -- return current buffer instance
  enter($name) -- activate named buffer, if it does not exist:
                  create and append to current buffer
  leave() -- leave current buffer, activate previous active buffer
  out($data) -- write data (string or buffer) to current active buffer
  find($name) -- find named buffer within root buffer
  undo() -- remove last created buffer

note: the find() method is mostly redundant, you can use enter() and/or current() 
      to fetch any buffer instance, or you can fetch them from $bm->buffers[$name]
      However, it is possible to output a buffer with the out() method, without
      registering it in the buffer_manager using enter(). In those cases, find()
      can be used to find the buffer.

TODO: make tests for undo()

*/

include '../buffer_manager.class.php';

$bm = new buffer_manager('document');

$bm->out('<html><head>');
$bm->enter('head');                 # create buffer 'head'
$bm->out('<title>');
$bm->enter('title');                # create buffer 'title' as a sub-buffer within the 'head' buffer
$bm->out('Page title');
$bm->leave();                       # leave 'title'
$bm->out('</title>');
$bm->out('<style>');
$bm->enter('style');                # create buffer 'style'
$bm->leave();                       # leave 'style'
$bm->out('</style>');
$bm->leave();                       # leave 'head'
$bm->out('</head><body>');
$bm->enter('body');                 # create buffer 'body'
$bm->out('<h1>Hello world</h1>');
$bm->leave();                       # leave 'body'
$bm->out('</body></html>');

$doc = $bm->current();  # fetch current buffer
echo ((($doc===$bm->buffers['document']) and 
      ($doc===$bm->find('document'))) ? 
  'document buffer found' : 
  'document buffer NOT found!').'<br />';

$doc->dump(); 

$body = $bm->enter('body');          # activate buffer 'body', fetch buffer in variable
$bm->out('<p>Body text</p>');
echo ((($body===$bm->buffers['body']) and 
       ($body===$bm->current()) and 
       ($body===$bm->find('body'))) ? 
  'body buffer found' : 
  'body buffer NOT found').'<br />';
  
$bm->enter('style');                 # activate buffer 'style'
$bm->out('p {color:green}');
$bm->leave();                        # leave 'style', activate 'body' (previous active buffer)

$bm->out(array(
  '<p>','More body text','</p>'));   # write array

$bm->leave();                        # leave 'body'

$doc->dump(); 

echo htmlspecialchars($doc).'<br />';

$bm->enter('title');
$title = $bm->current();             # fetch current buffer instance ('title')
$title->replace('New page title');   # replace() is defined in buffer.class.php

echo ((($title===$bm->find('title')) and 
       ($title===$bm->buffers['title'])) ?
    'title buffer found' :
    'title buffer NOT found').'<br />';


echo htmlspecialchars($doc).'<br />';

$style = $bm->find('style');         # find named buffer
$style->append('h1 {color:red}');    # append() is defined in buffer.class.php

echo htmlspecialchars($doc).'<br />';

?>