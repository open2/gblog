<?
include_once("./_common.php");

$blog_id      = $_POST[blog_id];
$tag_id       = $_POST[tag_id];
$mb_id        = $_POST[mb_id];
$target_tag0  = explode(',', trim($_POST[target_tag]));
$target_tag   = $target_tag0[0];

// ������ �±��� id�� ã�´�
$sql = " select id from $gb4[tag_table] where tag = '$target_tag' ";
$result = sql_fetch($sql);

if (!$result) {
    // �±װ� ���� ��� ���ο� �±׸� �߰�
    sql_query("insert into {$gb4[tag_table]} set tag='{$target_tag}', tag_count=0, regdate='{$g4[time_ymdhis]}', lastdate='{$g4[time_ymdhis]}'");
    $new_id = mysql_insert_id();
} else {
    $new_id = $result['id'];
}

// ���ο� tag�� id�� ����
$sql = " update $gb4[taglog_table] set tag_id = '$new_id' where blog_id = '$blog_id' and tag_id = '$tag_id' ";
sql_query($sql);

// ������Ʈ�� Ƚ���� ã�Ƽ� tag_count�� ����
$updated_rows = mysql_affected_rows();
$sql = " update $gb4[tag_table] set tag_count = tag_count + $updated_rows where id = '$new_id' ";
sql_query($sql);
$sql = " update $gb4[tag_table] set tag_count = tag_count - $updated_rows where id = '$tag_id' ";
sql_query($sql);

// tag log�� �׸��� ���� tag���� ����
$sql = " select a.id from $gb4[tag_table] a left join $gb4[taglog_table] b on a.id = b.tag_id where b.id is null ";
$result = sql_query($sql);
while($res = sql_fetch_array($result)) {
    $del_id = $res['id'];
    $sql = " DELETE FROM $gb4[tag_table] where id = '$del_id' ";
    sql_query($sql);
}

?>