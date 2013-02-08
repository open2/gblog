<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// 페이징 가져오기
//$paging = get_blog_paging(5, $page, $total_page, get_page_uri($REQUEST_URI) );
$paging = get_paging(5, $page, $total_page, get_page_uri($REQUEST_URI) ); 

$paging = str_replace("처음", "<img src='$blog_skin_url/img/btn_list_start.gif' border='0' align='absmiddle' title='처음'>", $paging);
$paging = str_replace("이전", "<img src='$blog_skin_url/img/btn_list_prev.gif' border='0' align='absmiddle' title='이전'>", $paging);
$paging = str_replace("다음", "<img src='$blog_skin_url/img/btn_list_next.gif' border='0' align='absmiddle' title='다음'>", $paging);
$paging = str_replace("맨끝", "<img src='$blog_skin_url/img/btn_list_end.gif' border='0' align='absmiddle' title='맨끝'>", $paging);
$paging = preg_replace("/<b>([0-9]*)<\/b>/", "<span class=\"move_list_spot\">$1</span>", $paging);

$cols = 80;
$rows = 5;

$total_post = number_format($total_post);

if($sql_search)     $search_result = "\"{$search}\" 검색결과 {$total_post}개.";
if($sql_cate)       $search_result = "\"{$cate}\" 분류에 글 총 {$total_post}개.";
if($sql_tag)        $search_result = "\"{$tag}\" 태그에 글 총 {$total_post}개.";
if($sql_monthly)    $search_result = "\"{$mon}\" 에 글 총 {$total_post}개.";
if($sql_cur) {
    if (empty($dd))
        $search_result = "\"{$yyyy}년 {$mm}월\" 에 글 총 {$total_post}개.";
    else
        $search_result = "\"{$yyyy}년 {$mm}월 {$dd}일\" 에 글 총 {$total_post}개.";
}
?>


<? // 글이 없을 때  출력
if( !count($post) ) { ?>

    <div id="entry">


        <table border=0 cellpadding=0 cellspacing=0 width=100%>
            <tr>
                <td width=16 height=16><img src="<?=$blog_skin_url?>/img/cont_top_left.gif" alt="" width="16" height="16"></td>
                <td width=100% height=16 style="background: url(<?=$blog_skin_url?>/img/cont_top.gif) no-repeat right top; padding-left:16px;"></td>
            </tr>
            <tr>
                <td width=16 style="background:url(<?=$blog_skin_url?>/img/cont_left.gif) repeat-y left top;"></td>
                <td width=100% bgcolor="#FFFFFF" style="background:url(<?=$blog_skin_url?>/img/cont_bg_01.gif) repeat-y right top; padding-right:16px;">
                    글이 존재하지 않습니다.
                </td>
            </tr>
            <tr>
                <td width=16 height=16><img src="<?=$blog_skin_url?>/img/cont_btm_left.gif" alt="" width="16" height="16"></td>
                <td width=100% height=16 style="background:url(<?=$blog_skin_url?>/img/cont_btm.gif) no-repeat right bottom;"></td>
            </tr>
        </table>

    </div>

<? }// 글이 없을 때 출력 끝

else { ?>

<div id="entry_box">

<table border=0 cellpadding=0 cellspacing=0 width=100%>
    <tr>
        <td width=16 height=16><img src="<?=$blog_skin_url?>/img/cont_top_left.gif" alt="" width="16" height="16"></td>
        <td width=100% height=16 style="background: url(<?=$blog_skin_url?>/img/cont_top.gif) no-repeat right top; padding-left:16px;"></td>
    </tr>
    <tr>
        <td width=16 style="background:url(<?=$blog_skin_url?>/img/cont_left.gif) repeat-y left top;"></td>
        <td width=100% bgcolor="#FFFFFF" style="background:url(<?=$blog_skin_url?>/img/cont_bg_01.gif) repeat-y right top; padding-right:16px;">

    <div class="search_result">
    <?=$search_result?>
    </div>

<? // 블로그 글 출력 루프 시작
    for($i=0; $i<count($post); $i++) { ?>

    <div id="search_entry">
        <!-- 제목 -->
        <span class="search_datetime"> <?=$post[$i]['post_date']?> </span>
        <span class="search_title"> 
            <a href="<?=$post[$i]['url']?>">
            [<?=$post[$i]['category_name']?>]  <?=$post[$i]['title']?></a> 
            <? if( $post[$i]['secret'] != 1 ) echo "<span class=\"secret\">(비공개)</span>"; ?>
            <span class="count_info">(<?=$post[$i]['comment_count']?>)</span>
        </span>
    </div>




<? }  // 블로그 글 출력 루프 끝 ?>


        </td>
    </tr>
    <tr>
        <td width=16 height=16><img src="<?=$blog_skin_url?>/img/cont_btm_left.gif" alt="" width="16" height="16"></td>
        <td width=100% height=16 style="background:url(<?=$blog_skin_url?>/img/cont_btm.gif) no-repeat right bottom;"></td>
    </tr>
</table>


</div>



<!-- 페이징 -->

<div class="move_list"><?=$paging?></div>


<?}?>

<?if(!$id) $id=0?>
