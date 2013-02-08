<?
include_once("./_common.php");

$g4['title'] = "��� ����";

if( $current['mb_id'] != $member['mb_id'] )
    alert('�ڽ��� ��α׸� ������ �� �ֽ��ϴ�.');

$page_size = 10;

// ������ ��ȣ �ʱ�ȭ
if( empty($page) )
    $page = 1;
else
    $page = (int)$page;

// �˻�
if( !empty($st) && !empty($sc) )
    if( $sz == 1 )
        $sql_search = " and $st = '$sc' ";
    else
        $sql_search = " and $st like '%$sc%' ";
else
    $sql_search = "";


// ����¡�� ���� �� �ε�
$sql = "select count(*) as cnt from {$gb4['comment_table']} c where c.blog_id='{$current['id']}' $sql_search";
$total_post = sql_fetch($sql);

$total_post = $total_post['cnt'];
$total_page = (int)($total_post/$page_size) + ($total_post%$page_size==0 ? 0 : 1);
$page_start = $page_size * ( $page - 1 );


// ���� �������� �� ������ DB ���� �ҷ��´�.
$sql = "select
            c.*,
            p.title
        from
            {$gb4['comment_table']} c left join {$gb4['post_table']} p on c.post_id=p.id
        where
            c.blog_id='{$current['id']}'
            $sql_search
        order by
            c.regdate desc
        limit
            {$page_start}, {$page_size}";
$qry = sql_query($sql);


// �� ������ ���� $comment ���� �ʱ�ȭ
$comment = array();
$index = 0;


while( $res = sql_fetch_array($qry) ) {

    // �ϴ� DB ���� ������ �� ������ ���� $comment ������ ��´�.
    $comment[$index] = $res;

    // ��ۿ� �з� ���� �Ǿ����� ������� '��ü' �� �⺻ �������ش�.
    if( empty($res['category_name']) ) $comment[$index]['category_name'] = '��ü';

    // ��� ���� �ּҸ� ��´�.
    $comment[$index]['permalink'] = get_comment_url($res['post_id'], $res['id']);

    $index++;
}


// ����¡ ��������
$paging = get_blog_paging(10, $page, $total_page, "?mb_id={$current['mb_id']}&st={$st}&sc={$sc}&page=");
$paging = str_replace("<null>","<span class='paging'>", $paging);
$paging = str_replace("</null>","</span>", $paging);
$paging = str_replace("<a","<a class='paging'", $paging);

include_once("{$g4[path]}/head.sub.php");
include_once("./admin.head.php");


?>

<div class="adm_info">
    <b>��� ����</b> : ��α׿� ��ϵ� ����� �����Ͻ� �� �ֽ��ϴ�. �ֱٿ� �ۼ��� ��ۺ��� ��µ˴ϴ�.
</div>

<form name="searchform" method="get" action="<?=$PHP_SELF?>">
<input type=hidden name=mb_id value="<?=$mb_id?>">
<table border=0 cellpadding=0 cellspacing=0 width=100%>
<tr>
    <td>
        ��� �� <?=$total_post?> ��.
    </td>
    <td align=right>
        <select name="st">
            <option value="writer_content"<?if($st=='writer_content')echo' selected'?>>����</option>
            <option value="writer_name"<?if($st=='writer_name')echo' selected'?>>�̸�</option>
            <option value="writer_email"<?if($st=='writer_email')echo' selected'?>>E-Mail</option>
        </select>
        <input type=text size=10 name=sc value="<?=$sc?>">
        <input type=submit value=�˻�>
    </td>
</tr>
</table>
</form>


 
<? /* ���� ���� ��  ��� */
if( !count($comment) ) { ?>

    <div style="text-align:center; width:100%; padding-top:30px; padding-bottom:30px; background-color: #efefef;">
            ����� �������� �ʽ��ϴ�.
    </div>

<? } /* ���� ���� �� ��� �� */  ?>

<?/* ��α� �� ��� ���� ����  */
for($i=0; $i<count($comment); $i++) { 

    // �̸� ���̸� �����Ѵ�.
    $len = 10;
    if( strlen($comment[$i]['writer_name']) > $len )
        $writer_name = cut_str($comment[$i]['writer_name'],$len);
    else
        $writer_name = $comment[$i]['writer_name'];

    // ���� ���̸� �����Ѵ�.
    $len = 500;
    if( strlen($comment[$i]['writer_content']) > $len )
        $writer_content = cut_str($comment[$i]['writer_content'],$len);
    else
        $writer_content = $comment[$i]['writer_content'];

    // ����
    if( $i % 2 != 0 ) $bgcolor='#ffffff'; else $bgcolor='#efefef';
    $bgcolor='#efefef';
?>

    <table border=0 cellpadding=5 cellspacing=1 width=100% style="margin-bottom:20px;" bgcolor="#cccccc">
    <tbody bgcolor="<?=$bgcolor?>">
    <tr>
        <td colspan=3> 
            <table border=0 cellpadding=0 cellspacing=0 width=100%>
            <tr>
                <td>
                    <a href="<?=$comment[$i]['permalink']?>"><b><?=$comment[$i]['title']?></b></a>
                    �� ����
                    <?if($comment[$i]['secret']){?> <font color="red">���</font> <?}?> ���

                </td>
                <td align=right>
                    <a href="<?=$comment[$i]['permalink']?>">����</a>,
                    <a href="javascript:del(<?=$comment[$i]['id']?>)">����</a>
                </td>
            </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td width=200> �ۼ��� : <a href="adm_comment_list.php?mb_id=<?=$mb_id?>&sz=1&st=writer_name&sc=<?=$comment[$i]['writer_name']?>"><?=$writer_name?></a> </td>
        <td width=150> IP : <a href="javascript:whois('<?=$comment[$i]['writer_ip']?>')"><?=$comment[$i]['writer_ip']?></a> </td>
        <td> <?=$comment[$i]['writer_email']?> </td>
    </tr>
    <tr>
        <td colspan=3 style="word-break:break-all; overflow:auto; background-color:#fff; padding:20px;">  
            <?=$writer_content?> 
        </td>
    </tr>
    <tr>
        <td colspan=3>
            Ȩ������ : 
            <?if( !empty($comment[$i]['writer_url']) ){?>
            <a href="http://<?=$comment[$i]['writer_url']?>" target="_blank"> 
            http://<?=$comment[$i]['writer_url']?>
            </a>
            <?}?>
        </td>
    </tr>
    <tr>
        <td colspan=3> 
            �ۼ��ð� : <?=$comment[$i]['regdate']?> 
            <? if( $comment[$i]['regdate'] != $comment[$i]['moddate'] ) { ?>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            �����ð� : <?=$comment[$i]['moddate']?> 
            <? } ?>
        </td>
    </tr>
    </table>


<? } /* ��α� �� ��� ���� �� */ ?>





<!-- ����¡ ���� -->
<div id=paging><?=$paging?></div>
<!-- ����¡ ���� -->

<script language=javascript>

function whois(ip) {
    whois_win = window.open('whois.php?mb_id=<?=$mb_id?>&query='+ip,'whois_win','width=600,height=500,scrollbars=yes');
    whois_win.focus();
}

function del(id) {
    if( confirm('�ѹ� ������ �ڷ�� ������ ����� �����ϴ�.\n\n���� �����Ͻðڽ��ϱ�?') )
        comment_del(id);
}
function comment_del(id) {
    url   = "comment_update.php";
    send = "mb_id=<?=$current['mb_id']?>";
    send += "&m=delete";
    send += "&comment_id=" + id;

    $.ajax({
        type: 'POST',
        url: url,
        data: send,
        cache: false,
        async: false,
        success: function(result) {
              return_comment_del(result);
            }
        });
}

function return_comment_del(result) {
  
    result      = result.split(',');
    msg_num     = result[0];

    switch (msg_num) {
        case '000': location.reload(); break;
        default:
            alert("������ �߻��Ͽ����ϴ�.\n\n"+result);
    }
}
</script>

<?
include_once("./admin.tail.php");
include_once("{$g4[path]}/tail.sub.php");
?>