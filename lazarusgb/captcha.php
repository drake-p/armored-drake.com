<?php 
/////////////////////////////////////////////
//
// CAPTCHA generator
// Last modified 08/05/2006 21:03:05
//
/////////////////////////////////////////////

// Start a buffer
ob_start();

// Lets grab the database username to use as a key
$include_path = dirname(__FILE__);
require_once $include_path.'/admin/config.inc.php';

$thekey = (!empty($GB_DB['user'])) ? $GB_DB['user'] : 'Lazarus';

// create an image with width 120px, height 20px
$image = imagecreate(200, 40);

// Assign a background colour
$background = imagecolorallocate($image, 255,255,255);

// Have we got a timehash to work with?

$hash = (!empty($_GET['hash'])) ? $_GET['hash'] : '';

if (!empty($hash) && is_numeric($hash))
{

   // Set the fonts to use
   $font[] = imageloadfont($include_path.'/fonts/atommicclock.gdf');
   $font[] = imageloadfont($include_path.'/fonts/anonymous.gdf');
   $font[] = imageloadfont($include_path.'/fonts/sfautomation.gdf');

//
// Background noise      
//
   
   // Random characters
   if (!isset($_GET['noise']))
   {
      $imagetext = strtoupper(MD5(time()));
      $horizpos = mt_rand(0, 10);   
 
      for ($i=0;$i<20;$i++)
      {
         $fontface = mt_rand(0,2);
         if ($fontface == 0)
         {
            $vertpos = mt_rand(0, 10);
         }
         elseif ($fontface == 1)
         {
            $vertpos = (mt_rand(0, 10)) - 7;
         }
         else
         {
            $vertpos = (mt_rand(0, 20)) - 7;
         }
         if (isset($_GET['grey']))
         {
            $rcolor = mt_rand(190, 240);
            $randcolour = imagecolorallocate($image, $rcolor, $rcolor, $rcolor);
         }
         else
         {
            $randcolour = imagecolorallocate($image, mt_rand(190, 240), mt_rand(190, 240), mt_rand(190, 240));
         }
         imagestring($image, $font[$fontface], $horizpos, $vertpos, $imagetext[$i], $randcolour);
         if ($horizpos >= 220) 
         { 
            $horizpos = 0; 
         }
         $horizpos += rand(20, 30);
      }
   }
   
   // Horizontal lines
   
   if (!isset($_GET['grid']))
   {
      $ypos = mt_rand(1, 5);
      while ($ypos <= 39) 
      {
         if (isset($_GET['grey']))
         {
            $rcolor = mt_rand(100, 240);
            $randcolour = imagecolorallocate($image, $rcolor, $rcolor, $rcolor);
         }
         else
         {
      	  $randcolour = imagecolorallocate($image, rand(100, 240), rand(100, 240), rand(100, 240));
      	}
         imageline($image, 0, $ypos, 240, $ypos, $randcolour);
         $ypos += mt_rand(2, 10);
      }
   
      // Vertical lines
   
      $xpos = mt_rand(1, 5);
      while ($xpos <= 249) 
      {
      	if (isset($_GET['grey']))
         {
            $rcolor = mt_rand(100, 240);
            $randcolour = imagecolorallocate($image, $rcolor, $rcolor, $rcolor);
         }
         else
         {
      	  $randcolour = imagecolorallocate($image, rand(100, 240), rand(100, 240), rand(100, 240));
      	}
         imageline($image, $xpos, 0, $xpos, 40,  $randcolour);
         $xpos += mt_rand(2, 10);
      }
   }
   
   // The real code
   
   $horizpos = mt_rand(10, 30);
   $realcode = '';
   $realcode = md5($thekey) . md5($hash);
   $realcode = strtoupper(md5($realcode));
   
   for ($i=0;$i<=30;$i+=7)
   {
      $fontface = mt_rand(0,2);
      if ($fontface == 0)
      {
         $vertpos = mt_rand(0, 10);
      }
      elseif ($fontface == 1)
      {
         $vertpos = (mt_rand(0, 10)) - 7;
      }
      else
      {
         $vertpos = (mt_rand(0, 20)) - 7;
      }
      $red = mt_rand(40, 140);
      if (isset($_GET['greyt']))
      {
         $green = $red;
         $blue = $red;
      }
      else
      {
         $green = mt_rand(40, 140);
         $blue = mt_rand(40, 140);
      }
      $maincolor = imagecolorallocate($image, $red, $green, $blue);
      $hilight = imagecolorallocate($image, $red + 60, $green + 60, $blue + 60);
      $shadow = imagecolorallocate($image, $red - 40, $green - 40, $blue - 40);
      imagestring($image, $font[$fontface], $horizpos + 1, $vertpos + 1, $realcode[$i], $shadow);
      imagestring($image, $font[$fontface], $horizpos - 1, $vertpos - 1, $realcode[$i], $hilight);
      imagestring($image, $font[$fontface], $horizpos, $vertpos, $realcode[$i], $maincolor);
      $horizpos += mt_rand(25, 40);
   } 
}
else // We've not got a hash so report an error
{
   $red = imagecolorallocate($image, 255, 0, 0);
   imagestring($image, 5, 2, 3, 'ERROR! ERROR!', $red);
}   

// Dump the image to the buffer
imagepng($image);

// Do our headers
$date = date("D, d M Y H:i:s");
header("Cache-Control: no-cache");
header("Pragma: no-cache");
header('Content-type: image/png');
header('Content-Length: ' . ob_get_length());
header("Expires: Sat, 1 Jan 2000 00:00:00 GMT");
header("Last-Modified: $date GMT");

// Dump the buffer
ob_end_flush();

// Tidy up
imagedestroy($image);

?>