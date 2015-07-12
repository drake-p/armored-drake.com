<?php 
/*
 * ----------------------------------------------
 * Lazarus Guestbook
 * by Stewart Souter
 * URL: www.carbonize.co.uk 
 * Based on Advanced Guestbook 2.3.x (PHP/MySQL)
 * Copyright (c)2001 Chi Kien Uong
 * URL: http://www.proxy2.de
 * Last Modified: 20th October 2005
 * ----------------------------------------------
 */
 
/* database settings */

$GB_DB['dbName'] = 'armored_guestbook'; // The name of your MySQL Database
$GB_DB['host']   = 'localhost'; // The server your database is on. Localhost is usualy correct
$GB_DB['user']   = 'armored_drake'; // Your MySQL username
$GB_DB['pass']   = 'drakemai11e'; // Your MySQL password

/* misc */

// This is used to make sure that the images and links work correctly
// It is the url of the guestbook folder with no trailing slash
// e.g http://www.yourdomain.com/guestbook
// If you are using this book as a module in PHP Nuke or similar then leave off the guestbooks folder
// e.g http://www.yourdomain.com
$GB_PG['base_url'] = 'http://www.armored-drake.com/lazarusgb';

// If you are using the guestbook as a module in POST-Nuke 0.x or PHP-Nuke 5.x or later set this to true
define('IS_MODULE', false);

// You can change this if some of the database table names are already used
// or if you want to host more than one guestbook on one database
$table_prefix = 'book';

// Put your email address here to receive email reports of any errors
$TEC_MAIL  = 'you_at_your_domain_dot_com';

// 
// Do not edit below this line unless you know what you are doing
//

$DB_CLASS  = 'mysql.class.php';
$GB_UPLOAD = 'public';
$GB_TMP    = 'tmp';

/* tables */

$GB_TBL['data']  = $table_prefix.'_data';
$GB_TBL['auth']  = $table_prefix.'_auth';
$GB_TBL['cfg']   = $table_prefix.'_config';
$GB_TBL['com']   = $table_prefix.'_com';
$GB_TBL['ip']    = $table_prefix.'_ip';
$GB_TBL['words'] = $table_prefix.'_words';
$GB_TBL['ban']   = $table_prefix.'_ban';
$GB_TBL['priv']  = $table_prefix.'_private';
$GB_TBL['smile'] = $table_prefix.'_smilies';
$GB_TBL['pics']  = $table_prefix.'_pics';

/* guestbook pages */

$GB_PG['index']    = 'index.php';
$GB_PG['admin']    = 'admin.php';
$GB_PG['comment']  = 'comment.php';
$GB_PG['addentry'] = 'addentry.php';

if ($GB_PG['base_url'] == '') {
    $inter_type = php_sapi_name();
    if ($inter_type == 'cgi') {
        if (isset($_SERVER['PATH_INFO']) && !empty($_SERVER['PATH_INFO'])) {
            $GB_PG['base_url'] = dirname($_SERVER['PATH_INFO']);
        } elseif (isset($_SERVER['REQUEST_URI']) && !empty($_SERVER['REQUEST_URI'])) {
            $GB_PG['base_url'] = dirname($_SERVER['REQUEST_URI']);
        } else {
            $GB_PG['base_url'] = dirname($_SERVER['SCRIPT_NAME']);
        }
    } else {
        $GB_PG['base_url'] = dirname($_SERVER['PHP_SELF']);
    }
}

?>