<?
include_once("./_common.php");

// captcha Ȯ��
if (!(get_session('captcha_keystring') && get_session('captcha_keystring') == $_POST['kcaptcha_key'])) {
    alert('�������� ������ �ƴѰ� �����ϴ�.');
}

// gblog index page
$url = "$g4[path]/gblog.index.php";

$mb_id = $_POST[mb_id];

if ($mb_id != $member[mb_id])
    alert("�ڽ��� ��αװ� �ƴϹǷ� ����� �� �����ϴ�.", "$url");

// ��α� id ã��
$current = sql_fetch("select * from $gb4[blog_table] where mb_id='$mb_id'");
if (!$current)
    alert("��αװ� �������� �����Ƿ� ����� �� �����ϴ�.", "$url");
$blog_id = $current['id'];
$skin_id = $current['skin_id'];

// ��ū ����
check_token();

// ��α� ������ ����
$sql = " delete from $gb4[category_table] where blog_id = '$blog_id' ";
sql_query($sql, FALSE);
$sql = " delete from $gb4[comment_table] where blog_id = '$blog_id' ";
sql_query($sql, FALSE);
$sql = " delete from $gb4[guestbook_table] where blog_id = '$blog_id' ";
sql_query($sql, FALSE);
$sql = " delete from $gb4[link_category_table] where blog_id = '$blog_id' ";
sql_query($sql, FALSE);
$sql = " delete from $gb4[link_table] where blog_id = '$blog_id' ";
sql_query($sql, FALSE);
$sql = " delete from $gb4[monthly_table] where blog_id = '$blog_id' ";
sql_query($sql, FALSE);
$sql = " delete from $gb4[post_table] where blog_id = '$blog_id' ";
sql_query($sql, FALSE);
$sql = " delete from $gb4[taglog_table] where blog_id = '$blog_id' ";
sql_query($sql, FALSE);
$sql = " delete from $gb4[trackback_table] where blog_id = '$blog_id' ";
sql_query($sql, FALSE);
$sql = " delete from $gb4[visit_table] where vi_blog_id = '$blog_id' ";
sql_query($sql, FALSE);
$sql = " delete from $gb4[visit_sum_table] where vs_blog_id = '$blog_id' ";
sql_query($sql, FALSE);

// ÷�� file ���� 
$sql = " select * from $gb4[file_table] where blog_id = '$blog_id' ";
$result = sql_query($sql);
while ($row = sql_fetch_array($result)) 
{
    // ���� ���� ...
}
$sql = " delete from $gb4[file_table] where blog_id = '$blog_id' ";
$result = sql_query($sql);

// ��α� skin count -1
$sql = " update $gb4[skin_table] set use_count=use_count-1 where id='$skin_id' ";
$result = sql_query($sql);

// ��α� ������ ���� �������� ����
$sql = " delete from $gb4[blog_table] where id = '$blog_id' ";
sql_query($sql, FALSE);

// ��α� �������� �̵�
goto_url ($url);
?>