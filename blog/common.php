<?
// gblog ������ �ʱ�ȭ
$current = array();
$use_sb = array();

// gblog ���
$g4['gb4_path'] = $gb4_path;

//����� ������ ���ֱ� ���� $gb4_path ������ ����
unset($gb4_path);

// gblog ������ �н��ϴ�.
include_once("$g4[gb4_path]/gblog.config.php");

//gblog.lib.php ������ �н��ϴ�.
include_once("$gb4[path]/lib/blog.lib.php");

// ��뺯���� escape
$mb_id = mysql_real_escape_string($mb_id);
$id = (int) $id;

// $mb_id�� �����Ǹ� �ش� blog ������ �н��ϴ�.
if ($mb_id)
    $current = have_a_blog($mb_id);

// �����α��� ������ ���� �մϴ�.
if ($current) {

    // ���� ��α� ������ �̸��� �ּҿ� Ȩ������ �ּҸ� ��´�.
    $row = get_member($current[mb_id], "mb_email, mb_homepage, mb_nick");
    $current[writer] = $row[mb_nick];
    $current[mb_email] = $row[mb_email];
    $current[mb_homepage] = $row[mb_homepage];

    // ������ �ּ�
    $current[admin_url] = revision_url("$gb4[bbs_url]/adm_post_list.php?mb_id=$member[mb_id]");
    $current[write_url] = revision_url("$gb4[bbs_url]/adm_write.php?mb_id=$member[mb_id]");

    // ���� ��α��ּ�
    $current[blog_url] = get_blog_url($mb_id);

    // top_image
    $current[top_image_path] = revision_url("$g4[path]/data/blog/top_image/$mb_id");
    $current[top_image_url] = revision_url("$gb4[root]/data/blog/top_image/$mb_id");

    // background_image
    $current[background_image_path] = revision_url("$g4[path]/data/blog/background_image/$mb_id");
    $current[background_image_url] = revision_url("$gb4[root]/data/blog/background_image/$mb_id");

    // background_repeat
    $current[background_repeat_path] = revision_url("$g4[path]/data/blog/background_repeat/$mb_id");
    $current[background_repeat_url] = revision_url("$gb4[root]/data/blog/background_repeat/$mb_id");

    // ������ �̹���
    $current[profile_image_path]  = revision_url("$g4[path]/data/blog/profile_image/$current[mb_id]");
    $current[profile_image_url]   = revision_url("$gb4[root]/data/blog/profile_image/$current[mb_id]");

    // ��Ÿ�� ��Ʈ
    $current[stylesheet_path] = revision_url("$g4[path]/data/blog/stylesheet/$mb_id.css");
    $current[stylesheet_url]  = revision_url("$gb4[root]/data/blog/stylesheet/$mb_id.css");

    // �˻�url
    if ($gb4[use_permalink] == 'numeric')
        $search_url = revision_url("$current[blog_url]/search/");
    else
        $search_url = revision_url("$current[blog_url]&search=");

    // ���̵�� �̸� ����
    $sidebar_define = array(
        'profile'           => '������',
        'admin'             => '������',
        'search'            => '�˻�',
        'category'          => '�ۺз�',
        'recent_post'       => '�ֱٱ�',
        'recent_comment'    => '�ֱٴ��',
        'recent_trackback'  => '�ֱٿ��α�',
        'tag'               => '�±ױ���',
        'calendar'          => '�޷�',
        'monthly'           => '����',
        'link'              => '���ã��',
        'counter'           => 'ī����',
        'rss'               => 'RSS',
        'user_1'            => '��������� 1',
        'user_2'            => '��������� 2',
        'user_3'            => '��������� 3',
        'user_4'            => '��������� 4',
        'user_5'            => '��������� 5'
    );

    // DB �� ���̵�� ���� ������ �⺻��.
    $sidebar_default = "$current[sidebar_left]$current[sidebar_right]$current[sidebar_garbage]";
    if (!trim($sidebar_default)) {
        $current[sidebar_left] = '';
        $current[sidebar_right] = 'profile,admin,search,category,recent_post,recent_comment,recent_trackback,tag,calendar,monthly,link,counter,rss';
        $current[sidebar_garbage] = 'user_1,user_2,user_3,user_4,user_5';
    }

    // ���̵�� ���̾ƿ�
    $sidebar_used  = "$current[sidebar_left]$current[sidebar_right]";
    foreach($sidebar_define as $key => $value) {
        if (strstr($sidebar_used,$key))
            $use_sb[$key] = true;
        else
            $use_sb[$key] = false;
    }

    $sidebar_left = get_sidebar_list('left');
    $sidebar_right = get_sidebar_list('right');
    //$sidebar_top = get_sidebar_list('top');
    //$sidebar_bottom = get_sidebar_list('bottom');

    // ��б�
    if ($member[mb_id] != $current[mb_id])
        $sql_secret = " and secret = 1 ";

    // ��Ų ȣ��
    $blog_skin = get_skin($current[mb_id], $preview);

    $blog_skin_path = revision_url("$gb4[path]/skin/blog/$blog_skin");
    $blog_skin_url = revision_url("$gb4[url]/skin/blog/$blog_skin");

    // ��α� ���� ���
    $current[file_path] = revision_url("$g4[path]/data/blog/file/$current[mb_id]");
    $current[file_url] = revision_url("$gb4[root]/data/blog/file/$current[mb_id]");

    // rss �ּ� : �۸Ӹ�ũ�� ����� ���
    if ($gb4[use_permalink] != 'none') {
        $current[rss] = revision_url("$current[blog_url]/rss");

    // rss �ּ� : �۸Ӹ�ũ�� ������� ���� ���
    } else {
        $current[rss] = "$gb4[bbs_path]/rss.php?mb_id=$mb_id";
    }

}

// ȸ���� ��� ȸ���� blog url�� �н��ϴ�.
if ($member[mb_id])
    $member[blog_url] = get_blog_url($member[mb_id]);
?>