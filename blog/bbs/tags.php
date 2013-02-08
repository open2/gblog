<?
include_once("./_common.php");

include_once("$gb4[path]/head.sub.php");
include_once("$blog_skin_path/head.skin.php");

$cloud = get_tag_cloud('time');

include_once("{$blog_skin_path}/tags.skin.php");

include_once("$blog_skin_path/tail.skin.php");
include_once("$gb4[path]/tail.sub.php");
?>