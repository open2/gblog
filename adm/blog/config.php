<?
include_once("./_common.php");

auth_check($auth[$sub_menu], "r");

$gb4[title] = "��α� �⺻����";

include_once("$g4[admin_path]/admin.head.php");

if( !trim(strlen($gb4['make_level']))             ) $gb4['make_level'] = 2;
if( !trim(strlen($gb4['make_point']))             ) $gb4['make_point'] = 0;
if( !trim(strlen($gb4['upload_blog_file_size']))  ) $gb4['upload_blog_file_size'] = 1024*1024*20; // 20�ް�
if( (int)$gb4['gb_page_rows'] <= 0                ) $gb4['gb_page_rows'] = 24;
if( !trim(strlen($gb4['upload_file_number']))     ) $gb4['upload_file_number'] = 5;
if( !trim(strlen($gb4['upload_one_file_size']))   ) $gb4['upload_one_file_size'] = 1024*1024; // 1�ް�
if( !trim(strlen($gb4['use_random_blog']))        ) $gb4['use_random_blog'] = 1;
if( !trim(strlen($gb4['use_permalink']))          ) $gb4['use_permalink'] = 'none';
if( !trim(strlen($gb4['profile_image_size']))     ) $gb4['profile_image_size'] = 66560;
if( !trim(strlen($gb4['top_image_size']))         ) $gb4['top_image_size'] = 1048576;
if( !trim(strlen($gb4['background_image_size']))  ) $gb4['background_image_size'] = 1048576;
?>
<table width=100% cellpadding=0 cellspacing=0 border=0>
<form name=form method=post action='config_update.php'>
<tr class='ht'>
    <td align=left><?=subtitle("��α� �⺻����")?></td>
</tr>
</table>

<table border=0 cellpadding=0 cellspacing=0>
    <tr class='ht'>
        <td width=300 height=40>
            ��α� ���� ������ ȸ�� ������ �������ּ���.
        </td>
        <td>
            <select name="make_level" required>
            <?for($i=2; $i<=10; $i++){
                if($gb4['make_level']==$i) $selected = 'selected'; else $selected = '';
                echo "<option value=$i $selected>$i ����</option>\n";
            }?>
            </select>
            �̻�
        </td>
    </tr>
    <tr><td height=1 colspan=2 bgcolor=#efefef></td></tr>
    <tr class='ht'>
        <td width=300 height=40>
            ��α� ������ ���� ����Ʈ
        </td>
        <td>
            <input type=text class=ed name=make_point value="<?=$gb4['make_point']?>"  numeric required itemname="��α� ������ ���� ����Ʈ" size=10>
            ����Ʈ. <span style="color:gray;font-size:10px">(0 �̸� ���� ����Ʈ ����.)</span>
        </td>
    </tr>
    <tr><td height=1 colspan=2 bgcolor=#efefef></td></tr>
    <tr class='ht'>
        <td width=300 height=40>
            ��α� �Ѱ��� �� ���� �뷮.
        </td>
        <td>
            <input type=text class=ed name=upload_blog_file_size value="<?=$gb4['upload_blog_file_size']?>"  numeric required itemname="��α� �Ѱ��� ������ �뷮" size=15>
            byte.<span style="color:gray;font-size:10px">(0 �̸� ���� ����)</span>
        </td>
    </tr>
    <tr><td height=1 colspan=2 bgcolor=#efefef></td></tr>
    <tr class='ht'>
        <td width=300 height=40>
            ��αװ������� �������� ��ϼ�
        </td>
        <td>
            <input type=text class=ed name=gb_page_rows value="<?=$gb4['gb_page_rows']?>" numeric required itemname="��αװ������� �������� ��ϼ�" size=15>
        </td>
    </tr>
    <tr><td height=1 colspan=2 bgcolor=#efefef></td></tr>
    <tr class='ht'>
        <td width=300 height=40>
            ÷�������� ����� ��� �����մϱ�?
        </td>
        <td>
            <input type=text class=ed name=upload_file_number value="<?=$gb4['upload_file_number']?>"  numeric required itemname="��ϰ����� ÷������ ����" size=10>
            ��. <span style="color:gray;font-size:10px">(0 �̸� ÷������ ��� �Ұ�, 999�� ���� ����.)</span>
        </td>
    </tr>
    <tr><td height=1 colspan=2 bgcolor=#efefef></td></tr>
    <tr class='ht'>
        <td width=300 height=40>
            ÷������ �Ѱ� �� ���� �뷮�� �������ּ���.
        </td>
        <td>
            <input type=text class=ed name=upload_one_file_size value="<?=$gb4['upload_one_file_size']?>"  numeric required itemname="÷������ �Ѱ� �� ���� �뷮" size=15>
            byte.<span style="color:gray;font-size:10px">(0 �̸� ���� ����)</span>
        </td>
    </tr>
    <tr><td height=1 colspan=2 bgcolor=#efefef></td></tr>
    <tr class='ht'>
        <td width=300 height=40>
            ������ �̹��� ���� �뷮�� �������ּ���.
        </td>
        <td>
            <input type=text class=ed name=profile_image_size value="<?=$gb4['profile_image_size']?>"  numeric required itemname="������ �̹��� ���� �뷮" size=15>
            byte. <span style="color:gray;font-size:10px">(0 �̸� ���� ����)</span>
        </td>
    </tr>
    <tr><td height=1 colspan=2 bgcolor=#efefef></td></tr>
    <tr class='ht'>
        <td width=300 height=40>
            ��� �̹��� ���� �뷮�� �������ּ���.
        </td>
        <td>
            <input type=text class=ed name=top_image_size value="<?=$gb4['top_image_size']?>"  numeric required itemname="��� �̹��� ���� �뷮" size=15>
            byte. <span style="color:gray;font-size:10px">(0 �̸� ���� ����)</span>
        </td>
    </tr>
    <tr><td height=1 colspan=2 bgcolor=#efefef></td></tr>
    <tr class='ht'>
        <td width=300 height=40>
            ��� �̹��� ���� �뷮�� �������ּ���.
        </td>
        <td>
            <input type=text class=ed name=background_image_size value="<?=$gb4['background_image_size']?>"  numeric required itemname="��� �̹��� ���� �뷮" size=15>
            byte. <span style="color:gray;font-size:10px">(0 �̸� ���� ����)</span>
        </td>
    </tr>
    <tr><td height=1 colspan=2 bgcolor=#efefef></td></tr>
    <tr class='ht'>
        <td width=300 height=60>
            ���� ��α� ����� ����Ͻðڽ��ϱ�?
        </td>
        <td>
            <input type=radio name=use_random_blog value=1<?if($gb4['use_random_blog']==1) echo ' checked'?> itemname='���� ��α�'> ����մϴ�.<br/>
            <input type=radio name=use_random_blog value=0<?if($gb4['use_random_blog']==0) echo ' checked'?> itemname='���� ��α�'> ������� �ʽ��ϴ�.
        </td>
    </tr>
    <tr><td height=1 colspan=2 bgcolor=#efefef></td></tr>
    <tr class='ht'>
        <td width=300 height=100>
            �۸Ӹ�ũ(Permalink) �� ����Ͻðڽ��ϱ�?
        </td>
        <td style="line-height:25px;">
            <input type=radio name=use_permalink value='none'<?if($gb4['use_permalink']=='none') echo ' checked'?> itemname='�۸Ӹ�ũ'>
            ������� �ʽ��ϴ�. 
            <br>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <span style="color:gray;font-size:10px">(��: <?=$g4['url']?>/<?=$gb4['blog']?>/?mb_id=userid&id=305)</span><br/>
            <!--
            <input type=radio name=use_permalink value='numeric'<?if($gb4['use_permalink']=='numeric') echo ' checked'?> itemname='�۸Ӹ�ũ'> 
            ����մϴ�.<br>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <span style="color:gray;font-size:10px">(��: <?=$g4['url']?>/<?=$gb4['blog']?>/userid/305)</span>
            <br/>
            <input type=radio name=use_permalink value='none'<?if($gb4['use_permalink']=='none') echo ' checked'?> itemname='�۸Ӹ�ũ'>
            ������� �ʽ��ϴ�. 
            <span style="color:gray;font-size:10px">(��: http://<?=$HTTP_HOST?>/blog/?mb_id=userid&id=305)</span>
            <br/>

            <input type=radio name=use_permalink value='numeric'<?if($gb4['use_permalink']=='numeric') echo ' checked'?> itemname='�۸Ӹ�ũ'> 
            ���ڷ� ����մϴ�.
            <span style="color:gray;font-size:10px">(��: http://<?=$HTTP_HOST?>/blog/userid/305/)</span>
            <br/>

            <input type=radio name=use_permalink value='character'<?if($gb4['use_permalink']=='character') echo ' checked'?> itemname='�۸Ӹ�ũ'>
            ���ڷ� ����մϴ�. 
            <span style="color:gray;font-size:10px">(��: http://<?=$HTTP_HOST?>/blog/userid/hello-world/)</span>
            <br/>

            <input type=radio name=use_permalink value='date'<?if($gb4['use_permalink']=='date') echo ' date'?> itemname='�۸Ӹ�ũ'>
            ��¥�� ���ڷ� ����մϴ�. 
            <span style="color:gray;font-size:10px">(��: http://<?=$HTTP_HOST?>/blog/userid/2006/12/25/hello-world/)</span>
            <br/>
            -->
        </td>
    </tr>
    <tr><td height=1 colspan=2 bgcolor=#efefef></td></tr>
</table>

<p align=center>
    <input type=submit class=btn1 accesskey='s' value='  Ȯ  ��  '>
</p>
</form>

<?
include_once("../admin.tail.php");
?>