<?
include_once("./_common.php");

$sql = "select * from {$gb4['file_table']} where blog_id = '{$current['id']}' and post_id = '{$post_id}' and file_num = '{$file_num}' ";
$file = sql_fetch($sql);

$sql = "select * from {$gb4['post_table']} where blog_id = '{$current['id']}' and id = '{$file['post_id']}'";
$post = sql_fetch($sql);

if( empty($file['real_name']) || empty($post['title']) )
    alert("���� ������ �������� �ʽ��ϴ�.");

if( $post['secret']==0 && $member['mb_id']!=$current['mb_id'] ) { 
    alert("�ٿ�ε� ������ �����ϴ�.");
}

sql_query("update {$gb4['file_table']} set download_count = download_count + 1 where blog_id = '{$current['id']}' and post_id = '{$post_id}' and file_num = '{$file_num}' ");

$g4[title] = "$group[gr_subject] > $board[bo_subject] > " . conv_subject($write[wr_subject], 255) . " > �ٿ�ε�";

$filepath = "{$current['file_path']}/{$file['save_name']}";
$filepath = addslashes($filepath);
if (preg_match("/^utf/i", $g4[charset]))
    $original = urlencode($file['real_name']);
else
    $original = $file['real_name'];

if (file_exists($filepath)) {
    if(eregi("msie", $_SERVER[HTTP_USER_AGENT]) && eregi("5\.5", $_SERVER[HTTP_USER_AGENT])) {
        header("content-type: doesn/matter");
        header("content-length: ".filesize("$filepath"));
        header("content-disposition: attachment; filename=\"$original\"");
        header("content-transfer-encoding: binary");
    } else {
        header("content-type: file/unknown");
        header("content-length: ".filesize("$filepath"));
        header("content-disposition: attachment; filename=\"$original\"");
        header("content-description: php generated data");
    }
    header("pragma: no-cache");
    header("expires: 0");
    flush();

    if (is_file("$filepath")) {
        $fp = fopen("$filepath", "rb");

        // 4.00 ��ü
        // �������ϸ� ���̷��� print �� echo �Ǵ� while ���� �̿��� ������ٴ� �̹����...
        //if (!fpassthru($fp)) {
        //    fclose($fp);
        //}

        while(!feof($fp)) { 
            echo fread($fp, 100*1024); 
            flush(); 
        } 
        fclose ($fp); 
        flush();
    } else {
        alert("�ش� �����̳� ��ΰ� �������� �ʽ��ϴ�.");
    }

} else {
    alert("������ ã�� �� �����ϴ�.");
}

?>
