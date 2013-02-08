<?
include_once("./_common.php");

// 로그인중인 경우 회원가입 할 수 없습니다.
if (!$member[mb_id]) 
    include_once("$g4[bbs_path]/register.php");
else
    goto_url($gb4[path]);
?>
