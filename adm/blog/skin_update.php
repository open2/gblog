<?
include_once("./_common.php");

auth_check($auth[$sub_menu], "w");

if ($is_admin != "super")
    alert("�ְ�����ڸ� ���� �����մϴ�.");

sql_query("update $gb4[skin_table] set used='$used' where id='$id'");

// �ش� ��Ų ����ڵ��� ��� ��� $prwview�� ����
if ($used == "0") {
    $row = sql_fetch(" select * from $gb4[skin_table] where skin='$gb4[default_skin]' ");
    $sql = "update $gb4[blog_table] set skin_id='$row[id]' where skin_id='$id'";
    sql_query($sql);
}
?>