<?
include_once("./_common.php");
include_once("$g4[path]/lib/trackback.lib.php");

if ($current[mb_id] != $member[mb_id])
    alert('�ڽ��� �۸� ���/������ �� �ֽ��ϴ�.');

$upload_max_filesize = ini_get('upload_max_filesize');

if (empty($_POST))
    alert("���� �Ǵ� �۳����� ũ�Ⱑ �������� ������ ���� �Ѿ� ������ �߻��Ͽ����ϴ�.\\n\\npost_max_size=".ini_get('post_max_size')." , upload_max_filesize=$upload_max_filesize\\n\\n�Խ��ǰ����� �Ǵ� ���������ڿ��� ���� �ٶ��ϴ�.");

// POST �� �Ѿ�� ���� ��ȿ�� �˻�
if (!trim($title))
    alert('������ �Է����ּ���.');

if (!trim($content))
    alert('������ �Է����ּ���.');

if (!strtotime($post_date) || strtotime($post_date) < 0)
    alert('���ۼ��Ͻ��� ������ �ùٸ��� �ʽ��ϴ�.');

// ���ۼ� �Ͻ� ���� �ɼ��� �������� ��� ����ð��� ����
if ($reload)
    $post_date = $g4[time_ymdhis];

// ���� �� ����
if ($m == 'u')
{
    // ������ ������ �����´�.
    $r = sql_fetch("select * from {$gb4[post_table]} where id='{$id}'");

    if ($r[secret] != $secret) {
        if ($secret == 0) {
            $count_field_prev = 'post_count'; 
            $count_field_next = 'secret_count'; 
        } else {
            $count_field_prev = 'secret_count';
            $count_field_next = 'post_count';
        }
    } else {
        if ($secret == 0) 
            $count_field = 'secret_count'; 
        else 
            $count_field = 'post_count';
    }

    // �������� �����Ѵ�.
    $sql = "update
                {$gb4[post_table]}
            set
                category_id         = '{$category_id}'
                ,division_id        = '{$division_id}'
                ,title              = '{$title}'
                ,content            = '{$content}'
                ,trackback_url      = '{$trackback_url}'
                ,post_date          = '{$post_date}'
                ,secret             = '{$secret}'
                ,use_comment        = '{$use_comment}'
                ,use_trackback      = '{$use_trackback}'
                ,use_rss            = '{$use_rss}'
                ,use_ccl_writer     = '{$use_ccl_writer}'
                ,use_ccl_commecial  = '{$use_ccl_commecial}'
                ,use_ccl_modify     = '{$use_ccl_modify}'
                ,use_ccl_allow      = '{$use_ccl_allow}'
            where id='{$id}'";
    sql_query($sql);

    // �� �������ΰ� ������� �ʾ��� ��
    if ($r[secret] == $secret) {

        // �з��� ����Ǿ��� ��� �ش� �з��� �� ī��Ʈ�� �����Ѵ�.
        if ($r[category_id] != $category_id) {
            sql_query("update {$gb4[category_table]} set {$count_field} = {$count_field} - 1 where id = '{$r[category_id]}'");
            sql_query("update {$gb4[category_table]} set {$count_field} = {$count_field} + 1 where id = '{$category_id}'");
        }

        // ������ �� ��¥�� ���� �����۰� �ٸ� �� ���� �� ������ �����Ѵ�.
        if (substr($post_date,0,7) != substr($r[post_date],0,7)) {

            // �������� ���� �� ������ �����Ѵ�.
            sql_query("update {$gb4[monthly_table]} set {$count_field} = {$count_field} - 1 where blog_id='{$current[id]}' and monthly='".substr($r[post_date],0,7)."'");

            // ������ �� ��¥�� ������ ���Ѵ�.
            $monthly = substr($post_date,0,7);

            // �ش� ���� �� ������ �����Ѵ�.
            $res = sql_fetch("select * from {$gb4[monthly_table]} where blog_id='{$current[id]}' and monthly='{$monthly}'");
            if (empty($res))
                sql_query("insert {$gb4[monthly_table]} set blog_id='{$current[id]}', monthly='{$monthly}', {$count_field}=1");
            else
                sql_query("update {$gb4[monthly_table]} set {$count_field} = {$count_field} + 1 where blog_id='{$current[id]}' and monthly='{$monthly}'");
        }

    // �� �������ΰ� ����Ǿ��� ��
    } else {

        // ��α� �������� �� ī��Ʈ ����
        sql_query("update {$gb4[blog_table]} set {$count_field_prev} = {$count_field_prev} - 1, {$count_field_next} = {$count_field_next} + 1 where id = '{$current[id]}'");

        // �ش� �з��� �� ī��Ʈ�� �����Ѵ�.
        sql_query("update {$gb4[category_table]} set {$count_field_prev} = {$count_field_prev} - 1 where id = '{$r[category_id]}'");
        sql_query("update {$gb4[category_table]} set {$count_field_next} = {$count_field_next} + 1 where id = '{$category_id}'");

        // �������� ���� �� ������ �����Ѵ�.
        sql_query("update {$gb4[monthly_table]} set {$count_field_prev} = {$count_field_prev} - 1 where blog_id='{$current[id]}' and monthly='".substr($r[post_date],0,7)."'");

        // ������ �� ��¥�� ������ ���Ѵ�.
        $monthly = substr($post_date,0,7);

        // �ش� ���� �� ������ �����Ѵ�.
        $res = sql_fetch("select * from {$gb4[monthly_table]} where blog_id='{$current[id]}' and monthly='{$monthly}'");
        if (empty($res))
            sql_query("insert {$gb4[monthly_table]} set blog_id='{$current[id]}', monthly='{$monthly}', {$count_field_next}=1");
        else
            sql_query("update {$gb4[monthly_table]} set {$count_field_next} = {$count_field_next} + 1 where blog_id='{$current[id]}' and monthly='{$monthly}'");
    }

    // �±� ��� ������ �����Ѵ�.
    $qry = sql_query("select * from {$gb4[taglog_table]} where post_id='{$id}'");
    while($res = sql_fetch_array($qry)) 
        sql_query("update {$gb4[tag_table]} set tag_count = tag_count - 1 where id='{$res[tag_id]}'");

    // �±׸� ��~~~ �����.
    sql_query("delete from {$gb4[taglog_table]} where post_id='{$id}'");

    // �±׸� �ٽ�-_- ���δ�.
    tag_add($id, $tag);

    // �ش� ��α� ���� ���̺� ������ ������Ʈ �ð��� ����� �����Ѵ�.
    sql_query("update {$gb4[blog_table]} set last_update = '{$g4[time_ymdhis]}' where mb_id='{$member[mb_id]}'");
}

// ���ο� �� ���
else
{
    if ($secret == 0) 
        $count_field = 'secret_count'; 
    else 
        $count_field = 'post_count';

    // ���� ����Ѵ�.
    $sql = "insert into
                {$gb4[post_table]}
            set
                blog_id             = '{$current[id]}'
                ,category_id        = '{$category_id}'
                ,division_id        = '{$division_id}'
                ,title              = '{$title}'
                ,content            = '{$content}'
                ,trackback_url      = '{$trackback_url}'
                ,post_date          = '{$post_date}'
                ,secret             = '{$secret}'
                ,use_comment        = '{$use_comment}'
                ,use_trackback      = '{$use_trackback}'
                ,use_rss            = '{$use_rss}'
                ,use_ccl_writer     = '{$use_ccl_writer}'
                ,use_ccl_commecial  = '{$use_ccl_commecial}'
                ,use_ccl_modify     = '{$use_ccl_modify}'
                ,use_ccl_allow      = '{$use_ccl_allow}'
                ,real_date          = '{$g4[time_ymdhis]}'";
    sql_query($sql);

    // ��� ����� �� ���� ��ȣ�� ��Ÿ���.
    $id = mysql_insert_id();

    // �ش� �з��� �� ī��Ʈ�� ������Ų��.
    if ($category_id)
        sql_query("update {$gb4[category_table]} set {$count_field} = {$count_field} + 1 where id = '{$category_id}'");

    // �±׸� ���δ�.
    tag_add($id, $tag);

    // �ش� ��α� ���� ���̺� �� ���� ���� �� ������ ������Ʈ �ð��� ����� �����Ѵ�.
    $sql = "update {$gb4[blog_table]} set {$count_field} = {$count_field} + 1 ,last_update = '{$g4[time_ymdhis]}' where mb_id='{$member[mb_id]}'";
    sql_query($sql);

    // ���� �� ������ ������Ų��.
    $monthly = substr($post_date,0,7);

    $res = sql_fetch("select * from {$gb4[monthly_table]} where blog_id='{$current[id]}' and monthly='$monthly'");

    if (empty($res))
        sql_query("insert {$gb4[monthly_table]} set blog_id='{$current[id]}', monthly='{$monthly}', {$count_field}=1");
    else
        sql_query("update {$gb4[monthly_table]} set {$count_field} = {$count_field} + 1 where blog_id='{$current[id]}' and monthly='{$monthly}'");

    // ���ε� ����
    //sql_query("update {$gb4[file_table]} set post_id='{$id}' where blog_id='{$current[id]}' and post_id='0'");
}

// ���ε�� ���� ���뿡�� ���� ū ��ȣ�� ��� �Ųٷ� Ȯ���� ���鼭
// ���� ������ ���ٸ� ���̺��� ������ �����մϴ�.
/*
$row = sql_fetch(" select max(file_num) as max_file_num from {$gb4[file_table]} where blog_id='{$current[id]}' and post_id='{$id}' ");
for ($i=(int)$row[max_file_num]; $i>=0; $i--) 
{
    $row2 = sql_fetch(" select save_name from {$gb4[file_table]} where blog_id='{$current[id]}' and post_id='{$id}' and file_num = '{$i}' ");

    // ������ �ִٸ� �����ϴ�.
    if ($row2[save_name]) break;

    // �׷��� �ʴٸ� ������ �����մϴ�.
    sql_query(" delete from {$gb4[file_table]} where blog_id='{$current[id]}' and post_id='{$id}' and file_num = '{$i}' ");
}
*/
//------------------------------------------------------------------------------

// ���丮�� ���ٸ� �����մϴ�. (�۹̼ǵ� �����ϱ���.)
@mkdir("$g4[path]/data/blog/file/$current[mb_id]", 0707);
@chmod("$g4[path]/data/blog/file/$current[mb_id]", 0707);

//------------------------------------------------------------------------------
// �Ʒ��� �ڵ�� bbs/write_update.php�� ���Ͼ��ε� �ڵ�� ���� �����ϴ� (������ ���/DB �κ� ���� �����ϰ�).

// "���ͳݿɼ� > ���� > ��������Ǽ��� > ��ũ���� > Action ��ũ���� > ��� �� ��" �� ����� ���� ó��
// �� �ɼ��� ��� �� ������ ������ ��� � ��ũ��Ʈ�� ���� ���� �ʽ��ϴ�.
//if (!$_POST[wr_content]) die ("������ �Է��Ͽ� �ֽʽÿ�.");

$chars_array = array_merge(range(0,9), range('a','z'), range('A','Z'));
//print_r2($chars_array); exit;

// ���� ���� ���ε�
$file_upload_msg = "";
$upload = array();

for ($i=0; $i<count($_FILES[bf_file][name]); $i++) 
{
    // ������ üũ�� �Ǿ��ִٸ� ������ �����մϴ�.
    if ($_POST[bf_file_del][$i]) 
    {
        $upload[$i][del_check] = true;
        $row = sql_fetch(" select save_name from $gb4[file_table] where blog_id = '$current[id]' and post_id = '$id' and file_num = '$i' ");
        @unlink("$g4[path]/data/blog/file/$current[mb_id]/$row[save_name]");
    }
    else
        $upload[$i][del_check] = false;

    $tmp_file  = $_FILES[bf_file][tmp_name][$i];
    $filename  = $_FILES[bf_file][name][$i];
    $filesize  = $_FILES[bf_file][size][$i];

    // ������ ������ ������ ū������ ���ε� �Ѵٸ�
    if ($filename)
    {
        if ($_FILES[bf_file][error][$i] == 1)
        {
            $file_upload_msg .= "\'{$filename}\' ������ �뷮�� ������ ����($upload_max_filesize)�� ������ ũ�Ƿ� ���ε� �� �� �����ϴ�.\\n";
            continue;
        }
        else if ($_FILES[bf_file][error][$i] != 0)
        {
            $file_upload_msg .= "\'{$filename}\' ������ ���������� ���ε� ���� �ʾҽ��ϴ�.\\n";
            continue;
        }
    }

    if (is_uploaded_file($tmp_file)) 
    {
        // �����ڰ� �ƴϸ鼭 ������ ���ε� ������� ũ�ٸ� �ǳʶ�
        if (!$is_admin && $filesize > $gb4[upload_one_file_size]) 
        {
            $file_upload_msg .= "\'{$filename}\' ������ �뷮(".number_format($filesize)." ����Ʈ)�� �Խ��ǿ� ����(".number_format($gb4[upload_one_file_size])." ����Ʈ)�� ������ ũ�Ƿ� ���ε� ���� �ʽ��ϴ�.\\n";
            continue;
        }

        //=================================================================\
        // 090714
        // �̹����� �÷��� ���Ͽ� �Ǽ��ڵ带 �ɾ� ���ε� �ϴ� ��츦 ����
        // �����޼����� ������� �ʴ´�.
        //-----------------------------------------------------------------
        $timg = @getimagesize($tmp_file);
        // image type
        if ( preg_match("/\.($config[cf_image_extension])$/i", $filename) ||
             preg_match("/\.($config[cf_flash_extension])$/i", $filename) ) 
        {
            if ($timg[2] < 1 || $timg[2] > 16)
            {
                //$file_upload_msg .= "\'{$filename}\' ������ �̹����� �÷��� ������ �ƴմϴ�.\\n";
                continue;
            }
        }
        //=================================================================

        $upload[$i][image] = $timg;

        // 4.00.11 - �۴亯���� ���� ���ε�� ������ ������ �����Ǵ� ������ ����
        if ($m == 'u')
        {
            // �����ϴ� ������ �ִٸ� �����մϴ�.
            $row = sql_fetch(" select save_name from $gb4[file_table] where blog_id = '$current[id]' and post_id = '$id' and file_num = '$i' ");
            @unlink("$g4[path]/data/blog/file/$current[mb_id]/$row[save_name]");
        }

        // ���α׷� ���� ���ϸ�
        $upload[$i][source] = $filename;
        $upload[$i][filesize] = $filesize;

        // �Ʒ��� ���ڿ��� �� ������ -x �� �ٿ��� ����θ� �˴��� ������ ���� ���ϵ��� ��
        $filename = preg_replace("/\.(php|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $filename);

        // ���̻縦 ���� ���ϸ�
        //$upload[$i][file] = abs(ip2long($_SERVER[REMOTE_ADDR])).'_'.substr(md5(uniqid($g4[server_time])),0,8).'_'.urlencode($filename);
        // �޺��µ��� ���� : �ѱ������� urlencode($filename) ó���� �Ұ�� '%'�� �ٿ��ְ� �Ǵµ� '%'ǥ�ô� �̵���÷��̾ �ν��� ���ϱ� ������ ����� �ȵ˴ϴ�. �׷��� ������ ���ϸ��� '%'�κ��� ���ָ� �ذ�˴ϴ�. 
        //$upload[$i][file] = abs(ip2long($_SERVER[REMOTE_ADDR])).'_'.substr(md5(uniqid($g4[server_time])),0,8).'_'.str_replace('%', '', urlencode($filename)); 
        shuffle($chars_array);
        $shuffle = implode("", $chars_array);
        // �Ҵ��� - ip�ּҸ� �״�� �����ϴ� ���̶� timestamp�� ����
        //$upload[$i][file] = abs(ip2long($_SERVER[REMOTE_ADDR])).'_'.substr($shuffle,0,8).'_'.str_replace('%', '', urlencode($filename)); 
        // ÷������ ÷�ν� ÷�����ϸ� ������ ���ԵǾ� ������ �Ϻ� PC���� ������ �ʰų� �ٿ�ε� ���� �ʴ� ������ �ֽ��ϴ�. (����� �� 090925)
        //$upload[$i][file] = time().'_'.substr($shuffle,0,8).'_'.str_replace('%', '', urlencode($filename));
        $upload[$i][file] = time().'_'.substr($shuffle,0,8).'_'.str_replace('%', '', urlencode(str_replace(' ', '_', $filename)));

        $dest_file = "$g4[path]/data/blog/file/$current[mb_id]/" . $upload[$i][file];

        // ���ε尡 �ȵȴٸ� �����޼��� ����ϰ� �׾�����ϴ�.
        $error_code = move_uploaded_file($tmp_file, $dest_file) or die($_FILES[bf_file][error][$i]);

        // �ö� ������ �۹̼��� �����մϴ�.
        chmod($dest_file, 0606);

        //$upload[$i][image] = @getimagesize($dest_file);

    }
}

//------------------------------------------------------------------------------
// ���� ���� ���ε�
// ���߿� ���̺� �����ϴ� ������ $wr_id ���� �����ؾ� �ϱ� �����Դϴ�.
for ($i=0; $i<count($upload); $i++) 
{
    $row = sql_fetch(" select count(*) as cnt from $gb4[file_table] where blog_id = '$current[id]' and post_id = '$id' and file_num = '$i' ");

    if ($row[cnt]) 
    {
        // ������ üũ�� �ְų� ������ �ִٸ� ������Ʈ�� �մϴ�.
        // �׷��� �ʴٸ� ���븸 ������Ʈ �մϴ�.
        if ($upload[$i][del_check] || $upload[$i][file]) 
        {
            $sql = " update $gb4[file_table]
                        set real_name = '{$upload[$i][source]}',
                            save_name = '{$upload[$i][file]}',
                            file_size = '{$upload[$i][filesize]}',
                            bf_width = '{$upload[$i][image][0]}',
                            bf_height = '{$upload[$i][image][1]}',
                            bf_type = '{$upload[$i][image][2]}',
                            file_datetime = '$g4[time_ymdhis]'
                      where blog_id = '$current[id]'
                        and post_id = '$id'
                        and file_num = '$i' ";
            sql_query($sql);
        } 
    } 
    else 
    {
        $sql = " insert into $gb4[file_table]
                    set blog_id = '$current[id]',
                        post_id = '$id',
                        file_num = '$i',
                        file_size = '{$upload[$i][filesize]}',
                        save_name = '{$upload[$i][file]}',
                        real_name = '{$upload[$i][source]}',
                        bf_width = '{$upload[$i][image][0]}',
                        bf_height = '{$upload[$i][image][1]}',
                        bf_type = '{$upload[$i][image][2]}',
                        download_count = 0,
                        file_datetime = '$g4[time_ymdhis]' ";
        sql_query($sql);
    }
}

// ���ε�� ���� ���뿡�� ���� ū ��ȣ�� ��� �Ųٷ� Ȯ���� ���鼭
// ���� ������ ���ٸ� ���̺��� ������ �����մϴ�.
$row = sql_fetch(" select max(file_num) as max_bf_no from $gb4[file_table] where blog_id = '$current[id]' and post_id = '$id' ");

for ($i=(int)$row[max_bf_no]; $i>=0; $i--) 
{
    $row2 = sql_fetch(" select save_name from $gb4[file_table] where blog_id = '$current[id]' and post_id = '$id' and file_num = '$i' ");

    // ������ �ִٸ� �����ϴ�.
    if ($row2[save_name]) break;

    // �׷��� �ʴٸ� ������ �����մϴ�.
    sql_query(" delete from $gb4[file_table] where blog_id = '$current[id]' and blog_id = '$id' and file_num = '$i' ");
}
//------------------------------------------------------------------------------

// Ʈ���� ���� ������.
if (($m != "m" && $trackback_url) || ($m=="m" && $trackback_url && $ping)) 
{
    $url = get_full_url($current[blog_url]);
    $msg = send_trackback($trackback_url, $url, $title, $current[blog_name], $content);
    if ($msg) 
        echo "<script language='JavaScript'>alert('$msg $trackback_url');</script>";
}

// ���� ���������� ������ ��� �ٽ� ���� �������� �̵�
if ($me)
    goto_url ("adm_post_list.php?mb_id=$member[mb_id]&page=$page&cate=$cate");

// ����ȭ�鿡�� ������ ��� �ٽ� ����ȭ������ �̵�
else 
    goto_url (get_post_url($id));


?>