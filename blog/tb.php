<?
//
// ���α� �� �޴� ������
//
define("_GNUBOARD_", TRUE);

include_once("./_common.php");


// ������ write_log() �� ��´�.
include_once("$g4[path]/lib/etc.lib.php");
//write_log("$g4[path]/lib/log/aaa", 1);

$msg = "";

//phpinfo(); exit;

//preg_match("/tb\.php\/([^\/]+)\/([^\/]+)$/", $_SERVER[PHP_SELF], $matches);
//$bo_table = $matches[1];
//$wr_id = $matches[2];

$arr = explode("/", $_SERVER[PATH_INFO]);
$mb_id = $arr[1];
$id = $arr[2];
$to_token = $arr[3];

// mb_id ��α��� �⺻������ �ε��Ѵ�.
$current = sql_fetch("select * from {$gb4['blog_table']} where mb_id='{$mb_id}'");

if( empty($current) )
    $msg = '�������� �ʴ� ��α� �Դϴ�.';

if( !$current['use_comment'] )
    $msg = '�� ��α״� ���α��� ������� �ʾҽ��ϴ�.';

$res = sql_fetch("select id from {$gb4['post_table']} where blog_id='{$current['id']}' and id={$id}");

// ���� ��α� �ּ�
$current['blog_url'] = get_blog_url($current['mb_id']);

// id�� ���ų� ���α����� �Ѿ�°� �ƴ϶��
if (!$res['id'] || !($_POST[title] && $_POST[excerpt] && $_POST[url] && $_POST[blog_name]) ) {
    goto_url("../../../".$gb4[url]);
    exit;
}


$index = array('blog_name', 'title', 'url', 'excerpt');

// utf-8 �� euc-kr �� ��ȯ
if( strtoupper($g4['charset']) != 'UTF-8' ) {
    for($i=0; $i<count($index); $i++) {
        if( is_utf8($_POST[$index[$i]]) )
            $_POST[$index[$i]] = convert_charset('UTF-8', 'CP949', $_POST[$index[$i]]);
    }
}

$title   = $_POST[title];
$excerpt = $_POST[excerpt];

if (strlen($title) > 255)   $title   = cut_str($title, 255);
if (strlen($excerpt) > 255) $excerpt = cut_str($excerpt, 255);

// �ι��� INSERT �Ǵ°��� ���� ����
if ($_POST[title]) {

    $res = sql_fetch("select use_trackback from {$gb4['post_table']} where blog_id='{$current['id']}' and id='{$id}'");

    if( !$res['use_trackback'] ) 
        $msg = "���α� ����� ������ ���Դϴ�.";

    // ��ū�˻�
    if (isset($g4['token_time']) == false)
        $g4['token_time'] = 3; 

    $sql = " delete from $g4[token_table] 
              where to_datetime < '".date("Y-m-d", $g4[server_time] - 86400 * $g4['token_time'])."' ";
    sql_query($sql);

    $sql = " select to_token from $g4[token_table]
              where to_token = '$to_token' ";
    $row = sql_fetch($sql);
    if ($row[to_token] && $to_token) {
        // �ι� �̻� ���α��� ������ ���ϵ��� �ϱ� ���Ͽ� ��ū�� �����Ѵ�
        sql_query(" delete from $g4[token_table] where to_token = '$to_token' ");
    } else {
        $msg = "���α� �ּҰ� �ùٸ��� �ʽ��ϴ�. (��ū ��ȿ�ð� ��� ��)";
    }



    $url = str_replace('http://','',$_POST['url']);

    if (!$msg) {
        $sql = "insert into 
                    {$gb4['trackback_table']}
                set
                    blog_id = '{$current['id']}'
                    ,post_id = '{$id}'
                    ,writer_name = '{$_POST['blog_name']}'
                    ,writer_url = '{$_POST['url']}'
                    ,writer_subject = '{$title}'
                    ,writer_content = '{$excerpt}'
                    ,writer_ip = '{$_SERVER['REMOTE_ADDR']}'
                    ,referer = '{$_SERVER['HTTP_REFERER']}'
                    ,regdate = '{$g4['time_ymdhis']}' ";
        $res = sql_query($sql, FALSE);
        if ($res) {
            $trackback_id = mysql_insert_id();
            sql_query("update {$gb4['blog_table']} set trackback_count = trackback_count + 1 where id='{$current['id']}'");
            sql_query("update {$gb4['post_table']} set trackback_count = trackback_count + 1 where blog_id='{$current['id']}' and id='{$id}'");
        } else 
            $msg = "{$gb4['post_table']} TABLE INSERT ����";
    }

    //write_log("$g4[path]/data/log/aaa", $msg);

    if ($msg) // ������(����)
    { 
        echo "<?xml version=\"1.0\" encoding=\"$g4[charset]\"?>\n";
        echo "<response>\n";
        echo "<error>1</error>\n";
        echo "<message>$msg</message>\n";
        echo "</response>\n";
        exit;
    } 
}
echo "<?xml version=\"1.0\" encoding=\"$g4[charset]\"?>\n";
echo "<response>\n";
echo "<error>0</error>\n";
echo "</response>\n";


?>