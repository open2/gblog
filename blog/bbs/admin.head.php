<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

function help($help="", $left=0, $top=0)
{
    global $g4;
    static $idx = 0;

    $idx++;

    $help = preg_replace("/\n/", "<br>", $help);
    
    $str  = "<img src='$g4[admin_path]/img/icon_help.gif' border=0 width=15 height=15 align=absmiddle onclick=\"help('help$idx', $left, $top);\" style='cursor:pointer;'>";
    //$str .= "<div id='help$idx' style='position:absolute; top:0px; left:0px; display:none;'>";
    $str .= "<div id='help$idx' style='position:absolute; display:none;'>";
    $str .= "<div id='csshelp1'><div id='csshelp2'><div id='csshelp3'>$help</div></div></div>";
    $str .= "</div>";

    return $str;
}

function print_menu1($key, $no)
{
    global $menu;

    $str = "<table width=130 cellpadding=1 cellspacing=0 id='menu_{$key}' style='position:absolute; display:none; z-index:1;' onpropertychange=\"selectBoxHidden('menu_{$key}')\"><colgroup><colgroup><colgroup width=10><tr><td rowspan=2 colspan=2 bgcolor=#EFCA95><table width=127 cellpadding=0 cellspacing=0 bgcolor=#FEF8F0><colgroup style='padding-left:10px'>";
    $str .= print_menu2($key, $no);
    $str .= "</table></td><td></td></tr><tr><td bgcolor=#DDDAD5 height=40></td></tr><tr><td width=4></td><td height=3 width=127 bgcolor=#DDDAD5></td><td bgcolor=#DDDAD5></td></tr></table>\n";

    return $str;
}


function print_menu2($key, $no)
{
    global $menu, $auth_menu, $is_admin, $auth, $g4;

    $str = "";
    for($i=1; $i<count($menu[$key]); $i++)
    {
//        if ($is_admin != "super" && (!array_key_exists($menu[$key][$i][0],$auth) || !strstr($auth[$menu[$key][$i][0]], "r")))           continue;

        if ($menu[$key][$i][0] == "-")
            $str .= "<tr><td class=bg_line{$no}></td></tr>";
        else
        {
            $span1 = $span2 = "";
            if (isset($menu[$key][$i][3]))
            {
                $span1 = "<span style='{$menu[$key][$i][3]}'>";
                $span2 = "</span>";
            }
            $str .= "<tr><td class=bg_menu{$no}>";
            if ($no == 2)
                $str .= "&nbsp;&nbsp;<img src='{$g4[admin_path]}/img/icon.gif' align=absmiddle> ";
            $str .= "<a href='{$menu[$key][$i][2]}' style='color:#555500;'>{$span1}{$menu[$key][$i][1]}{$span2}</a></td></tr>";

            $auth_menu[$menu[$key][$i][0]] = $menu[$key][$i][1];
        }
    }

    return $str;
}


$amenu = array('글 관리', '링크 관리', '통계 관리', '환경설정');

$menu["menu0"] = array (
    array("000000", "글 관리", "$gb4[bbs_path]/adm_post_list.php?mb_id={$member['mb_id']}"),
    array("", "글 관리", "$gb4[bbs_path]/adm_post_list.php?mb_id={$member['mb_id']}"),
    array("-"),
    array("", "분류 관리", "$gb4[bbs_path]/adm_category.php?mb_id={$member['mb_id']}"),
    array("-"),
    array("", "댓글 관리", "$gb4[bbs_path]/adm_comment_list.php?mb_id={$member['mb_id']}"),
    array("", "엮인글 관리", "$gb4[bbs_path]/adm_trackback_list.php?mb_id={$member['mb_id']}"),
    array("-"),
    array("", "태그 관리", "$gb4[bbs_path]/adm_tag_list.php?mb_id={$member['mb_id']}"),
);
$menu["menu1"] = array (
    array("000000", "링크 관리", "$gb4[bbs_path]/adm_link.php?mb_id={$member['mb_id']}"),
    array("", "링크 분류 관리", "$gb4[bbs_path]/adm_link_category.php?mb_id={$member['mb_id']}"),
    array("", "링크 관리", "$gb4[bbs_path]/adm_link.php?mb_id={$member['mb_id']}"),
);
$menu["menu2"] = array (
    array("000000", "통계 관리", "$gb4[bbs_path]/visit_list.php?mb_id={$member['mb_id']}"),
    array("", "접속자", "$gb4[bbs_path]/visit_list.php?mb_id={$member['mb_id']}"),
    array("", "도메인", "$gb4[bbs_path]/visit_domain.php?mb_id={$member['mb_id']}"),
    array("", "브라우저", "$gb4[bbs_path]/visit_browser.php?mb_id={$member['mb_id']}"),
    array("", "운영체제", "$gb4[bbs_path]/visit_os.php?mb_id={$member['mb_id']}"),
);
$menu["menu3"] = array (
    array("000000", "환경 설정", "$gb4[bbs_path]/join_blog.php?w=u&mb_id={$member['mb_id']}"),
    array("", "기본환경 설정", "$gb4[bbs_path]/join_blog.php?w=u&mb_id={$member['mb_id']}"),
    array("", "디자인 설정", "$gb4[bbs_path]/adm_design.php?mb_id={$member['mb_id']}"),
    array("", "레이아웃 설정", "$gb4[bbs_path]/adm_layout.php?mb_id={$member['mb_id']}"),
    array("", "블로그 폐쇄", "$gb4[bbs_path]/adm_close_blog.php"),
);
?>

<script type="text/javascript" src="<?=$g4['path']?>/js/common.js"></script>
<script type="text/javascript" src="<?=$g4['path']?>/js/sideview.js"></script>
<script type="text/javascript" src="<?=$g4['path']?>/js/jquery.js"></script>
<script language="JavaScript">
if (!g4_is_ie) document.captureEvents(Event.MOUSEMOVE)
document.onmousemove = getMouseXY;
var tempX = 0;
var tempY = 0;
var prevdiv = null;
var timerID = null;

function getMouseXY(e) 
{
    if (g4_is_ie) { // grab the x-y pos.s if browser is IE
        tempX = event.clientX + document.body.scrollLeft;
        tempY = event.clientY + document.body.scrollTop;
    } else {  // grab the x-y pos.s if browser is NS
        tempX = e.pageX;
        tempY = e.pageY;
    }  

    if (tempX < 0) {tempX = 0;}
    if (tempY < 0) {tempY = 0;}  

    return true;
}

function imageview(id, w, h)
{

    menu(id);

    var el_id = document.getElementById(id);

    //submenu = eval(name+".style");
    submenu = el_id.style;
    submenu.left = tempX - ( w + 11 );
    submenu.top  = tempY - ( h / 2 );

    selectBoxVisible();

    if (el_id.style.display != 'none')
        selectBoxHidden(id);
}

function help(id, left, top)
{
    menu(id);

    var el_id = document.getElementById(id);

    //submenu = eval(name+".style");
    submenu = el_id.style;
    submenu.left = tempX - 50 + left;
    submenu.top  = tempY + 15 + top;

    selectBoxVisible();

    if (el_id.style.display != 'none')
        selectBoxHidden(id);
}

// TEXTAREA 사이즈 변경
function textarea_size(fld, size)
{
	var rows = parseInt(fld.rows);

	rows += parseInt(size);
	if (rows > 0) {
		fld.rows = rows;
	}
}
var save_layer = null;
function layer_view(link_id, menu_id, opt, x, y)
{
    var link = document.getElementById(link_id);
    var menu = document.getElementById(menu_id);

    //for (i in link) { document.write(i + '<br/>'); } return;

    if (save_layer != null)
    {
        save_layer.style.display = "none";
        selectBoxVisible();
    }

    if (link_id == '')
        return;

    if (opt == 'hide')
    {
        menu.style.display = 'none';
        selectBoxVisible();
    }
    else
    {
        x = parseInt(x);
        y = parseInt(y);
        menu.style.left = get_left_pos(link) + x;
        menu.style.top  = get_top_pos(link) + link.offsetHeight + y;
        menu.style.display = 'block';
    }

    save_layer = menu;
}
</script>

<link rel="stylesheet" href="<?=$gb4['bbs_path']?>/admin.style.css" type="text/css">

<div id="gblog">

    <div id="head" class="head">

    <table border=0 cellpadding=0 cellspacing=1 width=100% bgcolor="#EFCA95">
    <tr>

    <td class=adm_menu onmouseover="layer_view('','','','','')"><a href="<?=$gb4['path']?>/gblog.index.php">블로그메인</a></td>
    <td class=adm_menu onmouseover="layer_view('','','','','')"><a href="<?=$current['blog_url']?>">내 블로그</a></td>
    <?
    foreach($amenu as $key=>$value)
    {
        $href1 = $href2 = "";
        if ($menu["menu{$key}"][0][2])
        {
            $href1 = "<a href='".$menu["menu{$key}"][0][2]."'>";
            $href2 = "</a>";
        }
        echo "<td class=adm_menu id='id_menu{$key}' onmouseover=\"layer_view('id_menu{$key}', 'menu_menu{$key}', 'view', 30, -5);\">{$href1}{$value}{$href2}";
        echo print_menu1("menu{$key}", 1);
        echo "</td>";
    }
    ?>
    
    </tr>
    </table>

    </div>

    <div id="main" onmouseover="layer_view('','','','','')">
