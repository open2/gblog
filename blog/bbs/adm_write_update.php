<?
include_once("./_common.php");
include_once("$g4[path]/lib/trackback.lib.php");

if ($current[mb_id] != $member[mb_id])
    alert('자신의 글만 등록/변경할 수 있습니다.');

$upload_max_filesize = ini_get('upload_max_filesize');

if (empty($_POST))
    alert("파일 또는 글내용의 크기가 서버에서 설정한 값을 넘어 오류가 발생하였습니다.\\n\\npost_max_size=".ini_get('post_max_size')." , upload_max_filesize=$upload_max_filesize\\n\\n게시판관리자 또는 서버관리자에게 문의 바랍니다.");

// POST 로 넘어온 변수 유효성 검사
if (!trim($title))
    alert('제목을 입력해주세요.');

if (!trim($content))
    alert('내용을 입력해주세요.');

if (!strtotime($post_date) || strtotime($post_date) < 0)
    alert('글작성일시의 형식이 올바르지 않습니다.');

// 글작성 일시 갱신 옵션을 선택했을 경우 현재시간을 저장
if ($reload)
    $post_date = $g4[time_ymdhis];

// 기존 글 수정
if ($m == 'u')
{
    // 원본글 정보를 가져온다.
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

    // 원본글을 수정한다.
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

    // 글 공개여부가 변경되지 않았을 때
    if ($r[secret] == $secret) {

        // 분류가 변경되었을 경우 해당 분류의 글 카운트를 조정한다.
        if ($r[category_id] != $category_id) {
            sql_query("update {$gb4[category_table]} set {$count_field} = {$count_field} - 1 where id = '{$r[category_id]}'");
            sql_query("update {$gb4[category_table]} set {$count_field} = {$count_field} + 1 where id = '{$category_id}'");
        }

        // 수정된 글 날짜의 월이 원본글과 다를 때 월별 글 갯수를 조정한다.
        if (substr($post_date,0,7) != substr($r[post_date],0,7)) {

            // 원본글의 월별 글 갯수를 감소한다.
            sql_query("update {$gb4[monthly_table]} set {$count_field} = {$count_field} - 1 where blog_id='{$current[id]}' and monthly='".substr($r[post_date],0,7)."'");

            // 수정된 글 날짜의 연월을 구한다.
            $monthly = substr($post_date,0,7);

            // 해당 월별 글 갯수를 증가한다.
            $res = sql_fetch("select * from {$gb4[monthly_table]} where blog_id='{$current[id]}' and monthly='{$monthly}'");
            if (empty($res))
                sql_query("insert {$gb4[monthly_table]} set blog_id='{$current[id]}', monthly='{$monthly}', {$count_field}=1");
            else
                sql_query("update {$gb4[monthly_table]} set {$count_field} = {$count_field} + 1 where blog_id='{$current[id]}' and monthly='{$monthly}'");
        }

    // 글 공개여부가 변경되었을 때
    } else {

        // 블로그 정보에서 글 카운트 조정
        sql_query("update {$gb4[blog_table]} set {$count_field_prev} = {$count_field_prev} - 1, {$count_field_next} = {$count_field_next} + 1 where id = '{$current[id]}'");

        // 해당 분류의 글 카운트를 조정한다.
        sql_query("update {$gb4[category_table]} set {$count_field_prev} = {$count_field_prev} - 1 where id = '{$r[category_id]}'");
        sql_query("update {$gb4[category_table]} set {$count_field_next} = {$count_field_next} + 1 where id = '{$category_id}'");

        // 원본글의 월별 글 갯수를 감소한다.
        sql_query("update {$gb4[monthly_table]} set {$count_field_prev} = {$count_field_prev} - 1 where blog_id='{$current[id]}' and monthly='".substr($r[post_date],0,7)."'");

        // 수정된 글 날짜의 연월을 구한다.
        $monthly = substr($post_date,0,7);

        // 해당 월별 글 갯수를 증가한다.
        $res = sql_fetch("select * from {$gb4[monthly_table]} where blog_id='{$current[id]}' and monthly='{$monthly}'");
        if (empty($res))
            sql_query("insert {$gb4[monthly_table]} set blog_id='{$current[id]}', monthly='{$monthly}', {$count_field_next}=1");
        else
            sql_query("update {$gb4[monthly_table]} set {$count_field_next} = {$count_field_next} + 1 where blog_id='{$current[id]}' and monthly='{$monthly}'");
    }

    // 태그 사용 갯수를 감소한다.
    $qry = sql_query("select * from {$gb4[taglog_table]} where post_id='{$id}'");
    while($res = sql_fetch_array($qry)) 
        sql_query("update {$gb4[tag_table]} set tag_count = tag_count - 1 where id='{$res[tag_id]}'");

    // 태그를 다~~~ 지운다.
    sql_query("delete from {$gb4[taglog_table]} where post_id='{$id}'");

    // 태그를 다시-_- 붙인다.
    tag_add($id, $tag);

    // 해당 블로그 정보 테이블에 마지막 업데이트 시간을 현재로 변경한다.
    sql_query("update {$gb4[blog_table]} set last_update = '{$g4[time_ymdhis]}' where mb_id='{$member[mb_id]}'");
}

// 새로운 글 등록
else
{
    if ($secret == 0) 
        $count_field = 'secret_count'; 
    else 
        $count_field = 'post_count';

    // 글을 등록한다.
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

    // 방금 등록한 글 고유 번호를 센타깐다.
    $id = mysql_insert_id();

    // 해당 분류의 글 카운트를 증가시킨돠.
    if ($category_id)
        sql_query("update {$gb4[category_table]} set {$count_field} = {$count_field} + 1 where id = '{$category_id}'");

    // 태그를 붙인다.
    tag_add($id, $tag);

    // 해당 블로그 정보 테이블에 글 갯수 증가 및 마지막 업데이트 시간을 현재로 변경한다.
    $sql = "update {$gb4[blog_table]} set {$count_field} = {$count_field} + 1 ,last_update = '{$g4[time_ymdhis]}' where mb_id='{$member[mb_id]}'";
    sql_query($sql);

    // 월별 글 갯수를 증가시킨다.
    $monthly = substr($post_date,0,7);

    $res = sql_fetch("select * from {$gb4[monthly_table]} where blog_id='{$current[id]}' and monthly='$monthly'");

    if (empty($res))
        sql_query("insert {$gb4[monthly_table]} set blog_id='{$current[id]}', monthly='{$monthly}', {$count_field}=1");
    else
        sql_query("update {$gb4[monthly_table]} set {$count_field} = {$count_field} + 1 where blog_id='{$current[id]}' and monthly='{$monthly}'");

    // 업로드 파일
    //sql_query("update {$gb4[file_table]} set post_id='{$id}' where blog_id='{$current[id]}' and post_id='0'");
}

// 업로드된 파일 내용에서 가장 큰 번호를 얻어 거꾸로 확인해 가면서
// 파일 정보가 없다면 테이블의 내용을 삭제합니다.
/*
$row = sql_fetch(" select max(file_num) as max_file_num from {$gb4[file_table]} where blog_id='{$current[id]}' and post_id='{$id}' ");
for ($i=(int)$row[max_file_num]; $i>=0; $i--) 
{
    $row2 = sql_fetch(" select save_name from {$gb4[file_table]} where blog_id='{$current[id]}' and post_id='{$id}' and file_num = '{$i}' ");

    // 정보가 있다면 빠집니다.
    if ($row2[save_name]) break;

    // 그렇지 않다면 정보를 삭제합니다.
    sql_query(" delete from {$gb4[file_table]} where blog_id='{$current[id]}' and post_id='{$id}' and file_num = '{$i}' ");
}
*/
//------------------------------------------------------------------------------

// 디렉토리가 없다면 생성합니다. (퍼미션도 변경하구요.)
@mkdir("$g4[path]/data/blog/file/$current[mb_id]", 0707);
@chmod("$g4[path]/data/blog/file/$current[mb_id]", 0707);

//------------------------------------------------------------------------------
// 아래의 코드는 bbs/write_update.php의 파일업로드 코드와 거의 같습니다 (데이터 경로/DB 부분 등을 제외하고).

// "인터넷옵션 > 보안 > 사용자정의수준 > 스크립팅 > Action 스크립팅 > 사용 안 함" 일 경우의 오류 처리
// 이 옵션을 사용 안 함으로 설정할 경우 어떤 스크립트도 실행 되지 않습니다.
//if (!$_POST[wr_content]) die ("내용을 입력하여 주십시오.");

$chars_array = array_merge(range(0,9), range('a','z'), range('A','Z'));
//print_r2($chars_array); exit;

// 가변 파일 업로드
$file_upload_msg = "";
$upload = array();

for ($i=0; $i<count($_FILES[bf_file][name]); $i++) 
{
    // 삭제에 체크가 되어있다면 파일을 삭제합니다.
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

    // 서버에 설정된 값보다 큰파일을 업로드 한다면
    if ($filename)
    {
        if ($_FILES[bf_file][error][$i] == 1)
        {
            $file_upload_msg .= "\'{$filename}\' 파일의 용량이 서버에 설정($upload_max_filesize)된 값보다 크므로 업로드 할 수 없습니다.\\n";
            continue;
        }
        else if ($_FILES[bf_file][error][$i] != 0)
        {
            $file_upload_msg .= "\'{$filename}\' 파일이 정상적으로 업로드 되지 않았습니다.\\n";
            continue;
        }
    }

    if (is_uploaded_file($tmp_file)) 
    {
        // 관리자가 아니면서 설정한 업로드 사이즈보다 크다면 건너뜀
        if (!$is_admin && $filesize > $gb4[upload_one_file_size]) 
        {
            $file_upload_msg .= "\'{$filename}\' 파일의 용량(".number_format($filesize)." 바이트)이 게시판에 설정(".number_format($gb4[upload_one_file_size])." 바이트)된 값보다 크므로 업로드 하지 않습니다.\\n";
            continue;
        }

        //=================================================================\
        // 090714
        // 이미지나 플래시 파일에 악성코드를 심어 업로드 하는 경우를 방지
        // 에러메세지는 출력하지 않는다.
        //-----------------------------------------------------------------
        $timg = @getimagesize($tmp_file);
        // image type
        if ( preg_match("/\.($config[cf_image_extension])$/i", $filename) ||
             preg_match("/\.($config[cf_flash_extension])$/i", $filename) ) 
        {
            if ($timg[2] < 1 || $timg[2] > 16)
            {
                //$file_upload_msg .= "\'{$filename}\' 파일이 이미지나 플래시 파일이 아닙니다.\\n";
                continue;
            }
        }
        //=================================================================

        $upload[$i][image] = $timg;

        // 4.00.11 - 글답변에서 파일 업로드시 원글의 파일이 삭제되는 오류를 수정
        if ($m == 'u')
        {
            // 존재하는 파일이 있다면 삭제합니다.
            $row = sql_fetch(" select save_name from $gb4[file_table] where blog_id = '$current[id]' and post_id = '$id' and file_num = '$i' ");
            @unlink("$g4[path]/data/blog/file/$current[mb_id]/$row[save_name]");
        }

        // 프로그램 원래 파일명
        $upload[$i][source] = $filename;
        $upload[$i][filesize] = $filesize;

        // 아래의 문자열이 들어간 파일은 -x 를 붙여서 웹경로를 알더라도 실행을 하지 못하도록 함
        $filename = preg_replace("/\.(php|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $filename);

        // 접미사를 붙인 파일명
        //$upload[$i][file] = abs(ip2long($_SERVER[REMOTE_ADDR])).'_'.substr(md5(uniqid($g4[server_time])),0,8).'_'.urlencode($filename);
        // 달빛온도님 수정 : 한글파일은 urlencode($filename) 처리를 할경우 '%'를 붙여주게 되는데 '%'표시는 미디어플레이어가 인식을 못하기 때문에 재생이 안됩니다. 그래서 변경한 파일명에서 '%'부분을 빼주면 해결됩니다. 
        //$upload[$i][file] = abs(ip2long($_SERVER[REMOTE_ADDR])).'_'.substr(md5(uniqid($g4[server_time])),0,8).'_'.str_replace('%', '', urlencode($filename)); 
        shuffle($chars_array);
        $shuffle = implode("", $chars_array);
        // 불당팩 - ip주소를 그대로 노출하는 것이라 timestamp로 변경
        //$upload[$i][file] = abs(ip2long($_SERVER[REMOTE_ADDR])).'_'.substr($shuffle,0,8).'_'.str_replace('%', '', urlencode($filename)); 
        // 첨부파일 첨부시 첨부파일명에 공백이 포함되어 있으면 일부 PC에서 보이지 않거나 다운로드 되지 않는 현상이 있습니다. (길상여의 님 090925)
        //$upload[$i][file] = time().'_'.substr($shuffle,0,8).'_'.str_replace('%', '', urlencode($filename));
        $upload[$i][file] = time().'_'.substr($shuffle,0,8).'_'.str_replace('%', '', urlencode(str_replace(' ', '_', $filename)));

        $dest_file = "$g4[path]/data/blog/file/$current[mb_id]/" . $upload[$i][file];

        // 업로드가 안된다면 에러메세지 출력하고 죽어버립니다.
        $error_code = move_uploaded_file($tmp_file, $dest_file) or die($_FILES[bf_file][error][$i]);

        // 올라간 파일의 퍼미션을 변경합니다.
        chmod($dest_file, 0606);

        //$upload[$i][image] = @getimagesize($dest_file);

    }
}

//------------------------------------------------------------------------------
// 가변 파일 업로드
// 나중에 테이블에 저장하는 이유는 $wr_id 값을 저장해야 하기 때문입니다.
for ($i=0; $i<count($upload); $i++) 
{
    $row = sql_fetch(" select count(*) as cnt from $gb4[file_table] where blog_id = '$current[id]' and post_id = '$id' and file_num = '$i' ");

    if ($row[cnt]) 
    {
        // 삭제에 체크가 있거나 파일이 있다면 업데이트를 합니다.
        // 그렇지 않다면 내용만 업데이트 합니다.
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

// 업로드된 파일 내용에서 가장 큰 번호를 얻어 거꾸로 확인해 가면서
// 파일 정보가 없다면 테이블의 내용을 삭제합니다.
$row = sql_fetch(" select max(file_num) as max_bf_no from $gb4[file_table] where blog_id = '$current[id]' and post_id = '$id' ");

for ($i=(int)$row[max_bf_no]; $i>=0; $i--) 
{
    $row2 = sql_fetch(" select save_name from $gb4[file_table] where blog_id = '$current[id]' and post_id = '$id' and file_num = '$i' ");

    // 정보가 있다면 빠집니다.
    if ($row2[save_name]) break;

    // 그렇지 않다면 정보를 삭제합니다.
    sql_query(" delete from $gb4[file_table] where blog_id = '$current[id]' and blog_id = '$id' and file_num = '$i' ");
}
//------------------------------------------------------------------------------

// 트랙백 핑을 보낸다.
if (($m != "m" && $trackback_url) || ($m=="m" && $trackback_url && $ping)) 
{
    $url = get_full_url($current[blog_url]);
    $msg = send_trackback($trackback_url, $url, $title, $current[blog_name], $content);
    if ($msg) 
        echo "<script language='JavaScript'>alert('$msg $trackback_url');</script>";
}

// 관리 페이지에서 수정한 경우 다시 관리 페이지로 이동
if ($me)
    goto_url ("adm_post_list.php?mb_id=$member[mb_id]&page=$page&cate=$cate");

// 메인화면에서 수정한 경우 다시 메인화면으로 이동
else 
    goto_url (get_post_url($id));


?>