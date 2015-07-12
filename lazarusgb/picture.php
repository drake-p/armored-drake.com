<?php 
/*
 * ----------------------------------------------
 * Lazarus Guestbook
 * by Stewart Souter
 * URL: www.carbonize.co.uk 
 * Based on Advanced Guestbook 2.3.x (PHP/MySQL)
 * Copyright (c)2001 Chi Kien Uong
 * URL: http://www.proxy2.de
 * Last Modified: Fri, 14 July 2006 13:14:08 GMT
 * ----------------------------------------------
 */
 
$include_path = dirname(__FILE__);
$border = 24;

include_once $include_path.'/admin/config.inc.php';

$GB_PG['base_url'] = '';

if (IS_MODULE) { 
  $ModName = basename(dirname( __FILE__ ));
  $GB_PG['base_url'] = '/modules/'.$ModName.'/';
}

if (!empty($_GET['img']) && !is_array($_GET['img'])) {
    $TheImg = htmlspecialchars($_GET['img']);
    if (file_exists('tmp/'.$TheImg)) {
        $size = @GetImageSize('tmp/'.$TheImg);
        $picture = $GB_PG['base_url'].'tmp/'.$TheImg;
    }
    if (file_exists('public/'.$TheImg)) {
        if (eregi("(^t_)(img-[0-9]+.[a-z]{3})",$TheImg,$regs)) {
            $size = @GetImageSize('public/'.$regs[2]);
            $picture = $GB_PG['base_url'].'public/'.$regs[2];
        } else {
            $size = @GetImageSize('public/'.$TheImg);
            $picture = $GB_PG['base_url'].'public/'.$TheImg;
        }
    }
}
if (isset($size[1]) && $size[1]>100) {
	$tbl_height = $size[1] + $border;
	$tbl_width = "100%";
} else {
	$tbl_height = 100;
	$tbl_width = 100 + $border;
}
?>
<html>
<head>
<title>Guestbook</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body bgcolor="#CCCCCC" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="<?php echo $tbl_height; ?>">
  <tr>
    <td align="center" valign="middle">
    <?php
        if (!empty($TheImg) && is_array($size)) {
            echo '<a href="javascript:window.close()"><img src="'.$picture.'" width="'.$size[0].'" height="'.$size[1].'" border="0"></a>';
        }
    ?>
    </td>
  </tr>
</table>
</body>
</html>