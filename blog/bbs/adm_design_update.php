<?
include_once("./_common.php");

if( $member['mb_id'] != $current['mb_id'] )
    alert('�ڽ��� ��α׸� ������ ������ �� �� �ֽ��ϴ�.');

if( $page_count < 1 ) alert('�������� �� ��¼��� 1 �̻����� �������ּ���.');
if( $list_count < 1 ) alert('��Ϻ� �� ��¼��� 1 �̻����� �������ּ���.');

// ��� �̹��� ����
$top_image_path = "{$g4[path]}/data/blog/top_image/{$mb_id}";
if( file_exists($top_image_path) && $top_image_del ) {
    unlink($top_image_path);
}

// ��� �̹��� ����
$background_image_path = "{$g4[path]}/data/blog/background_image/{$mb_id}";
if( file_exists($background_image_path) && $background_image_del ) {
    unlink($background_image_path);
}

// ��� �̹��� ���� (�ݺ�)
$background_repeat_path = "{$g4[path]}/data/blog/background_repeat/{$mb_id}";
if( file_exists($background_repeat_path) && $background_repeat_del ) {
    unlink($background_repeat_path);
}

// ��Ÿ�Ͻ�Ʈ ����
$stylesheet_path = "{$g4[path]}/data/blog/stylesheet/{$mb_id}.css";
if( file_exists($stylesheet_path) && $stylesheet_del ) {
    unlink($stylesheet_path);
}

// ��� �̹��� üũ
$top_image      = $_FILES['top_image']['tmp_name'];
$top_image_size = $_FILES['top_image']['size'];

if( $top_image_size ) {

    // �̹��� ����, ���� �� mime ���� �ε�
    $get_image_size = getimagesize($top_image);

    // ������ ���� ��� �̹����� �ƴ�
    if( empty($get_image_size) )
        alert("�̹��� ���ϸ� ���ε� �����մϴ�.");

    // MINE ���� �˻��Ͽ� PNG, GIF, JPG �� ���ε� �����ϰ� ��
    $image_type = array('image/png', 'image/jpeg', 'image/gif');

    if( !in_array($get_image_size['mime'], $image_type) )
        alert("��� �̹����� JPG, GIF, PNG ���ĸ� ���ε� �����մϴ�.");
/*
    if( $gb4['top_image_width'] != 0 && $get_image_size[0] > $gb4['top_image_width'] )
        alert("��� �̹����� ���� ����� {$gb4['top_image_width']}�ȼ� ���� Ů�ϴ�.");

    if( $gb4['top_image_height'] != 0 && $get_image_size[1] > $gb4['top_image_height'] )
        alert("��� �̹����� ���� ����� {$gb4['top_image_height']}�ȼ� ���� Ů�ϴ�.");
*/
    if( $gb4['top_image_size'] != 0 && $top_image_size > $gb4['top_image_size'] )
        alert("��� �̹����� �뷮�� ".number_format($gb4['top_image_size'])." byte ���� Ů�ϴ�.");

    if( $_FILES['top_image']['error'] != 0 )
        alert("������ ���������� ���ε� ���� �ʾҽ��ϴ�.");

    $top_image_path = "{$g4[path]}/data/blog/top_image";
    @mkdir($top_image_path, 0707);
    @chmod($top_image_path, 0707);
    if( !is_dir($top_image_path) )
        alert('���丮�� �������� �ʽ��ϴ�.');

    $top_image_file = "{$top_image_path}/{$member['mb_id']}";

    // ���ε尡 �ȵȴٸ� �����޼��� ����ϰ� �׾�����ϴ�.
    $error_code = move_uploaded_file($top_image, $top_image_file) or die($_FILES['top_image']['error']);

    // �ö� ������ �۹̼��� �����մϴ�.
    chmod($top_image_file, 0606);
}



// ��� �̹��� üũ
$background_image      = $_FILES['background_image']['tmp_name'];
$background_image_size = $_FILES['background_image']['size'];

if( $background_image_size ) {

    // �̹��� ����, ���� �� mime ���� �ε�
    $get_image_size = getimagesize($background_image);

    // ������ ���� ��� �̹����� �ƴ�
    if( empty($get_image_size) )
        alert("�̹��� ���ϸ� ���ε� �����մϴ�.");

    // MINE ���� �˻��Ͽ� PNG, GIF, JPG �� ���ε� �����ϰ� ��
    $image_type = array('image/png', 'image/jpeg', 'image/gif');

    if( !in_array($get_image_size['mime'], $image_type) )
        alert("��� �̹����� JPG, GIF, PNG ���ĸ� ���ε� �����մϴ�.");
/*
    if( $gb4['background_image_width'] != 0 && $get_image_size[0] > $gb4['background_image_width'] )
        alert("��� �̹����� ���� ����� {$gb4['background_image_width']}�ȼ� ���� Ů�ϴ�.");

    if( $gb4['background_image_height'] != 0 && $get_image_size[1] > $gb4['background_image_height'] )
        alert("��� �̹����� ���� ����� {$gb4['background_image_height']}�ȼ� ���� Ů�ϴ�.");
*/
    if( $gb4['background_image_size'] != 0 && $background_image_size > $gb4['background_image_size'] )
        alert("��� �̹����� �뷮�� ".number_format($gb4['background_image_size'])." byte ���� Ů�ϴ�.");

    if( $_FILES['background_image']['error'] != 0 )
        alert("������ ���������� ���ε� ���� �ʾҽ��ϴ�.");

    $background_image_path = "{$g4[path]}/data/blog/background_image";
    @mkdir($background_image_path, 0707);
    @chmod($background_image_path, 0707);
    if( !is_dir($background_image_path) )
        alert('���丮�� �������� �ʽ��ϴ�.');

    $background_image_file = "{$background_image_path}/{$member['mb_id']}";

    // ���ε尡 �ȵȴٸ� �����޼��� ����ϰ� �׾�����ϴ�.
    $error_code = move_uploaded_file($background_image, $background_image_file) or die($_FILES['background_image']['error']);

    // �ö� ������ �۹̼��� �����մϴ�.
    chmod($background_image_file, 0606);
}


// ��� �̹��� üũ (�ݺ�)
$background_repeat      = $_FILES['background_repeat']['tmp_name'];
$background_repeat_size = $_FILES['background_repeat']['size'];

if( $background_repeat_size ) {

    // �̹��� ����, ���� �� mime ���� �ε�
    $get_image_size = getimagesize($background_repeat);

    // ������ ���� ��� �̹����� �ƴ�
    if( empty($get_image_size) )
        alert("�̹��� ���ϸ� ���ε� �����մϴ�.");

    // MINE ���� �˻��Ͽ� PNG, GIF, JPG �� ���ε� �����ϰ� ��
    $image_type = array('image/png', 'image/jpeg', 'image/gif');

    if( !in_array($get_image_size['mime'], $image_type) )
        alert("��� �̹����� JPG, GIF, PNG ���ĸ� ���ε� �����մϴ�.");

    if( $gb4['background_image_size'] != 0 && $background_repeat_size > $gb4['background_image_size'] )
        alert("��� �̹����� �뷮�� ".number_format($gb4['background_image_size'])." byte ���� Ů�ϴ�.");

    if( $_FILES['background_repeat']['error'] != 0 )
        alert("������ ���������� ���ε� ���� �ʾҽ��ϴ�.");

    $background_repeat_path = "{$g4[path]}/data/blog/background_repeat";
    @mkdir($background_repeat_path, 0707);
    @chmod($background_repeat_path, 0707);
    if( !is_dir($background_repeat_path) )
        alert('���丮�� �������� �ʽ��ϴ�.');

    $background_repeat_file = "{$background_repeat_path}/{$member['mb_id']}";

    // ���ε尡 �ȵȴٸ� �����޼��� ����ϰ� �׾�����ϴ�.
    $error_code = move_uploaded_file($background_repeat, $background_repeat_file) or die($_FILES['background_repeat']['error']);

    // �ö� ������ �۹̼��� �����մϴ�.
    chmod($background_repeat_file, 0606);
}


// ��Ÿ�Ͻ�Ʈ üũ
$stylesheet      = $_FILES['stylesheet']['tmp_name'];
$stylesheet_name = $_FILES['stylesheet']['name'];
$stylesheet_size = $_FILES['stylesheet']['size'];

if( $stylesheet_size ) {
    
    $type = strtolower(substr($stylesheet_name,strlen($stylesheet_name)-3,3));
    if(  $type != 'css' )
        alert("Ȯ���ڰ� .css �� ���ϸ� ���ε� �����մϴ�.");

    if( $gb4['stylesheet_size'] != 0 && $stylesheet_size > $gb4['stylesheet_size'] )
        alert("StyleSheet �뷮�� ".get_filesize(50*1024)." ���� Ů�ϴ�.");

    if( $_FILES['stylesheet']['error'] != 0 )
        alert("������ ���������� ���ε� ���� �ʾҽ��ϴ�.");

    $stylesheet_path = "{$g4[path]}/data/blog/stylesheet";
    @mkdir($stylesheet_path, 0707);
    @chmod($stylesheet_path, 0707);
    if( !is_dir($stylesheet_path) )
        alert('���丮�� �������� �ʽ��ϴ�.');

    $stylesheet_file = "{$stylesheet_path}/{$member['mb_id']}.css";

    // ���ε尡 �ȵȴٸ� �����޼��� ����ϰ� �׾�����ϴ�.
    $error_code = move_uploaded_file($stylesheet, $stylesheet_file) or die($_FILES['stylesheet']['error']);

    // �ö� ������ �۹̼��� �����մϴ�.
    chmod($stylesheet_file, 0606);
}

if( !empty($sidebar_category) )     $sidebar_category   = 1; else $sidebar_category = 0;
if( !empty($sidebar_monthly) )      $sidebar_monthly    = 1; else $sidebar_monthly  = 0;
if( !empty($sidebar_calendar) )     $sidebar_calendar   = 1; else $sidebar_calendar = 0;
if( !empty($sidebar_link) )         $sidebar_link       = 1; else $sidebar_link     = 0;
if( !empty($sidebar_post) )         $sidebar_post       = 1; else $sidebar_post     = 0;
if( !empty($sidebar_comment) )      $sidebar_comment    = 1; else $sidebar_comment  = 0;
if( !empty($sidebar_trackback) )    $sidebar_trackback  = 1; else $sidebar_trackback= 0;
if( !empty($sidebar_search) )       $sidebar_search     = 1; else $sidebar_search   = 0;
if( !empty($sidebar_visit) )        $sidebar_visit      = 1; else $sidebar_visit    = 0;
if( !empty($sidebar_tag) )          $sidebar_tag        = 1; else $sidebar_tag      = 0;
if( !empty($sidebar_use1) )         $sidebar_use1       = 1; else $sidebar_use1     = 0;
if( !empty($sidebar_use2) )         $sidebar_use2       = 1; else $sidebar_use2     = 0;
if( !empty($sidebar_use3) )         $sidebar_use3       = 1; else $sidebar_use3     = 0;
if( !empty($sidebar_use4) )         $sidebar_use4       = 1; else $sidebar_use4     = 0;
if( !empty($sidebar_use5) )         $sidebar_use5       = 1; else $sidebar_use5     = 0;

// ��Ų ��� ī���� ����
$res = sql_fetch("select skin_id from {$gb4['blog_table']} where id={$current['id']}");
if( $res['skin_id'] != $skin_id ) {
    sql_query("update {$gb4['skin_table']} set use_count=use_count-1  where id={$res['skin_id']}");
    sql_query("update {$gb4['skin_table']} set use_count=use_count+1  where id={$skin_id}");
}

// ������ ����  ������Ʈ
$sql = " update {$gb4['blog_table']} set mb_id = '{$member['mb_id']}' ";
$sql.= " ,blog_align = '{$blog_align}' ";
$sql.= " ,page_count = '{$page_count}' ";
$sql.= " ,list_count = '{$list_count}' ";
$sql.= " ,blog_head = '{$blog_head}' ";
$sql.= " ,blog_tail = '{$blog_tail}' ";
$sql.= " ,content_head = '{$content_head}' ";
$sql.= " ,content_tail = '{$content_tail}' ";
$sql.= " ,skin_id = '{$skin_id}' ";
$sql.= " ,top_menu_color = '{$top_menu_color}' ";
$sql.= " ,image_width = '{$image_width}' ";
$sql.= " ,blog_width = '{$blog_width}' ";
$sql.= " ,background_repeat = '{$background_repeat}' ";
/*
$sql.= " ,stylesheet = '{$stylesheet }' ";
$sql.= " ,sidebar_category = '{$sidebar_category}' ";
$sql.= " ,sidebar_monthly = '{$sidebar_monthly}' ";
$sql.= " ,sidebar_calendar = '{$sidebar_calendar}' ";
$sql.= " ,sidebar_link = '{$sidebar_link}' ";
$sql.= " ,sidebar_post = '{$sidebar_post}' ";
$sql.= " ,sidebar_comment = '{$sidebar_comment}' ";
$sql.= " ,sidebar_trackback = '{$sidebar_trackback}' ";
$sql.= " ,sidebar_search = '{$sidebar_search}' ";
$sql.= " ,sidebar_visit = '{$sidebar_visit}' ";
$sql.= " ,sidebar_tag = '{$sidebar_tag}' ";
$sql.= " ,sidebar_use1 = '{$sidebar_use1}' ";
$sql.= " ,sidebar_use2 = '{$sidebar_use2}' ";
$sql.= " ,sidebar_use3 = '{$sidebar_use3}' ";
$sql.= " ,sidebar_use4 = '{$sidebar_use4}' ";
$sql.= " ,sidebar_use5 = '{$sidebar_use5}' ";
*/
$sql.= " ,sidebar_post_num = '{$sidebar_post_num}' ";
$sql.= " ,sidebar_comment_num = '{$sidebar_comment_num}' ";
$sql.= " ,sidebar_trackback_num = '{$sidebar_trackback_num}' ";
$sql.= " ,sidebar_post_length = '{$sidebar_post_length}' ";
$sql.= " ,sidebar_comment_length = '{$sidebar_comment_length}' ";
$sql.= " ,sidebar_trackback_length = '{$sidebar_trackback_length}' ";
$sql.= " ,sidebar_tag_print = '{$sidebar_tag_print}' ";
$sql.= " ,sidebar_tag_length = '{$sidebar_tag_length}' ";
$sql.= " ,sidebar_tag_gap = '{$sidebar_tag_gap}' ";
$sql.= " ,sidebar_user1_title = '{$sidebar_user1_title}' ";
$sql.= " ,sidebar_user2_title = '{$sidebar_user2_title}' ";
$sql.= " ,sidebar_user3_title = '{$sidebar_user3_title}' ";
$sql.= " ,sidebar_user4_title = '{$sidebar_user4_title}' ";
$sql.= " ,sidebar_user5_title = '{$sidebar_user5_title}' ";
$sql.= " ,sidebar_user1_content = '{$sidebar_user1_content}' ";
$sql.= " ,sidebar_user2_content = '{$sidebar_user2_content}' ";
$sql.= " ,sidebar_user3_content = '{$sidebar_user3_content}' ";
$sql.= " ,sidebar_user4_content = '{$sidebar_user4_content}' ";
$sql.= " ,sidebar_user5_content = '{$sidebar_user5_content}' ";
$sql.= " where id = '{$current['id']}' ";

sql_query($sql);

// �ش� �޽����� ��� �� �ڽ��� ��α׷� �̵�
alert( "������ ������ ����Ǿ����ϴ�.", urldecode($url) );
?>