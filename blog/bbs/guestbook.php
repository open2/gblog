<?
include_once("./_common.php");

$g4['title'] = $current[blog_name] . "-guest book";

// ������ ��ȣ �ʱ�ȭ
if (isset($page))
    $page = (int)$page; 
else
    $page = 1;

$page_size = (int)$gb4[gb_page_rows];

$sql_from = " from $gb4[guestbook_table] ";
$sql_where = " where blog_id='$current[id]' ";

// ����¡�� ���� �� �ε�
$sql = "select count(*) as cnt $sql_from $sql_where ";
$total_post = sql_fetch($sql);
$total_post = $total_post['cnt'];

$total_page = (int)($total_post/$page_size) + ($total_post%$page_size==0 ? 0 : 1);
$page_start = $page_size * ( $page - 1 );

$sql = "select * $sql_from $sql_where order by id desc limit {$page_start}, {$page_size}";
$qry = sql_query($sql);

$guestbook_list = array();
while ($res=sql_fetch_array($qry))  {
    // ��б� / ��б� + ��α� �������� �� / ��б� + �۾��� + �۾���=�湮��
    if( !$res['secret'] || ($res['secret'] && $current['mb_id'] == $member['mb_id']) || ($res['secret'] && $member['mb_id'] && $res['mb_id'] == $member['mb_id']) ) 
    {
        // �̸��� ���̵�並 �Ǵ�.
        $res['writer_name'] = get_sideview($res['mb_id'], $res['writer_name'], $res['writer_email'], $res['writer_url']);

        // ��ۿ� �ٹٲ��� ���� ��� <br/> �±׸� �Կ��ش�.
        $res['writer_content'] = nl2br($res['writer_content']);
        $res['answer_content'] = nl2br($res['answer_content']);

        // url auto link
        $res['writer_content'] = url_auto_link($res['writer_content']);
        $res['answer_content'] = url_auto_link($res['answer_content']);
    }  
    else 
    {
        // �����ڸ� �� �� �ִ� ��
        $res['writer_name'] = get_sideview($res['mb_id'], $res['writer_name'], $res['writer_email'], $res['writer_url']);
        $res['writer_content'] = '�����ڸ� �� �� �ִ� �� �Դϴ�.';
    }

    array_push($guestbook_list, $res);
}

include_once("$gb4[path]/head.sub.php");
include_once("$blog_skin_path/head.skin.php");

include_once("{$blog_skin_path}/guestbook_list.skin.php");
include_once("{$blog_skin_path}/guestbook.skin.php");

include_once("{$blog_skin_path}/tail.skin.php");
include_once("$gb4[path]/tail.sub.php");
?>