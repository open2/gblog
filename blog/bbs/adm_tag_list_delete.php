<?
include_once("./_common.php");

$blog_id = $_POST[blog_id];
$tag_id = $_POST[tag_id];

// get blog_id 
$sql = " select blog_id from $gb4[taglog_table] where tag_id = '$tag_id' ";
$result = sql_fetch($sql);

if ($mb_id != $member['mb_id'] || $result['blog_id'] != $blog_id)
    alert("�ٶ������� ���� ���� �Դϴ�.");

// ������ tag ���ڸ� select
$sql = " SELECT count(*) as cnt from $gb4[taglog_table] where blog_id = '$blog_id' and tag_id = '$tag_id' ";
$result = sql_fetch($sql);
$del_count = $result['cnt'];

// tag count�� update
$sql = " UPDATE $gb4[tag_table] SET tag_count = tag_count - $del_count where id = '$tag_id' ";
sql_query($sql);

// tag�� ����
$sql = " DELETE FROM $gb4[taglog_table] where blog_id = '$blog_id' and tag_id = '$tag_id' ";
sql_query($sql);

// �ش� �޽����� ��� �� �ڽ��� ��α׷� �̵�
alert( "�±װ� ���� �Ǿ����ϴ�.", "$gb4[path]/adm_tag_list.php?mb_id=$mb_id");
?>