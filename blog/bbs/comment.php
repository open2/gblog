<?
include_once("./_common.php");
include_once("$gb4[bbs_path]/_header.php");

// 현재 페이지의 글 정보를 DB 에서 불러온다.
$qry = sql_query("select * from {$gb4['comment_table']} c where c.blog_id='{$current['id']}' and c.post_id='{$id}' order by c.comment_num");

// 글 정보를 담을 $comment 변수 초기화
$comment = array();
$index = 0;

// $comment 변수에 글 내용을 모두 담는다.
while( $res = sql_fetch_array($qry) ) {

    // 댓글 정보를 배열에 담는다.
    $comment[$index] = $res;

    // 댓글 고유 주소를 담는다.
    $comment[$index]['permalink'] = get_comment_url($res['post_id'], $res['id']);

    if( !$res['secret'] || ($res['secret'] && $current['mb_id'] == $member['mb_id']) || ($res['secret'] && $member['mb_id'] && $res['mb_id'] == $member['mb_id']) ) {

        // 이름에 사이드뷰를 건다.
        $comment[$index]['writer_name'] = get_sideview($res['mb_id'], $res['writer_name'], $res['writer_email'], $res['writer_url']);

        // 댓글에 줄바꿈이 있을 경우 <br/> 태그를 먹여준다.
        $comment[$index]['writer_content'] = nl2br($res['writer_content']);

        // url auto link
        $comment[$index]['writer_content'] = url_auto_link($comment[$index]['writer_content']);

    }  else {

        // 관리자만 볼 수 있는 댓글
        //$comment[$index]['writer_name'] = '비밀댓글';
        $comment[$index]['writer_name'] = get_sideview($res['mb_id'], $res['writer_name'], $res['writer_email'], $res['writer_url']);
        $comment[$index]['writer_content'] = '관리자만 볼 수 있는 댓글 입니다.';
    }

    $index++;
}
echo count($comment)."\n";

include_once("{$blog_skin_path}/comment.skin.php");

?>