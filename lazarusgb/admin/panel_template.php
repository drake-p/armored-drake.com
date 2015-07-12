<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.1 Transitional//EN">
<html>
<head>
<title>Guestbook - Templates</title>
<meta http-equiv="content-type" content="text/html; charset=<?php echo $this->VARS['charset']; ?>">
<style type="text/css">
<!--
body { background-color: #E5E5E5; color: #000; font-family: Verdana, Arial;}
.menu a, .menu a:visited, .menu a:active { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; font-weight: bold; color: blue;}
.input { border: 1px solid black; }
.text_size1 {  font-family: <?php echo $this->VARS["font_face"]; ?>; font-size: <?php echo $this->VARS["tb_font_1"]; ?>}
.text_size2 {  font-family: <?php echo $this->VARS["font_face"]; ?>; font-size: <?php echo $this->VARS["tb_font_2"]; ?>}
.font {  font-family: <?php echo $this->VARS["font_face"]; ?>; }
.textfield {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; width: 550px; height: 500px;}
.lefttd { background-color: #EFEFEF; font-size: 12px; }
.lefttd a, .lefttd a:visited { color: blue; }
.righttd { background-color: #DEE3E7; font-size: 12px;}
.section { color: #FFF; background-color: navy; font-weight: bold; font-size: 14px; text-align: center; }
.subsection { color: #C0C0C0; font-weight: bold; font-size: 10px; }
table { background-color: #000; border: 0; width: 100%; }
.input { border: 1px solid black; }

-->
</style>
</head>
<body>
<h4 align="center">G U E S T B O O K &nbsp; T E M P L A T E S</h4>
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
  <table>
    <tr> 
      <td colspan="2" height="25" class="section">Templates</td>
    </tr>
    <tr> 
      <td colspan="2" class="subsection">Give write permissions to the webserver on the template files!</td>
    </tr>
    <tr> 
      <td valign="top" class="lefttd"> <b>Guestbook Templates</b><br>
          <br>
        <table cellspacing="0" cellpadding="1">
<?php 
if (!empty($template_list))
{
   reset($template_list);
foreach ($template_list as $templatename) {
    echo "         <tr> 
            <td width=\"15\" class=\"lefttd\">-</td>
            <td class=\"lefttd\"><a href=\"$this->SELF?action=template&amp;tpl_name=$templatename&amp;gbsession=$this->gbsession&amp;uid=$this->uid$this->ISINCLUDED\">
            $templatename</a></td>
          </tr>\n";
}
}
?>
        </table>
       <br>
      </td>
      <td valign="top" align="center" class="righttd">
        <table cellspacing="0" cellpadding="2">
          <tr>
            <td align="center" class="righttd"><b> <?php echo $tpl_name.$can_edit; ?></b></td>
          </tr>
          <tr>
            <td class="righttd">
              <textarea name="gb_template" cols="60" rows="30" class="textfield" wrap="VIRTUAL" class="input"<?php echo $button_status; ?>><?php echo htmlspecialchars($gb_template); ?></textarea>
            </td>
          </tr>
        </table>
        <br>
      </td>
    </tr>
  </table>
  <br>
  <center>
    <input type="submit" value="Submit Settings"<?php echo $button_status; ?>>
    <input type="reset" value="Reset">
    <input type="hidden" name="uid" value="<?php echo $this->uid; ?>">
    <input type="hidden" name="gbsession" value="<?php echo $this->gbsession; ?>">
    <input type="hidden" name="action" value="template">
    <input type="hidden" name="tpl_name" value="<?php echo $tpl_name; ?>">
    <input type="hidden" name="save" value="update">
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
