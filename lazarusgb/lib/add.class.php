<?php 
/*
 * ----------------------------------------------
 * Lazarus Guestbook
 * by Stewart Souter
 * URL: www.carbonize.co.uk 
 * Based on Advanced Guestbook 2.3.x (PHP/MySQL)
 * Copyright (c)2001 Chi Kien Uong
 * URL: http://www.proxy2.de
 * Last Modified: Mon, 24 July 2006 19:29:48 GMT
 * ----------------------------------------------
 */

class addentry 
{

   var $db;
   var $ip;
   var $include_path;
   var $template;
   var $name = '';
   var $email = '';
   var $url = '';
   var $comment = '';
   var $location = '';
   var $icq = '';
   var $aim = '';
   var $msn = '';
   var $yahoo = '';
   var $skype = '';
   var $gender = '';
   var $bottest = '';
   var $userfile = '';
   var $timehash = '';
   var $user_img = '';
   var $preview = '';
   var $private = '';
   var $image_file = '';
   var $image_tag = '';
   var $table = array();

   function addentry($path = '')
   {
      global $GB_TBL, $_SERVER, $GB_PG;
      if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']) && preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/',$_SERVER['HTTP_X_FORWARDED_FOR']) && !preg_match('/^(192\.168\.)|(127\.0\.0\.1)|(10\.)|(169\.((1[2-9])|(2[0-9])|(30)|(31))\.)/', $_SERVER['HTTP_X_FORWARDED_FOR']))
      {
         $this->ip = addslashes($_SERVER['HTTP_X_FORWARDED_FOR']);
      }
      else
      {
         $this->ip = addslashes($_SERVER['REMOTE_ADDR']);
      }
      $this->db = new guestbook_vars($path);
      $this->db->getVars();
      $this->template = &$this->db->template;
      $this->include_path = $path;
      $this->table = &$GB_TBL;
      $GB_PG['base_url'] = $this->db->VARS['base_url'];
   }
   
//
// Lets check the tmp folder for any images older than 30 minutes and delete them
//

   function clear_tmpfiles($cachetime = 1800)
   {
      global $GB_TMP;
      $delfiles = 0;
      $filelist = '';
      if (is_dir($this->include_path.'/'.$GB_TMP))
      {
         if (is_writable($this->include_path.'/'.$GB_TMP))
         {
            chdir($this->include_path.'/'.$GB_TMP);
            $hnd = opendir(".");
            while (($file = readdir($hnd)))
            {
               if(is_file($file))
               {
                  $filelist[] = $file;
               }
            }
            closedir($hnd);
         }
      }
      if (is_array($filelist))
      {
         while (list ($key, $file) = each ($filelist))
         {
            $tmpfile = explode(".",$file);
            if (preg_match('/jpg|gif|png|swf|bmp/i', $tmpfile[1]))
            {
               $tmpfile[0] = preg_replace('/img-/', '', $tmpfile[0]);
               if ($tmpfile[0] < (time()-$cachetime))
               {
                  if (unlink($file))
                  {
                     $delfiles ++;
                  }
               }
            }
         }
      }
      return $delfiles;
   }

    
//
// Check the submitted entry to make sure it's all nice and fits in with our rules
//

   function check_entry($type = '')
   {
      global $GB_UPLOAD, $GB_TMP, $GB_PG;
      $this->db->VARS['max_img_size'] = $this->db->VARS['max_img_size']*1024;
      $the_time = time();
      if ($this->db->VARS['banned_ip'] == 1)
      {
         if ($this->db->isBannedIp($this->ip))
         {
            return $this->db->gb_error($this->db->LANG['ErrorPost9']);
         }
      }
      if ($this->db->VARS['flood_check'] == 1)
      {
         if ($this->db->FloodCheck($this->ip))
         {
            return $this->db->gb_error($this->db->LANG['ErrorPost8']);
         }
      }
      if (is_array($this->userfile) && $this->userfile['userfile']['tmp_name'] != 'none' && (strpos($this->userfile['userfile']['type'], 'image') === 0))
      {
         $extension = array('1' => 'gif','2' => 'jpg','3' => 'png', '6' => 'bmp');
         if ($this->userfile['userfile']['size'] > $this->db->VARS['max_img_size'])
         {
            return $this->db->gb_error($this->db->LANG['ErrorPost6']);
         }
         else
         {
            move_uploaded_file($this->userfile['userfile']['tmp_name'], $this->include_path.'/'.$GB_TMP.'/img-'.$the_time.'.tmp');
            $size = GetImageSize("$this->include_path/$GB_TMP/img-$the_time.tmp");
            if ((($size[2] > 0) && ($size[2] < 4)) || ($size[2] == 6))
            {
               $this->image_file = "img-$the_time.".$extension[$size[2]];
               $img = new gb_image();
               $img->set_destdir("$this->include_path/$GB_UPLOAD");
               $img->set_border_size($this->db->VARS['img_width'], $this->db->VARS['img_height']);
               if ($type == 'preview')
               {
                  rename("$this->include_path/$GB_TMP/img-$the_time.tmp", "$this->include_path/$GB_TMP/$this->image_file");
                  chmod($this->include_path.'/'.$GB_TMP.'/'.$this->image_file, 0755);
                  $new_img_size = $img->get_img_size_format($size[0], $size[1]);
                  $GB_UPLOAD = $GB_TMP;
                  $row['p_filename'] = $this->image_file;
                  $row['width'] = $size[0];
                  $row['height'] = $size[1];
                  eval("\$this->tmp_image = \"".$this->template->get_template('user_pic')."\";");
               }
               else
               {
                  rename("$this->include_path/$GB_TMP/img-$the_time.tmp", "$this->include_path/$GB_UPLOAD/$this->image_file");
                  chmod($this->include_path.'/'.$GB_UPLOAD.'/'.$this->image_file, 0755);
                  if ($this->db->VARS['thumbnail'] == 1)
                  {
                     $min_size = 1024*$this->db->VARS['thumb_min_fsize'];
                     $img->set_min_filesize($min_size);
                     $img->set_prefix("t_");
                     $img->create_thumbnail("$this->include_path/$GB_UPLOAD/$this->image_file","$this->image_file");
                  }
               }
            }
            else
            {
               return $this->db->gb_error($this->db->LANG['ErrorPost7']);
            }
         }
      }
      if (!empty($this->user_img))
      {
         $this->image_file = trim($this->user_img);
      }
      $this->name = $this->db->FormatString($this->name);
      $this->email = $this->db->FormatString($this->email);
      $this->location = $this->db->FormatString($this->location);
      $this->comment = $this->db->FormatString($this->comment);
      $this->icq = intval($this->db->FormatString($this->icq));
      $this->aim = htmlspecialchars($this->db->FormatString($this->aim));
      $this->msn = htmlspecialchars($this->db->FormatString($this->msn));
      $this->yahoo = htmlspecialchars($this->db->FormatString($this->yahoo));
      $this->skype = htmlspecialchars($this->db->FormatString($this->skype));
      //if (!eregi("^[a-z0-9._-]+@+[a-z0-9._-]+\.[a-z]{2,6}$", $this->email) )
      if (!$this->db->check_emailaddress($this->email))
      {
         $this->email = '';
      }
      if (!get_magic_quotes_gpc())
      {
         $this->bottest = addslashes($this->bottest);
         $this->db->VARS['bottestanswer'] = addslashes($this->db->VARS['bottestanswer']);
         $this->timehash = addslashes($this->timehash);
      }
      if (($this->icq < 1000) || ($this->icq >999999999))
      {
         $this->icq=0;
      }
      if ($this->name == '')
      {
         return $this->db->gb_error($this->db->LANG['ErrorPost1']);
      }
      if ($this->timehash == '')
      {
         return $this->db->gb_error($this->db->LANG['ErrorPost4']);
      }
      elseif (strlen($this->comment)<$this->db->VARS['min_text'] || strlen($this->comment)>$this->db->VARS['max_text'])
      {
         return $this->db->gb_error($this->db->LANG['ErrorPost3']);
      }
      elseif ((($this->db->VARS['require_email'] == 1) || ($this->db->VARS['require_email'] == 4)) && $this->email == '')
      {
         return $this->db->gb_error($this->db->LANG['ErrorPost12']);
      }
      elseif ((($this->db->VARS['antibottest'] == 1) || ($this->db->VARS['antibottest'] == 2)) && ($this->bottest == ''))
      {
         return $this->db->gb_error($this->db->LANG['ErrorPost13']);
      }
      elseif (($this->db->VARS['antibottest'] == 1) && (strtolower($this->bottest) != strtolower($this->db->VARS['bottestanswer'])))
      {
         return $this->db->gb_error($this->db->LANG['ErrorPost14']);
      }
      elseif (($this->db->VARS['antibottest'] == 2) && (!$this->db->captcha_test($this->bottest, $this->timehash)))
      {
         return $this->db->gb_error($this->db->LANG['ErrorPost14']);
      }
      else
      {
         $this->url = trim($this->url);
         if (substr($this->url, 0, 4) == 'www.')
         {
            $this->url = 'http://'.$this->url;
         }
         if (!preg_match('@^http://[_a-z0-9-]+\\.[_a-z0-9-]+@i', $this->url))
         {
            $this->url = '';
         }
         if (htmlspecialchars($this->url) != $this->url)
         {
            $this->url = '';
         }
      }
      if ($this->db->VARS['censor'] == 1)
      {
         $this->name = $this->db->CensorBadWords($this->name);
         $this->email = $this->db->CensorBadWords($this->email);
         $this->location = $this->db->CensorBadWords($this->location);
         $this->comment = $this->db->CensorBadWords($this->comment);
         $this->url = $this->db->CensorBadWords($this->url);
      }
      if ($this->db->VARS['censor'] == 2)
      {
         if ($this->db->BlockBadWords($this->name) || $this->db->BlockBadWords($this->email) || $this->db->BlockBadWords($this->location) || $this->db->BlockBadWords($this->comment) || $this->db->BlockBadWords($this->url))
         {
            return $this->db->gb_error($this->db->LANG['ErrorPost10']);
         }     
      }
      if (!$this->db->CheckWordLength($this->name) || !$this->db->CheckWordLength($this->location))
      {
         return $this->db->gb_error($this->db->LANG['ErrorPost4']);
      }
      if (!$this->db->CheckWordLength($this->comment))
      {
         return $this->db->gb_error($this->db->LANG['ErrorPost10']);
      }
      $decodedhash = $this->db->generate_timehash($this->timehash);
      if (($the_time < ($decodedhash + $this->db->VARS['post_time_min'])))
      {
         return $this->db->gb_error($this->db->LANG['ErrorPost15']);
      }
      if (($the_time > ($decodedhash + $this->db->VARS['post_time_max'])) && ($this->db->VARS['post_time_max'] != 0))
      {
         return $this->db->gb_error($this->db->LANG['ErrorPost16']);
      }
      return 1;
   }
    
//
// Format our entry for MySQL insertion then insert it
//    

   function add_guest()
   {
      global $GB_TMP, $GB_UPLOAD, $GB_PG;
      if (($this->preview == 1) && ($this->user_img))
      {
         $img = new gb_image();
         $img->set_destdir("$this->include_path/$GB_UPLOAD");
         $img->set_border_size($this->db->VARS['img_width'], $this->db->VARS['img_height']);
         if ($this->db->VARS['thumbnail'] == 1)
         {
            $min_size = 1024*$this->db->VARS['thumb_min_fsize'];
            $img->set_min_filesize($min_size);
            $img->set_prefix('t_');
            $img->create_thumbnail("$this->include_path/$GB_TMP/$this->user_img",$this->user_img);
         }
         copy("$this->include_path/$GB_TMP/$this->user_img", "$this->include_path/$GB_UPLOAD/$this->user_img");
         unlink("$this->include_path/$GB_TMP/$this->user_img");
         $this->image_file = $this->user_img;
      }
      $this->name = htmlspecialchars($this->name);
      $this->location = htmlspecialchars($this->location);
      $this->comment = htmlspecialchars($this->comment);
      if ($this->db->VARS['allow_html'] == 1)
      {
         $this->comment = $this->db->allowed_html($this->comment);
      }
      if ($this->db->VARS['agcode'] == 1)
      {
         $this->comment = $this->db->AGCode($this->comment);
      }
      if (!get_magic_quotes_gpc())
      {
         $this->name = addslashes($this->name);
         $this->location = addslashes($this->location);
         $this->aim = addslashes($this->aim);
         $this->msn = addslashes($this->msn);
         $this->yahoo = addslashes($this->yahoo);
         $this->skype = addslashes($this->skype);
         $this->email = addslashes($this->email);
         $this->url = addslashes($this->url);
         $this->comment = addslashes($this->comment);
         $this->gender = addslashes($this->gender);
      }
      $host = htmlspecialchars(@gethostbyaddr($this->ip));
      $agent = htmlspecialchars($_SERVER['HTTP_USER_AGENT']);
      $the_time = time();
      $this->accepted = '1';
      if ($this->db->VARS['require_checking'] == 1)
      {
         $this->accepted = ($this->private == 1) ? '1' : '0';
      }
      $sql_usertable = (($this->private == 1) && ($this->db->VARS['allow_private'] == 1)) ? $this->table['priv'] : $this->table['data'];
      $this->db->query("INSERT INTO $sql_usertable (name,gender,email,url,date,location,host,browser,comment,icq,aim,msn,yahoo,skype,accepted,ip) VALUES ('$this->name','$this->gender','$this->email','$this->url',$the_time,'$this->location','$host','$agent','$this->comment',$this->icq,'$this->aim','$this->msn','$this->yahoo','$this->skype',$this->accepted,'$this->ip')");
      if (!empty($this->image_file) || !empty($this->user_img))
      {
         $size = GetImageSize("$this->include_path/$GB_UPLOAD/$this->image_file");
         if ((is_array($size)) && ((($size[2] > 0) && ($size[2] < 4)) || $size[2] == 6))
         {
            $book_id = ($this->private==1) ? 1 : 2;
            $p_filesize = filesize("$this->include_path/$GB_UPLOAD/$this->image_file");
            $this->db->fetch_array($this->db->query("SELECT MAX(id) AS msg_id FROM $sql_usertable"));
            $this->db->query("INSERT INTO ".$this->table['pics']." (msg_id,book_id,p_filename,p_size,width,height) VALUES ('".$this->db->record['msg_id']."',$book_id,'$this->image_file','$p_filesize','$size[0]','$size[1]')");
         }
      }
      $LANG =& $this->db->LANG;
      if ($this->db->check_emailaddress($this->db->VARS['book_mail']))
      {
        $admin_email = $this->db->VARS['book_mail'];
      }
      elseif ($this->db->check_emailaddress($this->db->VARS['admin_mail']))
      {
        $admin_email = $this->db->VARS['admin_mail'];
      }
      else 
      {
        $admin_email = '';
      }
      if ($this->email == '')
      {
        $from_email = ($admin_email != '') ? $admin_email :  'guestbookentry@'.$host;
      }
      else
      {
        $from_email = $this->email;
      }
      $hostname = (preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/', $host) ) ? 'IP' : 'Host';
      $messagebody = $LANG['FormName'].': '.$this->name."<br>\n";
      $messagebody .= $hostname.': '.$host."<br>\n";
      $messagebody .= ($this->location != '') ? $LANG['FormLoc'].': '.$this->location."<br>\n" : '';
      $messagebody .= ($this->email != '') ? $LANG['FormEmail'].': '.$this->email."<br>\n" : '';
      $messagebody .= ($this->url != '') ? $LANG['FormUrl'].': <a href="'.$this->url.'" target="_blank">'.$this->url."</a><br>\n" : ''; 
      $messagebody .= ($this->aim != '') ? 'AIM: '.$this->aim."<br>\n" : '';
      $messagebody .= ($this->icq != '') ? 'ICQ: '.$this->icq."<br>\n" : ''; 
      $messagebody .= ($this->msn != '') ? 'MSN: '.$this->msn."<br>\n" : ''; 
      $messagebody .= ($this->yahoo != '') ? 'Yahoo: '.$this->yahoo."<br>\n" : '';
      $messagebody .= ($this->skype != '') ? 'Skype: '.$this->skype."<br>\n" : '';
      $messagebody .= "<br>\n<br>\n".nl2br($this->comment);
      $messagebody = stripslashes($messagebody);
      $fromname = $this->db->undo_htmlspecialchars(stripslashes($this->name));
      if (($this->db->VARS['notify_guest'] == 1) && ($this->email != '') && ($admin_email != ''))
      {
         if ($this->db->VARS['html_email'] == 1)
         {
            $email_message = nl2br($this->db->VARS['notify_mes']);
            $content_type = "\nContent-Type: text/html; charset=\"".$this->db->VARS['charset']."\"";
            $email_message = str_replace('[NAME]', stripslashes($this->name), $email_message);
         }
         else
         {
            $email_message = $this->db->VARS['notify_mes'];
            $email_message = str_replace('[NAME]', $fromname, $email_message);
            $content_type = '';
         }
         @mail($this->email,$this->db->LANG["EmailGuestSubject"],$email_message, "From: ".$admin_email."\nX-Mailer: Lazarus Guestbook".$content_type);
      }
      if ($this->db->check_emailaddress($this->db->VARS['admin_mail']))
      {
        if (($this->db->VARS['notify_private'] == 1) && ($this->private == 1))
        {
           @mail($this->db->VARS['admin_mail'],$this->db->LANG['EmailAdminSubject'].' - '.$this->db->LANG['FormPriv'],$this->db->LANG['FormPriv']."\n\n".$messagebody, "From: \"".$fromname."\" <".$from_email.">\nX-Mailer: Lazarus Guestbook\nContent-Type: text/html; charset=\"".$this->db->VARS['charset']."\"");
        }
        if ((($this->db->VARS['notify_admin'] == 1) || ($this->db->VARS['require_checking'])) && ($this->private == 0))
        {
           @mail($this->db->VARS["admin_mail"],$this->db->LANG["EmailAdminSubject"],$messagebody, "From: \"".$fromname."\" <".$from_email.">\nX-Mailer: Lazarus Guestbook\nContent-Type: text/html; charset=\"".$this->db->VARS['charset']."\"");
        }
      }
      if ($this->db->VARS['flood_check'] == 1)
      {
        $this->db->query("INSERT INTO ".$this->table['ip']." (guest_ip,timestamp) VALUES ('$this->ip','$the_time')");
      }      
      $LANG =& $this->db->LANG;
      $VARS =& $this->db->VARS;
      $success_message = $LANG['BookMess10'];
      if ($this->db->VARS['require_checking'] == 1)
      {
         $success_message = $LANG['BookMess11'];
      }
      $success_html = '';
      eval("\$success_html .= \"".$this->template->get_template('success_header')."\";");
      eval("\$success_html .= \"".$this->template->get_template('success')."\";");
      eval("\$success_html .= \"".$this->template->get_template('footer')."\";");
      return $success_html;
   }
    
//
// Generate the smileys table used on the add entry form using the smileys in the smileys table.
//    
    
   function generate_smilies()
   {
      global $GB_PG;
      // Change this if you want more than 12 smileys shown if they exist in the database
      // Remember to edit the smileys.php if you want more smileys
      $howmany = 12;
      //Create an empty smiley array incase there are not the required number of smileys
      for ($i=1;$i<=$howmany;$i++)
      {
         $smileyimage[$i] = ' ';
      }
      //Set the counter to 0
      $smileyarray = 0;
      //Retrieve the smiley table
      $smileyq = $this->db->query('SELECT * FROM '.$this->db->table['smile'].' ORDER BY id ASC LIMIT '.$howmany);
      //Load the smileys into img tags
      while ($thesmiley = $this->db->fetch_array($smileyq))
      {
         $smileyarray++;
         $smileyimage[$smileyarray] = "<a href=\"javascript:emoticon('".$thesmiley['s_code']."')\"><img src=\"".$GB_PG['base_url']."/img/smilies/".$thesmiley['s_filename']."\" alt=\"".$thesmiley['s_code']."\" title=\"".$thesmiley['s_emotion']."\" border=\"0\"></a>";
      }
      $smileytable = '';
      eval("\$smileytable = \"".$this->template->get_template('smileys')."\";");
      return $smileytable;
   }
		
//
// Generate the form for them to sign
//

   function form_addguest()
   {
      global $GB_PG, $_COOKIE;
      $LANG =& $this->db->LANG;
      $VARS =& $this->db->VARS;
      $antispam = $this->db->VARS['antispam_word'];
      $HTML_CODE = ($this->db->VARS['allow_html'] == 1) ? $this->db->LANG['BookMess2'] : $this->db->LANG['BookMess1'];
      if (isset($_COOKIE['lang']) && !empty($_COOKIE['lang']) && file_exists($this->include_path.'/lang/codes-'.$_COOKIE['lang'].'.php'))
      {
         $LANG_CODES = $GB_PG[base_url].'/lang/codes-'.$_COOKIE['lang'].'.php';
      }
      elseif (file_exists($this->include_path.'/lang/codes-'.$VARS['lang'].'.php'))
      {
         $LANG_CODES = $GB_PG['base_url'].'/lang/codes-'.$VARS['lang'].'.php';
      }
      else
      {
         $LANG_CODES = $GB_PG['base_url'].'/lang/codes-english.php';
      }
      $AG_CODE = ($this->db->VARS['agcode'] == 1) ? '<a href="'.$LANG_CODES.'?show=agcode" onclick="openCentered(\''.$LANG_CODES.'?show=agcode\',\'_codes\',640,450,\'scrollbars=yes\'); return false;" target="_codes">'.$this->db->LANG['FormMess3'].'</a>' : $this->db->LANG['FormMess6'];
      if ($this->db->VARS['smilies'] == 1)
      {
         $SMILE_CODE = $this->db->LANG['FormMess2'];
         $SMILEYS = $this->generate_smilies();
         $SMILEYPOP = '<a href="'.$LANG_CODES.'?show=smilies" onclick="openCentered(\''.$LANG_CODES.'?show=smilies\',\'_codes\',640,450,\'scrollbars=yes\'); return false;" target="_codes">'.$this->db->LANG['FormMess4'].'</a>';
      }
      else
      {
         $SMILE_CODE = $this->db->LANG['FormMess7'];
         $SMILEYS = '';
         $SMILEYPOP = '';
      }
      $EXTRAJS = '';
      $EMAILJS = '';
      $BOTTEST = '';
      $EMAILREQ = '';
      if ((($this->db->VARS['require_email'] == 1) || ($this->db->VARS['require_email'] == 4)))
      {
         $EXTRAJS .= " document.book.gb_email.value=trim(document.book.gb_email.value);\nif(document.book.gb_email.value == \"\") {\n   alert(\"".$LANG['ErrorPost12']."\");\n   document.book.gb_email.focus();\n   return false;\n }";
         $EMAILREQ = '*';
      }
      $OPTIONS[] ='';
      if ($this->db->VARS['require_email'] != 2)
      {
         eval("\$OPTIONS['email'] = \"".$this->template->get_template('form_email')."\";");
      }
      if ($this->db->VARS['allow_loc'] == 1)
      {
         eval("\$OPTIONS['location'] = \"".$this->template->get_template('form_loc')."\";");
      }       
      if ($this->db->VARS['allow_url'] == 1)
      {
         eval("\$OPTIONS['url'] = \"".$this->template->get_template('form_url')."\";");
      }
      if ($this->db->VARS['allow_icq'] == 1)
      {
         eval("\$OPTIONS['icq'] = \"".$this->template->get_template('form_icq')."\";");
      }
      if ($this->db->VARS['allow_aim'] == 1)
      {
         eval("\$OPTIONS['aim'] = \"".$this->template->get_template('form_aim')."\";");
      }
      if ($this->db->VARS['allow_yahoo'] == 1)
      {
         eval("\$OPTIONS['yahoo'] = \"".$this->template->get_template('form_yahoo')."\";");
      }
      if ($this->db->VARS['allow_skype'] == 1)
      {
         eval("\$OPTIONS['skype'] = \"".$this->template->get_template('form_skype')."\";");
      }
      if ($this->db->VARS['allow_msn'] == 1)
      {
         eval("\$OPTIONS['msn'] = \"".$this->template->get_template('form_msn')."\";");
      }
      if ($this->db->VARS['allow_gender'] == 1)
      {
         eval("\$OPTIONS['gender'] = \"".$this->template->get_template('form_gender')."\";");
      }
      if ($this->db->VARS['allow_img'] == 1)
      {
         eval("\$OPTIONS['img'] = \"".$this->template->get_template('form_image')."\";");
      }
      $TIMEHASH = $this->db->generate_timehash();
      $OPTIONS['timehash'] = '<input type="hidden" name="gb_timehash" value="'.$TIMEHASH.'">';
      $OPTIONAL = implode("\n",$OPTIONS);
      if ($this->db->VARS['antibottest'] == 1)
      {
         $EXTRAJS .= " document.book.gb_bottest.value=trim(document.book.gb_bottest.value);\n if(document.book.gb_bottest.value == \"\") {\n   alert(\"".$LANG['ErrorPost13']."\");\n   document.book.gb_bottest.focus();\n   return false;\n }";
         $bot_question = (get_magic_quotes_gpc()) ? stripslashes($this->db->VARS['bottestquestion']) : $this->db->VARS['bottestquestion'];
         eval("\$BOTTEST .= \"".$this->template->get_template('form_bots')."\";");
      }
      elseif ($this->db->VARS['antibottest'] == 2)
      {
         $EXTRAJS .= " document.book.gb_bottest.value=trim(document.book.gb_bottest.value);\n if(document.book.gb_bottest.value == \"\") {\n   alert(\"".$LANG['ErrorPost13']."\");\n   document.book.gb_bottest.focus();\n   return false;\n }";
         if ($this->db->VARS['captcha_noise'] == 0)
         {
            $CAPTCHAVARS[] = 'noise=no';
         }
         if ($this->db->VARS['captcha_grid'] == 0)
         {
            $CAPTCHAVARS[] = 'grid=no';
         }
         if ($this->db->VARS['captcha_grey'] == 0)
         {
            $CAPTCHAVARS[] = 'grey=yes';
         }
         if ($this->db->VARS['captcha_greytext'] == 0)
         {
            $CAPTCHAVARS[] = 'greyt=no';
         }
         if (isset($CAPTCHAVARS) && is_array($CAPTCHAVARS))
         {
            $TIMEHASH .= '&'.implode('&',$CAPTCHAVARS);
         }
         eval("\$BOTTEST .= \"".$this->template->get_template('form_captcha')."\";");
      }      
      $PRIVATE = ($this->db->VARS['allow_private'] == 1) ? "<input type=\"checkbox\" name=\"gb_private\" value=\"1\"> <font size=\"1\" face=\"$VARS[font_face]\">$LANG[FormPriv]</font>" : '';
      $display_tags = '';
      if ($this->db->VARS['agcode'] == 1)
      {
         $display_tags = '<input type="button" value="b" onClick="agCode(\'b\'); return false;" style="width:34px;font-weight:bold;">
         <input type="button" value="i" onClick="agCode(\'i\'); return false;" style="width:34px;font-style:italic;">
         <input type="button" value="u" onClick="agCode(\'u\'); return false;" style="width:34px;text-decoration:underline;">
         ';
         if ($this->db->VARS['allow_emailagcode'] == 1)
         {
            $display_tags .= '<input type="button" value="@" onClick="agCode(\'email\'); return false;" style="width:34px;">
            ';
         }         
         if ($this->db->VARS['allow_imgagcode'] == 1)
         {
            $display_tags .= '<input type="button" value="img" onClick="agCode(\'img\'); return false;" style="width:34px;">
            ';
         }
         if ($this->db->VARS['allow_urlagcode'] == 1)
         {
            $display_tags .= '<input type="button" value="url" onClick="agCode(\'url\'); return false;" style="width:34px;">
            ';
         }
         $display_tags .= "<br>\n";
      }
      $addform_html = '';
      eval("\$addform_html = \"".$this->template->get_template('header')."\";");
      eval("\$addform_html .= \"".$this->template->get_template('form')."\";");
      eval("\$addform_html .= \"".$this->template->get_template('footer')."\";");
      return $addform_html;
   }
    
//
// If they want to preview their entry then we need to format the data
//    

   function preview_entry()
   {
      global $GB_PG;
      if (get_magic_quotes_gpc())
      {
         $this->name = stripslashes($this->name);
         $this->comment = stripslashes($this->comment);
         $this->location = stripslashes($this->location);
      }
      $this->name = htmlspecialchars($this->name);
      $message = htmlspecialchars($this->comment);
      $message = nl2br($message);
      $this->url = trim($this->url);
      $this->email = trim($this->email);
      $TEXTEMAIL = '';
      if (!$this->db->check_emailaddress($this->email))
      {
         $this->email = '';
      }
      if (substr($this->url, 0, 4) == 'www.')
      {
         $this->url = 'http://'.$this->url;
      }
      if (!preg_match('@^http://[_a-z0-9-]+\\.[_a-z0-9-]+@i', $this->url))
      {
         $this->url = '';
      }
      if (htmlspecialchars($this->url) != $this->url)
      {
         $this->url = '';
      }
      if ($this->db->VARS['allow_html'] == 1)
      {
         $message = $this->db->allowed_html($message);
      }
      if ($this->db->VARS['smilies'] == 1)
      {
         $message = $this->db->emotion($message);
      }
      if ($this->db->VARS['agcode'] == 1)
      {
         $message = $this->db->AGCode($message);
      }
      $antispam = $this->db->VARS['antispam_word'];
      $this->location = htmlspecialchars($this->location);
      $this->comment = htmlspecialchars($this->comment);
      $USER_PIC =(isset($this->tmp_image)) ? $this->tmp_image : '';
      $DATE = $this->db->DateFormat(time());
      $host = htmlspecialchars(@gethostbyaddr($this->ip));
      $agent = htmlspecialchars($_SERVER['HTTP_USER_AGENT']);
      $LANG =& $this->db->LANG;
      $VARS =& $this->db->VARS;
      if ($this->url && $this->db->VARS['allow_url'] == 1)
      {
         $row['url'] = $this->url;
         eval("\$URL = \"".$this->template->get_template('url')."\";");
      }
      else
      {
         $URL = '';
      }
      if ($this->location && ($this->db->VARS['allow_loc'] == 1))
      {
         $row['location'] = $this->location;
         eval("\$LOCATION = \"".$this->template->get_template('location')."\";");
      }
      else
      {
         $LOCATION = '';
      }     
      if ($this->icq && $this->db->VARS['allow_icq'] == 1)
      {
         $row['icq'] = $this->icq;
         eval("\$ICQ = \"".$this->template->get_template('icq')."\";");
      }
      else
      {
         $ICQ = '';
      }
      if ($this->aim && $this->db->VARS['allow_aim'] == 1)
      {
         $row['aim'] = $this->aim;
         eval("\$AIM = \"".$this->template->get_template('aim')."\";");
      }
      else
      {
         $AIM = '';
      }
      if ($this->msn && $this->db->VARS['allow_msn'] == 1)
      {
         $row['msn'] = $this->msn;
         eval("\$MSN = \"".$this->template->get_template('msn')."\";");
      }
      else
      {
         $MSN = '';
      }
      if ($this->yahoo && $this->db->VARS['allow_yahoo'] == 1)
      {
         $row['yahoo'] = $this->yahoo;
         eval("\$YAHOO = \"".$this->template->get_template('yahoo')."\";");
      }
      else
      {
         $YAHOO = '';
      }      
      if ($this->skype && $this->db->VARS['allow_skype'] == 1)
      {
         $row['skype'] = $this->skype;
         eval("\$SKYPE = \"".$this->template->get_template('skype')."\";");
      }
      else
      {
         $SKYPE = '';
      }
      if ($this->email && ($this->db->VARS['require_email'] < 2))
      {
         $row['email'] = $this->email;
         if ($this->db->VARS['encrypt_email'] == 1)
         {
            $EMAILJS = "function getEmail(email) {\n    var stringPos = false; \n    var stringEmail = \"\"; \n    if (email.length>0) { \n    for (var i=0; i<email.length; i++) {    \n    stringPos = (i % 2) ? false : true; \n    if (stringPos == true) { \n    stringEmail = stringEmail + \"%\" + email.charAt(i); \n    } else { \n    stringEmail = stringEmail + email.charAt(i); \n    } \n  } \n  stringEmail = unescape(stringEmail); \n  window.location.href = stringEmail; \n  } \n}\n";
            $encemail = bin2hex($row['email']);
            $TEXTEMAIL = $this->db->html_encode($row['email']);
            $TEXTEMAIL = str_replace('&#64;', '<b>&#64;</b>', $TEXTEMAIL);
            $TEXTEMAIL = str_replace('&#46;', '<b>&#46;</b>', $TEXTEMAIL);
            $MAILTO = "javascript:getEmail('6d61696c746f3a$encemail')";
         }
         else
         {
            $MAILTO = 'mailto:'.$row['email'];
            $TEXTEMAIL = $row['email'];
         }
         eval("\$EMAIL = \"".$this->template->get_template('email')."\";");
      }
      else
      {
         $EMAILJS = '';
         $EMAIL = '';
      }
      if ($this->db->VARS['allow_gender'] == 1)
      {
         if (($this->gender!='f')&&($this->gender!='m'))
         {
            $GENDER = '';
         }
         else
         {
            $GENDER = ($this->gender == 'f') ? '&nbsp;<img src="'.$GB_PG['base_url'].'/img/female.gif" width="12" height="12" alt="'.$LANG['FormFemale'].'" title="'.$LANG['FormFemale'].'">' : '&nbsp;<img src="'.$GB_PG['base_url'].'/img/male.gif" width="12" height="12" alt="'.$LANG['FormMale'].'" title="'.$LANG['FormMale'].'">';
         }
      }
      else
      {
         $GENDER = '';
      }
      if ($this->db->VARS['show_ip'] == 1)
      {
         $hostname = (preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/', $host) ) ? 'IP' : 'Host';
         $HOST = "$hostname: $host\n";
      }
      else
      {
         $HOST = '';
      }
      $HIDDEN = "<input type=\"hidden\" name=\"gb_preview\" value=\"1\">\n";
      $HIDDEN .= "<input type=\"hidden\" name=\"gb_name\" value=\"".$this->name."\">\n";
      $HIDDEN .= "<input type=\"hidden\" name=\"gb_email\" value=\"".$this->email."\">\n";
      $HIDDEN .= "<input type=\"hidden\" name=\"gb_comment\" value=\"".$this->comment."\">\n";
      $HIDDEN .= "<input type=\"hidden\" name=\"gb_location\" value=\"".$this->location."\">\n";
      $HIDDEN .= "<input type=\"hidden\" name=\"gb_timehash\" value=\"".$this->timehash."\">\n";
      if ($this->image_file)
      {
         $HIDDEN .= "<input type=\"hidden\" name=\"gb_user_img\" value=\"".$this->image_file."\">\n";
      }
      if ($this->bottest)
      {
         $HIDDEN .= "<input type=\"hidden\" name=\"gb_bottest\" value=\"".$this->bottest."\">\n";
      }
      if ($this->private == 1)
      {
         $HIDDEN .= "<input type=\"hidden\" name=\"gb_private\" value=\"".$this->private."\">\n";
      }
      if ($this->db->VARS['allow_url'] == 1)
      {
         $HIDDEN .= "<input type=\"hidden\" name=\"gb_url\" value=\"".$this->url."\">\n";
      }
      if ($this->db->VARS['allow_gender'] == 1)
      {
         $HIDDEN .= "<input type=\"hidden\" name=\"gb_gender\" value=\"".$this->gender."\">\n";
      }
      if ($this->icq && $this->db->VARS['allow_icq'] == 1)
      {
         $HIDDEN .= "<input type=\"hidden\" name=\"gb_icq\" value=\"".$this->icq."\">\n";
      }
      if ($this->aim && $this->db->VARS['allow_aim'] == 1)
      {
         $HIDDEN .= "<input type=\"hidden\" name=\"gb_aim\" value=\"".$this->aim."\">\n";
      }
      if ($this->msn && $this->db->VARS['allow_msn'] == 1)
      {
         $HIDDEN .= "<input type=\"hidden\" name=\"gb_msn\" value=\"".$this->msn."\">\n";
      }
      if ($this->yahoo && $this->db->VARS['allow_yahoo'] == 1)
      {
         $HIDDEN .= "<input type=\"hidden\" name=\"gb_yahoo\" value=\"".$this->yahoo."\">\n";
      }
      if ($this->skype && $this->db->VARS['allow_skype'] == 1)
      {
         $HIDDEN .= "<input type=\"hidden\" name=\"gb_skype\" value=\"".$this->skype."\">\n";
      }
      $theirbrowser = $this->db->browser_detect($agent); 
      $AGENT = '';    
      $row['name'] = $this->name;
      $row['location'] = $this->location;
      $GB_PREVIEW = '';
      $preview_html = '';
      eval("\$GB_PREVIEW = \"".$this->template->get_template('preview_entry')."\";");
      eval("\$preview_html = \"".$this->template->get_template('header')."\";");
      eval("\$preview_html .= \"".$this->template->get_template('preview')."\";");
      eval("\$preview_html .= \"".$this->template->get_template('footer')."\";");
      return $preview_html;
   }
    
//
// Do whatever was requested from the addentry page. ie Display form or process entry
//    

   function process($action = '')
   {
      switch ($action)
      {
         case $this->db->LANG['FormSubmit']:
            if ($this->preview == 1)
            {
               $this->comment = $this->db->undo_htmlspecialchars($this->comment);
               $this->name = $this->db->undo_htmlspecialchars($this->name);
               $this->location = $this->db->undo_htmlspecialchars($this->location);
            }
            $this->clear_tmpfiles();
            $status = $this->check_entry();
            return ($status == 1) ? $this->add_guest() : $status;
            break;

         case $this->db->LANG['FormPreview']:
            $status = $this->check_entry('preview');
            return ($status == 1) ? $this->preview_entry() : $status;
            break;

         default:
            return $this->form_addguest();
      }
   }
}
?>