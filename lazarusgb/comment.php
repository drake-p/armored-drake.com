<?php 
/*
 * ----------------------------------------------
 * Lazarus Guestbook
 * by Stewart Souter
 * URL: www.carbonize.co.uk 
 * Based on Advanced Guestbook 2.3.2 (PHP/MySQL)
 * Copyright (c)2001 Chi Kien Uong
 * URL: http://www.proxy2.de
 * Last Modified: Fri, 14 July 2006 16:54:35 GMT
 * ----------------------------------------------
 */
 
define('IS_INCLUDE', false); 
 
$include_path = dirname(__FILE__);
include_once $include_path.'/admin/config.inc.php';
include_once $include_path.'/lib/mysql.class.php';
include_once $include_path.'/lib/image.class.php';
include_once $include_path.'/lib/template.class.php';
include_once $include_path.'/lib/vars.class.php';
include_once $include_path.'/lib/comment.class.php';

//
// Convert any data into usable variables
//

$gb_com = new gb_comment($include_path);
if ($gb_com->db->VARS['disablecomments'] == 1)
{
   header("Location: $GB_PG[index]");
}
else
{
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
}

?>