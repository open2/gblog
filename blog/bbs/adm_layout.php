<?
include_once("./_common.php");

$g4['title'] = "��α� ���̾ƿ� ����";

if( $member['mb_id'] != $current['mb_id'] )
    alert('�ڽ��� ��α׸� ���̾ƿ� ������ �� �� �ֽ��ϴ�.');

$sidebar_garbage = get_sidebar_list('garbage');
//$sidebar_top     = get_sidebar_list('top');
//$sidebar_bottom  = get_sidebar_list('bottom');

// ��Ų ȣ��
include_once("{$g4[path]}/head.sub.php");
include_once("./admin.head.php");
?>
<script type="text/javascript" src="<?=$g4['path']?>/js/prototype.js"></script>

<script type="text/javascript">
	var djConfig = { isDebug: true };
</script>

<script type="text/javascript" src="<?=$g4['path']?>/js/dojo.js"></script>
<script type="text/javascript">

dojo.require("dojo.dnd.*");
dojo.require("dojo.event.*");

function init_sub(name) {
    var area = $(name);
    new dojo.dnd.HtmlDropTarget(area, ["sidebar"]);
    var lis = area.getElementsByTagName("li");
    for(var x=0; x<lis.length; x++){
        new dojo.dnd.HtmlDragSource(lis[x], "sidebar");
    }
}

function init() {
    //init_sub("sidebar_top");
    init_sub("sidebar_right");
    //init_sub("sidebar_bottom");
    init_sub("sidebar_left");
    init_sub("garbage");
}

function move_left() {
    var right = $("sidebar_right");
    var left  = $("sidebar_left");
    var lis   = right.getElementsByTagName("li");
    var max   = lis.length;
    for(var x=0; x<max; x++){
        left.appendChild(lis[0]);
    }
}

function move_right() {
    var right = $("sidebar_right");
    var left  = $("sidebar_left");
    var lis   = left.getElementsByTagName("li");
    var max   = lis.length;
    for(var x=0; x<max; x++){
        right.appendChild(lis[0]);
    }
}

function save_sub(name) {
    var list = "";
    var area = $(name);
    var lis = area.getElementsByTagName("li");
    for(var x=0; x<lis.length; x++) {
        if(name=='garbage' && lis[x].id=='admin') {
            return false;
        }
        list += lis[x].id + ',';
    }
    return list;
}

function reset() {

    if (!confirm("���� ���̾ƿ��� �ʱ�ȭ �Ͻðڽ��ϱ�?")) return;

    var list_top    = '';
    var list_bottom = '';
    var list_right  = '';
    var list_left   = '';
    var list_garbage= '';

    var param = "";
    param = param + "mb_id=<?=$mb_id?>";
    param = param + "&list_top="+list_top;
    param = param + "&list_bottom="+list_bottom;
    param = param + "&list_left="+list_left;
    param = param + "&list_right="+list_right;
    param = param + "&list_garbage="+list_garbage;

    url = "adm_layout_update.php";
    var myAjax = new Ajax.Request(
        url, 
        {
            method: 'post', 
            parameters: param, 
            onComplete: function () {
                location.reload();
            }
        });
    
}

function save() {
    //var list_top    = save_sub("sidebar_top");
    var list_right  = save_sub("sidebar_right");
    //var list_bottom = save_sub("sidebar_bottom");
    var list_left   = save_sub("sidebar_left");
    var list_garbage= save_sub("garbage");

    if (!list_garbage) {
        alert('�����ڹ�ư�� �ݵ�� ����ؾ� �մϴ�.');
        return;
    }

    var param = "";
    param = param + "mb_id=<?=$mb_id?>";
    //param = param + "&list_top="+list_top;
    //param = param + "&list_bottom="+list_bottom;
    param = param + "&list_left="+list_left;
    param = param + "&list_right="+list_right;
    param = param + "&list_garbage="+list_garbage;

    url = "adm_layout_update.php";
    var myAjax = new Ajax.Request(
        url, 
        {
            method: 'post', 
            parameters: param, 
            onComplete: save_result
        });
    
}

function save_result(req) {
    errnum = get_xml(req.responseXML, "errnum");
    if( errnum == '000' ) {
        alert('���������� ����Ǿ����ϴ�.');
    } else { 
        alert('������ ���Ͽ� ������� ���߽��ϴ�.');
    }
}

function get_xml(xml, node) {
    items = xml.getElementsByTagName("items");
    return items[0].getElementsByTagName(node)[0].firstChild.nodeValue;
}

dojo.event.connect(dojo, "loaded", "init");

</script>

<style>
ul { 
    margin:0px; 
    padding:10px;
    border:1px solid #ccc;
    background-color:#efefef;
}
li {
    background-color:#F7F7F7;
    list-style:none;
    text-align:center; 
    width:105px;
    cursor:move;
    font-weight:bold;
    font-size:12px;
    color:#000;
    border:1px solid #ccc;
    border-right:1px solid #5E5E5E;
    border-bottom:1px solid #5E5E5E;
    margin-bottom:5px;
    line-height:25px;
}
</style>


<table border=0 cellpadding=3 cellspacing=0 width=750 align=center>
<!--
<tr align=center>
    <td colspan=4> ��� </td>
</tr>
<tr align=center>
    <td colspan=4>
        <ul id="sidebar_top">
        <?for($i=0, $max=count($sidebar_top); $i<$max; $i++) {?>
            <li id="<?=$sidebar_top[$i]?>"><?=$sidebar_define[$sidebar_top[$i]]?></li>
        <?}?>
        </ul>
    </td>
</tr>
-->
<tr align=center>
    <td> ���� ���̵�� </td>
    <td> ���� </td>
    <td> ������ ���̵�� </td>
    <td> �̻�� </td>
</tr>
<tr>
    <td width=130 valign=top>
        <ul id="sidebar_left">
        <?for($i=0, $max=count($sidebar_left); $i<$max; $i++) {?>
            <li id="<?=$sidebar_left[$i]?>"><?=$sidebar_define[$sidebar_left[$i]]?></li>
        <?}?>
        </ul>
    </td>
    <td align=center valign=top>
        <table border=0 cellpadding=0 cellspacing=1 bgcolor="#cccccc" width="100%" height="200">
        <tr>
            <td bgcolor="#cccccc" align=center>
                <input type=button value="<< ��� ��������" onclick=move_left()>
                &nbsp;
                <input type=button value="��� ���������� >>" onclick=move_right()>
            </td>
        </tr>
        </table>
        <p align=center>
            <input type=button value="�����ϱ�" onclick="save()" style="width:100px; height:50px;">
        </p>
    </td>
    <td width=130 align=right valign=top>
        <ul id="sidebar_right">
        <?for($i=0, $max=count($sidebar_right); $i<$max; $i++) {?>
            <li id="<?=$sidebar_right[$i]?>"><?=$sidebar_define[$sidebar_right[$i]]?></li>
        <?}?>
        </ul>
    </td>
    <td colspan=3 width=130 valign=top align=center>
        <ul id="garbage" style="background-color:#F7D595">
        <?for($i=0, $max=count($sidebar_garbage); $i<$max; $i++) {?>
            <li id="<?=$sidebar_garbage[$i]?>"><?=$sidebar_define[$sidebar_garbage[$i]]?></li>
        <?}?>
        </ul>
        <input type=button value="�ʱ�ȭ" onclick="reset()" style="width:80px; height:30px;">
    </td>
</tr>
<!--
<tr align=center>
    <td colspan=4> �ϴ� </td>
</tr>
<tr align=center>
    <td colspan=4>
        <ul id="sidebar_bottom">
        <?for($i=0, $max=count($sidebar_bottom); $i<$max; $i++) {?>
            <li id="<?=$sidebar_bottom[$i]?>"><?=$sidebar_define[$sidebar_bottom[$i]]?></li>
        <?}?>
        </ul>
    </td>
</tr>
-->
</table>

<?
include_once("./admin.tail.php");
include_once("{$g4[path]}/tail.sub.php");
?>