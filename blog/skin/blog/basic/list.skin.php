<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ�

// ����¡ ��������
//$paging = get_blog_paging(5, $page, $total_page, get_page_uri($REQUEST_URI) );
$paging = get_paging(5, $page, $total_page, get_page_uri($REQUEST_URI) ); 

$paging = str_replace("ó��", "<img src='$blog_skin_url/img/btn_list_start.gif' border='0' align='absmiddle' title='ó��'>", $paging);
$paging = str_replace("����", "<img src='$blog_skin_url/img/btn_list_prev.gif' border='0' align='absmiddle' title='����'>", $paging);
$paging = str_replace("����", "<img src='$blog_skin_url/img/btn_list_next.gif' border='0' align='absmiddle' title='����'>", $paging);
$paging = str_replace("�ǳ�", "<img src='$blog_skin_url/img/btn_list_end.gif' border='0' align='absmiddle' title='�ǳ�'>", $paging);
$paging = preg_replace("/<b>([0-9]*)<\/b>/", "<span class=\"move_list_spot\">$1</span>", $paging);

$cols = 80;
$rows = 5;

$total_post = number_format($total_post);

if($sql_search)     $search_result = "\"{$search}\" �˻���� {$total_post}��.";
if($sql_cate)       $search_result = "\"{$cate}\" �з��� �� �� {$total_post}��.";
if($sql_tag)        $search_result = "\"{$tag}\" �±׿� �� �� {$total_post}��.";
if($sql_monthly)    $search_result = "\"{$mon}\" �� �� �� {$total_post}��.";
if($sql_cur) {
    if (empty($dd))
        $search_result = "\"{$yyyy}�� {$mm}��\" �� �� �� {$total_post}��.";
    else
        $search_result = "\"{$yyyy}�� {$mm}�� {$dd}��\" �� �� �� {$total_post}��.";
}
?>


<? // ���� ���� ��  ���
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
                    ���� �������� �ʽ��ϴ�.
                </td>
            </tr>
            <tr>
                <td width=16 height=16><img src="<?=$blog_skin_url?>/img/cont_btm_left.gif" alt="" width="16" height="16"></td>
                <td width=100% height=16 style="background:url(<?=$blog_skin_url?>/img/cont_btm.gif) no-repeat right bottom;"></td>
            </tr>
        </table>

    </div>

<? }// ���� ���� �� ��� ��

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

<? // ��α� �� ��� ���� ����
    for($i=0; $i<count($post); $i++) { ?>

    <div id="search_entry">
        <!-- ���� -->
        <span class="search_datetime"> <?=$post[$i]['post_date']?> </span>
        <span class="search_title"> 
            <a href="<?=$post[$i]['url']?>">
            [<?=$post[$i]['category_name']?>]  <?=$post[$i]['title']?></a> 
            <? if( $post[$i]['secret'] != 1 ) echo "<span class=\"secret\">(�����)</span>"; ?>
            <span class="count_info">(<?=$post[$i]['comment_count']?>)</span>
        </span>
    </div>




<? }  // ��α� �� ��� ���� �� ?>


        </td>
    </tr>
    <tr>
        <td width=16 height=16><img src="<?=$blog_skin_url?>/img/cont_btm_left.gif" alt="" width="16" height="16"></td>
        <td width=100% height=16 style="background:url(<?=$blog_skin_url?>/img/cont_btm.gif) no-repeat right bottom;"></td>
    </tr>
</table>


</div>



<!-- ����¡ -->

<div class="move_list"><?=$paging?></div>


<?}?>

<?if(!$id) $id=0?>
