<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ� 

/*
-----------------------------------------------------------
    ��α� ���� ���� �迭�� ��ȯ
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
            $row[category_name] = '�з�����';

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
    explode �� trim �� ���ÿ�
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
    xml ���� ���
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
    Ư������ ��ȯ
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
    charset ���� ����
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
    url ���� ����
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
    �ۿ� ���� �±� ����� ��ȯ
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
    �±� ��� ���� �� ��ũ ����
-----------------------------------------------------------
*/
function get_tag_cloud($flag, $len=0) {

    global $current, $gb4;

    $tag_max = 5; // �±� �ܰ�

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
            // �ð���
            usort($tags,'tag_sort_time');
            break;
        default:
            // �α��
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
    ���̵�� ��ġ�� ��� ���
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
    html �±� ���� ���߱�
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
    �±� ���� URL
-----------------------------------------------------------
*/
function get_tag_cloud_url() {
    global $gb4, $current;

    $tag_url = $gb4[bbs_url];

    switch ($gb4[use_permalink]) {

         // �۸���ũ�� ������� ���� ��
        case 'none':
            $tag_url .= '/tags.php?mb_id='.$current[mb_id];
            break;

        // �۸���ũ�� ���ڷ� ����� ��
        case 'numeric':
            $tag_url .= '/'.$current[mb_id].'/tags';
            break;
    }

    return revision_url($tag_url);
}

/*
-----------------------------------------------------------
    ���� URL
-----------------------------------------------------------
*/
function get_guestbook_url() {
    global $gb4, $current;

    $tag_url = $gb4[bbs_url];

    switch ($gb4[use_permalink]) {

         // �۸���ũ�� ������� ���� ��
        case 'none':
            $tag_url .= '/guestbook.php?mb_id='.$current[mb_id];
            break;

        // �۸���ũ�� ���ڷ� ����� ��
        case 'numeric':
            $tag_url .= '/'.$current[mb_id].'/guestbook';
            break;
    }

    return revision_url($tag_url);

}

/*
-----------------------------------------------------------
    ���� �ּ� ��ȯ - ����¡��
-----------------------------------------------------------
*/

function get_guestbook_page_url() {

    global $gb4;

    $url = get_guestbook_url();

    switch ($gb4[use_permalink]) {

         // �۸���ũ�� ������� ���� ��
        case 'none':
            $url .= $gb4[ampersand] . "page=";
            break;

        // �۸���ũ�� ���ڷ� ����� ��
        case 'numeric':
            $url .= '/page/';
            break;
    }

    return revision_url($url);
}

/*
-----------------------------------------------------------
    �̸����� �ּ� ��ȯ
-----------------------------------------------------------
*/
function get_preview_url() {

    global $gb4, $current;

    switch ($gb4[use_permalink]) {

         // �۸���ũ�� ������� ���� ��
        case 'none':
            $url = $current[blog_url].$gb4[ampersand].'preview=';
            break;

        // �۸���ũ�� ���ڷ� ����� ��
        case 'numeric':
            $url = $current[blog_url].'/preview/';
            break;
    }

    return revision_url($url);
}
/*
-----------------------------------------------------------
    ������ �ּ� ��ȯ
-----------------------------------------------------------
*/
function get_page_uri($uri) {

    global $gb4;

    switch ($gb4[use_permalink]) {

         // �۸���ũ�� ������� ���� ��
        case 'none':
            $uri = eregi_replace("[\?&]page=([0-9]+)", "", $uri);
            $uri.= "&page=";
            break;

        // �۸���ũ�� ���ڷ� ����� ��
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
    �Խñۿ� ÷�ε� ������ ��´�. (�迭�� ��ȯ)
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
    �ؽ�Ʈ�� utf-8 ���� �˻��ϴ� �Լ�
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
    ���� ��α� �ּҸ� �����ϴ� �Լ�
-----------------------------------------------------------
*/
function get_random_blog_url() {

    // ���̺� �̸��� �������� ���� gb4 ȯ�溯���� ����Ѵ�.
    // ���ΰ� ���� ��α״� �����ϱ� ���� $member �� $current �� �����´�.
    global $gb4, $member, $current;

    $where = " 1 ";
    if ($member['mb_id'])
        $where .= " and mb_id <> '{$member[mb_id]}' ";
    if ($current['mb_id'] && $current['mb_id'] != $member['mb_id'])
        $where .= " and mb_id <> '{$current[mb_id]}' ";
    $where .= " and use_random = 1 and post_count >= 0 ";

    // �������� ��ġ�� ��α� ���̵� ã�´�.
    $sql = " select mb_id from $gb4[blog_table] where $where order by rand() ";
    $res = sql_fetch($sql);

    // ��� �ּҸ� �����Ѵ�.
    if ($res)
        return get_blog_url($res[mb_id]);
    else
        return $gb4[index];
}

/*
-----------------------------------------------------------
    �������� ������ ������ �����ϴ� �Լ�
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
    �������� ������ ������ ���� �ϴ� �Լ�
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
    �ش� ȸ���� ��α� �ּҸ� �˷��ִ� �Լ�
-----------------------------------------------------------
*/
function get_blog_url($mb_id) {

    global $gb4, $current;

    switch ($gb4[use_permalink]) {

         // �۸���ũ�� ������� ���� ��
        case 'none':
            $blog_url = '/?mb_id='.$mb_id;
            break;

        // �۸���ũ�� ���ڷ� ����� ��
        case 'numeric':
            $blog_url = '/'.$mb_id;
            break;
    }

    $blog_url = revision_url($gb4[url].$blog_url);

    return $blog_url;
}

/*
-----------------------------------------------------------
    http:// ���� �����ϴ� ��ü �ּҸ� ��ȯ
-----------------------------------------------------------
*/
function get_full_url($link) {
    global $gb4;
    return $gb4[host].revision_url($link);
}

/*
-----------------------------------------------------------
    �ش���� �����ּҸ� �˷��ִ� �Լ�
-----------------------------------------------------------
*/
function get_post_url($id, $mb_id="") {

    global $gb4, $current;

    if (!$mb_id)
        $mb_id = $current[mb_id];

    switch ($gb4[use_permalink]) {

         // �۸���ũ�� ������� ���� ��
        case 'none':
            $post_url = $gb4[ampersand].'id='.$id;
            break;

        // �۸���ũ�� ���ڷ� ����� ��
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
    �ش� ����� ���� �ּҸ� �˷��ִ� �Լ�
-----------------------------------------------------------
*/
function get_comment_url($post_id, $comment_id, $mb_id="") {

    global $gb4, $current;

    if (!$mb_id)
        $mb_id = $current[mb_id];

    switch ($gb4[use_permalink]) {

         // �۸���ũ�� ������� ���� ��
        case 'none':
            $comment_url = $gb4[ampersand].'id='.$post_id.'#c'.$comment_id;
            break;

        // �۸���ũ�� ���ڷ� ����� ��
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
    �ش� ���α��� �����ּҸ� �˷��ִ� �Լ�
-----------------------------------------------------------
*/
function get_trackback_url($id, $trackback_id, $mb_id="") {

    global $gb4, $current;

    if (!$mb_id)
        $mb_id = $current[mb_id];

    switch ($gb4[use_permalink]) {

         // �۸���ũ�� ������� ���� ��
        case 'none':
            $trackback_url = $gb4[ampersand].'id='.$id.'#t'.$trackback_id;
            break;

        // �۸���ũ�� ���ڷ� ����� ��
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
    �з��� �����ּҸ� �˷��ִ� �Լ�
-----------------------------------------------------------
*/
function get_category_url($category_name, $mb_id="") {

    global $gb4, $current;

    $category_name = urlencode($category_name);

    if (!$mb_id)
        $mb_id = $current[mb_id];

    switch ($gb4[use_permalink]) {

         // �۸���ũ�� ������� ���� ��
        case 'none':
            $category_url = $gb4[ampersand].'cate='.$category_name;
            break;

        // �۸���ũ�� ���ڷ� ����� ��
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
    ��������Ʈ Permalink
-----------------------------------------------------------
*/
function get_monthly_url($monthly, $mb_id="") {

    global $gb4, $current;

    if (!$mb_id)
        $mb_id = $current[mb_id];

    switch ($gb4[use_permalink]) {

         // �۸���ũ�� ������� ���� ��
        case 'none':
            $monthly_url = $gb4[ampersand].'mon='.$monthly;
            break;

        // �۸���ũ�� ���ڷ� ����� ��
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
    Tag �˻� URL
-----------------------------------------------------------
*/

function get_tag_url($tag, $mb_id="") {

    global $current, $gb4;

    $tag = urlencode($tag);

    if (!$mb_id)
        $mb_id = $current[mb_id];

    switch ($gb4[use_permalink]) {

         // �۸���ũ�� ������� ���� ��
        case 'none':
            $tag_url = $gb4[ampersand].'tag=' . $tag;
            break;

        // �۸���ũ�� ���ڷ� ����� ��
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
    Charset �� ��ȯ�ϴ� �Լ�
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
    ����ڰ� �Է��� Tag �� DB �� �����ϴ� �Լ�
-----------------------------------------------------------
Tag ���̴� ',' (�ĸ�)�� �����Ѵ�.
��) ��α�, blog, gblog, GBlog, sir, SIR
*/
function tag_add($id, $tag) {

    global $g4, $gb4, $current, $member;

    // �±װ� ������ �±׸� �޾ƾ���..
    if (trim($tag))
    {
        // �±׸� , �ĸ� �������� �ڻ쳽��.
        $tags = explode(',', $tag);

        // �ڻ쳽 �±׸� �տ������� �ϳ��� �ݴ´�.
        while( $tag = array_shift($tags)) {

            // �� �±״� ����!!
            if (!trim($tag)) continue;

            // �±׸� ������ �۾�;; �ش�.
            $tag = trim(htmlspecialchars($tag, ENT_QUOTES));

            // ������ ���� �±װ� �ִ��� �˻��غ���.
            $r = sql_fetch("select * from {$gb4[tag_table]} where tag = '{$tag}' ");

            // ������ �±װ� ���� ���� �ʴ� ��� ����Ѵ�.
            if (empty($r)) {

                // �̰� ����ϴ°Ŵ�
                sql_query("insert into {$gb4[tag_table]} set tag='{$tag}', tag_count=1, regdate='{$g4[time_ymdhis]}', lastdate='{$g4[time_ymdhis]}'");

                // ����� �±� ������ȣ�� ������.
                $tag_id = mysql_insert_id();

            // ������ �±װ� �����ϸ�
            } else  {

                // �±� ������ȣ�� ������.
                $tag_id = $r[id];

                // ���� ����ߴٰ� count �� �ϳ� ������Ű�� lastdate �� ������Ʈ ���ش�.
                sql_query("update {$gb4[tag_table]} set tag_count = tag_count + 1, lastdate='{$g4[time_ymdhis]}' where id='{$tag_id}'");

            }// end if

            // ���� ����ϴ� �ۿ� �±�(����ǥ)�� �̻ڰ� �޾ƺ���.
            $sql = "insert into {$gb4[taglog_table]} set blog_id = '{$current[id]}' ,post_id = '{$id}' ,tag_id  = '{$tag_id}' ,regdate = '{$g4[time_ymdhis]}'";
            sql_query($sql);

        } // end while

    } // end if
}

/*
-----------------------------------------------------------
    ���� ��αװ� ���� ��α����� �˻��ϴ� �Լ�
-----------------------------------------------------------
*/
function is_myblog() {
    global $current, $member;
    return $current[mb_id]==$member[mb_id];
}

/*
-----------------------------------------------------------
    �ش��ϴ� ȸ���� ��α׸� ������ �ִ��� �˻��Ѵ�.
-----------------------------------------------------------
*/
function have_a_blog($mb_id) {
    global $gb4;
    $r = sql_fetch("select * from $gb4[blog_table] where mb_id='$mb_id'");
    return $r;
}

/*
-----------------------------------------------------------
    ������� ��Ų ��θ� �˾Ƴ���.
-----------------------------------------------------------
������ basic ��Ų�� �⺻���� �Ѵ�.
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
    ��α� ���� Paging �Լ�
-----------------------------------------------------------
���������� ������ ��, ����������, ����������, URL
*/
function get_blog_paging($write_pages, $cur_page, $total_page, $url, $add="")
{
    $str = "";
    $start_page = ( ( (int)( ($cur_page - 1) / $write_pages)) * $write_pages) + 1;
    $end_page = $start_page + $write_pages - 1;

    if ($cur_page != 1)
        $str .= " &nbsp;<a href='" . $url . 1 . "{$add}'>��ó��</a>";
    else
        $str .= " &nbsp;<null>��ó��</null>";

    if ($cur_page > $write_pages)
        $str .= " &nbsp;<a href='" . $url . ($start_page-1) . "{$add}'>���� {$write_pages} ��</a>";
    else
        $str .= " &nbsp;<null>���� {$write_pages} ��</null>";

    if ($end_page >= $total_page) $end_page = $total_page;

    if ($cur_page > 1)
        $str .= " &nbsp;<a href='" . $url . ($cur_page-1) . "{$add}'>����������</a>";
    else
        $str .= " &nbsp;<null>����������</null>";

    if ($total_page > 1) {
        for ($k=$start_page;$k<=$end_page;$k++) {
            if ($cur_page != $k)
                $str .= " &nbsp;<a href='$url$k{$add}'><span>$k</span></a>";
            else
                $str .= " &nbsp;<null>$k</null> ";
        }
    }

    if ($total_page > $cur_page)
        $str .= " &nbsp;<a href='" . $url . ($cur_page+1) . "{$add}'>����������</a>";
    else
        $str .= " &nbsp;<null>����������</null>";

    if ($end_page < $total_page)
        $str .= " &nbsp;<a href='" . $url . ($end_page+1) . "{$add}'>���� {$write_pages} ��</a>";
    else
        $str .= " &nbsp;<null>���� {$write_pages} ��</null>";

    if ($cur_page < $total_page)
        $str .= " &nbsp;<a href='" . $url . ($total_page) . "{$add}'>�ǳ�</a>";
    else
        $str .= " &nbsp;<null>�ǳ�</null>";


    return $str;
}

if (!function_exists('get_member_level_select')) {

// ȸ�������� SELECT �������� ����
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
                if ($config[cf_use_member_icon] == 2) // ȸ��������+�̸�
                    $tmp_name = $tmp_name . " <span class='member'>$name</span>";
            }
        }
        $title_mb_id = "[$mb_id]";
    } else {
        $tmp_name = "<span class='guest'>$name</span>";
        $title_mb_id = "[��ȸ��]";
    }

    return "<a href=\"javascript:;\" onClick=\"showSideView(this, '$mb_id', '$name', '$email', '$homepage');\" title=\"{$title_mb_id}{$title_name}\">$tmp_name</a>";
}

// ������ ���̰� �ϴ� ��ũ (�̹���, �÷���, ������)
function blog_view_file_link($file, $width, $height, $content="")
{
    global $config;
    global $g4;
    global $gb4, $current;
    static $ids;

    $board[bo_image_width] = $current[image_width];

    if (!$file) return;

    $ids++;

    // ������ ���� �Խ��Ǽ����� �̹����� ���� ũ�ٸ� �Խ��Ǽ��� ������ ���߰� ������ ���� ���̸� ���
    if ($width > $board[bo_image_width] && $board[bo_image_width])
    {
        $rate = $board[bo_image_width] / $width;
        $width = $board[bo_image_width];
        $height = (int)($height * $rate);
    }

    // ���� �ִ� ��� ���� ������ �Ӽ��� �ְ�, ������ �ڵ� ���ǵ��� �ڵ带 ������ �ʴ´�.
    if ($width)
        $attr = " width='$width' height='$height' ";
    else
        $attr = "";

    if (preg_match("/\.($config[cf_image_extension])$/i", $file))
        // �̹����� �Ӽ��� ���� �ʴ� ������ �̹��� Ŭ���� ���� �̹����� �����ֱ� ���Ѱ���
        // �Խ��Ǽ��� �̹������� ũ�ٸ� ��Ų�� �ڹٽ�ũ��Ʈ���� �̹����� �ٿ��ش�
        return "<img src='$g4[path]/data/blog/file/$current[mb_id]/".urlencode($file)."' name='target_resize_image[]' onclick='image_window(this);' style='cursor:pointer;' title='$content'>";
    // 110106 : FLASH XSS �������� ���Ͽ� �ڵ� ��ü�� ����
    /*
    else if (preg_match("/\.($config[cf_flash_extension])$/i", $file))
        //return "<embed src='$g4[path]/data/file/$board[bo_table]/$file' $attr></embed>";
        return "<script>doc_write(flash_movie('$g4[path]/data/blog/file/$current[mb_id]/$file', '_g4_{$ids}', '$width', '$height', 'transparent'));</script>";
    */
    //=============================================================================================
    // ������ ���Ͽ� �Ǽ��ڵ带 �ɴ� ��츦 �����ϱ� ���� ��θ� �������� ����
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