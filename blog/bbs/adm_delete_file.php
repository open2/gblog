<?
include_once("./_common.php");
include_once("./_header.php");

$sql = "select * from {$gb4['file_table']} where id = '{$id}'";
$res = sql_fetch($sql);

if (empty($res)) $msg = "������ �����ϴ�.";

$file = $current['file_path'] .'/'. $res['save_name'];

if (!file_exists($file)) $msg = "������ �����ϴ�.";

@unlink($file);

@sql_query("delete from {$gb4['file_table']} where id='{$res['id']}'");

//if (!$msg) $msg = "{$res['real_name']} ������ �����Ͽ����ϴ�.";

$res = sql_fetch("select sum(file_size) as max_size from {$gb4['file_table']} where id='{$current['id']}'");
$max_size = $res['max_size'];
sql_query("update {$gb4['blog_table']} set total_file_size = '{$max_size}' where id='{$current['id']}'");

echo $msg;
?>