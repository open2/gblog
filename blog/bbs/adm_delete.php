<?
include_once("./_common.php");

if ($current[mb_id] != $member[mb_id])
    alert('자신의 글만 삭제할 수 있습니돠.');

$post = sql_fetch("select * from {$gb4[post_table]} where id='{$id}'");
$blog_id = $post[blog_id];
$mb = sql_fetch(" select mb_id from $gb4[blog_table]  where id = '$blog_id' ");
$blog_mb_id = $mb[mb_id];

if (empty($post))
    alert('존재하지 않는 글 입니돠.');

if ($post[secret] == 0) 
    $count_field = 'secret_count'; 
else 
    $count_field = 'post_count';

// 글을 지운돠.
sql_query("delete from {$gb4[post_table]} where id='{$id}'");

// 첨부파일을 지운돠.
$qry = sql_query("select * from {$gb4[file_table]} where blog_id='{$current[id]}' and post_id='{$id}'");
while( $res = sql_fetch_array($qry)) {
    if (!empty($res[save_name])) {
        @unlink("{$current[file_path]}/{$res[save_name]}");
    }
}
sql_query("delete from {$gb4[file_table]} where post_id='{$id}'");

// 태그카운트를 하나씩 빼준다
$sql = " select * from {$gb4[taglog_table]} where post_id='{$id}'";
$qry = sql_query($sql);
while( $res = sql_fetch_array($qry)) {
    $sql = " select tag_count from $gb4[tag_table] where id = '$res[tag_id]' ";
    $res2 = sql_fetch($sql);

    // tag_count가 1이거나 그 이하면 지우고, 아니면 숫자를 차감한다.
    if ($res2[tag_count] > 1)
        $sql = " update $gb4[tag_table] set tag_count = tag_count - 1 where id = '$res[tag_id]' ";
    else
        $sql = " delete from $gb4[tag_table] where id = '$res[tag_id]' ";
    sql_query($sql);
}

// 태그를 지운돠.
sql_query("delete from {$gb4[taglog_table]} where post_id='{$id}'");

// 댓글을 지운돠.
sql_query("delete from {$gb4[comment_table]} where post_id='{$id}'");

// 엮인글을 지운돠.
sql_query("delete from {$gb4[trackback_table]} where post_id='{$id}'");

// 해당 분류의 글 카운트를 감소시킨돠.
if ($post[category_id])
    sql_query("update {$gb4[category_table]} set {$count_field} = {$count_field} - 1 where id = '{$post[category_id]}'");

// 해당 블로그 정보 테이블에 글,댓글,엮인글 갯수 감소 및 마지막 업데이트 시간을 현재로 변경한다.
// 게시글 삭제는 자주 있는 일이 아니므로, 카운터 재조정의 기회로 쓴다.
if ($post[secret] == 0) 
    $sql = " select count(*) as cnt from $gb4[post_table] where blog_id = '$blog_id' and secret = '0' ";
else
    $sql = " select count(*) as cnt from $gb4[post_table] where blog_id = '$blog_id' and secret = '1' ";
$post_cnt = sql_fetch($sql);

$sql = " select count(*) as cnt from $gb4[comment_table] where blog_id = '$blog_id' ";
$comment_cnt = sql_fetch($sql);

$sql = "update {$gb4[blog_table]} set {$count_field} = '$post_cnt[cnt]' ,comment_count = '$comment_count[cnt]' ,trackback_count = '$trackback_cnt[cnt]' ,last_update = '{$g4[time_ymdhis]}' where mb_id='{$blog_mb_id}'";
sql_query($sql);

$sql = " select count(*) as cnt from $gb4[trackback_table] where blog_id = '$blog_id' ";
$trackback_cnt = sql_fetch($sql);

// 월별 갯수를 감소한돠.
$monthly = substr($post[post_date],0,7);
sql_query("update {$gb4[monthly_table]} set {$count_field} = {$count_field} - 1 where blog_id = '{$current[id]}' and monthly = '{$monthly}'");


// 관리 페이지에서 수정한 경우 다시 관리 페이지로 이동
if ($me)
    goto_url ("adm_post_list.php?mb_id={$blog_mb_id}&page={$page}&cate={$cate}");

// 메인화면에서 수정한 경우 다시 메인화면으로 이동
else {
    // 관리자가 삭제하는 경우 관리자의 블로그로 못 돌아가게 게시자의 블로그로 가게 수정
    goto_url (get_blog_url($blog_mb_id));
}
?>