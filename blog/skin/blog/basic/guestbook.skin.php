<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
?>

<div id="entry">
<table border=0 cellpadding=0 cellspacing=0 width=100%>
    <tr>
        <td width=16 height=16><img src="<?=$blog_skin_url?>/img/cont_top_left.gif" alt="" width="16" height="16"></td>
        <td width=100% height=16 style="background: url(<?=$blog_skin_url?>/img/cont_top.gif) no-repeat right top; padding-left:16px;"></td>
    </tr>
    <tr>
        <td width=16 style="background:url(<?=$blog_skin_url?>/img/cont_left.gif) repeat-y left top;"></td>
        <td width=100% bgcolor="#FFFFFF" style="background:url(<?=$blog_skin_url?>/img/cont_bg_01.gif) repeat-y right top; padding-right:16px;">

            <div id="guestbook_list"><?=$guestbook?></div>

        </td>
    </tr>
    <tr>
        <td width=16 height=16><img src="<?=$blog_skin_url?>/img/cont_btm_left.gif" alt="" width="16" height="16"></td>
        <td width=100% height=16 style="background:url(<?=$blog_skin_url?>/img/cont_btm.gif) no-repeat right bottom;"></td>
    </tr>
</table>
</div>

<?if(!$id) $id=0?>
