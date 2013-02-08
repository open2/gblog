<?
include_once("./_common.php");

include_once("{$g4[path]}/head.sub.php");
include_once("./admin.head.php");

$g4['title'] = "��α� �±� ����";

// get mb_id, blog_id
$mb_id = $member['mb_id'];
$blog_id = $current[id];
if (!$blog_id || $current['mb_id'] != $mb_id)
    alert('�ڽ��� ��α׸� ������ �� �ֽ��ϴ�.');

$page_size = (int)$gb4[gb_page_rows];

// ������ ��ȣ �ʱ�ȭ
if (empty($page))
    $page = 1;
else
    $page = (int)$page;

$sql_from = " FROM $gb4[tag_table] b left join $gb4[taglog_table] a on (a.tag_id = b.id) ";
$sql_where = " WHERE a.blog_id = '$blog_id' ";

// ����¡�� ���� �� �ε�
$sql = " SELECT count(distinct a.tag_id) as cnt
                $sql_from
                $sql_where
          ";
$total_post = sql_fetch($sql);

$total_post = $total_post['cnt'];
$total_page = (int)($total_post/$page_size) + ($total_post%$page_size==0 ? 0 : 1);
$page_start = $page_size * ( $page - 1);

// get tag list
$sql = " SELECT count(*) as cnt, b.*
                $sql_from 
                $sql_where 
          GROUP BY a.tag_id 
          ORDER BY cnt desc 
          LIMIT {$page_start}, {$page_size} ";
$result = sql_query($sql);

$post = array();
$index = 0;
while( $res = sql_fetch_array($result)) {
    // �ϴ� DB ���� ������ �� ������ ���� $post ������ ��´�.
    $post[$index] = $res;
    $post[$index][tag_url] = "$gb4[url]/?mb_id=$mb_id&tag=$res[tag]";
    $post[$index][modify_url] = "<a href=\"javascript:tag_copy('{$post[$index][id]}')\"><img src='$gb4[path]/img/icon_modify.gif' border=0 title='����'></a>";
    $post[$index][delete_url] = "<a href=\"javascript:post_delete('adm_tag_list_delete.php?', '$res[id]');\"><img src='$gb4[path]/img/icon_delete.gif' border=0 title='����'></a>";
    $post[$index][index] = $index + 1;
    $index++;
}

  //  $s_del = "<a href=\"javascript:post_delete('board_delete.php', '$row[bo_table]');\"><img src='img/icon_delete.gif' border=0 title='����'></a>";
  //  $s_copy = "<a href=\"javascript:board_copy('$row[bo_table]');\"><img src='img/icon_copy.gif' border=0 title='����'></a>";


// ����¡ ��������
$paging = get_paging(10, $page, $total_page, "?mb_id={$current['mb_id']}&page=");
?>

<div class="adm_info">
    <b>�±� ����</b> : ���� �±׸� �����ϴ� ������ �Դϴ�.
</div>

<form name=fboardlist method=post>
<input type=hidden name=sst   value="<?=$sst?>">
<input type=hidden name=sod   value="<?=$sod?>">
<input type=hidden name=sfl   value="<?=$sfl?>">
<input type=hidden name=stx   value="<?=$stx?>">
<input type=hidden name=page  value="<?=$page?>">
<input type=hidden name=token value="<?=$token?>">

<table border=0 cellpadding=0 cellspacing=1 width=100% bgcolor="#cccccc">
    <tbody bgcolor="#ffffff">
    <tr height=25 align=center bgcolor="#efefef">
        <td width=30> no. </td>
        <td > �±� </td>
        <td width=80> ������� </td>
        <td width=80> �������� </td>
        <td width=80> count(my) </td>
        <td width=80> count(blog) </td>
        <td width=120> </td>
    </tr>


<? /* ���� ���� ��  ��� */
    if (!count($post)) { ?>
    <tr>
        <td height=50 colspan=7 align=center>
            ���� �������� �ʽ��ϴ�.
        </td>
    </tr>
<? } /* ���� ���� �� ��� �� */?>

<?  /* ��� ���� ����  */
    for($i=0; $i<count($post); $i++) { 
?>
    <tr bgcolor="<?=$bgcolor?>" class='ht center'>
        <td align=center height=25> <?=$post[$i]['index']?> </td>
        <td align=center><a href="<?=$post[$i]['tag_url']?>" target=_new><?=$post[$i]['tag']?></a></td>
        <td align=center><?=substr($post[$i]['regdate'],0,10)?></td>
        <td align=center><?=substr($post[$i]['lastdate'],0,10)?></td>
        <td align=right><?=number_format($post[$i]['cnt'])?> ��&nbsp;&nbsp;</td>
        <td align=right><?=number_format($post[$i]['tag_count'])?> ��&nbsp;&nbsp;</td>
        <td align=center><?=$post[$i]['modify_url']?>&nbsp;<?=$post[$i]['delete_url']?></td>
    </tr>

<? } /* ��� ���� �� */ ?>

</table>
</form>

<!-- ����¡ ���� -->
<div id=paging><?=$paging?></div>
<!-- ����¡ ���� -->

<script language="JavaScript">
function tag_copy(id) {
    window.open("./adm_tag_copy.php?tag_id="+id+"&blog_id="+<?=$blog_id?>, "TagCopy", "left=10,top=10,width=500,height=200");
}
</script>

<script language="JavaScript">
function post_delete(action_url, val)
{
	var f = document.fpost;

  if(confirm("�ѹ� ������ �ڷ�� ������ ����� �����ϴ�.\n\n���� �����Ͻðڽ��ϱ�?")) {
      f.mb_id.value     = "<?=$member[mb_id]?>";
      f.tag_id.value    = val;
      f.blog_id.value   = <?=$blog_id?>;
    	f.action          = action_url;
    	f.submit();
	}
}
</script>

<form name='fpost' method='post'>
<input type='hidden' name='sst'   value='<?=$sst?>'>
<input type='hidden' name='sod'   value='<?=$sod?>'>
<input type='hidden' name='sfl'   value='<?=$sfl?>'>
<input type='hidden' name='stx'   value='<?=$stx?>'>
<input type='hidden' name='page'  value='<?=$page?>'>
<input type='hidden' name='token' value='<?=$token?>'>

<input type='hidden' name='mb_id'   value="">
<input type='hidden' name='tag_id'  value="">
<input type='hidden' name='blog_id' value="">
</form>

<?
include_once("./admin.tail.php");
include_once("$gb4[path]/tail.sub.php");
?>