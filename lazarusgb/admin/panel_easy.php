<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.1 Transitional//EN">
<html>
<head>
<title>Guestbook - Easy Admin</title>
<meta http-equiv="content-type" content="text/html; charset=<?php echo $this->VARS['charset']; ?>">
<style type="text/css">
<!--
body { background-color: #E5E5E5; color: #000; font-family: Verdana, Arial;}
.menu a, .menu a:visited, .menu a:active { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 13px; font-weight: bold; color: blue;}
.input { border: 1px solid black; }
.lefttd { background-color: #EFEFEF; font-size: 12px; }
.righttd { background-color: #DEE3E7; font-size: 12px;}
.section , .section a{ color: #FFF; background-color: navy; font-weight: bold; font-size: 14px; }
.subsection { color: #C0C0C0; font-weight: bold; font-size: 10px; }
td { font-family: Verdana, Arial; }
table { background-color: #000; border: 0; }
select { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 9pt}
input { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 9pt}
.options { background-color: #FFF; }
-->
</style>
<script language="JavaScript">
<!--
function CheckValue() {
  if(!(document.FormMain.record.value >= 1)) {
    alert("Invalid record number!");
    document.FormMain.record.focus();
    return false;
  }
}
function CheckValue2() {
  if(!(document.FormMain2.record.value >= 1)) {
    alert("Invalid record number!");
    document.FormMain2.record.focus();
    return false;
  }
}
function delete_post(theURL,AlertMessage) {
   if (confirm('Are you sure you want to '+AlertMessage+'?')) {
      window.location.href=theURL;
   }
   else {
      alert ('Ok, no action has been taken');
   }
}
function doMultiple(AlertMessage) {
   if (confirm('Are you sure you want to '+AlertMessage+' the selected entries/comments?')) {
      flag = 0;
   }
   else {
      flag = 1;
      alert ('Ok, no action has been taken');
   }
}
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
    theURL = 'picture.php?img='+Image;
    popup = window.open(theURL,winName,features);
    popup.focus();  
}
//-->
</script>
</head>
<body>
<h4 align="center">E A S Y &nbsp; A D M I N</h4>
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
<small>To check your servers PHP settings <a href="<?php echo $this->SELF; ?>?action=info&amp;gbsession=<?php echo $this->gbsession; ?>&amp;uid=<?php echo $this->uid; ?>">click here.</a></small>
<form method="post" action="<?php echo $this->SELF; ?>" name="FormMain" onsubmit="return CheckValue()">
  <table cellspacing="0" cellpadding="2" align="center" width="100%">
    <tr>
      <td class="section">
        <input type="text" name="record" size="12">
        <input type="submit" value="Jump to record">
        <input type="hidden" name="action" value="show">
        <input type="hidden" name="gbsession" value="<?php echo $this->gbsession; ?>">
        <input type="hidden" name="uid" value="<?php echo $this->uid; ?>">
        <input type="hidden" name="tbl" value="<?php echo $tbl; ?>">
      </td>
      <td align="right" class="section">&nbsp;
<?php
echo "<a href=\"$this->SELF?action=show&amp;tbl=$tbl&amp;entry=0&amp;gbsession=$this->gbsession&amp;uid=$this->uid$this->ISINCLUDED\">Goto Top</a>\n";
if ($prev_page >= 0) {
 echo "  &nbsp;&nbsp;<a href=\"$this->SELF?action=show&amp;tbl=$tbl&amp;entry=$prev_page&amp;gbsession=$this->gbsession&amp;uid=$this->uid$this->ISINCLUDED\">Previous Page</a>\n";
}
if ($next_page < $total) {
 echo "  &nbsp;&nbsp;<a href=\"$this->SELF?action=show&amp;tbl=$tbl&amp;entry=$next_page&amp;gbsession=$this->gbsession&amp;uid=$this->uid$this->ISINCLUDED\">Next Page</a>\n";
}

?> </td>
   </tr>
  </table>
  <?php echo $this->HIDDENINC; ?>
</form>
<form action="<?php echo $this->SELF; ?>" method="post">
<input type="hidden" name="gbsession" value="<?php echo $this->gbsession; ?>">
<input type="hidden" name="uid" value="<?php echo $this->uid; ?>">
<input type="hidden" name="tbl" value="<?php echo $tbl; ?>">
<input type="hidden" name="action" value="multi">
<input type="hidden" name="rid" value="<?php echo $entry; ?>">
<?php echo $this->HIDDENINC; 
if ($total >= 1)
{ ?>
  <table cellspacing="1" cellpadding="5" align="center" width="100%">
    <tr bgcolor="#BCBCDE">
      <td width="30%"><font size="2"><b>Name</b></font></td>
      <td width="60%"><font size="2"><b>Comments</b></font></td>
      <td width="9%">&nbsp;</td>
      <td width="1%">&nbsp;</td>
    </tr>

<?php
}
$id = $total-$entry;
$i=0;
if ($total < 1)
{
   echo ('<h3>No entries found</h3>');
}
else
{
while ($row = $this->db->fetch_array($result)) {

$name = $row['name'];
$delpic = '';
$date = date("D, F j, Y H:i",($row['date'] += $this->VARS['offset']*3600));
$comment = nl2br($row['comment']);
$bgcolor = ($i % 2) ? "#F7F7F7" : "#DEDFDF";
$bgcolor = ($row['accepted'] == 0) ? '#FFC0CB' : $bgcolor;
$i++;

echo " <tr bgcolor=\"$bgcolor\">\n   <td width=\"30%\" valign=\"top\">
  <table cellspacing=\"0\" cellpadding=\"2\" style=\"background-color:$bgcolor;\">\n    <tr>
     <td style=\"text-align:left;\"><font size=\"1\">$id)</font></td>
     <td><font size=\"2\"><b>$name</b></font></td>\n    </tr>\n    <tr>\n";
if ($row['email']) {
  echo "     <td><font size=\"1\"><b>e-mail</b></font></td>
     <td><font size=\"1\"><a href=\"mailto:$row[email]\">$row[email]</a></font></td>\n    </tr>\n";
}
if ($row['url']) {
  echo "    <tr>\n     <td><b><font size=\"1\">URL:</font></b></td>
     <td><a href=\"$row[url]\" target=\"_blank\"><font size=\"1\">$row[url]</font></a></td>\n    </tr>\n";
}
if ($row['icq'] && $this->VARS["allow_icq"]==1) {
  echo "    <tr>\n     <td><b><font size=\"1\">ICQ:</font></b></td>
     <td><font size=\"1\">$row[icq]</font></td>\n    </tr>\n";
}
if ($row['aim'] && $this->VARS["allow_aim"]==1) {
  echo "    <tr>\n     <td><b><font size=\"1\">Aim:</font></b></td>
     <td><font size=\"1\">$row[aim]</font></td>\n    </tr>\n";
}
if ($row['yahoo'] && $this->VARS["allow_yahoo"]==1) {
  echo "    <tr>\n     <td><b><font size=\"1\">Yahoo:</font></b></td>
     <td><font size=\"1\">$row[yahoo]</font></td>\n    </tr>\n";
}
if ($row['msn'] && $this->VARS["allow_msn"]==1) {
  echo "    <tr>\n     <td><b><font size=\"1\">MSN:</font></b></td>
     <td><font size=\"1\">$row[msn]</font></td>\n    </tr>\n";
}
if ($row['skype'] && $this->VARS["allow_skype"]==1) {
  echo "    <tr>\n     <td><b><font size=\"1\">Skype:</font></b></td>
     <td><font size=\"1\">$row[skype]</font></td>\n    </tr>\n";
}
if ($this->VARS["allow_gender"]==1) {
  if ($row['gender']=='f') {
    echo "    <tr>\n     <td><b><font size=\"1\">Gender:</font></b></td>
     <td><font size=\"1\">female</font></td>\n    </tr>\n";
  } elseif ($row['gender']=='m') {
    echo "    <tr>\n     <td><b><font size=\"1\">Gender:</font></b></td>
     <td><font size=\"1\">male</font></td>\n    </tr>\n";
  }
}
if ($row['location']) {
  echo "    <tr>\n     <td><b><font size=\"1\">Location:</font></b></td>
     <td><font size=\"1\">$row[location]</font></td>\n    </tr>\n";
}
$hostname = (ereg("^[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}$", $row['host'])) ? "IP" : "Host";
$their_ip = (ereg("^[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}$", $row['host'])) ? $row['host'] : '';
$their_ip = ($row['ip'] != '') ? $row['ip'] : $their_ip;
$resolve_ip = ($their_ip != '') ? " (<a href=\"http://dnstools.com/?".$their_ip."\" target=\"_blank\" title=\"Resolve\">".$their_ip."</a>)" : '';
$bandelete = ($their_ip != '') ? "<br>\n<a href=\"javascript:delete_post('".$this->SELF."?action=banip&amp;ip=".$their_ip."&amp;tbl=".$tbl."&amp;rid=".$entry."&amp;gbsession=".$this->gbsession."&amp;uid=".$this->uid.$this->ISINCLUDED."','ban ".$their_ip."')\">ban IP</a> | <a href=\"javascript:delete_post('".$this->SELF."?action=bandel&amp;ip=".$their_ip."&amp;tbl=".$tbl."&amp;id=".$row['id']."&amp;rid=".$entry."&amp;gbsession=".$this->gbsession."&amp;uid=".$this->uid.$this->ISINCLUDED."','delete this post and ban ".$their_ip."')\">delete post and ban IP</a>" : '';
echo "  </table>\n   </td>\n   <td width=\"60%\" valign=\"top\"><font face=\"Arial\" size=\"1\"><b>".$date." ".$hostname.": ".$row['host'].$resolve_ip.$bandelete."</b></font>\n    <hr size=\"1\">
    <font size=2>";
if ($row['p_filename'] && ereg("^img-",$row['p_filename'])) {
    $new_img_size = $img->get_img_size_format($row['width'], $row['height']);
    if (file_exists("./$GB_UPLOAD/t_$row[p_filename]")) {
        $row['p_filename'] = "t_$row[p_filename]";       
    }
    echo "<a href=\"javascript:gb_picture('$row[p_filename]',$row[width],$row[height])\"><img src=\"$GB_UPLOAD/$row[p_filename]\" align=\"left\" border=\"0\" style=\"float:left;\" $new_img_size[2]></a>";
    $fromtable = ($tbl == 'gb') ? 'pub' : 'priv';
    $delpic = "<br>\n<a href=\"javascript:delete_post('$this->SELF?action=del&amp;tbl=".$fromtable."pics&amp;id=".$row['id']."&amp;rid=".$entry."&amp;gbsession=$this->gbsession&amp;uid=$this->uid$this->ISINCLUDED','delete this image')\">delete image</a>";
}
echo "$comment</font>\n";
if ($tbl == 'gb') {
    $this->db->query("select * from ".$this->table['com']." where id='$row[id]' order by com_id asc");
    while ($com = $this->db->fetch_array($this->db->result)) {
    $hostname = (ereg("^[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}$", $com['host'])) ? "IP" : "Host";
    $their_comip = (ereg("^[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}$", $com['host'])) ? $com['host'] : '';
   $their_comip = ($com['ip'] != '') ? $com['ip'] : $their_comip;
   $resolve_comip = ($their_comip != '') ? " (<a href=\"http://dnstools.com/?".$their_comip."\" target=\"_blank\" title=\"Resolve\">".$their_comip."</a>)" : '';
   $combandelete = ($their_ip != '') ? "<br>\n<a href=\"javascript:delete_post('".$this->SELF."?action=banip&amp;ip=".$their_comip."&amp;rid=".$entry."&amp;tbl=com&amp;gbsession=".$this->gbsession."&amp;uid=".$this->uid.$this->ISINCLUDED."','ban ".$their_comip."')\">ban IP</a> | <a href=\"javascript:delete_post('".$this->SELF."?action=bandel&amp;ip=".$their_comip."&amp;tbl=com&amp;id=".$com['com_id']."&amp;rid=".$entry."&amp;gbsession=".$this->gbsession."&amp;uid=".$this->uid.$this->ISINCLUDED."','delete this comment and ban ".$their_comip."')\">delete comment and ban IP</a>" : '';
      $com["comments"] = nl2br($com["comments"]);
      $combgcolor = ($com['comaccepted'] == 0) ? '#FFC0CB' : $bgcolor;
      echo "<table width=\"90%\" border=\"0\" cellspacing=\"1\" bgcolor=\"#000000\" cellpadding=\"3\" align=\"center\" style=\"clear:both;margin:3px auto 0 auto;\">\n";
     // echo "<tr bgcolor=\"$combgcolor\"><td colspan=\"3\"><hr size=\"1\"></td></tr>\n";
      echo "<tr bgcolor=\"$combgcolor\"><td valign=\"top\" colspan=\"3\"><b><font size=\"1\" face=\"Arial, Helvetica, sans-serif\">".date("D, F j, Y H:i",$com['timestamp'])." $hostname: $com[host] $resolve_comip $combandelete</font></b></td>";
      echo "<tr bgcolor=\"$combgcolor\"><td valign=\"top\"><font size=\"1\" face=\"Verdana, Arial, Helvetica, sans-serif\">$com[name]:<br>\n";
      echo "$com[comments]</font></td>";
      echo "<td align=\"right\"><font size=\"1\" face=\"Verdana, Arial, Helvetica, sans-serif\"><b><a href=\"$this->SELF?action=edit&amp;rid=$entry&amp;tbl=com&amp;id=$com[com_id]&amp;gbsession=$this->gbsession&amp;uid=$this->uid$this->ISINCLUDED\">edit</a><br><a href=\"javascript:delete_post('$this->SELF?action=del&amp;tbl=com&amp;id=$com[com_id]&amp;rid=$entry&amp;gbsession=$this->gbsession&amp;uid=$this->uid$this->ISINCLUDED','delete this comment')\">delete</a>";
			if ($com['comaccepted'] == 0)
			{
				echo "<br><a href=\"$this->SELF?action=accept&amp;tbl=com&amp;rid=$entry&amp;id=$com[com_id]&amp;gbsession=$this->gbsession&amp;uid=$this->uid$this->ISINCLUDED\">accept</a><br>";
			}
			else
			{
				echo "<br><a href=\"$this->SELF?action=unaccept&amp;tbl=com&amp;rid=$entry&amp;id=$com[com_id]&amp;gbsession=$this->gbsession&amp;uid=$this->uid$this->ISINCLUDED\">unaccept</a><br>";
			}
			echo "</font></td>
         <td width=\"5\"><input type=\"checkbox\" name=\"comarray[$com[com_id]]\"></td>
         </tr></table>";
    }
}
echo "   </td>
   <td width=\"10%\" style=\"line-height: 18px;\"><font size=\"1\"><b><a href=\"$this->SELF?action=edit&amp;rid=$entry&amp;tbl=$tbl&amp;id=$row[id]&amp;gbsession=$this->gbsession&amp;uid=$this->uid$this->ISINCLUDED\">edit post</a><br>
    <a href=\"javascript:delete_post('$this->SELF?action=del&amp;tbl=$tbl&amp;id=$row[id]&amp;rid=$entry&amp;gbsession=$this->gbsession&amp;uid=$this->uid$this->ISINCLUDED','delete this post')\">delete post</a>$delpic";
		
if ($row['accepted'] == 0)
{
	echo "<br>\n<a href=\"$this->SELF?action=accept&amp;tbl=$tbl&amp;id=$row[id]&amp;rid=$entry&amp;gbsession=$this->gbsession&amp;uid=$this->uid$this->ISINCLUDED\">accept post</a>";
}
else
{
	echo "<br>\n<a href=\"$this->SELF?action=unaccept&amp;tbl=$tbl&amp;id=$row[id]&amp;rid=$entry&amp;gbsession=$this->gbsession&amp;uid=$this->uid$this->ISINCLUDED\">unaccept post</a>";
}	
echo "</b></font></td>
<td><input type=\"checkbox\" name=\"entryarray[$row[id]]\"></td>
</tr>\n";
$id--;

}

?>
<tr>
<td colspan="4" align="right" bgcolor="#DDDDDD"><input type="submit" value="Delete Selected" name="multidelete" onClick="doMultiple('delete');if(flag==1) return false;">
 &nbsp; <input type="submit" value="Accept Selected" name="multiaccept" onClick="doMultiple('accept');if(flag==1) return false;">
  &nbsp; <input type="submit" value="Unaccept Selected" name="multiunaccept" onClick="doMultiple('unaccept');if(flag==1) return false;"></td>
</tr>
  </table>
  </form>
<?php } ?>  
  <form method="post" action="<?php echo $this->SELF; ?>" name="FormMain2" onsubmit="return CheckValue2()">
  <table cellspacing="0" cellpadding="2" align="center" width="100%">
    <tr>
      <td class="section">
        <input type="text" name="record" size="12">
        <input type="submit" value="Jump to record">
        <input type="hidden" name="action" value="show">
        <input type="hidden" name="gbsession" value="<?php echo $this->gbsession; ?>">
        <input type="hidden" name="uid" value="<?php echo $this->uid; ?>">
        <input type="hidden" name="tbl" value="<?php echo $tbl; ?>">
      </td>
      <td align="right" class="section">&nbsp;
<?php
echo "<a href=\"$this->SELF?action=show&amp;tbl=$tbl&amp;entry=0&amp;gbsession=$this->gbsession&amp;uid=$this->uid$this->ISINCLUDED\">Goto Top</a>\n";
if ($prev_page >= 0) {
 echo "  &nbsp;&nbsp;<a href=\"$this->SELF?action=show&amp;tbl=$tbl&amp;entry=$prev_page&amp;gbsession=$this->gbsession&amp;uid=$this->uid$this->ISINCLUDED\">Previous Page</a>\n";
}
if ($next_page < $total) {
 echo "  &nbsp;&nbsp;<a href=\"$this->SELF?action=show&amp;tbl=$tbl&amp;entry=$next_page&amp;gbsession=$this->gbsession&amp;uid=$this->uid$this->ISINCLUDED\">Next Page</a>\n";
}

?> </td>
   </tr>
  </table>
  <?php echo $this->HIDDENINC; ?>
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
