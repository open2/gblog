<?
include_once("./_common.php");

/*
    ������ �̹��� ��� ����
*/
$r = sql_fetch("select profile_image from {$gb4['blog_table']} where mb_id='$mb_id'");

if( empty($r) ) exit;

Header("Content-type: image/jpeg");

echo $r['profile_image'];
?>