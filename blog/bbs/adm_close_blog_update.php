<?
include_once("./_common.php");

// captcha 확인
if (!(get_session('captcha_keystring') && get_session('captcha_keystring') == $_POST['kcaptcha_key'])) {
    alert('정상적인 접근이 아닌것 같습니다.');
}

// gblog index page
$url = "$g4[path]/gblog.index.php";

$mb_id = $_POST[mb_id];

if ($mb_id != $member[mb_id])
    alert("자신의 블로그가 아니므로 폐쇄할 수 없습니다.", "$url");

// 블로그 id 찾기
$current = sql_fetch("select * from $gb4[blog_table] where mb_id='$mb_id'");
if (!$current)
    alert("블로그가 존재하지 않으므로 폐쇄할 수 없습니다.", "$url");
$blog_id = $current['id'];
$skin_id = $current['skin_id'];

// 토큰 인증
check_token();

// 블로그 데이터 삭제
$sql = " delete from $gb4[category_table] where blog_id = '$blog_id' ";
sql_query($sql, FALSE);
$sql = " delete from $gb4[comment_table] where blog_id = '$blog_id' ";
sql_query($sql, FALSE);
$sql = " delete from $gb4[guestbook_table] where blog_id = '$blog_id' ";
sql_query($sql, FALSE);
$sql = " delete from $gb4[link_category_table] where blog_id = '$blog_id' ";
sql_query($sql, FALSE);
$sql = " delete from $gb4[link_table] where blog_id = '$blog_id' ";
sql_query($sql, FALSE);
$sql = " delete from $gb4[monthly_table] where blog_id = '$blog_id' ";
sql_query($sql, FALSE);
$sql = " delete from $gb4[post_table] where blog_id = '$blog_id' ";
sql_query($sql, FALSE);
$sql = " delete from $gb4[taglog_table] where blog_id = '$blog_id' ";
sql_query($sql, FALSE);
$sql = " delete from $gb4[trackback_table] where blog_id = '$blog_id' ";
sql_query($sql, FALSE);
$sql = " delete from $gb4[visit_table] where vi_blog_id = '$blog_id' ";
sql_query($sql, FALSE);
$sql = " delete from $gb4[visit_sum_table] where vs_blog_id = '$blog_id' ";
sql_query($sql, FALSE);

// 첨부 file 삭제 
$sql = " select * from $gb4[file_table] where blog_id = '$blog_id' ";
$result = sql_query($sql);
while ($row = sql_fetch_array($result)) 
{
    // 파일 삭제 ...
}
$sql = " delete from $gb4[file_table] where blog_id = '$blog_id' ";
$result = sql_query($sql);

// 블로그 skin count -1
$sql = " update $gb4[skin_table] set use_count=use_count-1 where id='$skin_id' ";
$result = sql_query($sql);

// 블로그 정보는 가장 마지막에 삭제
$sql = " delete from $gb4[blog_table] where id = '$blog_id' ";
sql_query($sql, FALSE);

// 블로그 메인으로 이동
goto_url ($url);
?>