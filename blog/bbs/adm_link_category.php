<?
include_once("./_common.php");

$g4['title'] = "블로그 링크 분류 관리";

if( $current['mb_id'] != $member['mb_id'] )
    alert('자신의 블로그만 링크 분류를 설정할 수 있습니다.');

$category = array();
$q = sql_query("select * from {$gb4['link_category_table']} where blog_id='{$current['id']}' order by rank desc");
while($r = sql_fetch_array($q)) array_push($category, $r);


include_once("{$g4[path]}/head.sub.php");
include_once("./admin.head.php");
?>
<script type="text/javascript" src="<?=$g4['path']?>/js/prototype.js"></script>

<div class="adm_info">
    <b>링크 관리</b> : 즐겨찾는 웹사이트의 분류를 관리할 수 있습니다.
</div>

<table width=600 cellspacing=0 cellspacing=0 align=center>
<form name=form>
<input type=hidden id=mb_id name=mb_id value="<?=$current['mb_id']?>">
<tr><td>

<table border=0 cellpadding=0 cellspacing=0 width=95% align=center>
<tr>
    <td width=320 style="line-height:25px;">
        링크 분류 관리
        <select id=ct_list name=ct_list style="width:300px;" size=15 onclick="category_select()">
            <? for( $i=0; $c = array_pop($category); $i++) { ?>
            <option value="<?=$c['id']?>"><?=$c['category_name']?></option>
            <? } ?>
        </select>
        <table border=0 cellpadding=0 cellspacing=0 width=300>
        <tr>
            <td>
                <input type=button value=△ onclick=category_up()>
                <input type=button value=▽ onclick=category_down()>
            </td>
            <td align=right>
                <input type=button value=삭제 onclick=category_delete()>
            </td>
        </tr>
        </table>
    </td>
    <td valign=top style="line-height:25px;">
        새 링크 분류추가<br/>
        <input type=text size=20 maxlength=20 id=ct_name name=ct_name onKeyPress="if(event.keyCode==13) return false;"> <input type=button value=추가 onclick=category_insert()>
        <br/>
        <br/>
        링크 분류 수정<br/>
        <input type=text size=20 maxlength=20 id=ct_mod_name name=ct_mod_name onKeyPress="if(event.keyCode==13) return false;"> <input type=button value=변경 onclick=category_modify()>
        <br/>
    </td>
</tr>
</table>

</td></tr></form></table>

<script language=javascript>

function category_up() {
    mb_id = $("mb_id").value;
    ct_index = $("ct_list").selectedIndex;
    ct_id = $("ct_list").value;
    if( ct_index > 0 ) {
        md_id = $("ct_list").options[ct_index-1].value;
        tmp = $("ct_list").options[ct_index-1].text;
        $("ct_list").options[ct_index-1].text = $("ct_list").options[ct_index].text;
        $("ct_list").options[ct_index].text = tmp;
        tmp = $("ct_list").options[ct_index-1].value;
        $("ct_list").options[ct_index-1].value = $("ct_list").options[ct_index].value;
        $("ct_list").options[ct_index].value = tmp;
        $("ct_list").options[ct_index-1].selected = true;
        url = 'adm_link_category_update.php';
        send = 'm=up&mb_id='+mb_id+'&ct_id='+ct_id+'&md_id='+md_id;
        var myAjax = new Ajax.Request(
            url, 
            {
                method: 'post', 
                parameters: send
            });
    }
}

function category_down() {
    mb_id = $("mb_id").value;
    ct_index = $("ct_list").selectedIndex;
    ct_id = $("ct_list").value;
    if( 0 <= ct_index && ct_index < $("ct_list").options.length-1 ) {
        md_id = $("ct_list").options[ct_index+1].value;
        tmp = $("ct_list").options[ct_index+1].text;
        $("ct_list").options[ct_index+1].text = $("ct_list").options[ct_index].text;
        $("ct_list").options[ct_index].text = tmp;
        tmp = $("ct_list").options[ct_index+1].value;
        $("ct_list").options[ct_index+1].value = $("ct_list").options[ct_index].value;
        $("ct_list").options[ct_index].value = tmp;
        $("ct_list").options[ct_index+1].selected = true;
        url = 'adm_link_category_update.php';
        send = 'm=down&mb_id='+mb_id+'&ct_id='+ct_id+'&md_id='+md_id;
        var myAjax = new Ajax.Request(
            url, 
            {
                method: 'post', 
                parameters: send
            });
    }
}

function category_select() {
    ct_list = $("ct_list");
    ct_mod_name = $("ct_mod_name");
    ct_index = ct_list.selectedIndex;
    if( ct_index > -1 )
        ct_mod_name.value = ct_list.options[ct_index].text;
}

function category_modify() {
    mb_id = $("mb_id").value;
    ct_id = $("ct_list").value;
    ct_index = $("ct_list").selectedIndex;
    if( ct_index > -1 ) {
        ct_text = $("ct_list").options[ct_index].text;
        ct_mod_name = $("ct_mod_name").value;
        url = 'adm_link_category_update.php';
        send = 'm=mod&mb_id='+mb_id+'&ct_id='+ct_id+'&ct_mod_name='+encodeURIComponent(ct_mod_name);
        msg = '\''+ct_text+'\' 링크 분류를 \''+ct_mod_name+'\' 로 변경 하시겠습니까?';
        if( !confirm(msg) ) return false;
        var myAjax = new Ajax.Request(
            url, 
            {
                method: 'post', 
                parameters: send,
                onComplete: return_category_modify
            });
    } else {
        $("ct_mod_name").value = '';
    }
}
function return_category_modify(req) {
    err = false;
    result = req.responseText;
    switch(result) {
        case '101': alert('자신의 블로그만 링크 분류를 설정할 수 있습니다.'); err=true; break;
        case '102': alert('링크 분류 이름을 입력해주세요.'); err=true; break;
        case '105': alert('같은 이름의 링크 분류가 이미 존재합니다.'); err=true; break;
        case '109': alert('링크 분류가 존재하지 않습니다.'); location.reload(); err=true; break;
    }
    if( !err ) {
        ct_mod_name = $("ct_mod_name").value;
        ct_index = $("ct_list").selectedIndex;
        $("ct_list").options[ct_index].text = ct_mod_name;
    }
}
function category_delete() {
    ct_id = $("ct_list").value;
    ct_index = $("ct_list").selectedIndex;
    if( ct_index < 0 ) return false;
    ct_text = $("ct_list").options[ct_index].text;
    mb_id = $("mb_id").value;
    url = 'adm_link_category_update.php';
    send = 'm=del&mb_id='+mb_id+'&ct_id='+ct_id;
    msg = '\''+ct_text+'\' 링크 분류를 정말 삭제하시겠습니까?';
    if( !confirm(msg) ) return false;
    var myAjax = new Ajax.Request(
        url, 
        {
            method: 'post', 
            parameters: send,
            onComplete: return_category_delete
        });
}
function return_category_delete(req) {
    err = false;
    result = req.responseText;
    switch(result) {
        case '101': alert('자신의 블로그만 링크 분류를 설정할 수 있습니다.'); err=true; break;
        case '109': alert('링크 분류가 존재하지 않습니다.'); location.reload(); err=true; break;
    }
    if( !err ) {
        ct_index = $("ct_list").selectedIndex;
        ct_length = $("ct_list").options.length;
        $("ct_list").remove(ct_index);
        if( ct_length <= 1 ) return;
        if( ct_index == ct_length-1 )
            $("ct_list").options[ct_index-1].selected = true;
        else
            $("ct_list").options[ct_index].selected = true;
    }
}
function category_insert() {
    mb_id = $("mb_id").value;
    ct_name = encodeURIComponent($("ct_name").value);
    //if( ct_name.length > 20 ) { alert("20자 까지만 입력해주세요."); return false; }
    url = "adm_link_category_update.php?";
    send = "m=new&mb_id=" + mb_id + "&ct_name=" + ct_name ;
    var myAjax = new Ajax.Request(
        url, 
        {
            method: 'post', 
            parameters: send,
            onComplete: return_category_insert
        });
}
function return_category_insert(req) {
    err = false;
    result = req.responseText;
    switch(result) {
        case '101': alert('자신의 블로그만 링크 분류를 설정할 수 있습니다.'); err=true; break;
        case '102': alert('링크 분류 이름을 입력해주세요.'); err=true; break;
        case '105': alert('같은 이름의 링크 분류가 이미 존재합니다.'); err=true; break;
    }
    if( !err ) {
        result  = result.split('|');
        index   = $("ct_list").options.length;
        ct_name = $("ct_name").value;
        ct_id   = result[1];
        $("ct_list").options[index] = new Option(ct_name, ct_id, false, false);
        $("ct_list").options[index].selected = true;
        $("ct_name").value = '';
        $("ct_name").focus();
    }
}

</script>
<?
include_once("./admin.tail.php");
include_once("{$g4[path]}/tail.sub.php");
?>