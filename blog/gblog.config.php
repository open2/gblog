<?
// GBlog ���� ���Ǵ� ���̺�
$gb4['blog_prefix']           = 'gb4_';
$gb4['blog_table']            = $gb4['blog_prefix'] . 'blog';
$gb4['category_table']        = $gb4['blog_prefix'] . 'category';
$gb4['comment_table']         = $gb4['blog_prefix'] . 'comment';
$gb4['config_table']          = $gb4['blog_prefix'] . 'config';
$gb4['division_table']        = $gb4['blog_prefix'] . 'division';
$gb4['file_table']            = $gb4['blog_prefix'] . 'file';
$gb4['guestbook_table']       = $gb4['blog_prefix'] . 'guestbook';
$gb4['link_category_table']   = $gb4['blog_prefix'] . 'link_category';
$gb4['link_table']            = $gb4['blog_prefix'] . 'link';
$gb4['monthly_table']         = $gb4['blog_prefix'] . 'monthly';
$gb4['neighborhood_table']    = $gb4['blog_prefix'] . 'neighborhood';
$gb4['post_table']            = $gb4['blog_prefix'] . 'post';
$gb4['skin_table']            = $gb4['blog_prefix'] . 'skin';
$gb4['tag_table']             = $gb4['blog_prefix'] . 'tag';
$gb4['taglog_table']          = $gb4['blog_prefix'] . 'taglog';
$gb4['trackback_table']       = $gb4['blog_prefix'] . 'trackback';
$gb4['visit_table']           = $gb4['blog_prefix'] . 'visit';
$gb4['visit_sum_table']       = $gb4['blog_prefix'] . 'visit_sum';

// ������
if (function_exists('sql_fetch')) {
    $gb4_config = sql_fetch(" select * from $gb4[config_table] ", false);
    $gb4 = array_merge($gb4, $gb4_config);
}

// ����
$gb4['blog']      = 'blog';
$gb4['admin']     = 'adm';
$gb4['bbs']       = 'bbs';
$gb4['root']      = "$g4[path]";
$gb4['path']      = "$gb4[root]/$gb4[blog]";
$gb4['admin_path'] = "$gb4[path]/$gb4[admin]";
$gb4['bbs_path']  = "$gb4[path]/$gb4[bbs]";
// $_SERVER[HTTP_HOST]�� ������ ���ڰ� "/"�� �ƴ� �� �����Ƿ� �ڿ� "/"�� ���δ�.
// ���߿� revision_url���� // �ΰ��� 1���� �ٲ��.
$gb4['host']      = "http://$_SERVER[HTTP_HOST]" . "/";

// permalink�� ���ؼ�
if ($gb4[use_permalink] == "none") {
    $gb4['root_url']  = $gb4['root'];
    $gb4['url']       = "$gb4[root]/$gb4[blog]";
} else if ($gb4[use_permalink] == "numeric") {
    // url�� �����η� ǥ������ ������ ����� ���ɴϴ�.
    $gb4['root_url']  = "/";
    $gb4['url']           = $gb4['root_url'] . "$gb4[blog]";
}

$gb4['bbs_url']   = "$gb4[url]/$gb4[bbs]";
$gb4['index']     = "$gb4[url]/index.php";

// ��Ų�� ���� �� ����� �⺻ skin�� �����Ѵ�
$gb4['default_skin']     = "basic";

// bit.ly api ������ ����Ѵ�
if ($g4[bitly_id])
    $gb4[bitly_id] = $g4[bitly_id];
else
    $gb4[bitly_id] = "";  // ����α׿��� �����Ϸ��� ��⿡ bit.ly id�� ������ �˴ϴ�.

if ($g4[bitly_key])
    $gb4[bitly_key] = $g4[bitly_key];
else
    $gb4[bitly_key] = "";   // ����α׿��� �����Ϸ��� ��⿡ bit.ly key�� ������ �˴ϴ�.
?>