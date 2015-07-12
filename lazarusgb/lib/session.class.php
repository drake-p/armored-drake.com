<?php 
/* 
 * ----------------------------------------------
 * Lazarus Guestbook
 * by Stewart Souter
 * URL: www.carbonize.co.uk 
 * Based on Advanced Guestbook 2.3.2 (PHP/MySQL)
 * Copyright (c)2001 Chi Kien Uong
 * URL: http://www.proxy2.de
 * Last Modified: 25th March 2005
 * ----------------------------------------------
 */

class gb_session extends gbook_sql
{

   var $expire = 7200;
   var $include_path;
   var $table;

   function gb_session($path = '')
   {
      global $GB_TBL;
      $this->table =& $GB_TBL;
      $this->gbook_sql();
      $this->connect();
      $this->include_path = $path;
   }

   function isValidSession($gbsession,$user_id)
   {
      $this->query("SELECT session, last_visit from ".$this->table['auth']." WHERE session='".addslashes($gbsession)."' and ID='".intval($user_id)."'");
      $row = $this->fetch_array($this->result);
      if ($row)
      {
         return ($this->expire + $row['last_visit'] > time()) ? $row["session"] : false;
      }
      else
      {
         return false;
      }
   }

   function isValidUser($user_id)
   {
      $this->query("SELECT username FROM ".$this->table['auth']." WHERE ID='".intval($user_id)."'");
      $this->fetch_array($this->result);
      return ($this->record) ? true : false;
   }

   function changePass($user_id,$new_password)
   {
      $this->query("UPDATE ".$this->table['auth']." SET password=PASSWORD('$new_password') WHERE ID='".intval($user_id)."'");
      return ($this->record) ? true : false;
   }

   function generateNewSessionID($user_id)
   {
      srand((double)microtime()*1000000);
      $gbsession = md5 (uniqid (rand()));
      $timestamp = time();
      $this->query("UPDATE ".$this->table['auth']." SET session='$gbsession', last_visit='$timestamp' WHERE ID='".intval($user_id)."'");
      return $gbsession;
   }

   function checkPass($username,$password)
   {
      $this->query("SELECT ID FROM ".$this->table['auth']." WHERE username='".addslashes($username)."' and password=PASSWORD('".addslashes($password)."')");
      $this->fetch_array($this->result);
      return ($this->record) ? $this->record['ID'] : false;
   }

   function checkSessionID()
   {
      global $username, $password, $gbsession, $uid;
      if (isset($gbsession) && isset($uid)) 
      {
         return ($this->isValidSession($gbsession,$uid)) ? array('session' => $gbsession, 'uid' => $uid) : false;
      }
      elseif (isset($username) && isset($password))
      {
         if (get_magic_quotes_gpc())
         {
            $username = stripslashes($username);
            $password = stripslashes($password);
         }
         $ID = $this->checkPass($username,$password);
         if ($ID)
         {
            $gbsession = $this->generateNewSessionID($ID);
            return array('session' => $gbsession, 'uid' => $ID);
         }
         else
         {
            return false;
         }
      }
      else
      {
         return false;
      }
   }
}
?>