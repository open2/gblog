<?
include_once("./_common.php");

$blog_id = $_POST[blog_id];
$tag_id = $_POST[tag_id];

// get blog_id 
$sql = " select blog_id from $gb4[taglog_table] where tag_id = '$tag_id' ";
$result = sql_fetch($sql);

if ($mb_id != $member['mb_id'] || $result['blog_id'] != $blog_id)
    alert("바람직하지 않은 접근 입니다.");

// 삭제할 tag 숫자를 select
$sql = " SELECT count(*) as cnt from $gb4[taglog_table] where blog_id = '$blog_id' and tag_id = '$tag_id' ";
$result = sql_fetch($sql);
$del_count = $result['cnt'];

// tag count를 update
$sql = " UPDATE $gb4[tag_table] SET tag_count = tag_count - $del_count where id = '$tag_id' ";
sql_query($sql);

// tag를 삭제
$sql = " DELETE FROM $gb4[taglog_table] where blog_id = '$blog_id' and tag_id = '$tag_id' ";
sql_query($sql);

// 해당 메시지를 출력 후 자신의 블로그로 이동
alert( "태그가 삭제 되었습니다.", "$gb4[path]/adm_tag_list.php?mb_id=$mb_id");
?>