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
        $r = sql_fetch("select * from {$gb4['link_table']} where blog_id='{$current['id']}' and id = '{$link_id}'");

        // ������ �׾�..
        if( empty($r) ) die("109");

        sql_query("update {$gb4['link_table']} set rank = rank - 1 where blog_id='{$current['id']}' and category_id='{$ct_id}' and id = '{$link_id}'");
        sql_query("update {$gb4['link_table']} set rank = rank + 1 where blog_id='{$current['id']}' and category_id='{$ct_id}' and id = '{$md_id}'");

        die('000');

    // �з� ������ ������.
    case 'down':

        // �ش� �з��� ���� �ִ��� �����غ���.
        $r = sql_fetch("select * from {$gb4['link_table']} where blog_id='{$current['id']}' and id = '{$link_id}'");

        // ������ �׾�..
        if( empty($r) ) die("109");

        sql_query("update {$gb4['link_table']} set rank = rank + 1 where blog_id='{$current['id']}' and category_id='{$ct_id}' and id = '{$link_id}'");
        sql_query("update {$gb4['link_table']} set rank = rank - 1 where blog_id='{$current['id']}' and category_id='{$ct_id}' and id = '{$md_id}'");

        die('000');

    // �з��� �����Ѵ�.
    case 'mod':

        // �з� �̸��� ���� �°��� �����غ���. ������ �״´�.
        if( !trim($link_mod_name) ) die("102");

        $link_mod_name = rawurldecode($link_mod_name);
        if( strtoupper($g4['charset']) != 'UTF-8' && is_utf8($link_mod_name) ) {
            $link_mod_name = convert_charset('UTF-8','CP949',$link_mod_name);
        }

        // �ּҰ� �ִ��� �˻��Ѵ�.
        if( !trim($link_mod_url) ) die("103");

        $link_mod_url = rawurldecode($link_mod_url);
        if( strtoupper($g4['charset']) != 'UTF-8' && is_utf8($link_mod_url) ) {
            $link_mod_url = convert_charset('UTF-8','CP949',$link_mod_url);
        }

        // �ش� �з��� ���� �ִ��� �����غ���.
        $r = sql_fetch("select * from {$gb4['link_table']} where blog_id='{$current['id']}' and id = '{$link_id}'");

        // ������ �׾�..
        if( empty($r) ) die("109");

        // ���� �̸��� �ִ��� �����ϸ� �� ����
        $r = sql_fetch("select * from {$gb4['link_table']} where blog_id='{$current['id']}' and category_id='{$ct_id}' and site_name = '{$link_mod_name}' and site_url = '{$link_mod_url}'");

        // ������ �ٲ���
        if( empty($r) ) {

            $link_mod_url = str_replace("http://","",$link_mod_url);

            // ���� ���� ��������.
            sql_query("update {$gb4['link_table']} set site_name = '$link_mod_name', site_url = '$link_mod_url' where blog_id='{$current['id']}' and id='{$link_id}'");

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
        $r = sql_fetch("select * from {$gb4['link_table']} where blog_id='{$current['id']}' and category_id='{$ct_id}' and id = '{$link_id}'");

        // ������ �׾�..
        if( empty($r) ) die("109");

        // �ش� �з��� rank �� ���.
        $rank = $r['rank'];

        // �з� ���� ���� ��������.
        sql_query("delete from {$gb4['link_table']} where blog_id='{$current['id']}' and id='{$link_id}'");

        // ������ �з����� rank ���� ū�� ������ -1 �Ѵ�
        sql_query("update {$gb4['link_table']} set rank = rank - 1 where blog_id='{$current['id']}' and category_id='{$ct_id}' and rank > {$rank}");

        // ������.
        die("000");

    // �з��� ���ı��� ���� �Ѵ�.
    case 'new':

        // �з� �̸��� ���� �°��� �����غ���. ������ �״´�.
        if( !trim($link_name) ) die("102");

        $link_name = rawurldecode($link_name);
        if( strtoupper($g4['charset']) != 'UTF-8' && is_utf8($link_name) ) {
            $link_name = convert_charset('UTF-8','CP949',$link_name);
        }

        // �ּҰ� �ִ��� �˻��Ѵ�.
        if( !trim($link_url) ) die("103");
        $link_url = rawurldecode($link_url);
        if( strtoupper($g4['charset']) != 'UTF-8' && is_utf8($link_url) ) {
            $link_url = convert_charset('UTF-8','CP949',$link_url);
        }

        // ���� �̸��� �ִ��� �����ϸ� �� ����
        $r = sql_fetch("select * from {$gb4['link_table']} where blog_id='{$current['id']}' and category_id='{$ct_id}' and (site_name = '{$link_name}' or site_url = '{$link_url}')");

        // ������ �޾Ƶ��
        if( empty($r) ) {

            // ������ ���� �����..
            $r = sql_fetch("select max(rank) as rank from {$gb4['link_table']} where blog_id='{$current['id']}' and category_id='{$ct_id}'");

            if( !$r['rank'] ) 
                $rank = 1; 
            else 
                $rank = $r['rank'] + 1;

            $link_url = str_replace("http://","",$link_url);

            // �������� �Ի� �����ش�.
            $sql = "insert into 
                        {$gb4['link_table']} 
                    set 
                        blog_id         = '{$current['id']}'
                        ,category_id    = '{$ct_id}'
                        ,site_name      = '{$link_name}'
                        ,site_url       = '{$link_url}'
                        ,rank           = '{$rank}'";
            sql_query($sql);

            // �Ի� ��ȣ �߱�
            $id = mysql_insert_id();

            // �Ӹ��� ������.
            die("000"); 

        // ���� �̸��� ������ ������ �ȵǾ�
        } else {

            // �״°Ŵ�.
            die("105"); 
        } // end if


        break;

    case 'reload' :
        header("Content-Type:text/xml;");
        echo "<?xml version=\"1.0\" encoding=\"{$g4[charset]}\"?>\n"; 
        echo "<channel>\n";
        $q = sql_query("select * from {$gb4['link_table']} where blog_id='{$current['id']}' and category_id='{$ct_id}' order by rank");
        while($r = sql_fetch_array($q)) {
            echo "<item>\n";
            echo "<id>{$r['id']}</id>\n";
            echo "<site_name><![CDATA[{$r['site_name']}]]></site_name>\n";
            echo "<site_url><![CDATA[{$r['site_url']}]]></site_url>\n";
            echo "</item>\n";
        }
        echo "</channel>\n";
        break;

} // end select
?>