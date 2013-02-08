<?
include_once("./_common.php");

auth_check($auth[$sub_menu], "w");

if ($is_admin != "super")
    alert("최고관리자만 접근 가능합니다.");

switch( $mode ) 
{
    case("delete"):

        // 첨부파일을 지운돠.
        $qry = sql_query("select * from {$gb4['file_table']} where blog_id='{$blog_id}'");
        while( $res = sql_fetch_array($qry) ) {
            if( !empty($res['save_name']) ) {
                @unlink("{$current['file_path']}/{$res['save_name']}");
                @rmdir("{$current['file_path']}");
            }
        }

        sql_query("delete from {$gb4['blog_table']}             where id='{$blog_id}'");
        sql_query("delete from {$gb4['category_table']}         where blog_id='{$blog_id}'");
        sql_query("delete from {$gb4['post_table']}             where blog_id='{$blog_id}'");
        sql_query("delete from {$gb4['comment_table']}          where blog_id='{$blog_id}'");
        sql_query("delete from {$gb4['trackback_table']}        where blog_id='{$blog_id}'");
        sql_query("delete from {$gb4['taglog_table']}           where blog_id='{$blog_id}'");
        sql_query("delete from {$gb4['file_table']}             where blog_id='{$blog_id}'");
        sql_query("delete from {$gb4['link_table']}             where blog_id='{$blog_id}'");
        sql_query("delete from {$gb4['link_category_table']}    where blog_id='{$blog_id}'");
        sql_query("delete from {$gb4['monthly_table']}          where blog_id='{$blog_id}'");
        sql_query("delete from {$gb4['visit_table']}            where vi_blog_id='{$blog_id}'");
        sql_query("delete from {$gb4['visit_sum_table']}        where vs_blog_id='{$blog_id}'");
        sql_query("delete from {$gb4['guestbook_table']}        where blog_id='{$blog_id}'");
        break;
}

goto_url('blog.php');
?>