<?
include_once("./_common.php");

$g4['title'] = "��α� �⺻��������";

// ��α� �⺻���������� ���
if( $w == 'u' ) 
{
    if( $mb_id != $member['mb_id'] )
        alert('�ڽ��� ��α׸� ������ ������ �� �ֽ��ϴ�.');

    // ��α� �⺻���� �ε�
    $r = sql_fetch("select * from {$gb4['blog_table']} where mb_id='{$member['mb_id']}'");

    if( empty($r) ) 
        alert('�������� �ʴ� ��α� �Դϴ�.', $g4['path']);

    extract($r);

    // ������ �̹���
    $profile_image = "{$g4[path]}/data/blog/profile_image/{$current['mb_id']}";

}

// ��α׸� ���� ���� ���
else 
{
    // ��α׸� ������ �ִٸ� �ڽ��� ��α׷� �̵��Ѵ�.
    if( have_a_blog($member['mb_id']) ) 
        alert('�̹� ��α׸� ������ ��ʴϴ�.', $blog_url);

    // ȸ�� ���� �˻�
    if( $member['mb_level'] < $gb4['make_level'] )
        alert("��α׸� ������ {$gb4['make_level']} �������� �����մϴ�.", $g4['path']);

    // ȸ�� ����Ʈ �˻�
    if( $member['mb_point'] < $gb4['make_point'] )
        alert('��α׸� �����Ϸ��� '.number_format($gb4['make_point']).' ����Ʈ�� �ʿ��մϴ�.', $g4['path']);

    // �ʱⰪ ����
    $blog_name = $member['mb_nick'];
    $blog_about = "{$blog_name}���� ��α�";
    $use_post = 0;
    $use_comment = 1;
    $use_guestbook = 1;
    $use_trackback = 1;
    $use_tag = 1;
    $use_ccl = 1;
    $use_autosource = 1;
    $use_random = 1;
    $rss_open = 1;
    $rss_count = 10;
    
 	  // ��α� ��ġ�� �̵��� url
	  $urlencode = "$gb4[path]";
}

include_once("{$g4[path]}/head.sub.php");
include_once("./admin.head.php");
?>


<script language=javascript src="<?=$g4['path']?>/js/sideview.js"></script>


<style type="text/css">
<!--
.m_title    { BACKGROUND-COLOR: #F7F7F7; PADDING-LEFT: 15px; PADDING-top: 5px; PADDING-BOTTOM: 5px; }
.m_padding  { PADDING-LEFT: 15px; PADDING-BOTTOM: 5px; PADDING-TOP: 5px; }
.m_padding2 { PADDING-LEFT: 0px; PADDING-top: 5px; PADDING-BOTTOM: 0px; }
.m_padding3 { PADDING-LEFT: 0px; PADDING-top: 5px; PADDING-BOTTOM: 5px; }
.m_text     { BORDER: #D3D3D3 1px solid; HEIGHT: 18px; BACKGROUND-COLOR: #ffffff; }
.m_text2    { BORDER: #D3D3D3 1px solid; HEIGHT: 18px; BACKGROUND-COLOR: #dddddd; }
.m_textarea { BORDER: #D3D3D3 1px solid; BACKGROUND-COLOR: #ffffff; WIDTH: 100%; word-break: break-all; }
.w_message  { font-family:����; font-size:9pt; color:#4B4B4B; }
.w_norobot  { font-family:����; font-size:9pt; color:#BB4681; }
.w_hand     { cursor:pointer; }
-->
</style>

<? if( $w == 'u' ) { ?>
<div class="adm_info">
    <b>�⺻ȯ�� ����</b> : <?=$member[mb_nick]?>���� �ҷα� �⺻������ ���� �մϴ�.
</div>
<? } else { ?>
<div class="adm_info">
    <b>��α� �����</b> : <?=$member[mb_nick]?>���� �ҷα׸� ���� �մϴ�.
</div>
<? } ?>

<table width=600 cellspacing=0 cellspacing=0 align=center>
<form name=fregisterform method=post action="join_blog_update.php" enctype="multipart/form-data" autocomplete="off">
<input type=hidden name=w                value="<?=$w?>">
<input type=hidden name=mb_id            value="<?=$member['mb_id']?>">
<input type=hidden name=url              value="<?=$urlencode?>">
<tr><td>

<table width="100%" cellspacing="0" cellpadding="0">
<tr>
    <td bgcolor="#cccccc">
        <table cellspacing=1 cellpadding=0 width=100%>
        <tr bgcolor="#ffffff">
            <td width="160" class=m_title>��α� ����</td>
            <td class=m_padding>
                <input class=m_text maxlength=150 size=50 name="blog_name" itemname="��α� ����" required value="<?=$blog_name?>">
            </td>
        </tr>
        <tr bgcolor="#ffffff">
            <td width="160" class=m_title>��α� ����</td>
            <td class=m_padding>
                <textarea class=m_text style="width:90%;height:50px;" name="blog_about" itemname="��α� ����"><?=$blog_about?></textarea>
            </td>
        </tr>
        <tr bgcolor="#ffffff">
            <td width="160" class=m_title>������ �̹���</td>
            <td class=m_padding>
                <input class=m_text type=file name='profile_image' size=30>
                <? if ($w == "u" && file_exists($profile_image)) { ?>
                <input type=checkbox name='profile_image_del' value='1'>  ����
                <br><a href="<?=$profile_image?>" target=_new><img src="<?=$profile_image?>" width=100px></a>
                <? } ?>

                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td class=m_padding3>
                        <? if( $gb4['profile_image_size'] ) {?>
                        (gif,jpg,png �� ���� / �뷮:<?=number_format($gb4['profile_image_size'])?>����Ʈ ���ϸ� ��ϵ˴ϴ�.)
                        <? } ?>
                    </td>
                </tr>
                </table>
            </td>
        </tr>
        <tr bgcolor="#ffffff">
            <td width="160" class=m_title>��α׾��� ����</td>
            <td class=m_padding>
                <?=get_member_level_select('use_post', 0, $member[mb_level], $use_post) ?> (0: ��ȥ��, 1: �մ�, 2: ȸ��)
            </td>
        </tr>
        <tr bgcolor="#ffffff">
            <td width="160" class=m_title>��۾��� ����</td>
            <td class=m_padding>
                <?=get_member_level_select('use_comment', 0, $member[mb_level], $use_comment) ?> (0: ��ȥ��, 1: �մ�, 2: ȸ��)
            </td>
        </tr>
        <tr bgcolor="#ffffff">
            <td width="160" class=m_title>���Ͼ��� ����</td>
            <td class=m_padding>
                <?=get_member_level_select('use_guestbook', 0, $member[mb_level], $use_guestbook) ?> (0: ��ȥ��, 1: �մ�, 2: ȸ��)
            </td>
        </tr>
        <tr bgcolor="#ffffff">
            <td width="160" class=m_title>���α� ��뿩��</td>
            <td class=m_padding>
                <input type=checkbox name=use_trackback value="1"<?if($use_trackback) echo ' checked'?>> ����մϴ�.
            </td>
        </tr>
        <tr bgcolor="#ffffff">
            <td width="160" class=m_title>�±� ��뿩��</td>
            <td class=m_padding>
                <input type=checkbox name=use_tag value="1"<?if($use_tag) echo ' checked'?>> ����մϴ�. (���� �±׸� �����Ϸ��� <a href="<?=$gb4[bbs_path]?>/adm_tag_list.php?mb_id=<?=$member['mb_id']?>">�±װ���</a>��)
            </td>
        </tr>
        <tr bgcolor="#ffffff">
            <td width="160" class=m_title>CCL ��뿩��</td>
            <td class=m_padding>
                <input type=checkbox name=use_ccl value="1"<?if($use_ccl) echo ' checked'?>> ����մϴ�.
            </td>
        </tr>
        <tr bgcolor="#ffffff">
            <td width="160" class=m_title>�ڵ���ó ��뿩��</td>
            <td class=m_padding>
                <input type=checkbox name=use_autosource value="1"<?if($use_autosource) echo ' checked'?>> ����մϴ�.
            </td>
        </tr>
        <tr bgcolor="#ffffff">
            <td width="160" class=m_title>������α� ��뿩��</td>
            <td class=m_padding>
                <input type=checkbox name=use_random value="1"<?if($use_random) echo ' checked'?>> ����մϴ�.
            </td>
        </tr>
        <tr bgcolor="#ffffff">
            <td width="160" class=m_title>RSS ��������</td>
            <td class=m_padding>
                <table border=0 cellpadding=0 cellspacing=0 width=100% height=23>
                <tr>
                    <td width=90>
                        <select name=rss_open onChange="is_rss_open();">
                            <option value="1"<?if($rss_open==1) echo ' selected'?>> ���� </option>
                            <option value="0"<?if($rss_open==0) echo ' selected'?>> ����� </option>
                            <option value="2"<?if($rss_open==2) echo ' selected'?>> �Ϻΰ��� </option>
                        </select>
                    </td>
                    <td>
                        <div id=rss_count_layer> 
                            <table border=0 cellpadding=0 cellspacing=0>
                            <tr>
                                <td>�ֽ�</td>
                                <td><input type=text size=5 name=rss_count value="<?=$rss_count?>" numeric required itemname="�ֽ� RSS ���� ����"></td>
                                <td>
                                �� ����
                                <?=help("���� : ��α׿� �ۼ��� ���� ��ü���� RSS�� �����մϴ�.<br><br>�Ϻΰ��� : ������ �� 255�� ������ RSS�� �����մϴ�.<br><br>����� : RSS�� �������� �ʽ��ϴ�. ")?>
                                </td>
                            </tr>
                            </table>
                        </div>
                        <script language=javascript>
                        function is_rss_open(){
                            ro = document.getElementById('rss_open');
                            rc = document.getElementById('rss_count');
                            rl = document.getElementById('rss_count_layer');
                            if(ro.value != 0) {
                                rc.style.display = 'block';
                                rl.style.display = 'block';
                            } else {
                                rc.style.display = 'none';
                                rl.style.display = 'none';
                            }
                        }
                        is_rss_open();
                        </script>
                    </td>
                </tr>
                </table>
            </td>
        </tr>

        <?if( $w=='u') {?>
        <tr bgcolor="#ffffff">
            <td width="160" class=m_title>��α� �� ��뷮</td>
            <td class=m_padding>
                <?=get_filesize($current['total_file_size'])?> / <?=get_filesize($gb4['upload_blog_file_size'])?>
            </td>
        </tr>
        <?}?>
        </table>
    </td>
</tr>
</table>

<p align=center>
    <INPUT type=image width="66" height="20" src="img/ok_btn.gif" border=0 accesskey='s'>
</p>

</td></tr></form></table>


<?
include_once("./admin.tail.php");
include_once("{$g4[path]}/tail.sub.php");
?>