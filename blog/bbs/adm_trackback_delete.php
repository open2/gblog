<?
include_once("./_common.php");

// mb_id �� ������ �״´�.
// mb_id �� ��α� �ĺ����̱⶧���� �ݵ��� �����ؾ� �Ѵ�.
if( empty($mb_id) ) die('109');

// �ϲ� �ƴѰ� �� ������..
if( $member['mb_id'] != $current['mb_id'] ) die('101');

// �����.
sql_query("delete from {$gb4['trackback_table']} where id = '$trackback_id'");

// �� ���� ���̺� ���α� ī��Ʈ�� ���ҽ�Ų��.
$sql = "update {$gb4['post_table']} set trackback_count = trackback_count - 1 where blog_id='{$current['id']}' and id='{$post_id}'";
sql_query($sql);

// ��α� ���� ���̺� ���α� ī��Ʈ�� ���ҽ�Ų��.
$sql = "update {$gb4['blog_table']} set trackback_count = trackback_count - 1 where id='{$current['id']}'";
sql_query($sql);

// ������.
die('000');
?>