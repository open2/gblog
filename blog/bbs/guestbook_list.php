<?
include_once("./_common.php");
include_once("./_header.php");

if (!$page) $page = 1;

$page_size = 10;

$sql = "select count(*) as cnt from {$gb4['guestbook_table']} where blog_id='{$current['id']}'";
$total_post = sql_fetch($sql);
$total_post = $total_post['cnt'];

$total_page = (int)($total_post/$page_size) + ($total_post%$page_size==0 ? 0 : 1);
$page_start = $page_size * ( $page - 1 );

$sql = "select * from {$gb4['guestbook_table']} where blog_id='{$current['id']}' order by id desc limit {$page_start}, {$page_size}";
$qry = sql_query($sql);

$guestbook_list = array();
while ($res=sql_fetch_array($qry))  {

    if( !$res['secret'] || ($res['secret'] && $current['mb_id'] == $member['mb_id']) || ($res['secret'] && $member['mb_id'] && $res['mb_id'] == $member['mb_id']) ) {

        // �̸��� ���̵�並 �Ǵ�.
        $res['writer_name'] = get_blog_sideview($res['mb_id'], $res['writer_name'], $res['writer_email'], $res['writer_url']);

        // ��ۿ� �ٹٲ��� ���� ��� <br/> �±׸� �Կ��ش�.
        $res['writer_content'] = nl2br($res['writer_content']);
        $res['answer_content'] = nl2br($res['answer_content']);

        // url auto link
        $res['writer_content'] = url_auto_link($res['writer_content']);
        $res['answer_content'] = url_auto_link($res['answer_content']);

    }  else {

        // �����ڸ� �� �� �ִ� ��
        //$res['writer_name'] = '��б�';
        $res['writer_name'] = get_blog_sideview($res['mb_id'], $res['writer_name'], $res['writer_email'], $res['writer_url']);
        $res['writer_content'] = '�����ڸ� �� �� �ִ� �� �Դϴ�.';
    }

    array_push($guestbook_list, $res);
}

include_once("{$blog_skin_path}/guestbook_list.skin.php");
?>