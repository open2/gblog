<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 

// 글자 색상
$weekday_color = "#505050"; // 평일
$saturday_color = "#53B8FF"; // 토요일
$sunday_color = "#EB4D00"; // 일요일 (공휴일)
$select_color = "#FFFFFF"; // 선택일

// 배경 색상
$today_bgcolor = "#D9D9D9"; // 오늘 
$select_bgcolor = "#53B8FF"; // 선택일



// 요일
$yoil = array ("일", "월", "화", "수", "목", "금", "토");

?>

<table border=0 cellpadding=0 cellspacing=0 align=center width=175>
<form name=fcalendar autocomplete=off>
<input type=hidden name=fld value='<?=$fld?>'>
<input type=hidden name=cur_date value='<?=$cur_date?>'>
<input type=hidden id=delimiter name=delimiter value='<?=$delimiter?>'>
<tr><td align=center height=30 class=year>
        <a href='<?=$yyyy_before_href?>'><img src="<?=$blog_skin_url?>/img/btn_cal_prev_year.gif" alt=""></a>
        <a href='<?=$mm_before_href?>'><img src="<?=$blog_skin_url?>/img/btn_cal_prev_month.gif" alt=""></a>
        &nbsp;
        <?=$yyyy?> 년
        <?=$mm?> 월
        &nbsp;
        <a href='<?=$mm_after_href?>'><img src="<?=$blog_skin_url?>/img/btn_cal_next_month.gif" alt=""></a>
        <a href='<?=$yyyy_after_href?>'><img src="<?=$blog_skin_url?>/img/btn_cal_next_year.gif" alt=""></a>
    </td>
</tr>
<tr>
    <td align=center>
        <table border=0 cellpadding=0 cellspacing=0 width=170>
        <tr align=center>
            <td width=14% style="color:<?=$sunday_color?>"><?=$yoil[0];?></td>
            <td width=14% style="color:<?=$weekday_color?>"><?=$yoil[1];?></td>
            <td width=14% style="color:<?=$weekday_color?>"><?=$yoil[2];?></td>
            <td width=14% style="color:<?=$weekday_color?>"><?=$yoil[3];?></td>
            <td width=14% style="color:<?=$weekday_color?>"><?=$yoil[4];?></td>
            <td width=14% style="color:<?=$weekday_color?>"><?=$yoil[5];?></td>
            <td width=14% style="color:<?=$saturday_color?>"><?=$yoil[6];?></td>
        </tr>
        <?
        $cnt = $day = 0;
        for ($i=0; $i<6; $i++)
        {
            echo "<tr>";
            for ($k=0; $k<7; $k++)
            {
                $cnt++;

                echo "<td align=center height=20>";

                if ($cnt > $dt[wday])
                {
                    $day++;
                    if ($day <= $last_day)
                    {
                        $mm2 = substr("0".$mm,-2);
                        $day2 =  substr("0".$day,-2);

                        echo "<table width=100% height=100% cellpadding=0 cellspacing=0><tr><td id='id$i$k' ";
                        if( in_array( $day, $calendar_post_day) )
                              echo "onclick=\"date_send('$yyyy', '$mm2', '$day2', '$k', '$yoil[$k]');\" style='cursor:pointer;' ";
                        echo "align=center>$day</td></tr></table>";

                        if ($k==0)
                            echo "<script language='JavaScript'>document.getElementById('id$i$k').style.color='$sunday_color';</script>";
                        else if ($k==6)
                            echo "<script language='JavaScript'>document.getElementById('id$i$k').style.color='$saturday_color';</script>";
                        else
                            echo "<script language='JavaScript'>document.getElementById('id$i$k').style.color='$weekday_color';</script>";

                        $tmp_date = $yyyy.substr("0".$mm,-2).substr("0".$day,-2);

                        $tmp = $mm2."-".$day2;

                        // 특정한 날이라면
                        if ($nal[$tmp])
                        {
                            $title = trim($nal[$tmp][1]);
                            //echo $title;
                            echo "<script language='JavaScript'>document.getElementById('id$i$k').title='{$title}';</script>";
                            if (trim($nal[$tmp][2]) == "*") {
                                echo "<script language='JavaScript'>document.getElementById('id$i$k').style.color='$sunday_color';</script>";
                            }
                        }
                        
                        // 오늘이라면
                        if ($today[year] == $yyyy && $today[mon] == $mm && $today[mday] == $day)
                        {
                            echo "<script language='JavaScript'>document.getElementById('id$i$k').style.backgroundColor='$today_bgcolor';</script>";
                            echo "<script language='JavaScript'>document.getElementById('id$i$k').title+='[오늘]';</script>";
                        }
                        // 선택일(넘어온 값) 이라면
                        else if ($tmp_date == $cur_date)
                        {
                            echo "<script language='JavaScript'>document.getElementById('id$i$k').style.color='$select_color';</script>";
                            echo "<script language='JavaScript'>document.getElementById('id$i$k').style.backgroundColor='$select_bgcolor';</script>";
                            echo "<script language='JavaScript'>document.getElementById('id$i$k').title+='[선택일]';</script>";
                        }
                        
                        // 오늘 작성된 글이 있다면
                        if( in_array( $day, $calendar_post_day) )
                        {
                            echo "<script language='JavaScript'>document.getElementById('id$i$k').style.fontWeight='bold';</script>";
                            echo "<script language='JavaScript'>document.getElementById('id$i$k').className = \"spot_post\";</script>";
                        }
                    } else
                        echo "&nbsp;";
                } else
                    echo "&nbsp;";
                echo "</td>";
            }
            echo "</tr>\n";
            if ($day >= $last_day)
                break;
        }
        ?>
        </table>
    </td>
</tr>
<tr>
    <td class="day">
        <?="<a href=\"javascript:date_send('{$today[year]}', '{$mon}', '{$mday}', '{$today[wday]}', '{$yoil[$today[wday]]}');\">";?>
        오늘 : <?="{$today[year]}년 {$today[mon]}월 {$today[mday]}일 ({$yoil[$today[wday]]})";?></a>
    </td>
</tr>
</form>
</table>