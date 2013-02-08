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
        $r = sql_fetch("select * from {$gb4['link_table']} where blog_id='{$current['id']}' and id = '{$link_id}'");

        // 없으면 죽어..
        if( empty($r) ) die("109");

        sql_query("update {$gb4['link_table']} set rank = rank - 1 where blog_id='{$current['id']}' and category_id='{$ct_id}' and id = '{$link_id}'");
        sql_query("update {$gb4['link_table']} set rank = rank + 1 where blog_id='{$current['id']}' and category_id='{$ct_id}' and id = '{$md_id}'");

        die('000');

    // 분류 순서를 내린다.
    case 'down':

        // 해당 분류가 실제 있는지 조사해본다.
        $r = sql_fetch("select * from {$gb4['link_table']} where blog_id='{$current['id']}' and id = '{$link_id}'");

        // 없으면 죽어..
        if( empty($r) ) die("109");

        sql_query("update {$gb4['link_table']} set rank = rank + 1 where blog_id='{$current['id']}' and category_id='{$ct_id}' and id = '{$link_id}'");
        sql_query("update {$gb4['link_table']} set rank = rank - 1 where blog_id='{$current['id']}' and category_id='{$ct_id}' and id = '{$md_id}'");

        die('000');

    // 분류를 수정한다.
    case 'mod':

        // 분류 이름은 끌고 온건지 조사해본다. 없으면 죽는다.
        if( !trim($link_mod_name) ) die("102");

        $link_mod_name = rawurldecode($link_mod_name);
        if( strtoupper($g4['charset']) != 'UTF-8' && is_utf8($link_mod_name) ) {
            $link_mod_name = convert_charset('UTF-8','CP949',$link_mod_name);
        }

        // 주소가 있는지 검사한다.
        if( !trim($link_mod_url) ) die("103");

        $link_mod_url = rawurldecode($link_mod_url);
        if( strtoupper($g4['charset']) != 'UTF-8' && is_utf8($link_mod_url) ) {
            $link_mod_url = convert_charset('UTF-8','CP949',$link_mod_url);
        }

        // 해당 분류가 실제 있는지 조사해본다.
        $r = sql_fetch("select * from {$gb4['link_table']} where blog_id='{$current['id']}' and id = '{$link_id}'");

        // 없으면 죽어..
        if( empty($r) ) die("109");

        // 같은 이름이 있는지 조사하면 다 나와
        $r = sql_fetch("select * from {$gb4['link_table']} where blog_id='{$current['id']}' and category_id='{$ct_id}' and site_name = '{$link_mod_name}' and site_url = '{$link_mod_url}'");

        // 없으면 바꿔줘
        if( empty($r) ) {

            $link_mod_url = str_replace("http://","",$link_mod_url);

            // 수정 쿼리 날려본다.
            sql_query("update {$gb4['link_table']} set site_name = '$link_mod_name', site_url = '$link_mod_url' where blog_id='{$current['id']}' and id='{$link_id}'");

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
        $r = sql_fetch("select * from {$gb4['link_table']} where blog_id='{$current['id']}' and category_id='{$ct_id}' and id = '{$link_id}'");

        // 없으면 죽어..
        if( empty($r) ) die("109");

        // 해당 분류의 rank 를 깐다.
        $rank = $r['rank'];

        // 분류 삭제 쿼리 날려본다.
        sql_query("delete from {$gb4['link_table']} where blog_id='{$current['id']}' and id='{$link_id}'");

        // 삭제된 분류보다 rank 값이 큰걸 모조리 -1 한다
        sql_query("update {$gb4['link_table']} set rank = rank - 1 where blog_id='{$current['id']}' and category_id='{$ct_id}' and rank > {$rank}");

        // 좋덴다.
        die("000");

    // 분류가 새식구를 맞이 한다.
    case 'new':

        // 분류 이름은 끌고 온건지 조사해본다. 없으면 죽는다.
        if( !trim($link_name) ) die("102");

        $link_name = rawurldecode($link_name);
        if( strtoupper($g4['charset']) != 'UTF-8' && is_utf8($link_name) ) {
            $link_name = convert_charset('UTF-8','CP949',$link_name);
        }

        // 주소가 있는지 검사한다.
        if( !trim($link_url) ) die("103");
        $link_url = rawurldecode($link_url);
        if( strtoupper($g4['charset']) != 'UTF-8' && is_utf8($link_url) ) {
            $link_url = convert_charset('UTF-8','CP949',$link_url);
        }

        // 같은 이름이 있는지 조사하면 다 나와
        $r = sql_fetch("select * from {$gb4['link_table']} where blog_id='{$current['id']}' and category_id='{$ct_id}' and (site_name = '{$link_name}' or site_url = '{$link_url}')");

        // 없으면 받아드려
        if( empty($r) ) {

            // 순서를 정해 줘야지..
            $r = sql_fetch("select max(rank) as rank from {$gb4['link_table']} where blog_id='{$current['id']}' and category_id='{$ct_id}'");

            if( !$r['rank'] ) 
                $rank = 1; 
            else 
                $rank = $r['rank'] + 1;

            $link_url = str_replace("http://","",$link_url);

            // 정식으로 입사 시켜준다.
            $sql = "insert into 
                        {$gb4['link_table']} 
                    set 
                        blog_id         = '{$current['id']}'
                        ,category_id    = '{$ct_id}'
                        ,site_name      = '{$link_name}'
                        ,site_url       = '{$link_url}'
                        ,rank           = '{$rank}'";
            sql_query($sql);

            // 입사 번호 발급
            $id = mysql_insert_id();

            // 임명장 보낸다.
            die("000"); 

        // 같은 이름이 있으면 가입이 안되야
        } else {

            // 죽는거다.
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