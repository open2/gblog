<?
include_once("./_common.php");

$g4['title'] = $current[blog_name] . "-블로그 글 관리";

// get mb_id, blog_id
$mb_id = $member['mb_id'];
$blog_id = $current[id];
if (!$blog_id || $current['mb_id'] != $mb_id)
    alert('자신의 블로그만 관리할 수 있습니다.');

// 페이지 번호 초기화
if (isset($page))
    $page = (int)$page; 
else
    $page = 1;

$page_size = (int)$gb4[gb_page_rows];

// 분류 검색
if (!empty($cate))
{
    // 분류이름 앞뒤 공백 제거
    $cate = trim($cate);

    // 분류이름으로 분류 고유번호를 가져온다.
    $res  = sql_fetch("select id from {$gb4['category_table']} where category_name = '{$cate}'");

    // 분류 고유번호로 검색 쿼리를 만들어 낸다.
    if (empty($res))
        $sql_cate = " and null ";
    else
        $sql_cate = " and p.category_id = '{$res['id']}' ";
}


// 페이징을 위한 값 로드
$sql = "select count(*) as cnt from {$gb4['post_table']} p where p.blog_id='{$current['id']}' $sql_cate $sql_tag";
$total_post = sql_fetch($sql);

$total_post = $total_post['cnt'];
$total_page = (int)($total_post/$page_size) + ($total_post%$page_size==0 ? 0 : 1);
$page_start = $page_size * ( $page - 1);

// 현재 페이지의 글 정보를 DB 에서 불러온다.
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


// 글 정보를 담을 $post 변수 초기화
$post = array();
$index = 0;

while( $res = sql_fetch_array($qry)) {

    // 일단 DB 에서 가져온 글 정보를 전부 $post 변수에 담는다.
    $post[$index] = $res;

    // 글에 분류 설정 되어있지 않을경우 '분류없음' 로 기본 설정해준다.
    if (empty($res['category_name'])) $post[$index]['category_name'] = '<font color=silver>분류없음</font>';

    // 글 고유주소 가져오기
    $post[$index]['url'] = get_post_url($res['id']);

    $index++;
}

// 페이징 가져오기
$paging = get_paging($page_size, $page, $total_page, "?mb_id={$current['mb_id']}&tag={$tag}&cate={$cate}&page=");

$cg = array();
$q = sql_query("select * from {$gb4['category_table']} where blog_id='{$current['id']}' order by rank desc");
while($r = sql_fetch_array($q)) array_push($cg, $r);

include_once("{$g4[path]}/head.sub.php");
include_once("./admin.head.php");

?>

<div class="adm_info">
    <b>글 관리</b> : 작성하신 글을 관리하는 페이지 입니다.
</div>

<table border=0 cellpadding=0 cellspacing=1 width=100% bgcolor="#cccccc">
    <tbody bgcolor="#ffffff">
    <tr height=25 align=center bgcolor="#efefef">
        <td width=30> <input type=checkbox onclick="all_click()" title="전체선택" alt="전체선택"> </td>
        <td width=100> 분류 </td>
        <td> 제목 </td>
        <td width=80> 등록일자 </td>
        <td width=50> 댓글 </td>
        <td width=50> 엮인글 </td>
        <td width=50> 조회수 </td>
    </tr>


<? /* 글이 없을 때  출력 */
    if (!count($post)) { ?>
    <tr>
        <td height=50 colspan=7 align=center>
            글이 존재하지 않습니다.
        </td>
    </tr>

<? } /* 글이 없을 때 출력 끝 */?>


<?  /* 블로그 글 출력 루프 시작  */
    for($i=0; $i<count($post); $i++) { ?>

    <?
    // 제목 길이를 제한한다.
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
            <a href="<?=$post[$i]['url']?>" style="font-size:0.9em; color:#ccc;">[보기]</a>
        </td>
        <td align=center><?=substr($post[$i]['post_date'],0,10)?></td>
        <td align=right><?=number_format($post[$i]['comment_count'])?> 개&nbsp;&nbsp;</td>
        <td align=right><?=number_format($post[$i]['trackback_count'])?> 개&nbsp;&nbsp;</td>
        <td align=right><?=number_format($post[$i]['hit'])?> &nbsp;&nbsp;</td>
    </tr>

<? } /* 블로그 글 출력 루프 끝 */ ?>



</table>

<table width=100% border=0 cellpadding=0 cellspacing=0 style="margin-top:10px;">
<tr>
    <td> 
        선택한 글을
        <select id="check_list" onchange="check_change()" align="absmiddle">
        <option>어떻게 할까요?</option>
        <option>--------------------------------------------------</option>
        <? for ($i=0; $i<count($cg); $i++) { ?>
        <option value="<?=$cg[$i]['id']?>">"<?=$cg[$i]['category_name']?>" 분류로 이동합니다.</option>
        <? } ?>
        <option>--------------------------------------------------</option>
        <option value="open">공개 합니다.</option>
        <option value="secret">비공개로 변경합니다.</option>
        <option>--------------------------------------------------</option>
        <option value="del" style="color:red;">삭제 합니다.</option>
        </select>

    </td>
    <td align=right>
    </td>
</tr>
</table>

<!-- 페이징 시작 -->
<div id=paging><?=$paging?></div>
<!-- 페이징 종료 -->

<script language=javascript>
/* 글 수정 페이지로 이동하는 함수 */
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
        alert('글을 선택해주세요.'); 
        chk[0].selected = true; 
        return; 
    }

    if (mode=='del') {
        if (!confirm('한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?')) 
            return;
    } else {
        if (!confirm('정말 변경하시겠습니까?')) 
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
        case '001': alert('본인의 블로그만 관리할 수 있습니다.'); return; break;
        case '002': alert('존재하지 않는 글 입니다.'); return; break;
        case '000': location.reload(); break;
        default:
            alert("오류가 발생하였습니다.\n\n"+res);
    }
}
</script>

<?
include_once("./admin.tail.php");
include_once("{$g4[path]}/tail.sub.php");
?>