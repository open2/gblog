<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ� 

// ��ǻ���� �����ǿ� ��Ű�� ����� �����ǰ� �ٸ��ٸ� ���̺� �ݿ���
if (get_cookie('ck_blog_visit_ip_'.$current['mb_id']) != $_SERVER['REMOTE_ADDR']) {
    set_cookie('ck_blog_visit_ip_'.$current['mb_id'], $_SERVER['REMOTE_ADDR'], 86400); // �Ϸ絿�� ����

    // vi_id�� auto_increment�� �����Կ� ���� ���ʿ���
    //$tmp_row = sql_fetch(" select max(vi_id) as max_vi_id from {$gb4['visit_table']} ");
    //$vi_id = $tmp_row[max_vi_id] + 1;

    $sql = " insert {$gb4['visit_table']} ( vi_blog_id, vi_mb_id, vi_ip, vi_date, vi_time, vi_referer, vi_agent ) values ( '$current[id]', '$member[mb_id]', '$_SERVER[REMOTE_ADDR]', '$g4[time_ymd]', '$g4[time_his]', '$_SERVER[HTTP_REFERER]', '$_SERVER[HTTP_USER_AGENT]' ) ";
    $result = sql_query($sql, FALSE);
    // �������� INSERT �Ǿ��ٸ� �湮�� �հ迡 �ݿ�
    if ($result) {
      
        // UPDATE�� �����ϰ� ������ �߻��� insert�� ���� (����������)
        $sql = " update {$gb4['visit_sum_table']} set vs_count = vs_count + 1 where vs_blog_id = '$current[id]' and vs_date = '$g4[time_ymd]' ";
        $result = sql_query($sql, FALSE);
        
        if ( mysql_affected_rows() == 0 ) {
            $sql = " insert {$gb4['visit_sum_table']} ( vs_blog_id, vs_count, vs_date) values ( '$current[id]', 1, '$g4[time_ymd]' ) ";
            $result = sql_query($sql);
        }

        // INSERT, UPDATE �Ȱ��� �ִٸ� �⺻ȯ�漳�� ���̺� ����
        // �湮�� ���ӽø��� ���� ������ ���� �ʱ� ���� (��û�� ������ ���� ^^)

        // ����
        $sql = " select vs_count as cnt from {$gb4['visit_sum_table']} where vs_blog_id = '$current[id]' and vs_date = '$g4[time_ymd]' ";
        $row = sql_fetch($sql);
        $vi_today = $row[cnt];

        // ����
        $sql = " select vs_count as cnt from {$gb4['visit_sum_table']} where vs_blog_id = '$current[id]' and  vs_date = DATE_SUB('$g4[time_ymd]', INTERVAL 1 DAY) ";
        $row = sql_fetch($sql);
        $vi_yesterday = $row[cnt];

        // �ִ�,��ü - ���������Բ��� SQL 2���� 1���� �ٿ��ּ̽��ϴ�.
        $sql = " select max(vs_count) as cnt, sum(vs_count) as total from {$gb4['visit_sum_table']} where vs_blog_id = '$current[id]' ";
        $row = sql_fetch($sql);
        $vi_sum = $row[total];
        $vi_max = $row[cnt];

        //$visit = "����:$vi_today,����:$vi_yesterday,�ִ�:$vi_max,��ü:$vi_sum";

        // �⺻���� ���̺� �湮�ڼ��� ����� �� 
        // �湮�ڼ� ���̺��� ���� �ʰ� ����Ѵ�.
        // ������ ���� ���κ� ����
        sql_query(" update {$gb4['blog_table']} set visit_today='{$vi_today}', visit_yesterday='{$vi_yesterday}', visit_max='{$vi_max}', visit_total='{$vi_sum}' where id='{$current[id]}' ");
    }
}
?>