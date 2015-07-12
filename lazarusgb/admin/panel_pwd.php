<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.1 Transitional//EN">
<html>
<head>
<title>Guestbook - Password Settings</title>
<meta http-equiv="content-type" content="text/html; charset=<?php echo $this->VARS['charset']; ?>">
<style type="text/css">
<!--
body { background-color: #E5E5E5; color: #000; font-family: Verdana, Arial;}
.menu a, .menu a:visited, .menu a:active { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; font-weight: bold; color: blue;}
.input { border: 1px solid black; }
.lefttd { background-color: #EFEFEF; font-size: 12px; }
.righttd { background-color: #DEE3E7; width: 300px; font-size: 12px;}
.section { color: #FFF; background-color: navy; font-weight: bold; font-size: 14px; text-align: center; }
.subsection { color: #C0C0C0; font-weight: bold; font-size: 10px; }
td { font-family: Verdana, Arial; }
table { background-color: #000; border: 0; width: 100%; }
-->
</style>
<script language="JavaScript">
<!--
function checkForm() {
  if (document.FormPwd.NEWadmin_pass.value != document.FormPwd.confirm.value) {
    alert("The passwords do not match!");
    return false;
  }
}
//-->
</script>
</head>
<body>
<h4 align="center">C H A N G E &nbsp; P A S S W O R D</h4>
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
<small>To check your servers PHP settings <a href="<?php echo $this->SELF; ?>?action=info&amp;gbsession=<?php echo $this->gbsession; ?>&amp;uid=<?php echo $this->uid.$this->ISINCLUDED; ?>">click here.</a></small>
<form action="<?php echo $this->SELF; ?>" name="FormPwd" method="post">
  <table>
    <tr>
      <td colspan="2" height="25" class="section">Guestbook Username/Password</td>
    </tr>
    <tr>
      <td colspan="2" class="subsection">Below you can change the username and/or password for the guestbook admin.</td>
    </tr>
    <tr>
      <td class="lefttd"><b>Your UserName</b><br>
      <small>Leave this alone unless you want to change your username.</small></td>
      <td class="righttd"><input type="text" name="NEWadmin_name" value="<?php echo $row["username"]; ?>" size="30" class="input"></td>
    </tr>
    <tr>
      <td class="lefttd"> <b>Enter New Password</b></td>
      <td class="righttd"><input type="password" name="NEWadmin_pass" size="30" class="input"></td>
    </tr>
    <tr>
      <td class="lefttd"><b>Confirm New Password</b></td>
      <td class="righttd">
        <input type="password" name="confirm" size="30" class="input">
        <input type="hidden" value="password" name="panel">
      </td>
    </tr>
  </table>
  <br>
  <table>
    <tr>
      <td colspan="2" height="25" class="section">Database Settings</td>
    </tr>
    <tr>
      <td colspan="2" class="subsection">Below are database settings for your mySQL database.</td>
    </tr>
    <tr>
      <td class="lefttd"><b>Database Name</b></td>
      <td class="righttd"><b><?php echo $this->db->db['dbName']; ?></b></td>
    </tr>
    <tr>
      <td class="lefttd"><b>MySQL Hostname</b><br>
        <small>Default is 'localhost'.</small></td>
      <td class="righttd"><b><?php echo $this->db->db['host']; ?></b></td>
    </tr>
    <tr>
      <td class="lefttd"> <b>MySQL Username</b><br>
        <small>Your mySQL username for the database.</small></td>
      <td class="righttd"><b><?php echo $this->db->db['user']; ?></b></td>
    </tr>
    <tr>
      <td class="lefttd" valign="top"> <b>Tables</b><br>
        <small>The tables used by the guestbook.</small></td>
      <td class="righttd"><font size="2" face="Verdana, Arial">

<?php
reset($this->table);
foreach ($this->table as $tablename) {
   echo "- ".$tablename."<br>\n";
}
?>
      </td>
    </tr>
  </table>
  <br>
  <center>
    <input type="submit" value="Submit Settings" onclick="return checkForm()">
    <input type="reset"  value="Reset">
    <input type="hidden" value="<?php echo $this->uid; ?>" name="uid">
    <input type="hidden" value="<?php echo $this->gbsession; ?>" name="gbsession">
    <input type="hidden" value="password" name="panel">
    <input type="hidden" value="save" name="action">
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
