<?
include_once("./_common.php");

$g4['title'] = "��α� ���";

$token = get_token();

// ��α� �⺻������ ��������
$blog = sql_fetch("select * from {$gb4['blog_table']} where mb_id='{$member['mb_id']}'");
if (!$blog)
    alert("��αװ� �����ϴ�");

include_once("{$g4[path]}/head.sub.php");
include_once("./admin.head.php");
?>

<style type="text/css">
<!--
.m_title    { BACKGROUND-COLOR: #F7F7F7; PADDING-LEFT: 15px; PADDING-top: 5px; PADDING-BOTTOM: 5px; }
.m_padding  { PADDING-LEFT: 15px; PADDING-BOTTOM: 5px; PADDING-TOP: 5px; }
.m_padding2 { PADDING-LEFT: 0px; PADDING-top: 5px; PADDING-BOTTOM: 0px; }
.m_padding3 { PADDING-LEFT: 0px; PADDING-top: 5px; PADDING-BOTTOM: 5px; }
.m_text     { BORDER: #D3D3D3 1px solid; HEIGHT: 18px; BACKGROUND-COLOR: #ffffff; }
.m_text2    { BORDER: #D3D3D3 1px solid; HEIGHT: 18px; BACKGROUND-COLOR: #dddddd; }
.m_textarea { BORDER: #D3D3D3 1px solid; BACKGROUND-COLOR: #ffffff; WIDTH: 100%; word-break: break-all; }
.w_message  { font-family:����; font-size:9pt; color:#4B4B4B; }
.w_norobot  { font-family:����; font-size:9pt; color:#BB4681; }
.w_hand     { cursor:pointer; }
-->
</style>

<div class="adm_info">
    <b>��α� ���</b> : <?=$member[mb_nick]?>���� �ҷα׸� ��� �մϴ�.
</div>

<table width=600 cellspacing=0 cellspacing=0 align=center>
<form name=fregisterform method=post enctype="multipart/form-data" autocomplete="off" onsubmit="return fconfigform_submit(this);">
<input type=hidden name=mb_id            value="<?=$member['mb_id']?>">
<input type=hidden name=token value='<?=$token?>'>

<tr><td>

<table width="100%" cellspacing="0" cellpadding="0">
<tr>
    <td bgcolor="#cccccc">
        <table cellspacing=1 cellpadding=0 width=100%>
        <tr bgcolor="#ffffff">
            <td class=m_padding>
            ��α׸� ����ϸ� ��� ��α� ������ �����Ǹ�, ������ ������ ������ �� �����ϴ�.<BR>
            ������ �Ͻ÷��� Ȯ�� ��ư�� �����ֽñ� �ٶ��ϴ�.
            </td>
        </tr>
        <tr bgcolor="#ffffff">
            <td class=m_padding>
                <img id='kcaptcha_image' border='0' style="cursor:pointer;">
                &nbsp;<input class='ed' type=input size=10 name='kcaptcha_key' itemname="�ڵ���Ϲ���" required>&nbsp;&nbsp;������ ���ڸ� �Է��ϼ���.
            </td>
        </tr>
        </table>
    </td>
</tr>
</table>

<p align=center>
    <input type=submit class=btn1 accesskey='s' value='  Ȯ  ��  '>
</p>

</td></tr></form></table>

<script type="text/javascript" src="<?="$g4[path]/js/jquery.js"?>"></script>
<script type="text/javascript" src="<?="$g4[path]/js/md5.js"?>"></script>
<script type="text/javascript" src="<?="$g4[path]/js/jquery.kcaptcha.js"?>"></script>
<script language="JavaScript">

function fconfigform_submit(f) {

    if (!confirm("��α׸� ���� ��� �Ͻðڽ��ϱ�?\n\n�ѹ� ����� ��α״� ������ �� �����ϴ�"))
        return;

    if (typeof(f.kcaptcha_key) != 'undefined') {
        if (hex_md5(f.kcaptcha_key.value) != md5_norobot_key) {
            alert('�ڵ���Ϲ����� ���ڰ� ����� �Էµ��� �ʾҽ��ϴ�.');
            f.kcaptcha_key.select();
            f.kcaptcha_key.focus();
            return false;
        }
    }

    f.action = "./adm_close_blog_update.php";
    return true;
}
</script>

<?
include_once("./admin.tail.php");
include_once("{$g4[path]}/tail.sub.php");
?>