<?php

// You can customize the date and time format using PHP.  As they are set now,
// the date will appear in the form "Sunday, January 11, 2004" and the time in
// the form "1:04 pm".  Another common date format would be 01.11.04; to change
// it to this, replace 'l, F j, Y' with 'm.d.y'.  More info can be found at
// http://us2.php.net/manual/en/function.date.php.

$dateFormat = 'l, F j, Y';
$timeFormat = 'g:i a';


if (empty($_POST['message'])) {
  header('Location: '.$_POST['bookurl'].'?contents=blank');
}

else {
  $entryFile = 'entries.txt';
  $formatFile = 'format.php';

  $message = stripslashes($_POST['message']);

  $allowedTags = '<a><em><strong><b><i><img>';

  $stripAttrib = 'javascript:|onclick|ondblclick|onmousedown|onmouseup|onmouseover|onmousemove|onmouseout|onkeypress|onkeydown|onkeyup|style|class|id';

  function removeEvilTags($source)
  {
     global $allowedTags;
     $source = strip_tags($source, $allowedTags);
     return preg_replace('/<(.*?)>/ie', "'<'.removeEvilAttributes('\\1').'>'", $source);
  }
  function removeEvilAttributes($tagSource)
  {
    global $stripAttrib;
    return stripslashes(preg_replace("/$stripAttrib/i", 'forbidden', $tagSource));
  }

  function word_wrap($message)
  {
    $maxLength = 60;
    $cut = ' ';
    $result = '';
    $wordlength = 0;

    $length = strlen($message);

    $tag = FALSE;
    for ($i = 0; $i < $length; $i++)
    {
      $char = substr($message, $i, 1);
      if ($char == '<') { $tag = TRUE; }
      elseif ($char == '>') { $tag = FALSE; }
      elseif (!$tag && $char == ' ') { $wordlength = 0; }
      elseif (!$tag) { $wordlength++; }
      if (!$tag && !($wordlength%$maxLength)) { $char .= $cut; }
      $result .= $char;
    }
  return $result;
  }

  $message = word_wrap(removeEvilTags($message));
  $message = str_replace(array('&', "\r\n\r\n"), array('&amp;', '</p><p>'), $message);
  $message = str_replace(array('&amp;gt;', '&amp;lt;', "\r\n"), array('&gt;', '&lt;', '<br />'), $message);

  $signername = strip_tags(stripslashes($_POST['signername']));
  $email = urlencode(strip_tags(stripslashes($_POST['email'])));
  $url = urlencode(strip_tags(stripslashes($_POST['url'])));
  $url = str_replace(array('%2F', '%3A'), array('/', ':'), $url);

  $formatted = file_get_contents($formatFile);
  $variables = array("\n", '%%signername%%', '%%email%%', '%%url%%', '%%message%%', '%%date%%', '%%time%%');
  $inputs = array('', $signername, $email, $url, $message, date($dateFormat), date($timeFormat));

  $formatted = str_replace($variables, $inputs, $formatted);

  $oldEntries = fopen($entryFile, 'r');
  $content = fread($oldEntries, filesize($entryFile));
  fclose($oldEntries);

  $newContent = $formatted."\n".$content;

  $allEntries = fopen($entryFile, 'w');
  fwrite($allEntries, $newContent);
  fclose($allEntries);

  header('Location: '.$_POST['bookurl']);

}

?>