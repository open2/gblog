<?
include_once("./_common.php");

// POST �� �Ѿ�� ���� ��ȿ�� �˻�
if( !trim($blog_name) )
    alert('��α� �̸��� �Է����ּ���.');

if( 0 > $rss_open || $rss_open > 2 )
    alert('RSS ���� ���θ� �������ּ���.');

if( $rss_open != 0 && !trim($rss_count) )
    alert('�ֽ� RSS ���� ������ �Է����ּ���.');

// ��α� �̸����� tag�� ����
$blog_name = strip_tags($blog_name);

// ������ �̹��� ����
$profile_image_path = "{$g4[path]}/data/blog/profile_image/{$mb_id}";
if( file_exists($profile_image_path) && $profile_image_del ) {
    unlink($profile_image_path);
}

// ������ �̹��� üũ
$profile_image      = $_FILES['profile_image']['tmp_name'];
$profile_image_size = $_FILES['profile_image']['size'];

if( $profile_image_size ) {

    // �̹��� ����, ���� �� mime ���� �ε�
    $get_image_size = getimagesize($profile_image);

    // ������ ���� ��� �̹����� �ƴ�
    if( empty($get_image_size) )
        alert("�̹��� ���ϸ� ���ε� �����մϴ�.");

    // MINE ���� �˻��Ͽ� PNG, GIF, JPG �� ���ε� �����ϰ� ��
    $image_type = array('image/png', 'image/jpeg', 'image/gif');

    if( !in_array($get_image_size['mime'], $image_type) )
        alert("������ �̹����� jpg, gif, png ���ĸ� ���ε� �����մϴ�.".$get_image_size['mime']);
/*
    if( $gb4['profile_image_width'] != 0 && $get_image_size[0] > $gb4['profile_image_width'] )
        alert("������ �̹����� ���� ����� {$gb4['profile_image_width']}�ȼ� ���� Ů�ϴ�.");

    if( $gb4['profile_image_height'] != 0 && $get_image_size[1] > $gb4['profile_image_height'] )
        alert("������ �̹����� ���� ����� {$gb4['profile_image_height']}�ȼ� ���� Ů�ϴ�.");
*/
    if( $gb4['profile_image_size'] != 0 && $profile_image_size > $gb4['profile_image_size'] )
        alert("������ �̹����� �뷮�� ".number_format($gb4['profile_image_size'])." byte ���� Ů�ϴ�.");

    // ������ �̹����� MySQL �� �����ϱ� ���� BLOB Ÿ������ ��ȯ
    //$profile_image  = addslashes(fread(fopen($profile_image, "r"), $profile_image_size));

    $profile_image_path = "{$g4[path]}/data/blog/profile_image";
    if( !is_dir($profile_image_path) )
        alert('���丮�� �������� �ʽ��ϴ�.');

    $profile_image_file = "{$profile_image_path}/{$member['mb_id']}";

    // ���ε尡 �ȵȴٸ� �����޼��� ����ϰ� �׾�����ϴ�.
    $error_code = move_uploaded_file($profile_image, $profile_image_file) or die($_FILES['profile_image']['error']);

    // �ö� ������ �۹̼��� �����մϴ�.
    chmod($profile_image_file, 0606);
}

// ��α� ���� ������Ʈ
if( $w == 'u' )
{
    // ������ �����Ѵ�.
    $sql = "update
                {$gb4['blog_table']}
            set
                blog_name      = '{$blog_name}'
                ,blog_about     = '{$blog_about}'
                ,rss_open       = '{$rss_open}'
                ,rss_count      = '{$rss_count}'
                ,last_update    = '{$g4['time_ymdhis']}'
                ,use_post       = '{$use_post}'
                ,use_comment    = '{$use_comment}'
                ,use_guestbook  = '{$use_guestbook}'
                ,use_trackback  = '{$use_trackback}'
                ,use_tag        = '{$use_tag}'
                ,use_ccl        = '{$use_ccl}'
                ,use_autosource = '{$use_autosource}'
                ,use_random     = '{$use_random}'
            where
                mb_id = '{$member['mb_id']}'";
    sql_query($sql);

    /*// ������ �̹��� ������Ʈ.
    if( $profile_image_size != 0 )
        sql_query("update {$gb4['blog_table']} set profile_image = '{$profile_image}' where mb_id = '{$member['mb_id']}'");
    */
    $msg = "�⺻������ ����Ǿ����ϴ�.";
}

// ��α� �����
else
{
    // ��α׸� ������ �ִٸ� �ڽ��� ��α׷� �̵��Ѵ�.
    if( have_a_blog($member['mb_id']) )
        alert('�̹� ��α׸� ������ ��ʴϴ�.', $blog_url);

    // ȸ�� ���� �˻�
    if( $member['mb_level'] < $gb4['make_level'] )
        alert("��α׸� ������ {$gb4['make_level']} �������� �����մϴ�.");

    // ȸ�� ����Ʈ �˻�
    if( $member['mb_point'] < $gb4['make_point'] )
        alert('��α׸� �����Ϸ��� '.number_format($gb4['make_point']).' ����Ʈ�� �ʿ��մϴ�.');

    // �⺻ basic ��Ų���
    $res = sql_fetch("select id from {$gb4['skin_table']} where skin='basic'");
    $skin_id = $res['id'];

    sql_query("update {$gb4['skin_table']} set use_count=use_count+1 where id={$skin_id}");

    // �ʱⰪ ����
    $page_count = 5;
    $list_count = 10;
    $sidebar_right   = 'profile,admin,search,category,recent_post,recent_comment,recent_trackback,tag,calendar,monthly,link,counter,rss';
    $sidebar_garbage = 'user_1,user_2,user_3,user_4,user_5';

    // ��α׸� �����Ѵ�.
    $sql = "insert into
                {$gb4['blog_table']}
            set
                id              = '{$member[mb_no]}'
                ,mb_id          = '{$member['mb_id']}'
                ,writer         = '{$member['mb_nick']}'
                ,blog_name      = '{$blog_name}'
                ,blog_about     = '{$blog_about}'
                ,rss_open       = '{$rss_open}'
                ,rss_count      = '{$rss_count}'
                ,last_update    = '{$g4['time_ymdhis']}'
                ,use_post       = '{$use_post}'
                ,use_comment    = '{$use_comment}'
                ,use_guestbook  = '{$use_guestbook}'
                ,use_trackback  = '{$use_trackback}'
                ,use_tag        = '{$use_tag}'
                ,use_ccl        = '{$use_ccl}'
                ,use_autosource = '{$use_autosource}'
                ,use_random     = '{$use_random}'
                ,regdate        = '{$g4['time_ymdhis']}'
                ,page_count     = '{$page_count}'
                ,list_count     = '{$list_count}'
                ,use_table      = '{$gb4['blog_table']}'
                ,skin_id        = '{$skin_id}'
                ,sidebar_right  = '{$sidebar_right}'
                ,sidebar_garbage= '{$sidebar_garbage}'
                ";
    sql_query($sql);

    // ��α� ���� ����Ʈ ����
    if( $gb4['make_point'] )
        insert_point( $member['mb_id'], $gb4['make_point'], '��α� ����' );


    $msg = "$member[mb_nick] ���� ��αװ� ����������ϴ�.\\n\\n��ſ� ��α� �ϼ��� ^O^";
}

if( $w == 'u' )
    alert( $msg );
else
    alert( $msg, urldecode("$gb4[path]/?mb_id=$member[mb_id]") );
?>