<?php 

if(isset($_GET['view']) || isset($_GET['src'])) {  # cancel automatic error report from TestUtils
  register_shutdown_function(function() {exit();});
  ob_start();
}  

include '../TestUtils/TestUtils.php';
include 'buffer.class.php';

test(true,NULL,NULL,$GLOBALS);

if(isset($_GET['view']) || isset($_GET['src'])) {
  ob_end_clean(); # started by TestUtils
  ob_end_clean(); # started above
  ob_start(); # fresh
}

testing('HTML example');

$doc = false;
test('$doc = new buffer("document")');
test('$doc->append(array(
    "<html><head>",
    new buffer("head"),
    "</head><body>",
    new buffer("body"),
    "</body></html>"))');

$head = false;
test('$head = $doc->find("head")');
test('$head->append(array("<title>",new buffer("title"),"</title>"))');
test('$head->append(array("<style type=\"text/css\">\n",new buffer("style","\n"),"\n</style>"))');
test('$head->append(array("<script type=\"text/javascript\"><!--\n",new buffer("script","\n"),"\n// --></script>"))');
test('$doc->find("script")->append("function f(){alert(\"Hello!\");}")');
test('$doc->find("title")->append("Hello world")');

function paragraphs($data,$attrs='') {
  if($attrs && $attrs[0] != ' ') $attrs = ' '.$attrs;
  $start = "<p$attrs>";
  $stop = '</p>';
  $b = new buffer('paragraphs',$stop.$start);
  $b->replace($data);
  return array($start,$b,$stop);
}

function menu($CSSclass='menu') {
  return array(
    '<div class="menu'.($CSSclass!='menu'?" $CSSclass":'').'"><ul><li>',
    new buffer($CSSclass,'</li><li>'),
    '</li></ul></div>');
}

$text = array(
  'Welcome to this little demo!',
  'This HTML page is constructed using multiple buffers. '.
    'Each buffer can be manipulated individually, so that you '.
    'can build the page in a non-sequential way. '.
    'This feature can be utilized in many ways, one of the '.
    'most usefull is the abillity to add CSS rules programatically.',
  'This example manipulates a menu by adding CSS rules and content '.
    'after the main content of the page has been output.',
  'See the PHP source code for more information.');
  
test('$doc->find("body")->append("<h1>Hello</h1>")');
test('$doc->find("body")->append(menu("MainMenu"))');
test('$doc->find("style")->append(".MainMenu {width:20em;float:right;margin-right:5em;}")');

test('$doc->find("body")->append(paragraphs($text))');
test('$doc->find("body")->append(paragraphs(array("Bye!"),"class=\'p1\'"))');
test('$doc->find("style")->append("p.p1 {color:red}")');

test('$doc->find("style")->append(".menu ul li {font-family:Verdana,sans-serif;font-size:150%;font-weight:bold;border-bottom:dotted 1px blue;}")');
test('$doc->find("style")->append(".menu ul li:first-child {border-top:dotted 1px blue;}")');
test('$doc->find("style")->append(".menu ul li a {color:blue;text-decoration:none;display:block;}")');
test('$doc->find("style")->append(".menu ul li a:hover {background-color:blue;color:yellow;text-decoration:underline;}")');

$MenuStyle = false;
test('$MenuStyle = new buffer("css",";")');
test('$MenuStyle->append(array(
  "list-style-type: none",
  "background-color:silver",
  "padding:1em",
  "margin:1em"))');
  
test('$doc->find("style")->append(".menu ul {")');
test('$doc->find("style")->append($MenuStyle)');
test('$doc->find("style")->append("}")');


function MenuItem($m,$URL,$title) {
  $m->append("<a href=\"$URL\">$title</a>");
}
$m = false;
test('$m = $doc->find("MainMenu")');
test('MenuItem($m,"?","Display tests")');
test('MenuItem($m,"?src","View source")');
test('MenuItem($m,"./","Page index")');
test('MenuItem($m,"/","Server index")');
test('MenuItem($m,"javascript:f();","JS test")');

if($_console) 
  echo "$doc\n\n* NOTE: you can run this test in the browser";
else {
  if(isset($_GET['view'])) {
    ob_end_clean();
    echo (string)$doc;
  }
  elseif(isset($_GET['src'])) {
    ob_end_clean();
    echo '<a href="?view">Back</a><br />';
    highlight_file(__FILE__);
  }
  else echo '<hr />'.htmlentities((string)$doc).'<hr />'.
    '<p><a href="?view">View as web page</a></p>';
}

?>