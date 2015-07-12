<?php 
/*
 * ----------------------------------------------
 * Lazarus Guestbook
 * by Stewart Souter
 * URL: www.carbonize.co.uk 
 * Based on Advanced Guestbook 2.3.x (PHP/MySQL)
 * Copyright (c)2001 Chi Kien Uong
 * URL: http://www.proxy2.de
 * Last Modified: Mon, 5 June 2006 08:47:30 GMT
 * ----------------------------------------------
 */

class gb_admin
{

   var $db;
   var $gbsession;
   var $SELF;
   var $uid;
   var $VARS;
   var $table;
   var $ISINCLUDED;
   var $GBLOGOUT;


   function gb_admin($gbsession,$uid)
   {
      global $_SERVER;
      $this->gbsession = $gbsession;
      $this->uid = $uid;
      $this->SELF = basename($_SERVER['PHP_SELF']);
   }

   function get_updated_vars()
   {
      $this->db->query("SELECT * FROM ".$this->table['cfg']);
      $this->VARS = $this->db->fetch_array($this->db->result);
      $this->db->free_result($this->db->result);
   }

   function NoCacheHeader()
   {
      header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
      header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . 'GMT');
      header('Cache-Control: no-cache, must-revalidate');
      header('Pragma: no-cache');
   }

   function show_panel($panel)
   {
      global $smilie_list, $smilie_data;
      $this->NoCacheHeader();
      include_once './admin/panel_'.$panel.'.php';
      include_once './admin/footer.inc.php';
   }
   
   function scan_templates_dir()
   {
      $template_list = '';
      chdir('./templates/classic');
      $hnd = opendir('.');
      while ($file = readdir($hnd))
      {
         if(is_file($file))
         {
            if ($file != '.' && $file != '..')
            {
               if (preg_match('/\.tpl$/',$file))
               {
                  $template_list[] = $file;
               }
            }
         }
      }
      closedir($hnd);
      if (isset($template_list))
      {
         asort($template_list);
      }
      chdir('../../');
      return $template_list; 
   }     

   function scan_smilie_dir()
   {
      $smilies = '';
      chdir('./img/smilies');
      $hnd = opendir('.');
      while ($file = readdir($hnd))
      {
         if(is_file($file))
         {
            if ($file != '.' && $file != '..')
            {
               if (preg_match('/\.gif|\.jpg|\.png|\.jpeg|\.bmp/i',$file))
               {
                  $smilie_list[] = $file;
               }
            }
         }
      }
      closedir($hnd);
      if (isset($smilie_list))
      {
         asort($smilie_list);
         for ($i=0;$i<sizeof($smilie_list);$i++)
         {
            $size = GetImageSize($smilie_list[$i]);
            if (is_array($size))
            {
               $smilies[$smilie_list[$i]] = '<img src="img/smilies/'.$smilie_list[$i].'" '.$size[3].'>';
            }
         }
      }
      chdir('../../');
      return $smilies;
   }

   function show_entry($tbl = 'gb', $rid = '')
   {
      global $entry, $record, $GB_UPLOAD;
      $entry = intval($entry);
      $record = intval($record);
      $rid = intval($rid);
      $entry = (!empty($rid)) ? $rid : $entry; 
      if ($tbl == 'priv')
      {
         $gb_tbl = $this->table['priv'];
         $book_id = 1;
      }
      else
      {
         $gb_tbl = $this->table['data'];
         $tbl = 'gb';
         $book_id = 2;
      }
      $entries_per_page = intval($this->VARS['entries_per_page']);
      if(!isset($entry))
      {
         $entry = 0;
      }
      if(!isset($record))
      {
         $record = 0;
      }
      $next_page = $entry+$entries_per_page;
      $prev_page = $entry-$entries_per_page;
      $this->db->query("select count(*) total from $gb_tbl");
      $this->db->fetch_array($this->db->result);
      $total = $this->db->record['total'];
      if ($record > 0 && $record <= $total)
      {
         $entry = $total-$record;
         $next_page = $entry+$entries_per_page;
         $prev_page = $entry-$entries_per_page;
      }
      $result = $this->db->query("select x.*, y.p_filename, y.width, y.height from $gb_tbl x left join ".$this->db->table['pics']." y on (x.id=y.msg_id and y.book_id=$book_id) order by id desc limit $entry, $entries_per_page");
      $img = new gb_image();
      $img->set_border_size($this->VARS['img_width'], $this->VARS['img_height']);
      $this->NoCacheHeader();
      include_once './admin/panel_easy.php';
      include_once './admin/footer.inc.php';
   }

//
// So they have chosen to delete something? Well lets delete it.
//

   function del_entry($entry_id,$tbl = 'gb')
   {
      global $GB_UPLOAD;
      $entry_id = intval($entry_id);
      switch ($tbl) {
      case 'gb' :
      $this->db->query("select p_filename from ".$this->table['pics']." WHERE (msg_id = '$entry_id' and book_id=2)");
      $result = $this->db->fetch_array($this->db->result);
      if ($result['p_filename'])
      {
         if (file_exists('./'.$GB_UPLOAD.'/'.$result['p_filename']))
         {
            unlink ('./'.$GB_UPLOAD.'/'.$result['p_filename']);
         }
         if (file_exists('./'.$GB_UPLOAD.'/t_'.$result['p_filename']))
         {
            unlink ('./'.$GB_UPLOAD.'/t_'.$result['p_filename']);
         }
      }
      $this->db->query("DELETE FROM ".$this->table['data']." WHERE (id = '$entry_id')");
      $this->db->query("DELETE FROM ".$this->table['com']." WHERE (id = '$entry_id')");
      $this->db->query("DELETE FROM ".$this->table['pics']." WHERE (msg_id = '$entry_id' and book_id=2)");
      break;

      case 'priv' :
         $this->db->query("select p_filename from ".$this->table['pics']." WHERE (msg_id = '$entry_id' and book_id=1)");
         $result = $this->db->fetch_array($this->db->result);
         if ($result['p_filename'])
         {
            if (file_exists('./'.$GB_UPLOAD.'/'.$result['p_filename']))
            {
               unlink ('./'.$GB_UPLOAD.'/'.$result['p_filename']);
            }
            if (file_exists('./'.$GB_UPLOAD.'/t_'.$result['p_filename']))
            {
               unlink ('./'.$GB_UPLOAD.'/t_'.$result['p_filename']);
            }
         }
         $this->db->query("DELETE FROM ".$this->table['priv']." WHERE (id = '$entry_id')");
         $this->db->query("DELETE FROM ".$this->table['pics']." WHERE (msg_id = '$entry_id' and book_id=1)");
         break;

      case 'com' :
         $this->db->query("DELETE FROM ".$this->table['com']." WHERE (com_id = '$entry_id')");
         break;

      case 'pubpics' :
         $this->db->query("select p_filename from ".$this->table['pics']." WHERE (msg_id = '$entry_id' and book_id=2)");
         $result = $this->db->fetch_array($this->db->result);
         if ($result['p_filename'])
         {
            if (file_exists('./'.$GB_UPLOAD.'/'.$result['p_filename']))
            {
               unlink ('./'.$GB_UPLOAD.'/'.$result['p_filename']);
            }
            if (file_exists('./'.$GB_UPLOAD.'/t_'.$result['p_filename']))
            {
               unlink ('./'.$GB_UPLOAD.'/t_'.$result['p_filename']);
            }
         }
         $this->db->query("DELETE FROM ".$this->table['pics']." WHERE (msg_id = '$entry_id' and book_id=2)");
         break;

      case 'privpics' :
         $this->db->query("select p_filename from ".$this->table['pics']." WHERE (msg_id = '$entry_id' and book_id=1)");
         $result = $this->db->fetch_array($this->db->result);
         if ($result['p_filename'])
         {
            if (file_exists('./'.$GB_UPLOAD.'/'.$result['p_filename']))
            {
               unlink ('./'.$GB_UPLOAD.'/'.$result['p_filename']);
            }
            if (file_exists('./'.$GB_UPLOAD.'/t_'.$result['p_filename']))
            {
               unlink ('./'.$GB_UPLOAD.'/t_'.$result['p_filename']);
            }
         }
         $this->db->query("DELETE FROM ".$this->table['pics']." WHERE (msg_id = $entry_id and book_id=1)");
         break;
      }
   }

//
// If we are moderating and have accepted the entry change it's status
//

   function accept_entry($entry_id,$tbl)
   {
      $entry_id = intval($entry_id);
      if ($tbl == 'com')
      {
         $this->db->query("UPDATE ".$this->table['com']." SET `comaccepted` = '1' WHERE com_id = $entry_id");
      }
      else
      {
         $this->db->query("UPDATE ".$this->table['data']." SET `accepted` = '1' WHERE id = $entry_id");
      }
   }
   
//
// We want to unaccept an entry (if that makes sense)
//

   function unaccept_entry($entry_id,$tbl)
   {
      $entry_id = intval($entry_id);
      if ($tbl == 'com')
      {
         $this->db->query("UPDATE ".$this->table['com']." SET `comaccepted` = '0' WHERE com_id = $entry_id");
      }
      else
      {
         $this->db->query("UPDATE ".$this->table['data']." SET `accepted` = '0' WHERE id = $entry_id");
      }
   }   

//
// If they have edited an entry we need to update it
//

   function update_record($entry_id,$tbl='gb')
   {
      global $_POST;
      if ($tbl == 'priv')
      {
         $gb_tbl = $this->table['priv'];
      }
      elseif ($tbl == 'com')
      {
         $gb_tbl = $this->table['com'];
      }
      else
      {
         $gb_tbl = $this->table['data'];
      }
      reset($_POST);
      if (!get_magic_quotes_gpc())
      {
         while (list($var, $value) = each($_POST))
         {
            $_POST[$var] = addslashes(trim($value));
         }
      }
      else
      {
         while (list($var, $value) = each($_POST))
         {
            $_POST[$var] = trim($value);
         }
      }
      if ($tbl == 'com')
      {
         $sqlquery = "UPDATE $gb_tbl SET name='$_POST[name]', comments='$_POST[comments]' WHERE (com_id = '$entry_id')";
      }
      else
      {
         if (!preg_match('/.+@[-a-z0-9_]+/i', $_POST['email'])) 
         {
            $_POST['email'] = '';
         }
         if (!preg_match('@^http://[-a-z0-9_]+@i', $_POST['url']))
         {
            $_POST['url'] = '';
         }
         $sqlquery = "UPDATE $gb_tbl set name='$_POST[name]', email='$_POST[email]', gender='$_POST[gender]', url='$_POST[url]', location='$_POST[location]', ";
         $sqlquery .= "host='$_POST[host]', browser='$_POST[browser]', comment='$_POST[comment]', icq='$_POST[icq]', aim='$_POST[aim]', msn='$_POST[msn]', yahoo='$_POST[yahoo]', skype='$_POST[skype]' WHERE (id = '$entry_id')";
      }
      $this->db->query($sqlquery);
   }

//
// Here we display the relevant form and data if they want to edit an entry
//

   function show_form($entry_id,$tbl = 'gb',$rid ='')
   {
      global $record;
      $record = intval($record);
      $rid = intval($rid);
      if ($tbl == 'priv')
      {
         $gb_tbl = $this->table['priv'];
         $this->db->query("select * from ".$this->table['priv']." where (id = '$entry_id')");
      }
      elseif ($tbl == 'com')
      {
         $gb_tbl = $this->table['com'];
         $this->db->query("select * from ".$this->table['com']." where (com_id = '$entry_id')");
      }
      else
      {
         $gb_tbl = $this->table['data'];
         $this->db->query("select * from ".$this->table['data']." where (id = '$entry_id')");
      }
      $row = $this->db->fetch_array($this->db->result);
      for(reset($row); $key=key($row); next($row))
      {
         $row[$key] = htmlspecialchars($row[$key]);
      }
      $this->NoCacheHeader();
      if ($tbl == 'com')
      {
         include_once './admin/panel_comedit.php';
      }
      else
      {
         include_once './admin/panel_edit.php';
      }
      include_once './admin/footer.inc.php';
   }
   
//
// Simple function to add an IP to the banned IP list
//

   function ban_ip($ip)
   {
      global $GB_TBL;
      if (preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/',$ip))
      {
         $this->db->query("INSERT INTO ".$GB_TBL['ban']." (ban_ip) VALUES('$ip')");
      }   
   }

//
// If they want to edit a template we need to show them the templates contents
// or replace the contents if they have sent us something to save
//

   function edit_template($tpl_name,$tpl_save,$included)
   {
      global $_POST, $template_list;
      $tpl_name = preg_replace('#(\.\.)|(/)|(\\\)#', '', $tpl_name);
      $this->NoCacheHeader();
      $can_edit = '';
      $button_status = '';
      $filename = './templates/classic/'.$tpl_name;
      if (file_exists($filename) && $tpl_name != '')
      {
         if ($tpl_save == 'update')
         {
            if (get_magic_quotes_gpc())
            {
               $_POST['gb_template'] = stripslashes($_POST['gb_template']);
            }
            $fd = fopen ($filename, 'w');
            fwrite($fd,$_POST['gb_template']);
            $gb_template = $_POST['gb_template'];
            fclose ($fd);
         }
         else
         {
            if(function_exists('file_get_contents'))
            {
               $gb_template = file_get_contents($filename);
            }
            // Legacy support for people with useless hosts
            elseif(filesize($filename) > 0)
            {
               $fd = fopen ($filename, "r");
               $gb_template = fread ($fd, filesize ($filename));
            }
            else
            {
               $gb_template = '';
            }
            if (is_writable($filename))
            {
               $can_edit = '';
               $button_status = '';
            }
            else
            {
               $can_edit = '<br><font color="red">YOU CANNOT EDIT THIS FILE UNLESS YOU GIVE WRITE PERMISSION TO THE SERVER</font>';
               $button_status = ' disabled';
            }
            
         }
      }
      else
      {
         $gb_template ='';
      }
      include_once './admin/panel_template.php';
      include_once './admin/footer.inc.php';
   }

//
// So they want to see what settings they have? well lets show them
//

   function show_settings($cat)
   {
      global $GB_TMP, $GB_UPLOAD;
      $this->db->query("SELECT * FROM ".$this->table['words']);
      while ($this->db->fetch_array($this->db->result))
      {
         $badwords[] = $this->db->record['word'];
      }
      $this->db->free_result($this->db->result);
      $this->db->query("SELECT * FROM ".$this->table['ban']);
      while ($this->db->fetch_array($this->db->result))
      {
         $banned_ips[] = $this->db->record['ban_ip'];
      }
      $this->db->free_result($this->db->result);
      $this->db->query("SELECT * FROM ".$this->table['auth']." WHERE ID=$this->uid");
      $row = $this->db->fetch_array($this->db->result);
      $this->NoCacheHeader();
      if ($cat == 'general')
      {
         include_once './admin/panel_main.php';
      }
      elseif ($cat == 'style')
      {
         include_once './admin/panel_style.php';
      }
      elseif ($cat == 'pwd')
      {
         include_once './admin/panel_pwd.php';
      }
      include_once './admin/footer.inc.php';
   }
   
}

?>