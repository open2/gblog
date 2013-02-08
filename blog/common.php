<?
// gblog 변수를 초기화
$current = array();
$use_sb = array();

// gblog 경로
$g4['gb4_path'] = $gb4_path;

//경로의 오류를 없애기 위해 $gb4_path 변수는 해제
unset($gb4_path);

// gblog 설정을 읽습니다.
include_once("$g4[gb4_path]/gblog.config.php");

//gblog.lib.php 파일을 읽습니다.
include_once("$gb4[path]/lib/blog.lib.php");

// 사용변수를 escape
$mb_id = mysql_real_escape_string($mb_id);
$id = (int) $id;

// $mb_id가 지정되면 해당 blog 정보를 읽습니다.
if ($mb_id)
    $current = have_a_blog($mb_id);

// 현재블로그의 정보를 설정 합니다.
if ($current) {

    // 현재 블로그 주인의 이메일 주소와 홈페이지 주소를 얻는다.
    $row = get_member($current[mb_id], "mb_email, mb_homepage, mb_nick");
    $current[writer] = $row[mb_nick];
    $current[mb_email] = $row[mb_email];
    $current[mb_homepage] = $row[mb_homepage];

    // 관리자 주소
    $current[admin_url] = revision_url("$gb4[bbs_url]/adm_post_list.php?mb_id=$member[mb_id]");
    $current[write_url] = revision_url("$gb4[bbs_url]/adm_write.php?mb_id=$member[mb_id]");

    // 현재 블로그주소
    $current[blog_url] = get_blog_url($mb_id);

    // top_image
    $current[top_image_path] = revision_url("$g4[path]/data/blog/top_image/$mb_id");
    $current[top_image_url] = revision_url("$gb4[root]/data/blog/top_image/$mb_id");

    // background_image
    $current[background_image_path] = revision_url("$g4[path]/data/blog/background_image/$mb_id");
    $current[background_image_url] = revision_url("$gb4[root]/data/blog/background_image/$mb_id");

    // background_repeat
    $current[background_repeat_path] = revision_url("$g4[path]/data/blog/background_repeat/$mb_id");
    $current[background_repeat_url] = revision_url("$gb4[root]/data/blog/background_repeat/$mb_id");

    // 프로필 이미지
    $current[profile_image_path]  = revision_url("$g4[path]/data/blog/profile_image/$current[mb_id]");
    $current[profile_image_url]   = revision_url("$gb4[root]/data/blog/profile_image/$current[mb_id]");

    // 스타일 시트
    $current[stylesheet_path] = revision_url("$g4[path]/data/blog/stylesheet/$mb_id.css");
    $current[stylesheet_url]  = revision_url("$gb4[root]/data/blog/stylesheet/$mb_id.css");

    // 검색url
    if ($gb4[use_permalink] == 'numeric')
        $search_url = revision_url("$current[blog_url]/search/");
    else
        $search_url = revision_url("$current[blog_url]&search=");

    // 사이드바 이름 정의
    $sidebar_define = array(
        'profile'           => '프로필',
        'admin'             => '관리자',
        'search'            => '검색',
        'category'          => '글분류',
        'recent_post'       => '최근글',
        'recent_comment'    => '최근댓글',
        'recent_trackback'  => '최근엮인글',
        'tag'               => '태그구름',
        'calendar'          => '달력',
        'monthly'           => '월별',
        'link'              => '즐겨찾기',
        'counter'           => '카운터',
        'rss'               => 'RSS',
        'user_1'            => '사용자정의 1',
        'user_2'            => '사용자정의 2',
        'user_3'            => '사용자정의 3',
        'user_4'            => '사용자정의 4',
        'user_5'            => '사용자정의 5'
    );

    // DB 에 사이드바 값이 없을때 기본값.
    $sidebar_default = "$current[sidebar_left]$current[sidebar_right]$current[sidebar_garbage]";
    if (!trim($sidebar_default)) {
        $current[sidebar_left] = '';
        $current[sidebar_right] = 'profile,admin,search,category,recent_post,recent_comment,recent_trackback,tag,calendar,monthly,link,counter,rss';
        $current[sidebar_garbage] = 'user_1,user_2,user_3,user_4,user_5';
    }

    // 사이드바 레이아웃
    $sidebar_used  = "$current[sidebar_left]$current[sidebar_right]";
    foreach($sidebar_define as $key => $value) {
        if (strstr($sidebar_used,$key))
            $use_sb[$key] = true;
        else
            $use_sb[$key] = false;
    }

    $sidebar_left = get_sidebar_list('left');
    $sidebar_right = get_sidebar_list('right');
    //$sidebar_top = get_sidebar_list('top');
    //$sidebar_bottom = get_sidebar_list('bottom');

    // 비밀글
    if ($member[mb_id] != $current[mb_id])
        $sql_secret = " and secret = 1 ";

    // 스킨 호출
    $blog_skin = get_skin($current[mb_id], $preview);

    $blog_skin_path = revision_url("$gb4[path]/skin/blog/$blog_skin");
    $blog_skin_url = revision_url("$gb4[url]/skin/blog/$blog_skin");

    // 블로그 파일 경로
    $current[file_path] = revision_url("$g4[path]/data/blog/file/$current[mb_id]");
    $current[file_url] = revision_url("$gb4[root]/data/blog/file/$current[mb_id]");

    // rss 주소 : 퍼머링크를 사용할 경우
    if ($gb4[use_permalink] != 'none') {
        $current[rss] = revision_url("$current[blog_url]/rss");

    // rss 주소 : 퍼머링크를 사용하지 않을 경우
    } else {
        $current[rss] = "$gb4[bbs_path]/rss.php?mb_id=$mb_id";
    }

}

// 회원의 경우 회원의 blog url을 읽습니다.
if ($member[mb_id])
    $member[blog_url] = get_blog_url($member[mb_id]);
?>