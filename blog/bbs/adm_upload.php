<?
include_once("./_common.php");
include_once("$g4[path]/lib/etc.lib.php");

// ���ǰ����� ���ǰ˻�
// ���ǰ����� ���� ���� ���(FF) ���� �������� ���̵� �� ������ �� ����
if( $current['mb_id'] != $member['mb_id'] ) {
    $sql = "select * from {$g4['login_table']} where mb_id='$mb_id' and lo_ip='{$_SERVER['REMOTE_ADDR']}'";
    $res = sql_fetch($sql);
    if( empty($res) ) exit;
}

if( !$id ) $id = 0;

// ���丮�� ���ٸ� �����մϴ�. (�۹̼ǵ� �����ϱ���.)
@mkdir($current['file_path'], 0707);
@chmod($current['file_path'], 0707);

$upload     = array();
$tmp_file   = $_FILES['file']['tmp_name'];
$filename   = $_FILES['file']['name'];
$filesize   = $_FILES['file']['size'];

if (is_uploaded_file($tmp_file)) 
{
    $filename = revision_charset($filename);

    $upload['source'] = $filename;
    $upload['filesize'] = $filesize;

    // �Ʒ��� ���ڿ��� �� ������ -x �� �ٿ��� ����θ� �˴��� ������ ���� ���ϵ��� ��
    $filename = preg_replace("/\.(php|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $filename);

    // ���̻縦 ���� ���ϸ�
    $upload['file'] = abs(ip2long($_SERVER['REMOTE_ADDR'])).'_'.substr(md5(uniqid($g4['server_time'])),0,8).'_'.str_replace('%', '', urlencode($filename)); 

    $dest_file = $current['file_path'] .'/'. $upload['file'];

    // ���ε尡 �ȵȴٸ� �����޼��� ����ϰ� �׾�����ϴ�.
    $error_code = move_uploaded_file($tmp_file, $dest_file) or die($_FILES['file']['error']);

    // �ö� ������ �۹̼��� �����մϴ�.
    chmod($dest_file, 0606);

    $res = sql_fetch("select max(file_num) as fn from {$gb4['file_table']} where blog_id='{$current['id']}' and post_id='{$id}'");
    $file_num = $res['fn']+1;

    $sql = " insert into {$gb4['file_table']}
                set blog_id = '{$current['id']}',
                    post_id = '{$id}',
                    file_num = '{$file_num}',
                    file_size = '{$upload['filesize']}',
                    save_name = '{$upload['file']}',
                    real_name = '{$upload['source']}',
                    file_datetime = '{$g4['time_ymdhis']}'";
    sql_query($sql);
}

$max_size = $current['total_file_size']+$upload['filesize'];

// ��α� �ѿ뷮 update
if( $current['total_file_size'] != $max_size ) {
    sql_query("update {$gb4['blog_table']} set total_file_size = '{$max_size}' where id='{$current['id']}'");
}
?>