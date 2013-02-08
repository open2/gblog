<?
include_once("./_common.php");

auth_check($auth[$sub_menu], "r");

$g4[title] = "블로그 스킨관리";

include_once("$g4[admin_path]/admin.head.php");

$skin_path = "$gb4[path]/skin/blog";

// 스킨 목록을 디렉토리에서 읽어옴. !== 은 4.0.0-RC2까지 존재하지 않았던 점에 주의.
$skins = array();
if ($handle = opendir($skin_path)) {
   while (false !== ($dir = readdir($handle))) {
       if(trim($dir)!='.'&&trim($dir)!='..') {
           array_push($skins,trim($dir));
       }
   }
   closedir($handle);
}

// 새로운 스킨 DB 에 저장
$skin_list = "''";
while($skin = array_pop($skins)) {
    $r = sql_fetch("select count(*) as cnt from $gb4[skin_table] where skin='$skin'");
    if(!$r[cnt])
        sql_query("insert into $gb4[skin_table] set skin='$skin', regdate='{$g4['time_ymdhis']}'");
    $skin_list.= ",'$skin'";
}

// 디렉토리에 없는 스킨 DB에서도 삭제
sql_query("delete from $gb4[skin_table] where skin not in($skin_list)");


$r = sql_fetch("select count(*) as cnt from $gb4[skin_table]");
$total = $r[cnt];
$page_size = 12;
$total_page = (int)($total/$page_size)+($total%$page_size!=0 ? 1 : 0);
if(!$page) $page = 1; else $page = (int)$page;
$page_start = $page_size*($page-1);
$paging = get_paging($page_size, $page, $total_page, "skin.php?page=");


// DB에서 스킨 목록가져오기
$skins = array();
$q = sql_query("select * from $gb4[skin_table] order by id desc limit $page_start,$page_size");
while($r = sql_fetch_array($q)) array_push($skins, $r);

?>
<table width=100% cellpadding=0 cellspacing=0 border=0>
<tr>
    <td align=left><?=subtitle("블로그 스킨관리")?></td>
</tr>
</table>

<table border=0 cellpadding=10 cellspacing=10>
    <tr>
    <? for($i=0; $i<count($skins); $i++) { ?>
        <td width=170<?if($skins[$i][used]) echo " bgcolor=#FFCCFF"?>>
            <table border=0 cellpadding=0 cellspacing=0 width=100% id="skin_<?=$skins[$i][id]?>">
                <tbody  align=center>
                <tr>
                    <td>
                        <img src='<?=$skin_path?>/<?=$skins[$i][skin]?>/img/preview.gif' width=200 height=160 border=0>
                    </td>
                </tr>
                <tr>
                    <td> <?=$skins[$i][skin]?> (<?=$skins[$i][use_count]?>)  </td>
                </tr>
                <tr>
                    <td> <?=substr($skins[$i][regdate],0,10)?> </td>
                </tr>
                <tr>
                    <td>
                        <input type=checkbox id=used_<?=$skins[$i][id]?> name=used_<?=$skins[$i][id]?> onClick="change_used_skin(<?=$skins[$i][id]?>)"<?if($skins[$i][used]) echo ' checked'?>> 사용
                    </td>
                </tr>
            </table>
        </td>
    <? 
        if(($i+1)%3==0) echo "</tr>\n\t<tr>\n";
    } 
    ?>

</table>

<p align=center>
<?=$paging?>
</p>

<script language=javascript>
function change_used_skin(id) {

    if ($('#used_'+id).attr('checked') == true)
        used = 1;
    else
        used = 0;

    url = 'skin_update.php';

    send = 'id='+id;
    send += '&used='+used;

    $.ajax({
        type: 'POST',
        url: url,
        data: send,
        cache: false,
        async: false,
        success: function(result) {

            if (used == 1) {
                $('#skin_'+id).css('background', '#FFCCFF');
            } else {
                $('#skin_'+id).css('background', '#FFFFFF');
            }
            
            }
        });
}
</script>

<?
include_once("../admin.tail.php");
?>