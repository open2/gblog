<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ� 

?>

<!-- �� ������ �з� ���� -->
<table width=100% cellpadding=0 cellspacing=0>
<tr>
    <td height=5></td>
</tr>
<tr>
    <td><img src='<?=$gb4[path]?>/img/new_blogger_head.gif' border=0></td>
</tr>
<tr>
    <td bgcolor=#f7f7f7>
        <div style="font-weight:bold; margin-bottom:5px; margin-left:10px;">�� ������ �з�</div>
        <table width=90% cellpadding=0 cellspacing=0 align=center>
        <?
        $sql = " select * from {$gb4['division_table']} order by dv_rank ";
        $result = sql_query($sql);
        for($i=0;$row=sql_fetch_array($result);$i++) {
            echo "<tr><td height=20><span class='cloudy'>&middot;</span> ";
            echo "<a href='gblog.index.php?dv_id={$row['dv_id']}'>";
            echo $row['dv_name'];
            echo "</td></tr>";
        }
        ?>
        </table>
    </td>
</tr>
<tr>
    <td><img src='<?=$gb4[path]?>/img/new_blogger_tail.gif' border=0></td>
</tr>
</table>
<!-- �� ������ �з� ���� -->
