<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.1 Transitional//EN">
<html>
<head>
<title>Guestbook - Style</title>
<meta http-equiv="content-type" content="text/html; charset=<?php echo $this->VARS['charset']; ?>">
<style type="text/css">
<!--
.text_size1 {  font-family: <?php echo $this->VARS["font_face"]; ?>; font-size: <?php echo $this->VARS["tb_font_1"]; ?>}
.text_size2 {  font-family: <?php echo $this->VARS["font_face"]; ?>; font-size: <?php echo $this->VARS["tb_font_2"]; ?>}
.font {  font-family: <?php echo $this->VARS["font_face"]; ?>; }
body { background-color: #E5E5E5; color: #000; font-family: Verdana, Arial;}
.menu a, .menu a:visited, .menu a:active { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; font-weight: bold; color: blue;}
.input { border: 1px solid black; }
.lefttd { background-color: #EFEFEF; font-size: 12px; }
.righttd { background-color: #DEE3E7; font-size: 12px;}
.displaytd { background-color: #FFF; width:50px; }
th { color: #FFF; background-color: navy; font-size: 14px; }
.subsection { color: #C0C0C0; font-weight: bold; font-size: 10px; }
td { font-family: Verdana, Arial; }
table { background-color: #000; border: 0; width: 100%; }

-->
</style>
<script type="text/javascript">
<!--
var timenow = new Date();
var day = timenow.getDay();
var hour = timenow.getHours();
var minute = timenow.getMinutes();
var serverTime = new Date(<?php echo(date("Y, n-1, j, G, i, s")); ?>)
var serverSecs = serverTime.getTime();
var pcSecs = timenow.getTime();
var theDiff = ((pcSecs - serverSecs)/3600000);
var theDiff = theDiff.toFixed(1);

switch (day)
{
 case 0 :
      textday = "Sunday";
      break;
 case 1 :
      textday = "Monday";
      break;  
 case 2 :
      textday = "Tuesday";
      break;  
 case 3 :
      textday = "Wednesday";
      break;  
 case 4 :
      textday = "Thursday";
      break;  
 case 5 :
      textday = "Friday";
      break;  
 case 6 :
      textday = "Saturday";
      break;      
}

if (hour < 10)
{
   hour = '0'+hour;
}
if (minute < 10)
{
   minute = '0'+minute;
}
//-->
</script>
</head>
<body>
<h4 align="center">S T Y L E &nbsp; S E T T I N G S</h4>
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
      <th colspan="3" align="center" height="25">The Include Code</th>
    </tr>
    <tr> 
      <td colspan="3" class="subsection">Integrating the guestbook into your website is now easier than ever.</td>
    </tr>
    <tr> 
      <td class="lefttd"><small>Simply make a web as normal then put the code on the right EXACTLY where you want the guestbook to appear in the page.<br>
      Please remember that the page you are putting the code into must use the .php extension and not .htm nor .html</small></td>
      <td valign="top" class="righttd" style="width: 280px;"> 
        <input type="text" name="nothing" value="<?php echo '&lt;?php include(\''.preg_replace("/admin$/", '', dirname(__FILE__)).'gbinclude.php\'); ?&gt;'; ?>" size="40" class="input">
      </td>
    </tr>
  </table>
  <br>
  <table>
    <tr> 
      <th colspan="3" align="center" height="25">Style Settings</th>
    </tr>
    <tr> 
      <td colspan="3" class="subsection">Please complete the 
        following fields, which provide information such as your guestbook's table 
        width, the color of the table and the font face and font size.</td>
    </tr>
    <tr> 
      <td class="lefttd"> <b>Page Background Color</b><br>
        <small>Format - #FFFFFF</small></td>
      <td valign="top" class="righttd" style="width: 280px;"> 
        <input type="text" name="pbgcolor" value="<?php echo $this->VARS["pbgcolor"]; ?>" size="10" maxlength="7" class="input">
      </td>
      <td class="displaytd">
        <table border="1" cellspacing="0" cellpadding="1" style="background-color:<?php echo $this->VARS["pbgcolor"]; ?>" bordercolor="#000000">
          <tr>
            <td>&nbsp;</td>
          </tr>
        </table>
      </td>
    </tr>
    <tr> 
      <td class="lefttd"> <b>Table Width</font></b><br>
        <small>You may use either exact pixels (recommended: 600) or a percentage (recommended: 95%)</small> </td>
      <td class="righttd" valign="top"> 
        <input type="text" name="width" value="<?php echo $this->VARS["width"]; ?>" size="10" maxlength="6" class="input">
      </td>
      <td class="displaytd" valign="top">&nbsp;</td>
    </tr>
    <tr> 
      <td class="lefttd"> <b>Font Face (e.g., Verdana)</b><br>
        <small>You may use a backup font as well. For example: to use Verdana as your first choice, with Arial as 
        a conditional font for those users that don't have Verdana as a font on their system, you would type "Verdana, Arial") </small> </td>
      <td class="righttd" valign="top"> 
        <input type="text" name="font_face" value="<?php echo htmlspecialchars($this->VARS['font_face']); ?>" size="38" maxlength="70" class="input">
      </td>
      <td style="width:50px;background-color:#FFF;" class="font">Font</td>
    </tr>
    <tr> 
      <td class="lefttd"> <b>Link Color</b><br>
        <small>Guestbook link color. Format - #FFFFFF</small> 
      </td>
      <td class="righttd" valign="top"> 
        <input type="text" name="link_color" value="<?php echo $this->VARS["link_color"]; ?>" size="10" maxlength="7" class="input">
      </td>
      <td class="displaytd"> 
        <table width="70" border="1" cellspacing="0" cellpadding="1" style="background-color:<?php echo $this->VARS["link_color"]; ?>" bordercolor="#000000">
          <tr> 
            <td>&nbsp;</td>
          </tr>
        </table>
      </td>
    </tr>
    <tr> 
      <td class="lefttd"> <b>Text Color</b><br>
        <small>Guestbook text color. Format - #FFFFFF</small> 
      </td>
      <td class="righttd" valign="top"> 
        <input type="text" name="text_color" value="<?php echo $this->VARS["text_color"]; ?>" size="10" maxlength="7" class="input">
      </td>
      <td class="displaytd"> 
        <table width="70" border="1" cellspacing="0" cellpadding="1" style="background-color:<?php echo $this->VARS["text_color"]; ?>" bordercolor="#000000">
          <tr> 
            <td>&nbsp;</td>
          </tr>
        </table>
      </td>
    </tr>
    <tr> 
      <td class="lefttd"> <b>Text Size 1</b><br>
        <small>The text font size.</small> </td>
      <td class="righttd" valign="top"> 
        <input type="text" name="tb_font_1" value="<?php echo $this->VARS["tb_font_1"]; ?>" size="6" maxlength="6" class="input">
      </td>
      <td style="width:50px;background-color:#FFF;" class="text_size1">Text Size 1</td>
    </tr>
    <tr> 
      <td class="lefttd"> <b>Text Size 2</b><br>
        <small>A smaller value is recommend here... but depending on your font face, you may want to alter this.</small> 
      </td>
      <td class="righttd" valign="top"> 
        <input type="text" name="tb_font_2" value="<?php echo $this->VARS["tb_font_2"]; ?>" size="6" maxlength="6" class="input">
      </td>
      <td style="width:50px;background-color:#FFF;" class="text_size2">Text Size 2</td>
    </tr>
    <tr> 
      <td class="lefttd"> <b>Table Header Background Color</b><br>
        <small>This is the background color of the section titles. Format - #FFFFFF</small></td>
      <td class="righttd" valign="top"> 
        <input type="text" name="tb_hdr_color" value="<?php echo $this->VARS["tb_hdr_color"]; ?>" size="10" maxlength="7" class="input">
      </td>
      <td class="displaytd"> 
        <table width="70" border="1" cellspacing="0" cellpadding="1" style="background-color:<?php echo $this->VARS["tb_hdr_color"]; ?>" bordercolor="#000000">
          <tr> 
            <td>&nbsp;</td>
          </tr>
        </table>
      </td>
    </tr>
    <tr> 
      <td class="lefttd"> <b>Table Header Strip Text Color</b><br>
        <small>This is the color of the text at the top of the sections. Format - #FFFFFF</small></td>
      <td class="righttd" valign="top"> 
        <input type="text" name="tb_text" value="<?php echo $this->VARS["tb_text"]; ?>" size="10" maxlength="7" class="input">
      </td>
      <td class="displaytd"> 
        <table width="70" border="1" cellspacing="0" cellpadding="1" style="background-color:<?php echo $this->VARS["tb_text"]; ?>" bordercolor="#000000">
          <tr> 
            <td>&nbsp;</td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>    
      <td class="lefttd"> <b>Table Background Color</b><br>
        <small>This is the color of the borders. Format - #FFFFFF</small></td>
      <td class="righttd" valign="top"> 
        <input type="text" name="tb_bg_color" value="<?php echo $this->VARS["tb_bg_color"]; ?>" size="10" maxlength="7" class="input">
      </td>
      <td class="displaytd"> 
        <table width="70" border="1" cellspacing="0" cellpadding="1" style="background-color:<?php echo $this->VARS["tb_bg_color"]; ?>" bordercolor="#000000">
          <tr> 
            <td>&nbsp;</td>
          </tr>
        </table>
      </td>
    </tr>
    <tr> 
      <td class="lefttd"> <b>First Alternating Table Column Color</b><br>
        <small>Format - #FFFFFF</small></td>
      <td class="righttd" valign="top"> 
        <input type="text" name="tb_color_1" value="<?php echo $this->VARS["tb_color_1"]; ?>" size="10" maxlength="7" class="input">
      </td>
      <td class="displaytd"> 
        <table width="70" border="1" cellspacing="0" cellpadding="1" style="background-color:<?php echo $this->VARS["tb_color_1"]; ?>" bordercolor="#000000">
          <tr> 
            <td>&nbsp;</td>
          </tr>
        </table>
      </td>
    </tr>
    <tr> 
      <td class="lefttd"> <b>Second Alternating Table Column Color</b><br>
        <small>Format - #FFFFFF</small></td>
      <td class="righttd" valign="top"> 
        <input type="text" name="tb_color_2" value="<?php echo $this->VARS["tb_color_2"]; ?>" size="10" maxlength="7" class="input">
      </td>
      <td class="displaytd"> 
        <table width="70" border="1" cellspacing="0" cellpadding="1" style="background-color:<?php echo $this->VARS["tb_color_2"]; ?>" bordercolor="#000000">
          <tr> 
            <td>&nbsp;</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <br>
<table>
  <tr>
    <th colspan="2" height="25">Date/Time Display Options</th>
  </tr>
  <tr>
    <td colspan="2" class="subsection">
      <b>This Guestbook can display dates and times in a number of different
        formats. Remember that the times listed are based on the location of
        your web server, which may be different than the time zone where you
        reside/work. You can change the time zone displayed by using the Time
        Zone Offset field. For instance, if you are on the East Coast of the
        US, but your server is on the West Coast of the US, you would have to
        offset the server time to reflect that (by typing a 3 in the Time Zone
        Offset field, reflecting the 3 hours difference). If the Time Zone
        difference is negative, use negative number (as in -2).</b>
      </td>
  </tr>
  <tr bgcolor="#f7f7f7">
    <td width="55%">
      <b><font size="2" face="Verdana, Arial">Server Time Zone Offset</font></b><font size="1" face="Verdana, Arial"><br>
        You can offset the time drawn from your web server. For instance,
        if your server time is EST (US), but you want all time to reflect Pacific
        Time (US), you would have to offset your server time by placing the
        time zone difference in this field (for this example, that would be
        -3. You would place -3 in this field). The default is for there to be
        no server time zone offset (0).</font>

    </td>
    <td width="45%" valign="top"><input type="text" name="offset" value="<?php echo $this->VARS["offset"]; ?>" size="3" maxlength="4" class="input"><br>
          <font size="1">The offset stated below is only offered as a guide</font><br>
          <table border="0" bgcolor="#000000" cellspacing="1" cellpadding="2">
         <tr>
            <td bgcolor="#f7f7f7" align="center"><font size="1">The time on the server is:</font></td>
            <td bgcolor="#f7f7f7" align="center"><font size="1">The time on your computer is:</font></td>
            <td bgcolor="#f7f7f7" align="center"><font size="1">The offset is:</font></td>
         </tr>
         <tr>
            <td bgcolor="#f7f7f7" align="center"><font size="1"><?php echo(date("l H:i")); ?></font></td>
            <td bgcolor="#f7f7f7" align="center"><font size="1"><script type="text/javascript">
<!--
document.writeln (textday + ' ' + hour + ':' + minute + '</font></td><td bgcolor="#f7f7f7" align="center"><font size="1">' + theDiff);
//-->
</script>
         </font></td>
         </tr>
      </table>
      </td>
  </tr>
  <tr bgcolor="#dedfdf">
    <td width="55%"> <b><font size="2" face="Verdana, Arial">Date Format</font></b> <font size="1" face="Verdana, Arial"><br>
      European Format is DD-MM-YR, while US format is MM-DD-YR. Expanded formats
      include full month name.</font></td>
    <td width="45%" valign="top"> <font size="2" face="Verdana, Arial">
      <input type="radio" name="dformat" value="ISO" <?php if ($this->VARS["dformat"] == "ISO") {echo "checked";}?>>
      International Standard Format (2000-04-17)<br> 
      <input type="radio" name="dformat" value="USx" <?php if ($this->VARS["dformat"] == "USx") {echo "checked";}?>>
      US Format (04-17-2000)<br>
      <input type="radio" name="dformat" value="US" <?php if ($this->VARS["dformat"] == "US") {echo "checked";}?>>
      Exp. US Format (Monday, April 25, 2000)<br>
      <input type="radio" name="dformat" value="Eurox" <?php if ($this->VARS["dformat"] == "Eurox") {echo "checked";}?>>
      European Format (17.04.2000)<br>
      <input type="radio" name="dformat" value="Euro" <?php if ($this->VARS["dformat"] == "Euro") {echo "checked";}?>>
      Exp. European Format (Monday, 25 April 2000) </font></td>
  </tr>
  <tr bgcolor="#f7f7f7">
    <td width="55%"> <b><font size="2" face="Verdana, Arial">Time Format</font></b> <font size="1" face="Verdana, Arial"><br>
      You can have time displayed in AM/PM format, or in 24-hour format.</font></td>
    <td width="45%" valign="top"> <font size="2" face="Verdana, Arial">
      <input type="radio" name="tformat" value="AMPM" <?php if ($this->VARS["tformat"] == "AMPM") {echo "checked";}?>>
      Use AM/PM Time Format<br>
      <input type="radio" name="tformat" value="24hr" <?php if ($this->VARS["tformat"] == "24hr") {echo "checked";}?>>
      User 24-Hour Format Time (eg, 23:15) </font></td>
  </tr>
</table>
<br>
<table>
  <tr>
    <th colspan="2" height="25">Ad Block</th>
  </tr>
  <tr>
    <td colspan="2" class="subsection">
      <b>Display an advert in your guestbook entries.</b>
      </td>
  </tr>
  <tr bgcolor="#f7f7f7">
    <td width="55%"> <b><font size="2" face="Verdana, Arial">Position</font></b> <font size="1" face="Verdana, Arial"><br>
      Here you can specify at what position you want the ad code to appear. 1 will be the very first entry, 2 the second and so on. 
      Set this to 0 to disable the code block.</font></td>
    <td width="45%" valign="top"><select name="ad_pos" class="input">
    <?php
    for ($i=0;$i<=($this->VARS['entries_per_page'] + 1);$i++)
    {
      echo '<option value="'.$i.'"';
      if ($i == $this->VARS['ad_pos'])
      {
         echo ' selected';
      }
      echo '>'.$i."</option>\n";
    }
    ?>
    </td>
  </tr>
  <tr bgcolor="#dedfdf">
   <td width="55%" valign="top"><b><font size="2" face="Verdana, Arial">Ad Code</font></b><br>
   <font size="1" face="Verdana, Arial">Put the code you wish to appear in the ad block here.</td>
   <td width="45%" valign="top"><textarea cols="30" wrap="virtual" name="ad_code" class="input"><?php echo $this->VARS['ad_code']; ?></textarea></td>
</table>
 <br>
  <center>
    <input type="submit" value="Submit Settings">
    <input type="reset" value="Reset">
    <input type="hidden" value="<?php echo $this->uid; ?>" name="uid">
    <input type="hidden" value="<?php echo $this->gbsession; ?>" name="gbsession">
    <input type="hidden" value="save" name="action">
    <input type="hidden" value="style" name="panel">
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
