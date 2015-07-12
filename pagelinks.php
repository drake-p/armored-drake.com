<?php

$entryFile = 'entries.txt';

$lines = file($entryFile);
$totalEntries = count($lines);

if(!$_SERVER['QUERY_STRING']) { $n = 1; }
else { $n = $_GET['n']; }

$d = $totalEntries / 10;
$f = floor($d);
if ($d == $f) { $numPages = $f; }
else { $numPages = $f + 1; }

if ($n > 1) { echo '<a href="'.$PHP_SELF.'?n='.($n - 1).'">&#171;</a> '; }
for ($i = 1; $i <= $numPages; $i++) {
  if ($i == $n) { echo $i.' '; }
  else { echo '<a href="'.$PHP_SELF.'?n='.$i.'">'.$i.'</a> '; }
}
if ($n < $numPages) { echo '<a href="'.$PHP_SELF.'?n='.($n + 1).'">&#187;</a> '; }

?>