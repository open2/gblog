<?
include_once("./_common.php");

auth_check($auth[$sub_menu], "r");

$g4[title] = "블로그 분류관리";

$division = array();
$q = sql_query("select * from {$gb4['division_table']} order by dv_rank desc");
while($r = sql_fetch_array($q)) array_push($division, $r);

include_once("../admin.head.php");

?>
<script language="javascript" src="<?=$g4['path']?>/js/prototype.js"></script>

<table width=600 cellspacing=0 cellspacing=0 align=center>
<form name=form>
<input type=hidden id=mb_id name=mb_id value="<?=$current['mb_id']?>">
<tr><td>

<table border=0 cellpadding=0 cellspacing=0 width=95% align=center>
<tr>
    <td width=320 style="line-height:25px;">
        분류 관리
        <select id=ct_list name=ct_list style="width:300px;" size=15 onclick="division_select()">
            <? for( $i=0; $d = array_pop($division); $i++) { ?>
            <option value="<?=$d['dv_id']?>"><?=$d['dv_name']?></option>
            <? } ?>
        </select>
        <table border=0 cellpadding=0 cellspacing=0 width=300>
        <tr>
            <td>
                <input type=button value=△ onclick=division_up()>
                <input type=button value=▽ onclick=division_down()>
            </td>
            <td align=right>
                <input type=button value=삭제 onclick=division_delete()>
            </td>
        </tr>
        </table>
    </td>
    <td valign=top style="line-height:25px;">
        새 분류추가<br/>
        <input type=text size=20 maxlength=20 id=ct_name name=ct_name onKeyPress="if(event.keyCode==13) return false;"> <input type=button value=추가 onclick=division_insert()>
        <br/>
        <br/>
        분류 수정<br/>
        <input type=text size=20 maxlength=20 id=ct_mod_name name=ct_mod_name onKeyPress="if(event.keyCode==13) return false;"> <input type=button value=변경 onclick=division_modify()>
        <br/>
    </td>
</tr>
</table>

</td></tr></form></table>

<script language=javascript>

function division_up() {
    
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

        url = 'division_update.php';
        send = 'mode=up&ct_id='+ct_id+'&md_id='+md_id;

        var myAjax = new Ajax.Request(
            url, 
            {
                method: 'post', 
                parameters: send
            });
    }
}

function division_down() {
    
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
        url = 'division_update.php';
        send = 'mode=down&ct_id='+ct_id+'&md_id='+md_id;
        var myAjax = new Ajax.Request(
            url, 
            {
                method: 'post', 
                parameters: send
            });
    }
}

function division_select() {
    ct_list = $("ct_list");
    ct_mod_name = $("ct_mod_name");
    ct_index = ct_list.selectedIndex;
    if( ct_index > -1 )
        ct_mod_name.value = ct_list.options[ct_index].text;
}

function division_modify() {
    
    ct_id = $("ct_list").value;
    ct_index = $("ct_list").selectedIndex;
    if( ct_index > -1 ) {
        ct_text = $("ct_list").options[ct_index].text;
        ct_mod_name = $("ct_mod_name").value;
        msg = '\''+ct_text+'\' 분류를 \''+ct_mod_name+'\' 로 변경 하시겠습니까?';
        url = 'division_update.php';
        send = 'mode=mod&ct_id='+ct_id+'&ct_mod_name='+encodeURIComponent(ct_mod_name);
        if( !confirm(msg) ) return false;
        var myAjax = new Ajax.Request(
            url, 
            {
                method: 'post', 
                parameters: send,
                onComplete: return_division_modify
            });
    } else {
        $("ct_mod_name").value = '';
    }
}
function return_division_modify(req) {
    err = false;
    result = req.responseText;
    switch(result) {
        case '101': alert('자신의 블로그만 분류를 설정할 수 있습니다.'); err=true; break;
        case '102': alert('분류 이름을 입력해주세요.'); err=true; break;
        case '105': alert('같은 이름의 분류가 이미 존재합니다.'); err=true; break;
        case '109': alert('분류가 존재하지 않습니다.'); location.reload(); err=true; break;
    }
    if( !err ) {
        ct_mod_name = $("ct_mod_name").value;
        ct_index = $("ct_list").selectedIndex;
        $("ct_list").options[ct_index].text = ct_mod_name;
    }
}
function division_delete() {
    ct_id = $("ct_list").value;
    ct_index = $("ct_list").selectedIndex;
    if( ct_index < 0 ) return false;
    ct_text = $("ct_list").options[ct_index].text;
    
    url = 'division_update.php';
    send = 'mode=del&ct_id='+ct_id;
    msg = '\''+ct_text+'\' 분류를 정말 삭제하시겠습니까?';
    if( !confirm(msg) ) return false;
    var myAjax = new Ajax.Request(
        url, 
        {
            method: 'post', 
            parameters: send,
            onComplete: return_division_delete
        });
}
function return_division_delete(req) {
    err = false;
    result = req.responseText;
    switch(result) {
        case '101': alert('자신의 블로그만 분류를 설정할 수 있습니다.'); err=true; break;
        case '109': alert('분류가 존재하지 않습니다.'); location.reload(); err=true; break;
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
function division_insert() {
    ct_name = encodeURIComponent($("ct_name").value);
    url = 'division_update.php';
    pam = "mode=new&ct_name=" + ct_name;
    var myAjax = new Ajax.Request( url, {method: 'post', parameters: pam, onComplete: return_division_insert });
}
function return_division_insert(req) {
    err = false;
    result = req.responseText;
    switch(result) {
        case '101': alert('자신의 블로그만 분류를 설정할 수 있습니다.'); err=true; break;
        case '102': alert('분류 이름을 입력해주세요.'); err=true; break;
        case '105': alert('같은 이름의 분류가 이미 존재합니다.'); err=true; break;
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
include_once("../admin.tail.php");
?>