<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 
?>

<!-- 신규 블로그 시작 -->
<table width=100% cellpadding=0 cellspacing=0>
<tr>
    <td><img src='<?=$gb4[path]?>/img/new_blogger.gif' border=0></td>
</tr>
<tr>
    <td height=5></td>
</tr>
<tr>
    <td><img src='<?=$gb4[path]?>/img/new_blogger_head.gif' border=0></td>
</tr>
<tr>
    <td bgcolor=#f7f7f7>
        <table width=90% cellpadding=0 cellspacing=0 align=center>
        <?
        $sql = " select * from {$gb4['blog_table']} order by id desc limit 15 ";
        $result = sql_query($sql);
        for($i=0;$row=sql_fetch_array($result);$i++) {
            $row[blog_name] = cut_str($row[blog_name],25);
            echo "<tr><td height=20><span class='cloudy'>&middot;</span> ";
            echo "<a href='".get_blog_url($row[mb_id])."'>";
            echo $row[blog_name];
            echo "</a> <span class='small cloudy'>(".(int)$row[post_count].")</span>";
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
<!-- 신규 블로그 종료 -->