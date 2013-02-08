<?
include_once("./_common.php");

if( $current['mb_id'] != $member['mb_id'] ) die('001');

$ids = explode(',', $ids);

for ($i=0; $i<count($ids); $i++) {

    $id = trim($ids[$i]);

    if (!$id) continue;

    // 원본글 정보를 가져온다.
    $post = sql_fetch("select * from {$gb4['post_table']} where id='{$id}'");

    if( empty($post) ) die('002');

    if( $post['secret'] == 0 ) 
        $count_field = 'secret_count'; 
    else 
        $count_field = 'post_count';

    switch ($mode) {

        case 'secret': // 비공개로 변경
        case 'open': // 공개로 변경

            if ($mode=='secret') 
                $secret = 0;
            else
                $secret = 1;

            if( $post['secret'] != $secret ) {
                if( $secret == 0 ) {
                    $count_field_prev = 'post_count'; 
                    $count_field_next = 'secret_count'; 
                } else {
                    $count_field_prev = 'secret_count';
                    $count_field_next = 'post_count';
                }
            } else {
                continue;
            }

            // 원본글을 수정한다.
            sql_query("update {$gb4['post_table']} set secret = '{$secret}' where id='{$id}'");

            // 블로그 정보에서 글 카운트 조정
            sql_query("update {$gb4['blog_table']} set {$count_field_prev} = {$count_field_prev} - 1, {$count_field_next} = {$count_field_next} + 1 where id = '{$current['id']}'");

            // 해당 분류의 글 카운트를 조정한다.
            sql_query("update {$gb4['category_table']} set {$count_field_prev} = {$count_field_prev} - 1 where id = '{$post['category_id']}'");
            sql_query("update {$gb4['category_table']} set {$count_field_next} = {$count_field_next} + 1 where id = '{$category_id}'");

            break;

        case 'del': // 글 삭제

            // 글을 지운돠.
            sql_query("delete from {$gb4['post_table']} where id='{$id}'");

            // 첨부파일을 지운돠.
            $qry = sql_query("select * from {$gb4['file_table']} where blog_id='{$current['id']}' and post_id='{$id}'");
            while( $res = sql_fetch_array($qry) ) {
                if( !empty($res['save_name']) ) {
                    @unlink("{$current['file_path']}/{$res['save_name']}");
                }
            }
            sql_query("delete from {$gb4['file_table']} where post_id='{$id}'");

            // 태그를 지운돠.
            sql_query("delete from {$gb4['taglog_table']} where post_id='{$id}'");

            // 댓글을 지운돠.
            sql_query("delete from {$gb4['comment_table']} where post_id='{$id}'");

            // 엮인글을 지운돠.
            sql_query("delete from {$gb4['trackback_table']} where post_id='{$id}'");

            // 해당 분류의 글 카운트를 감소시킨돠.
            if( $post['category_id'] )
                sql_query("update {$gb4['category_table']} set {$count_field} = {$count_field} - 1 where id = '{$post['category_id']}'");

            // 해당 블로그 정보 테이블에 글,댓글,엮인글 갯수 감소 및 마지막 업데이트 시간을 현재로 변경한돠.
            $sql = "update
                        {$gb4['blog_table']}
                    set
                         {$count_field} = {$count_field} - 1
                        ,comment_count = comment_count - {$post['comment_count']}
                        ,trackback_count = trackback_count - {$post['trackback_count']}
                        ,last_update = '{$g4['time_ymdhis']}'
                    where
                        mb_id='{$member['mb_id']}'";
            sql_query($sql);

            // 월별 갯수를 감소한돠.
            $monthly = substr($post['post_date'],0,7);
            sql_query("update {$gb4['monthly_table']} set {$count_field} = {$count_field} - 1 where blog_id = '{$current['id']}' and monthly = '{$monthly}'");
            break;

        default: // 분류이동

            $category_id = $mode;

            // 원본글을 수정한다.
            sql_query("update {$gb4['post_table']} set category_id = '{$category_id}' where id='{$id}'");

            sql_query("update {$gb4['category_table']} set {$count_field} = {$count_field} - 1 where id = '{$post['category_id']}'");
            sql_query("update {$gb4['category_table']} set {$count_field} = {$count_field} + 1 where id = '{$category_id}'");

            // 해당 블로그 정보 테이블에 마지막 업데이트 시간을 현재로 변경한다.
            sql_query("update {$gb4['blog_table']} set last_update = '{$g4['time_ymdhis']}' where mb_id='{$member['mb_id']}'");

            break;
    }
}

die('000');
?>