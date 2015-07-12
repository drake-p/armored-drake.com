<?php 
/*
 * ----------------------------------------------
 * Lazarus Guestbook 
 * by Stewart Souter
 * URL: www.carbonize.co.uk 
 * Based on Advanced Guestbook 2.3.x (PHP/MySQL)
 * Copyright (c)2001 Chi Kien Uong
 * URL: http://www.proxy2.de
 * Last Modified: Tue, 1 August 2006 14:40:55 GMT
 * ----------------------------------------------
 */

class gb_comment 
{

   var $comment;
   var $ip;
   var $id;
   var $db;
   var $user;
   var $pass_comment;
   var $template;
   var $path;
   var $bottest;
   var $timehash;

   function gb_comment($path = '')
   {
      global $_SERVER, $GB_PG;
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
      $this->path = $path;
      $this->template =& $this->db->template;
      $GB_PG['base_url'] = $this->db->VARS['base_url'];
   }

//
// Are they trying to make a comment on a post that actualy exists?
//

   function is_valid_id()
   {
      $this->db->query("SELECT id FROM ".$this->db->table['data']." WHERE (id = '".intval($this->id)."')");
      $this->db->fetch_array($this->db->result);
      return ($this->db->record) ? true : false;
   }

//
// Dynamically generate the smileys table for the form using the smileys in the smileys table
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
         $smileyimage[$smileyarray] = '<a href="javascript:emoticon(\''.$thesmiley['s_code'].'\')"><img src="'.$GB_PG['base_url'].'/img/smilies/'.$thesmiley['s_filename'].'" alt="'.$thesmiley['s_code'].'" title="'.$thesmiley['s_emotion'].'" border="0"></a>';
      }
      $smileytable = '<table border="0" cellspacing="0" cellpadding="6" align="center">';
      eval("\$smileytable .= \"".$this->template->get_template('smileys')."\";");
      return $smileytable;
   }

//
// Generate the comments form if thats what we want to do
//

   function comment_form()
   {
      global $GB_UPLOAD, $GB_PG, $include_path;
      $this->db->query("select x.*, y.p_filename, y.width, y.height, z.comments from ".$this->db->table['data']." x left join ".$this->db->table['pics']." y on (x.id=y.msg_id and y.book_id=2) left join ".$this->db->table['com']." z on (x.id=z.id) WHERE (x.accepted='1' AND x.id=".intval($this->id).") group by x.id order by x.id desc limit 1");
      $row = $this->db->fetch_array($this->db->result);
      $LANG =& $this->db->LANG;
      $VARS =& $this->db->VARS;
      if (isset($_COOKIE['lang']) && !empty($_COOKIE['lang']) && file_exists($include_path.'/lang/codes-'.$_COOKIE['lang'].'.php'))
      {
         $LANG_CODES = $GB_PG['base_url'].'/lang/codes-'.$_COOKIE['lang'].'.php';
      }
      elseif (file_exists($include_path.'/lang/codes-'.$VARS['lang'].'.php'))
      {
         $LANG_CODES = $GB_PG['base_url'].'/lang/codes-'.$VARS['lang'].'.php';
      }
      else
      {
         $LANG_CODES = $GB_PG['base_url'].'/lang/codes-english.php';
      }
      $antispam = $this->db->VARS['antispam_word'];
      $HTML_CODE = ($this->db->VARS['allow_html'] == 1) ? $this->db->LANG['BookMess2'] : $this->db->LANG['BookMess1'];
      $AG_CODE = ($this->db->VARS['agcode'] == 1) ? '<a href="'.$LANG_CODES.'?show=agcode" onclick="openCentered(\''.$LANG_CODES.'?show=agcode\',\'_codes\',640,450,\'scrollbars=yes\')" target="_codes">'.$this->db->LANG['FormMess3'].'</a>' : $this->db->LANG['FormMess6'];
      $SMILE_CODE = $this->db->LANG['FormMess7'];
      $DATE = $this->db->DateFormat($row['date']);
      $MESSAGE = nl2br($row['comment']);
      $SMILEYS = '<table border="0" cellspacing="0" cellpadding="6" align="center">';
      $SMILEYPOP = '';
      $id = $this->id;
      $bgcolor = $this->db->VARS['tb_color_1'];
      $COMMENT ='';
      if ($row['p_filename'] && preg_match('/^img-/',$row['p_filename']))
      {
         $img = new gb_image();
         $img->set_border_size($this->db->VARS['img_width'], $this->db->VARS['img_height']);
         $new_img_size = $img->get_img_size_format($row['width'], $row['height']);
         if (file_exists($this->path.'/'.$GB_UPLOAD.'/t_'.$row['p_filename']))
         {
            $row['p_filename'] = 't_'.$row['p_filename'];
         }
         eval("\$USER_PIC = \"".$this->template->get_template('user_pic')."\";");
      }
      else
      {
         $USER_PIC = '';
      }
      if ($this->db->VARS['smilies'] == 1)
      {
         $MESSAGE = $this->db->emotion($MESSAGE);
         $SMILEYS = $this->generate_smilies();
         $SMILE_CODE = $this->db->LANG['FormMess2'];
         $SMILEYPOP = '<a href="'.$LANG_CODES.'?show=smilies" onclick="openCentered(\''.$LANG_CODES.'?show=smilies\',\'_codes\',640,450,\'scrollbars=yes\')" target="_codes">'.$this->db->LANG['FormMess4'].'</a>';
      }
/*      if (!$row['location'])
      {
         $row['location'] = '-';
      }*/
      if ($row['url'] && ($this->db->VARS['allow_url'] == 1))
      {
         eval("\$URL = \"".$this->template->get_template('url')."\";");
      }
      else
      {
         $URL = '';
      }
      if ($row['location'] && ($this->db->VARS['allow_loc'] == 1))
      {
         eval("\$LOCATION = \"".$this->template->get_template('location')."\";");
      }
      else
      {
         $LOCATION = '';
      }      
      if ($row['icq'] && ($this->db->VARS['allow_icq'] == 1))
      {
         eval("\$ICQ = \"".$this->template->get_template('icq')."\";");
      }
      else
      {
         $ICQ = '';
      }
      if ($row['aim'] && ($this->db->VARS['allow_aim'] == 1))
      {
         eval("\$AIM = \"".$this->template->get_template('aim')."\";");
      }
      else
      {
         $AIM = '';
      }
      if ($row['msn'] && ($this->db->VARS['allow_msn'] == 1))
      {
         eval("\$MSN = \"".$this->template->get_template('msn')."\";");
      }
      else
      {
         $MSN = '';
      }
      if ($row['yahoo'] && ($this->db->VARS['allow_yahoo'] == 1))
      {
         eval("\$YAHOO = \"".$this->template->get_template('yahoo')."\";");
      }
      else
      {
         $YAHOO = '';
      }
      if ($row['skype'] && ($this->db->VARS['allow_skype'] == 1))
      {
         eval("\$SKYPE = \"".$this->template->get_template('skype')."\";");
      }
      else
      {
         $SKYPE = '';
      }      
      if ($row['email'] && ($this->db->VARS['require_email'] < 2))
      {
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
            $MAILTO = "mailto:$row[email]";
            $TEXTEMAIL = $row['email'];
         }
         eval("\$EMAIL = \"".$this->template->get_template('email')."\";");
      }
      else
      {
         $EMAIL = '';
         $TEXTEMAIL = '';
      }
      if ($this->db->VARS['allow_gender'] == 1)
      {
         $GENDER = ($row['gender'] == 'f') ? "&nbsp;<img src=\"$GB_PG[base_url]/img/female.gif\" width=\"12\" height=\"12\" alt=\"$LANG[FormFemale]\" title=\"$LANG[FormFemale]\">" : "&nbsp;<img src=\"$GB_PG[base_url]/img/male.gif\" width=\"12\" height=\"12\" alt=\"$LANG[FormMale]\" title=\"$LANG[FormMale]\">";
      }
      else
      {
         $GENDER = '';
      }
      if ($this->db->VARS['show_ip'] == 1)
      {
         $hostname = (preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/', $row['host']) ) ? 'IP' : 'Host';
         $HOST = $hostname.': '.$row['host']."\n";
      }
      else
      {
         $HOST='';
      }
      $TIMEHASH = $this->db->generate_timehash();
      $TimehashTag = '<input type="hidden" name="gb_timehash" value="'.$TIMEHASH.'">';
      $EXTRAJS = '';
      if ($this->db->VARS['need_pass'] == 1)
      {
         $com_question = ($this->db->VARS['com_question'] != '') ? $this->db->VARS['com_question']."<br>\n" : '';
         eval("\$COMMENT_PASS = \"".$this->template->get_template('com_pass')."\";");
      }
      elseif ($this->db->VARS['need_pass'] == 2)
      {
         $EXTRAJS .= 'function checkForm() {
          document.book.gb_bottest.value=trim(document.book.gb_bottest.value);
          if(document.book.gb_bottest.value == "") {
             alert("'.$LANG['ErrorPost13'].'");
             document.book.gb_bottest.focus();
             return false;
          }
          flag=1;
         return true;
         }';
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
         eval("\$COMMENT_PASS = \"".$this->template->get_template('form_captcha')."\";");
      }
      else
      {
         $COMMENT_PASS = '';
      }
      $GB_COMMENT = '#';
      $GB_ENTRY = '';
      $EMAILJS = '';
      $display_tags = '';
      if ($this->db->VARS['agcode'] == 1)
      {
         $display_tags = '<input type="button" value="b" onClick="agCode(\'b\'); return false;" style="width:34px;font-weight:bold;">
         <input type="button" value="i" onClick="agCode(\'i\'); return false;" style="width:34px;font-style:italic;">
         <input type="button" value="u" onClick="agCode(\'u\'); return false;" style="width:34px;text-decoration:underline;">
         <input type="button" value="@" onClick="agCode(\'email\'); return false;" style="width:34px;">
         ';
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
      if ($row['comments'])
      {
         $this->db->query("SELECT * FROM ".$this->db->table['com']." WHERE id='$row[id]' AND comaccepted='1' order by com_id asc");
         while ($com = $this->db->fetch_array($this->db->result))
         {
            $COMDATE = $this->db->DateFormat($com['timestamp']);
            $comhostname = (preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/', $com['host'])) ? 'IP' : 'Host';
            $comhost = ($this->db->VARS['show_ip'] == 1) ? '<i>'.$comhostname.': '.$com['host']."</i><br>\n" : '';
            $com['comments'] = ($this->db->VARS['smilies'] == 1) ? nl2br($this->db->emotion($com['comments'])) : nl2br($com['comments']);
            eval("\$COMMENT .= \"".$this->template->get_template('com')."\";");
         }
      }       
      $theirbrowser = $this->db->browser_detect($row['browser']);
      $comment_html = '';
      eval("\$GB_ENTRY = \"".$this->template->get_template('entry')."\";");
      $GB_ENTRY .= $TimehashTag;
      eval("\$comment_html = \"".$this->template->get_template('header')."\";");
      eval("\$comment_html .= \"".$this->template->get_template('comment')."\";");
      eval("\$comment_html .= \"".$this->template->get_template('footer')."\";");
      return $comment_html;
   }


//
// Check the submitted comment to make sure it's nice an clean and do some formatting
//

   function check_comment()
   {
      $the_time = time();
      $this->comment = $this->db->FormatString($this->comment);
      if (empty($this->comment))
      {
         return $this->db->gb_error($this->db->LANG['ErrorPost11']);
      }
      $this->user = $this->db->FormatString($this->user);
      if (empty($this->user))
      {
         return $this->db->gb_error($this->db->LANG['ErrorPost1']);
      }
      if (empty($this->timehash))
      {
         return $this->db->gb_error($this->db->LANG['ErrorPost4']);
      }
      if (($this->db->VARS['need_pass'] == 1) && empty($this->pass_comment)) 
      {
         return $this->db->gb_error($this->db->LANG['ErrorPost13']);
      }
      if (($this->db->VARS['need_pass'] == 2) && empty($this->bottest)) 
      {
         return $this->db->gb_error($this->db->LANG['ErrorPost13']);
      }
      if (!$this->db->CheckWordLength($this->user))
      {
         return $this->db->gb_error($this->db->LANG['ErrorPost4']);
      }
      if (!$this->db->CheckWordLength($this->comment))
      {
         return $this->db->gb_error($this->db->LANG['ErrorPost10']);
      }
      $this->comment = htmlspecialchars($this->comment);
      if ($this->db->VARS['censor'] == 1)
      {
         $this->user = $this->db->CensorBadWords($this->user);
         $this->comment = $this->db->CensorBadWords($this->comment);
      } 
      if ($this->db->VARS['censor'] == 2)
      {
         if ($this->db->BlockBadWords($this->user) || $this->db->BlockBadWords($this->comment))
         {
            return $this->db->gb_error($this->db->LANG['ErrorPost10']);
         }
      }
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
         $this->user = addslashes($this->user);
         $this->comment = addslashes($this->comment);
         $this->bottest = addslashes($this->bottest);
         $this->db->VARS['bottestanswer'] = addslashes($this->db->VARS['bottestanswer']);
         $this->db->VARS['comment_pass'] = addslashes($this->db->VARS['comment_pass']);
         $this->timehash = addslashes($this->timehash);
         $this->pass_comment = addslashes($this->pass_comment);
      }
      $this->user = htmlspecialchars($this->user);
      if ($this->db->VARS['need_pass'] == 1)
      {
         if (strtolower($this->db->VARS['comment_pass']) != strtolower($this->pass_comment))
         {
            return $this->db->gb_error($this->db->LANG['PassMess3']);
         }
      }
      if (($this->db->VARS['need_pass'] == 2) && (!$this->db->captcha_test($this->bottest, $this->timehash)))
      {
         return $this->db->gb_error($this->db->LANG['ErrorPost14']);
      }      
      if ($this->db->VARS['flood_check'] == 1 )
      {
         if ($this->db->FloodCheck($this->ip))
         {
            return $this->db->gb_error($this->db->LANG['ErrorPost8']);
         }
      }
      if ($this->db->VARS['banned_ip'] == 1)
      {
         if ($this->db->isBannedIp($this->ip))
         {
            return $this->db->gb_error($this->db->LANG['ErrorPost9']);
         }
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
// Insert the formatted comment into our database
//

   function insert_comment()
   {
      $the_time = time();
      $LANG =& $this->db->LANG;
      $host = addslashes(htmlspecialchars(@gethostbyaddr($this->ip)));
      $this->accepted = 1;
      if ($this->db->VARS['require_comchecking'] == 1)
      {
         $this->accepted = 0;
      }
      $this->db->query("INSERT INTO ".$this->db->table['com']." (id,name,comments,host,timestamp,comaccepted,ip) VALUES ('$this->id','$this->user','$this->comment','$host','$the_time','$this->accepted','$this->ip')");
      $this->db->query("SELECT comment FROM ".$this->db->table['data']." WHERE id=".$this->id);
      $original = $this->db->fetch_array($this->db->result);
      $hostname = ( preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/', $host) ) ? 'IP' : 'Host';
      $messagebody = $LANG['FormName'].': '.$this->user."<br>\n";
      $messagebody .= $hostname.': '.$host."<br>\n<br>\n";
      $messagebody .= $LANG['EmailMess1'].":<br>\n".$original['comment']."<br>\n<br>\n";
      $messagebody .= $LANG['EmailMess2'].":<br>\n".nl2br($this->comment);
      $messagebody = stripslashes($messagebody);
      if (($this->db->VARS['notify_admin_com'] == 1) || ($this->db->VARS['require_comchecking']))
      {
         @mail($this->db->VARS['admin_mail'],$this->db->LANG['EmailAdminComSubject'],$messagebody, "From: ".$this->db->VARS['admin_mail']."\nX-Mailer: Lazarus Guestbook\nContent-Type: text/html; charset=\"".$this->db->VARS['charset']."\"");
      }
   }

   function comment_action($action = '')
   {
      global $GB_PG, $IS_INCLUDE;
      if ($this->id && $this->is_valid_id() && $action == 1)
      {
         $status = $this->check_comment();
         if ($status == 1)
         {
            $this->insert_comment();
            $LANG =& $this->db->LANG;
            $VARS =& $this->db->VARS;
            $success_message = $LANG['BookMess10'];
            if ($this->db->VARS['require_comchecking'] == 1)
            {
               $success_message = $LANG['BookMess11'];
            }
            $success_html = '';
            eval("\$success_html .= \"".$this->template->get_template('success_header')."\";");
            eval("\$success_html .= \"".$this->template->get_template('success')."\";");
            eval("\$success_html .= \"".$this->template->get_template('footer')."\";");
            echo $success_html;
         }
         else
         {
            echo $status;
         }
      }
      elseif ($this->id && $this->is_valid_id())
      {
         echo $this->comment_form();
      }
      else
      {
         if (IS_INCLUDE)
         {
            echo ("<META HTTP-EQUIV=Refresh CONTENT=\"0; URL=".$GB_PG['index']."\">");
         }
         else
         {
            header("Location: $GB_PG[index]");
         }
      }
   }

}

?>