<?php 
/*
 * ----------------------------------------------
 * Lazarus Guestbook
 * by Stewart Souter
 * URL: www.carbonize.co.uk 
 * Based on Advanced Guestbook 2.3.x (PHP/MySQL)
 * Copyright (c)2001 Chi Kien Uong
 * URL: http://www.proxy2.de
 * Last Modified: 15th October 2005
 * ----------------------------------------------
 */
 
define('IS_INCLUDE', false);

$include_path = dirname(__FILE__);
include_once $include_path.'/admin/config.inc.php';
include_once $include_path.'/lib/mysql.class.php';
include_once $include_path.'/lib/image.class.php';
include_once $include_path.'/lib/template.class.php';
include_once $include_path.'/lib/vars.class.php';
include_once $include_path.'/lib/add.class.php';

$gb_post = new addentry($include_path);

$antispam = $gb_post->db->VARS['antispam_word'];

//
// Here we just check if anything was submitted and if so handle it
//

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
   $gb_post->gender = (isset($_POST['gb_gender'])) ? $_POST['gb_gender'] : '';
   $gb_post->bottest = (isset($_POST['gb_bottest'])) ? $_POST['gb_bottest'] : '';
   $gb_post->timehash = (isset($_POST['gb_timehash'])) ? $_POST['gb_timehash'] : '';
   $gb_post->userfile = (isset($_FILES['userfile']['tmp_name']) && $_FILES['userfile']['tmp_name'] != '') ? $_FILES : '';
   $gb_post->user_img = (isset($_POST['gb_user_img'])) ? $_POST['gb_user_img'] : '';
   $gb_post->preview = (isset($_POST['gb_preview'])) ? 1 : 0;
   $gb_post->private = (isset($_POST['gb_private'])) ? 1 : 0;
   echo $gb_post->process($_POST['agb_action'.$antispam]);
}
else
{
   echo $gb_post->process();  // nothing submitted so display the form
}

?>