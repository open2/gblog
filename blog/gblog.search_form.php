<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 
?>

<!-- 블로그 검색 시작 -->
<form method=get action='<?=$PHP_SELF?>'>
<select name=st>
<option value='title|content' <?if ($st == 'title|content') echo 'selected'?>> 제목+내용 </option>
<option value='title' <?if ($st == 'title') echo 'selected'?>> 제목 </option>
<option value='content' <?if ($st == 'content') echo 'selected'?>> 내용 </option>
<option value='mb_id' <?if ($st == 'mb_id') echo 'selected'?>> 회원아이디 </option>
<option value='mb_nick' <?if ($st == 'mb_nick') echo 'selected'?>> 닉네임 </option>
</select>
<input type=text name=sv size=20 value="<?=$sv?>">
<input type=submit value="검색">
</form>
<!-- 블로그 검색 종료 -->
