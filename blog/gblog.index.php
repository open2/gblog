<?
include_once("./_common.php");

$g4['title'] = "GBlog ����";
include_once("./gblog.head.php");
?>

<table width="100%" cellpadding=0 cellspacing=0>
<tr>
    <td valign=top width=454>

        <!-- ��α� �˻� -->
        <? include_once("gblog.search_form.php") ?>

        <!-- ���� �ö�� �� -->
        <? 
        if ($st && $sv)
            include_once("gblog.search.php");
        else
            include_once("gblog.new_post.php");
        ?>


    </td>
    <td width=20>&nbsp;</td>
    <td valign=top width=210>

        <!-- �ű� ��α� -->
        <? include_once("gblog.new_bloger.php") ?>

        <!-- �� ������ �з� ���� -->
        <? include_once("gblog.post_category.php") ?>

    </td>
</tr></table>

<?
include_once("./gblog.tail.php");
?>
