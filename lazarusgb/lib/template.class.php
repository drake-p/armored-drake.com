<?php 
/*
 * ----------------------------------------------
 * Lazarus Guestbook
 * by Stewart Souter
 * URL: www.carbonize.co.uk 
 * Based on Advanced Guestbook 2.3.x (PHP/MySQL)
 * Copyright (c)2001 Chi Kien Uong
 * URL: http://www.proxy2.de
 * Last Modified: Tue, 9 May 2006 01:37:29 GMT
 * ----------------------------------------------
 */

class gb_template
{

   var $template = array();
   var $root_dir;
   var $LANG;
   var $plain_html = array();

   function gb_template($path='')
   {
      $this->root_dir = $path;
   }

   function set_rootdir($tpl_dir)
   {
      if (!is_dir($tpl_dir))
      {
         return false;
      }
      $this->root_dir = $tpl_dir;
      return true;
   }

   function set_lang($language = '')
   {
      if (!empty($language) && file_exists($this->root_dir.'/lang/'.$language.'.php'))
      {
         $this->language = $language;
      }
      else
      {
         $this->language = 'english';
      }
      return $this->language;
   }

   function get_content()
   {
      if (!isset($this->LANG))
      {
         include $this->root_dir.'/lang/'.$this->language.'.php';
         $this->LANG =& $LANG;
         $this->WEEKDAY =& $weekday;
         $this->MONTHS =& $months;
      }
      return $this->LANG;
   }

   function get_template($tpl)
   {
      if (!isset($this->template[$tpl]))
      {
         $filename = $this->root_dir.'/templates/classic/'.$tpl.'.tpl';
         if ((IS_MODULE || IS_INCLUDE) && (($tpl == 'header') || ($tpl == 'footer') || ($tpl == 'success_header')))
         {
            $this->template[$tpl] = '';
         }
         elseif (file_exists($filename))
         {
            if(function_exists('file_get_contents'))
            {
               $this->template[$tpl] = file_get_contents($filename);
            }
            elseif (filesize($filename) > 0)
            {
               $fd = fopen ($filename, "r");
               $this->template[$tpl] = fread ($fd, filesize($filename));
               fclose ($fd);
            }
            else
            {
               $this->template[$tpl] = '';
            }
            $this->template[$tpl] = str_replace('"', '\"', $this->template[$tpl]);
         }
         if ((IS_MODULE) || (IS_INCLUDE) || (isset($included)))
         { 
            $styles = array('class=\"font1\"' => 'style=\"font-family:$VARS[font_face];font-size:$VARS[tb_font_1];color:$VARS[text_color];\"',
            'class=\"font2\"' => 'style=\"font-family:$VARS[font_face];font-size:$VARS[tb_font_2];color:$VARS[text_color];\"',
            'class=\"font3\"' => 'style=\"font-family:Arial,Helvetica,sans-serif;font-size:7.5pt;color:$VARS[text_color];font-weight: bold;\"',
            'class=\"input\"' => 'style=\"font-family:$VARS[font_face];font-size:9pt\"',
            'class=\"select\"' => 'style=\"font-family:$VARS[font_face];font-size:9pt\"');
            $this->template[$tpl] = strtr($this->template[$tpl], $styles);
         }
         if ($tpl == 'footer')
         {
            $this->template['footer'] = '<div style=\"text-align:center;font-family:Arial,Helvetica,sans-serif;font-size:10px;color:#B6B6B6;font-weight:bold;\">Powered by Lazarus Guestbook from <a href=\"http://lazarus.carbonize.co.uk/\" target=\"_blank\" style=\"color:#CCC\">carbonize.co.uk</a></div>'."\n".$this->template['footer'];
         }
      }
      return $this->template[$tpl];
   }

}

?>