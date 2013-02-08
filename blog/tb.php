<?
//
// 엮인글 핑 받는 페이지
//
define("_GNUBOARD_", TRUE);

include_once("./_common.php");


// 오류는 write_log() 로 잡는다.
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

// mb_id 블로그의 기본정보를 로드한다.
$current = sql_fetch("select * from {$gb4['blog_table']} where mb_id='{$mb_id}'");

if( empty($current) )
    $msg = '존재하지 않는 블로그 입니다.';

if( !$current['use_comment'] )
    $msg = '이 블로그는 엮인글을 허락하지 않았습니다.';

$res = sql_fetch("select id from {$gb4['post_table']} where blog_id='{$current['id']}' and id={$id}");

// 현재 블로그 주소
$current['blog_url'] = get_blog_url($current['mb_id']);

// id가 없거나 엮인글으로 넘어온게 아니라면
if (!$res['id'] || !($_POST[title] && $_POST[excerpt] && $_POST[url] && $_POST[blog_name]) ) {
    goto_url("../../../".$gb4[url]);
    exit;
}


$index = array('blog_name', 'title', 'url', 'excerpt');

// utf-8 을 euc-kr 로 변환
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

// 두번씩 INSERT 되는것을 막기 위해
if ($_POST[title]) {

    $res = sql_fetch("select use_trackback from {$gb4['post_table']} where blog_id='{$current['id']}' and id='{$id}'");

    if( !$res['use_trackback'] ) 
        $msg = "엮인글 사용이 금지된 글입니다.";

    // 토큰검사
    if (isset($g4['token_time']) == false)
        $g4['token_time'] = 3; 

    $sql = " delete from $g4[token_table] 
              where to_datetime < '".date("Y-m-d", $g4[server_time] - 86400 * $g4['token_time'])."' ";
    sql_query($sql);

    $sql = " select to_token from $g4[token_table]
              where to_token = '$to_token' ";
    $row = sql_fetch($sql);
    if ($row[to_token] && $to_token) {
        // 두번 이상 엮인글을 보내지 못하도록 하기 위하여 토큰을 삭제한다
        sql_query(" delete from $g4[token_table] where to_token = '$to_token' ");
    } else {
        $msg = "엮인글 주소가 올바르지 않습니다. (토큰 유효시간 경과 등)";
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
            $msg = "{$gb4['post_table']} TABLE INSERT 오류";
    }

    //write_log("$g4[path]/data/log/aaa", $msg);

    if ($msg) // 비정상(오류)
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