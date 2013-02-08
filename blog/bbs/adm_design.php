<?
include_once("./_common.php");

$g4['title'] = "블로그 디자인 설정";

if ($member['mb_id'] != $current['mb_id'] )
    alert('자신의 블로그만 디자인 설정을 할 수 있습니다.');

// 블로그 기본정보 로드
$r = sql_fetch("select * from {$gb4['blog_table']} where mb_id='{$member['mb_id']}'");

if (empty($r)) 
    alert('존재하지 않는 블로그 입니다.');

extract($r);

$skins = array();
$sql = "select * from {$gb4['skin_table']} where used=1 order by id";
$qry = sql_query($sql);
while( $res = sql_fetch_array($qry)) {
    array_push($skins, $res);
    if (!$skin_id ) {
        if($res['skin'] == 'basic') {
            $skin_id = $res['id'];
        }
    }
}

if (empty($sidebar_post_num)) $sidebar_post_num = 5;
if (empty($sidebar_comment_num)) $sidebar_comment_num = 5;
if (empty($sidebar_trackback_num)) $sidebar_trackback_num = 5;

if (empty($sidebar_post_length)) $sidebar_post_length = 30;
if (empty($sidebar_comment_length)) $sidebar_comment_length = 30;
if (empty($sidebar_trackback_length)) $sidebar_trackback_length = 30;

// top_image
$top_image = "{$g4[path]}/data/blog/top_image/{$mb_id}";

// background_image 고정
$background_image = "{$g4[path]}/data/blog/background_image/{$mb_id}";

// background_repeat 반복
$background_repeat = "{$g4[path]}/data/blog/background_repeat/{$mb_id}";

// stylesheet
$stylesheet = "{$g4[path]}/data/blog/stylesheet/{$mb_id}.css";


// 스킨 호출
include_once("{$gb4[path]}/head.sub.php");
include_once("./admin.head.php");
?>

<form name=form_skin method=post action="adm_design_update.php" enctype="multipart/form-data" autocomplete="off">
<input type=hidden name=mb_id value="<?=$member['mb_id']?>">
<input type=hidden name=url value="<?=$urlencode?>">
<input type=hidden name=mode value="1">

<table border=0 cellpadding=5 cellspacing=1 width=100% bgcolor="#cccccc">
<tbody  bgcolor="#ffffff">
<tr>
    <td width=150 bgcolor="#efefef" style="padding-left:20px">
        스킨 선택
    </td>
    <td style="padding-left:20px">
        <table border=0 cellpadding=0 cellspacing=0>
        <tr>
            <td width=100>
                <select id="skin_id" name="skin_id" onChange="preview_skin()">
                <?for($i=0; $i<count($skins); $i++) {?>
                <option value="<?=$skins[$i]['id']?>" <? if($skin_id==$skins[$i]['id']) echo ' selected'; ?> > <?=$skins[$i]['skin']?> </option>
                <?}?>
                </select>
                <br>
                <br>
                <input type=button value="미리보기" onclick="blog_skin_preview()">
            </td>
            <td width=200 align=right>
                <img id="preview_skin_image" width=200 height=160 style="border:1px solid #ccc">
            </td>
        </tr>
        </table>
    </td>
</tr>
<tr>
    <td bgcolor="#efefef" style="padding-left:20px">
        블로그 정렬
    </td>
    <td style="padding-left:20px">
        <input type="radio" name="blog_align" value="left"<?if($blog_align=='left') echo ' checked'?>> 왼쪽
        <input type="radio" name="blog_align" value="center"<?if($blog_align=='center') echo ' checked'?>> 중앙
        <input type="radio" name="blog_align" value="right"<?if($blog_align=='right') echo ' checked'?>> 오른쪽
    </td>
</tr>
<tr>
    <td bgcolor="#efefef" style="padding-left:20px">
        상단 이미지
    </td>
    <td style="padding-left:20px">
        <?if (file_exists($top_image)) { ?>
        <br>기존 상단 이미지를 삭제하시겠습니까? <input type="checkbox" name="top_image_del"> <br><br>
        <?} ?> 
        
        <input type="file" size="30" name="top_image"> <br>

        가로 800 x 세로 100 픽셀, <?=get_filesize($gb4['top_image_size'])?> 까지만 업로드 가능합니다.
    </td>
</tr>
<tr>
    <td bgcolor="#efefef" style="padding-left:20px">
        고정 배경 이미지
    </td>
    <td style="padding-left:20px">
        <?if (file_exists($background_image)) { ?>
        <br>기존 배경 이미지(고정)를 삭제하시겠습니까? <input type="checkbox" name="background_image_del"> <br><br>
        <?} ?> 
        
        <input type="file" size="30" name="background_image"> <br>

        GIF, JPG, PNG 파일만 업로드 가능합니다. <?=get_filesize($gb4['background_image_size'])?> 까지만 업로드 가능합니다.
    </td>
</tr>
<tr>
    <td bgcolor="#efefef" style="padding-left:20px">
        반복 배경 이미지
    </td>
    <td style="padding-left:20px">
        <?if (file_exists($background_repeat)) { ?>
        <br>기존 배경 이미지(반복)를 삭제하시겠습니까? <input type="checkbox" name="background_repeat_del"> <br><br>
        <?} ?> 
        
        <input type="file" size="30" name="background_repeat"> <br>

        GIF, JPG, PNG 파일만 업로드 가능합니다. <?=get_filesize($gb4['background_image_size'])?> 까지만 업로드 가능합니다.
    </td>
</tr>
<tr>
    <td bgcolor="#efefef" style="padding-left:20px">
        사용자 StyleSheet
    </td>
    <td style="padding-left:20px">
        <?if (file_exists($stylesheet)) { ?>
        <br> 삭제하시겠습니까? <input type="checkbox" name="stylesheet_del"> <br><br>
        <?} ?> 
        
        <input type="file" size="30" name="stylesheet"> <br>
        CSS 파일만 업로드 가능합니다. <?=get_filesize(50*1024)?> 까지만 업로드 가능합니다.
    </td>
</tr>

<tr>
    <td width=150 bgcolor="#efefef" style="padding-left:20px">페이지별 글 출력 수</td>
    <td style="padding-left:20px">
        <input type=text name=page_count value="<?=$page_count?>" size=10 numeric required itemname="페이지별 글 출력 수"> 개
    </td>
</tr>
<tr>
    <td bgcolor="#efefef" style="padding-left:20px">목록별 글 출력 수</td>
    <td style="padding-left:20px">
        <input type=text name=list_count value="<?=$list_count?>" size=10 numeric required itemname="목록별 글 출력 수"> 개
    </td>
</tr>
<tr>
    <td bgcolor="#efefef" style="padding-left:20px">최상단 메뉴 색상</td>
    <td style="padding-left:20px">
        <input type=text name=top_menu_color value="<?=$top_menu_color?>" size=10 required itemname="최상단 메뉴 색상">
    </td>
</tr>
<tr>
    <td bgcolor="#efefef" style="padding-left:20px">포스트 영역</td>
    <td style="padding-left:20px">
        <select name=blog_width>
        <option value=0 <? if ($blog_width == 0) echo 'selected'?>>좁게 
        <option value=1 <? if ($blog_width == 1) echo 'selected'?>>넓게 (2단인 경우에만)
        </select>
    </td>
</tr>
<tr>
    <td bgcolor="#efefef" style="padding-left:20px">첨부이미지 가로크기</td>
    <td style="padding-left:20px">
        <input type=text name=image_width value="<?=$image_width?>" size=10 numeric required itemname="첨부이미지 가로크기">
    </td>
</tr>

</table>

<p align=center>
    <INPUT type=image width="66" height="20" src="img/ok_btn.gif" border=0 accesskey='s'>
</p>

<table border=0 cellpadding=5 cellspacing=1 width=100% bgcolor="#cccccc">
<tbody  bgcolor="#ffffff">
<!--
<tr>
    <td width=150 bgcolor="#efefef" style="padding-left:20px">
        사이드바
    </td>
    <td>
        <table border=0 cellpadding=0 cellspacing=0 width=95% align=center>
        <tr>
            <td valign=top width=50%>
                <input type="checkbox" name="sidebar_category"<?if($sidebar_category) echo ' checked';?>> 분류 <br>
                <input type="checkbox" name="sidebar_monthly"<?if($sidebar_monthly) echo ' checked';?>> 월별목록 <br>
                <input type="checkbox" name="sidebar_calendar"<?if($sidebar_calendar) echo ' checked';?>> 달력 <br>
                <input type="checkbox" name="sidebar_tag"<?if($sidebar_tag) echo ' checked';?>> 태그 <br>
                <input type="checkbox" name="sidebar_link"<?if($sidebar_link) echo ' checked';?>> 링크 <br>
                <input type="checkbox" name="sidebar_post"<?if($sidebar_post) echo ' checked';?>> 최신글 <br>
                <input type="checkbox" name="sidebar_comment"<?if($sidebar_comment) echo ' checked';?>> 최신댓글 <br>
                <input type="checkbox" name="sidebar_trackback"<?if($sidebar_trackback) echo ' checked';?>> 최신엮인글 <br>
                <input type="checkbox" name="sidebar_search"<?if($sidebar_search) echo ' checked';?>> 검색 <br>
                <input type="checkbox" name="sidebar_visit"<?if($sidebar_visit) echo ' checked';?>> 카운터 <br>
            </td>
            <td valign=top width=50%>
                <input type="checkbox" name="sidebar_use1"<?if($sidebar_use1) echo ' checked';?>> 사용자정의1 <br>
                <input type="checkbox" name="sidebar_use2"<?if($sidebar_use2) echo ' checked';?>> 사용자정의2 <br>
                <input type="checkbox" name="sidebar_use3"<?if($sidebar_use3) echo ' checked';?>> 사용자정의3 <br>
                <input type="checkbox" name="sidebar_use4"<?if($sidebar_use4) echo ' checked';?>> 사용자정의4 <br>
                <input type="checkbox" name="sidebar_use5"<?if($sidebar_use5) echo ' checked';?>> 사용자정의5 <br>
            </td>
        </tr>
        </table>
    </td>
</tr>
-->
<tr>
    <td width=150 bgcolor="#efefef" style="padding-left:20px">
        사이드바 출력설정
    </td>
    <td>
        <table border=0 cellpadding=0 cellspacing=0 width=500>
        <tr>
            <td width=250 align=right>
                최신글 목록 갯수 
                <input type="text" size="5" name="sidebar_post_num" required numeric itemname="최신글 목록 갯수" value="<?=$sidebar_post_num?>"> 개
            </td>
            <td width=250 align=right>
                최신글 글자수 제한 
                <input type="text" size="5" name="sidebar_post_length" required lengtheric itemname="최신글 글자수" value="<?=$sidebar_post_length?>"> 글자 
            </td>
        </tr>
        <tr>
            <td align=right>
                댓글 목록 갯수 
                <input type="text" size="5" name="sidebar_comment_num" required numeric itemname="댓글 목록 갯수" value="<?=$sidebar_comment_num?>"> 개 
            </td>
            <td align=right>
                댓글 글자수 제한 
                <input type="text" size="5" name="sidebar_comment_length" required lengtheric itemname="댓글 글자수" value="<?=$sidebar_comment_length?>"> 글자 
            </td>
        </tr>
        <tr>
            <td align=right>
                엮인글 목록 갯수 
                <input type="text" size="5" name="sidebar_trackback_num" required numeric itemname="엮인글 목록 갯수" value="<?=$sidebar_trackback_num?>"> 개 
            </td>
            <td align=right>
                엮인글 글자수 제한 
                <input type="text" size="5" name="sidebar_trackback_length" required lengtheric itemname="엮인글 글자수" value="<?=$sidebar_trackback_length?>"> 글자 
            </td>
        </tr>
        <tr>
            <td style="padding-top:10px;" colspan=2 align=right>
                태그 출력 방법
                <select name="sidebar_tag_print">
                <option value="1"<?if($sidebar_tag_print==1) echo ' selected';?>> 인기 태그 </option>
                <option value="2"<?if($sidebar_tag_print==2) echo ' selected';?>> 최근 태그 </option>
                </select>
                &nbsp;&nbsp;
                태그 출력 갯수
                <input type="text" size="5" name="sidebar_tag_length" required lengtheric itemname="태그 출력 갯수" value="<?=$sidebar_tag_length?>"> 개
                &nbsp;&nbsp;
                태그 레벨 간격 
                <input type="text" size="5" name="sidebar_tag_gap" required lengtheric itemname="태그 레벨" value="<?=$sidebar_tag_gap?>"> 
            </td>
        </tr>
        </table>
    </td>
</tr>
</table>

<p align=center>
    <INPUT type=image width="66" height="20" src="img/ok_btn.gif" border=0 accesskey='s'>
</p>

<table border=0 cellpadding=5 cellspacing=1 width=100% bgcolor="#cccccc">
<tbody  bgcolor="#ffffff">
<tr>
    <td width=150 bgcolor="#efefef" style="padding-left:20px">블로그 상단</td>
    <td>
        <textarea name="blog_head" style="width:100%" rows="5"><?=$blog_head?></textarea>
    </td>
</tr>
<tr>
    <td bgcolor="#efefef" style="padding-left:20px">블로그 하단</td>
    <td>
        <textarea name="blog_tail" style="width:100%" rows="5"><?=$blog_tail?></textarea>
    </td>
</tr>
<tr>
    <td bgcolor="#efefef" style="padding-left:20px">본문 상단</td>
    <td>
        <textarea name="content_head" style="width:100%" rows="5"><?=$content_head?></textarea>
    </td>
</tr>
<tr>
    <td bgcolor="#efefef" style="padding-left:20px">본문 하단</td>
    <td>
        <textarea name="content_tail" style="width:100%" rows="5"><?=$content_tail?></textarea>
    </td>
</tr>
</table>

<p align=center>
    <INPUT type=image width="66" height="20" src="img/ok_btn.gif" border=0 accesskey='s'>
</p>


<table border=0 cellpadding=5 cellspacing=1 width=100% bgcolor="#cccccc">
<tbody  bgcolor="#ffffff">
<tr>
    <td width=150 bgcolor="#efefef" style="padding-left:20px">
        사용자 정의 사이드바 1
    </td>
    <td>
        <input type=text name="sidebar_user1_title" style="width:100%" value="<?=$sidebar_user1_title?>">
        <textarea name="sidebar_user1_content" style="width:100%" rows=5><?=$sidebar_user1_content?></textarea>
    </td>
</tr>
<tr>
    <td bgcolor="#efefef" style="padding-left:20px">
        사용자 정의 사이드바 2
    </td>
    <td>
        <input type=text name="sidebar_user2_title" style="width:100%" value="<?=$sidebar_user2_title?>">
        <textarea name="sidebar_user2_content" style="width:100%" rows=5><?=$sidebar_user2_content?></textarea>
    </td>
</tr>
<tr>
    <td bgcolor="#efefef" style="padding-left:20px">
        사용자 정의 사이드바 3
    </td>
    <td>
        <input type=text name="sidebar_user3_title" style="width:100%" value="<?=$sidebar_user3_title?>">
        <textarea name="sidebar_user3_content" style="width:100%" rows=5><?=$sidebar_user3_content?></textarea>
    </td>
</tr>
<tr>
    <td bgcolor="#efefef" style="padding-left:20px">
        사용자 정의 사이드바 4
    </td>
    <td>
        <input type=text name="sidebar_user4_title" style="width:100%" value="<?=$sidebar_user4_title?>">
        <textarea name="sidebar_user4_content" style="width:100%" rows=5><?=$sidebar_user4_content?></textarea>
    </td>
</tr>
<tr>
    <td bgcolor="#efefef" style="padding-left:20px">
        사용자 정의 사이드바 5
    </td>
    <td>
        <input type=text name="sidebar_user5_title" style="width:100%" value="<?=$sidebar_user5_title?>">
        <textarea name="sidebar_user5_content" style="width:100%" rows=5><?=$sidebar_user5_content?></textarea>
    </td>
</tr>
</table>




<p align=center>
    <INPUT type=image width="66" height="20" src="img/ok_btn.gif" border=0 accesskey='s'>
</p>

</form>


<script language=javascript>

preview_skin();

function preview_skin() {
    index = document.getElementById("skin_id").selectedIndex;
    text = document.getElementById("skin_id").options[index].text;
    image = document.getElementById("preview_skin_image");
    image.src = "<?=$gb4[path]?>/skin/blog/"+text+"/img/preview.gif";
    image.style.width = "200";
    image.style.height = "160";
}
function blog_skin_preview() {
    index = document.getElementById("skin_id").selectedIndex;
    text = document.getElementById("skin_id").options[index].text;
    window.open("<?=get_preview_url()?>"+text);
}
</script>



<?
include_once("./admin.tail.php");
include_once("{$gb4[path]}/tail.sub.php");
?>