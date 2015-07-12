<?php 
/*
 * ----------------------------------------------
 * Lazarus Guestbook
 * by Stewart Souter
 * URL: www.carbonize.co.uk 
 * Based on Advanced Guestbook 2.3.x (PHP/MySQL)
 * Copyright (c)2001 Chi Kien Uong
 * URL: http://www.proxy2.de
 * Last Modified: Sat, 8 July 2006 17:55:58 GMT
 * ----------------------------------------------
 */
 
$include_path = dirname(__FILE__);
include_once $include_path.'/admin/config.inc.php';
include_once $include_path.'/lib/mysql.class.php';
include_once $include_path.'/lib/image.class.php';
include_once $include_path.'/lib/template.class.php';
include_once $include_path.'/lib/session.class.php';
include_once $include_path.'/lib/admin.class.php';

if (!isset($PHP_SELF))
{
	$PHP_SELF = $_SERVER['PHP_SELF'];
	if (isset($_GET))
	{
		while (list($name, $value) = each($_GET))
		{
			$$name = $value;
		}
	}
	if (isset($_POST))
	{
		while (list($name, $value) = each($_POST))
		{
			$$name = $value;
		}
	}
	if (isset($_COOKIE))
	{
		while (list($name, $value) = each($_COOKIE))
		{
			$$name = $value;
		}
	}
}
if (IS_MODULE) 
{
   $password = urldecode($password);
   $username = urldecode($username);
}
$gb_auth = new gb_session($include_path);
$AUTH = $gb_auth->checkSessionID();
$VARS = $gb_auth->fetch_array($gb_auth->query("SELECT * FROM ".$gb_auth->table['cfg']));
$GB_PG['base_url'] = $VARS['base_url'];
$gb_auth->free_result($gb_auth->result);
$template = new gb_template($include_path);
$included = (isset($included)) ? $included : '';
if (isset($_COOKIE['lang']) && !empty($_COOKIE['lang']))
{
	$template->set_lang($_COOKIE['lang']);
}
else
{
	$template->set_lang($VARS['lang']);
}
$LANG = $template->get_content();

if (!$AUTH)
{
	define('IS_INCLUDE', false);
	$message = (isset($username) || isset($password)) ? $LANG['PassMess2'] : $LANG['PassMess1'];
	if ($included == 1)
	{
		$ISINCLUDED = "\n<input type=\"hidden\" name=\"included\" value=\"1\">";
		$LINKS = '';
	}
	else
	{
		$ISINCLUDED = '';
		$LINKS = "<b><img src=\"$GB_PG[base_url]/img/return.gif\" width=\"10\" height=\"10\"> <a href=\"$GB_PG[index]\">$LANG[BookMess4]</a> | <img src=\"$GB_PG[base_url]/img/sign.gif\" width=\"9\" height=\"12\"> <a href=\"$GB_PG[addentry]\" rel=\"nofollow\">$LANG[BookMess3]</a></b>";
	}
	$EMAILJS = '';
   $enter_html = '';
	eval("\$enter_html = \"".$template->get_template('header')."\";");
	eval("\$enter_html .= \"".$template->get_template('admin_enter')."\";");
	eval("\$enter_html .= \"".$template->get_template('footer')."\";");
	echo $enter_html;
}
else
{
	$action = (!isset($action)) ? '' : $action;
	$rid = (!isset($rid)) ? '' : $rid;
	$admin = new gb_admin($AUTH['session'],$AUTH['uid']);
	$admin->VARS =& $VARS;
	$admin->db =& $gb_auth;
	$admin->table =& $GB_TBL;
	if ($included == 1)
	{
		$admin->ISINCLUDED = '&amp;included=1';
		$admin->HIDDENINC = '<input type="hidden" name="included" value="1">';
	}
	else
	{
		$admin->ISINCLUDED = '';
		$admin->HIDDENINC = '';
	}

	switch ($action)
	{

		case 'accept':
			$admin->accept_entry($id,$tbl);
			$admin->show_entry($tbl,$rid);
			break;
			
		case 'unaccept':
			$admin->unaccept_entry($id,$tbl);
			$admin->show_entry($tbl,$rid);
			break;			

		case 'show':
			$admin->show_entry($tbl,$rid);
			break;

		case 'del':
			$admin->del_entry($id,$tbl);
			$admin->show_entry($tbl,$rid);
			break;

		case 'edit':
			$admin->show_form($id,$tbl,$rid);
			break;

		case 'info':
			$admin->show_panel("info");
			break;
			
		case 'multi':
		   if (isset($comarray))
		   {
		      foreach ($comarray as $key => $value) 
            {
               if (isset($multidelete))
               {
        	         $admin->del_entry($key,'com');
        	      }
        	      elseif (isset($multiaccept))
        	      {
        	         $admin->accept_entry($key,'com');
        	      }
        	      elseif (isset($multiunaccept))
        	      {
        	         $admin->unaccept_entry($key,'com');
        	      }
            }
		   }
		   if (isset($entryarray))
		   {
		      foreach ($entryarray as $key => $value) 
            {
               if (isset($multidelete))
               {
        	         $admin->del_entry($key,$tbl);
        	      }
        	      elseif (isset($multiaccept))
        	      {
        	         $admin->accept_entry($key,$tbl);
        	      }
        	      elseif (isset($multiunaccept))
        	      {
        	         $admin->unaccept_entry($key,$tbl);
        	      }
            }
		   }
		   $admin->show_entry($tbl,$rid);
		   break;

		case 'smilies':
			if (isset($scan_dir))
			{
				$smilie_list = $admin->scan_smilie_dir();
			}
			if (isset($del_smilie))
			{
				$gb_auth->query("DELETE FROM ".$GB_TBL['smile']." WHERE id='$del_smilie'");
			}
			if (isset($edit_smilie))
			{
				if (isset($s_code) && isset($s_emotion))
				{
					if (!get_magic_quotes_gpc())
					{
						$s_code = addslashes($s_code);
						$s_emotion = addslashes($s_emotion);
					}
					$gb_auth->query("UPDATE ".$GB_TBL['smile']." SET s_code='$s_code', s_emotion='$s_emotion' WHERE id='$edit_smilie'");
				}
				$gb_auth->query("SELECT * FROM ".$GB_TBL['smile']." WHERE id='$edit_smilie'");
				if ($gb_auth->fetch_array($gb_auth->result))
				{
					$smilie_data = $gb_auth->record;
				}
			}
			if (isset($add_smilies))
			{
				if(isset($new_smilie) && isset($new_emotion))
				{
					for(reset($new_smilie); $key=key($new_smilie); next($new_smilie))
					{
						if (!empty($new_emotion[$key]) && !empty($new_smilie[$key]))
						{
							$size = GetImageSize("./img/smilies/$key");
							$gb_auth->query("INSERT INTO ".$GB_TBL['smile']." (s_code,s_filename,s_emotion,width,height) VALUES('".$new_smilie[$key]."','$key','".$new_emotion[$key]."','".$size[0]."','".$size[1]."')");
						}
					}
				}
			}
			$admin->show_panel('smilies');
			break;

		case 'update':
			$admin->update_record($id,$tbl);
			$admin->show_entry($tbl,$rid);
			break;

		case 'template':
			$tpl_name = (isset($tpl_name)) ? $tpl_name : '';
			$save = (isset($save)) ? $save : '';
			$template_list = $admin->scan_templates_dir();
			$admin->edit_template($tpl_name,$save,$included);
			break;
			
		case 'bandel':
		   $admin->ban_ip($ip);
		   $admin->del_entry($id,$tbl);
			$admin->show_entry($tbl,$rid);
		   break;
		   
		case 'banip':
		   $admin->ban_ip($ip);
			$admin->show_entry($tbl,$rid);
		   break;		   

		case 'save':
			if ($panel == 'general')
			{
				if ($allow_img == 1)
				{
					$upload_dir = $include_path."/$GB_UPLOAD";
					$test = @is_dir($upload_dir);
					if (!$test)
					{
						@mkdir($upload_dir, 0777);
					}
				}
				$notify_private = (isset($notify_private)) ? 1 : 0;
				$notify_admin = (isset($notify_admin)) ? 1 : 0;
				$notify_admin_com =(isset($notify_admin_com)) ? 1 : 0;
				$notify_guest = (isset($notify_guest)) ? 1 : 0;
				$captcha_noise = (isset($captcha_noise)) ? 1 : 0;
				$captcha_grid = (isset($captcha_grid)) ? 1 : 0;
				$captcha_grey = (isset($captcha_grey)) ? 1 : 0;
				$captcha_greytext = (isset($captcha_greytext)) ? 1 : 0;
				$always_bookemail = (isset($always_bookemail)) ? 1 : 0;
				$thumbnail = (isset($thumbnail)) ? 1 : 0;
				$use_regex = (isset($use_regex)) ? 1 : 0;
				$html_email = (isset($html_email)) ? 1 : 0;
				$allow_imgagcode = (isset($allow_imgagcode)) ? 1 : 0;
				$allow_urlagcode = (isset($allow_urlagcode)) ? 1 : 0;
				$allow_emailagcode = (isset($allow_emailagcode)) ? 1 : 0;
				$disablecomments = (isset($disablecomments)) ? 1 : 0;
				$sqlquery= "UPDATE ".$GB_TBL['cfg']." set agcode='$agcode', allow_html='$allow_html', smilies='$smilies', require_email='$require_email', use_regex='$use_regex', post_time_max='$post_time_max', post_time_min='$post_time_min', base_url='$base_url', ";
				$sqlquery.="admin_mail='$admin_mail', notify_private='$notify_private', notify_admin='$notify_admin', notify_guest='$notify_guest', notify_mes='$notify_mes', entries_per_page='$entries_per_page', antibottest='$antibottest', bottestquestion='$bottestquestion', bottestanswer='$bottestanswer', ";
				$sqlquery.="show_ip='$show_ip', lang='$lang', min_text='$min_text', max_text='$max_text', max_word_len='$max_word_len', require_checking='$require_checking', notify_admin_com='$notify_admin_com', require_comchecking='$require_comchecking', charset='$charset', disablecomments='$disablecomments', ";
				$sqlquery.="censor='$censor', flood_check='$flood_check', banned_ip='$banned_ip', flood_timeout='$flood_timeout', allow_icq='$allow_icq', allow_private='$allow_private', encrypt_email='$encrypt_email', allow_url='$allow_url', html_email='$html_email', allow_loc='$allow_loc', ";
				$sqlquery.="captcha_noise='$captcha_noise', captcha_grid='$captcha_grid', captcha_grey='$captcha_grey', captcha_greytext='$captcha_greytext', book_mail='$book_mail', always_bookemail='$always_bookemail', agcode_img_width='$agcode_img_width', agcode_img_height='$agcode_img_height', ";
				$sqlquery.="allow_aim='$allow_aim', allow_gender='$allow_gender', allow_img='$allow_img', max_img_size='$max_img_size', allow_yahoo='$allow_yahoo', allow_skype='$allow_skype', allow_msn='$allow_msn', allow_urlagcode='$allow_urlagcode', allow_imgagcode='$allow_imgagcode', allow_emailagcode='$allow_emailagcode', ";
				$sqlquery.="need_pass='$need_pass', comment_pass='$comment_pass', img_width='$img_width', img_height='$img_height', thumbnail='$thumbnail', thumb_min_fsize='$thumb_min_fsize', allowed_tags='$allowed_tags', hide_comments='$hide_comments', com_question='$com_question' WHERE (config_id = '1')";
				$gb_auth->query($sqlquery);
				$badwords=trim($badwords);
				$badwords=str_replace("\r", '', $badwords);
				if (!get_magic_quotes_gpc())
				{
					$badwords = stripslashes($badwords);
				}
				$word_array = explode("\n", $badwords);
				if (sizeof($word_array) > 0)
				{
					$sqlquery= "DELETE from ".$GB_TBL['words'];
					$gb_auth->query($sqlquery);
					for($i=0;$i<sizeof($word_array);$i++)
					{
						if (trim($word_array[$i]) != '') 
						{
							$sqlquery= "INSERT INTO ".$GB_TBL['words']." (word) VALUES('$word_array[$i]')";
							$gb_auth->query($sqlquery);
						}
					}
				}
				$banned_ips=trim($banned_ips);
				$banned_ips=str_replace("\r", '', $banned_ips);
				$ip_array = explode("\n", $banned_ips);
				if (sizeof($ip_array) > 0)
				{
					$sqlquery= "DELETE from ".$GB_TBL['ban'];
					$gb_auth->query($sqlquery);
					for($i=0;$i<sizeof($ip_array);$i++)
					{
						if (ereg("^[0-9]{1,3}\\.[0-9]{1,3}\\.",$ip_array[$i]))
						{
							$sqlquery= "INSERT INTO ".$GB_TBL['ban']." (ban_ip) VALUES('$ip_array[$i]')";
							$gb_auth->query($sqlquery);
						}
					}
				}
				$admin->get_updated_vars();
				$admin->show_settings('general');
			}
			elseif ($panel == 'style')
			{
				$sqlquery = "UPDATE ".$GB_TBL['cfg']." set pbgcolor='$pbgcolor', text_color='$text_color', link_color='$link_color', width='$width', ad_pos='$ad_pos', ad_code='$ad_code', ";
				$sqlquery .= "tb_font_1='$tb_font_1', tb_font_2='$tb_font_2', font_face='$font_face', tb_hdr_color='$tb_hdr_color', tb_bg_color='$tb_bg_color', tb_text='$tb_text', ";
				$sqlquery .= "tb_color_1='$tb_color_1', tb_color_2='$tb_color_2', dformat='$dformat', tformat='$tformat', offset='$offset' WHERE (config_id = '1')";
				$gb_auth->query($sqlquery);
				$admin->get_updated_vars();
				$admin->show_settings('style');
			}
			elseif ($panel == 'password')
			{
			   if (trim($NEWadmin_pass) == '') 
            {
				  $sqlquery= "UPDATE ".$GB_TBL['auth']." set username='$NEWadmin_name' WHERE (ID = '$uid')";
				}
				else
				{              
              $sqlquery= "UPDATE ".$GB_TBL['auth']." set username='$NEWadmin_name', password=PASSWORD('$NEWadmin_pass') WHERE (ID = '$uid')";
            }
				$gb_auth->query($sqlquery);
				$admin->get_updated_vars();
				$admin->show_settings('pwd');
			}
			else
			{
				$admin->show_panel();
			}
			break;

		case 'settings':
			if ($panel == 'general')
			{
				$admin->show_settings('general');
			}
			elseif ($panel == 'style')
			{
				$admin->show_settings('style');
			}
			elseif ($panel == 'pwd')
			{
				$admin->show_settings('pwd');
			}
			else
			{
				$admin->show_panel();
			}
			break;

		case 'logout':
			$gb_auth->generateNewSessionID($uid);
			$message = $LANG['PassMess1'];
			if (IS_MODULE && eregi("modules",$_SERVER['PHP_SELF']))
			{
				$ModName = basename(dirname( __FILE__ ));
				$base_url = "../../modules.php?op=modload&name=$ModName&file=index";
				header("Location: $base_url");
				exit();
			}
			if ($included == 1)
			{
				die ('Successfully logged out. Click <a href="javascript:window.close()">HERE</a> to close this window.');
			}
			header("Location: $GB_PG[index]");
			break;

		default:
			$admin->show_panel("intro");
			break;
	}

}

?>