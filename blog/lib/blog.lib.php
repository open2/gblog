<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 

/*
-----------------------------------------------------------
    블로그 메인 글을 배열로 반환
-----------------------------------------------------------
*/
function get_blog_main($dv_id='', $st='', $sv='', $limit=15, $page=1)
{
    global $g4, $gb4, $blog_search_paging;

    $ret = array();

    if (!$page) $page = 1;

    $sql_select = " p.*, b.mb_id, b.mb_nick as writer, c.category_name ";
    $sql_from = " $gb4[post_table] as p left join $g4[member_table] as b on p.blog_id = b.mb_no left join $gb4[category_table] as c on p.category_id = c.id ";

    if ($dv_id)
        $sql_dv_id = " and division_id='$dv_id' ";

    if ($st && $sv)  {
        switch ($st) {
            case 'mb_id':
            case 'mb_nick':
            case 'mb_id|mb_nick':
            case 'title':
            case 'content':
            case 'title|content':
                $is_search = true; break;
            default: 
                $is_search = false;
        }

        if ($is_search) {
            if(strstr($st, '|')) {
                $arr = explode('|', $st);
                $sql_search = " and (".$arr[0]." LIKE '%$sv%' ";
                for ($i=1; $i<count($arr); $i++) {
                    $sql_search .= " or ".$arr[$i]." LIKE '%$sv%' ";
                }
                $sql_search .= ")";
            } else {
                $sql_search = " and $st LIKE '%$sv%' ";
            }
        }
    }

    $sql_where = " p.secret=1 $sql_dv_id $sql_search ";
    $sql_order = " p.id desc ";

    $row = sql_fetch(" select count(*) as cnt from $sql_from where $sql_where ");
    $total_post = $row[cnt];

    $total_page = (int)($total_post/$limit) + ($total_post%$limit==0 ? 0 : 1);
    $page_start = $limit * ($page - 1);

    $sql = " select $sql_select from $sql_from where $sql_where order by $sql_order limit $page_start, $limit ";
    $qry = sql_query($sql);
    while ($row = sql_fetch_array($qry)) 
    {
        if (!$row[category_name]) 
            $row[category_name] = '분류없음';

        $row[post_date] = date("m-d", strtotime($row[post_date]));

        $row[content] = strip_tags($row[content]);
        $row[content] = cut_str($row[content], 300);

        $ret[] = $row;
    }

    $blog_search_paging = get_paging(10, $page, $total_page, "$PHP_SELF?st=$st&sv=$sv&page="); 

    return $ret;
}

/*
-----------------------------------------------------------
    explode 와 trim 을 동시에
-----------------------------------------------------------
*/
function explode_trim($ch, $str) {
    $str = explode($ch, $str);
    for ($i=0; $i<sizeof($str); $i++)
        $str[$i] = trim($str[$i]);
    return $str;
}

/*
-----------------------------------------------------------
    xml 파일 출력
-----------------------------------------------------------
*/
function echo_xml($xml) 
{
    header("Content-type: text/xml"); 
    header("Cache-Control: no-cache, must-revalidate"); 
    header("Pragma: no-cache");   
    echo $xml;
}

/*
-----------------------------------------------------------
    특수문자 변환
-----------------------------------------------------------
*/
function specialchars_replace($str, $len=0) 
{
    if ($len) 
        $str = substr($str, 0, $len);

    $str = preg_replace("/&/", "&amp;", $str);
    $str = preg_replace("/</", "&lt;", $str);
    $str = preg_replace("/>/", "&gt;", $str);
    return $str;
}

/*
-----------------------------------------------------------
    charset 오류 수정
-----------------------------------------------------------
*/
function revision_charset($str) {
    global $g4;
    $str = trim($str);
    $charset = str_replace('-', '', $g4[charset]);
    if (strtolower($charset) != 'utf8' && is_utf8($str)) {
        $str = convert_charset('utf-8', 'euc-kr', $str);
    }
    return $str;
}

/*
-----------------------------------------------------------
    url 오류 수정
-----------------------------------------------------------
*/
function revision_url($path) {
	while (strpos(" $path", '//')) {
        $path = str_replace('//', '/', $path);
    }
	return $path;
}

/*
-----------------------------------------------------------
    글에 대한 태그 목록을 반환
-----------------------------------------------------------
*/
function get_post_tag($id, $sec="", $link=false) 
{
    global $gb4, $current;

    $tag = array();

    $sql = "select t.id, t.tag from $gb4[taglog_table] l left join $gb4[tag_table] t on l.tag_id=t.id where blog_id='$current[id]' and post_id = '$id'";
    $qry = sql_query($sql);

    while ($row = sql_fetch_array($qry))
    {
        $tag[] = $row[tag];
    }

    if ($sec) 
    {
        unset($lst);
        foreach($tag as $val) 
        {
            if ($link)
                $lst .= "<a href=\"".get_tag_url($val)."\" rel=\"tag\">".$val."</a>".$sec;
            else
                $lst .= $val.$sec;
        }
        $tag = substr($lst, 0, strlen($lst)-strlen($sec));
    }

    return $tag;
}

/*
-----------------------------------------------------------
    태그 출력 순서 및 랭크 적용
-----------------------------------------------------------
*/
function get_tag_cloud($flag, $len=0) {

    global $current, $gb4;

    $tag_max = 5; // 태그 단계

    if ($flag=='popular') $flag = 1;
    if ($flag=='time'   ) $flag = 2;

    if ($flag==1)
        $orderby = 'tag_count desc';
    else
        $orderby = 'l.regdate desc';

    $tags = array();
    $index = 0;
    $sql = "select
                 t.tag as tag
                ,count(tag) as tag_count
                ,l.regdate
            from
                {$gb4[taglog_table]} l,
                {$gb4[tag_table]} t
            where
                blog_id = '{$current[id]}'
            and
                l.tag_id = t.id
            group by
                l.tag_id
            order by
                $orderby ";
    if ($len) $sql .= " limit {$len}";
    $qry = sql_query($sql);

    while( $res = sql_fetch_array($qry))  {
        $tags[$index] = $res;
        $tags[$index][url] = get_tag_url($res[tag]);
        $index++;
    }

    usort($tags,'tag_sort_make_rank');

    for($i=0, $max=count($tags); $i<$max; $i++) {
        $p = $tags[$i-1][rank];
        $s = $tags[$i-1][tag_count] - $current[sidebar_tag_gap];
        $l = $tags[$i-1][tag_count] + $current[sidebar_tag_gap];
        $n = $tags[$i][tag_count];

        if (!$i) {
            $r = 1;
        } elseif ($p == $tag_max) {
            $r = $tag_max;
        } elseif ($s <= $n && $n <= $l) {
            $r = $p;
        } elseif ($p < $n) {
            $r = $p + 1;
        }
        $tags[$i][rank] = $r;
    }

    switch($flag) {
        case '2':
            // 시간순
            usort($tags,'tag_sort_time');
            break;
        default:
            // 인기순
            usort($tags,'tag_sort_rank');
            break;
    }

    return $tags;
}
function tag_sort_make_rank($tag_current, $tag_next) {
    return strtotime($tag_current[tag_count])-strtotime($tag_next[tag_count]);
}
function tag_sort_rank($tag_current, $tag_next) {
    return strtotime($tag_next[rank])-strtotime($tag_current[rank]);
}
function tag_sort_time($tag_current, $tag_next) {
    return strtotime($tag_next[regdate])-strtotime($tag_current[regdate]);
}

/*
-----------------------------------------------------------
    사이드바 위치별 목록 얻기
-----------------------------------------------------------
*/
function get_sidebar_list($pos) {
    global $current;
    $sidebar = array();
    $sb['sidebar_'.$pos] = explode(",",$current['sidebar_'.$pos]);
    for($i=0, $max=count($sb['sidebar_'.$pos]); $i<$max; $i++) {
        if (trim($sb['sidebar_'.$pos][$i])) {
            array_push($sidebar, $sb['sidebar_'.$pos][$i]);
        }
    }
    return $sidebar;
}
/*
-----------------------------------------------------------
    html 태그 갯수 맞추기
-----------------------------------------------------------
*/
function get_sync_tag($content, $tag) {

    $tag = strtolower($tag);
    $res = strtolower($content);

    $open  = substr_count($res, "<$tag");
    $close = substr_count($res, "</$tag");

    if ($open > $close) {

        $gap = $open - $close;
        for($i=0; $i<$gap; $i++)
            $content .= "</$tag>";

    } else {

        $gap = $close - $open;
        for($i=0; $i<$gap; $i++)
            $content = "<$tag>".$content;
    }

    return $content;
}

/*
-----------------------------------------------------------
    태그 구름 URL
-----------------------------------------------------------
*/
function get_tag_cloud_url() {
    global $gb4, $current;

    $tag_url = $gb4[bbs_url];

    switch ($gb4[use_permalink]) {

         // 퍼마링크를 사용하지 않을 때
        case 'none':
            $tag_url .= '/tags.php?mb_id='.$current[mb_id];
            break;

        // 퍼마링크를 숫자로 사용할 때
        case 'numeric':
            $tag_url .= '/'.$current[mb_id].'/tags';
            break;
    }

    return revision_url($tag_url);
}

/*
-----------------------------------------------------------
    방명록 URL
-----------------------------------------------------------
*/
function get_guestbook_url() {
    global $gb4, $current;

    $tag_url = $gb4[bbs_url];

    switch ($gb4[use_permalink]) {

         // 퍼마링크를 사용하지 않을 때
        case 'none':
            $tag_url .= '/guestbook.php?mb_id='.$current[mb_id];
            break;

        // 퍼마링크를 숫자로 사용할 때
        case 'numeric':
            $tag_url .= '/'.$current[mb_id].'/guestbook';
            break;
    }

    return revision_url($tag_url);

}

/*
-----------------------------------------------------------
    방명록 주소 반환 - 페이징시
-----------------------------------------------------------
*/

function get_guestbook_page_url() {

    global $gb4;

    $url = get_guestbook_url();

    switch ($gb4[use_permalink]) {

         // 퍼마링크를 사용하지 않을 때
        case 'none':
            $url .= $gb4[ampersand] . "page=";
            break;

        // 퍼마링크를 숫자로 사용할 때
        case 'numeric':
            $url .= '/page/';
            break;
    }

    return revision_url($url);
}

/*
-----------------------------------------------------------
    미리보기 주소 반환
-----------------------------------------------------------
*/
function get_preview_url() {

    global $gb4, $current;

    switch ($gb4[use_permalink]) {

         // 퍼마링크를 사용하지 않을 때
        case 'none':
            $url = $current[blog_url].$gb4[ampersand].'preview=';
            break;

        // 퍼마링크를 숫자로 사용할 때
        case 'numeric':
            $url = $current[blog_url].'/preview/';
            break;
    }

    return revision_url($url);
}
/*
-----------------------------------------------------------
    페이지 주소 반환
-----------------------------------------------------------
*/
function get_page_uri($uri) {

    global $gb4;

    switch ($gb4[use_permalink]) {

         // 퍼마링크를 사용하지 않을 때
        case 'none':
            $uri = eregi_replace("[\?&]page=([0-9]+)", "", $uri);
            $uri.= "&page=";
            break;

        // 퍼마링크를 숫자로 사용할 때
        case 'numeric':
            $uri = eregi_replace("page/([0-9]+)","", $uri);
            if (substr($uri, strlen($uri)-1, 1) != '/') $uri .= '/';
            $uri .= "page/";
            break;
    }

    return $uri;
}
/*
-----------------------------------------------------------
    게시글에 첨부된 파일을 얻는다. (배열로 반환)
-----------------------------------------------------------
*/
function get_blog_file($post_id)
{
    global $g4, $gb4, $qstr, $current;

    $file = array();
    $file[count] = 0;
    $sql = " select * from {$gb4[file_table]} where blog_id = '{$current[id]}' and post_id = '{$post_id}' order by file_num ";
    $result = sql_query($sql);
    while ($row = sql_fetch_array($result))
    {
        $no = $file[count];
        $file[$no][href] = "{$gb4[bbs_path]}/download.php?mb_id={$current[mb_id]}&post_id={$post_id}&file_num={$row[file_num]}";
        $file[$no][download] = $row[download_count];
        $file[$no][path] = "{$g4[path]}/data/blog/file/{$current[mb_id]}";
        $file[$no][size] = $row[file_size]; //get_filesize($row[file_size]);
        $file[$no][datetime] = $row[datetime];
        $file[$no][save_name] = $row[save_name];
        $file[$no][real_name] = $row[real_name];
        $file[$no][image_width] = $row[bf_width] ? $row[bf_width] : 640;
        $file[$no][image_height] = $row[bf_height] ? $row[bf_height] : 480;
        $file[$no][image_type] = $row[bf_type];
        $file[$no][view] = blog_view_file_link($row[save_name], $row[bf_width], $row[bf_height], "");
        $file[count]++;
    }

    return $file;
}
/*
-----------------------------------------------------------
    텍스트가 utf-8 인지 검사하는 함수
-----------------------------------------------------------
*/
if(!function_exists('is_utf8')){
function is_utf8($string) {

  // From http://w3.org/International/questions/qa-forms-utf-8.html
  return preg_match('%^(?:
        [\x09\x0A\x0D\x20-\x7E]            # ASCII
      | [\xC2-\xDF][\x80-\xBF]            # non-overlong 2-byte
      |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
      | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
      |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
      |  \xF0[\x90-\xBF][\x80-\xBF]{2}    # planes 1-3
      | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
      |  \xF4[\x80-\x8F][\x80-\xBF]{2}    # plane 16
 )*$%xs', $string);

}
}

/*
-----------------------------------------------------------
    랜덤 블로그 주소를 리턴하는 함수
-----------------------------------------------------------
*/
function get_random_blog_url() {

    // 테이블 이름을 가져오기 위해 gb4 환경변수를 사용한다.
    // 본인과 현재 블로그는 제외하기 위해 $member 와 $current 를 가져온다.
    global $gb4, $member, $current;

    $where = " 1 ";
    if ($member['mb_id'])
        $where .= " and mb_id <> '{$member[mb_id]}' ";
    if ($current['mb_id'] && $current['mb_id'] != $member['mb_id'])
        $where .= " and mb_id <> '{$current[mb_id]}' ";
    $where .= " and use_random = 1 and post_count >= 0 ";

    // 랜덤값에 위치한 블로그 아이디를 찾는다.
    $sql = " select mb_id from $gb4[blog_table] where $where order by rand() ";
    $res = sql_fetch($sql);

    // 결과 주소를 리턴한다.
    if ($res)
        return get_blog_url($res[mb_id]);
    else
        return $gb4[index];
}

/*
-----------------------------------------------------------
    이전글의 내용을 가져와 리턴하는 함수
-----------------------------------------------------------
*/
function get_prev_post($blog_id,$date) {
    global $gb4;

    $sql = "select
                id,title,post_date
            from
                {$gb4[post_table]}
            where
                blog_id='{$blog_id}'
                and secret=1
                and post_date < '$date'
            order by
                post_date desc
            limit 1";
    $r = sql_fetch($sql);

    if (!$r[id])
        $r[display] = 'none';
    else
        $r[display] = 'block';

    $r[href] = get_post_url($r[id]);

    return $r;
}

/*
-----------------------------------------------------------
    다음글의 내용을 가져와 리턴 하는 함수
-----------------------------------------------------------
*/
function get_next_post($blog_id,$date) {
    global $gb4;

    $sql = "select
                id,title,post_date
            from
                {$gb4[post_table]}
            where
                blog_id='{$blog_id}'
                and secret=1
                and post_date > '$date'
            order by
                post_date
            limit 1";
    $r = sql_fetch($sql);

    if (!$r[id])
        $r[display] = 'none';
    else
        $r[display] = 'block';

    $r[href] = get_post_url($r[id]);

    return $r;
}

/*
-----------------------------------------------------------
    해당 회원의 블로그 주소를 알려주는 함수
-----------------------------------------------------------
*/
function get_blog_url($mb_id) {

    global $gb4, $current;

    switch ($gb4[use_permalink]) {

         // 퍼마링크를 사용하지 않을 때
        case 'none':
            $blog_url = '/?mb_id='.$mb_id;
            break;

        // 퍼마링크를 숫자로 사용할 때
        case 'numeric':
            $blog_url = '/'.$mb_id;
            break;
    }

    $blog_url = revision_url($gb4[url].$blog_url);

    return $blog_url;
}

/*
-----------------------------------------------------------
    http:// 부터 시작하는 전체 주소를 반환
-----------------------------------------------------------
*/
function get_full_url($link) {
    global $gb4;
    return $gb4[host].revision_url($link);
}

/*
-----------------------------------------------------------
    해당글의 고유주소를 알려주는 함수
-----------------------------------------------------------
*/
function get_post_url($id, $mb_id="") {

    global $gb4, $current;

    if (!$mb_id)
        $mb_id = $current[mb_id];

    switch ($gb4[use_permalink]) {

         // 퍼마링크를 사용하지 않을 때
        case 'none':
            $post_url = $gb4[ampersand].'id='.$id;
            break;

        // 퍼마링크를 숫자로 사용할 때
        case 'numeric':
            $post_url = "/$id";
            break;
    }

    $post_url = get_blog_url($mb_id).$post_url;
    $post_url = revision_url($post_url);

    return $post_url;
}

/*
-----------------------------------------------------------
    해당 댓글의 고유 주소를 알려주는 함수
-----------------------------------------------------------
*/
function get_comment_url($post_id, $comment_id, $mb_id="") {

    global $gb4, $current;

    if (!$mb_id)
        $mb_id = $current[mb_id];

    switch ($gb4[use_permalink]) {

         // 퍼마링크를 사용하지 않을 때
        case 'none':
            $comment_url = $gb4[ampersand].'id='.$post_id.'#c'.$comment_id;
            break;

        // 퍼마링크를 숫자로 사용할 때
        case 'numeric':
            $comment_url = '/'.$post_id.'#c'.$comment_id;
            break;
    }

    $comment_url = get_blog_url($mb_id).$comment_url;
    $comment_url = revision_url($comment_url);

    return $comment_url;
}

/*
-----------------------------------------------------------
    해당 엮인글의 고유주소를 알려주는 함수
-----------------------------------------------------------
*/
function get_trackback_url($id, $trackback_id, $mb_id="") {

    global $gb4, $current;

    if (!$mb_id)
        $mb_id = $current[mb_id];

    switch ($gb4[use_permalink]) {

         // 퍼마링크를 사용하지 않을 때
        case 'none':
            $trackback_url = $gb4[ampersand].'id='.$id.'#t'.$trackback_id;
            break;

        // 퍼마링크를 숫자로 사용할 때
        case 'numeric':
            $trackback_url = '/'.$id.'#t'.$trackback_id;
            break;
    }

    $trackback_url = get_blog_url($mb_id).$trackback_url;
    $trackback_url = revision_url($trackback_url);

    return $trackback_url;
}

/*
-----------------------------------------------------------
    분류의 고유주소를 알려주는 함수
-----------------------------------------------------------
*/
function get_category_url($category_name, $mb_id="") {

    global $gb4, $current;

    $category_name = urlencode($category_name);

    if (!$mb_id)
        $mb_id = $current[mb_id];

    switch ($gb4[use_permalink]) {

         // 퍼마링크를 사용하지 않을 때
        case 'none':
            $category_url = $gb4[ampersand].'cate='.$category_name;
            break;

        // 퍼마링크를 숫자로 사용할 때
        case 'numeric':
            $category_url = '/category/'.$category_name;
            break;
    }

    $category_url = get_blog_url($mb_id).$category_url;
    $category_url = revision_url($category_url);

    return $category_url;
}

/*
-----------------------------------------------------------
    월별포스트 Permalink
-----------------------------------------------------------
*/
function get_monthly_url($monthly, $mb_id="") {

    global $gb4, $current;

    if (!$mb_id)
        $mb_id = $current[mb_id];

    switch ($gb4[use_permalink]) {

         // 퍼마링크를 사용하지 않을 때
        case 'none':
            $monthly_url = $gb4[ampersand].'mon='.$monthly;
            break;

        // 퍼마링크를 숫자로 사용할 때
        case 'numeric':
            $monthly = str_replace('-','/',$monthly);
            $monthly_url = '/'.$monthly;
            break;
    }

    $monthly_url = get_blog_url($mb_id).$monthly_url;
    $monthly_url = revision_url($monthly_url);

    return $monthly_url;
}
/*
-----------------------------------------------------------
    Tag 검색 URL
-----------------------------------------------------------
*/

function get_tag_url($tag, $mb_id="") {

    global $current, $gb4;

    $tag = urlencode($tag);

    if (!$mb_id)
        $mb_id = $current[mb_id];

    switch ($gb4[use_permalink]) {

         // 퍼마링크를 사용하지 않을 때
        case 'none':
            $tag_url = $gb4[ampersand].'tag=' . $tag;
            break;

        // 퍼마링크를 숫자로 사용할 때
        case 'numeric':
            $tag_url = '/tag/' . $tag;
            break;
    }

    $tag_url = get_blog_url($mb_id).$tag_url;
    $tag_url = revision_url($tag_url);

    return $tag_url;
}

/*
-----------------------------------------------------------
    Charset 을 변환하는 함수
-----------------------------------------------------------
*/
function convert_charset($from_charset, $to_charset, $str) {
    if (function_exists('iconv'))
        return iconv($from_charset, $to_charset, $str);
    elseif (function_exists('mb_convert_encoding'))
        return mb_convert_encoding($str, $to_charset, $from_charset);
    else
        die("Not found 'iconv' or 'mbstring' library in server.");
}

/*
-----------------------------------------------------------
    사용자가 입력한 Tag 를 DB 에 저장하는 함수
-----------------------------------------------------------
Tag 사이는 ',' (컴마)로 구분한다.
예) 블로그, blog, gblog, GBlog, sir, SIR
*/
function tag_add($id, $tag) {

    global $g4, $gb4, $current, $member;

    // 태그가 있으면 태그를 달아야지..
    if (trim($tag))
    {
        // 태그를 , 컴마 기준으로 박살낸다.
        $tags = explode(',', $tag);

        // 박살낸 태그를 앞에서부터 하나씩 줍는다.
        while( $tag = array_shift($tags)) {

            // 빈 태그는 열외!!
            if (!trim($tag)) continue;

            // 태그를 깨끗이 닦아;; 준다.
            $tag = trim(htmlspecialchars($tag, ENT_QUOTES));

            // 기존에 같은 태그가 있는지 검사해본다.
            $r = sql_fetch("select * from {$gb4[tag_table]} where tag = '{$tag}' ");

            // 기존에 태그가 존재 하지 않는 경우 등록한다.
            if (empty($r)) {

                // 이게 등록하는거다
                sql_query("insert into {$gb4[tag_table]} set tag='{$tag}', tag_count=1, regdate='{$g4[time_ymdhis]}', lastdate='{$g4[time_ymdhis]}'");

                // 등록한 태그 고유번호를 따낸다.
                $tag_id = mysql_insert_id();

            // 기존에 태그가 존재하면
            } else  {

                // 태그 고유번호를 따낸다.
                $tag_id = $r[id];

                // 지금 사용했다고 count 를 하나 증가시키고 lastdate 를 업데이트 해준다.
                sql_query("update {$gb4[tag_table]} set tag_count = tag_count + 1, lastdate='{$g4[time_ymdhis]}' where id='{$tag_id}'");

            }// end if

            // 지금 등록하는 글에 태그(꼬리표)를 이쁘게 달아본다.
            $sql = "insert into {$gb4[taglog_table]} set blog_id = '{$current[id]}' ,post_id = '{$id}' ,tag_id  = '{$tag_id}' ,regdate = '{$g4[time_ymdhis]}'";
            sql_query($sql);

        } // end while

    } // end if
}

/*
-----------------------------------------------------------
    현재 블로그가 나의 블로그인지 검사하는 함수
-----------------------------------------------------------
*/
function is_myblog() {
    global $current, $member;
    return $current[mb_id]==$member[mb_id];
}

/*
-----------------------------------------------------------
    해당하는 회원이 블로그를 가지고 있는지 검사한다.
-----------------------------------------------------------
*/
function have_a_blog($mb_id) {
    global $gb4;
    $r = sql_fetch("select * from $gb4[blog_table] where mb_id='$mb_id'");
    return $r;
}

/*
-----------------------------------------------------------
    사용중인 스킨 경로를 알아낸다.
-----------------------------------------------------------
없으면 basic 스킨을 기본으로 한다.
*/
function get_skin($mb_id, $preview='') {
    global $gb4;
    if (empty($preview)) {
        $sql = "select skin from {$gb4[blog_table]} b, {$gb4[skin_table]} s where b.skin_id = s.id and b.mb_id='$mb_id' and used='1' ";
        $r = sql_fetch($sql);
        if (!$r) {
            return $gb4[default_skin];
        }
        return $r[skin];
    } else {
        return $preview;
    }
}

/*
-----------------------------------------------------------
    블로그 전용 Paging 함수
-----------------------------------------------------------
한페이지에 보여줄 행, 현재페이지, 총페이지수, URL
*/
function get_blog_paging($write_pages, $cur_page, $total_page, $url, $add="")
{
    $str = "";
    $start_page = ( ( (int)( ($cur_page - 1) / $write_pages)) * $write_pages) + 1;
    $end_page = $start_page + $write_pages - 1;

    if ($cur_page != 1)
        $str .= " &nbsp;<a href='" . $url . 1 . "{$add}'>맨처음</a>";
    else
        $str .= " &nbsp;<null>맨처음</null>";

    if ($cur_page > $write_pages)
        $str .= " &nbsp;<a href='" . $url . ($start_page-1) . "{$add}'>이전 {$write_pages} 개</a>";
    else
        $str .= " &nbsp;<null>이전 {$write_pages} 개</null>";

    if ($end_page >= $total_page) $end_page = $total_page;

    if ($cur_page > 1)
        $str .= " &nbsp;<a href='" . $url . ($cur_page-1) . "{$add}'>이전페이지</a>";
    else
        $str .= " &nbsp;<null>이전페이지</null>";

    if ($total_page > 1) {
        for ($k=$start_page;$k<=$end_page;$k++) {
            if ($cur_page != $k)
                $str .= " &nbsp;<a href='$url$k{$add}'><span>$k</span></a>";
            else
                $str .= " &nbsp;<null>$k</null> ";
        }
    }

    if ($total_page > $cur_page)
        $str .= " &nbsp;<a href='" . $url . ($cur_page+1) . "{$add}'>다음페이지</a>";
    else
        $str .= " &nbsp;<null>다음페이지</null>";

    if ($end_page < $total_page)
        $str .= " &nbsp;<a href='" . $url . ($end_page+1) . "{$add}'>다음 {$write_pages} 개</a>";
    else
        $str .= " &nbsp;<null>다음 {$write_pages} 개</null>";

    if ($cur_page < $total_page)
        $str .= " &nbsp;<a href='" . $url . ($total_page) . "{$add}'>맨끝</a>";
    else
        $str .= " &nbsp;<null>맨끝</null>";


    return $str;
}

if (!function_exists('get_member_level_select')) {

// 회원권한을 SELECT 형식으로 얻음
function get_member_level_select($name, $start_id=0, $end_id=10, $selected='', $event='')
{
    global $g4;

    $str = "<select name='$name' $event>";
    for ($i=$start_id; $i<=$end_id; $i++)
    {
        $str .= "<option value='$i'";
        if ($i == $selected) 
            $str .= " selected";
        $str .= ">$i</option>";
    }
    $str .= "</select>";
    return $str;
}

}

function get_blog_sideview($mb_id, $name="", $email="", $homepage="")
{
    global $config, $g4, $gb4;

    $email = base64_encode($email);
    $homepage = set_http($homepage);

    $name = preg_replace("/\&#039;/", "", $name);
    $name = preg_replace("/\'/", "", $name);
    $name = preg_replace("/\"/", "&#034;", $name);
    $title_name = $name;

    if ($mb_id) {
        $tmp_name = "<span class='member'>$name</span>";
        if ($config[cf_use_member_icon]) {
            $mb_dir = substr($mb_id,0,2);
            $icon_file_path = "$g4[path]/data/member/$mb_dir/{$mb_id}.gif";
            $icon_file_url = "$gb4[root]/data/member/$mb_dir/{$mb_id}.gif";

            //if (file_exists($icon_file) && is_file($icon_file)) {
            if (file_exists($icon_file_path)) {
                //$size = getimagesize($icon_file);
                //$width = $size[0];
                //$height = $size[1];
                $width = $config[cf_member_icon_width];
                $height = $config[cf_member_icon_height];
                $tmp_name = "<img src='{$icon_file_url}' width='$width' height='$height' align='absmiddle' border='0'>";
                if ($config[cf_use_member_icon] == 2) // 회원아이콘+이름
                    $tmp_name = $tmp_name . " <span class='member'>$name</span>";
            }
        }
        $title_mb_id = "[$mb_id]";
    } else {
        $tmp_name = "<span class='guest'>$name</span>";
        $title_mb_id = "[비회원]";
    }

    return "<a href=\"javascript:;\" onClick=\"showSideView(this, '$mb_id', '$name', '$email', '$homepage');\" title=\"{$title_mb_id}{$title_name}\">$tmp_name</a>";
}

// 파일을 보이게 하는 링크 (이미지, 플래쉬, 동영상)
function blog_view_file_link($file, $width, $height, $content="")
{
    global $config;
    global $g4;
    global $gb4, $current;
    static $ids;

    $board[bo_image_width] = $current[image_width];

    if (!$file) return;

    $ids++;

    // 파일의 폭이 게시판설정의 이미지폭 보다 크다면 게시판설정 폭으로 맞추고 비율에 따라 높이를 계산
    if ($width > $board[bo_image_width] && $board[bo_image_width])
    {
        $rate = $board[bo_image_width] / $width;
        $width = $board[bo_image_width];
        $height = (int)($height * $rate);
    }

    // 폭이 있는 경우 폭과 높이의 속성을 주고, 없으면 자동 계산되도록 코드를 만들지 않는다.
    if ($width)
        $attr = " width='$width' height='$height' ";
    else
        $attr = "";

    if (preg_match("/\.($config[cf_image_extension])$/i", $file))
        // 이미지에 속성을 주지 않는 이유는 이미지 클릭시 원본 이미지를 보여주기 위한것임
        // 게시판설정 이미지보다 크다면 스킨의 자바스크립트에서 이미지를 줄여준다
        return "<img src='$g4[path]/data/blog/file/$current[mb_id]/".urlencode($file)."' name='target_resize_image[]' onclick='image_window(this);' style='cursor:pointer;' title='$content'>";
    // 110106 : FLASH XSS 공격으로 인하여 코드 자체를 막음
    /*
    else if (preg_match("/\.($config[cf_flash_extension])$/i", $file))
        //return "<embed src='$g4[path]/data/file/$board[bo_table]/$file' $attr></embed>";
        return "<script>doc_write(flash_movie('$g4[path]/data/blog/file/$current[mb_id]/$file', '_g4_{$ids}', '$width', '$height', 'transparent'));</script>";
    */
    //=============================================================================================
    // 동영상 파일에 악성코드를 심는 경우를 방지하기 위해 경로를 노출하지 않음
    //---------------------------------------------------------------------------------------------
    /*
    else if (preg_match("/\.($config[cf_movie_extension])$/i", $file))
        //return "<embed src='$g4[path]/data/file/$board[bo_table]/$file' $attr></embed>";
        return "<script>doc_write(obj_movie('$g4[path]/data/file/$board[bo_table]/$file', '_g4_{$ids}', '$width', '$height'));</script>";
    */
    //=============================================================================================
}

// http://kr.php.net/implode]
if(!function_exists('implode_wrapped')){
function implode_wrapped($before, $after, $glue, $array){
    $output = '';
    foreach($array as $item){
        $output .= $before . $item . $after . $glue;
    }
    return substr($output, 0, -strlen($glue));
}
}
?>