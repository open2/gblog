<?
include_once("./_common.php");

// mb_id 가 없으면 죽는다.
if( empty($mb_id) ) die("101");

// 자신의 블로그가 없어도 죽는거다.
$current = sql_fetch("select * from {$gb4['blog_table']} where mb_id='{$mb_id}'");
if( empty($current) ) die("102");

// 게시글의 포스팅이 없어도 죽는거다
$sql = "select * from {$gb4['post_table']} where blog_id='{$current[id]}' and id='{$id}'";
$post = sql_fetch($sql);
if( empty($post) ) die("103");

// bitly_url이 있는지 본다. 없으면 죽어야지
if( empty($bitly_url) ) die("104");

$sql = " update {$gb4['post_table']} set bitly_url = '$bitly_url' where blog_id='{$current[id]}' and id='{$id}'";
sql_query($sql);

die("000");
?>