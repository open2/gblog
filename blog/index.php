<?
include_once("./_common.php");

// �Ķ���� mb_id ���� ���� ���� ��α� �������� �̵�
if (!$mb_id) {
    goto_url( "$gb4[url]/gblog.index.php");
}

// ��αװ� ������, ��α׸����� �̵� �մϴ�.
if (!$current && $member[mb_id] && $mb_id == "$member[mb_id]" ) {
    alert('��α� ������ �̵��մϴ�.', "$gb4[bbs_path]/join_blog.php");
}

// ��α׸� �����ֱ� ���� ������ �н��ϴ�.
if ($current) {

    // $id�� ���� ������ 0����
    if (!$id) $id = 0;

    // ������ ��ȣ �ʱ�ȭ
    if (!is_numeric($page))
        $page = 1;
    else
        $page = (int)$page;

    // ����Ʈ �˻�
    if (!empty($search))
    {
        $search = revision_charset($search);
        $sql_search = " and (p.title like '%$search%' or p.content like '%$search%') ";
    }

    // �з� �˻�
    if (!empty($cate))
    {
        $cate = revision_charset($cate);

        if ($cate != 'all')  {

            // �з��̸����� �з� ������ȣ�� �����´�.
            $row  = sql_fetch("select id from $gb4[category_table] where category_name = '$cate'");

            // �з� ������ȣ�� �˻� ������ ����� ����.
            if (empty($row))
                $sql_cate = " and null ";
            else
                $sql_cate = " and p.category_id = '$row[id]' ";
        }
    }

    // �±׺� �˻�, �� ���ô�;
    if (!empty($tag))
    {
        // �±� �� �� ���� ����
        $tag = revision_charset($tag);

        // �±� ������ȣ�� ���� �´�.
        $row = sql_fetch("select id from $gb4[tag_table] where tag = '$tag'");

        // �±� ������ȣ�� ������ ��쿡�� �˻��� �����ϴ�.
        if (!empty($row))
        {
            // �±� ������ȣ�� �ش� �±׸� ����� ���� ã�Ƴ� �˻� ������ �����.
            $sql_tag = "and p.id in(''";

            $qry = sql_query("select post_id from $gb4[taglog_table] where tag_id='$row[id]' ");

            while($row = sql_fetch_array($qry))
                $sql_tag .= ",".$row[post_id];

            $sql_tag .= ")";
        }
    }

    // ���� �˻�
    if (!empty($mon))
    {
        // ���� ����
        $mon = trim($mon);

        // �ش� ���� ���� �����ϴ��� �˻�
        $row = sql_fetch("select * from $gb4[monthly_table] where blog_id='$current[id]' and monthly='$mon'");

        // �ش� ���� ���� �ִٸ� �˻��� �߰�
        if (!empty($row))
            $sql_monthly = " and left(post_date,7)='$mon' ";

        $month = explode('-',$mon);
        $yyyy = $month[0];
        $mm = $month[1];
    }

    // ��¥�� �˻�
    if (!empty($yyyy) && !empty($mm) && empty($dd))
    {
        $sql_cur = " and left(post_date,7) = '$yyyy-$mm' ";
    }
    elseif (!empty($yyyy) && !empty($mm) && !empty($dd))
    {
        $sql_cur = " and left(post_date,10) = '$yyyy-$mm-$dd' ";
    }
    
    // �� �ϳ��� �� ���
    if (!empty($id)) $sql_id = " and p.id = '$id' ";
    
    // ����¡�� ���� �� �ε�
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
    
    // ���� �������� �� ������ DB ���� �ҷ��´�.
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
    
    //  $post ������ �� ������ ��� ��´�.
    
    // �� ������ ���� $post ���� �ʱ�ȭ
    $post = array();
    $index = 0;
    
    while ($row = sql_fetch_array($qry)) {
    
        // �ϴ� DB ���� ������ �� ������ ���� $post ������ ��´�.
        $post[$index] = $row;

        // contents
        $post[$index][rich_post] = $post[$index][content];
        $post[$index][content] = conv_content($post[$index][content], 1);
    
        // ���� �����ּҸ� �߰��� �����Ѵ�.
        $post[$index][url] = get_post_url($row[id]);
    
        // �ۿ� �з� ���� �Ǿ����� ������� '�з�����' �� �⺻ �������ش�.
        if (empty($row[category_name]))
            $post[$index][category_name] = '�з�����';
    
        // �̹��� ��������
        $post[$index][content] = preg_replace("/(\<img)([^\>]*)(\>)/i", "\\1 name='target_resize_image[]' onclick='image_window(this)' style='cursor:pointer;' \\2 \\3", $post[$index][content]);
    
        // div �±� ������ �����.
        $post[$index][content] = get_sync_tag($post[$index][content], 'div');
    
        /*---------------------------------------
            ÷�������� �����´�.
        -----------------------------------------*/
        $post[$index][file] = get_blog_file($row[id]);
    
        /*---------------------------------------
            �±׸� �����´�.
        -----------------------------------------*/
        $post[$index][tag] = get_post_tag($row[id], ', ', true);
    
        /*---------------------------------------
            ���α��� �����´�
        -----------------------------------------*/
    
        // ���α��� ������ �迭�� �ʱ�ȭ�Ѵ�.
        $post[$index][trackback] = array();
    
        // ���α��� DB ���� �����´�.
        $trackback_sql = "select * from $gb4[trackback_table] where post_id='{$post[$index][id]}' order by id";
        $trackback_qry = sql_query($trackback_sql);
    
        // $post[$index][trackback] ������ ���� ���� ���α��� ��´�.
        while ($trackback_row = sql_fetch_array($trackback_qry))
        {
            $trackback_row[writer_content] = strip_tags($trackback_row[writer_content]);
    
            // ���� ��α� �ּҸ� ��ũ �Ǵ�.
            if ($trackback_row[writer_url]) {
                $writer_url = str_replace("http://", "", $trackback_row[writer_url]);
                $trackback_row[writer_subject] = "<a href='http://$writer_url' target=_blank>$trackback_row[writer_subject]</a>";
            }
    
            $trackback_row[writer_content] = cut_str($trackback_row[writer_content], 255);
    
            // ���α� ������ �迭�� ��´�.
            array_push($post[$index][trackback], $trackback_row);
    
            // ���� �迭�� �ε��� ��ȣ�� ��´�.
            $trackback_index = count($post[$index][trackback])-1;
        }
    
        // ���α� �ּ�
        $post[$index][trackback_url] = "$g4[url]/$gb4[blog]/tb.php/$current[mb_id]/$row[id]";
    
        if (!empty($id)) {
    
            // ���� �� ������ �迭(href, content)�� ��´�.
            $post[$index][prev] = get_prev_post($current[id], $row[post_date]);
    
            // ���� �� ������ ��´�.
            $post[$index][next] = get_next_post($current[id], $row[post_date]);
        }
    
        $index++;
    }
    
    // ��� ��������
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
    
        // ��ȸ�� ����
        // �ѹ� �������� �������� �ݱ��������� ī��Ʈ�� ������Ű�� ����
        $ss_name = "ss_blog_view_$mb_id_$id";
        if (!get_session($ss_name))
        {
            sql_query("update $gb4[post_table] set hit = hit + 1 where blog_id = '$current[id]' and id = '$id'");
            set_session($ss_name, TRUE);
        }
    }

    // ������ Ÿ��Ʋ
    $g4[title] = $current[blog_name];

    // ��α׺� �湮�� ��踦 ������Ʈ
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

    // �� �ϳ��� ����, bit.ly Short URL�� �����ش�
    if (!empty($id) && $gb4[bitly_id] && $gb4[bitly_key] && !$post[0][bitly_url]) {
        // �����θ� ������ ��α� url�� �������ݴϴ�.
        $blog_url = "$g4[url]$_SERVER[REQUEST_URI]";
    ?>
        <script language=javascript>
        // encode �� ���� �Ѱ��ָ�, �˾Ƽ� decode�ؼ� ����� return ���ش�.
        // encode �ϱ� ���� url�� �־�� ����� ���� �� �ֱ� ������, �ᱹ 2���� �Ѱ��ش�.
        // ��? java script������ urlencode, urldecode�� �����ϱ�. ����
        // ���� �̰Ŵ� �������� �ؾ� �Ѵ�. ��??? �׷��� ������ html page�� ������Ʈ ����~!
        var bitly_id = '<?=$gb4[bitly_id]?>';
        var bitly_key = '<?=$gb4[bitly_key]?>';
        get_bitly_gblog('#bitly_url', '<?=$current[mb_id]?>', '<?=$id?>', '<?=urlencode($blog_url)?>', '<?=$blog_url?>');
        </script>
<? } ?>