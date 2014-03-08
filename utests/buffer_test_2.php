<?php 
include '../TestUtils/TestUtils.php';
include 'buffer.class.php';

test(true,NULL,NULL,$GLOBALS);

testing('primitive CSV example');

function ColSet($sep="\t"){ return new buffer('ColSet',$sep); }
function RowSet($sep="\n"){ return new buffer('RowSet',$sep); }

function Compose($datarow,$idx,$extra) {
  list($R,$C) = $extra; # rows/cols
  $C->replace($datarow);
  $R->append(clone $C);  # append clone to rows
}

$data = array(
  array(1,2,2,1,3,4,5,3,2),
  array(2,1,2,1,2,3,4,3,3),
  array(1,3,1,2,3,2,2,2,1),
  array(3,2,2,3,1,3,2,1,1),
  array(2,2,3,3,4,1,4,1,2)
);

$R = RowSet(); # collection of rows

array_walk($data,'Compose',array($R,ColSet()));

echo $R."\n";

test('(string)$R->content[0]',implode("\t",$data[0]));
test('ord($R->separator)',ord("\n"));
test('ord($R->content[0]->separator)',ord("\t"));
test('$R->content[0]->content[0]',$data[0][0]);
test('$R->content[3]->content[2]',$data[3][2]);
test('$R->content[4]->content[8]',$data[4][8]);

?>