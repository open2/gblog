<?
include_once("./_common.php");

$g4['title'] = "GBlog 메인";
include_once("./gblog.head.php");
?>

<table width="100%" cellpadding=0 cellspacing=0>
<tr>
    <td valign=top width=454>

        <!-- 블로그 검색 -->
        <? include_once("gblog.search_form.php") ?>

        <!-- 새로 올라온 글 -->
        <? 
        if ($st && $sv)
            include_once("gblog.search.php");
        else
            include_once("gblog.new_post.php");
        ?>


    </td>
    <td width=20>&nbsp;</td>
    <td valign=top width=210>

        <!-- 신규 블로그 -->
        <? include_once("gblog.new_bloger.php") ?>

        <!-- 글 주제별 분류 시작 -->
        <? include_once("gblog.post_category.php") ?>

    </td>
</tr></table>

<?
include_once("./gblog.tail.php");
?>
