<?
include_once("./_common.php");

if ($current[mb_id] != $member[mb_id])
    alert('�ڽ��� �۸� ������ �� �ֽ��ϵ�.');

$post = sql_fetch("select * from {$gb4[post_table]} where id='{$id}'");
$blog_id = $post[blog_id];
$mb = sql_fetch(" select mb_id from $gb4[blog_table]  where id = '$blog_id' ");
$blog_mb_id = $mb[mb_id];

if (empty($post))
    alert('�������� �ʴ� �� �Դϵ�.');

if ($post[secret] == 0) 
    $count_field = 'secret_count'; 
else 
    $count_field = 'post_count';

// ���� �����.
sql_query("delete from {$gb4[post_table]} where id='{$id}'");

// ÷�������� �����.
$qry = sql_query("select * from {$gb4[file_table]} where blog_id='{$current[id]}' and post_id='{$id}'");
while( $res = sql_fetch_array($qry)) {
    if (!empty($res[save_name])) {
        @unlink("{$current[file_path]}/{$res[save_name]}");
    }
}
sql_query("delete from {$gb4[file_table]} where post_id='{$id}'");

// �±�ī��Ʈ�� �ϳ��� ���ش�
$sql = " select * from {$gb4[taglog_table]} where post_id='{$id}'";
$qry = sql_query($sql);
while( $res = sql_fetch_array($qry)) {
    $sql = " select tag_count from $gb4[tag_table] where id = '$res[tag_id]' ";
    $res2 = sql_fetch($sql);

    // tag_count�� 1�̰ų� �� ���ϸ� �����, �ƴϸ� ���ڸ� �����Ѵ�.
    if ($res2[tag_count] > 1)
        $sql = " update $gb4[tag_table] set tag_count = tag_count - 1 where id = '$res[tag_id]' ";
    else
        $sql = " delete from $gb4[tag_table] where id = '$res[tag_id]' ";
    sql_query($sql);
}

// �±׸� �����.
sql_query("delete from {$gb4[taglog_table]} where post_id='{$id}'");

// ����� �����.
sql_query("delete from {$gb4[comment_table]} where post_id='{$id}'");

// ���α��� �����.
sql_query("delete from {$gb4[trackback_table]} where post_id='{$id}'");

// �ش� �з��� �� ī��Ʈ�� ���ҽ�Ų��.
if ($post[category_id])
    sql_query("update {$gb4[category_table]} set {$count_field} = {$count_field} - 1 where id = '{$post[category_id]}'");

// �ش� ��α� ���� ���̺� ��,���,���α� ���� ���� �� ������ ������Ʈ �ð��� ����� �����Ѵ�.
// �Խñ� ������ ���� �ִ� ���� �ƴϹǷ�, ī���� �������� ��ȸ�� ����.
if ($post[secret] == 0) 
    $sql = " select count(*) as cnt from $gb4[post_table] where blog_id = '$blog_id' and secret = '0' ";
else
    $sql = " select count(*) as cnt from $gb4[post_table] where blog_id = '$blog_id' and secret = '1' ";
$post_cnt = sql_fetch($sql);

$sql = " select count(*) as cnt from $gb4[comment_table] where blog_id = '$blog_id' ";
$comment_cnt = sql_fetch($sql);

$sql = "update {$gb4[blog_table]} set {$count_field} = '$post_cnt[cnt]' ,comment_count = '$comment_count[cnt]' ,trackback_count = '$trackback_cnt[cnt]' ,last_update = '{$g4[time_ymdhis]}' where mb_id='{$blog_mb_id}'";
sql_query($sql);

$sql = " select count(*) as cnt from $gb4[trackback_table] where blog_id = '$blog_id' ";
$trackback_cnt = sql_fetch($sql);

// ���� ������ �����ѵ�.
$monthly = substr($post[post_date],0,7);
sql_query("update {$gb4[monthly_table]} set {$count_field} = {$count_field} - 1 where blog_id = '{$current[id]}' and monthly = '{$monthly}'");


// ���� ���������� ������ ��� �ٽ� ���� �������� �̵�
if ($me)
    goto_url ("adm_post_list.php?mb_id={$blog_mb_id}&page={$page}&cate={$cate}");

// ����ȭ�鿡�� ������ ��� �ٽ� ����ȭ������ �̵�
else {
    // �����ڰ� �����ϴ� ��� �������� ��α׷� �� ���ư��� �Խ����� ��α׷� ���� ����
    goto_url (get_blog_url($blog_mb_id));
}
?>