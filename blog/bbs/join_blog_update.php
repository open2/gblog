<?
include_once("./_common.php");

// POST 로 넘어온 변수 유효성 검사
if( !trim($blog_name) )
    alert('블로그 이름을 입력해주세요.');

if( 0 > $rss_open || $rss_open > 2 )
    alert('RSS 공개 여부를 선택해주세요.');

if( $rss_open != 0 && !trim($rss_count) )
    alert('최신 RSS 공개 갯수를 입력해주세요.');

// 블로그 이름에서 tag를 삭제
$blog_name = strip_tags($blog_name);

// 프로필 이미지 삭제
$profile_image_path = "{$g4[path]}/data/blog/profile_image/{$mb_id}";
if( file_exists($profile_image_path) && $profile_image_del ) {
    unlink($profile_image_path);
}

// 프로필 이미지 체크
$profile_image      = $_FILES['profile_image']['tmp_name'];
$profile_image_size = $_FILES['profile_image']['size'];

if( $profile_image_size ) {

    // 이미지 가로, 세로 및 mime 정보 로드
    $get_image_size = getimagesize($profile_image);

    // 정보가 없을 경우 이미지가 아님
    if( empty($get_image_size) )
        alert("이미지 파일만 업로드 가능합니다.");

    // MINE 값을 검사하여 PNG, GIF, JPG 만 업로드 가능하게 함
    $image_type = array('image/png', 'image/jpeg', 'image/gif');

    if( !in_array($get_image_size['mime'], $image_type) )
        alert("프로필 이미지는 jpg, gif, png 형식만 업로드 가능합니다.".$get_image_size['mime']);
/*
    if( $gb4['profile_image_width'] != 0 && $get_image_size[0] > $gb4['profile_image_width'] )
        alert("프로필 이미지의 가로 사이즈가 {$gb4['profile_image_width']}픽셀 보다 큽니다.");

    if( $gb4['profile_image_height'] != 0 && $get_image_size[1] > $gb4['profile_image_height'] )
        alert("프로필 이미지의 세로 사이즈가 {$gb4['profile_image_height']}픽셀 보다 큽니다.");
*/
    if( $gb4['profile_image_size'] != 0 && $profile_image_size > $gb4['profile_image_size'] )
        alert("프로필 이미지의 용량이 ".number_format($gb4['profile_image_size'])." byte 보다 큽니다.");

    // 프로필 이미지를 MySQL 에 삽입하기 위해 BLOB 타입으로 변환
    //$profile_image  = addslashes(fread(fopen($profile_image, "r"), $profile_image_size));

    $profile_image_path = "{$g4[path]}/data/blog/profile_image";
    if( !is_dir($profile_image_path) )
        alert('디렉토리가 존재하지 않습니다.');

    $profile_image_file = "{$profile_image_path}/{$member['mb_id']}";

    // 업로드가 안된다면 에러메세지 출력하고 죽어버립니다.
    $error_code = move_uploaded_file($profile_image, $profile_image_file) or die($_FILES['profile_image']['error']);

    // 올라간 파일의 퍼미션을 변경합니다.
    chmod($profile_image_file, 0606);
}

// 블로그 정보 업데이트
if( $w == 'u' )
{
    // 쿼리를 실행한다.
    $sql = "update
                {$gb4['blog_table']}
            set
                blog_name      = '{$blog_name}'
                ,blog_about     = '{$blog_about}'
                ,rss_open       = '{$rss_open}'
                ,rss_count      = '{$rss_count}'
                ,last_update    = '{$g4['time_ymdhis']}'
                ,use_post       = '{$use_post}'
                ,use_comment    = '{$use_comment}'
                ,use_guestbook  = '{$use_guestbook}'
                ,use_trackback  = '{$use_trackback}'
                ,use_tag        = '{$use_tag}'
                ,use_ccl        = '{$use_ccl}'
                ,use_autosource = '{$use_autosource}'
                ,use_random     = '{$use_random}'
            where
                mb_id = '{$member['mb_id']}'";
    sql_query($sql);

    /*// 프로필 이미지 업데이트.
    if( $profile_image_size != 0 )
        sql_query("update {$gb4['blog_table']} set profile_image = '{$profile_image}' where mb_id = '{$member['mb_id']}'");
    */
    $msg = "기본설정이 변경되었습니다.";
}

// 블로그 만들기
else
{
    // 블로그를 가지고 있다면 자신의 블로그로 이동한다.
    if( have_a_blog($member['mb_id']) )
        alert('이미 블로그를 가지고 계십니다.', $blog_url);

    // 회원 레벨 검사
    if( $member['mb_level'] < $gb4['make_level'] )
        alert("블로그를 생성은 {$gb4['make_level']} 레벨부터 가능합니다.");

    // 회원 포인트 검사
    if( $member['mb_point'] < $gb4['make_point'] )
        alert('블로그를 생성하려면 '.number_format($gb4['make_point']).' 포인트가 필요합니다.');

    // 기본 basic 스킨사용
    $res = sql_fetch("select id from {$gb4['skin_table']} where skin='basic'");
    $skin_id = $res['id'];

    sql_query("update {$gb4['skin_table']} set use_count=use_count+1 where id={$skin_id}");

    // 초기값 셋팅
    $page_count = 5;
    $list_count = 10;
    $sidebar_right   = 'profile,admin,search,category,recent_post,recent_comment,recent_trackback,tag,calendar,monthly,link,counter,rss';
    $sidebar_garbage = 'user_1,user_2,user_3,user_4,user_5';

    // 블로그를 생성한다.
    $sql = "insert into
                {$gb4['blog_table']}
            set
                id              = '{$member[mb_no]}'
                ,mb_id          = '{$member['mb_id']}'
                ,writer         = '{$member['mb_nick']}'
                ,blog_name      = '{$blog_name}'
                ,blog_about     = '{$blog_about}'
                ,rss_open       = '{$rss_open}'
                ,rss_count      = '{$rss_count}'
                ,last_update    = '{$g4['time_ymdhis']}'
                ,use_post       = '{$use_post}'
                ,use_comment    = '{$use_comment}'
                ,use_guestbook  = '{$use_guestbook}'
                ,use_trackback  = '{$use_trackback}'
                ,use_tag        = '{$use_tag}'
                ,use_ccl        = '{$use_ccl}'
                ,use_autosource = '{$use_autosource}'
                ,use_random     = '{$use_random}'
                ,regdate        = '{$g4['time_ymdhis']}'
                ,page_count     = '{$page_count}'
                ,list_count     = '{$list_count}'
                ,use_table      = '{$gb4['blog_table']}'
                ,skin_id        = '{$skin_id}'
                ,sidebar_right  = '{$sidebar_right}'
                ,sidebar_garbage= '{$sidebar_garbage}'
                ";
    sql_query($sql);

    // 블로그 생성 포인트 차감
    if( $gb4['make_point'] )
        insert_point( $member['mb_id'], $gb4['make_point'], '블로그 생성' );


    $msg = "$member[mb_nick] 님의 블로그가 만들어졌습니다.\\n\\n즐거운 블로깅 하세요 ^O^";
}

if( $w == 'u' )
    alert( $msg );
else
    alert( $msg, urldecode("$gb4[path]/?mb_id=$member[mb_id]") );
?>