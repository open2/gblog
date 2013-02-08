<?
include_once("./_common.php");

// 게시판 정보가 맞게 왔는지 확인
$bo_table = $_POST[bo_table];
$wr_id = $_POST[wr_id];

if (!$bo_table || !$wr_id)
    die('101');

// 비회원은 쓸 수 없는 기능
if (!$is_member || !$member['mb_id'])
    die('102');

// 블로그가 있는지 확인
$sql = " select * from $gb4[blog_table] where mb_id = '$member[mb_id]' ";
$blog = sql_fetch($sql);
if (!$blog)
    die('103');

// 게시글 정보를 가져오고
$tmp_write_table = $g4['write_prefix'] . $bo_table; // 게시판 테이블 전체이름
$sql = " select * from $tmp_write_table where wr_id= '$wr_id' ";
$result = sql_fetch($sql);
if (!$result)
    die('104');

// 비회원의 글이거나 자신의 글이 아니면 돌려보내고
if (!$result[mb_id] || $member[mb_id] !== $result[mb_id])
    die('105');

// 이미 블로그로 이동한 글이면, 역시 돌려보내고
$sql = " select id, count(*) as cnt from $gb4[post_table] where blog_id='$blog[id]' and bo_table='$bo_table' and wr_id='$wr_id' ";
$dup = sql_fetch($sql);
if ($dup['cnt'])
    die("106,$dup[id]");

// 요기부터는 bbs/adm_write_update.php의 코드를 참조해서 수정해씀 -------------

    // 블로그 등록일을 게시글 등록일로 하니까 헷갈려서, 옮기는 날짜로 변경함
    $post_date = $g4[time_ymdhis];

    // 블로그로 옮기는 것은 무조건 공개로. ㅋㅋ
    $secret = 1;

    if ($secret == 0) 
        $count_field = 'secret_count'; 
    else 
        $count_field = 'post_count';

    // 글을 등록한다.
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

    // 방금 등록한 글 고유 번호를 센타깐다.
    $id = mysql_insert_id();

    // 해당 블로그 정보 테이블에 글 갯수 증가 및 마지막 업데이트 시간을 현재로 변경한다.
    $sql = "update {$gb4[blog_table]} set {$count_field} = {$count_field} + 1 ,last_update = '{$g4[time_ymdhis]}' where mb_id='{$member[mb_id]}'";
    sql_query($sql);

    // 월별 글 갯수를 증가시킨다.
    $monthly = substr($post_date,0,7);

    $res = sql_fetch("select * from {$gb4[monthly_table]} where blog_id='{$blog[id]}' and monthly='$monthly'");

    if (empty($res))
        sql_query("insert {$gb4[monthly_table]} set blog_id='{$blog[id]}', monthly='{$monthly}', {$count_field}=1");
    else
        sql_query("update {$gb4[monthly_table]} set {$count_field} = {$count_field} + 1 where blog_id='{$blog[id]}' and monthly='{$monthly}'");

    // 업로드 파일
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

        // 이번에는 파일도 옮겨줘야징.
        $tmp_file = "$g4[path]/data/file/$bo_table/" . $file1[bf_file];
        $dest_file = "$g4[path]/data/blog/file/$blog[mb_id]/" . $file1[bf_file];
        copy($tmp_file, $dest_file) or die("107");
        $i++;
    }

    // 헷갈리지 않게 adm_write_update.php의 코드가 아닌 부분은 요기부터 추가 합니다. ---
    sql_query(" update {$gb4[post_table]} set bo_table='$bo_table', wr_id='{$wr_id}' where id = '$id' ");

    // 뺏다가 다시 넣으면 특수문자들이 문제가 되니까 업데이트를.
    sql_query(" update {$gb4[post_table]} as t1, ( select * from $tmp_write_table where wr_id='$wr_id') as t2 set t1.title=t2.wr_subject, t1.content=t2.wr_content where t1.id = '$id' ");

// 잘 등록 되었다고 메시지를 팍~
die("000");

?>