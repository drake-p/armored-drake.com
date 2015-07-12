<script language="JavaScript" type="text/Javascript">
<!--
var flag=0;
function SetFlag() {
  flag=1;
}
function emoticon(text) {
  text = ' ' + text + ' ';
  if (document.book.gb_comment.createTextRange && document.book.gb_comment.caretPos) {
   var caretPos = document.book.gb_comment.caretPos;
   caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? text + ' ' : text;
   document.book.gb_comment.focus();
  } else {
    document.book.gb_comment.value  += text;
	document.book.gb_comment.focus();
  }
}
var flag=0;
function openCentered(theURL,winName,winWidth,winHeight,features) {
 var w = (screen.width - winWidth)/2;
 var h = (screen.height - winHeight)/2 - 30;
 features = features+',width='+winWidth+',height='+winHeight+',top='+h+',left='+w;
 window.open(theURL,winName,features);
}
function agCode(theTag)
{
   var text1 = '[' + theTag + ']';
   var text2 = '[/' + theTag + ']';
	if (typeof(document.book.gb_comment.caretPos) != "undefined" && document.book.gb_comment.createTextRange)
	{
		var caretPos = document.book.gb_comment.caretPos, temp_length = caretPos.text.length;

		caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? text1 + caretPos.text + text2 + ' ' : text1 + caretPos.text + text2;

		if (temp_length == 0)
		{
			caretPos.moveStart("character", -text2.length);
			caretPos.moveEnd("character", -text2.length);
			caretPos.select();
		}
		else
			document.book.gb_comment.focus(caretPos);
	}
	else if (typeof(document.book.gb_comment.selectionStart) != "undefined")
	{
		var begin = document.book.gb_comment.value.substr(0, document.book.gb_comment.selectionStart);
		var selection = document.book.gb_comment.value.substr(document.book.gb_comment.selectionStart, document.book.gb_comment.selectionEnd - document.book.gb_comment.selectionStart);
		var end = document.book.gb_comment.value.substr(document.book.gb_comment.selectionEnd);
		var newCursorPos = document.book.gb_comment.selectionStart;
		var scrollPos = document.book.gb_comment.scrollTop;

		document.book.gb_comment.value = begin + text1 + selection + text2 + end;

		if (document.book.gb_comment.setSelectionRange)
		{
			if (selection.length == 0)
				document.book.gb_comment.setSelectionRange(newCursorPos + text1.length, newCursorPos + text1.length);
			else
				document.book.gb_comment.setSelectionRange(newCursorPos, newCursorPos + text1.length + selection.length + text2.length);
			document.book.gb_comment.focus();
		}
		document.book.gb_comment.scrollTop = scrollPos;
	}
	else
	{
		document.book.gb_comment.value += text1 + text2;
		document.book.gb_comment.focus(document.book.gb_comment.value.length - 1);
	}
}
function storeCaret(text)
{
	if (typeof(text.createTextRange) != "undefined")
		text.caretPos = document.selection.createRange().duplicate();
}
$EXTRAJS
function trim(value) {
   startpos=0;
   while((value.charAt(startpos)==" ")&&(startpos<value.length)) {
      startpos++;
   }
   if(startpos==value.length) {
      value="";
   } else {
      value=value.substring(startpos,value.length);
      endpos=(value.length)-1;
      while(value.charAt(endpos)==" ") {
         endpos--;
      }
      value=value.substring(0,endpos+1);
   }
   return(value);
}
//-->
</script>
<table border="0" cellspacing="0" cellpadding="2" align="center" width="$VARS[width]">
   <tr>
    <td height="45"><b><font size="4" face="$VARS[font_face]" color="$VARS[text_color]">$LANG[FormSelect]</font></b></td>
    <td height="45">&nbsp;</td>
  </tr>
  <tr>
    <td width="45%" valign="bottom" class="font2">$LANG[BookMess9]</td>
    <td width="55%" align="right" valign="bottom" class="font2"> <b><img src="$GB_PG[base_url]/img/return.gif" width="10" height="10" alt="">
     <a href="$GB_PG[index]">$LANG[BookMess4]</a> | <img src="$GB_PG[base_url]/img/lock.gif" width="9" height="11" alt=""> <a href="$GB_PG[admin]">$LANG[BookMess5]</a></b></td>
  </tr>
</table>
<form method="post" action="$GB_PG[comment]" name="book" onsubmit="return SetFlag()">
  <table border="0" cellspacing="1" cellpadding="4" width="$VARS[width]" align="center" bgcolor="$VARS[tb_bg_color]">
    <tr>
     <td colspan="2" bgcolor="$VARS[tb_hdr_color]"><b><font size="2" face="$VARS[font_face]" color="$VARS[tb_text]">$LANG[BookMess3]:</font></b></td>
    </tr>

$GB_ENTRY

    <tr bgcolor="$VARS[tb_color_1]">
      <td width="32%" valign="top" class="font1"><img src="$GB_PG[base_url]/img/edit.gif" width="18" height="13" alt="">$LANG[BookMess7]:<br>
			$SMILEYS
          <tr align="center">
            <td colspan="3"> 
              <div align="center" class="font2">$SMILEYPOP
              </div>
            </td>
          </tr>
        </table>
      </td>
      <td class="font1">$display_tags<textarea name="gb_comment" cols="32" rows="6" style="float:left;" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);" onchange="storeCaret(this);"></textarea></td>
      </td>
    </tr>
    <tr bgcolor="$VARS[tb_color_1]">
      <td width="32%" class="font1">$LANG[FormName]:</td>
      <td><input type="text" name="gb_user" size="35" maxlength="25"></td>
    </tr>
    
$COMMENT_PASS

    <tr bgcolor="$VARS[tb_color_1]">
      <td width="32%"><div align="left" class="font2">$HTML_CODE<br>$SMILE_CODE<br>$AG_CODE</div></td>
      <td>
        <input type="submit" name="action" value="$LANG[FormSubmit]" class="input" onclick="if(flag==1) return false;">
        <input type="reset" value="$LANG[FormReset]" class="input">
        <input type="hidden" name="gb_id" value="$id">
        <input type="hidden" name="gb_action$antispam" value="1">
      </td>
    </tr>
  </table>
</form>
