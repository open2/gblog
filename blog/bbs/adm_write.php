<?
include_once("./_common.php");

$g4['title'] = "��α� �� �ۼ�/����";

if ($current['mb_id'] != $member['mb_id'])
    alert('�ڽ��� �۸� ���/������ �� �ֽ��ϴ�.');

if (!$id) $id = 0;

// �� ������ ���
if ($m == 'u')
{
    $r = sql_fetch("select * from {$gb4['post_table']} where id='{$id}'");
    if (empty($r))
        alert('�������� �ʴ� ���Դϴ�.');

    extract($r);

    $reload = '';

    if ($use_comment)
        $use_comment = 'checked'; else $use_comment = '';

    if ($use_trackback)
        $use_trackback = 'checked'; else $use_trackback = '';

    if ($use_ccl_writer)
        $use_ccl_writer = 'checked'; else $use_ccl_writer = '';

    if ($use_ccl_commecial)
        $use_ccl_commecial = 'checked'; else $use_ccl_commecial = '';

    if ($use_ccl_modify)
        $use_ccl_modify = 'checked'; else $use_ccl_modify = '';

    if ($use_ccl_allow)
        $use_ccl_allow = 'checked'; else $use_ccl_allow = '';

    $content = get_text($r['content'],0);

    // �±� ��������
    if ($current['use_tag']) {
        $use_tag = 'checked';
        $tag = get_post_tag($id, ', ');
    }
}
// �� ���ε�� �� ���
else
{
    $post_date = date('Y-m-d H:i:s');
    $secret = 1;
    $reload = 'checked';

    $use_ccl_commecial = 'checked';
    $use_ccl_modify = 'checked';

    if ($current['rss_open']      )    $use_rss       = 1;
    if ($current['use_comment']   )    $use_comment   = 'checked';
    if ($current['use_trackback'] )    $use_trackback = 'checked';
    if ($current['use_tag'] )          $use_tag = 'checked';
    
}

$file = get_blog_file($id);

// �з� ȣ��
$category = array();
$q = sql_query(" select * from {$gb4['category_table']} where blog_id='{$current['id']}' order by rank ");
while ($r = sql_fetch_array($q)) array_push($category, $r);

if ($m == "u")
{
$file = get_blog_file($id);;
}

// ���� ����
$file_script = "";
$file_length = -1;
// ������ ��� ���Ͼ��ε� �ʵ尡 ���������� �þ�� �ϰ� ���� ǥ�õ� ���־�� �մϴ�.
if ($m == "u")
{
    for ($i=0; $i<$file[count]; $i++)
    {
        $row = sql_fetch(" select real_name from $gb4[file_table] where blog_id = '$current[id]' and post_id = '$id' and file_num = '$i' ");
        if ($row[real_name])
        {
            $file_script .= "add_file(\"<input type='checkbox' name='bf_file_del[$i]' value='1'><a href='{$file[$i][href]}'>{$file[$i][real_name]}({$file[$i][size]})</a> ���� ����";
            if ($is_file_content)
                //$file_script .= "<br><input type='text' class=ed size=50 name='bf_content[$i]' value='{$row[bf_content]}' title='���ε� �̹��� ���Ͽ� �ش� �Ǵ� ������ �Է��ϼ���.'>";
                // ÷�����ϼ����� ' �Ǵ� " �ԷµǸ� �������� �κ� ����
                $file_script .= "<br><input type='text' class=ed size=50 name='bf_content[$i]' value='".addslashes(get_text($row[bf_content]))."' title='���ε� �̹��� ���Ͽ� �ش� �Ǵ� ������ �Է��ϼ���.'>";
            $file_script .= "\");\n";
        }
        else
            $file_script .= "add_file('');\n";
    }
    $file_length = $file[count] - 1;
}

if ($file_length < 0)
{
    $file_script .= "add_file('');\n";
    $file_length = 0;
}

// ���� ����� �±׵�
$sql = " select distinct b.tag from $gb4[taglog_table] a left join $gb4[tag_table] b on (a.tag_id = b.id) where a.blog_id = '$current[id]' ";
$tags = sql_query($sql);
$tags_array = array();
while ($res = sql_fetch_array($tags)) {
    $tags_array[] = $res[tag];
}

// ����α� ��ü���� ����� �±׵�
if (count($tags_array))
    $in_list = "where tag not in ( " . implode_wrapped("'", "'", ",", $tags_array) . " )";
else
    $in_list = "";
$sql = " select tag from $gb4[tag_table] $in_list order by tag_count desc, lastdate desc limit 10 ";
$tags = sql_query($sql);
$tags_array_gblog = array();
while ($res = sql_fetch_array($tags)) {
    $tags_array_gblog[] = $res[tag];
}

//--------------------------------------------------------------------------

include_once("$gb4[path]/head.sub.php");
include_once("$blog_skin_path/head.skin.php");

include_once ("$blog_skin_path/write.skin.php");

include_once("$blog_skin_path/tail.skin.php");
include_once("$gb4[path]/tail.sub.php");
?>