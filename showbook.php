<?php

$entryFile = 'entries.txt';

$lines = file($entryFile);
$totalEntries = count($lines);

if(!$_SERVER['QUERY_STRING']) { $n = 1; }
else { $n = $_GET['n']; }
$min = 10 * ($n - 1);
$max = 10 * $n - 1;

foreach($lines as $lineNum => $line)
{
  if ($lineNum < $min) {}
  elseif ($lineNum > $max) { break; }
  else {
    $entryNum = $totalEntries - $lineNum;
    echo $line;
  }
}

?>