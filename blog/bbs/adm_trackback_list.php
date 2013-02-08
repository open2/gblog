<?
include_once("./_common.php");

$g4['title'] = "���α� ����";

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
$sql = "select count(*) as cnt from {$gb4['trackback_table']} c where c.blog_id='{$current['id']}' $sql_search";
$total_post = sql_fetch($sql);

$total_post = $total_post['cnt'];
$total_page = (int)($total_post/$page_size) + ($total_post%$page_size==0 ? 0 : 1);
$page_start = $page_size * ( $page - 1 );

// ���� �������� �� ������ DB ���� �ҷ��´�.
$sql = "select
            t.*
            ,p.title
        from
            {$gb4['trackback_table']} t left join {$gb4['post_table']} p on t.post_id=p.id
        where
            t.blog_id='{$current['id']}'
            $sql_search
        order by
            t.regdate desc
        limit
            {$page_start}, {$page_size}";
$qry = sql_query($sql);

// �� ������ ���� $post ���� �ʱ�ȭ
$post = array();
$index = 0;

while( $res = sql_fetch_array($qry) ) {

    // �ϴ� DB ���� ������ �� ������ ���� $trackback ������ ��´�.
    $trackback[$index] = $res;
    $trackback[$index]['permalink'] = get_trackback_url($res['post_id'],$res['id']);
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
    <b>���α� ����</b> : ��α׿� ��ϵ� ���α�(Trackback)�� �����ϴ� ������ �Դϴ�.
</div>

<form name="searchform" method="get" action="<?=$PHP_SELF?>">
<input type=hidden name=mb_id value="<?=$mb_id?>">
<table border=0 cellpadding=0 cellspacing=0 width=100%>
<tr>
    <td>
        ���α� �� <?=$total_post?> ��.
    </td>
    <td align=right>
        <select name="st">
            <option value="writer_content"<?if($st=='writer_content') echo' selected'?>>����</option>
            <option value="writer_name"<?if($st=='writer_name') echo' selected'?>>�̸�</option>
        </select>
        <input type=text size=10 name=sc value="<?=$sc?>">
        <input type=submit value=�˻�>
    </td>
</tr>
</table>
</form>


 
<? /* ���� ���� ��  ��� */
if( !count($trackback) ) { ?>

    <div style="text-align:center; width:100%; padding-top:30px; padding-bottom:30px; background-color: #efefef;">
            ���α��� �������� �ʽ��ϴ�.
    </div>

<? } /* ���� ���� �� ��� �� */  ?>

<?/* ��α� �� ��� ���� ����  */
for($i=0; $i<count($trackback); $i++) { 

    // �̸� ���̸� �����Ѵ�.
    $len = 10;
    if( strlen($trackback[$i]['writer_name']) > $len )
        $writer_name = cut_str($trackback[$i]['writer_name'],$len);
    else
        $writer_name = $trackback[$i]['writer_name'];

    // ���� ���̸� �����Ѵ�.
    $len = 500;
    if( strlen($trackback[$i]['writer_content']) > $len )
        $writer_content = cut_str($trackback[$i]['writer_content'],$len);
    else
        $writer_content = $trackback[$i]['writer_content'];

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
                    <a href="<?=$trackback[$i]['permalink']?>"><b><?=$trackback[$i]['title']?></b></a> �� ���� ���α�
                </td>
                <td align=right>
                    <a href="<?=$trackback[$i]['permalink']?>">����</a>,
                    <a href="javascript:trackback_del(<?=$trackback[$i]['id']?>,<?=$trackback[$i]['post_id']?>)">����</a>
                </td>
            </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td width=200> �ۼ��� : <a href="adm_trackback_list.php?mb_id=<?=$mb_id?>&sz=1&st=writer_name&sc=<?=$trackback[$i]['writer_name']?>"><?=$writer_name?></a> </td>
        <td width=150> IP : <a href="javascript:whois('<?=$trackback[$i]['writer_ip']?>')"><?=$trackback[$i]['writer_ip']?></a> </td>
        <td> &nbsp; </td>
    </tr>
    <tr>
        <td colspan=3 style="word-break:break-all; overflow:auto; background-color:#fff; padding:20px;">  
            <?=$writer_content?> 
        </td>
    </tr>
    <tr>
        <td colspan=3>
            ���α� �ּ�  : 
            <a href="http://<?=$trackback[$i]['writer_url']?>" target="_blank"> 
            http://<?=$trackback[$i]['writer_url']?>
            </a>
        </td>
    </tr>
    <tr>
        <td colspan=3> 
            ��Ͻð� : <?=$trackback[$i]['regdate']?> 
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

function trackback_del(trackback_id, post_id) {
    if( !confirm('�ѹ� ������ �ڷ�� ������ ����� �����ϴ�.\n\n���� �����Ͻðڽ��ϱ�?') ) 
        return;

    url   = "adm_trackback_delete.php";
    send = "mb_id=<?=$current['mb_id']?>";
    send += "&trackback_id=" + trackback_id;
    send += "&post_id=" + post_id;

    $.ajax({
        type: 'POST',
        url: url,
        data: send,
        cache: false,
        async: false,
        success: function(result) {
              return_trackback_del(result);
            }
        });
}

function return_trackback_del(result) {

    result      = result.split(',');
    msg_num     = result[0];

    switch (msg_num) {
        case '101' : alert('�ڽ��� ��α׸� ������ �� �ֽ��ϴ�.'); break;
        case '109' : alert('mb_id �� �����ϴ�.'); break;
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