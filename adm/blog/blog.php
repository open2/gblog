<?
include_once("./_common.php");

auth_check($auth[$sub_menu], "r");

$g4[title] = "��α� ȸ������";

include_once("../admin.head.php");

$colspan = 9;
$page_size = 15;

// ������ ��ȣ �ʱ�ȭ
if( empty($page) )
    $page = 1;
else
    $page = (int)$page;

$total_blog = sql_fetch("select count(*) as cnt from {$gb4['blog_table']}");
$total_blog = $total_blog['cnt'];
$total_page = (int)($total_blog/$page_size) + ($total_blog%$page_size==0 ? 0 : 1);
$page_start = $page_size * ( $page - 1 );


// ����¡ ��������
$paging = get_paging($page_size, $page, $total_page, "?page=");


$qry = sql_query("select * from {$gb4['blog_table']} order by id desc limit {$page_start}, {$page_size}");
?>
<table width=100% cellpadding=0 cellspacing=0 border=0>
<form name=form method=post action='javascript:fconfigform_submit(document.fconfigform);'>
<tr>
    <td align=left><?=subtitle("��α� ȸ������")?></td>
</tr>
</table>



<table border=0 cellpadding=0 cellspacing=1 width=100% align=center>
<tbody align=center>
<tr><td colspan='<?=$colspan?>' class='line1'></td></tr>
<tr class='bgcol1 bold col1 ht center'>
    <td width=80> ���̵� </td>
    <td width=80> �ʸ� </td>
    <td> ��α� �̸� </td>
    <td width=50> �ۼ� </td>
    <td width=50> ��ۼ� </td>
    <td width=60> ���αۼ� </td>
    <td width=80> ������Ʈ </td>
    <td width=80> ������¥ </td>
    <td width=30> - </td>
</tr>
<tr><td colspan='<?=$colspan?>' class='line2'></td></tr>

<?
for($i=0; $res = sql_fetch_array($qry); $i++) {
    $blog_url = get_blog_url($res['mb_id']); 
    $list = $i%2;
    if( strlen($res['blog_name']) > 30 ) $res['blog_name'] = cut_str($res['blog_name'],30);
?>

<tr class='list<?=$list?> col1 ht center'>
    <td> <?=$res['mb_id']?> </td>
    <td> <?=get_sideview($res['mb_id'], $res['writer'])?> </td>
    <td> 
    <a href="<?=$blog_url?>" target="_blank"><?=$res['blog_name']?></a> 
    <? // Ż���� ȸ������ Ȯ��.
    $mb = get_member($res['mb_id'],"mb_leave_date");
    if ($mb[mb_leave_date] !== "")
        echo " **Ż���� ȸ���� ��α��Դϴ�. �������ּ���";
    ?>
    </td>
    <td> <?=$res['post_count']?> </td>
    <td> <?=$res['comment_count']?> </td>
    <td> <?=$res['trackback_count']?> </td>
    <td> <?=substr($res['last_update'],0,10)?> </td>
    <td> <?=substr($res['regdate'],0,10)?> </td>
    <td> <a href="javascript:del('blog_update.php?mode=delete&blog_id=<?=$res['id']?>')"><img src="../img/icon_delete.gif" border=0></a> </td>

<?
}
?>
<tr><td colspan='<?=$colspan?>' class='line2'></td></tr>
</table>

<p align=center>
<?=$paging?>
</p>

<?
include_once("../admin.tail.php");
?>