<?php 
/*
 * ----------------------------------------------
 * Lazarus Guestbook
 * by Stewart Souter
 * URL: www.carbonize.co.uk 
 * Based on Advanced Guestbook 2.3.x (PHP/MySQL)
 * Copyright (c)2001 Chi Kien Uong
 * URL: http://www.proxy2.de
 * Last Modified: Fri, 14 July 2006 16:54:42 GMT
 * ----------------------------------------------
 */

$include_path = dirname(__FILE__);
include_once $include_path.'/admin/config.inc.php';
include_once $include_path.'/lib/mysql.class.php';
include_once $include_path.'/lib/image.class.php';
include_once $include_path.'/lib/template.class.php';

define('IS_INCLUDE', false);

//
// Is the guestbook being used as a CMS module?
//

if (IS_MODULE)
{
   if (!eregi("modules.php", $_SERVER['PHP_SELF']))
   {
      die ("You can't access this file directly...");
   }
   $ModName = basename(dirname( __FILE__ ));

   ob_start();
   include('header.php');

   $GB_SELF = basename($_SERVER['PHP_SELF']);
   $GB_PG['index']    = $GB_SELF.'?op=modload&name='.$ModName.'&amp;file=index';
   $GB_PG['admin']    = $GB_SELF.'?op=modload&name='.$ModName.'&amp;file=index&amp;agbook=admin';
   $GB_PG['comment']  = $GB_SELF.'?op=modload&name='.$ModName.'&amp;file=index&amp;agbook=comment';
   $GB_PG['addentry'] = $GB_SELF.'?op=modload&name='.$ModName.'&amp;file=index&amp;agbook=addentry';

   $agbook = (isset($_GET['agbook'])) ? $_GET['agbook'] : '';
   
   function display_entries()
   {
      global $include_path, $GB_PG, $ModName;
      include_once $include_path.'/lib/gb.class.php';
      $gb = new guestbook($include_path);
      $GB_PG['base_url'] = $gb->db->VARS['base_url'].'/modules/'.$ModName;
      $entry = (isset($_GET['entry'])) ? $_GET['entry'] : 0;
      $entry = (isset($_POST['entry'])) ? $_POST['entry'] : $entry;
      echo $gb->show_entries($entry);
      $gb->db->close_db();
   }   

   switch ($agbook)
   {

      case 'admin':
         include_once $include_path.'/lib/session.class.php';
         $gb_auth = new gb_session($include_path);
         $AUTH = $gb_auth->checkSessionID();
         $VARS = $gb_auth->fetch_array($gb_auth->query("SELECT * FROM ".$gb_auth->table['cfg']));
         $GB_PG['base_url'] = $VARS['base_url'].'/modules/'.$ModName;
         $gb_auth->free_result($gb_auth->result);
         $template = new gb_template($include_path);
         if (isset($_COOKIE['lang']) && !empty($_COOKIE['lang']))
         {
            $template->set_lang($_COOKIE['lang']);
         }
         else
         {
            $template->set_lang($VARS['lang']);
         }
         $LANG = $template->get_content();
         $gb_auth->close_db();
         if (!$AUTH)
         {
            $LINKS = "<b><img src=\"$GB_PG[base_url]/img/return.gif\" width=\"10\" height=\"10\"> <a href=\"$GB_PG[index]\">$LANG[BookMess4]</a> | <img src=\"$GB_PG[base_url]/img/sign.gif\" width=\"9\" height=\"12\"> <a href=\"$GB_PG[addentry]\" rel=\"nofollow\">$LANG[BookMess3]</a></b>";
            $message = (isset($username) || isset($password)) ? $LANG['PassMess2'] : $LANG['PassMess1'];
            eval("\$enter_html = \"".$template->get_template('header')."\";");
            eval("\$enter_html .= \"".$template->get_template('admin_enter')."\";");
            eval("\$enter_html .= \"".$template->get_template('footer')."\";");
            echo $enter_html;
         }
         else
         {
            $GB_PG['admin'] = $GB_PG['base_url'].'/admin.php?username='.urlencode($username).'&password='.urlencode($password).'&enter=1';
            header('Location: '.$GB_PG[admin]);
            exit();
         }
         break;

      case 'comment':
         include_once $include_path.'/lib/vars.class.php';
         include_once $include_path.'/lib/comment.class.php';
         $gb_com = new gb_comment($include_path);
         if ($gb_com->db->VARS['disablecomments'] == 1)
         {
            display_entries();
            break;
         } 
         $GB_PG['base_url'] = $gb_com->db->VARS['base_url'].'/modules/'.$ModName;
         $antispam = $gb_com->db->VARS['antispam_word'];
         $gb_com->id = (isset($_GET['gb_id'])) ? $_GET['gb_id'] : '';
         $gb_com->id = (isset($_POST['gb_id'])) ? $_POST['gb_id'] : $gb_com->id;
         $gb_com->comment = (isset($_POST['gb_comment'])) ? $_POST['gb_comment'] : '';
         $gb_com->timehash = (isset($_POST['gb_timehash'])) ? $_POST['gb_timehash'] : '';
         $gb_com->bottest = (isset($_POST['gb_bottest'])) ? $_POST['gb_bottest'] : '';
         $gb_com->user = (isset($_POST['gb_user'])) ? $_POST['gb_user'] : '';
         $gb_com->pass_comment = (isset($_POST['pass_comment'])) ? $_POST['pass_comment'] : '';
         $gb_action = (isset($_POST['gb_action'.$antispam])) ? $_POST['gb_action'.$antispam] : '';
         $gb_com->comment_action($gb_action);
         $gb_com->db->close_db();
         break;

      case 'addentry':
         include_once $include_path.'/lib/vars.class.php';
         include_once $include_path.'/lib/add.class.php';
         $gb_post = new addentry($include_path);
         $GB_PG['base_url'] = $gb_post->db->VARS['base_url'].'/modules/'.$ModName;
         $antispam = $gb_post->db->VARS['antispam_word'];
         if (isset($_POST['agb_action'.$antispam]))
         {
            $gb_post->name = (isset($_POST['gb_name'])) ? $_POST['gb_name'] : '';
            $gb_post->email = (isset($_POST['gb_email'])) ? $_POST['gb_email'] : '';
            $gb_post->url = (isset($_POST['gb_url'])) ? $_POST['gb_url'] : '';
            $gb_post->comment = (isset($_POST['gb_comment'])) ? $_POST['gb_comment'] : '';
            $gb_post->location = (isset($_POST['gb_location'])) ? $_POST['gb_location'] : '';
            $gb_post->icq = (isset($_POST['gb_icq'])) ? $_POST['gb_icq'] : '';
            $gb_post->aim = (isset($_POST['gb_aim'])) ? $_POST['gb_aim'] : '';
            $gb_post->msn = (isset($_POST['gb_msn'])) ? $_POST['gb_msn'] : '';
            $gb_post->yahoo = (isset($_POST['gb_yahoo'])) ? $_POST['gb_yahoo'] : '';
            $gb_post->skype = (isset($_POST['gb_skype'])) ? $_POST['gb_skype'] : '';
            $gb_post->timehash = (isset($_POST['gb_timehash'])) ? $_POST['gb_timehash'] : '';
            $gb_post->bottest = (isset($_POST['gb_bottest'])) ? $_POST['gb_bottest'] : '';
            $gb_post->gender = (isset($_POST['gb_gender'])) ? $_POST['gb_gender'] : '';
            $gb_post->userfile = (isset($_FILES['userfile']['tmp_name']) && $_FILES['userfile']['tmp_name'] != "") ? $_FILES : '';
            $gb_post->user_img = (isset($_POST['gb_user_img'])) ? $_POST['gb_user_img'] : '';
            $gb_post->preview = (isset($_POST['gb_preview'])) ? 1 : 0;
            $gb_post->private = (isset($_POST['gb_private'])) ? 1 : 0;
            echo $gb_post->process($_POST['agb_action'.$antispam]);
         }
         else
         {
            echo $gb_post->process();
         }
         $gb_post->db->close_db();
         break;

      default:
         include_once $include_path.'/lib/vars.class.php';
         display_entries();
   }
   ob_end_flush();
   $base_path = dirname(dirname($include_path));
   chdir("$base_path");
   include($base_path.'/footer.php');

}
else
{
      include_once $include_path.'/lib/vars.class.php';
      include_once $include_path.'/lib/gb.class.php';
      $gb = new guestbook($include_path);
      $GB_PG['base_url'] = $gb->db->VARS['base_url'];
      $entry = (isset($_GET['entry'])) ? $_GET['entry'] : 0;
      $entry = (isset($_POST['entry'])) ? $_POST['entry'] : $entry;
      echo $gb->show_entries($entry);
}

?>