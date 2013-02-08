<?
include_once("./_common.php");
include_once("./_header.php");

echo "{\"files\":[";
$sql = " select * from {$gb4['file_table']} where blog_id = '{$current['id']}' and post_id = '{$post_id}'";
$qry = sql_query($sql);
while($res = sql_fetch_array($qry)) {
    echo "{\"id\":\"{$res['id']}\",\"file_num\":\"{$res['file_num']}\",\"save_name\":\"{$res['save_name']}\", \"real_name\":\"{$res['real_name']}\", \"file_size\":\"{$res['file_size']}\"},";
}
echo "]}";
?>