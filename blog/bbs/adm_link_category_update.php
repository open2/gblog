<?
include_once("./_common.php");

// mb_id �� ������ �״´�.
if( empty($mb_id) ) die("101");

// �ڽ��� ��αװ� ��� �״°Ŵ�.
$current = sql_fetch("select * from {$gb4['blog_table']} where mb_id='{$mb_id}'");
if( empty($current) ) die("101");

// �ڽ��� ��αװ� �ƴѰ� �����Ϸ��� �ϸ� ��� �״°Ŵ�.
if( $current['mb_id'] != $member['mb_id'] ) die("101");

switch( $m ) { 

    // �з� ������ �ø���.
    case 'up':

        // �ش� �з��� ���� �ִ��� �����غ���.
        $r = sql_fetch("select * from {$gb4['link_category_table']} where blog_id='{$current['id']}' and id = '{$ct_id}'");

        // ������ �׾�..
        if( empty($r) ) die("109");

        sql_query("update {$gb4['link_category_table']} set rank = rank - 1 where blog_id='{$current['id']}' and id = '{$ct_id}'");
        sql_query("update {$gb4['link_category_table']} set rank = rank + 1 where blog_id='{$current['id']}' and id = '{$md_id}'");

        die('000');

    // �з� ������ ������.
    case 'down':

        // �ش� �з��� ���� �ִ��� �����غ���.
        $r = sql_fetch("select * from {$gb4['link_category_table']} where blog_id='{$current['id']}' and id = '{$ct_id}'");

        // ������ �׾�..
        if( empty($r) ) die("109");

        sql_query("update {$gb4['link_category_table']} set rank = rank + 1 where blog_id='{$current['id']}' and id = '{$ct_id}'");
        sql_query("update {$gb4['link_category_table']} set rank = rank - 1 where blog_id='{$current['id']}' and id = '{$md_id}'");

        die('000');

    // �з��� �����Ѵ�.
    case 'mod':

        // �з� �̸��� ���� �°��� �����غ���. ������ �״´�.
        if( !trim($ct_mod_name) ) die("102");

        $ct_mod_name = rawurldecode($ct_mod_name);
        if( strtoupper($g4['charset']) != 'UTF-8' && is_utf8($ct_mod_name) ) {
            $ct_mod_name = convert_charset('UTF-8','CP949',$ct_mod_name);
        }

        // �ش� �з��� ���� �ִ��� �����غ���.
        $r = sql_fetch("select * from {$gb4['link_category_table']} where blog_id='{$current['id']}' and id = '{$ct_id}'");

        // ������ �׾�..
        if( empty($r) ) die("109");

        // ���� �̸��� �ִ��� �����ϸ� �� ����
        $r = sql_fetch("select * from {$gb4['link_category_table']} where blog_id='{$current['id']}' and category_name = '{$ct_mod_name}'");

        // ������ �ٲ���
        if( empty($r) ) {

            // ���� ���� ��������.
            sql_query("update {$gb4['link_category_table']} set category_name = '$ct_mod_name' where blog_id='{$current['id']}' and id='{$ct_id}'");

            // �����ߴ�.
            die("000"); 

        // ���� �̸��� ������ ������ �ȵǾ�
        } else {

            // �״°Ŵ�.
            die("105"); 

        } // end if

    // �з� ����
    case 'del':

        // �ش� �з��� ���� �ִ��� �����غ���.
        $r = sql_fetch("select * from {$gb4['link_category_table']} where blog_id='{$current['id']}' and id = '{$ct_id}'");

        // ������ �׾�..
        if( empty($r) ) die("109");

        // �ش� �з��� rank �� ���.
        $rank = $r['rank'];

        // �з� ���� ���� ��������.
        sql_query("delete from {$gb4['link_category_table']} where blog_id='{$current['id']}' and id='{$ct_id}'");

        // ������ �з����� rank ���� ū�� ������ -1 �Ѵ�
        sql_query("update {$gb4['link_category_table']} set rank = rank - 1 where blog_id='{$current['id']}' and rank > {$rank}");

        // �뻧�� ���������� �������� ���� ���� �Ҵ°Ŵ�.
        $res = sql_fetch("select max(rank) as max from {$gb4['link_table']} where blog_id='{$current['id']}' and category_id=0");
        sql_query("update {$gb4['link_table']} set category_id=0, rank=rank+{$res['max']} where blog_id='{$current['id']}' and category_id='{$ct_id}'");

        // ������.
        die("000");

    // �з��� ���ı��� ���� �Ѵ�.
    case 'new':

        // �з� �̸��� ���� �°��� �����غ���. ������ �״´�.
        if( !trim($ct_name) ) die("102");
        $ct_name = rawurldecode($ct_name);
        if( strtoupper($g4['charset']) != 'UTF-8' && is_utf8($ct_name) ) {
            $ct_name = convert_charset('UTF-8','CP949',$ct_name);
        }

        // ���� �̸��� �ִ��� �����ϸ� �� ����
        $r = sql_fetch("select * from {$gb4['link_category_table']} where blog_id='{$current['id']}' and category_name = '{$ct_name}'");

        // ������ �޾Ƶ��
        if( empty($r) ) {

            // ������ ���� �����..
            $r = sql_fetch("select max(rank) as rank from {$gb4['link_category_table']} where blog_id='{$current['id']}'");

            if( !$r['rank'] ) 
                $rank = 1; 
            else 
                $rank = $r['rank'] + 1;

            // �������� �Ի� �����ش�.
            $sql = "insert into 
                        {$gb4['link_category_table']} 
                    set 
                        blog_id         = '{$current['id']}'
                        ,category_name  = '{$ct_name}'
                        ,category_count = '0'
                        ,rank           = '{$rank}'";
            sql_query($sql);

            // �Ի� ��ȣ �߱�
            $id = mysql_insert_id();

            // �Ӹ��� ������.
            die("000|".$id); 

        // ���� �̸��� ������ ������ �ȵǾ�
        } else {

            // �״°Ŵ�.
            die("105"); 
        } // end if

} // end select

?>