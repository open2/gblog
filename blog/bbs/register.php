<?
include_once("./_common.php");

// �α������� ��� ȸ������ �� �� �����ϴ�.
if (!$member[mb_id]) 
    include_once("$g4[bbs_path]/register.php");
else
    goto_url($gb4[path]);
?>
