<?
include_once("./_common.php");
include_once("$gb4[bbs_path]/_header.php");

// ���� �������� �� ������ DB ���� �ҷ��´�.
$qry = sql_query("select * from {$gb4['comment_table']} c where c.blog_id='{$current['id']}' and c.post_id='{$id}' order by c.comment_num");

// �� ������ ���� $comment ���� �ʱ�ȭ
$comment = array();
$index = 0;

// $comment ������ �� ������ ��� ��´�.
while( $res = sql_fetch_array($qry) ) {

    // ��� ������ �迭�� ��´�.
    $comment[$index] = $res;

    // ��� ���� �ּҸ� ��´�.
    $comment[$index]['permalink'] = get_comment_url($res['post_id'], $res['id']);

    if( !$res['secret'] || ($res['secret'] && $current['mb_id'] == $member['mb_id']) || ($res['secret'] && $member['mb_id'] && $res['mb_id'] == $member['mb_id']) ) {

        // �̸��� ���̵�並 �Ǵ�.
        $comment[$index]['writer_name'] = get_sideview($res['mb_id'], $res['writer_name'], $res['writer_email'], $res['writer_url']);

        // ��ۿ� �ٹٲ��� ���� ��� <br/> �±׸� �Կ��ش�.
        $comment[$index]['writer_content'] = nl2br($res['writer_content']);

        // url auto link
        $comment[$index]['writer_content'] = url_auto_link($comment[$index]['writer_content']);

    }  else {

        // �����ڸ� �� �� �ִ� ���
        //$comment[$index]['writer_name'] = '��д��';
        $comment[$index]['writer_name'] = get_sideview($res['mb_id'], $res['writer_name'], $res['writer_email'], $res['writer_url']);
        $comment[$index]['writer_content'] = '�����ڸ� �� �� �ִ� ��� �Դϴ�.';
    }

    $index++;
}
echo count($comment)."\n";

include_once("{$blog_skin_path}/comment.skin.php");

?>