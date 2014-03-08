<?php 
include '../TestUtils/TestUtils.php';
include 'buffer.class.php';

test(true,NULL,NULL,$GLOBALS);

testing('buffer class');

$b = false;
test('$b = new buffer("test")');
test('$b->append("A")');
test('"$b"','A');

$b2 = false;
test('$b2 = new buffer("inner")');
test('$b2->append("B")');
test('"$b2"','B');
test('$b->append($b2)');
test('"$b"','AB');

testing('buffer::find method');
test('$b->find("inner")',$b2);
test('(string)$b->find("inner")','B');
test('"".$b->find("inner")','B');


?>