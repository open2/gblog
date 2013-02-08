<?
include_once("./_common.php");

// 파라메터 mb_id 값이 없을 때는 블로그 메인으로 이동
if (!$mb_id) {
    goto_url( "$gb4[url]/gblog.index.php");
}

// 블로그가 없으면, 블로그만들기로 이동 합니다.
if (!$current && $member[mb_id] && $mb_id == "$member[mb_id]" ) {
    alert('블로그 만들기로 이동합니다.', "$gb4[bbs_path]/join_blog.php");
}

// 블로그를 보여주기 위한 정보를 읽습니다.
if ($current) {

    // $id에 값이 없으면 0으로
    if (!$id) $id = 0;

    // 페이지 번호 초기화
    if (!is_numeric($page))
        $page = 1;
    else
        $page = (int)$page;

    // 사이트 검색
    if (!empty($search))
    {
        $search = revision_charset($search);
        $sql_search = " and (p.title like '%$search%' or p.content like '%$search%') ";
    }

    // 분류 검색
    if (!empty($cate))
    {
        $cate = revision_charset($cate);

        if ($cate != 'all')  {

            // 분류이름으로 분류 고유번호를 가져온다.
            $row  = sql_fetch("select id from $gb4[category_table] where category_name = '$cate'");

            // 분류 고유번호로 검색 쿼리를 만들어 낸다.
            if (empty($row))
                $sql_cate = " and null ";
            else
                $sql_cate = " and p.category_id = '$row[id]' ";
        }
    }

    // 태그별 검색, 아 빡시다;
    if (!empty($tag))
    {
        // 태그 앞 뒤 공백 제거
        $tag = revision_charset($tag);

        // 태그 고유번호를 가져 온다.
        $row = sql_fetch("select id from $gb4[tag_table] where tag = '$tag'");

        // 태그 고유번호가 존재할 경우에만 검색이 가능하다.
        if (!empty($row))
        {
            // 태그 고유번호로 해당 태그를 사용한 글을 찾아내 검색 쿼리를 만든다.
            $sql_tag = "and p.id in(''";

            $qry = sql_query("select post_id from $gb4[taglog_table] where tag_id='$row[id]' ");

            while($row = sql_fetch_array($qry))
                $sql_tag .= ",".$row[post_id];

            $sql_tag .= ")";
        }
    }

    // 월별 검색
    if (!empty($mon))
    {
        // 공백 제거
        $mon = trim($mon);

        // 해당 월에 글이 존재하는지 검사
        $row = sql_fetch("select * from $gb4[monthly_table] where blog_id='$current[id]' and monthly='$mon'");

        // 해당 월에 글이 있다면 검색어 추가
        if (!empty($row))
            $sql_monthly = " and left(post_date,7)='$mon' ";

        $month = explode('-',$mon);
        $yyyy = $month[0];
        $mm = $month[1];
    }

    // 날짜별 검색
    if (!empty($yyyy) && !empty($mm) && empty($dd))
    {
        $sql_cur = " and left(post_date,7) = '$yyyy-$mm' ";
    }
    elseif (!empty($yyyy) && !empty($mm) && !empty($dd))
    {
        $sql_cur = " and left(post_date,10) = '$yyyy-$mm-$dd' ";
    }
    
    // 글 하나만 볼 경우
    if (!empty($id)) $sql_id = " and p.id = '$id' ";
    
    // 페이징을 위한 값 로드
    if ($cate || $tag || $mon || $sql_cur || $search)
        $page_size = $current[list_count];
    else
        $page_size = $current[page_count];
    
    if (empty($page_size)) $page_size = 10;
    
    $sql = "select count(*) as cnt from $gb4[post_table] p where p.blog_id='$current[id]' $sql_cate $sql_tag $sql_monthly $sql_secret $sql_cur $sql_search";
    $total_post = sql_fetch($sql);

    $total_post = $total_post[cnt];
    $total_page = (int)($total_post/$page_size) + ($total_post%$page_size==0 ? 0 : 1);
    $page_start = $page_size * ( $page - 1);
    
    // 현재 페이지의 글 정보를 DB 에서 불러온다.
    $sql = "select
                p.*,
                c.category_name
            from
                $gb4[post_table] p left join $gb4[category_table] c on p.category_id = c.id
            where
                p.blog_id='$current[id]'
                $sql_cate
                $sql_tag
                $sql_id
                $sql_monthly
                $sql_cur
                $sql_search
                $sql_secret
            order by
                p.post_date desc
            limit
                $page_start, $page_size";
    $qry = sql_query($sql);
    
    //  $post 변수에 글 내용을 모두 담는다.
    
    // 글 정보를 담을 $post 변수 초기화
    $post = array();
    $index = 0;
    
    while ($row = sql_fetch_array($qry)) {
    
        // 일단 DB 에서 가져온 글 정보를 전부 $post 변수에 담는다.
        $post[$index] = $row;

        // contents
        $post[$index][rich_post] = $post[$index][content];
        $post[$index][content] = conv_content($post[$index][content], 1);
    
        // 글의 고유주소를 추가로 저장한다.
        $post[$index][url] = get_post_url($row[id]);
    
        // 글에 분류 설정 되어있지 않을경우 '분류없음' 로 기본 설정해준다.
        if (empty($row[category_name]))
            $post[$index][category_name] = '분류없음';
    
        // 이미지 리사이즈
        $post[$index][content] = preg_replace("/(\<img)([^\>]*)(\>)/i", "\\1 name='target_resize_image[]' onclick='image_window(this)' style='cursor:pointer;' \\2 \\3", $post[$index][content]);
    
        // div 태그 갯수를 맞춘다.
        $post[$index][content] = get_sync_tag($post[$index][content], 'div');
    
        /*---------------------------------------
            첨부파일을 가져온다.
        -----------------------------------------*/
        $post[$index][file] = get_blog_file($row[id]);
    
        /*---------------------------------------
            태그를 가져온다.
        -----------------------------------------*/
        $post[$index][tag] = get_post_tag($row[id], ', ', true);
    
        /*---------------------------------------
            엮인글을 가져온다
        -----------------------------------------*/
    
        // 엮인글을 가져올 배열을 초기화한다.
        $post[$index][trackback] = array();
    
        // 엮인글을 DB 에서 가져온다.
        $trackback_sql = "select * from $gb4[trackback_table] where post_id='{$post[$index][id]}' order by id";
        $trackback_qry = sql_query($trackback_sql);
    
        // $post[$index][trackback] 변수에 현재 글의 엮인글을 담는다.
        while ($trackback_row = sql_fetch_array($trackback_qry))
        {
            $trackback_row[writer_content] = strip_tags($trackback_row[writer_content]);
    
            // 제목에 블로그 주소를 링크 건다.
            if ($trackback_row[writer_url]) {
                $writer_url = str_replace("http://", "", $trackback_row[writer_url]);
                $trackback_row[writer_subject] = "<a href='http://$writer_url' target=_blank>$trackback_row[writer_subject]</a>";
            }
    
            $trackback_row[writer_content] = cut_str($trackback_row[writer_content], 255);
    
            // 엮인글 정보를 배열에 담는다.
            array_push($post[$index][trackback], $trackback_row);
    
            // 현재 배열의 인덱스 번호를 담는다.
            $trackback_index = count($post[$index][trackback])-1;
        }
    
        // 엮인글 주소
        $post[$index][trackback_url] = "$g4[url]/$gb4[blog]/tb.php/$current[mb_id]/$row[id]";
    
        if (!empty($id)) {
    
            // 이전 글 내용을 배열(href, content)로 담는다.
            $post[$index][prev] = get_prev_post($current[id], $row[post_date]);
    
            // 다음 글 내용을 담는다.
            $post[$index][next] = get_next_post($current[id], $row[post_date]);
        }
    
        $index++;
    }
    
    // 댓글 가져오기
    if (!empty($id) && $post[0][use_comment] && $current[use_comment])
    {
        ob_start();
        include_once("$gb4[bbs_path]/comment.php");
        $comment = ob_get_contents();
        ob_clean();
    
        $temp = explode("\n",$comment);
        $comment = '';
        for($i=1, $max=count($temp); $i<$max; $i++)
            $comment .= $temp[$i];
    }
    
    if (!empty($id)) {
    
        $g4[title] .= ' - '.$post[0][title];
    
        // 조회수 증가
        // 한번 읽은글은 브라우저를 닫기전까지는 카운트를 증가시키지 않음
        $ss_name = "ss_blog_view_$mb_id_$id";
        if (!get_session($ss_name))
        {
            sql_query("update $gb4[post_table] set hit = hit + 1 where blog_id = '$current[id]' and id = '$id'");
            set_session($ss_name, TRUE);
        }
    }

    // 브라우져 타이틀
    $g4[title] = $current[blog_name];

    // 블로그별 방문자 통계를 업데이트
    include_once("$gb4[bbs_path]/visit_insert.inc.php");

    include_once("$gb4[path]/head.sub.php");

    include_once("$gb4[bbs_path]/sidebar.php");
    include_once("$blog_skin_path/head.skin.php");
    
    if ($cate || $tag || $mon || $sql_cur || $sql_search)
        include_once("$blog_skin_path/list.skin.php");
    else
        include_once("$blog_skin_path/index.skin.php");
    
    include_once("$blog_skin_path/tail.skin.php");

    include_once("$gb4[path]/tail.sub.php");
    }

    // 글 하나만 볼때, bit.ly Short URL을 보여준다
    if (!empty($id) && $gb4[bitly_id] && $gb4[bitly_key] && !$post[0][bitly_url]) {
        // 단축경로를 생성할 블로그 url을 정의해줍니다.
        $blog_url = "$g4[url]$_SERVER[REQUEST_URI]";
    ?>
        <script language=javascript>
        // encode 된 것을 넘겨주면, 알아서 decode해서 결과를 return 해준다.
        // encode 하기 전의 url이 있어야 결과를 꺼낼 수 있기 때문에, 결국 2개를 넘겨준다.
        // 왜? java script에서는 urlencode, urldecode가 없으니까. ㅎㅎ
        // 글쿠 이거는 마지막에 해야 한다. 왜??? 그래야 정보를 html page에 업데이트 하쥐~!
        var bitly_id = '<?=$gb4[bitly_id]?>';
        var bitly_key = '<?=$gb4[bitly_key]?>';
        get_bitly_gblog('#bitly_url', '<?=$current[mb_id]?>', '<?=$id?>', '<?=urlencode($blog_url)?>', '<?=$blog_url?>');
        </script>
<? } ?>