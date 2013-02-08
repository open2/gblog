<?
include_once("./_common.php");

$g4['title'] = $current[blog_name] . "-��α� �� ����";

// get mb_id, blog_id
$mb_id = $member['mb_id'];
$blog_id = $current[id];
if (!$blog_id || $current['mb_id'] != $mb_id)
    alert('�ڽ��� ��α׸� ������ �� �ֽ��ϴ�.');

// ������ ��ȣ �ʱ�ȭ
if (isset($page))
    $page = (int)$page; 
else
    $page = 1;

$page_size = (int)$gb4[gb_page_rows];

// �з� �˻�
if (!empty($cate))
{
    // �з��̸� �յ� ���� ����
    $cate = trim($cate);

    // �з��̸����� �з� ������ȣ�� �����´�.
    $res  = sql_fetch("select id from {$gb4['category_table']} where category_name = '{$cate}'");

    // �з� ������ȣ�� �˻� ������ ����� ����.
    if (empty($res))
        $sql_cate = " and null ";
    else
        $sql_cate = " and p.category_id = '{$res['id']}' ";
}


// ����¡�� ���� �� �ε�
$sql = "select count(*) as cnt from {$gb4['post_table']} p where p.blog_id='{$current['id']}' $sql_cate $sql_tag";
$total_post = sql_fetch($sql);

$total_post = $total_post['cnt'];
$total_page = (int)($total_post/$page_size) + ($total_post%$page_size==0 ? 0 : 1);
$page_start = $page_size * ( $page - 1);

// ���� �������� �� ������ DB ���� �ҷ��´�.
$sql = "select
            p.*,
            c.category_name
        from
            {$gb4['post_table']} p left join {$gb4['category_table']} c on p.category_id = c.id
        where
            p.blog_id='{$current['id']}'
            $sql_cate
            $sql_tag
            $sql_id
        order by
            p.post_date desc
        limit
            {$page_start}, {$page_size}";
$qry = sql_query($sql);


// �� ������ ���� $post ���� �ʱ�ȭ
$post = array();
$index = 0;

while( $res = sql_fetch_array($qry)) {

    // �ϴ� DB ���� ������ �� ������ ���� $post ������ ��´�.
    $post[$index] = $res;

    // �ۿ� �з� ���� �Ǿ����� ������� '�з�����' �� �⺻ �������ش�.
    if (empty($res['category_name'])) $post[$index]['category_name'] = '<font color=silver>�з�����</font>';

    // �� �����ּ� ��������
    $post[$index]['url'] = get_post_url($res['id']);

    $index++;
}

// ����¡ ��������
$paging = get_paging($page_size, $page, $total_page, "?mb_id={$current['mb_id']}&tag={$tag}&cate={$cate}&page=");

$cg = array();
$q = sql_query("select * from {$gb4['category_table']} where blog_id='{$current['id']}' order by rank desc");
while($r = sql_fetch_array($q)) array_push($cg, $r);

include_once("{$g4[path]}/head.sub.php");
include_once("./admin.head.php");

?>

<div class="adm_info">
    <b>�� ����</b> : �ۼ��Ͻ� ���� �����ϴ� ������ �Դϴ�.
</div>

<table border=0 cellpadding=0 cellspacing=1 width=100% bgcolor="#cccccc">
    <tbody bgcolor="#ffffff">
    <tr height=25 align=center bgcolor="#efefef">
        <td width=30> <input type=checkbox onclick="all_click()" title="��ü����" alt="��ü����"> </td>
        <td width=100> �з� </td>
        <td> ���� </td>
        <td width=80> ������� </td>
        <td width=50> ��� </td>
        <td width=50> ���α� </td>
        <td width=50> ��ȸ�� </td>
    </tr>


<? /* ���� ���� ��  ��� */
    if (!count($post)) { ?>
    <tr>
        <td height=50 colspan=7 align=center>
            ���� �������� �ʽ��ϴ�.
        </td>
    </tr>

<? } /* ���� ���� �� ��� �� */?>


<?  /* ��α� �� ��� ���� ����  */
    for($i=0; $i<count($post); $i++) { ?>

    <?
    // ���� ���̸� �����Ѵ�.
    if (strlen($post[$i]['title']) > 30)
        $post[$i]['title'] = cut_str($post[$i]['title'],30);

    if (!$post[$i]['secret']) $bgcolor='#FFFCFB'; else $bgcolor='#ECFCFF';
    ?>

    <!--<tr height=25 onmouseover="this.style.background='#FEF8F0'" onmouseout="this.style.background='#ffffff'">-->
    <tr height=25 bgcolor="<?=$bgcolor?>">
        <td align=center><input type=checkbox name="id" value="<?=$post[$i]['id']?>" title="<?=$post[$i]['id']?>" alt="<?=$post[$i]['id']?>"></td>
        <td align=center><?=$post[$i]['category_name']?></td>
        <td style="overflow:hidden">&nbsp;
            <a href="javascript:post_mod(<?=$post[$i]['id']?>)"><?=$post[$i]['title']?></a>
            &nbsp;&nbsp;
            <a href="<?=$post[$i]['url']?>" style="font-size:0.9em; color:#ccc;">[����]</a>
        </td>
        <td align=center><?=substr($post[$i]['post_date'],0,10)?></td>
        <td align=right><?=number_format($post[$i]['comment_count'])?> ��&nbsp;&nbsp;</td>
        <td align=right><?=number_format($post[$i]['trackback_count'])?> ��&nbsp;&nbsp;</td>
        <td align=right><?=number_format($post[$i]['hit'])?> &nbsp;&nbsp;</td>
    </tr>

<? } /* ��α� �� ��� ���� �� */ ?>



</table>

<table width=100% border=0 cellpadding=0 cellspacing=0 style="margin-top:10px;">
<tr>
    <td> 
        ������ ����
        <select id="check_list" onchange="check_change()" align="absmiddle">
        <option>��� �ұ��?</option>
        <option>--------------------------------------------------</option>
        <? for ($i=0; $i<count($cg); $i++) { ?>
        <option value="<?=$cg[$i]['id']?>">"<?=$cg[$i]['category_name']?>" �з��� �̵��մϴ�.</option>
        <? } ?>
        <option>--------------------------------------------------</option>
        <option value="open">���� �մϴ�.</option>
        <option value="secret">������� �����մϴ�.</option>
        <option>--------------------------------------------------</option>
        <option value="del" style="color:red;">���� �մϴ�.</option>
        </select>

    </td>
    <td align=right>
    </td>
</tr>
</table>

<!-- ����¡ ���� -->
<div id=paging><?=$paging?></div>
<!-- ����¡ ���� -->

<script language=javascript>
/* �� ���� �������� �̵��ϴ� �Լ� */
function post_mod(id) {
    location.href = 'adm_write.php?m=u&me=1&page=<?=$page?>&cate=<?=$cate?>&mb_id=<?=$member['mb_id']?>&id='+id;
}
function all_click() {
    var id = document.getElementsByName("id");
    var ids = '';
    var chn = 0;

    for (var i=0; i<id.length; i++) {
        if (id[i].checked==true) chn++;
    }
    if (chn==id.length) {
        for (var i=0; i<id.length; i++) {
            id[i].checked = false;
        }
    } else {
        for (var i=0; i<id.length; i++) {
            id[i].checked = true;
        }
    }
}
function check_change() {
    var id  = document.getElementsByName("id");
    var chk = $("#check_list");
    var cnt = 0;
    var ids = '';
    var mode= chk.val();

    for (var i=0; i<id.length; i++) {
        if (id[i].checked==true) {
            ids += id[i].value + ',';
            cnt++;
        }
    }

    if (!cnt) { 
        alert('���� �������ּ���.'); 
        chk[0].selected = true; 
        return; 
    }

    if (mode=='del') {
        if (!confirm('�ѹ� ������ �ڷ�� ������ ����� �����ϴ�.\n\n���� �����Ͻðڽ��ϱ�?')) 
            return;
    } else {
        if (!confirm('���� �����Ͻðڽ��ϱ�?')) 
            return;
    }

    url   = "adm_post_change.php";
    send  = "mb_id=<?=$mb_id?>&mode=" + mode + "&ids=" + ids;

    $.ajax({
        type: 'POST',
        url: url,
        data: send,
        cache: false,
        async: false,
        success: function(result) {
              return_check_change(result);
            }
        });
}

function return_check_change(result) {

    result      = result.split(',');
    msg_num     = result[0];

    switch (msg_num) {
        case '001': alert('������ ��α׸� ������ �� �ֽ��ϴ�.'); return; break;
        case '002': alert('�������� �ʴ� �� �Դϴ�.'); return; break;
        case '000': location.reload(); break;
        default:
            alert("������ �߻��Ͽ����ϴ�.\n\n"+res);
    }
}
</script>

<?
include_once("./admin.tail.php");
include_once("{$g4[path]}/tail.sub.php");
?>