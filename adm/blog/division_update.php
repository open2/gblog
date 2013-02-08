<?
include_once('./_common.php');

auth_check($auth[$sub_menu], "r");

$g4[title] = "��α� �з�����";

switch( $mode ) { 

    // �з� ������ �ø���.
    case 'up':

        // �ش� �з��� ���� �ִ��� �����غ���.
        $r = sql_fetch("select * from {$gb4['division_table']} where dv_id = '{$ct_id}'");

        // ������ �׾�..
        if( empty($r) ) die("109");

        sql_query("update {$gb4['division_table']} set dv_rank = dv_rank - 1 where dv_id = '{$ct_id}'");
        sql_query("update {$gb4['division_table']} set dv_rank = dv_rank + 1 where dv_id = '{$md_id}'");

        die('000');

    // �з� ������ ������.
    case 'down':

        // �ش� �з��� ���� �ִ��� �����غ���.
        $r = sql_fetch("select * from {$gb4['division_table']} where dv_id = '{$ct_id}'");

        // ������ �׾�..
        if( empty($r) ) die("109");

        sql_query("update {$gb4['division_table']} set dv_rank = dv_rank + 1 where dv_id = '{$ct_id}'");
        sql_query("update {$gb4['division_table']} set dv_rank = dv_rank - 1 where dv_id = '{$md_id}'");

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
        $r = sql_fetch("select * from {$gb4['division_table']} where dv_id = '{$ct_id}'");

        // ������ �׾�..
        if( empty($r) ) die("109");

        // ���� �̸��� �ִ��� �����ϸ� �� ����
        $r = sql_fetch("select * from {$gb4['division_table']} where dv_name = '{$ct_mod_name}'");

        // ������ �ٲ���
        if( empty($r) ) {

            // ���� ���� ��������.
            sql_query("update {$gb4['division_table']} set dv_name = '{$ct_mod_name}' where dv_id='{$ct_id}'");

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
        $r = sql_fetch("select * from {$gb4['division_table']} where dv_id = '{$ct_id}'");

        // ������ �׾�..
        if( empty($r) ) die("109");

        // �ش� �з��� dv_rank �� ���.
        $dv_rank = $r['dv_rank'];

        // �з� ���� ���� ��������.
        sql_query("delete from {$gb4['division_table']} where dv_id='{$ct_id}'");

        // ������ �з����� dv_rank ���� ū�� ������ -1 �Ѵ�
        sql_query("update {$gb4['division_table']} set dv_rank = dv_rank - 1 where dv_rank > {$dv_rank}");

        // �뻧�� ���������� �������� ���� ���� �Ҵ°Ŵ�.
        sql_query("update {$gb4['post_table']} set division_id=0 where division_id='{$ct_id}'");

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
        $r = sql_fetch("select * from {$gb4['division_table']} where dv_name = '{$ct_name}'");

        // ������ �޾Ƶ��
        if( empty($r) ) {

            // ������ ���� �����..
            $r = sql_fetch("select max(dv_rank) as dv_rank from {$gb4['division_table']} ");

            if( !$r['dv_rank'] ) 
                $dv_rank = 1; 
            else 
                $dv_rank = $r['dv_rank'] + 1;

            // �������� �Ի� �����ش�.
            $sql = "insert into 
                        {$gb4['division_table']} 
                    set 
                        dv_name  = '{$ct_name}'
                        ,dv_rank = '{$dv_rank}'";
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