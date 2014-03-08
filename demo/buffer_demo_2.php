<?php 

include 'buffer.class.php';

$doc = new buffer("document");
$doc->append(array(
    "<html><head>",
    new buffer("head"),
    "</head><body>",
    new buffer("body"),
    "</body></html>"));
$head = $doc->find("head");
$head->append(array("<title>",new buffer("title"),"</title>"));
$head->append(array("<style type=\"text/css\">\n",new buffer("style","\n"),"\n</style>"));
$head->append(array("<script type=\"text/javascript\"><!--\n",new buffer("script","\n"),"\n// --></script>"));
$doc->find("title")->append("Hello world");

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
    'This feature can be utilized in many ways, you can for example '.
    'add CSS rules programatically and build menues and such without '.
    'regard to "where" in the output flow you are in the program.',
  'This example manipulates a menu by adding CSS rules and content '.
    'after the main content of the page has been output.',
  'See the PHP source code for more information.');
  
$doc->find("body")->append("<h1>Hello</h1>");
$doc->find("body")->append(menu("MainMenu"));
$doc->find("style")->append(".MainMenu {width:20em;float:right;margin-right:5em;}");

$doc->find("body")->append(paragraphs($text));
$doc->find("body")->append(paragraphs(array("Bye!"),'class="p1"'));
$doc->find("style")->append("p.p1 {color:red}");

$doc->find("style")->append(".menu ul li {font-family:Verdana,sans-serif;font-size:150%;font-weight:bold;border-bottom:dotted 1px blue;}");
$doc->find("style")->append(".menu ul li:first-child {border-top:dotted 1px blue;}");
$doc->find("style")->append(".menu ul li a {color:blue;text-decoration:none;display:block;}");
$doc->find("style")->append(".menu ul li a:hover {background-color:blue;color:yellow;text-decoration:underline;}");

# CSS ruleset
$MenuStyle = new buffer("MenuStyle",";");
$MenuStyle->append(array("list-style-type:none","margin:1em"));

$doc->find("style")->append(".menu ul {");
$doc->find("style")->append($MenuStyle);
$doc->find("style")->append("}");

# not too late, can still append style rules:
$MenuStyle->append("background-color:silver");
$doc->find("MenuStyle")->append("padding:1em");

function MenuItem($m,$URL,$title) {
  $m->append("<a href=\"$URL\">$title</a>");
}

$m = $doc->find("MainMenu");
MenuItem($m,"?src","View source");
MenuItem($m,"?dump","Show tree");
MenuItem($m,"./","Page index");
MenuItem($m,"/","Server index");

# Add some JS 
$doc->find("script")->append("function f(){alert(\"Hello!\");}");
MenuItem($m,"javascript:f();","JS test");

if(isset($_GET['src'])) {
  echo '<a href="?">Back</a><br />';
  highlight_file(__FILE__);  
} elseif(isset($_GET['dump'])) {
  echo '<a href="?">Back</a><br />';
  $doc->dump(0,true);  
} else echo (string) $doc;

?>