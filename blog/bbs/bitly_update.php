<?
include_once("./_common.php");

// mb_id �� ������ �״´�.
if( empty($mb_id) ) die("101");

// �ڽ��� ��αװ� ��� �״°Ŵ�.
$current = sql_fetch("select * from {$gb4['blog_table']} where mb_id='{$mb_id}'");
if( empty($current) ) die("102");

// �Խñ��� �������� ��� �״°Ŵ�
$sql = "select * from {$gb4['post_table']} where blog_id='{$current[id]}' and id='{$id}'";
$post = sql_fetch($sql);
if( empty($post) ) die("103");

// bitly_url�� �ִ��� ����. ������ �׾����
if( empty($bitly_url) ) die("104");

$sql = " update {$gb4['post_table']} set bitly_url = '$bitly_url' where blog_id='{$current[id]}' and id='{$id}'";
sql_query($sql);

die("000");
?>