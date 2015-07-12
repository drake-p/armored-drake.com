<?php 
/*
 * ----------------------------------------------
 * Lazarus Guestbook
 * by Stewart Souter
 * URL: www.carbonize.co.uk 
 * Based on Advanced Guestbook 2.3.x (PHP/MySQL)
 * Copyright (c)2001 Chi Kien Uong
 * URL: http://www.proxy2.de
 * Last Modified: Fri, 7 July 2006 20:33:30 GMT
 * ----------------------------------------------
 */

class guestbook_vars extends gbook_sql
{

   var $VARS;
   var $LANG;
   var $table = array();
   var $SMILIES;
   var $template;

   function guestbook_vars($path = '')
   {
      global $GB_TBL;
      $this->table =& $GB_TBL;
      $this->gbook_sql();
      $this->connect();
      $this->template = new gb_template($path);
   }

   function getVars()
   {
      global $_COOKIE;
      $this->VARS = $this->fetch_array($this->query("SELECT * FROM ".$this->table['cfg']));
      $this->free_result($this->result);
      if (isset($_COOKIE['lang']) && !empty($_COOKIE['lang']))
      {
         $this->template->set_lang($_COOKIE['lang']);
      }
      else
      {
         $this->template->set_lang($this->VARS['lang']);
      }
      $this->LANG = $this->template->get_content();
      return $this->VARS;
   }

//
// This function converts a string into the relevant ascii HTML entities.
//

   function html_encode($string)
   {
      $ret_string = '';
      $len = strlen( $string );
      for ($x = 0; $x < $len; $x++) 
      {
         $ord = ord($string[$x]);
         $ret_string .= '&#'.$ord.';';
      } 
      return $ret_string;
   } 
   
//
// If they are previewing their entry we need to undo the HTMLspecialchars function
//   

   function undo_htmlspecialchars($string)
   {
      $html = array (
      '&amp;'  => '&',
      '&quot;' => '"',
      '&lt;'   => '<',
      '&gt;'   => '>'
      );
      $string = strtr($string, $html);
      return ($string);
   } 
   
//
// Lets turn our smiley codes into img tags
//      

   function emotion($message)
   {
      global $GB_PG;
      if (!isset($this->SMILIES))
      {
         $this->query("SELECT * FROM ".$this->table['smile']);
         while ($this->fetch_array($this->result))
         {
            $this->SMILIES[$this->record['s_code']] = '<img src="'.$GB_PG['base_url'].'/img/smilies/'.$this->record['s_filename'].'" width="'.$this->record['width'].'" height="'.$this->record['height'].'" alt="'.$this->record['s_emotion'].'" title="'.$this->record['s_emotion'].'" align="bottom">';
         }
      }
      if (isset($this->SMILIES))
      {
         for(reset($this->SMILIES); $key = key($this->SMILIES); next($this->SMILIES))
         {
            $message = str_replace("$key",$this->SMILIES[$key],$message);
         }
      }
      return $message;
   }

   function DateFormat($timestamp)
   {
      $timestamp += $this->VARS['offset']*3600;
      list($wday,$mday,$month,$year,$hour,$minutes,$hour12,$ampm) = split("( )",date("w j n Y H i h A",$timestamp));
      if ($this->VARS['tformat'] == 'AMPM')
      {
         $newtime = " $hour12:$minutes $ampm";
      }
      else
      {
         $newtime = " $hour:$minutes";
      }
      if ($this->VARS['dformat'] == 'ISO')
      {
         $newdate = " $year-$month-$mday";
      }
      elseif ($this->VARS['dformat'] == 'USx')
      {
         $newdate = " $month-$mday-$year";
      }
      elseif ($this->VARS['dformat'] == 'US')
      {
         $month -= 1;
         $newdate = $this->template->WEEKDAY[$wday].", ".$this->template->MONTHS[$month]." $mday, $year";
      }
      elseif ($this->VARS['dformat'] == 'Euro')
      {
         $month -= 1;
         $newdate = $this->template->WEEKDAY[$wday].", $mday ".$this->template->MONTHS[$month]." $year";
      }
      else
      {
         $newdate = "$mday.$month.$year";
      }
      return ($newdate=$newdate.$newtime);
   }
   
   function AGCode($string)
   {
      $string = ' '.$string;
      if ($this->VARS['allow_urlagcode'] == 1)
      {
         // This bit will automatically make any urls clickable
         // It is taken from phpBB. Thanks guys.
         $string = preg_replace("#(^|[\n ])([\w]+?://[\w\#$%&~/.\-;:=,?@\[\]+]*)#is", "$1[url]$2[/url]", $string);
         $string = preg_replace("#(^|[\n ])((www|ftp)\.[\w\#$%&~/.\-;:=,?@\[\]+]*)#is", "$1[url]$2[/url]", $string);      
      }
      // No point doing these checks if there is no AGcode
      if (!(strpos($string, '[') && strpos($string, ']')))
      {
         $string = substr($string, 1);
         return $string;
      }
      $string = preg_replace("/\[b\](.+?)\[\/b\]/is","<b>$1</b>",$string);
      $string = preg_replace("/\[i\](.+?)\[\/i\]/is","<i>$1</i>",$string);
      $string = preg_replace("/\[u\](.+?)\[\/u\]/is","<u>$1</u>",$string);
      if ($this->VARS['allow_emailagcode'] == 1)
      {
         $string = preg_replace("/\[email\]([a-z0-9\-_\.\+]+@[a-z0-9\-]+\.[a-z0-9\-\.]+?)\[\/email\]/ies", "'<a href=\"'.\$this->html_encode('mailto:$1').'\">'.\$this->html_encode('$1').'</a>'", $string);
      }
      if ($this->VARS['allow_imgagcode'] == 1)
      {
         while (preg_match('!\[img\](http://[^[]+)\[/img\]!i', $string, $img_url))
         {
            if (isset($img_url[1]))
            {
               $imagesize = @getimagesize($img_url[1]);
               if (is_array($imagesize))
               {
                  $imgsize = $this->check_image_size($imagesize[0], $imagesize[1]);
                  $string = preg_replace('!\[img\]'.$img_url[1].'\[/img\]!i',"<a href=\"$img_url[1]\" target=\"_blank\"><img src=\"$img_url[1]\" border=\"0\" alt=\"\" $imgsize[2]></a>",$string);
               }
            }
         }
      }
      if ($this->VARS['allow_urlagcode'] == 1)
      {
         $string = eregi_replace("\\[url\\]www\\.", "[url]http://www.", $string);
         $string = preg_replace("/\[url\]((http|https|ftp|mailto):\/\/([a-z0-9\.\-@:]+)[a-z0-9;\/\?:@=\&\$\-_\.\+!*'\(\),\#%~]*?)\[\/url\]/is","[<a href=\"$1\" target=\"_blank\" rel=\"nofollow\" title=\"$1\">$3</a>]",$string);
         $string = preg_replace("/\[url=((http|https|ftp|mailto):\/\/[a-z0-9;\/\?:@=\&\$\-_\.\+!*'\(\),~%#]+?)\](.+?)\[\/url\]/is","<a href=\"$1\" target=\"_blank\" title=\"$1\" rel=\"nofollow\">$3</a>",$string);
      }
      $string = substr($string, 1);
      return $string;
   }
   
//
// Generate a mildly encrypted timestamp
//

   function generate_timehash($timehash = '') 
   {
      global $GB_DB;
      $hashkey = md5($GB_DB['dbName'].$GB_DB['user']);
      if ($timehash != '')
      {
         return (($timehash / ord($hashkey[7])) + ord($hashkey[13])) - ord($hashkey[24]);
      }
      else
      {
         return (((time() + ord($hashkey[24])) - ord($hashkey[13])) * ord($hashkey[7]));
      }
   }   

//
// Code to allow certain HTML tags
//
    
   function allowed_html($string)
   {
      $allowed_tags = split(',', $this->VARS['allowed_tags']);
      for ($i = 0; $i <= (count($allowed_tags) - 1); $i++)
      {
         $allowed_tags[$i] = trim($allowed_tags[$i]);
         $string = eregi_replace("&lt;$allowed_tags[$i]&gt;([^\\[]*)&lt;/$allowed_tags[$i]&gt;","<$allowed_tags[$i]>\\1</$allowed_tags[$i]>", $string);
      }
      return $string;
   }

//
// Remove spaces from the ends of the strings and any double spaces from the middle
//

   function FormatString($strg)
   {
      $strg = trim($strg);
      $strg = ereg_replace("[ ]+", " ", $strg);
      return $strg;
   }

//
// Make sure there are now stupidly long or short words
//

   function CheckWordLength($strg)
   {
      $word_array = split ("[ |\n]",$strg);
      for ($i=0;$i<sizeof($word_array);$i++)
      {
         if ((ereg("^\\[(url|img|email)\\].+\\]",$word_array[$i])) || (eregi("^(http://)|(https://)|(ftp://)|(www\\.)", $word_array[$i])))
         {
            if (strlen($word_array[$i]) > 150)
            {
               return false;
            }
         }
         elseif (strlen($word_array[$i]) > $this->VARS['max_word_len'])
         {
            return false;
         }
      }
      return true;
   }

   function isBannedIp($ip)
   {
      $this->query("SELECT * from ".$this->table['ban']);
      if (!$this->result)
      {
         return false;
      }
      while ($row = $this->fetch_array($this->result))
      { 
         if ((strpos($ip, $row['ban_ip'])) === 0)
         {
            return true;
         }
      }
      return false;
   }

   function FloodCheck($ip)
   {
      $the_time = time()-$this->VARS['flood_timeout'];
      $this->query("DELETE FROM ".$this->table['ip']." WHERE (timestamp < $the_time)");
      $this->query("SELECT * FROM ".$this->table['ip']." WHERE (guest_ip = '$ip')");
      $this->fetch_array($this->result);
      return ($this->record) ? true : false;
   }

   function CensorBadWords($strg)
   {
      $replace = '#@*%!';
      $this->query("select * from ".$this->table['words']);
      if ($this->VARS['use_regex'] == 0)
      {
         while ($row = $this->fetch_array($this->result))
         {
            $row['word'] = preg_quote($row['word'], '/');
            $strg = preg_replace('^'.$row['word'].'^i', $replace, $strg);
         }
      }
      else
      {
         while ($row = $this->fetch_array($this->result))
         {
            $strg = preg_replace('^'.$row['word'].'^i', $replace, $strg);
         }
      }
      return $strg;
   }
   
   function BlockBadWords($strg)
   {
      $this->query("select * from ".$this->table['words']);
      if ($this->VARS['use_regex'] == 0)
      {
         while ($row = $this->fetch_array($this->result))
         {
            $row['word'] = preg_quote($row['word'], '/');
            if (preg_match('^'.$row['word'].'^i', $strg))
            {
               return true;
            }
         }
      }
      else
      {
         while ($row = $this->fetch_array($this->result))
         {
            if (preg_match('^'.$row['word'].'^i', $strg))
            {
               return true;
            }
         }
      }
      return false;
   }   

   function gb_error($ERROR)
   {
      global $GB_PG;
      $LANG =& $this->LANG;
      $VARS =& $this->VARS;
      $EMAILJS = '';
      $error_html = '';
      eval("\$error_html = \"".$this->template->get_template('header')."\";");
      eval("\$error_html .= \"".$this->template->get_template('error')."\";");
      eval("\$error_html .= \"".$this->template->get_template('footer')."\";");
      return $error_html;
   }
   
   function browser_detect($useragent)
   {
      $browsers = array('firefox', 'opera', 'konqueror', 'netscape', 'aol', 'camino', 'chimera', 'crazy', 'galeon', 'k-meleon', 'maxthon', 'safari', 'slimbrowser', 'amaya', 'avantbrowser', 'msie', 'gecko', 'mozilla');
      for ($i=0;$i<sizeof($browsers);$i++)
      {
         if (eregi($browsers[$i], $useragent))
         {
            $theirbrowser = $browsers[$i];
            $theirbrowser = ($theirbrowser == 'mozilla') ? 'ns' : $theirbrowser;
            $theirbrowser = ($theirbrowser == 'gecko') ? 'mozilla' : $theirbrowser;
            break;
         }
      }
      $theirbrowser = (!isset($theirbrowser)) ? 'question' : $theirbrowser;
      return $theirbrowser;
   }
   
   function check_emailaddress($email)
   {
      $addr_spec = '([^\\x00-\\x20\\x22\\x28\\x29\\x2c\\x2e\\x3a-\\x3c'.
			'\\x3e\\x40\\x5b-\\x5d\\x7f-\\xff]+|\\x22([^\\x0d'.
			'\\x22\\x5c\\x80-\\xff]|\\x5c[\\x00-\\x7f])*\\x22)'.
			'(\\x2e([^\\x00-\\x20\\x22\\x28\\x29\\x2c\\x2e'.
			'\\x3a-\\x3c\\x3e\\x40\\x5b-\\x5d\\x7f-\\xff]+|'.
			'\\x22([^\\x0d\\x22\\x5c\\x80-\\xff]|\\x5c\\x00'.
			'-\\x7f)*\\x22))*\\x40([^\\x00-\\x20\\x22\\x28'.
			'\\x29\\x2c\\x2e\\x3a-\\x3c\\x3e\\x40\\x5b-\\x5d'.
			'\\x7f-\\xff]+|\\x5b([^\\x0d\\x5b-\\x5d\\x80-\\xff'.
			']|\\x5c[\\x00-\\x7f])*\\x5d)(\\x2e([^\\x00-\\x20'.
			'\\x22\\x28\\x29\\x2c\\x2e\\x3a-\\x3c\\x3e\\x40'.
			'\\x5b-\\x5d\\x7f-\\xff]+|\\x5b([^\\x0d\\x5b-'.
			'\\x5d\\x80-\\xff]|\\x5c[\\x00-\\x7f])*\\x5d))*';
			
      if (preg_match("!^$addr_spec$!i", $email))
      {
	       return true;
      }
      else
      {
         return false;
      }
   }
   
   function captcha_test($thecode, $hash)
   {
      global $GB_DB;
      $realcode = md5($GB_DB['user']) . md5($hash);
      $realcode = md5($realcode);
      $realcode = $realcode[0] . $realcode[7] . $realcode[14] . $realcode[21] . $realcode[28]; 
      if (strtolower($realcode) == strtolower($thecode))
      {
         return true;
      }
      return false;
   }
   
   function check_image_size($img_width, $img_height)
   {
      $max_width = $this->VARS['agcode_img_width'];
      $max_height = $this->VARS['agcode_img_height'];
      if ($img_width>$max_width && ($max_width > 0)) {
         $tag_height = ($max_width/$img_width)*$img_height;
         $tag_width = $max_width;
         if ($tag_height>$max_height) {
             $tag_width = ($max_height/$tag_height)*$tag_width;
             $tag_height = $max_height;
         }
      } elseif ($img_height>$max_height && ($max_height > 0)) {
         $tag_width = ($max_height/$img_height)*$img_width;
         $tag_height = $max_height;
         if ($tag_width>$max_width) {
            $tag_height = ($max_width/$tag_width)*$tag_height;
            $tag_width = $this->max_width;
         }
      } else {
         $tag_width = $img_width;
         $tag_height = $img_height;
      }
      $tag_width = round($tag_width);
      $tag_height = round($tag_height);
      return array(
         "$tag_width",
         "$tag_height",
         "width=\"$tag_width\" height=\"$tag_height\""
      );   
   }   
   
}
?>