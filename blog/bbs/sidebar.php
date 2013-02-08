<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// 사이드바 - 분류
if( $use_sb['category'] ) {

    $category[0] = array( 'blog_id' => $current['id'], 'category_name' => '전체', 'post_count' => $current['post_count'], 'secret_count' => $current['secret_count'], 'rank' => '0', 'url' => get_category_url('all') );

    $qry = sql_query("select * from {$gb4['category_table']} where blog_id='{$current['id']}' order by rank ");
    while( $res = sql_fetch_array($qry) ) {
        if($member['mb_id']==$current['mb_id'])
            $res['post_count'] += $res['secret_count'];
        $res['url'] = get_category_url($res['category_name']);
        array_push($category, $res);
    }
}

// 사이드바 - monthly
if( $use_sb['monthly'] ) {
    $monthly = array();
    $qry = sql_query("select * from {$gb4['monthly_table']} where blog_id='{$current['id']}' and post_count>0 order by monthly desc ");
    while( $res = sql_fetch_array($qry) ) {
        if( $member['mb_id'] == $current['mb_id'] ) 
            $res['post_count'] += $res['secret_count'];
        $res['url'] = get_monthly_url($res['monthly']);
        array_push($monthly, $res);
    }
}


// 사이드바 - tags cloud
if( $current['use_tag'] && $use_sb['tag'] )
    $tags = get_tag_cloud($current['sidebar_tag_print'], $current['sidebar_tag_length']);



// 사이드바 - 최근 글 목록
if( $use_sb['recent_post'] ) {
    $new_post = array();
    $qry = sql_query("select * from {$gb4['post_table']} where blog_id='{$current['id']}' $sql_secret order by post_date desc limit 0,{$current['sidebar_post_num']}");
    while( $res = sql_fetch_array($qry) ) {
        if( strlen($res['title']) > $current['sidebar_post_length'])
            $res['title'] = cut_str($res['title'],$current['sidebar_post_length']);
        $res['url'] = get_post_url($res['id']);
        array_push($new_post, $res);
    }
}


// 사이드바 - 최근 댓글 목록
if( $use_sb['recent_comment'] ) {
    $new_comment = array();
    if( $member['mb_id']==$current['mb_id'] ) $ps = ''; else $ps = 'and p.secret=1';
    $qry = sql_query("select c.* from {$gb4['comment_table']} c, {$gb4['post_table']} p where c.post_id=p.id $ps and c.blog_id='{$current[id]}' order by c.regdate desc limit 0,{$current['sidebar_comment_num']}");
    while( $res = sql_fetch_array($qry) ) {

        if( ! (!$res['secret'] || ($res['secret'] && $current['mb_id'] == $member['mb_id']) || ($res['secret'] && $member['mb_id'] && $res['mb_id'] == $member['mb_id'])) ) {
            $res['writer_content'] = '관리자만 볼 수 있는 댓글 입니다.';
        }
        if( strlen($res['writer_content']) > $current['sidebar_comment_length'])
            $res['writer_content'] = cut_str($res['writer_content'],$current['sidebar_comment_length']);
        $res['url'] = get_comment_url($res['post_id'],$res['id']);
        array_push($new_comment, $res);
    }
}


// 사이드바 - 최근 엮인글 목록
if( $use_sb['recent_trackback'] ) {
    $new_trackback = array();
    $qry = sql_query("select * from {$gb4['trackback_table']} where blog_id='{$current[id]}' order by regdate desc limit 0,{$current['sidebar_trackback_num']}");
    while( $res = sql_fetch_array($qry) ) {
        if( strlen($res['writer_content']) > $current['sidebar_trackback_length'])
            $res['writer_content'] = cut_str($res['writer_content'],$current['sidebar_trackback_length']);
        $res['url'] = get_trackback_url($res['post_id'], $res['id']);
        $res['writer_content'] = htmlspecialchars($res['writer_content']);
        array_push($new_trackback, $res);
    }
}


// 사이드바 - 달력
if( $use_sb['calendar'] ) {
    include_once("$gb4[bbs_path]/calendar.php");
    $calendar = get_calendar($yyyy, $mm, $dd);
}


// 사이드바 - 링크
if( $use_sb['link'] ) {
    $link = array();
    $qry = sql_query("select l.*, c.* from {$gb4['link_table']} l, {$gb4['link_category_table']} c where l.category_id=c.id and l.blog_id='{$current[id]}' order by c.rank,l.rank");
    while($res = sql_fetch_array($qry)) array_push($link, $res);
    if( empty($link) ) $category_name = ''; else $category_name = '기타';

    // 기타분류 링크 출력
    $qry = sql_query("select * from {$gb4['link_table']} where blog_id='{$current[id]}' and category_id=0 order by rank");
    while($res = sql_fetch_array($qry)) {
        $res['category_name'] = $category_name;
        array_push($link, $res);
    }
}
?>