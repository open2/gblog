<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ�

$cols = 80;
$rows = 5;
?>

    <!-- �ڸ�Ʈ ���̸���. ����~ -->
    <?if (count($comment)) {?>
    <div><img src="<?=$blog_skin_url?>/img/img_comment.gif" alt="" width="104" height="15"></div>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" id="comment">
        <tr>
            <td width="3" height="3"><img src="<?=$blog_skin_url?>/img/comment_top_left.gif" alt=""></td>
            <td width=100% height="3" style="background:url(<?=$blog_skin_url?>/img/comment_top.gif) no-repeat right top;"></td>
        </tr>
        <tr>
            <td width="3" style="background:url(<?=$blog_skin_url?>/img/comment_left.gif) repeat-y left top;"></td>
            <td width="100%" style="background:url(<?=$blog_skin_url?>/img/comment_bg_01.gif) repeat-y right top; padding:5px 10px 0 7px;">



<!-- ��� ��� ���� -->
<?
for($i=0; $i<count($comment); $i++) { 
    if( $comment[$i]['secret'] && $member['mb_id'] && ($member['mb_id']==$current['mb_id'] || $comment[$i]['mb_id'] == $member['mb_id']) )
        $comment[$i]['writer_name'] .= " <font color=red style=font-weight:normal>(��д��)</font>";
?>

    <!-- �ڸ�Ʈ ��� -->
    <div id="c<?=$comment[$i]['id']?>">

        <?if ( $comment[$i]['comment_re_num']==0 ){ // ���� ��� ?>

        <div>
            <?if($i>0){?><ul style="background:url(<?=$blog_skin_url?>/img/line_01.gif) repeat-x left center; height:15px;"></ul><br><?}?>
            <table border=0 cellpadding=0 cellspacing=0 width=100%>
            <tr>
                <td class="name_01">
                    <font class="name_02" id="writer_name<?=$comment[$i]['id']?>"><?=$comment[$i]['writer_name']?></font>&nbsp;
                    <font class="date" id="writer_date<?=$comment[$i]['id']?>"><?=$comment[$i]['moddate']?></font>
                </td>
                <td class="function">
                    <!-- ��� ����/����/����Ǵ�� ��ư -->
                    &nbsp;<a href="<?=$comment[$i]['permalink']?>">permalink</a> |
                    &nbsp;<a href="javascript:comment_permission(<?=$id?>,<?=$comment[$i]['id']?>,'mod')">modify</a> |
                    &nbsp;<a href="javascript:comment_permission(<?=$id?>,<?=$comment[$i]['id']?>,'del')">delete</a> |
                    <? if ( $comment[$i]['comment_re_num'] == 0 ) { ?> 
                    &nbsp;<a href="javascript:comment_reply(<?=$id?>,<?=$comment[$i]['id']?>,<?=$comment[$i]['comment_num']?>)">reply</a>
                    <? } ?>
                    <!-- ��ư ����-->
                </td>
            </tr>
            <tr>
                <td colspan=2>
                <!-----------------------------------
                    ������ �� ����
                ------------------------------------->

                <!-- ����� ��� �ۼ��� �̿��ϴ� ���̾� -->
                <div id="r<?=$comment[$i]['id']?>"></div>

                <!-- ����/���� �� ��й�ȣ �Է�â -->
                <div id="p<?=$comment[$i]['id']?>" style="display:none">
                    ��й�ȣ :
                    <input type=password name=input_pw<?=$comment[$i]['id']?> id=input_pw<?=$comment[$i]['id']?> size=20>
                    <input type=button value=���� onclick="comment_password_confirm('<?=$comment[$i]['id']?>')">
                </div>

                <!-- ���� �� �� �Է�â -->
                <div id="m<?=$comment[$i]['id']?>" style="display:none">
                </div>

                <!-----------------------------------
                    ������ �� ����
                ------------------------------------->
                </td>
            </tr>
            <tr>
                <td colspan=2>
                    <ul style="margin-top:10px; line-height:20px;">
                    <span id=comment_writer_content<?=$comment[$i]['id']?>><?=$comment[$i]['writer_content']?></span>
                    </ul>
                </td>
            </tr>
            </table>
        </div>

        <?} else { // ����� ��� ?>
        
        <!--�亯�ڸ�Ʈ ���-->
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
                        <td class="name_01" id="writer_name<?=$comment[$i]['id']?>">
                            <font class="name_02"><img src="<?=$blog_skin_url?>/img/icon_reply.gif" alt="" style="vertical-align:middle;">&nbsp;<?=$comment[$i]['writer_name']?></font>
                            <font class="date" id="writer_date<?=$comment[$i]['id']?>"><?=$comment[$i]['moddate']?></font>
                        </td>
                        <td class="function">
                            <!-- ��� ����/����/����Ǵ�� ��ư -->
                            &nbsp;<a href="<?=$comment[$i]['permalink']?>">permalink</a> |
                            &nbsp;<a href="javascript:comment_permission(<?=$id?>,<?=$comment[$i]['id']?>,'mod')">modify</a> |
                            &nbsp;<a href="javascript:comment_permission(<?=$id?>,<?=$comment[$i]['id']?>,'del')">delete</a>
                            <!-- ��ư ����-->
                        </td>
                    </tr>
                    </table>
                    <div id="m<?=$comment[$i]['id']?>" style="display:none"></div>

                    <ul style="margin-top:10px; line-height:20px;">
                    <span id=comment_writer_content<?=$comment[$i]['id']?>><?=$comment[$i]['writer_content']?></span>
                    </ul>
                </div>
                </td>
            </tr>
            <tr>
                <td width="3" height="3"><img src="<?=$blog_skin_url?>/img/comment_re_btm_left.gif" alt=""></td>
                <td width="100%" height="3" style="background:url(<?=$blog_skin_url?>/img/comment_re_btm.gif) no-repeat right ;"></td>
            </tr>
        </table>
        <!--�亯�ڸ�Ʈ ��� ��-->

        <?}?>
    </div>
    <!-- �ڸ�Ʈ ��� �� -->

<?}?>



            </td>
        </tr>
        <tr>
            <td width="3" height="3"><img src="<?=$blog_skin_url?>/img/comment_btm_left.gif" alt=""></td>
            <td width=100% height="3" style="background:url(<?=$blog_skin_url?>/img/comment_btm.gif) no-repeat right ;"></td>
        </tr>
    </table>
        <?}?>

    <!--�ڸ�Ʈ ���̸��� �� -_-a -->
    
<!--�ڸ�Ʈ ��� -->
<div id="comment_form<?=$id?>">

<form name="comment_form" action="javascript:send_comment(<?=$id?>)" method="post">
<input type=hidden id=mb_id name=mb_id value=<?=$current['mb_id']?>>
<input type=hidden id=comment_num<?=$id?> name=comment_num<?=$id?> value=''>

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
                <td><input id="writer_name<?=$id?>" required itemname="�̸�" value="" name="writer_name<?=$id?>" type="text" style="width:85px;" class="input_search">&nbsp;��й�ȣ&nbsp;<input type="password" size=40 id="writer_pw<?=$id?>" name="writer_pw<?=$id?>" required itemname="��й�ȣ" minlength=4 style="width:85px;" class="input_search"></td>
                <td width="180" rowspan=3 valign="top">
                    <img id='kcaptcha_image' />
                    <input title="������ ���ڸ� �Է��ϼ���." type="input" name="wr_key" id="wr_key" size="10" itemname="�ڵ���Ϲ���" required class=ed>
                </td>
            </tr>
            <tr>
                <td width="60">�̸���</td>
                <td><input id="writer_email<?=$id?>" email itemname="�̸���" value="" name="writer_email<?=$id?>" type="text" style="width:226px;" class="input_search"></td>
            </tr>
            <tr>
                <td width="60">Ȩ������</td>
                <td><input id="writer_url<?=$id?>" name="writer_url<?=$id?>" value="" type="text" style="width:226px;" class="input_search"></td>
            </tr>
            <? } ?>
            <tr id=form_secret>
                <td width="60">��д��</td>
                <td><input type=checkbox id=secret<?=$id?> name=secret<?=$id?> value=1></td>
            </tr>
            <tr>
                <td colspan="3">
                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td>
                            <textarea id=writer_content<?=$id?> name=writer_content<?=$id?> cols=<?=$cols?> rows=<?=$rows?> style="width:100%; height:130px;" class="input_search"></textarea>
                        </td>
                        <td width=70 align=center>
                            <input type=image src="<?=$blog_skin_url?>/img/btn_confirm.gif" alt="" width="49" height="71" hspace="5" align="absmiddle">
                            <img src="<?=$blog_skin_url?>/img/btn_cancel.gif" style="cursor:pointer;" onclick="comment_on();">
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
<!--�ڸ�Ʈ ��� ��-->
</form>
</div>
<!-- ��� �Է�â ���� -->

<!-- ���� Ȥ�� ����� ��� ��Ͻ� ����ϴ� �Է��� ����-->
<div id="hidden_comment" style="display:none">
    <table border=0 cellpadding=0 cellspacing=0>
    <?if( empty($member['mb_id']) ) {?>
    <tr><td class=input_td> �̸� </td><td> <input type=text id=comment_mod_name name=comment_mod_name size=40 value="input_name"> 
    </td></tr>
    <tr><td class=input_td> �̸��� </td><td> <input type=text id=comment_mod_email name=comment_mod_email size=40 value="input_email"> </td></tr>
    <tr><td class=input_td> Ȩ������ </td><td> <input type=text id=comment_mod_url name=comment_mod_url size=40 value="input_url"> </td></tr>
    <?}?>
    <tr><td colspan=2><textarea id='comment_mod_content' name='comment_mod_content' cols=<?=$cols?> rows=<?=$rows?>>input_content</textarea></td></tr>
    </table>
    <input type=hidden id=comment_mod_pw name=comment_mod_pw value="input_pw">
    <input type=button value='��     ��' onclick='comment_mod_guest_form_send(input_id)'>
    <input type=button value='��     ��' onclick='comment_on()'>
</div>
<!-- ���� Ȥ�� ����� ��� ��Ͻ� ����ϴ� �Է��� ���� -->

<script type="text/javascript" src="<?="$g4[path]/js/jquery.kcaptcha.js"?>"></script>