<table border="0" cellspacing="1" cellpadding="5" width="$VARS[width]" align="center" bgcolor="$VARS[tb_bg_color]">
 <tr>
   <td colspan="2" bgcolor="$VARS[tb_hdr_color]"><b><font size="2" face="$VARS[font_face]" color="$VARS[tb_text]">$LANG[BookMess3]:</font></b>
   </td>
 </tr>
 <tr bgcolor="$VARS[tb_color_1]">
   <td width="32%" valign="top">
     <table border="0" cellspacing="0" cellpadding="2">
        <tr><td class="font2" valign="top" width="8%"><b>1)</b></td>
        <td width="92%" class="font1"><b>$row[name]</b>&nbsp;$GENDER</td></tr>
        <tr><td colspan="2" class="font1">$TEXTEMAIL</td></tr>
        <tr><td colspan="2" class="font2">$LANG[FormLoc]:<br>$row[location]</td></tr>
        <tr><td colspan="2"><img src="$GB_PG[base_url]/img/ip.gif" width="14" height="14" alt="$LANG[AltIP]" title="$LANG[AltIP]">&nbsp;&nbsp;<img src="$GB_PG[base_url]/img/browsers/$theirbrowser.png" width="14" height="14" alt="$AGENT" title="$AGENT"> $URL $MSN $YAHOO $ICQ $AIM $SKYPE</td></tr>
     </table>
   </td>
   <td width="68%" class="font1" valign="top">
     <div align="left" class="font3"><img src="$GB_PG[base_url]/img/post.gif" width="9" height="9" alt="">$DATE&nbsp;$HOST
       <img src="$GB_PG[base_url]/img/edit.gif" width="18" height="13" border="0" alt=""> $EMAIL
     </div>
       <hr size="1">$USER_PIC $message
   </td>
 </tr>
 <tr bgcolor="$VARS[tb_color_1]">
   <td width="32%">&nbsp;</td>
   <td>
    <input type="button" name="back" value="$LANG[FormBack]" class="input" onclick="javascript:history.back()">
    <input type="submit" name="agb_action$antispam" value="$LANG[FormSubmit]" class="input" onclick="if(flag==1) return false;">
    $HIDDEN
   </td>
 </tr>
</table>
