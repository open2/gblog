<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ�

include_once("$gb4[path]/head.sub.php");

include_once("$g4[path]/lib/outlogin.lib.php");
include_once("$g4[path]/lib/poll.lib.php");
include_once("$g4[path]/lib/visit.lib.php");
include_once("$g4[path]/lib/connect.lib.php");
include_once("$g4[path]/lib/popular.lib.php");

//print_r2(get_defined_constants());

// ����� ȭ�� ��ܰ� ������ ����ϴ� �������Դϴ�.
// ���, ���� ȭ���� �ٹ̷��� �� ������ �����մϴ�.

$table_width = 1004;
?>

<!-- ��� ��� ���� -->
<table width="<?=$table_width?>" cellspacing="0" cellpadding="0">
<tr>
    <td background="<?=$gb4['path']?>/img/top_img_bg.gif">
        <table width="100%" height="52" cellspacing="0" cellpadding="0">
        <tr>
            <td><img src="<?=$gb4['path']?>/img/top_img.gif" width="100%" height="52"></td>
        </tr>
        </table></td>
</tr>
</table>
<!-- ��� ��� �� -->

<!-- ��� �ΰ� �� ��ư ���� -->
<table width="<?=$table_width?>" cellspacing="0" cellpadding="0">
<tr>
    <td width="43" height="57"></td>
    <!-- �ΰ� -->
    <td width="220"><a href="<?=$gb4['path']?>/gblog.index.php"><img src="<?=$gb4['path']?>/img/logo.jpg" width="220" height="57" border="0"></a></td>
    <td>
        <table width=100% border=0 cellpadding=0 cellspacing=0>
        <tr>
            <td>&nbsp;</td>
        </tr>
        </table>
    </td>
    <td width="390" align="right">
        <table border="0" cellspacing="0" cellpadding="0">
        <tr>
            <!-- ó������ ��ư -->
            <td width="78"><a href="<?=$gb4['path']?>/gblog.index.php"><img src="<?=$gb4['path']?>/img/top_m01.gif" width="78" height="31" border="0"></a></td>

            <? if (!$member['mb_id']) { ?>
            <!-- �α��� ���� -->
            <td width="78"><a href="<?=$gb4['bbs_path']?>/login.php?url=<?=$urlencode?>"><img src="<?=$gb4['path']?>/img/top_m02.gif" width="78" height="31" border="0"></a></td>
            <td width="78"><a href="<?=$gb4['bbs_path']?>/register.php"><img src="<?=$gb4['path']?>/img/top_m03.gif" width="78" height="31" border="0"></a></td>
            <? } else { ?>
            <!-- �α��� ���� -->
            <td width="78"><a href="<?=$g4['bbs_path']?>/logout.php?url=<?=$gb4[path]?>"><img src="<?=$gb4['path']?>/img/top_m04.gif" width="78" height="31" border="0"></a></td>
            <td width="78"><a href="<?=$gb4['bbs_path']?>/member_confirm.php?url=register_form.php"><img src="<?=$gb4['path']?>/img/top_m05.gif" width="78" height="31" border="0"></a></td>
            <? } ?>
            
            <!-- �ֱٰԽù� ��ư -->
            <td width="78"><a href="<?=$gb4['bbs_path']?>/new.php"><img src="<?=$gb4['path']?>/img/top_m06.gif" width="78" height="31" border="0"></a></td>

        </tr>
        </table></td>
    <td width="35"></td>
</tr>
</table>
<!-- ��� �ΰ� �� ��ư �� -->

<!-- �˻� ���� -->
<table width="<?=$table_width?>" cellspacing="0" cellpadding="0">
<tr>
    <td width="43" height="11"></td>
    <td width="220"></td>
    <td width=""></td>
    <td width="234"><img src="<?=$gb4['path']?>/img/search_top.gif" width="234" height="11"></td>
    <td width="35"></td>
</tr>
<tr>
    <td height="33"><img src="<?=$gb4['path']?>/img/bar_01.gif" width="43" height="33"></td>
    <td><img src="<?=$gb4['path']?>/img/bar_02.gif" width="220" height="33"></td>
    <td background="<?=$gb4['path']?>/img/bar_03.gif" width="472" height="33"><table width=100% cellpadding=0 cellspacing=0><tr><td width=25>&nbsp;</td><td><?//=popular();?></td></tr></table></td>
    <td>
        <table width="100%" height="33" cellspacing="0" cellpadding="0">
        <form name="fsearchbox" method="get" action="javascript:fsearchbox_submit(document.fsearchbox);">
        <!-- <input type="hidden" name="sfl" value="concat(wr_subject,wr_content)"> -->
        <input type="hidden" name="sfl" value="wr_subject||wr_content">
        <input type="hidden" name="sop" value="and">
        <tr>
            <td width="25" height="25"><img src="<?=$gb4['path']?>/img/search_01.gif" width="25" height="25"></td>
            <td width="136" valign="middle" bgcolor="#F4F4F4"><INPUT name="stx" maxlengt=20 style="BORDER : 0px solid; width: 125px; HEIGHT: 20px; BACKGROUND-COLOR: #F4F4F4" maxlength="20"></td>
            <td width="12"><img src="<?=$gb4['path']?>/img/search_02.gif" width="12" height="25"></td>
            <td width="48"><input type="image" src="<?=$gb4['path']?>/img/search_button.gif" width="48" height="25" border="0"></td>
            <td width="13"><img src="<?=$gb4['path']?>/img/search_03.gif" width="13" height="25"></td>
        </tr>
        <tr>
            <td width="234" height="8" colspan="5"><img src="<?=$gb4['path']?>/img/search_down.gif" width="234" height="8"></td>
        </tr>
        </form>
        </table></td>
    <td></td>
</tr>
</table>

<script language="JavaScript">
function fsearchbox_submit(f)
{
    if (f.stx.value == '')
    {
        alert("�˻�� �Է��ϼ���.");
        f.stx.select();
        f.stx.focus();
        return;
    }

    /*
    // �˻��� ���� ���ϰ� �ɸ��� ��� �� �ּ��� �����ϼ���.
    var cnt = 0;
    for (var i=0; i<f.stx.value.length; i++)
    {
        if (f.stx.value.charAt(i) == ' ')
            cnt++;
    }

    if (cnt > 1)
    {
        alert("���� �˻��� ���Ͽ� �˻�� ������ �Ѱ��� �Է��� �� �ֽ��ϴ�.");
        f.stx.select();
        f.stx.focus();
        return;
    }
    */

    f.action = "<?=$gb4['bbs_path']?>/search.php";
    f.submit();
}
</script>
<!-- �˻� �� -->

<div style='height:18px;'></div>

<style type="text/css">
#middiv {
	width:<?=$table_width?>px;
	position:relative;
	margin:0px auto;
    vertical-align:top;
    float:left;
}
#middiv #mleft  { width:220px; float:left; padding:0 0 0 43; }
#middiv #mright { width:683px; float:left; padding:0 0 0 15; }
</style>

<table width='<?=$table_width?>' cellpadding=0 cellspacing=0 border=0>
<tr>
    <td width=43></td>
    <!-- ���� �޴� -->
    <td width=220 valign=top>
        <?=outlogin("basic"); // �ܺ� �α��� ?>

        <div style='height:10px;'></div>

        <!-- �� ��α� ���� ���� -->
        <? if ($is_member) { ?>
        <a href='<?=$member['blog_url']?>'>
            <img src='<?=$gb4[path]?>/img/gblog_go.gif' border=0>
        </a>
        <? } ?>
        <!-- �� ��α� ���� ���� -->

        <div style='height:10px;'></div>

        <?=poll("basic"); // �������� ?>

        <div style='height:10px;'></div>

        <?=visit("basic"); // �湮�ڼ� ?>

        <div style='height:10px;'></div>

        <?=connect(); // ���� �����ڼ� ?>
    </td>
    <td width=18></td>
    <!-- �߰� -->
    <td width=683 valign=top>
