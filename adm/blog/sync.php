<?
include_once('./_common.php');

auth_check($auth[$sub_menu], "r");

$g4[title] = "ī��Ʈ ����";

include_once("$g4[admin_path]/admin.head.php");


if ($run)
{
    // ��ü ��/���/Ʈ���� ���� �缳��
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

    // �з��� �� ���� �缳��
    $q = sql_query("select * from $gb4[category_table] order by id");
    while($r = sql_fetch_array($q)){
        $p = sql_fetch("select count(*) as cnt from $gb4[post_table] where category_id='{$r['id']}' and secret=1");
        sql_query("update $gb4[category_table] set post_count='{$p['cnt']}' where id='{$r['id']}'");
        $p = sql_fetch("select count(*) as cnt from $gb4[post_table] where category_id='{$r['id']}' and secret=0");
        sql_query("update $gb4[category_table] set secret_count='{$p['cnt']}' where id='{$r['id']}'");
    }

    // �۰� ��� ���� �缳��
    $q = sql_query("select * from $gb4[comment_table] order by id");
    while($r = sql_fetch_array($q)){
        $c = sql_fetch("select blog_id from $gb4[post_table] where id='{$r['post_id']}'");
        sql_query("update $gb4[comment_table] set blog_id='{$c['blog_id']}' where post_id='{$r['post_id']}'");
    }

    // �ۺ� ��� ���� �缳��
    $q = sql_query("select * from $gb4[post_table] order by id");
    while($r = sql_fetch_array($q)){
        $c = sql_fetch("select count(*) as cnt from $gb4[comment_table] where post_id='{$r['id']}'");
        sql_query("update $gb4[post_table] set comment_count='{$c['cnt']}' where id='{$r['id']}'");
    }

    // �ۺ� ���α� ���� �缳��
    $q = sql_query("select * from $gb4[post_table] order by id");
    while($r = sql_fetch_array($q)){
        $c = sql_fetch("select count(*) as cnt from $gb4[trackback_table] where post_id='{$r['id']}'");
        sql_query("update $gb4[post_table] set trackback_count='{$c['cnt']}' where id='{$r['id']}'");
    }


    // ���� �� ���� �缳��
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

    // �±� ���� �缳��

    echo 'ī��Ʈ ������ �Ϸ�Ǿ����ϴ�.';

} else {
    echo "���� ��α��� ��,���,���α� ���� ī��Ʈ�� �����մϴ�.<p>";
    echo "<input type=button value='��     ��' onclick=\"location.href='$PHP_SELF?run=1'\">";
}

include_once("$g4[admin_path]/admin.tail.php");
?>