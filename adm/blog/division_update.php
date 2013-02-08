<?
include_once('./_common.php');

auth_check($auth[$sub_menu], "r");

$g4[title] = "블로그 분류관리";

switch( $mode ) { 

    // 분류 순서를 올린다.
    case 'up':

        // 해당 분류가 실제 있는지 조사해본다.
        $r = sql_fetch("select * from {$gb4['division_table']} where dv_id = '{$ct_id}'");

        // 없으면 죽어..
        if( empty($r) ) die("109");

        sql_query("update {$gb4['division_table']} set dv_rank = dv_rank - 1 where dv_id = '{$ct_id}'");
        sql_query("update {$gb4['division_table']} set dv_rank = dv_rank + 1 where dv_id = '{$md_id}'");

        die('000');

    // 분류 순서를 내린다.
    case 'down':

        // 해당 분류가 실제 있는지 조사해본다.
        $r = sql_fetch("select * from {$gb4['division_table']} where dv_id = '{$ct_id}'");

        // 없으면 죽어..
        if( empty($r) ) die("109");

        sql_query("update {$gb4['division_table']} set dv_rank = dv_rank + 1 where dv_id = '{$ct_id}'");
        sql_query("update {$gb4['division_table']} set dv_rank = dv_rank - 1 where dv_id = '{$md_id}'");

        die('000');

    // 분류를 수정한다.
    case 'mod':

        // 분류 이름은 끌고 온건지 조사해본다. 없으면 죽는다.
        if( !trim($ct_mod_name) ) die("102");

        $ct_mod_name = rawurldecode($ct_mod_name);
        if( strtoupper($g4['charset']) != 'UTF-8' && is_utf8($ct_mod_name) ) {
            $ct_mod_name = convert_charset('UTF-8','CP949',$ct_mod_name);
        }

        // 해당 분류가 실제 있는지 조사해본다.
        $r = sql_fetch("select * from {$gb4['division_table']} where dv_id = '{$ct_id}'");

        // 없으면 죽어..
        if( empty($r) ) die("109");

        // 같은 이름이 있는지 조사하면 다 나와
        $r = sql_fetch("select * from {$gb4['division_table']} where dv_name = '{$ct_mod_name}'");

        // 없으면 바꿔줘
        if( empty($r) ) {

            // 수정 쿼리 날려본다.
            sql_query("update {$gb4['division_table']} set dv_name = '{$ct_mod_name}' where dv_id='{$ct_id}'");

            // 성공했다.
            die("000"); 

        // 같은 이름이 있으면 수정이 안되야
        } else {

            // 죽는거다.
            die("105"); 

        } // end if

    // 분류 삭제
    case 'del':

        // 해당 분류가 실제 있는지 조사해본다.
        $r = sql_fetch("select * from {$gb4['division_table']} where dv_id = '{$ct_id}'");

        // 없으면 죽어..
        if( empty($r) ) die("109");

        // 해당 분류의 dv_rank 를 깐다.
        $dv_rank = $r['dv_rank'];

        // 분류 삭제 쿼리 날려본다.
        sql_query("delete from {$gb4['division_table']} where dv_id='{$ct_id}'");

        // 삭제된 분류보다 dv_rank 값이 큰걸 모조리 -1 한다
        sql_query("update {$gb4['division_table']} set dv_rank = dv_rank - 1 where dv_rank > {$dv_rank}");

        // 대빵이 없어졌으니 새끼들은 전부 길을 잃는거다.
        sql_query("update {$gb4['post_table']} set division_id=0 where division_id='{$ct_id}'");

        // 좋덴다.
        die("000");

    // 분류가 새식구를 맞이 한다.
    case 'new':

        // 분류 이름은 끌고 온건지 조사해본다. 없으면 죽는다.
        if( !trim($ct_name) ) die("102");

        $ct_name = rawurldecode($ct_name);
        if( strtoupper($g4['charset']) != 'UTF-8' && is_utf8($ct_name) ) {
            $ct_name = convert_charset('UTF-8','CP949',$ct_name);
        }

        // 같은 이름이 있는지 조사하면 다 나와
        $r = sql_fetch("select * from {$gb4['division_table']} where dv_name = '{$ct_name}'");

        // 없으면 받아드려
        if( empty($r) ) {

            // 순서를 정해 줘야지..
            $r = sql_fetch("select max(dv_rank) as dv_rank from {$gb4['division_table']} ");

            if( !$r['dv_rank'] ) 
                $dv_rank = 1; 
            else 
                $dv_rank = $r['dv_rank'] + 1;

            // 정식으로 입사 시켜준다.
            $sql = "insert into 
                        {$gb4['division_table']} 
                    set 
                        dv_name  = '{$ct_name}'
                        ,dv_rank = '{$dv_rank}'";
            sql_query($sql);

            // 입사 번호 발급
            $id = mysql_insert_id();

            // 임명장 보낸다.
            die("000|".$id); 

        // 같은 이름이 있으면 가입이 안되야
        } else {

            // 죽는거다.
            die("105"); 
        } // end if

} // end select

?>