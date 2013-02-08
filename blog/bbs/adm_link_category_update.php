<?
include_once("./_common.php");

// mb_id 가 없으면 죽는다.
if( empty($mb_id) ) die("101");

// 자신의 블로그가 없어도 죽는거다.
$current = sql_fetch("select * from {$gb4['blog_table']} where mb_id='{$mb_id}'");
if( empty($current) ) die("101");

// 자신의 블로그가 아닌걸 수정하려고 하면 기냥 죽는거다.
if( $current['mb_id'] != $member['mb_id'] ) die("101");

switch( $m ) { 

    // 분류 순서를 올린다.
    case 'up':

        // 해당 분류가 실제 있는지 조사해본다.
        $r = sql_fetch("select * from {$gb4['link_category_table']} where blog_id='{$current['id']}' and id = '{$ct_id}'");

        // 없으면 죽어..
        if( empty($r) ) die("109");

        sql_query("update {$gb4['link_category_table']} set rank = rank - 1 where blog_id='{$current['id']}' and id = '{$ct_id}'");
        sql_query("update {$gb4['link_category_table']} set rank = rank + 1 where blog_id='{$current['id']}' and id = '{$md_id}'");

        die('000');

    // 분류 순서를 내린다.
    case 'down':

        // 해당 분류가 실제 있는지 조사해본다.
        $r = sql_fetch("select * from {$gb4['link_category_table']} where blog_id='{$current['id']}' and id = '{$ct_id}'");

        // 없으면 죽어..
        if( empty($r) ) die("109");

        sql_query("update {$gb4['link_category_table']} set rank = rank + 1 where blog_id='{$current['id']}' and id = '{$ct_id}'");
        sql_query("update {$gb4['link_category_table']} set rank = rank - 1 where blog_id='{$current['id']}' and id = '{$md_id}'");

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
        $r = sql_fetch("select * from {$gb4['link_category_table']} where blog_id='{$current['id']}' and id = '{$ct_id}'");

        // 없으면 죽어..
        if( empty($r) ) die("109");

        // 같은 이름이 있는지 조사하면 다 나와
        $r = sql_fetch("select * from {$gb4['link_category_table']} where blog_id='{$current['id']}' and category_name = '{$ct_mod_name}'");

        // 없으면 바꿔줘
        if( empty($r) ) {

            // 수정 쿼리 날려본다.
            sql_query("update {$gb4['link_category_table']} set category_name = '$ct_mod_name' where blog_id='{$current['id']}' and id='{$ct_id}'");

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
        $r = sql_fetch("select * from {$gb4['link_category_table']} where blog_id='{$current['id']}' and id = '{$ct_id}'");

        // 없으면 죽어..
        if( empty($r) ) die("109");

        // 해당 분류의 rank 를 깐다.
        $rank = $r['rank'];

        // 분류 삭제 쿼리 날려본다.
        sql_query("delete from {$gb4['link_category_table']} where blog_id='{$current['id']}' and id='{$ct_id}'");

        // 삭제된 분류보다 rank 값이 큰걸 모조리 -1 한다
        sql_query("update {$gb4['link_category_table']} set rank = rank - 1 where blog_id='{$current['id']}' and rank > {$rank}");

        // 대빵이 없어졌으니 새끼들은 전부 길을 잃는거다.
        $res = sql_fetch("select max(rank) as max from {$gb4['link_table']} where blog_id='{$current['id']}' and category_id=0");
        sql_query("update {$gb4['link_table']} set category_id=0, rank=rank+{$res['max']} where blog_id='{$current['id']}' and category_id='{$ct_id}'");

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
        $r = sql_fetch("select * from {$gb4['link_category_table']} where blog_id='{$current['id']}' and category_name = '{$ct_name}'");

        // 없으면 받아드려
        if( empty($r) ) {

            // 순서를 정해 줘야지..
            $r = sql_fetch("select max(rank) as rank from {$gb4['link_category_table']} where blog_id='{$current['id']}'");

            if( !$r['rank'] ) 
                $rank = 1; 
            else 
                $rank = $r['rank'] + 1;

            // 정식으로 입사 시켜준다.
            $sql = "insert into 
                        {$gb4['link_category_table']} 
                    set 
                        blog_id         = '{$current['id']}'
                        ,category_name  = '{$ct_name}'
                        ,category_count = '0'
                        ,rank           = '{$rank}'";
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