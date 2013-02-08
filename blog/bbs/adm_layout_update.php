<?
include_once("./_common.php");

if( empty($mb_id) ) $err = '109';

if( !$err ) {
    $sql = "update {$gb4['blog_table']} set sidebar_left='{$list_left}' ,sidebar_right='{$list_right}' ,sidebar_garbage='{$list_garbage}' where id='{$current['id']}'";
    sql_query($sql);
    $err = '000';
}

header("Content-Type:text/xml;");
echo "<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n";
echo "<items>\n";
echo "<errnum>{$err}</errnum>\n";
echo "</items>";

?>