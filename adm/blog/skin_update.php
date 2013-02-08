<?
include_once("./_common.php");

auth_check($auth[$sub_menu], "w");

if ($is_admin != "super")
    alert("최고관리자만 접근 가능합니다.");

sql_query("update $gb4[skin_table] set used='$used' where id='$id'");

// 해당 스킨 사용자들의 경우 모두 $prwview로 변경
if ($used == "0") {
    $row = sql_fetch(" select * from $gb4[skin_table] where skin='$gb4[default_skin]' ");
    $sql = "update $gb4[blog_table] set skin_id='$row[id]' where skin_id='$id'";
    sql_query($sql);
}
?>