<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ�

// ����¡ ��������
$paging = get_paging($page_size, $page, $total_page, get_guestbook_page_url() ); 

// �⺻���� �Ѿ���� �������� �Ʒ��� ���� ��ȯ�Ͽ� �̹����ε� ����� �� �ֽ��ϴ�.
$paging = str_replace("ó��", "<img src='$blog_skin_url/img/btn_list_start.gif' border='0' align='absmiddle' title='ó��'>", $paging);
$paging = str_replace("����", "<img src='$blog_skin_url/img/btn_list_prev.gif' border='0' align='absmiddle' title='����'>", $paging);
$paging = str_replace("����", "<img src='$blog_skin_url/img/btn_list_next.gif' border='0' align='absmiddle' title='����'>", $paging);
$paging = str_replace("�ǳ�", "<img src='$blog_skin_url/img/btn_list_end.gif' border='0' align='absmiddle' title='�ǳ�'>", $paging);
$paging = preg_replace("/<b>([0-9]*)<\/b>/", "<span class=\"move_list_spot\">$1</span>", $paging);

$cols = 80;
$rows = 5;

?>

    <form name="guestbook_form" id="guestbook_form" onsubmit="javascript:send_guestbook();" method="post" enctype="multipart/form-data" style="margin:0px;">
    <input type=hidden id=mb_id name=mb_id value=<?=$current['mb_id']?>>
    <input type=hidden id=guestbook_id name=guestbook_id value=''>

    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td width="3" height="3"><img src="<?=$blog_skin_url?>/img/comment_top_left.gif" alt=""></td>
            <td width=100% height="3" style="background:url(<?=$blog_skin_url?>/img/comment_top.gif) no-repeat right top;"></td>
        </tr>
        <tr>
            <td width="3" style="background:url(<?=$blog_skin_url?>/img/comment_left.gif) repeat-y left top;"></td>
            <td width="100%" style="background:url(<?=$blog_skin_url?>/img/comment_bg_01.gif) repeat-y right top; padding:5px 10px 0 7px;">

                <table width="100%" border="0" cellspacing="0" cellpadding="0" id="comment_write">
                <? if( empty($member['mb_id']) ) { ?>
                <tr>
                    <td width="60">�̸�</td>
                    <td><input id="writer_name" required itemname="�̸�" value="" name="writer_name" type="text" style="width:85px;" class="input_search">&nbsp;��й�ȣ&nbsp;<input type="password" size=40 id="writer_pw" name="writer_pw" required itemname="��й�ȣ" minlength=4 style="width:85px;" class="input_search"></td>
                    <td width="180" rowspan=3 valign="top">
                        <img id='kcaptcha_image' />
                        <input title="������ ���ڸ� �Է��ϼ���." type="input" name="wr_key" id="wr_key" size="10" itemname="�ڵ���Ϲ���" required class=ed>
                    </td>
                </td>

                </tr>
                <tr>
                    <td width="60">�̸���</td>
                    <td><input id="writer_email" email itemname="�̸���" value="" name="writer_email" type="text" style="width:226px;" class="input_search"></td>
                </tr>
                <tr>
                    <td width="60">Ȩ������</td>
                    <td><input id="writer_url" name="writer_name" value="" type="text" style="width:226px;" class="input_search"></td>
                </tr>
                <? } ?>
                <tr>
                    <td width="60">��б�</td>
                    <td><input type=checkbox id=secret name=secret value=1></td>
                </tr>
                <tr>
                    <td colspan="3">
                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td>
                                <textarea id=writer_content name=writer_content required cols=<?=$cols?> rows=<?=$rows?> style="width:100%; height:130px;" class="input_search"></textarea>
                            </td>
                            <td width=70 align=center>
                                <input type=image src="<?=$blog_skin_url?>/img/btn_confirm.gif" alt="" width="49" height="71" hspace="5" align="absmiddle">
                                <img src="<?=$blog_skin_url?>/img/btn_cancel.gif" style="cursor:pointer;" onclick="guestbook_on();">
                            </td>
                        </tr>
                        </table>
                    </td>
                </tr>
                </table>

            </td>
        </tr>
        <tr>
            <td width="3" height="3"><img src="<?=$blog_skin_url?>/img/comment_btm_left.gif" alt=""></td>
            <td width=100% height="3" style="background:url(<?=$blog_skin_url?>/img/comment_btm.gif) no-repeat right ;"></td>
        </tr>
    </table>
    </form>

    <?if (count($guestbook_list)) {?>
    <!--<div><img src="<?=$blog_skin_url?>/img/img_comment.gif" alt="" width="104" height="15"></div>-->
    <table width="100%" border="0" cellspacing="0" cellpadding="0" id="comment">
        <tr>
            <td width="3" height="3"><img src="<?=$blog_skin_url?>/img/comment_top_left.gif" alt=""></td>
            <td width=100% height="3" style="background:url(<?=$blog_skin_url?>/img/comment_top.gif) no-repeat right top;"></td>
        </tr>
        <tr>
            <td width="3" style="background:url(<?=$blog_skin_url?>/img/comment_left.gif) repeat-y left top;"></td>
            <td width="100%" style="background:url(<?=$blog_skin_url?>/img/comment_bg_01.gif) repeat-y right top; padding:5px 10px 0 7px;">

                <!-- ���� ��� ���� -->
                <?
                for($i=0; $i<count($guestbook_list); $i++) { 
                    if( $guestbook_list[$i]['secret'] && $member['mb_id'] && ($member['mb_id']==$current['mb_id'] || $guestbook_list[$i]['mb_id'] == $member['mb_id']) )
                        $guestbook_list[$i]['writer_name'] .= " <font color=red style=font-weight:normal>(��б�)</font>";

                    // ȸ���� ��쿡�� ���۸�, ��ȸ���� ��쿡�� ��ȸ���۸� ����/������ �����ϰ� flag ����
                    $mod_flag = 0;
                    if ($member[mb_id] && $member[mb_id] ==  $guestbook_list[$i]['mb_id'])
                        $mod_flag = 1;
                    if (!$is_member && $guestbook_list[$i]['mb_id'] == "")
                        $mod_flag = 1;
                    if ($member[mb_id] && $member[mb_id] ==  $current['mb_id'])
                        $mod_flag = 1;
                ?>

                <!-- ���� ��� -->
                <div id="c<?=$guestbook_list[$i]['id']?>">

                    <div>
                        <?if($i>0){?><ul style="background:url(<?=$blog_skin_url?>/img/line_01.gif) repeat-x left center; height:15px;"></ul><br><?}?>
                        <table border=0 cellpadding=0 cellspacing=0 width=100%>
                        <tr>
                            <td class="name_01" width="50%">
                                <font class="name_02" id="writer_name<?=$guestbook_list[$i]['id']?>"><?=$guestbook_list[$i]['writer_name']?></font>&nbsp;
                                <font class="date" id="writer_date<?=$guestbook_list[$i]['id']?>"><?=$guestbook_list[$i]['regdate']?></font>
                            </td>
                            <td class="function">
                                <!-- ���� ����/����/�����ǹ��� ��ư -->
                                <? if ($mod_flag) { ?>
                                &nbsp;<a href="javascript:guestbook_permission(<?=$guestbook_list[$i]['id']?>,'mod')">modify</a> |
                                &nbsp;<a href="javascript:guestbook_permission(<?=$guestbook_list[$i]['id']?>,'del')">delete</a>
                                <? } ?>
                                <? if (empty($guestbook_list[$i]['answer_content'])&&$member['mb_id']==$current['mb_id']) { ?>  |
                                &nbsp;<a href="javascript:guestbook_reply(<?=$guestbook_list[$i]['id']?>)">reply</a>
                                <? } ?>
                                <!-- ��ư ����-->
                            </td>
                        </tr>
                        <tr>
                            <td colspan=2>
                            <!-----------------------------------
                                ������ �� ����
                            ------------------------------------->

                            <!-- ������ ���� �ۼ��� �̿��ϴ� ���̾� -->
                            <div id="r<?=$guestbook_list[$i]['id']?>"></div>

                            <!-- ����/���� �� ��й�ȣ �Է�â -->
                            <div id="p<?=$guestbook_list[$i]['id']?>" style="display:none">
                                ��й�ȣ :
                                <input type=password name=input_pw<?=$guestbook_list[$i]['id']?> id=input_pw<?=$guestbook_list[$i]['id']?> size=20>
                                <input type=button value=���� onclick="guestbook_password_confirm('<?=$guestbook_list[$i]['id']?>')">
                            </div>

                            <!-- ���� �� �� �Է�â -->
                            <div id="m<?=$guestbook_list[$i]['id']?>" style="display:none"></div>

                            <!-----------------------------------
                                ������ �� ����
                            ------------------------------------->
                            </td>
                        </tr>
                        <tr>
                            <td colspan=2>
                                <ul style="margin-top:10px; line-height:20px;">
                                <span id=guestbook_writer_content<?=$guestbook_list[$i]['id']?>><?=$guestbook_list[$i]['writer_content']?></span>
                                </ul>
                            </td>
                        </tr>
                        </table>
                    </div>

                    <? if ($guestbook_list[$i]['answer_content']) { ?>
                    
                    <!--�亯���� ���-->
                    <div style="height:10px;"></div>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" id="comment_re">
                        <tr>
                            <td width="3" height="3"><img src="<?=$blog_skin_url?>/img/comment_re_top_left.gif" alt=""></td>
                            <td width="100%" height="3" style="background:url(<?=$blog_skin_url?>/img/comment_re_top.gif) no-repeat right top;"></td>
                        </tr>
                        <tr>
                            <td width="3" style="background:url(<?=$blog_skin_url?>/img/comment_re_left.gif) repeat-y left top;"></td>
                            <td width="100%" style="background:url(<?=$blog_skin_url?>/img/comment_re_bg_01.gif) repeat-y right top; padding:5px 10px 0 7px;">
                            <div>
                                <table border=0 cellpadding=0 cellspacing=0 width=100%>
                                <tr>
                                    <td class="name_01" id="writer_name<?=$guestbook_list[$i]['id']?>" width="50%">
                                        <font class="name_02"><img src="<?=$blog_skin_url?>/img/icon_reply.gif" alt="" style="vertical-align:middle;">&nbsp;<?=get_sideview($current['mb_id'], $current['writer'], $current['mb_email'], $member['blog_url'])?></font>
                                        <font class="date" id="writer_date<?=$guestbook_list[$i]['id']?>"><?=$guestbook_list[$i]['ansdate']?></font>
                                    </td>
                                    <td class="function">
                                        <!-- ���� ����/����/�����ǹ��� ��ư -->
                                        <?if ($member['mb_id']==$current['mb_id']) {?>
                                        &nbsp;<a href="javascript:guestbook_permission(<?=$guestbook_list[$i]['id']?>,'mod',1)">modify</a> |
                                        &nbsp;<a href="javascript:guestbook_permission(<?=$guestbook_list[$i]['id']?>,'del',1)">delete</a>
                                        <?}?>
                                        <!-- ��ư ����-->
                                    </td>
                                </tr>
                                </table>

                                <!-- ���� �� �� �Է�â -->
                                <div id="mr<?=$guestbook_list[$i]['id']?>" style="display:none"></div>

                                <ul style="margin-top:10px; line-height:20px;">
                                <span id=guestbook_writer_re_content<?=$guestbook_list[$i]['id']?>><?=$guestbook_list[$i]['answer_content']?></span>
                                </ul>

                            </div>
                            </td>
                        </tr>
                        <tr>
                            <td width="3" height="3"><img src="<?=$blog_skin_url?>/img/comment_re_btm_left.gif" alt=""></td>
                            <td width="100%" height="3" style="background:url(<?=$blog_skin_url?>/img/comment_re_btm.gif) no-repeat right ;"></td>
                        </tr>
                    </table>
                    <!--�亯���� ��� ��-->

                    <?}?>
                </div>
                <!-- ���� ��� �� -->

                <?}?>

                <div class="move_list"><?=$paging?></div>

            </td>
        </tr>
        <tr>
            <td width="3" height="3"><img src="<?=$blog_skin_url?>/img/comment_btm_left.gif" alt=""></td>
            <td width=100% height="3" style="background:url(<?=$blog_skin_url?>/img/comment_btm.gif) no-repeat right ;"></td>
        </tr>
    </table>
    <?}?>

<!-- ���� Ȥ�� ����� ��� ��Ͻ� ����ϴ� �Է��� ����-->
<div id="hidden_comment" style="display:none">
    <table border=0 cellpadding=0 cellspacing=0>
    <?if( empty($member['mb_id']) ) {?>
    <tr><td class=input_td> �̸� </td><td> <input type=text id=guestbook_mod_name name=guestbook_mod_name size=40 value="input_name"> </td></tr>
    <tr><td class=input_td> �̸��� </td><td> <input type=text id=guestbook_mod_email name=guestbook_mod_email size=40 value="input_email"> </td></tr>
    <tr><td class=input_td> Ȩ������ </td><td> <input type=text id=guestbook_mod_url name=guestbook_mod_url size=40 value="input_url"> </td></tr>
    <?}?>
    <tr><td colspan=2><textarea id='guestbook_mod_content' name='guestbook_mod_content' cols=<?=$cols?> rows=<?=$rows?>>input_content</textarea></td></tr>
    </table>
    <input type=hidden id=guestbook_mod_pw name=guestbook_mod_pw value="input_pw">
    <input type=button value='��     ��' onclick='guestbook_mod_guest_form_send(input_id,input_re)'>
    <input type=button value='��     ��' onclick='guestbook_on()'>
</div>
<!-- ���� Ȥ�� ����� ��� ��Ͻ� ����ϴ� �Է��� ���� -->

<script type="text/javascript" src="<?="$g4[path]/js/jquery.kcaptcha.js"?>"></script>