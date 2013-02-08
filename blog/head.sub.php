<?
include_once("$g4[path]/head.sub.php");
?>

<script language=javascript>
// gblog에서 쓰는 java script 변수들을 설정
var mb_id           = '<?=$current[mb_id]?>';
var flag            = '';
var member_mb_id    = "<?=$member['mb_id']?>";
var count           = <?=count($post)?>;
var post_id         = <?=$id?>;
var trackback_id    = 0;
var gb4_blog        = "<?=$gb4['bbs_path']?>";
var use_comment     = "<?=$use_comment?>";
var page            = "<?=$page?>";
</script>

<script language="javascript" src="<?=$gb4['path']?>/js/blog.js"></script>
<script language="javascript" src="<?=$g4['path']?>/js/ajax.js"></script>
<script language="javascript" src="<?=$g4['path']?>/js/sideview.js"></script>

<?
include_once("$gb4[bbs_path]/sidebar.php");
?>

<?
// sidebar의 on/off를 설정
$sud = array('sud_post','sud_tag','sud_calendar','sud_monthly','sud_search','sud_category','sud_comment','sud_trackback','sud_link','sud_custom1','sud_custom2','sud_custom3','sud_custom4','sud_custom5');
$sud_btn = array();
$sud_script = "";
$sud_style  = "<style>\n";
for ($i=0, $max=count($sud); $i<$max; $i++) {
    $ck = $_COOKIE[$current['mb_id'].$sud[$i]];
    $sud_style  .= "#{$sud[$i]} { display:{$ck}; }\n";
    $sud_script .= "var v_{$sud[$i]} = document.getElementById('{$sud[$i]}');\n";
    $sud_script .= "if (v_{$sud[$i]}) { v_{$sud[$i]}.style.display = '$ck' };\n";
    if ($ck=='none')
        $sud_btn[$sud[$i]] = 'down';
    else
        $sud_btn[$sud[$i]] = 'up';
}
$sud_style .= "</style>\n";
// stype에서 on/off를 안하고, java script에서 하면 일단 on 되었다가 off 되어서 좀 그렇습니다.
echo $sud_style;
?>

<script language=javascript>
// 초기 sidebar의 메뉴의 모양을 잡아준다
<?=$sud_script?>
// sidebar의 메뉴를 on/off
function sud(id) {
    var div = document.getElementById(id);
    var btn = document.getElementById(id+"_button");

    if (div) {
        if (div.style.display=='none') {
            div.style.display = 'block';
            btn.src = "<?=$blog_skin_url?>/img/icon_up.gif";
        } else {
            div.style.display = 'none';
            btn.src = "<?=$blog_skin_url?>/img/icon_down.gif";
        }

        set_cookie(mb_id+id, div.style.display, 720, g4_cookie_domain);
    }
}
</script>