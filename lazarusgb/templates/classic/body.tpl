<script type="text/javascript">
<!--
function gb_picture(Image,imgWidth,imgHeight) {
    var border = 24;
    var img = Image;
    var features;
    var w;
    var h;
    winWidth = (imgWidth<100) ? 100 : imgWidth+border;
    winHeight = (imgHeight<100) ? 100 : imgHeight+border;
    if (imgWidth+border > screen.width) {
        winWidth = screen.width-10;
        w = (screen.width - winWidth)/2;
        features = "scrollbars=yes";      
    } else {
        w = (screen.width - (imgWidth+border))/2;
    }
    if (imgHeight+border > screen.height) {
        winHeight = screen.height-60;
        h = 0;
        features = "scrollbars=yes";      
    } else {
        h = (screen.height - (imgHeight+border))/2 - 20;
    }
    winName = (img.indexOf("t_") == -1) ? img.substr(4,(img.length-8)) : img.substr(6,(img.length-10));
    features = features+',toolbar=no,width='+winWidth+',height='+winHeight+',top='+h+',left='+w;
    theURL = '$GB_PG[base_url]/picture.php?img='+Image;
    popup = window.open(theURL,winName,features);
    popup.focus();  
}
$EMAILJS
function getbyid(id) {
	itm = null;
	if (document.getElementById) {
		itm = document.getElementById(id);
	}
	else if (document.all) {
		itm = document.all[id];
	}
	else if (document.layers) 	{
		itm = document.layers[id];
	}
	return itm;
}
function toggleview(id) {
	if ( ! id ) return;
	if ( itm = getbyid(id) ) {
		if (itm.style.display == "none")	{
			show_div(itm);
		} else {
			hide_div(itm);
		}
	}
}
function hide_div(itm) {
	if ( ! itm ) return;
	itm.style.display = "none";
}
function show_div(itm) {
	if ( ! itm ) return;
	itm.style.display = "";
}
// -->
</script> 
<table>
  <tr>
    <td border="0" cellspacing="0" cellpadding="0" align="center" width="95%"><img alt="guestbook logo" src="Images/guestbooklogo.gif" ><p></td>
  </tr>
</table>
<table border="0" cellspacing="0" cellpadding="2" align="center" width="$VARS[width]">
  <tr>
    <td width="56%">$TPL[GB_TIME]<br>$LANG[BookMess6]</td>
    <td width="44%" align="right" valign="bottom" class="font2"><img src="$GB_PG[base_url]/img/sign.gif" width="9" height="12" alt=""> <a href="$GB_PG[addentry]" rel="nofollow"><b>$LANG[BookMess3]</b></a>
      <b>| <img src="$GB_PG[base_url]/img/lock.gif" width="9" height="11" alt=""> <a href="$GB_PG[admin]" rel="nofollow">$LANG[BookMess5]</a></b>
    </td>
   </tr>
  </table>
<!--Start Top Navigation -->
  <table width="$VARS[width]" border="0" cellspacing="0" cellpadding="2" align="center">
    <tr valign="bottom">
      <td class="font2"><img src="$GB_PG[base_url]/img/point3.gif" width="9" height="9" alt="">$LANG[NavTotal]
       <b><font color="#DD0000">$TPL[GB_TOTAL]</font></b> &nbsp; $LANG[NavRecords] <b><font color="#DD0000">$VARS[entries_per_page]</font></b></td>
      <td align="right" class="font2"> $TPL[GB_NAVIGATION]
      </td>
   </tr>
  </table>
<!--End Top Navigation -->
  <table border="0" cellspacing="1" cellpadding="5" align="center" width="$VARS[width]" bgcolor="$VARS[tb_bg_color]">
    <tr bgcolor="$VARS[tb_hdr_color]">
      <td width="32%"><font size="2" face="$VARS[font_face]" color="$VARS[tb_text]"><b>$LANG[FormName]</b></font></td>
      <td width="68%"><font size="2" face="$VARS[font_face]" color="$VARS[tb_text]"><b>$LANG[BookMess7]</b></font></td>
    </tr>
<!--Start Guestbook Entries -->
$TPL[GB_ENTRIES]
<!--End Guestbook Entries -->
 </table>
<!--Start Bottom Navigation -->
 <table width="$VARS[width]" border="0" cellspacing="0" cellpadding="2" align="center">
  <tr valign="top">
   <td class="font2"><img src="$GB_PG[base_url]/img/point2.gif" width="9" height="9" alt="">$TPL[GB_HTML_CODE] </td>
   <td align="right" class="font2"> $TPL[GB_NAVIGATION]</td>
  </tr>
 </table>
<!--End Bottom Navigation -->
