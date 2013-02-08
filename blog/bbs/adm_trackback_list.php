<?
include_once("./_common.php");

$g4['title'] = "엮인글 관리";

if( $current['mb_id'] != $member['mb_id'] )
    alert('자신의 블로그만 관리할 수 있습니다.');

$page_size = 10;

// 페이지 번호 초기화
if( empty($page) )
    $page = 1;
else
    $page = (int)$page;

// 검색
if( !empty($st) && !empty($sc) )
    if( $sz == 1 )
        $sql_search = " and $st = '$sc' ";
    else
        $sql_search = " and $st like '%$sc%' ";
else
    $sql_search = "";

// 페이징을 위한 값 로드
$sql = "select count(*) as cnt from {$gb4['trackback_table']} c where c.blog_id='{$current['id']}' $sql_search";
$total_post = sql_fetch($sql);

$total_post = $total_post['cnt'];
$total_page = (int)($total_post/$page_size) + ($total_post%$page_size==0 ? 0 : 1);
$page_start = $page_size * ( $page - 1 );

// 현재 페이지의 글 정보를 DB 에서 불러온다.
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

// 글 정보를 담을 $post 변수 초기화
$post = array();
$index = 0;

while( $res = sql_fetch_array($qry) ) {

    // 일단 DB 에서 가져온 글 정보를 전부 $trackback 변수에 담는다.
    $trackback[$index] = $res;
    $trackback[$index]['permalink'] = get_trackback_url($res['post_id'],$res['id']);
    $index++;
}

// 페이징 가져오기
$paging = get_blog_paging(10, $page, $total_page, "?mb_id={$current['mb_id']}&st={$st}&sc={$sc}&page=");
$paging = str_replace("<null>","<span class='paging'>", $paging);
$paging = str_replace("</null>","</span>", $paging);
$paging = str_replace("<a","<a class='paging'", $paging);

include_once("{$g4[path]}/head.sub.php");
include_once("./admin.head.php");
?>

<div class="adm_info">
    <b>엮인글 관리</b> : 블로그에 등록된 엮인글(Trackback)을 관리하는 페이지 입니다.
</div>

<form name="searchform" method="get" action="<?=$PHP_SELF?>">
<input type=hidden name=mb_id value="<?=$mb_id?>">
<table border=0 cellpadding=0 cellspacing=0 width=100%>
<tr>
    <td>
        엮인글 총 <?=$total_post?> 건.
    </td>
    <td align=right>
        <select name="st">
            <option value="writer_content"<?if($st=='writer_content') echo' selected'?>>내용</option>
            <option value="writer_name"<?if($st=='writer_name') echo' selected'?>>이름</option>
        </select>
        <input type=text size=10 name=sc value="<?=$sc?>">
        <input type=submit value=검색>
    </td>
</tr>
</table>
</form>


 
<? /* 글이 없을 때  출력 */
if( !count($trackback) ) { ?>

    <div style="text-align:center; width:100%; padding-top:30px; padding-bottom:30px; background-color: #efefef;">
            엮인글이 존재하지 않습니다.
    </div>

<? } /* 글이 없을 때 출력 끝 */  ?>

<?/* 블로그 글 출력 루프 시작  */
for($i=0; $i<count($trackback); $i++) { 

    // 이름 길이를 제한한다.
    $len = 10;
    if( strlen($trackback[$i]['writer_name']) > $len )
        $writer_name = cut_str($trackback[$i]['writer_name'],$len);
    else
        $writer_name = $trackback[$i]['writer_name'];

    // 본문 길이를 제한한다.
    $len = 500;
    if( strlen($trackback[$i]['writer_content']) > $len )
        $writer_content = cut_str($trackback[$i]['writer_content'],$len);
    else
        $writer_content = $trackback[$i]['writer_content'];

    // 배경색
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
                    <a href="<?=$trackback[$i]['permalink']?>"><b><?=$trackback[$i]['title']?></b></a> 에 대한 엮인글
                </td>
                <td align=right>
                    <a href="<?=$trackback[$i]['permalink']?>">보기</a>,
                    <a href="javascript:trackback_del(<?=$trackback[$i]['id']?>,<?=$trackback[$i]['post_id']?>)">삭제</a>
                </td>
            </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td width=200> 작성자 : <a href="adm_trackback_list.php?mb_id=<?=$mb_id?>&sz=1&st=writer_name&sc=<?=$trackback[$i]['writer_name']?>"><?=$writer_name?></a> </td>
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
            엮인글 주소  : 
            <a href="http://<?=$trackback[$i]['writer_url']?>" target="_blank"> 
            http://<?=$trackback[$i]['writer_url']?>
            </a>
        </td>
    </tr>
    <tr>
        <td colspan=3> 
            등록시간 : <?=$trackback[$i]['regdate']?> 
        </td>
    </tr>
    </table>


<? } /* 블로그 글 출력 루프 끝 */ ?>





<!-- 페이징 시작 -->
<div id=paging><?=$paging?></div>
<!-- 페이징 종료 -->

<script language=javascript>

function whois(ip) {
    whois_win = window.open('whois.php?mb_id=<?=$mb_id?>&query='+ip,'whois_win','width=600,height=500,scrollbars=yes');
    whois_win.focus();
}

function trackback_del(trackback_id, post_id) {
    if( !confirm('한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?') ) 
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
        case '101' : alert('자신의 블로그만 접근할 수 있습니다.'); break;
        case '109' : alert('mb_id 가 없습니다.'); break;
        case '000': location.reload(); break;
        default:
            alert("오류가 발생하였습니다.\n\n"+result);
    }
}


</script>

<?
include_once("./admin.tail.php");
include_once("{$g4[path]}/tail.sub.php");
?>