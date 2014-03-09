<?php 

include '../buffer.class.php';

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
echo "$R\n\n";

array_walk($R->content,
  create_function('$C','$C->separator = ";";'));
echo "$R\n\n";

$R->separator = '|';
echo "$R\n\n";

?>