<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ� 
?>

<!-- ��α� �˻� ���� -->
<form method=get action='<?=$PHP_SELF?>'>
<select name=st>
<option value='title|content' <?if ($st == 'title|content') echo 'selected'?>> ����+���� </option>
<option value='title' <?if ($st == 'title') echo 'selected'?>> ���� </option>
<option value='content' <?if ($st == 'content') echo 'selected'?>> ���� </option>
<option value='mb_id' <?if ($st == 'mb_id') echo 'selected'?>> ȸ�����̵� </option>
<option value='mb_nick' <?if ($st == 'mb_nick') echo 'selected'?>> �г��� </option>
</select>
<input type=text name=sv size=20 value="<?=$sv?>">
<input type=submit value="�˻�">
</form>
<!-- ��α� �˻� ���� -->
