<?
include_once("./_common.php");

$g4['title'] = "��α� ��ũ ����";

if( $current['mb_id'] != $member['mb_id'] )
    alert('�ڽ��� ��α׸� ��ũ�� ������ �� �ֽ��ϴ�.');

if( !strlen(trim($ct_id)) ) {
    $res = sql_fetch(" select * from {$gb4['link_category_table']} where blog_id='{$current['id']}' order by rank limit 1");
    $ct_id = $res['id'];
}

$category = array();
$q = sql_query("select * from {$gb4['link_category_table']} where blog_id='{$current['id']}' order by rank");
while($r = sql_fetch_array($q)) array_push($category, $r);

$link = array();
$q = sql_query("select * from {$gb4['link_table']} where blog_id='{$current['id']}' and category_id='{$ct_id}' order by rank");
while($r = sql_fetch_array($q)) array_push($link, $r);


include_once("{$g4[path]}/head.sub.php");
include_once("./admin.head.php");
?>
<script type="text/javascript" src="<?=$g4['path']?>/js/prototype.js"></script>

<div class="adm_info">
    <b>��ũ ����</b> : ���ã�� ������Ʈ�� �����Ͻ� �� �ֽ��ϴ�.
</div>


<table width=600 cellspacing=0 cellspacing=0 align=center>
<form name=form>
<input type=hidden id=mb_id name=mb_id value="<?=$current['mb_id']?>">
<input type=hidden id=ct_id name=ct_id value="<?=$ct_id?>">
<tr><td>
<tr><td>

<table border=0 cellpadding=0 cellspacing=0 width=95% align=center>
<tr>
    <td colspan="2">
        �з� ����
        <select name="ct_id" onChange="link_list_reload(this.value);">
        <? for($i=0; $i<count($category); $i++) { ?>
        <option value="<?=$category[$i]['id']?>" <?if($ct_id==$category[$i]['id']) echo ' selected'?>> <?=$category[$i]['category_name']?> </option>
        <? } ?>
        <option value="0" <?if($ct_id==0) echo ' selected'?>> ��Ÿ </option>
        </select>
    </td>
</tr>
<tr>
    <td width=320 style="line-height:25px;">
        ��ũ ����
        <select id=link_list name=link_list style="width:300px;" size=15 onchange="link_select()">
            <? for( $i=0; $i<count($link); $i++) { ?>
            <option value="<?=$link[$i]['id']?>"><?=$link[$i]['site_name']?></option>
            <? } ?>
        </select>
        

        <table border=0 cellpadding=0 cellspacing=0 width=300>
        <tr>
            <td>
                <input type=button value=�� onclick=link_up()>
                <input type=button value=�� onclick=link_down()>
            </td>
            <td align=right>
                <input type=button value=���� onclick=link_delete()>
            </td>
        </tr>
        </table>

    </td>
    <td valign=top style="line-height:25px;">
        �� ��ũ�߰�<br/>
        �̸� : <input type=text size=30 maxlength=20 id=link_name name=link_name onKeyPress="if(event.keyCode==13) return false;"><br/>
        �ּ� : <input type=text size=30 maxlength=100 id=link_url name=link_url onKeyPress="if(event.keyCode==13) return false;"> <input type=button value=�߰� onclick=link_insert()>
        <br/>
        <br/>
        ��ũ ����<br/>
        �̸� : <input type=text size=30 maxlength=20 id=link_mod_name name=link_mod_name onKeyPress="if(event.keyCode==13) return false;"> <br/>
        �ּ� : <input type=text size=30 maxlength=100 id=link_mod_url name=link_mod_url onKeyPress="if(event.keyCode==13) return false;"> <br/>
        <input type=button value=���� onclick=link_modify()>
        <br/>
        <div id="link_urls" style="margin:0;padding:0;">
        <? for( $i=0; $i<count($link); $i++) { ?>
        <input type="hidden" name="link_url_list<?=$i?>" id="link_url_list<?=$i?>" value="<?=$link[$i]['site_url']?>">
        <? } ?>
        </div>

    </td>
</tr>
</table>

</td></tr></form></table>

<script language=javascript>

function link_up() {
    mb_id = $("mb_id").value;
    ct_id = $("ct_id").value;
    link_index = $("link_list").selectedIndex;
    link_id = $("link_list").value;
    if( link_index > 0 ) {
        md_id = $("link_list").options[link_index-1].value;
        tmp = $("link_list").options[link_index-1].text;
        $("link_list").options[link_index-1].text = $("link_list").options[link_index].text;
        $("link_list").options[link_index].text = tmp;
        tmp = $("link_list").options[link_index-1].value;
        $("link_list").options[link_index-1].value = $("link_list").options[link_index].value;
        $("link_list").options[link_index].value = tmp;
        $("link_list").options[link_index-1].selected = true;
        url = 'adm_link_update.php';
        send = 'm=up&mb_id='+mb_id+'&link_id='+link_id+'&ct_id='+ct_id+'&md_id='+md_id;
        var myAjax = new Ajax.Request(
            url, 
            {
                method: 'post', 
                parameters: send
            });
    }
}

function link_down() {
    mb_id = $("mb_id").value;
    ct_id = $("ct_id").value;
    link_index = $("link_list").selectedIndex;
    link_id = $("link_list").value;
    if( 0 <= link_index && link_index < $("link_list").options.length-1 ) {
        md_id = $("link_list").options[link_index+1].value;
        tmp = $("link_list").options[link_index+1].text;
        $("link_list").options[link_index+1].text = $("link_list").options[link_index].text;
        $("link_list").options[link_index].text = tmp;
        tmp = $("link_list").options[link_index+1].value;
        $("link_list").options[link_index+1].value = $("link_list").options[link_index].value;
        $("link_list").options[link_index].value = tmp;
        $("link_list").options[link_index+1].selected = true;
        url = 'adm_link_update.php';
        send = 'm=down&mb_id='+mb_id+'&link_id='+link_id+'&ct_id='+ct_id+'&md_id='+md_id;
        var myAjax = new Ajax.Request(
            url, 
            {
                method: 'post', 
                parameters: send
            });
    }
}

function link_select() {
    link_list = $("link_list");
    link_url_list = $("link_url_list");
    link_mod_name = $("link_mod_name");
    link_mod_url = $("link_mod_url");
    link_index = link_list.selectedIndex;
    if( link_index > -1 ) {
        link_mod_name.value = link_list.options[link_index].text;
        link_mod_url.value = $("link_url_list"+link_index).value;
    }
}

function link_modify() {
    mb_id = $("mb_id").value;
    ct_id = $("ct_id").value;
    link_id = $("link_list").value;
    link_index = $("link_list").selectedIndex;
    if( link_index > -1 ) {
        link_text = $("link_list").options[link_index].text;
        link_mod_name = $("link_mod_name").value;
        link_mod_url = $("link_mod_url").value;
        url = 'adm_link_update.php';
        send = 'm=mod&mb_id='+mb_id+'&ct_id='+ct_id+'&link_id='+link_id+'&link_mod_name='+encodeURIComponent(link_mod_name)+'&link_mod_url='+encodeURIComponent(link_mod_url);
        msg = '���� �Ͻðڽ��ϱ�?';
        if( !confirm(msg) ) return false;
        var myAjax = new Ajax.Request(
            url, 
            {
                method: 'post', 
                parameters: send,
                onComplete: return_link_modify
            });
    } else {
        $("link_mod_name").value = '';
        $("link_mod_url").value = '';
    }
}
function return_link_modify(req) {
    err = false;
    result = req.responseText;
    switch(result) {
        case '101': alert('�ڽ��� ��α׸� ��ũ�� ������ �� �ֽ��ϴ�.'); err=true; break;
        case '102': alert('��ũ �̸��� �Է����ּ���.'); err=true; break;
        case '105': alert('���� �̸��� ��ũ�� �̹� �����մϴ�.'); err=true; break;
        case '109': alert('��ũ�� �������� �ʽ��ϴ�.'); location.reload(); err=true; break;
        case '000': break;
        default: alert(result); return; break;
    }
    if( !err ) {
        alert('���� �߽��ϴ�.');
        link_mod_name = $("link_mod_name").value;
        link_index = $("link_list").selectedIndex;
        $("link_list").options[link_index].text = link_mod_name;
        $("link_url_list"+link_index).value = link_mod_url;
        $("link_name").value = '';
        $("link_url").value = '';
    }
}
function link_delete() {
    link_id = $("link_list").value;
    link_index = $("link_list").selectedIndex;
    if( link_index < 0 ) return false;
    link_text = $("link_list").options[link_index].text;
    mb_id = $("mb_id").value;
    ct_id = $("ct_id").value;
    url = 'adm_link_update.php';
    send = 'm=del&mb_id='+mb_id+'&ct_id='+ct_id+'&link_id='+link_id;
    msg = '\''+link_text+'\' ��ũ�� ���� �����Ͻðڽ��ϱ�?';
    if( !confirm(msg) ) return false;
    var myAjax = new Ajax.Request(
        url, 
        {
            method: 'post', 
            parameters: send,
            onComplete: return_link_delete
        });
}
function return_link_delete(req) {
    err = false;
    result = req.responseText;
    switch(result) {
        case '101': alert('�ڽ��� ��α׸� ��ũ�� ������ �� �ֽ��ϴ�.'); err=true; break;
        case '109': alert('��ũ�� �������� �ʽ��ϴ�.'); location.reload(); err=true; break;
    }
    if( !err ) {
        link_index = $("link_list").selectedIndex;
        link_length = $("link_list").options.length;
        $("link_list").remove(link_index);
        if( link_length <= 1 ) return;
        if( link_index == link_length-1 )
            $("link_list").options[link_index-1].selected = true;
        else
            $("link_list").options[link_index].selected = true;
    }
}
function link_insert() {
    mb_id = $("mb_id").value;
    ct_id = $("ct_id").value;
    link_name = encodeURIComponent($("link_name").value);
    link_url = encodeURIComponent($("link_url").value);
    //if( link_name.length > 20 ) { alert("20�� ������ �Է����ּ���."); return false; }
    send = "m=new&mb_id=" + mb_id + "&ct_id="+ct_id+"&link_name=" + link_name + "&link_url=" + link_url ;
    url = "adm_link_update.php";
    var myAjax = new Ajax.Request(
        url, 
        {
            method: 'post', 
            parameters: send,
            onComplete: return_link_insert
        });
}
function return_link_insert(req) 
{
    var errnum = req.responseText;
    switch(errnum) {
        case '000': link_list_reload($F('ct_id')); break;
        case '101': alert('�ڽ��� ��α׸� ��ũ�� ������ �� �ֽ��ϴ�.'); break;
        case '102': alert('��ũ �̸��� �Է����ּ���.'); break;
        case '103': alert('��ũ �ּҸ� �Է����ּ���.'); break;
        case '105': alert('���� �̸��� ��ũ�� �̹� �����մϴ�.'); break;
        default: alert('������ �߻��Ͽ����ϴ�. ' + errnum + ' Error');  break;
    }
}
function link_list_reload(ct_id) 
{
    $("link_name").value = '';
    $("link_url").value = '';
    $("link_mod_name").value = '';
    $("link_mod_url").value = '';

    $('ct_id').value = ct_id;
    url = 'adm_link_update.php';
    send = 'm=reload&mb_id='+$F('mb_id')+'&ct_id='+ct_id;
    var myAjax = new Ajax.Request(
        url, 
        {
            method: 'post', 
            parameters: send,
            onComplete: return_link_list_reload
        });
}
function return_link_list_reload(req) 
{
    var channel = req.responseXML.getElementsByTagName('channel')[0];

    $('link_list').options.length = 0;
    var item = channel.getElementsByTagName('item');
    len = item.length;

    $('link_urls').innerHTML = '';
    $('link_list').options.length = len;
    for(i=0; i<len; i++) {
        id        = item[i].getElementsByTagName('id')[0].firstChild.nodeValue;
        site_url  = item[i].getElementsByTagName('site_url')[0].firstChild.nodeValue;
        site_name = item[i].getElementsByTagName('site_name')[0].firstChild.nodeValue;
        $('link_list').options[i].text  = site_name;
        $('link_list').options[i].value = id;
        $('link_urls').innerHTML        = $('link_urls').innerHTML + "<input type='hidden' name='ink_url_list"+i+"' id='link_url_list"+i+"' value='"+site_url+"'>";
        $('link_url_list'+i).value      = site_url;
    }
}

</script>
<?
include_once("./admin.tail.php");
include_once("{$g4[path]}/tail.sub.php");
?>