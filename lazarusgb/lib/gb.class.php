<?php 
/*
 * ----------------------------------------------
 * Lazarus Guestbook
 * by Stewart Souter
 * URL: www.carbonize.co.uk 
 * Based on Advanced Guestbook 2.3.x (PHP/MySQL)
 * Copyright (c)2001 Chi Kien Uong
 * URL: http://www.proxy2.de
 * Last Modified: Wed, 12 July 2006 22:25:53 GMT
 * ----------------------------------------------
 */

class guestbook
{

    var $total;
    var $db;
    var $template;
    var $path;

   function guestbook($path = '')
   {
	   global $GB_PG;
      $this->db = new guestbook_vars($path);
      $this->db->getVars();
      $this->total = 0;
      $this->path = $path;
      $this->template = &$this->db->template;
      $GB_PG['base_url'] = $this->db->VARS['base_url'];
   }
    
//
// Generate our next page/last page links
//    

   function get_nav($totalentries,$entry)
   {
      global $_SERVER, $GB_PG;
      $VARS =& $this->VARS;
      $self = (IS_MODULE && preg_match('/\?/',$GB_PG['index'])) ? $GB_PG[index].'&amp;entry=' : basename($_SERVER['PHP_SELF']).'?entry=';
      $entriesperpage = $this->db->VARS['entries_per_page']; //How many entries to be displayed on a page
      $totalpages = ceil($totalentries/$entriesperpage); // How high do the links go?

      if ($totalentries <= $entriesperpage) // Check we have enough entries to make more than one page
      {
         return '';
      }

      $currentpage = (!empty($entry)) ? ceil(($entry + $entriesperpage) / $entriesperpage) : 1; // What page we on?
      
      $loopstart = ($currentpage > 3) ? $currentpage - 2 : 1;
      $loops = ($totalpages > 4) ? 5 : $totalpages;
      if ($loopstart == 2)
      {
         $pagination = '<a href="'.$self.'0">1</a>';
      }
      elseif ($loopstart > 2)
      {
         $pagination = '<a href="'.$self.'0">1</a> ...';
      }
      else
      {
         $pagination = '';
      }
      
      for ($i = $loopstart; $i < ($loopstart + $loops); $i++)
      {
         if ($i < 1)
         {
            continue;
         }
         if (($i > $totalpages) || ($i > $currentpage + 2))
         {
            break;
         }
         if ($i == $currentpage)
         {
            $pagination .= ' ['.$i.']';
         }
         else
         { 
            $pagination .= ' <a href="'.$self.(($i - 1) * $entriesperpage).'">'.$i.'</a>';
         }
      }
      
      if ($loopstart < ($totalpages - $loops))
      {
         $pagination .= ' ... <a href="'.$self.(($totalpages * $entriesperpage) - $entriesperpage).'">'.$totalpages.'</a>';
      }
      elseif ($loopstart == ($totalpages - $loops))
      {
         $pagination .= ' <a href="'.$self.(($totalpages * $entriesperpage) - $entriesperpage).'">'.$totalpages.'</a>';
      }
      
      return $pagination;
   }  

   function show_entries($entry = 0)
   {
      global $GB_PG;
      $entry = intval($entry);
      $LANG =& $this->db->LANG;
      $VARS =& $this->db->VARS;
      $this->db->fetch_array($this->db->query("select count(*) as total from ".$this->db->table['data']." WHERE accepted='1'"));
      $this->total = $this->db->record['total'];
      $TPL = $this->get_entries($entry,$this->db->VARS['entries_per_page']);
      $TPL['GB_TOTAL'] = $this->total;
      $TPL['GB_JUMPMENU'] = implode("\n",$this->generate_JumpMenu());
      $TPL['GB_TIME'] = $this->db->DateFormat(time());
      $TPL['GB_NAVIGATION'] = $this->get_nav($this->total,$entry);
      $TPL['GB_HTML_CODE'] = ($this->db->VARS['allow_html'] == 1) ? $this->db->LANG['BookMess2'] : $this->db->LANG['BookMess1'];
      $EMAILJS = '';
      if ($this->db->VARS['encrypt_email'] == 1)
      {
         $EMAILJS = "function getEmail(email) {\n    var stringPos = false; \n    var stringEmail = \"\"; \n    if (email.length>0) { \n    for (var i=0; i<email.length; i++) {    \n    stringPos = (i % 2) ? false : true; \n    if (stringPos == true) { \n    stringEmail = stringEmail + \"%\" + email.charAt(i); \n    } else { \n    stringEmail = stringEmail + email.charAt(i); \n    } \n  } \n  stringEmail = unescape(stringEmail); \n  window.location.href = stringEmail; \n  } \n}\n";
      }
      $guestbook_html = '';
      eval("\$guestbook_html = \"".$this->template->get_template('header')."\";");
      eval("\$guestbook_html .= \"".$this->template->get_template('body')."\";");
      eval("\$guestbook_html .= \"".$this->template->get_template('footer')."\";");
      return $guestbook_html;
   }
    
//
// Generate the drop down jump menu
//    

   function generate_JumpMenu()
   {
      $menu_array[] = '<select name="entry" class="select">';
      $menu_array[] = '<option value="0" selected="selected">'.$this->db->LANG['FormSelect'].'</option>';
      if ($this->db->VARS['entries_per_page'] < $this->total)
      {
         $remain = $this->total % $this->db->VARS['entries_per_page'];
         $i = $this->total-$remain;
         if ($remain > 0)
         {
            $menu_array[] = '<option value="0">'.$i.'-'.$this->total.'</option>';
         }         
         while ($i > 0)
         {
            $num_max = $i;
            $num_min = $num_max-$this->db->VARS['entries_per_page'];
            $num_min++;
            $menu_array[] = '<option value="'.$remain.'">'.$num_min.'-'.$num_max.'</option>';
            $i = $num_min-1;
            $remain += $this->db->VARS['entries_per_page'];
         }
      }
      $menu_array[] = '</select>';
      $menu_array[] = '<input type="submit" value="'.$this->db->LANG['FormButton'].'" class="input">';
      return $menu_array;
   }
    
//
// Retrieve and format our entries for dislaying
//

   function get_entries($entry,$last_entry)
   {
      global $GB_UPLOAD, $GB_PG;
      $entry = intval($entry);
      $VARS =& $this->db->VARS;
      $last_entry = intval($last_entry);
      $img = new gb_image();
      $img->set_border_size($this->db->VARS['img_width'], $this->db->VARS['img_height']);
      $LANG =& $this->db->LANG;
      $id = $this->total-$entry;
      $HOST = '';
      $COMMENT = '';
      $GB_ENTRIES = '';
      $i = 0;
      $template['entry'] = $this->template->get_template('entry');
      $template['location'] = $this->template->get_template('location');
      $template['com'] = $this->template->get_template('com');
      $template['url'] = $this->template->get_template('url');
      $template['icq'] = $this->template->get_template('icq');
      $template['aim'] = $this->template->get_template('aim');
      $template['msn'] = $this->template->get_template('msn');
      $template['yahoo'] = $this->template->get_template('yahoo');
      $template['skype'] = $this->template->get_template('skype');
      $template['email'] = $this->template->get_template('email');
      $template['image'] = $this->template->get_template('user_pic');
      $result = $this->db->query("SELECT x.*, y.p_filename, y.width, y.height, z.comments FROM ".$this->db->table['data']." x LEFT JOIN ".$this->db->table['pics']." y ON (x.id=y.msg_id and y.book_id=2) LEFT JOIN ".$this->db->table['com']." z ON (x.id=z.id) WHERE x.accepted='1' GROUP BY x.id ORDER BY x.id DESC LIMIT $entry, $last_entry");
      while ($row = $this->db->fetch_array($result))
      {
         if (($this->db->VARS['ad_pos'] > 0) && ($this->db->VARS['ad_code'] != '') && ($this->db->VARS['ad_pos'] == ($i + 1)))
         {
            $GB_ENTRIES .= '<tr bgcolor="';
            $GB_ENTRIES .= ($i % 2) ? $this->db->VARS['tb_color_2'] : $this->db->VARS['tb_color_1'];
            $GB_ENTRIES .= '"><td colspan="2" align="center" class="font1">'.$this->db->VARS['ad_code'].'</td></tr>';
            $i++; 
         }
         $DATE = $this->db->DateFormat($row['date']);
         $MESSAGE = nl2br($row['comment']);
         if ($row['p_filename'] && preg_match('/^img-/',$row['p_filename']))
         {
            if (file_exists($this->path.'/'.$GB_UPLOAD.'/t_'.$row['p_filename']))
            {
               $row['p_filename'] = 't_'.$row['p_filename'];
            }
            $new_img_size = $img->get_img_size_format($row['width'], $row['height']);
            eval("\$USER_PIC = \"".$template['image']."\";");
         }
         else
         {
            $USER_PIC = '';
         }
         if ($this->db->VARS['smilies'] == 1)
         {
            $MESSAGE = $this->db->emotion($MESSAGE);
         }
/*         if (!$row['location'])
         {
            $row['location'] = '-';
         }*/
         $bgcolor = ($i % 2) ? $this->db->VARS['tb_color_2'] : $this->db->VARS['tb_color_1'];
         $i++;
         if ($row['url'] && ($this->db->VARS['allow_url'] == 1))
         {
            eval("\$URL = \"".$template['url']."\";");
         }
         else
         {
            $URL = '';
         }
         if ($row['location'] && ($this->db->VARS['allow_loc'] == 1))
         {
            eval("\$LOCATION = \"".$template['location']."\";");
         }
         else
         {
            $LOCATION = '';
         }
         if (($row['icq']) && ($this->db->VARS['allow_icq'] == 1))
         {
            eval("\$ICQ = \"".$template['icq']."\";");
         }
         else
         {
            $ICQ = '';
         }
         if (($row['aim']) && ($this->db->VARS['allow_aim'] == 1))
         {
            eval("\$AIM = \"".$template['aim']."\";");
         }
         else
         {
            $AIM = '';
         }
         if (($row['msn']) && ($this->db->VARS['allow_msn'] == 1))
         {
            eval("\$MSN = \"".$template['msn']."\";");
         }
         else
         {
            $MSN = '';
         }
         if (($row['yahoo']) && ($this->db->VARS['allow_yahoo'] == 1))
         {
            eval("\$YAHOO = \"".$template['yahoo']."\";");
         }
         else
         {
            $YAHOO = '';
         }
         if (($row['skype']) && ($this->db->VARS['allow_skype'] == 1))
         {
            eval("\$SKYPE = \"".$template['skype']."\";");
         }
         else
         {
            $SKYPE = '';
         }
         if ($row['email'] && ($this->db->VARS['require_email'] < 2))
         {
            if ($this->db->VARS['encrypt_email'] == 1)
            {
               $encemail = bin2hex($row['email']);
               $TEXTEMAIL = $this->db->html_encode($row['email']);
               $TEXTEMAIL = str_replace('&#64;', '<b>&#64;</b>', $TEXTEMAIL);
               $TEXTEMAIL = str_replace('&#46;', '<b>&#46;</b>', $TEXTEMAIL);
               $MAILTO = 'javascript:getEmail(\'6d61696c746f3a'.$encemail.'\')';
            }
            else
            {
               $MAILTO = 'mailto:'.$row[email];
               $TEXTEMAIL = $row['email'];
            }
            eval("\$EMAIL = \"".$template['email']."\";");
         }
         else
         {
            $EMAIL = '';
            $TEXTEMAIL = '';
         }
         if ($this->db->VARS['allow_gender'] == 1)
         {
            if ($row['gender'] != 'x')
            {
               $GENDER = ($row['gender'] == 'f') ? '&nbsp;<img src="'.$GB_PG['base_url'].'/img/female.gif" width="12" height="12" alt="'.$this->db->LANG['FormFemale'].'" title="'.$this->db->LANG['FormFemale'].'">' : '&nbsp;<img src="'.$GB_PG['base_url'].'/img/male.gif" width="12" height="12" alt="'.$this->db->LANG['FormMale'].'" title="'.$this->db->LANG['FormMale'].'">';
            }
            else
            {
               $GENDER = '';
            }
         }
         else
         {
            $GENDER = '';
         }
         $GB_COMMENT = (((IS_MODULE) || (IS_INCLUDE)) && preg_match('/\?/',$GB_PG['comment'])) ? $GB_PG['comment'].'&amp;gb_id='.$row['id'] : $GB_PG['comment'].'?gb_id='.$row['id'];
         if ($this->db->VARS['disablecomments'] != 1)
         {
            $COMMENTLINK = '<a href="'.$GB_COMMENT.'" rel="nofollow"><img src="'.$GB_PG['base_url'].'/img/edit.gif" width="18" height="13" border="0" alt="'.$LANG['AltCom'].'" title="'.$LANG['AltCom'].'"></a>';
         }
         else
         {
            $COMMENTLINK = '';
         }
         if ($this->db->VARS['show_ip'] == 1)
         {
            $hostname = (preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/', $row['host'])) ? 'IP' : 'Host';
            $HOST = "$hostname: $row[host]\n";
         }
         if ($row['comments'])
         {
            $this->db->query("SELECT * FROM ".$this->db->table['com']." WHERE id='$row[id]' AND comaccepted='1' order by com_id asc");
            if (($this->db->VARS['hide_comments'] == 1) && ($this->db->num_rows($this->db->result) > 0))
            {
               $COMMENT .= "<script type=\"text/Javascript\">document.write('<br clear=\"both\"><a href=\"javascript:toggleview(\'com".$row['id']."_open\');\" title=\"".$LANG['BookMess12']."\" style=\"clear:all;\">".$LANG['BookMess12']."</a>";
               $COMMENT .= "<div id=\"com".$row['id']."_open\" style=\"display:none;position:relative;\">')</script>";
            }
            while ($com = $this->db->fetch_array($this->db->result))
            {
               $COMDATE = $this->db->DateFormat($com['timestamp']);
               $comhostname = (preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/', $com['host'])) ? 'IP' : 'Host';
               $comhost = ($this->db->VARS['show_ip'] == 1) ? '<i>'.$comhostname.': '.$com['host']."</i><br>\n" : '';
               $com['comments'] = ($this->db->VARS['smilies'] == 1) ? nl2br($this->db->emotion($com['comments'])) : nl2br($com['comments']);
               eval("\$COMMENT .= \"".$template['com']."\";");
            }
            if ($this->db->VARS['hide_comments'] == 1)
            {
               $COMMENT .= "<script type=\"text/Javascript\">document.write('</div>')</script>\n";
            }
         }          
         $theirbrowser = $this->db->browser_detect($row['browser']);
         eval("\$GB_ENTRIES .= \"".$template['entry']."\";");
         $COMMENT = '';
         $id--;
         if (($this->db->VARS['ad_pos'] > $last_entry) && ($this->db->VARS['ad_code'] != '') && ($i == $last_entry))
         {
            $GB_ENTRIES .= '<tr bgcolor="';
            $GB_ENTRIES .= ($i % 2) ? $this->db->VARS['tb_color_2'] : $this->db->VARS['tb_color_1'];
            $GB_ENTRIES .= '"><td colspan="2" align="center" class="font1">'.$this->db->VARS['ad_code'].'</td></tr>';
            $i++; 
         }               
      }
      $TPL['GB_ENTRIES'] = $GB_ENTRIES;
      return $TPL;
   }

}

?>