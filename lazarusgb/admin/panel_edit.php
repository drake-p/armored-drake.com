<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.1 Transitional//EN">
<html>
<head>
<title>Guestbook - Edit Entry</title>
<meta http-equiv="content-type" content="text/html; charset=<?php echo $this->VARS['charset']; ?>">
<style type="text/css">
<!--
body { background-color: #E5E5E5; color: #000; font-family: Verdana, Arial;}
.menu a, .menu a:visited, .menu a:active { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; font-weight: bold; color: blue;}
.input { border: 1px solid black; }
.lefttd { background-color: #EFEFEF; font-size: 12px; width: 25%; }
.righttd { background-color: #DEE3E7; font-size: 12px;}
.section { color: #FFF; background-color: navy; font-weight: bold; font-size: 14px; text-align: center; }
.subsection { color: #C0C0C0; font-weight: bold; font-size: 10px; }
td { font-family: Verdana, Arial; }
table { background-color: #000; border: 0; width: 100%; }
-->
</style>
</head>
<body>
<h4 align="center">G U E S T B O O K &nbsp; A D M I N</h4>
<hr>
<span class="menu"><a href="<?php echo $this->SELF.'?action=show&amp;tbl=priv&amp;gbsession='.$this->gbsession.'&amp;uid='.$this->uid.$this->ISINCLUDED; ?>">Private Messages</a> |
<a href="<?php echo $this->SELF.'?action=show&amp;tbl=gb&amp;gbsession='.$this->gbsession.'&amp;uid='.$this->uid.$this->ISINCLUDED; ?>">Easy Admin</a> |
<a href="<?php echo $this->SELF.'?action=settings&amp;panel=general&amp;gbsession='.$this->gbsession.'&amp;uid='.$this->uid.$this->ISINCLUDED; ?>">General Settings</a> |
<a href="<?php echo $this->SELF.'?action=settings&amp;panel=style&amp;gbsession='.$this->gbsession.'&amp;uid='.$this->uid.$this->ISINCLUDED; ?>">Style</a>
| <a href="<?php echo $this->SELF.'?action=template&amp;gbsession='.$this->gbsession.'&amp;uid='.$this->uid.$this->ISINCLUDED; ?>">Templates</a>
| <a href="<?php echo $this->SELF.'?action=smilies&amp;gbsession='.$this->gbsession.'&amp;uid='.$this->uid.$this->ISINCLUDED; ?>">Smilies</a>
| <a href="<?php echo $this->SELF.'?action=settings&amp;panel=pwd&amp;gbsession='.$this->gbsession.'&amp;uid='.$this->uid.$this->ISINCLUDED; ?>">Password</a>
| <a href="<?php echo $this->SELF.'?action=logout&amp;gbsession='.$this->gbsession.'&amp;uid='.$this->uid.$this->ISINCLUDED; ?>">Logout</a></span><br>
<hr>
<small>To check your servers PHP settings <a href="<?php echo $this->SELF; ?>?action=info&amp;gbsession=<?php echo $this->gbsession; ?>&amp;uid=<?php echo $this->uid.$this->ISINCLUDED; ?>">click here.</a></small>
<br>
<form method="post" action="<?php echo $this->SELF; ?>">
  <table cellspacing="1" cellpadding="4" align="center">
    <tr>
      <td colspan="2" height="25"class="section">Edit the guestbook entry</td>
    </tr>
    <tr>
      <td class="lefttd">Name:</td>
      <td class="righttd"><input type="text" name="name" size="44" maxlength="50" value="<?php echo $row['name']; ?>" class="input"></td>
    </tr>
    <tr>
      <td class="lefttd">E-mail:</td>
      <td class="righttd"><input type="text" name="email" size="44" maxlength="60" value="<?php echo $row['email']; ?>" class="input"></td>
    </tr>
    <tr>
      <td class="lefttd">Location:</td>
      <td class="righttd"><input type="text" name="location" size="44" maxlength="60" value="<?php echo $row['location']; ?>" class="input"></td>
    </tr>
    <tr>
      <td class="lefttd">Homepage:</td>
      <td class="righttd"><input type="text" name="url" size="44" maxlength="60" value="<?php echo $row['url']; ?>" class="input"></td>
    </tr>
    <tr>
      <td class="lefttd">ICQ:</td>
      <td class="righttd"><input type="text" name="icq" size="44" maxlength="60" value="<?php if ($row['icq']!=0) {echo $row['icq'];} ?>" class="input"></td>
    </tr>
    <tr>
      <td class="lefttd">Aim:</td>
      <td class="righttd"><input type="text" name="aim" size="44" maxlength="60" value="<?php echo $row['aim']; ?>" class="input"></td>
    </tr>
    <tr>
      <td class="lefttd">Yahoo:</td>
      <td class="righttd"><input type="text" name="yahoo" size="44" maxlength="35" value="<?php echo $row['yahoo']; ?>" class="input"></td>
    </tr>
    <tr>
      <td class="lefttd">MSN:</td>
      <td class="righttd"><input type="text" name="msn" size="44" maxlength="60" value="<?php echo $row['msn']; ?>" class="input"></td>
    </tr>
    <tr>
      <td class="lefttd">Skype:</td>
      <td class="righttd"><input type="text" name="skype" size="44" maxlength="35" value="<?php echo $row['skype']; ?>" class="input"></td>
    </tr>    		    
    <tr>
      <td class="lefttd">Gender:</td>
      <td class="righttd"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><input type="radio" name="gender" value="m"<?php if ($row['gender']=='m') {echo ' checked';} ?>>male
        <input type="radio" name="gender" value="f"<?php if ($row['gender']=='f') {echo ' checked';} ?>>female
				<input type="radio" name="gender" value="x"<?php if (!$row['gender'] || $row['gender']=='x') { echo ' checked'; }?>>Not Saying</td>
    </tr>
    <tr>
      <td class="lefttd">Host:</td>
      <td class="righttd"><input type="text" name="host" size="44" maxlength="60" value="<?php echo $row['host']; ?>" class="input"></td>
    </tr>
    <tr>
      <td class="lefttd">Browser:</td>
      <td class="righttd"><input type="text" name="browser" size="44" maxlength="250" value="<?php echo $row['browser']; ?>" class="input"></td>
    </tr>
    <tr>
      <td valign="top" class="lefttd">Their Message:</td>
      <td class="righttd">
        <textarea name="comment" cols="42" rows="10" wrap="VIRTUAL" class="input"><?PHP echo $row['comment']; ?></textarea>
      </td>
    </tr>
  </table>
  <br>
	<center>
        <input type="submit" value="Save Changes">
        <input type="reset"  value="Reset">
        <input type="button" value="Go Back" onclick="javascript:history.go(-1)">
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        <input type="hidden" name="gbsession" value="<?php echo $this->gbsession; ?>">
        <input type="hidden" name="uid" value="<?php echo $this->uid; ?>">
        <input type="hidden" name="tbl" value="<?php echo $tbl; ?>">
        <input type="hidden" name="rid" value="<?php echo $rid; ?>">
        <?php echo $this->HIDDENINC; ?>
	</center>
</form>
<hr>
<span class="menu"><a href="<?php echo $this->SELF.'?action=show&amp;tbl=priv&amp;gbsession='.$this->gbsession.'&amp;uid='.$this->uid.$this->ISINCLUDED; ?>">Private Messages</a> | 
<a href="<?php echo $this->SELF.'?action=show&amp;tbl=gb&amp;gbsession='.$this->gbsession.'&amp;uid='.$this->uid.$this->ISINCLUDED; ?>">Easy Admin</a> | 
<a href="<?php echo $this->SELF.'?action=settings&amp;panel=general&amp;gbsession='.$this->gbsession.'&amp;uid='.$this->uid.$this->ISINCLUDED; ?>">General Settings</a> | 
<a href="<?php echo $this->SELF.'?action=settings&amp;panel=style&amp;gbsession='.$this->gbsession.'&amp;uid='.$this->uid.$this->ISINCLUDED; ?>">Style</a>
| <a href="<?php echo $this->SELF.'?action=template&amp;gbsession='.$this->gbsession.'&amp;uid='.$this->uid.$this->ISINCLUDED; ?>">Templates</a>
| <a href="<?php echo $this->SELF.'?action=smilies&amp;gbsession='.$this->gbsession.'&amp;uid='.$this->uid.$this->ISINCLUDED; ?>">Smilies</a>
| <a href="<?php echo $this->SELF.'?action=settings&amp;panel=pwd&amp;gbsession='.$this->gbsession.'&amp;uid='.$this->uid.$this->ISINCLUDED; ?>">Password</a>
| <a href="<?php echo $this->SELF.'?action=logout&amp;gbsession='.$this->gbsession.'&amp;uid='.$this->uid.$this->ISINCLUDED; ?>">Logout</a></span>
<hr>
