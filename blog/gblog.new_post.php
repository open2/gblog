<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ� 

?>

<!-- ���� �ö�� �� ���� -->
<img src='<?=$gb4[path]?>/img/new_text.gif' border=0>

<table width=440 cellpadding=0 cellspacing=0 align=center>
<?
$main = get_blog_main($dv_id, $st, $sv, 15);

for($i=0; $i<sizeof($main); $i++) 
{
    $row = $main[$i];

    if ($i>0) {
        echo "<tr><td height=5></td></tr>";
        echo "<tr><td height=1 bgcolor=#d2d2d2></td></tr>";
        echo "<tr><td height=5></td></tr>";
    }

    echo "<tr><td height=25><span style='font-size:11pt;font-weight:bold;letter-spacing:-1px;'>";
    echo "<a href='".get_post_url($row[id],$row[mb_id])."'>".$row[title]."</a></span></td></tr>";
    echo "<tr><td height=20><span>".$row[writer]."</span> <span class=cloudy>���� �ۼ�</span> ";
    echo "<span class='cloudy small'>(".$row[post_date].", ".$row[category_name].")</span></td></tr>";
    echo "<tr><td class=lh style='word-break:break-all;'><div class=cloudy>".$row[content]."</div></td></tr>";
}

if ($i==0) {
    echo "<tr><td height=100>���� �ö�� ���� ���� �����ϴ�.</td></tr>";
}
?>
<tr><td height=5></td></tr>
<tr><td height=1 bgcolor=#d2d2d2></td></tr>
</table>
<!-- ���� �ö�� �� ���� -->
