<?

// mktime() 함수는 1970 ~ 2038년까지만 계산되므로 사용하지 않음
// 참고 : http://phpschool.com/bbs2/inc_view.html?id=3924&code=tnt2&start=0&mode=search&s_que=mktime&field=title&operator=and&period=all
function spacer($year, $month)
{
    $day = 1;
    $spacer = array(0, 3, 2, 5, 0, 3, 5, 1, 4, 6, 2, 4);
    $year = $year - ($month < 3);
    $result = ($year + (int) ($year/4) - (int) ($year/100) + (int) ($year/400) + $spacer[$month-1] + $day) % 7;
    return $result;
}

function get_calendar($yyyy, $mm, $dd) 
{

    global $_SERVER, $g4, $gb4, $current, $blog_skin_path, $calendar_post_day, $blog_skin_url, $sql_secret;

    $today = getdate($g4['server_time']);

    if( empty($yyyy) ) $yyyy = $today['year'];
    if( empty($mm) ) $mm = sprintf("%02d",$today['mon']);

    // 이번달 글작성한 날짜 가져옴.
    $calendar_post_day = array();
    $qry = sql_query("select mid(post_date,9,2) as day from {$gb4['post_table']} where blog_id='{$current['id']}' and left(post_date,7)='{$yyyy}-{$mm}' {$sql_secret} group by day order by day");
    while( $res = sql_fetch_array($qry) ) array_push($calendar_post_day, $res['day']);

    // 오늘
    $today = getdate($g4[server_time]);
    $mon  = substr("0".$today[mon],-2);
    $mday = substr("0".$today[mday],-2);

    if( $yyyy && $mm && $dd )
        $cur_date = "{$yyyy}-{$mm}-{$dd}";
    else 
        $cur_date = "";

    // delimiter 를 없앤다
    $cur_date = preg_replace("/([^0-9]*)/", "", $cur_date);

    if ($cur_date && !$yyyy)
    {
        $yyyy = substr($cur_date,0,4);
        $mm = substr($cur_date,4,2);
    }
    else
    {
        if (!$yyyy) $yyyy = $today['year'];
        if (!$mm) $mm = $today['mon'];
    }
    $yyyy = (int)$yyyy;
    $mm = (int)$mm;

    $f = @file("{$g4['bbs_path']}/calendar/$yyyy.txt");
    if ($f) {
        while ($line = each($f)) {
            $tmp = explode("|", $line[value]);
            $nal[$tmp[0]] = $tmp;
            //print_r2($nal);
        }
    }

    $spacer = spacer($yyyy, $mm);

    $endday = array(1=>31, 28, 31, 30 , 31, 30, 31, 31, 30 ,31 ,30, 31);
    // 윤년 계산 부분이다. 4년에 한번꼴로 2월이 28일이 아닌 29일이 있다.
    if( $yyyy%4 == 0 && $yyyy%100 != 0 || $yyyy%400 == 0 )
        $endday[2] = 29; // 조건에 적합할 경우 28을 29로 변경

    // 해당월의 1일
    $mktime = mktime(0,0,0,$mm,1,$yyyy);
    $dt = getdate(strtotime(date("Y-m-1", $mktime)));

    $dt[wday] = $spacer;

    // 해당월의 마지막 날짜,
    //$last_day = date("t", $mktime);
    $last_day = $endday[$mm];

    $yyyy_before = $yyyy;
    $mm_before = $mm - 1;
    if ($mm_before < 1)
    {
        $yyyy_before--;
        $mm_before = 12;
    }

    $yyyy_after = $yyyy;
    $mm_after = $mm + 1;
    if ($mm_after > 12)
    {
        $yyyy_after++;
        $mm_after = 1;
    }

    $fr_yyyy = $yyyy - 80;
    $to_yyyy = $yyyy + 80;

    $mm = sprintf("%02d", $mm);
    $dd = sprintf("%02d", $dd);

    $mm_after = sprintf("%02d", $mm_after);
    $mm_before = sprintf("%02d", $mm_before);

    if( $gb4['use_permalink'] == 'none' ) 
    {
        $yyyy_before_href = "{$current['blog_url']}{$gb4['ampersand']}yyyy=".($yyyy-1)."&mm={$mm}";
        $yyyy_after_href = "{$current['blog_url']}{$gb4['ampersand']}yyyy=".($yyyy+1)."&mm={$mm}";

        $mm_after_href = "{$current['blog_url']}{$gb4['ampersand']}yyyy={$yyyy_after}&mm={$mm_after}";
        $mm_before_href = "{$current['blog_url']}{$gb4['ampersand']}yyyy={$yyyy_before}&mm={$mm_before}";
    }
    else
    {
        $yyyy_before_href = "{$current['blog_url']}/".($yyyy-1)."/{$mm}";
        $yyyy_after_href = "{$current['blog_url']}/".($yyyy+1)."/{$mm}";

        $mm_after_href = "{$current['blog_url']}/{$yyyy_after}/{$mm_after}";
        $mm_before_href = "{$current['blog_url']}/{$yyyy_before}/{$mm_before}";
    }

    /*
    $yyyy_select = "<select name=yyyy onchange='document.fcalendar.submit();'>";
    for ($i=$fr_yyyy; $i<=$to_yyyy; $i++)
    {
        if ($i == $yyyy) $selected = " selected";
        else $selected = "";
        $yyyy_select .= "<option value='{$i}'{$selected}>$i 년</option>";
    }
    $yyyy_select .= "</select>";

    $mm_select = "<select name=mm onchange='document.fcalendar.submit();'>";
    for ($i=1; $i<=12; $i++)
    {
        if ($i == $mm) $selected = " selected";
        else $selected = "";
        $mm_select .= "<option value='{$i}'{$selected}>$i 월</option>";
    }
    $mm_select .= "</select>";
    */

    ob_start();
    include_once("$blog_skin_path/calendar.skin.php");
    ?>
    <script language="JavaScript">
    //
    // year : 4자리
    // month : 1~2자리
    // day : 1~2자리
    // wday : 요일 숫자 (0:일 ~ 6:토)
    // handay : 요일 한글
    //
    function date_send(year, month, day, wday, handay)
    {
        //var delimiter = document.getElementById('delimiter').value;
        //document.getElementById('<?=$fld?>').value = year + delimiter + month + delimiter + day;
        //window.close();
        //cur_date = year + "-" + month + "-" + day;
        <? if( $gb4['use_permalink'] == 'none' ) { ?>
        location.href = "<?=$current['blog_url'].$gb4['ampersand']?>yyyy="+year+"&mm="+month+"&dd="+day; //+"&cur_date="+cur_date;
        <? } else { ?>
            location.href = "<?=$current['blog_url']?>/"+year+"/"+month+"/"+day; //+"&cur_date="+cur_date;
        <? } ?> 
    }
    </script>
    <?
    $calendar = ob_get_contents();
    ob_clean();

    return $calendar;
}
?>