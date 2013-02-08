<?
include_once("./_common.php");

$g4['title'] = "댓글 관리";

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
$sql = "select count(*) as cnt from {$gb4['comment_table']} c where c.blog_id='{$current['id']}' $sql_search";
$total_post = sql_fetch($sql);

$total_post = $total_post['cnt'];
$total_page = (int)($total_post/$page_size) + ($total_post%$page_size==0 ? 0 : 1);
$page_start = $page_size * ( $page - 1 );


// 현재 페이지의 글 정보를 DB 에서 불러온다.
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


// 글 정보를 담을 $comment 변수 초기화
$comment = array();
$index = 0;


while( $res = sql_fetch_array($qry) ) {

    // 일단 DB 에서 가져온 글 정보를 전부 $comment 변수에 담는다.
    $comment[$index] = $res;

    // 댓글에 분류 설정 되어있지 않을경우 '전체' 로 기본 설정해준다.
    if( empty($res['category_name']) ) $comment[$index]['category_name'] = '전체';

    // 댓글 고유 주소를 담는다.
    $comment[$index]['permalink'] = get_comment_url($res['post_id'], $res['id']);

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
    <b>댓글 관리</b> : 블로그에 등록된 댓글을 관리하실 수 있습니다. 최근에 작성된 댓글부터 출력됩니다.
</div>

<form name="searchform" method="get" action="<?=$PHP_SELF?>">
<input type=hidden name=mb_id value="<?=$mb_id?>">
<table border=0 cellpadding=0 cellspacing=0 width=100%>
<tr>
    <td>
        댓글 총 <?=$total_post?> 건.
    </td>
    <td align=right>
        <select name="st">
            <option value="writer_content"<?if($st=='writer_content')echo' selected'?>>내용</option>
            <option value="writer_name"<?if($st=='writer_name')echo' selected'?>>이름</option>
            <option value="writer_email"<?if($st=='writer_email')echo' selected'?>>E-Mail</option>
        </select>
        <input type=text size=10 name=sc value="<?=$sc?>">
        <input type=submit value=검색>
    </td>
</tr>
</table>
</form>


 
<? /* 글이 없을 때  출력 */
if( !count($comment) ) { ?>

    <div style="text-align:center; width:100%; padding-top:30px; padding-bottom:30px; background-color: #efefef;">
            댓글이 존재하지 않습니다.
    </div>

<? } /* 글이 없을 때 출력 끝 */  ?>

<?/* 블로그 글 출력 루프 시작  */
for($i=0; $i<count($comment); $i++) { 

    // 이름 길이를 제한한다.
    $len = 10;
    if( strlen($comment[$i]['writer_name']) > $len )
        $writer_name = cut_str($comment[$i]['writer_name'],$len);
    else
        $writer_name = $comment[$i]['writer_name'];

    // 본문 길이를 제한한다.
    $len = 500;
    if( strlen($comment[$i]['writer_content']) > $len )
        $writer_content = cut_str($comment[$i]['writer_content'],$len);
    else
        $writer_content = $comment[$i]['writer_content'];

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
                    <a href="<?=$comment[$i]['permalink']?>"><b><?=$comment[$i]['title']?></b></a>
                    에 대한
                    <?if($comment[$i]['secret']){?> <font color="red">비밀</font> <?}?> 댓글

                </td>
                <td align=right>
                    <a href="<?=$comment[$i]['permalink']?>">보기</a>,
                    <a href="javascript:del(<?=$comment[$i]['id']?>)">삭제</a>
                </td>
            </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td width=200> 작성자 : <a href="adm_comment_list.php?mb_id=<?=$mb_id?>&sz=1&st=writer_name&sc=<?=$comment[$i]['writer_name']?>"><?=$writer_name?></a> </td>
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
            홈페이지 : 
            <?if( !empty($comment[$i]['writer_url']) ){?>
            <a href="http://<?=$comment[$i]['writer_url']?>" target="_blank"> 
            http://<?=$comment[$i]['writer_url']?>
            </a>
            <?}?>
        </td>
    </tr>
    <tr>
        <td colspan=3> 
            작성시간 : <?=$comment[$i]['regdate']?> 
            <? if( $comment[$i]['regdate'] != $comment[$i]['moddate'] ) { ?>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            수정시간 : <?=$comment[$i]['moddate']?> 
            <? } ?>
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

function del(id) {
    if( confirm('한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?') )
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
            alert("오류가 발생하였습니다.\n\n"+result);
    }
}
</script>

<?
include_once("./admin.tail.php");
include_once("{$g4[path]}/tail.sub.php");
?>