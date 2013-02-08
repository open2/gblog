<?
include_once("./_common.php");

if( $current['mb_id'] != $member['mb_id'] ) die('001');

$ids = explode(',', $ids);

for ($i=0; $i<count($ids); $i++) {

    $id = trim($ids[$i]);

    if (!$id) continue;

    // ������ ������ �����´�.
    $post = sql_fetch("select * from {$gb4['post_table']} where id='{$id}'");

    if( empty($post) ) die('002');

    if( $post['secret'] == 0 ) 
        $count_field = 'secret_count'; 
    else 
        $count_field = 'post_count';

    switch ($mode) {

        case 'secret': // ������� ����
        case 'open': // ������ ����

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

            // �������� �����Ѵ�.
            sql_query("update {$gb4['post_table']} set secret = '{$secret}' where id='{$id}'");

            // ��α� �������� �� ī��Ʈ ����
            sql_query("update {$gb4['blog_table']} set {$count_field_prev} = {$count_field_prev} - 1, {$count_field_next} = {$count_field_next} + 1 where id = '{$current['id']}'");

            // �ش� �з��� �� ī��Ʈ�� �����Ѵ�.
            sql_query("update {$gb4['category_table']} set {$count_field_prev} = {$count_field_prev} - 1 where id = '{$post['category_id']}'");
            sql_query("update {$gb4['category_table']} set {$count_field_next} = {$count_field_next} + 1 where id = '{$category_id}'");

            break;

        case 'del': // �� ����

            // ���� �����.
            sql_query("delete from {$gb4['post_table']} where id='{$id}'");

            // ÷�������� �����.
            $qry = sql_query("select * from {$gb4['file_table']} where blog_id='{$current['id']}' and post_id='{$id}'");
            while( $res = sql_fetch_array($qry) ) {
                if( !empty($res['save_name']) ) {
                    @unlink("{$current['file_path']}/{$res['save_name']}");
                }
            }
            sql_query("delete from {$gb4['file_table']} where post_id='{$id}'");

            // �±׸� �����.
            sql_query("delete from {$gb4['taglog_table']} where post_id='{$id}'");

            // ����� �����.
            sql_query("delete from {$gb4['comment_table']} where post_id='{$id}'");

            // ���α��� �����.
            sql_query("delete from {$gb4['trackback_table']} where post_id='{$id}'");

            // �ش� �з��� �� ī��Ʈ�� ���ҽ�Ų��.
            if( $post['category_id'] )
                sql_query("update {$gb4['category_table']} set {$count_field} = {$count_field} - 1 where id = '{$post['category_id']}'");

            // �ش� ��α� ���� ���̺� ��,���,���α� ���� ���� �� ������ ������Ʈ �ð��� ����� �����ѵ�.
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

            // ���� ������ �����ѵ�.
            $monthly = substr($post['post_date'],0,7);
            sql_query("update {$gb4['monthly_table']} set {$count_field} = {$count_field} - 1 where blog_id = '{$current['id']}' and monthly = '{$monthly}'");
            break;

        default: // �з��̵�

            $category_id = $mode;

            // �������� �����Ѵ�.
            sql_query("update {$gb4['post_table']} set category_id = '{$category_id}' where id='{$id}'");

            sql_query("update {$gb4['category_table']} set {$count_field} = {$count_field} - 1 where id = '{$post['category_id']}'");
            sql_query("update {$gb4['category_table']} set {$count_field} = {$count_field} + 1 where id = '{$category_id}'");

            // �ش� ��α� ���� ���̺� ������ ������Ʈ �ð��� ����� �����Ѵ�.
            sql_query("update {$gb4['blog_table']} set last_update = '{$g4['time_ymdhis']}' where mb_id='{$member['mb_id']}'");

            break;
    }
}

die('000');
?>