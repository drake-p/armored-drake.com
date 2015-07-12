<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.1 Transitional//EN">
<html>
<head>
<title>Guestbook - General Settings</title>
<meta http-equiv="content-type" content="text/html; charset=<?php echo $this->VARS['charset']; ?>">
<style type="text/css">
<!--
body { background-color: #E5E5E5; color: #000; font-family: Verdana, Arial;}
.menu a, .menu a:visited, .menu a:active { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; font-weight: bold; color: blue;}
.input { border: 1px solid black; }
.lefttd { background-color: #EFEFEF; font-size: 12px; }
.righttd { background-color: #DEE3E7; width: 300px; font-size: 12px;}
th { color: #FFF; background-color: navy; font-size: 14px; }
.subsection { color: #C0C0C0; font-weight: bold; font-size: 10px; }
td { font-family: Verdana, Arial; }
table { background-color: #000; border: 0; width: 100%; }
-->
</style>
<script language=JavaScript>
<!--
function CheckValue() {
  if(!(document.FormMain.entries_per_page.value >= 1)) {
    alert("The maximum records per page must be greater than 0!");
    document.FormMain.entries_per_page.focus();
    return false;
  }
}
//-->
</script>
</head>
<body>
<h4 align="center">G E N E R A L &nbsp; S E T T I N G S</h4>
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
<form action="<?php echo $this->SELF; ?>" name="FormMain" method="post" onsubmit="return CheckValue()">
<table>
  <tr>
    <th colspan="2" height="25">General Options</th>
  </tr>
  <tr>
    <td colspan="2" class="subsection">Below are numerous configuration options for your guestbook.</td>
  </tr>
  <tr>
    <td class="lefttd"> <b>Maximum Records Displayed Per Page</b><br>
      <small>20 records per page is recommend.</small></td>
    <td valign="top" class="righttd">
      <input type="text" name="entries_per_page" value="<?php echo $this->VARS['entries_per_page']; ?>" maxlength="5" size="5" class="input"></td>
  </tr>
  <tr>
    <td valign="top" class="lefttd"> <b>Language</b><br>
      <small>The language file you want to use.</small></td>
    <td valign="top" class="righttd"><input type="text" name="lang" value="<?php echo $this->VARS['lang']; ?>" class="input">
     <select name="lang_file" onChange="forms[0].lang.value=options[selectedIndex].value" class="input">
      <option value="english" selected>Language</option>
<?php
chdir("./lang");
$hnd = opendir(".");
while ($file = readdir($hnd)) {
    if(is_file($file)) {
        if (!ereg("^codes-",$file)) {
            $langlist[] = $file;
        }
    }
}
closedir($hnd);
if ($langlist) {
    asort($langlist);
    while (list ($key, $file) = each ($langlist)) {
        if (ereg(".php|.php3",$file,$regs)) {
            $language = str_replace("$regs[0]","","$file");
            echo "<option value=\"$language\">$language</option>\n";
        }
    }
}
chdir("../");
?>
     </select>
    </td>
  </tr>
  <tr>
    <td valign="top" class="lefttd"> <b>Character Set</b><br>
      <small>What character set do you want the guestbook to use?<br>
      If you are using the gbinclude.php file to integrate the guestbook into your site then you must
      make sure the character set here matches the one on the page you have included the guestbook in. 
      If these are different you may mess up entries when using the edit function of easy admin.</small></td>
    <td valign="top" class="righttd"><input type="text" name="charset" value="<?php echo $this->VARS['charset']; ?>" class="input">
     <select name="charset_list" onChange="forms[0].charset.value=options[selectedIndex].value" class="input">
      <option value="<?php echo $this->VARS['charset']; ?>">Charset</option>
      <option value="utf-8">utf-8</option>
      <option value="utf-16">utf-16</option>
      <option value="iso-8859-1">iso-8859-1</option>
      <option value="iso-8859-2">iso-8859-2</option>
      <option value="iso-8859-3">iso-8859-3</option>
      <option value="iso-8859-4">iso-8859-4</option>
      <option value="iso-8859-5">iso-8859-5</option>
      <option value="iso-8859-6-i">iso-8859-6-i</option>
      <option value="iso-8859-7">iso-8859-7</option>
      <option value="iso-8859-8-i">iso-8859-8-i</option>
      <option value="iso-8859-9">iso-8859-9</option>
      <option value="iso-8859-10">iso-8859-10</option>
      <option value="iso-8859-13">iso-8859-13</option>
      <option value="iso-8859-14">iso-8859-14</option>
      <option value="iso-8859-15">iso-8859-15</option>
      <option value="us-ascii">us-ascii</option>
      <option value="euc-jp">euc-jp</option>
      <option value="shift_jis">shift_jis</option>
      <option value="iso-2022-jp">iso-2022-jp</option>
      <option value="euc-kr">euc-kr</option>
      <option value="gb2312">gb2312</option>
      <option value="gb18030">gb18030</option>
      <option value="big5">big5</option>
      <option value="tis-620">tis-620</option>
      <option value="koi8-r">koi8-r</option>
      <option value="koi8-u">koi8-u</option>
      <option value="macintosh">macintosh</option>
      <option value="windows-1250">windows-1250</option>
      <option value="windows-1251">windows-1251</option>
      <option value="windows-1252">windows-1252</option>
      <option value="windows-1253">windows-1253</option>
      <option value="windows-1254 ">windows-1254</option>
      <option value="windows-1255">windows-1255</option>
      <option value="windows-1256">windows-1256</option>
      <option value="windows-1257">windows-1257</option>
      </select>
    </td>
  </tr>
  <tr>
    <td valign="top" class="lefttd"> <b>Show Guest's IP or Hostname</b><br>
      <small>For security reasons, you may wish to display the IP or Hostname of
      the person signing your guestbook in their post. The default is ON.</small>
    </td>
    <td valign="top" class="righttd">
      <input type="radio" name="show_ip" id="showip1" value="1" <?php if ($this->VARS['show_ip'] == 1) {echo 'checked';}?>>
      <label for="showip1">Show IP or Hostname</label> <br>
      <input type="radio" name="show_ip" id="showip2" value="0" <?php if ($this->VARS['show_ip'] == 0) {echo 'checked';}?>>
      <label for="showip2">Hide IP or Hostname<label>
    </td>
  </tr>
  <tr>
    <td valign="top" class="lefttd"> <b>HTML Codes</b><br>
      <small>If HTML Code is enabled, this means the users can use <b>allowed</b> HTML
      tags in the comment field.</small></td>
    <td valign="top" class="righttd">
        <input type="radio" name="allow_html" id="allow_html1" value="1" <?php if ($this->VARS['allow_html'] == 1) {echo 'checked';}?>>
        <label for="allow_html1">allow HTML Codes</label> <br>
        <input type="radio" name="allow_html" id="allow_html2" value="0" <?php if ($this->VARS['allow_html'] == 0) {echo 'checked';}?>>
        <label for="allow_html2">disable HTML Codes
    </td>
  </tr>
    <tr>
    <td valign="top" class="lefttd"> <b>Allowed HTML Tags</b><br>
      <small>Here you can specify which tags you wish to allow. 
      You can only use tags where the closing tag is identical to the opening tag. eg &lt;b&gt;..&lt;/b&gt;, &lt;i&gt;..&lt;/i&gt;</small></td>
    <td valign="top" class="righttd">
        <input type="text" name="allowed_tags" value="<?php echo $this->VARS['allowed_tags']; ?>" size="30" maxlength="30" class="input"><br>
        <small>Seperate tags with commas (ie i,u,b).</small>
    </td>
  </tr>
  <tr>
    <td valign="top" class="lefttd"> <b>Smilies</b><br>
      <small>If you have used email or internet chat, you are likely
      familiar with the smilie concept. Certain standard emoticons are automatically
      converted into smilies.</small></td>
    <td valign="top" class="righttd">
        <input type="radio" name="smilies" id="smilies1" value="1" <?php if ($this->VARS['smilies'] == 1) {echo 'checked';}?>>
        <label for="smilies1">activate Smilies</label><br>
        <input type="radio" name="smilies" id="smilies2" value="0" <?php if ($this->VARS['smilies'] == 0) {echo 'checked';}?>>
        <label for="smilies2">disable Smilies</label>
    </td>
  </tr>
  <tr>
    <td valign="top" class="lefttd"> <b>AGCodes</b><br>
      <small>AGCode is a variation on the HTML tags you may already be familiar with.
       Basically, it allows you to add functionality or style to your message that would normally require HTML.
       You can use AGCode even if HTML is not enabled for the guestbook.<br>
			 The IMG agcode allows the poster to post an image in their post. This image may be posted on a different website and may not be safe.
          You can specify a maximum width and height for images posted using the [img] tag and any images larger than this will be resized using HTML.
          Images posted using the [img] AGcode are clickable and will open the full size image in a new window.<br>
			 The URL agcode allows the poster to post links in their post. If you allow the URL AGcode then ALL urls in the entry will automatically be made into links.</small></td>
    <td valign="top" class="righttd">
        <input type="radio" name="agcode" id="agcode1" value="1" <?php if ($this->VARS['agcode'] == 1) {echo 'checked';}?>>
        <label for="agcode1">allow AGCodes</label><br>
        <input type="radio" name="agcode" id="agcode2" value="0" <?php if ($this->VARS['agcode'] == 0) {echo 'checked';}?>>
        <label for="agcode2">disable AGCodes</label><br>
        <input type="checkbox" name="allow_emailagcode" id="allow_emailagcode" value="1" <?php if ($this->VARS['allow_emailagcode'] == 1) {echo 'checked';}?>>
        <label for="allow_emailagcode">Use the EMAIL agcode.</label><br>
        <input type="checkbox" name="allow_imgagcode" id="allow_imgagcode" value="1" <?php if ($this->VARS['allow_imgagcode'] == 1) {echo 'checked';}?>>
        <label for="allow_imgagcode">Use the IMG agcode.</label><br>
        <input type="checkbox" name="allow_urlagcode" id="allow_urlagcode" value="1" <?php if ($this->VARS['allow_urlagcode'] == 1) {echo 'checked';}?>>
        <label for="allow_urlagcode">Use the URL agcode.</label><br>
        Maximum Width = <input type="text" name="agcode_img_width" size="3" maxlength="5" value="<?php echo $this->VARS['agcode_img_width']; ?>" class="input"><br>
        Maximum Height = <input type="text" name="agcode_img_height" size="3" maxlength="5" value="<?php echo $this->VARS['agcode_img_height']; ?>" class="input"><br>
        <small>You can disable this feature by setting both to 0.</small>
    </td>
  </tr>
  <tr>
    <td valign="top" class="lefttd"> <b>Email Encryption</b><br>
      <small>You can have the email addresses shown in the guestbook encrypted to prevent them being harvested by spammers.</small></td>
    <td valign="top" class="righttd">
        <input type="radio" name="encrypt_email" id="encrypt_email1" value="1" <?php if ($this->VARS['encrypt_email'] == 1) {echo 'checked';}?>>
        <label for="encrypt_email1">Encrypt email addresses</label><br>
        <input type="radio" name="encrypt_email" id="encrypt_email2" value="0" <?php if ($this->VARS['encrypt_email'] == 0) {echo 'checked';}?>>
        <label for="encrypt_email2">Do not encrypt email addresses</label>
    </td>
  </tr>  
  <tr>
    <td valign="top" class="lefttd"> <b>Base URL</b><br>
      <small>The base url is used to make sure images and links are correct. If you are using the guestbook as a module this should be set to the url of your nuke installation (http://yoursite.com or http://yoursite.com/NUKEFOLDER).
      If you are not using Lazarus as a module this should be set to the url you see in the address bar of your web browser but without the /admin.php nor anything that comes after the .php</small></td>
    <td valign="top" class="righttd">
        <input type="text" name="base_url" value="<?php echo $this->VARS['base_url']; ?>" size="30" class="input">
    </td>
  </tr>    
</table>
<br>
  <table>
    <tr> 
      <th colspan="2" height="25">Field Definitions</th>
    </tr>
    <tr> 
      <td colspan="2" class="subsection">Below are numerous configuration options for your guestbook fields.</td>
    </tr>
    <tr>
      <td valign="top" class="lefttd"> <b>Require Email Address</b><br>
        <small>These options are to do with the email address field. You can make the email address a required field or
        leave it as optional. You can also have the email address shown in the entry (Display) or not (Hide).<br>
        You also have the option of removing the email field all together.</small></td>
      <td valign="top" class="righttd">
        <b>Require an email and</b><br>
        <input type="radio" name="require_email" value="1" <?php if ($this->VARS['require_email'] == 1) {echo 'checked';}?>>
        Display 
        <input type="radio" name="require_email" value="4" <?php if ($this->VARS['require_email'] == 4) {echo 'checked';}?>>
        Hide<br> 
        <b>Do not require an email and</b><br>       
        <input type="radio" name="require_email" value="0" <?php if ($this->VARS['require_email'] == 0) {echo 'checked';}?>>
        Display 
        <input type="radio" name="require_email" value="3" <?php if ($this->VARS['require_email'] == 3) {echo 'checked';}?>>
        Hide<br>
        <br>
        <input type="radio" name="require_email" value="2" <?php if ($this->VARS['require_email'] == 2) {echo 'checked';}?>>
        Do not use email field</td>
    </tr>
    <tr>
      <td valign="top" class="lefttd"> <b>Location Field</b></td>
      <td valign="top" class="righttd">
        <input type="radio" name="allow_loc" value="1" <?php if ($this->VARS['allow_loc'] == 1) {echo 'checked';}?>>On 
        <input type="radio" name="allow_loc" value="0" <?php if ($this->VARS['allow_loc'] == 0) {echo 'checked';}?>>Off
      </td>
    </tr>    
    <tr>
      <td valign="top" class="lefttd"> <b>Homepage Field</b></td>
      <td valign="top" class="righttd">
        <input type="radio" name="allow_url" value="1" <?php if ($this->VARS['allow_url'] == 1) {echo 'checked';}?>>On 
        <input type="radio" name="allow_url" value="0" <?php if ($this->VARS['allow_url'] == 0) {echo 'checked';}?>>Off
      </td>
    </tr>
    <tr>
      <td valign="top" class="lefttd"> <b>Gender Field</b></td>
      <td valign="top" class="righttd">
        <input type="radio" name="allow_gender" value="1" <?php if ($this->VARS['allow_gender'] == 1) {echo 'checked';}?>>On 
        <input type="radio" name="allow_gender" value="0" <?php if ($this->VARS['allow_gender'] == 0) {echo 'checked';}?>>Off
      </td>
    </tr>
    <tr>
      <td valign="top" class="lefttd"> <b>ICQ Field</b></td>
      <td valign="top" class="righttd">
        <input type="radio" name="allow_icq" value="1" <?php if ($this->VARS['allow_icq'] == 1) {echo 'checked';}?>>On 
        <input type="radio" name="allow_icq" value="0" <?php if ($this->VARS['allow_icq'] == 0) {echo 'checked';}?>>Off
      </td>
    </tr>
    <tr>
      <td valign="top" class="lefttd"> <b>AIM Field</b></td>
      <td valign="top" class="righttd">
        <input type="radio" name="allow_aim" value="1" <?php if ($this->VARS['allow_aim'] == 1) {echo 'checked';}?>>On 
        <input type="radio" name="allow_aim" value="0" <?php if ($this->VARS['allow_aim'] == 0) {echo 'checked';}?>>Off
      </td>
    </tr>
    <tr>
      <td valign="top" class="lefttd"> <b>MSN Field</b></td>
      <td valign="top" class="righttd">
        <input type="radio" name="allow_msn" value="1" <?php if ($this->VARS['allow_msn'] == 1) {echo 'checked';}?>>On 
        <input type="radio" name="allow_msn" value="0" <?php if ($this->VARS['allow_msn'] == 0) {echo 'checked';}?>>Off
      </td>
    </tr>
    <tr>
      <td valign="top" class="lefttd"> <b>Yahoo Field</b></td>
      <td valign="top" class="righttd">
		  <input type="radio" name="allow_yahoo" value="1" <?php if ($this->VARS['allow_yahoo'] == 1) {echo 'checked';}?>>On 
        <input type="radio" name="allow_yahoo" value="0" <?php if ($this->VARS['allow_yahoo'] == 0) {echo 'checked';}?>>Off
      </td>
    </tr>
    <tr>
      <td valign="top" class="lefttd"> <b>Skype Field</b></td>
      <td valign="top" class="righttd">
		  <input type="radio" name="allow_skype" value="1" <?php if ($this->VARS['allow_skype'] == 1) {echo 'checked';}?>>On 
        <input type="radio" name="allow_skype" value="0" <?php if ($this->VARS['allow_skype'] == 0) {echo 'checked';}?>>Off
      </td>
    </tr>    
    <tr>
      <td valign="top" class="lefttd"> <b>Private Messages</b><br>
        <small>Do you want to show the private message option on the add entry form?</small></td>
      <td valign="top" class="righttd">
        <input type="radio" name="allow_private" value="1" <?php if ($this->VARS['allow_private'] == 1) {echo 'checked';}?>>
        Allow private messages<br>
        <input type="radio" name="allow_private" value="0" <?php if ($this->VARS['allow_private'] == 0) {echo 'checked';}?>>
        No private messages</td>
    </tr>
    <tr>
      <td valign="top" class="lefttd"> <b>Hide Comments</b><br>
        <small>If this option is selected then any comments are initially hidden and become visible when a javascript link is clicked.</small></td>
      <td valign="top" class="righttd">
        <input type="radio" name="hide_comments" value="1" <?php if ($this->VARS['hide_comments'] == 1) {echo 'checked';}?>>
        Hide Comments<br>
        <input type="radio" name="hide_comments" value="0" <?php if ($this->VARS['hide_comments'] == 0) {echo 'checked';}?>>
        Show Comments</td>
    </tr>   
    <tr>
      <td valign="top" class="lefttd"> <b>Picture Upload</b><br>
      
      <?php if ((is_writable($GB_TMP)) && (is_writable($GB_UPLOAD))) 
      {  ?>
        <small>You can allow guests to upload an image with their post. You can specify the height and width of the image that is displayed in the entry here. All
        images which are bigger will automatically be resized when displayed in the entry but the full size image can still be seen by clicking on the smaller image.
        The height and width you specify here are also used to specify the size of any thumbnail created.</small></td>
      <td valign="top" class="righttd">
        <input type="radio" name="allow_img" value="1" <?php if ($this->VARS['allow_img'] == 1) {echo 'checked';}?>>
        allow Picture Upload <br>
        <input type="radio" name="allow_img" value="0" <?php if ($this->VARS['allow_img'] == 0) {echo 'checked';}?>>
        disable Picture Upload <br>
        <font size="1">Size image will appear in entry:<br>
        <input type="text" name="img_width" size="3" value="<?php echo $this->VARS['img_width']; ?>" class="input">
        X 
        <input type="text" name="img_height" size="3" value="<?php echo $this->VARS['img_height']; ?>" class="input">
        <small>width x height</small><br>
				<small>Maximum filesize: </small><input type="text" name="max_img_size" size="4" value="<?php echo round($this->VARS['max_img_size']); ?>" class="input"><small>kb</small>
				<?php }
				else
				{  ?>
			<small>You can allow guests to upload an image with their post. You can specify the height and width of the image that is displayed in the entry here. All
        images which are bigger will automatically be resized when displayed in the entry but the full size image can still be seen by clicking on the smaller image.
        The height and width you specify here is also used to specify the size of any thumbnail created.<br>
        <font color="red"><b>THESE OPTIONS ARE DISABLED AS YOU NEED TO CHANGE THE PERMISSIONS OF THE PUBLIC AND TMP FOLDERS TO 777 SO THAT THE GUESTBOOK CAN MOVE THE IMAGES THERE.<br></small></td>
      <td valign="top" class="righttd">
        <input type="radio" name="allow_img" value="1" disabled>
        allow Picture Upload <br>
        <input type="radio" name="allow_img" value="0" checked disabled>
        disable Picture Upload <br>
        <font size="1">Size in entry:<br>
        <input type="text" name="img_width" size="5" value="<?php echo $this->VARS['img_width']; ?>" class="input" disabled>
        X 
        <input type="text" name="img_height" size="5" value="<?php echo $this->VARS['img_height']; ?>" class="input" disabled>
        <small>width x height</small><br>
				<small>Maximum filesize: </small><input type="text" name="max_img_size" size="4" value="<?php echo round($this->VARS['max_img_size']); ?>" class="input" disabled><small>kb</small>
				<?php } ?>
        </td>
    </tr>
    <tr>
      <td valign="top" class="lefttd"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b>Thumbnails</b><br>
        <small>If you have Image Magick or PHP's GD extension on your server you can make smaller versions of the uploaded
        pictures to appear in the guestbook. This will save your bandwidth.</small></td>
      <td valign="top" class="righttd">
        <input type="checkbox" name="thumbnail" value="1" <?php if ($this->VARS['thumbnail'] == 1) {echo 'checked';}?>>
        create thumbnails<br>
        <small>Create thumbnail if filesize is over<br></small>
        <input type="text" name="thumb_min_fsize" size="5" value="<?php echo $this->VARS['thumb_min_fsize']; ?>" class="input">
        <small> kb</small></td>
    </tr>
  </table>
  <br>
<table>
  <tr>
    <th colspan="2" height="25">Email Options</th>
  </tr>
  <tr>
    <td colspan="2" class="subsection">Sendmail is installed on most Unix/Linux servers by default. The path to sendmail is stored in the php.ini file.</td>
  </tr>
  <tr>
    <td valign="top" class="lefttd"> <b>Webmaster E-mail</b><br>
       <small>Your e-mail address</small></td>
    <td valign="top" class="righttd"><input type="text" name="admin_mail" value="<?php echo $this->VARS['admin_mail']; ?>" size="30" maxlength="60" class="input"></td>
  </tr>
  <tr>
    <td valign="top" class="lefttd"> <b>Guestbooks E-mail</b><br>
       <small>This email address will be used when sending thank you emails to guests. 
       It will also be used when sending notifications to you about new entries when no email address has been supplied in the entry. 
       If this is left blank then the webmasters email address from above will be used.<br>
       You can choose to have all notifications that get sent to the webmaster to appear from this address. 
       Again if no email has been supplied here then the webmaster address from above will be used.</small></td>
    <td valign="top" class="righttd"><input type="text" name="book_mail" value="<?php echo $this->VARS['book_mail']; ?>" size="30" maxlength="60" class="input"><br>
    <input type="checkbox" name="always_bookemail" value="1" <?php if ($this->VARS['always_bookemail'] == 1) {echo 'checked';}?>> Always send notifications from this</td>
  </tr>
  <tr>
    <td valign="top" class="lefttd">
      <b>E-mail notification</b><br>
        <small>Select whether you want to send emails to yourself when someone has signed your guestbook.
        Note: your email address above must be valid and an email process must
        be properly configured.</small>
    </td>
    <td valign="top" class="righttd">
      <input type="checkbox" name="notify_private" value="1" <?php if ($this->VARS['notify_private'] == 1) {echo 'checked';}?>>
      Notify webmaster of private messages<br>
      <input type="checkbox" name="notify_admin" value="1" <?php if ($this->VARS['notify_admin'] == 1) {echo 'checked';}?>>
      Notify webmaster of new public messages<br>
      <input type="checkbox" name="notify_admin_com" value="1" <?php if ($this->VARS['notify_admin_com'] == 1) {echo 'checked';}?>>
      Notify webmaster of new comments<br>      
      <input type="checkbox" name="notify_guest" value="1" <?php if ($this->VARS['notify_guest'] == 1) {echo 'checked';}?>>
      Send thank you email to guests</td>
  </tr>
  <tr>
    <td valign="top" class="lefttd"><b>Thank You Email</b><br>
       <small>You can customise the thank you email that guests recieve when they submit a post in the guestbook.
        Simply edit the wording in the box to the right. You can choose to send emails as HTML or plaintext. 
		  If you choose HTML then a &lt;br&gt; tag is inserted at the end of every line automatically.
		  A short thank you is best.<br>
        <br>
        You can have the guests name put into the email for you by simply putting <b>[NAME]</b> where you want
        it to be. The guestbook will replace the <b>[NAME]</b> with the name supplied in the entry.</small>
    </td>
    <td valign="top" class="righttd">
    	<input type="checkbox" name="html_email" value="1" <?php if ($this->VARS['html_email'] == 1) {echo 'checked';}?>> Email contains HTML.
      <textarea rows="5" cols="30" wrap="virtual" name="notify_mes" class="input"><?php echo $this->VARS['notify_mes']; ?></textarea>
    </td>
  </tr>
</table>
<br>
<table>
  <tr>
    <th colspan="2" height="25">Security Options</th>
  </tr>
  <tr>
    <td colspan="2" class="subsection">Below are numerous configuration options for your Guestbook.</td>
  </tr>
   <tr>
    <td valign="top" class="lefttd"> <b>Posting Times</b><br>
      <small>To help fight spam the guestbook makes a note of what time the guest opened the addentry and comments page. 
      When the guest posts their entry the guestbook then compares this time with the values on the right and rejects it if 
      they posted it to fast or took to long. If you do not wish to use either of these timings simply set it to 0.<br>
      This check is for entries and comments so don't use to long a waiting time.</small>
      </td>
    <td valign="top" class="righttd">
         How long until they can post their entry:<br>
        <input type="text" size="2" maxlength="2" name="post_time_min" value="<?php echo($this->VARS['post_time_min']); ?>" class="input"> seconds<br>
        Amount of time they have to post their entry:<br>
        <input type="text" size="4" maxlength="5" name="post_time_max" value="<?php echo($this->VARS['post_time_max']); ?>" class="input"> seconds<br>
    </td>
  </tr>  
  <tr>
    <td valign="top" class="lefttd"> <b>Anti Bot Test</b><br>
      <small>You can require that users have to enter the randomly generated characters from an image or answer a question you have set before their post gets added.<br>
			If you select the question method then simply specify <b>BOTH</b> the question and the answer in their respective boxes on the right<br>
			Try to keep the question short, you are limited to 50 characters. The answer is limited to 20 characters<br>
			Some examples are:<br>
			Question: What colour is the sky?<br>
			Answer: blue<br>
			Answers are not case sensitive so they could answer the above as blue or Blue or even BLUE and it will pass.<br>
      If you choose the image verification (CAPTCHA) method then you can adjust the four CAPTCHA settings to change the image that is created.</small>
      </td>
    <td valign="top" class="righttd">
        <input type="radio" name="antibottest" value="2" <?php if ($this->VARS['antibottest'] == 2) {echo 'checked';}?>> Use image verification.<br>
        <input type="radio" name="antibottest" value="1" <?php if ($this->VARS['antibottest'] == 1) {echo 'checked';}?>> Use bot test question.<br>
        <input type="radio" name="antibottest" value="0" <?php if ($this->VARS['antibottest'] == 0) {echo 'checked';}?>> Use no bot test.<br>
        <fieldset><legend>Anti Bot Question</legend>Question: <input type="text" name="bottestquestion" size="30" maxlength="50" value="<?php echo htmlspecialchars($this->VARS['bottestquestion']); ?>" class="input"><br><br>
        Answer:&nbsp; &nbsp;<input type="text" name="bottestanswer" size="30" maxlength="20" value="<?php echo $this->VARS['bottestanswer']; ?>" class="input"></fieldset>
        <fieldset><legend>CAPTCHA Options</legend>
        <input type="checkbox" name="captcha_noise"<?php if ($this->VARS['captcha_noise'] == 1) {echo ' checked';}?>> Use background noise<br>
        <input type="checkbox" name="captcha_grid"<?php if ($this->VARS['captcha_grid'] == 1) {echo ' checked';}?>> Use background grid<br>
        <input type="checkbox" name="captcha_grey"<?php if ($this->VARS['captcha_grey'] == 1) {echo ' checked';}?>> Use colour in background<br>
        <input type="checkbox" name="captcha_greytext"<?php if ($this->VARS['captcha_greytext'] == 1) {echo ' checked';}?>> Use colour in the code
        </fieldset>
    </td>
  </tr>  
  <tr>
    <td valign="top" class="lefttd"> <b>Guestbook Comment</b><br>
      <small>If you want to password-protect the comment feature, set this option to 'Password required'.<br>
      You can also specify a message to be displayed with the password input. This could be used to add an 
      anti bot test style question or password hint to the comment form. Leave this field empty if you do not wish to use it.<br>
      You can also use CAPTCHA to prevent spam. This is instead of the password. You can set th eimage options above.<br>
      You can totally disable comments if you wish.</small>
      </td>
    <td valign="top" class="righttd">
        <input type="radio" name="need_pass" value="0" <?php if ($this->VARS['need_pass'] == 0) {echo 'checked';}?>>
        No Password required<br>
        <input type="radio" name="need_pass" value="1" <?php if ($this->VARS['need_pass'] == 1) {echo 'checked';}?>>
        Password required <br>
        <input type="radio" name="need_pass" value="2" <?php if ($this->VARS['need_pass'] == 2) {echo 'checked';}?>>
        Use CAPTCHA<br>
        Password: <input type="text" name="comment_pass" size="18" value="<?php echo $this->VARS['comment_pass']; ?>" class="input"><br>
        Message: <input type="text" name="com_question" size="30" maxlength="50" value="<?php echo htmlspecialchars($this->VARS['com_question']); ?>" class="input"><br>
        <input type="checkbox" name="disablecomments"<?php if ($this->VARS['disablecomments'] == 1) {echo ' checked';}?> id="disablecomments"> <label for="disablecomments">Disable Comments</label>
    </td>
  </tr>
  <tr>
    <td valign="top" class="lefttd"> <b>Moderate Posts</b><br>
      <small>Activate this option if you wish to review all entries before they appear in the guestbook. This does not apply to private messages.
			You must supply an email address above so you can be notified of new entries.</small>
      </td>
    <td valign="top" class="righttd">
        <input type="radio" name="require_checking" value="1" <?php if ($this->VARS['require_checking'] == 1) {echo 'checked';}?>>
        Moderate posts<br>
        <input type="radio" name="require_checking" value="0" <?php if ($this->VARS['require_checking'] == 0) {echo 'checked';}?>>
        Do not moderate posts<br>
    </td>
  </tr>
  <tr>
    <td valign="top" class="lefttd"> <b>Moderate Comments</b><br>
      <small>Activate this option if you wish to review all comments before they appear in the guestbook.
			You must supply an email address above so you can be notified of new comments.</small>
      </td>
    <td valign="top" class="righttd">
        <input type="radio" name="require_comchecking" value="1" <?php if ($this->VARS['require_comchecking'] == 1) {echo 'checked';}?>>
        Moderate comments<br>
        <input type="radio" name="require_comchecking" value="0" <?php if ($this->VARS['require_comchecking'] == 0) {echo 'checked';}?>>
        Do not moderate comments<br>
    </td>
  </tr>  	  
  <tr>
      <td valign="top" class="lefttd"> <b>Message Length</b><br>
        <small>You can set the minimum and maximum
        message length here. The max. word length is an option to avoid messages 
        from nice people entering a bunch of characters without spaces. :)</small>
      </td>
    <td valign="top" class="righttd">
      <input type="text" name="min_text" size="5" value="<?php echo $this->VARS['min_text']; ?>" class="input">
      Min. message length<br>
      <input type="text" name="max_text" size="5" value="<?php echo $this->VARS['max_text']; ?>" class="input">
      Max. message length<br>
      <input type="text" name="max_word_len" size="5" value="<?php echo $this->VARS['max_word_len']; ?>" class="input">
      Max. Word length</td>
  </tr>
  <tr>
    <td valign="top" class="lefttd">
      <b>Censor Option</b><br>
			<small>You may have certain words censored on your Guestbook. Words you choose to
        censor can be replaced by asterisks or any post conatining these words blocked. All subjects and messages will
        be affected. Just select the appropriate option from those offered on the right.<br><br>
        Type all words you want censored in the box on the right putting each word on its own line. If you type "dog", all messages containing the string "dog"
      would be blocked or the word dog replaced (dog, for instance, would appear as "#@*%!").<br><br>
			You can use regular expressions to make your censoring more powerful. Only enable this option if you understand PHP's regular expressions.</small><br>
			<input type="checkbox" name="use_regex" value="1" <?php if ($this->VARS['use_regex'] == 1) {echo 'checked';}?>> Use Regex.</td>
    <td valign="top" class="righttd">
    <input type="radio" name="censor" value="0"<?php if ($this->VARS['censor'] == 0) {echo ' checked';}?>> Do not censor posts<br>
    <br>
    Censor posts and...<br>
    <input type="radio" name="censor" value="1"<?php if ($this->VARS['censor'] == 1) {echo ' checked';}?>> Replace words
    <input type="radio" name="censor" value="2"<?php if ($this->VARS['censor'] == 2) {echo ' checked';}?>> Block post<br>
     <b>Words to Censor</b><br>
     <textarea name="badwords" rows="5" cols="30" wrap="VIRTUAL" class="input">
<?php
if (isset($badwords) && sizeof($badwords)>0) {
  for ($i=0; $i<sizeof($badwords); $i++) {
    echo "$badwords[$i]\n";
  }
}
?></textarea><br>
    </td>
  </tr>
  <tr valign="top">
    <td class="lefttd"> <b>Flood Check?</b><br>
      <small>You may prevent your users from flooding your Guestbook with posts by activating this feature.
      By enabling floodcheck, you disallow users from posting within a given time span of their last post.
      In other words, if you set a floodcheck time span of 60 seconds, a user may not post a note within 60 seconds of his last post.</small>
      <br><br><center>
      <input type="radio" name="flood_check" value="1" <?php if ($this->VARS['flood_check'] == 1) {echo 'checked';}?>> FloodCheck On
      <input type="radio" name="flood_check" value="0" <?php if ($this->VARS['flood_check'] == 0) {echo 'checked';}?>> FloodCheck Off
      </center>
      </td>
    <td valign="top" class="righttd"> <b>FloodCheck Time Span</b><br>
      <small>Set the amount of time in seconds used by FloodCheck to prevent post flooding.
      Recommended: 60. Type the number of seconds only.</small><br>
      <input type="text" name="flood_timeout" size="5" value="<?php echo $this->VARS['flood_timeout']; ?>" class="input">
    </td>
  </tr>
  <tr>
    <td valign="top" class="lefttd">
      <b>Banned IP?</b><br>
        <small>You may ban any IP numbers from signing your Guestbook. Type in the complete IP number (as in 243.21.31.7),
        or use a partial IP number (as in 243.21.31.). The Guestbook will do matches from the beginning of each IP number that you enter.
        Thus, If you enter a partial IP of 243.21.31., someone attempting to sign who has an IP number of 243.21.31.5
        will not be able to sign. Similarly, if you have an IP ban on 243.21., someone signing who has an IP of 243.21.3.44
        will not be able to sign. Thus, be careful when you add IPs to your ban list and be as specific as possible.
        The IP Ban prevents anyone with matching IP number from signing your Guestbook.<br>
        You must have specify atleast two octets (ie 255.255.)</small>
        <center>
        <input type="radio" name="banned_ip" value="1" <?php if ($this->VARS['banned_ip'] == 1) {echo 'checked';}?>> yes
        <input type="radio" name="banned_ip" value="0" <?php if ($this->VARS['banned_ip'] == 0) {echo 'checked';}?>> no
        </center>
    </td>
    <td valign="top" class="righttd">
     <b>IP Number Ban List:</b><br>
      <small>Put each IP number on its own line.<small><br>
     <textarea name="banned_ips" rows=8 cols=30 wrap="VIRTUAL" class="input">
<?php
if (isset($banned_ips) && sizeof($banned_ips)>0) {
  for ($i=0; $i<sizeof($banned_ips); $i++) {
    echo "$banned_ips[$i]\n";
  }
}
?></textarea>
    </td>
  </tr>
</table>
 <br>
  <center>
    <input type="submit" value="Submit Settings">
    <input type="reset" value="Reset">
    <input type="hidden" value="<?php echo $this->uid; ?>" name="uid">
    <input type="hidden" value="<?php echo $this->gbsession; ?>" name="gbsession">
    <input type="hidden" value="save" name="action">
    <input type="hidden" value="general" name="panel">
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
