<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.1 Transitional//EN">
<html>
<head>
<title>Guestbook - Smilies</title>
<meta http-equiv="content-type" content="text/html; charset=<?php echo $this->VARS['charset']; ?>">
<style type="text/css">
<!--
.text_size1 {  font-family: <?php echo $this->VARS["font_face"]; ?>; font-size: <?php echo $this->VARS["tb_font_1"]; ?>}
.text_size2 {  font-family: <?php echo $this->VARS["font_face"]; ?>; font-size: <?php echo $this->VARS["tb_font_2"]; ?>}
.font {  font-family: <?php echo $this->VARS["font_face"]; ?>; }
body { background-color: #E5E5E5; color: #000; font-family: Verdana, Arial;}
.menu a, .menu a:visited, .menu a:active { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; font-weight: bold; color: blue;}
.section { color: #FFF; background-color: navy; font-weight: bold; font-size: 14px; text-align: center; }
.subsection { color: #FFF; font-weight: bold; font-size: 10px; background-color: gray;}
table { background-color: #000; border: 0; width: 100%; }
.righttd { background-color: #DEE3E7; font-size: 12px;}
-->
</style>
</head>
<body>
<h4 align="center">G U E S T B O O K &nbsp; S M I L I E S</h4>
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
<form action="<?php echo $this->SELF; ?>" name="FormMain" method="post">
  <table cellspacing="1" cellpadding="7" align="center">
        <tr> 
            <td colspan="6" align="center" height="25" class="section">Smilies</td>
        </tr>
        <tr> 
            <td height="25" class="subsection"><b>Smilie</b></td>
            <td class="subsection"><b>Filename</b></td>
            <td class="subsection"><b>Code</b></td>
            <td class="subsection"><b>Alt Text</b></td>
            <td colspan="2" class="subsection"><b>Action</b></td>
          </tr>
<?php
if (isset($smilie_data)) {
    echo "
          <tr bgcolor=\"#f7f7f7\"> 
            <td><img src=\"img/smilies/$smilie_data[s_filename]\" width=\"$smilie_data[width]\" height=\"$smilie_data[height]\"></td>
            <td><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\">$smilie_data[s_filename]</font></td>
            <td><input type=\"text\" name=\"s_code\" value=\"".htmlspecialchars($smilie_data['s_code'])."\" size=\"15\"></td>
            <td><input type=\"text\" name=\"s_emotion\" value=\"".htmlspecialchars($smilie_data['s_emotion'])."\" size=\"25\"><input type=\"hidden\" name=\"edit_smilie\" value=\"$smilie_data[id]\"></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>\n";
} else {
    $this->db->query("SELECT * FROM ".$this->table['smile']." ORDER BY s_filename ASC");
    while ($this->db->fetch_array($this->db->result)) {
        echo "
          <tr bgcolor=\"#f7f7f7\"> 
            <td><img src=\"img/smilies/".$this->db->record['s_filename']."\" width=\"".$this->db->record['width']."\" height=\"".$this->db->record['height']."\"></td>
            <td><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\">".$this->db->record['s_filename']."</font></td>
            <td><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\">".$this->db->record['s_code']."</font></td>
            <td><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\">".$this->db->record['s_emotion']."</font></td>
            <td class=\"righttd\"><a href=\"$this->SELF?action=smilies&amp;gbsession=$this->gbsession&amp;uid=$this->uid&amp;edit_smilie=".$this->db->record['id']."\">edit</a></td>
            <td class=\"righttd\"><a href=\"$this->SELF?action=smilies&amp;gbsession=$this->gbsession&amp;uid=$this->uid&amp;del_smilie=".$this->db->record['id']."\">delete</a></td>
          </tr>\n";
    }
}
if (isset($smilie_list)) {
reset($smilie_list);
    foreach ($smilie_list as $key => $value)
    {
        echo "
          <tr bgcolor=\"#f7f7f7\"> 
            <td>$value</td>
            <td><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\">$key</font></td>
            <td><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\"><input type=\"text\" name=\"new_smilie[$key]\" size=\"15\"></font></td>
            <td><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\"><input type=\"text\" name=\"new_emotion[$key]\" size=\"25\"></font></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>\n";
    }
}
?>
        </table>
        <div align="center"><br>
          <font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b><a href="<?php echo $this->SELF; ?>?action=smilies&amp;gbsession=<?php echo $this->gbsession; ?>&amp;uid=<?php echo $this->uid; ?>&amp;scan_dir=1">Scan directory (img/smilies)</a></b><br><br>
          </font></div>
  <br>
  <center>
    <input type="submit" value="Submit Settings">
    <input type="reset" value="Reset">
    <input type="hidden" name="uid" value="<?php echo $this->uid; ?>">
    <input type="hidden" name="gbsession" value="<?php echo $this->gbsession; ?>">
    <input type="hidden" name="action" value="smilies">
    <input type="hidden" name="add_smilies" value="1">
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
