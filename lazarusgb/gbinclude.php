<?php 
/*
 * ----------------------------------------------
 * Lazarus Guestbook
 * by Stewart Souter
 * URL: www.carbonize.co.uk 
 * Based on Advanced Guestbook 2.3.x (PHP/MySQL)
 * Copyright (c)2001 Chi Kien Uong
 * URL: http://www.proxy2.de
 * Last Modified: Fri, 14 July 2006 16:54:49 GMT
 * ----------------------------------------------
 */
 
if ((strpos(' '.$_SERVER['REQUEST_URI'], 'gbinclude.php')) || (strpos(' '.$_SERVER['PHP_SELF'], 'gbinclude.php')))
{
   die ("You cannot access this file directly.<br>\nThis file is designed to make integrating the guestbook into your site easier.<br>\nPlace the code you will find in the Style section of the admin into a webpage where you want the guestbook to appear.<br>\nPlease remember that the page you place the include code on must end with the .php extension and not .htm nor .html");
}

$include_path = dirname(__FILE__);
include_once $include_path.'/admin/config.inc.php';
include_once $include_path.'/lib/mysql.class.php';
include_once $include_path.'/lib/image.class.php';
include_once $include_path.'/lib/template.class.php';

define('IS_INCLUDE', true);

ob_start();
$GB_SELF = $_SERVER['PHP_SELF'];
$GB_PG['index']= $GB_SELF;
$GB_PG['comment']  = $GB_SELF.'?agbook=comment';
$GB_PG['addentry'] = $GB_SELF.'?agbook=addentry';

$agbook = (isset($_GET['agbook'])) ? $_GET['agbook'] : '';

function display_entries()
{
   global $include_path, $GB_PG;
   include_once $include_path.'/lib/gb.class.php';
   $gb = new guestbook($include_path);
   $GB_PG['base_url'] = $gb->db->VARS['base_url'];
   $GB_PG['admin']= $GB_PG['base_url'].'/admin.php?included=1" target="_blank';
   $entry = (isset($_GET['entry'])) ? $_GET['entry'] : 0;
   $entry = (isset($_POST['entry'])) ? $_POST['entry'] : $entry;
   echo $gb->show_entries($entry);
   $gb->db->close_db();
}

switch ($agbook)
{

   case 'comment':
      include_once $include_path.'/lib/vars.class.php';
      include_once $include_path.'/lib/comment.class.php';
      $gb_com = new gb_comment($include_path);
      if ($gb_com->db->VARS['disablecomments'] == 1)
      {
         display_entries();
         break;
      }      
      $GB_PG['base_url'] = $gb_com->db->VARS['base_url'];
      $GB_PG['admin']= $GB_PG['base_url'].'/admin.php?included=1" target="_blank';
      $antispam = $gb_com->db->VARS['antispam_word'];
      $gb_com->id = (isset($_GET['gb_id'])) ? $_GET['gb_id'] : '';
      $gb_com->id = (isset($_POST['gb_id'])) ? $_POST['gb_id'] : $gb_com->id;
      $gb_com->comment = (isset($_POST['gb_comment'])) ? $_POST['gb_comment'] : '';
      $gb_com->timehash = (isset($_POST['gb_timehash'])) ? $_POST['gb_timehash'] : '';
      $gb_com->user = (isset($_POST['gb_user'])) ? $_POST['gb_user'] : '';
      $gb_com->bottest = (isset($_POST['gb_bottest'])) ? $_POST['gb_bottest'] : '';
      $gb_com->pass_comment = (isset($_POST['pass_comment'])) ? $_POST['pass_comment'] : '';
      $gb_action = (isset($_POST['gb_action'.$antispam])) ? $_POST['gb_action'.$antispam] : '';
      $gb_com->comment_action($gb_action);
      $gb_com->db->close_db();
      break;

   case 'addentry':
      include_once $include_path.'/lib/vars.class.php';
      include_once $include_path.'/lib/add.class.php';
      $gb_post = new addentry($include_path);
      $GB_PG['admin']= $gb_post->db->VARS['base_url'].'/admin.php?included=1" target="_blank';
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
         $gb_post->userfile = (isset($_FILES['userfile']['tmp_name']) && $_FILES['userfile']['tmp_name'] != '') ? $_FILES : '';
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

?>