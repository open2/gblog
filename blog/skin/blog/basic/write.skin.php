<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ�

include_once("$g4[path]/lib/cheditor4.lib.php");
echo "<script type='text/javascript' src='$g4[cheditor4_path]/cheditor.js'></script>";
echo cheditor1('content', '100%', '450px');
?>

<form name="fwrite" method="post" onsubmit="return fwrite_submit(this);" enctype="multipart/form-data" style="margin:0px;">
<table width="100%" cellpadding=0 cellspacing=0 align=center>
<input type=hidden name=m       value="<?=$m?>">
<input type=hidden name=id      value="<?=$id?>">
<input type=hidden name=mb_id   value="<?=$current[mb_id]?>">
<input type=hidden name=url     value="<?=$urlencode?>">
<input type=hidden name=me      value="<?=$me?>">
<input type=hidden name=page    value="<?=$page?>">
<input type=hidden name=cate    value="<?=$cate?>">
<tr><td>

<table border=0 cellpadding=0 cellspacing=0 width=100%>
<tr>
    <td width=70 height=30> �з� ���� </td>
    <td>
        <select name="category_id">
            <option value="">��ü</option>
            <?for($i=0; $i<count($category); $i++){?>
            <option value="<?=$category[$i]['id']?>"<?if($category_id==$category[$i]['id']) echo ' selected'?>><?=$category[$i]['category_name']?></option>
            <?}?>
        </select>
    </td>
</tr>
<tr>
    <td width=70 height=30> ���� </td>
    <td>
        <input type=text name=title style="width:98%;" maxlength=100 value="<?=$title?>" required itemname="����">
    </td>
</tr>
<tr>
    <td colspan=2>
        <?=cheditor2('content', $content);?>
    </td>
</tr>

<? if ($gb4[upload_file_number] > 0) { 
    // �ִ� ���� ���ε� ����
    $board[bo_upload_count] = $gb4[upload_file_number];

    // �Ʒ��κ��� �״�4�� write.skin.php�� �״�� copy
?>
<tr>
    <td width=70px height=30px valign=top><table cellpadding=0 cellspacing=0><tr><td style=" padding-top: 10px;">���� <span onclick="add_file();" style='cursor:pointer; font-family:tahoma; font-size:12pt;'>+</span> <span onclick="del_file();" style='cursor:pointer; font-family:tahoma; font-size:12pt;'>-</span></td></tr></table></td>
    <td style='padding:5 0 5 0;'><table id="variableFiles" cellpadding=0 cellspacing=0></table><?// print_r2($file); ?>
        <script language="JavaScript">
        var flen = 0;
        function add_file(delete_code)
        {
            var upload_count = <?=(int)$board[bo_upload_count]?>;
            if (upload_count && flen >= upload_count)
            {
                alert("�� �Խ����� "+upload_count+"�� ������ ���� ���ε尡 �����մϴ�.");
                return;
            }

            var objTbl;
            var objRow;
            var objCell;
            if (document.getElementById)
                objTbl = document.getElementById("variableFiles");
            else
                objTbl = document.all["variableFiles"];

            objRow = objTbl.insertRow(objTbl.rows.length);
            objCell = objRow.insertCell(0);

            objCell.innerHTML = "<input type='file' class='field_pub_01' name='bf_file[]' title='���� �뷮 <?=$upload_max_filesize?> ���ϸ� ���ε� ����'>";
            if (delete_code)
                objCell.innerHTML += delete_code;
            else
            {
                <? if ($is_file_content) { ?>
                objCell.innerHTML += "<br><input type='text' class='field_pub_01' size=50 name='bf_content[]' title='���ε� �̹��� ���Ͽ� �ش� �Ǵ� ������ �Է��ϼ���.'> ������ ������ �Է��ϼ���.";
                <? } ?>
                ;
            }

            flen++;
        }

        <?=$file_script; //�����ÿ� �ʿ��� ��ũ��Ʈ?>

        function del_file()
        {
            // file_length ���Ϸδ� �ʵ尡 �������� �ʾƾ� �մϴ�.
            var file_length = <?=(int)$file_length?>;
            var objTbl = document.getElementById("variableFiles");
            if (objTbl.rows.length - 1 > file_length)
            {
                objTbl.deleteRow(objTbl.rows.length - 1);
                flen--;
            }
        }
        </script></td>
</tr>
<? } ?>

<?
$qry = sql_query("select * from {$gb4[division_table]} order by dv_rank");
if (mysql_num_rows($qry)>0) {
?>
<tr>
    <td width=70 height=30> ������ </td>
    <td>
        <select name="division_id">
        <option value=0<?if(!$division_id) echo ' selected'?>> ���þ��� </option>
        <? while ($res=sql_fetch_array($qry)) { ?>
        <option value=<?=$res['dv_id']?><?if($res['dv_id']==$division_id) echo ' selected'?>> <?=$res['dv_name']?> </option>
        <?}?>
        </select>
    </td>
</tr>
<?}?>
<? if ($use_tag) { ?>
<tr>
    <td width=70 height=30> �±� </td>
    <td>
        <input type=text name=tag maxlength=100 size=50 value="<?=$tag?>"> �ĸ��� ���� (��: ��α�,�״�����,SIR)
        <?
        // ���� ����� �±׵�
        if (count($tags_array))
            echo "<BR>";
        echo implode(", ", $tags_array);
        
        // ����α� ��ü���� ����� �±׵�
        if (count($tags_array_gblog))
            echo "<BR>";
        echo implode(", ", $tags_array_gblog);
        ?>
    </td>
</tr>
<script language="javascript">
function add_tag(target_id, tag_id) {
    
}
</script>
<? } ?>
<tr>
    <td width=70 height=30> Ʈ���� </td>
    <td>
        <input type=text name=trackback_url maxlength=255 size=50 value="<?=$trackback_url?>">
        <? if ($m == 'm') { ?>
        <input type=checkbox name=ping value=1> �� ����
        <? } ?>
    </td>
</tr>
<tr>
    <td width=70 height=30> ���ۼ� �Ͻ� </td>
    <td>
        <input type=text name=post_date maxlength=19 style="width:130px;" value="<?=$post_date?>" required itemname="���ۼ� �Ͻ�">
        <input type=checkbox name=reload <?=$reload?>> ����
    </td>
</tr>
<tr>
    <td width=70 height=30> �������� </td>
    <td>
        <input type=radio name=secret value=1<?if($secret==1) echo ' checked'?>> ����
        <input type=radio name=secret value=0<?if($secret==0) echo ' checked'?>> �����
        (����� ���ý� RSS �� �������� �ʽ��ϴ�.)
        <!--<input type=radio name=secret value=2<?if($secret==2) echo ' checked'?>> ����-->
    </td>
</tr>
<tr>
    <td width=70 height=30> RSS ���� </td>
    <td>
        <input type=radio name=use_rss value=1<?if($use_rss==1) echo ' checked'?>> ����
        <input type=radio name=use_rss value=0<?if($use_rss==0) echo ' checked'?>> �����
        <!--<input type=radio name=secret value=2<?if($secret==2) echo ' checked'?>> ����-->
    </td>
</tr>
<tr>
    <td width=70 height=30> ���� </td>
    <td>
        <input type=checkbox name=use_comment id=use_comment value=1 <?=$use_comment?>> <label for=use_comment>�� �ۿ� ����� �� �� �ֽ��ϴ�.</label><br/>
        <input type=checkbox name=use_trackback id=use_trackback value=1 <?=$use_trackback?>> <label for=use_trackback>�� �ۿ� Ʈ������ ������ �ֽ��ϴ�.</label>
    </td>
</tr>
<? if ($current[use_ccl] || $use_ccl_writer || $use_ccl_commecial || $use_ccl_modify || $use_ccl_allow) {?>
<tr>
    <td width=70 height=30> ���۱� </td>
    <td>
        <input type=checkbox name=use_ccl_writer id=use_ccl_writer value=1 <?=$use_ccl_writer?>> <label for=use_ccl_writer>������ǥ��</label>
        <input type=checkbox name=use_ccl_commecial id=use_ccl_commecial value=1 <?=$use_ccl_commecial?>> <label for=use_ccl_commecial>�񿵸�</label>
        <input type=checkbox name=use_ccl_modify id=use_ccl_modify value=1 <?=$use_ccl_modify?>> <label for=use_ccl_modify>�������</label>
        <input type=checkbox name=use_ccl_allow id=use_ccl_allow value=1 <?=$use_ccl_allow?>> <label for=use_ccl_allow>�������Ǻ������</label>
        &nbsp;&nbsp;<a href="http://www.creativecommons.or.kr/info/about" target=_new>(<b>CCL�� ���ؼ�</b>)</a>
    </td>
</tr>
<? } ?>
</table>

<p align=center>
    <INPUT type=image width="66" height="20" src="<?=$blog_skin_url?>/img/btn_ok.gif" border=0 accesskey='s'>&nbsp;
    <a href="<?=$member['blog_url']?>"><img id="btn_list" src="<?=$blog_skin_url?>/img/btn_list.gif" border=0></a>
</p>

</td></tr></table>
</form>

<script language="javascript">
with (document.fwrite) {
    if (typeof(title) != "undefined")
        title.focus();
    else if (typeof(content) != "undefined")
        content.focus();
}

function fwrite_submit(f) {
  
    <? echo cheditor3('content'); ?>

    if (document.getElementById('tx_content')) {
        if (!ed_content.inputLength()) { 
            alert('������ �Է��Ͻʽÿ�.'); 
            ed_content.returnFalse();
            return false;
        }
    }
    
    f.action = './adm_write_update.php';
    
    return true;
}
</script>