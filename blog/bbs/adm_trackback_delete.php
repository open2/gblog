<?
include_once("./_common.php");

// mb_id 가 없으면 죽는다.
// mb_id 는 블로그 식별자이기때문에 반듯이 존재해야 한다.
if( empty($mb_id) ) die('109');

// 니꺼 아닌걸 왜 지울라고..
if( $member['mb_id'] != $current['mb_id'] ) die('101');

// 지운다.
sql_query("delete from {$gb4['trackback_table']} where id = '$trackback_id'");

// 글 정보 테이블에 엮인글 카운트를 감소시킨다.
$sql = "update {$gb4['post_table']} set trackback_count = trackback_count - 1 where blog_id='{$current['id']}' and id='{$post_id}'";
sql_query($sql);

// 블로그 정보 테이블에 엮인글 카운트를 감소시킨다.
$sql = "update {$gb4['blog_table']} set trackback_count = trackback_count - 1 where id='{$current['id']}'";
sql_query($sql);

// 지웠다.
die('000');
?>