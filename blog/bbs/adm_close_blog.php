<?
include_once("./_common.php");

$g4['title'] = "블로그 폐쇄";

$token = get_token();

// 블로그 기본정보를 가져오기
$blog = sql_fetch("select * from {$gb4['blog_table']} where mb_id='{$member['mb_id']}'");
if (!$blog)
    alert("블로그가 없습니다");

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
.w_message  { font-family:돋움; font-size:9pt; color:#4B4B4B; }
.w_norobot  { font-family:돋움; font-size:9pt; color:#BB4681; }
.w_hand     { cursor:pointer; }
-->
</style>

<div class="adm_info">
    <b>블로그 폐쇄</b> : <?=$member[mb_nick]?>님의 불로그를 폐쇄 합니다.
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
            블로그를 폐쇄하면 모든 블로그 정보가 삭제되며, 삭제된 정보는 복구할 수 없습니다.<BR>
            삭제를 하시려면 확인 버튼을 눌러주시기 바랍니다.
            </td>
        </tr>
        <tr bgcolor="#ffffff">
            <td class=m_padding>
                <img id='kcaptcha_image' border='0' style="cursor:pointer;">
                &nbsp;<input class='ed' type=input size=10 name='kcaptcha_key' itemname="자동등록방지" required>&nbsp;&nbsp;왼쪽의 글자를 입력하세요.
            </td>
        </tr>
        </table>
    </td>
</tr>
</table>

<p align=center>
    <input type=submit class=btn1 accesskey='s' value='  확  인  '>
</p>

</td></tr></form></table>

<script type="text/javascript" src="<?="$g4[path]/js/jquery.js"?>"></script>
<script type="text/javascript" src="<?="$g4[path]/js/md5.js"?>"></script>
<script type="text/javascript" src="<?="$g4[path]/js/jquery.kcaptcha.js"?>"></script>
<script language="JavaScript">

function fconfigform_submit(f) {

    if (!confirm("블로그를 정말 폐쇄 하시겠습니까?\n\n한번 폐쇄한 블로그는 복구할 수 없습니다"))
        return;

    if (typeof(f.kcaptcha_key) != 'undefined') {
        if (hex_md5(f.kcaptcha_key.value) != md5_norobot_key) {
            alert('자동등록방지용 글자가 제대로 입력되지 않았습니다.');
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