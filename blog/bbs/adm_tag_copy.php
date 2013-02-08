<?
include_once("./_common.php");

$g4[title] = "블로그 태그 변경";
include_once("$g4[path]/head.sub.php");

// get mb_id, blog_id
$mb_id = $member['mb_id'];

$sql = " select mb_id from $gb4[blog_table] where id = '$blog_id' ";
$result = sql_fetch($sql);
if ($result[mb_id]  && $mb_id != $result['mb_id'])
    alert('자신의 블로그만 관리할 수 있습니다.');

$sql = " select tag from $gb4[tag_table] where id = '$tag_id' ";
$result = sql_fetch($sql);
$tag_name = $result['tag'];
?>

<link rel="stylesheet" href="./admin.style.css" type="text/css">

<form name="fcopy" method='post' onsubmit="fcopy_submit(this);" autocomplete="off">
<input type="hidden" name="blog_id" value="<?=$blog_id?>">
<input type="hidden" name="tag_id"  value="<?=$tag_id?>">
<input type="hidden" name="mb_id"   value="<?=$mb_id?>">

<table width=100% cellpadding=0 cellspacing=0>
<colgroup width=30% class='col1 pad1 bold right'>
<colgroup width=70% class='col2 pad2'>
<tr><td colspan=2 height=5></td></tr>
<tr>
    <td colspan=2 class=title align=left><img src='<?=$g4[admin_path]?>/img/icon_title.gif'> <?=$g4[title]?></td>
</tr>
<tr><td colspan=2 class='line1'></td></tr>
<tr class='ht'>
	<td>원본 태그</td>
	<td><?=$tag_name?></td>
</tr>
<tr class='ht'>
	<td>변경할 태그</td>
	<td><input type=text class=ed name="target_tag" size="20" maxlength="20" required itemname="태그"></td>
</tr>
<tr height=40>
    <td></td>
	<td>
        <input type="submit" value="  변  경  " class=btn1>&nbsp;
        <input type="button" value="창닫기" onclick="window.close();" class=btn1>
    </td>
</tr>
</table>

</form>

<script language='javascript'>
function fcopy_submit(f)
{
    f.action = "./adm_tag_copy_update.php";
    f.target = "_parent";
    f.submit();
    self.close();
    opener.location.reload();
}
</script>

<?
include_once("$g4[path]/tail.sub.php");
?>