<?
include_once("./_common.php");

// �Խ��� ������ �°� �Դ��� Ȯ��
$bo_table = $_POST[bo_table];
$wr_id = $_POST[wr_id];

if (!$bo_table || !$wr_id)
    die('101');

// ��ȸ���� �� �� ���� ���
if (!$is_member || !$member['mb_id'])
    die('102');

// ��αװ� �ִ��� Ȯ��
$sql = " select * from $gb4[blog_table] where mb_id = '$member[mb_id]' ";
$blog = sql_fetch($sql);
if (!$blog)
    die('103');

// �Խñ� ������ ��������
$tmp_write_table = $g4['write_prefix'] . $bo_table; // �Խ��� ���̺� ��ü�̸�
$sql = " select * from $tmp_write_table where wr_id= '$wr_id' ";
$result = sql_fetch($sql);
if (!$result)
    die('104');

// ��ȸ���� ���̰ų� �ڽ��� ���� �ƴϸ� ����������
if (!$result[mb_id] || $member[mb_id] !== $result[mb_id])
    die('105');

// �̹� ��α׷� �̵��� ���̸�, ���� ����������
$sql = " select id, count(*) as cnt from $gb4[post_table] where blog_id='$blog[id]' and bo_table='$bo_table' and wr_id='$wr_id' ";
$dup = sql_fetch($sql);
if ($dup['cnt'])
    die("106,$dup[id]");

// �����ʹ� bbs/adm_write_update.php�� �ڵ带 �����ؼ� �����ؾ� -------------

    // ��α� ������� �Խñ� ����Ϸ� �ϴϱ� �򰥷���, �ű�� ��¥�� ������
    $post_date = $g4[time_ymdhis];

    // ��α׷� �ű�� ���� ������ ������. ����
    $secret = 1;

    if ($secret == 0) 
        $count_field = 'secret_count'; 
    else 
        $count_field = 'post_count';

    // ���� ����Ѵ�.
    $sql = "insert into
                {$gb4[post_table]}
            set
                blog_id             = '{$blog[id]}'
                ,category_id        = '0'
                ,division_id        = '0'
                ,title              = ''
                ,content            = ''
                ,trackback_url      = ''
                ,post_date          = '{$post_date}'
                ,secret             = '$secret'
                ,use_comment        = '1'
                ,use_trackback      = '1'
                ,use_rss            = '1'
                ,use_ccl_writer     = '1'
                ,use_ccl_commecial  = '1'
                ,use_ccl_modify     = '1'
                ,use_ccl_allow      = '0'
                ,real_date          = '{$g4[time_ymdhis]}'";
    sql_query($sql);

    // ��� ����� �� ���� ��ȣ�� ��Ÿ���.
    $id = mysql_insert_id();

    // �ش� ��α� ���� ���̺� �� ���� ���� �� ������ ������Ʈ �ð��� ����� �����Ѵ�.
    $sql = "update {$gb4[blog_table]} set {$count_field} = {$count_field} + 1 ,last_update = '{$g4[time_ymdhis]}' where mb_id='{$member[mb_id]}'";
    sql_query($sql);

    // ���� �� ������ ������Ų��.
    $monthly = substr($post_date,0,7);

    $res = sql_fetch("select * from {$gb4[monthly_table]} where blog_id='{$blog[id]}' and monthly='$monthly'");

    if (empty($res))
        sql_query("insert {$gb4[monthly_table]} set blog_id='{$blog[id]}', monthly='{$monthly}', {$count_field}=1");
    else
        sql_query("update {$gb4[monthly_table]} set {$count_field} = {$count_field} + 1 where blog_id='{$blog[id]}' and monthly='{$monthly}'");

    // ���ε� ����
    //sql_query("update {$gb4[file_table]} set post_id='{$id}' where blog_id='{$current[id]}' and post_id='0'");
    $sql = " select * from {$g4[board_file_table]} where bo_table = '$bo_table' and wr_id = '$wr_id' ";
    $file = sql_query($sql);
    $i = 0;
    while ($file1 = sql_fetch_array($file)) {
        $sql = "  INSERT into $gb4[file_table]
                          set blog_id = '{$blog[id]}',
                              post_id = '$id',
                              file_num = '$i',
                              file_size = '{$file1[bf_filesize]}',
                              save_name = '{$file1[bf_file]}',
                              real_name = '{$file1[bf_source]}',
                              bf_width = '{$file1[bf_width]}',
                              bf_height = '{$file1[bf_height]}',
                              bf_type = '{$file1[bf_type]}',
                              download_count = 0,
                              file_datetime = '$g4[time_ymdhis]' ";
        sql_query($sql);

        // �̹����� ���ϵ� �Ű����¡.
        $tmp_file = "$g4[path]/data/file/$bo_table/" . $file1[bf_file];
        $dest_file = "$g4[path]/data/blog/file/$blog[mb_id]/" . $file1[bf_file];
        copy($tmp_file, $dest_file) or die("107");
        $i++;
    }

    // �򰥸��� �ʰ� adm_write_update.php�� �ڵ尡 �ƴ� �κ��� ������ �߰� �մϴ�. ---
    sql_query(" update {$gb4[post_table]} set bo_table='$bo_table', wr_id='{$wr_id}' where id = '$id' ");

    // ���ٰ� �ٽ� ������ Ư�����ڵ��� ������ �Ǵϱ� ������Ʈ��.
    sql_query(" update {$gb4[post_table]} as t1, ( select * from $tmp_write_table where wr_id='$wr_id') as t2 set t1.title=t2.wr_subject, t1.content=t2.wr_content where t1.id = '$id' ");

// �� ��� �Ǿ��ٰ� �޽����� ��~
die("000");

?>