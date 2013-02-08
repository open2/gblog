<?
include_once('./_common.php');

auth_check($auth[$sub_menu], "r");

$g4[title] = "카운트 조정";

include_once("$g4[admin_path]/admin.head.php");


if ($run)
{
    // 전체 글/댓글/트랙백 갯수 재설정
    $q = sql_query("select * from $gb4[blog_table] order by id");
    while($r = sql_fetch_array($q)) {
        $r2 = sql_fetch("select count(*) as cnt from $gb4[post_table] where blog_id='$r[id]' and secret=1");
        sql_query("update $gb4[blog_table] set post_count='$r2[cnt]' where id='$r[id]'");
        $r2 = sql_fetch("select count(*) as cnt from $gb4[post_table] where blog_id='$r[id]' and secret=0");
        sql_query("update $gb4[blog_table] set secret_count='$r2[cnt]' where id='$r[id]'");
        $r2 = sql_fetch("select count(*) as cnt from $gb4[comment_table] where blog_id='$r[id]'");
        sql_query("update $gb4[blog_table] set comment_count='$r2[cnt]' where id='$r[id]'");
        $r2 = sql_fetch("select count(*) as cnt from $gb4[trackback_table] where blog_id='$r[id]'");
        sql_query("update $gb4[blog_table] set trackback_count='$r2[cnt]' where id='$r[id]'");
    }

    // 분류별 글 갯수 재설정
    $q = sql_query("select * from $gb4[category_table] order by id");
    while($r = sql_fetch_array($q)){
        $p = sql_fetch("select count(*) as cnt from $gb4[post_table] where category_id='{$r['id']}' and secret=1");
        sql_query("update $gb4[category_table] set post_count='{$p['cnt']}' where id='{$r['id']}'");
        $p = sql_fetch("select count(*) as cnt from $gb4[post_table] where category_id='{$r['id']}' and secret=0");
        sql_query("update $gb4[category_table] set secret_count='{$p['cnt']}' where id='{$r['id']}'");
    }

    // 글과 댓글 연결 재설정
    $q = sql_query("select * from $gb4[comment_table] order by id");
    while($r = sql_fetch_array($q)){
        $c = sql_fetch("select blog_id from $gb4[post_table] where id='{$r['post_id']}'");
        sql_query("update $gb4[comment_table] set blog_id='{$c['blog_id']}' where post_id='{$r['post_id']}'");
    }

    // 글별 댓글 갯수 재설정
    $q = sql_query("select * from $gb4[post_table] order by id");
    while($r = sql_fetch_array($q)){
        $c = sql_fetch("select count(*) as cnt from $gb4[comment_table] where post_id='{$r['id']}'");
        sql_query("update $gb4[post_table] set comment_count='{$c['cnt']}' where id='{$r['id']}'");
    }

    // 글별 엮인글 갯수 재설정
    $q = sql_query("select * from $gb4[post_table] order by id");
    while($r = sql_fetch_array($q)){
        $c = sql_fetch("select count(*) as cnt from $gb4[trackback_table] where post_id='{$r['id']}'");
        sql_query("update $gb4[post_table] set trackback_count='{$c['cnt']}' where id='{$r['id']}'");
    }


    // 월별 글 갯수 재설정
    sql_query("delete from $gb4[monthly_table]");
    $q = sql_query("select * from $gb4[post_table] order by id");
    while($r = sql_fetch_array($q)){
        $monthly = substr($r['post_date'],0,7);
        if( $r['secret'] == 0 ) 
            $count_field = 'secret_count'; 
        else 
            $count_field = 'post_count';
        $r2 = sql_fetch("select * from $gb4[monthly_table] where blog_id='{$r['blog_id']}' and monthly = '{$monthly}'");
        if( empty($r2) )
            sql_query("insert $gb4[monthly_table] set blog_id='{$r['blog_id']}', monthly='{$monthly}', {$count_field}=1");
        else
            sql_query("update $gb4[monthly_table] set {$count_field} = {$count_field} + 1 where blog_id='{$r['blog_id']}' and monthly='{$monthly}'");
    }

    // 태그 갯수 재설정

    echo '카운트 조정이 완료되었습니다.';

} else {
    echo "전제 블로그의 글,댓글,엮인글 등의 카운트를 조정합니다.<p>";
    echo "<input type=button value='시     작' onclick=\"location.href='$PHP_SELF?run=1'\">";
}

include_once("$g4[admin_path]/admin.tail.php");
?>