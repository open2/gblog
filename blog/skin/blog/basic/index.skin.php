<?
if (!defined("_GNUBOARD_")) exit; // ���� ������ ���� �Ұ�

// ����¡ ��������
//$paging = get_blog_paging(5, $page, $total_page, get_page_uri($REQUEST_URI) );
$paging = get_paging(10, $page, $total_page, get_page_uri($REQUEST_URI) ); 

// �⺻���� �Ѿ���� �������� �Ʒ��� ���� ��ȯ�Ͽ� �̹����ε� ����� �� �ֽ��ϴ�.
$paging = str_replace("ó��", "<img src='$blog_skin_url/img/btn_list_start.gif' border='0' align='absmiddle' title='ó��'>", $paging);
$paging = str_replace("����", "<img src='$blog_skin_url/img/btn_list_prev.gif' border='0' align='absmiddle' title='����'>", $paging);
$paging = str_replace("����", "<img src='$blog_skin_url/img/btn_list_next.gif' border='0' align='absmiddle' title='����'>", $paging);
$paging = str_replace("�ǳ�", "<img src='$blog_skin_url/img/btn_list_end.gif' border='0' align='absmiddle' title='�ǳ�'>", $paging);
$paging = preg_replace("/<b>([0-9]*)<\/b>/", "<span class=\"move_list_spot\">$1</span>", $paging);

//$cols = 80;
//$rows = 5;
?>

<? // ���� ���� ��  ���
if( !count($post) ) { ?>

    <div id="entry">
        <table border=0 cellpadding=0 cellspacing=0 width=100%>
            <tr>
                <td width=16 height=16><img src="<?=$blog_skin_url?>/img/cont_top_left.gif" alt="" width="16" height="16"></td>
                <td width=100% height=16 style="background: url(<?=$blog_skin_url?>/img/cont_top.gif) no-repeat right top; padding-left:16px;"></td>
            </tr>
            <tr>
                <td width=16 style="background:url(<?=$blog_skin_url?>/img/cont_left.gif) repeat-y left top;"></td>
                <td width=100% bgcolor="#FFFFFF" style="background:url(<?=$blog_skin_url?>/img/cont_bg_01.gif) repeat-y right top; padding-right:16px;">
                    ���� �������� �ʽ��ϴ�.
                </td>
            </tr>
            <tr>
                <td width=16 height=16><img src="<?=$blog_skin_url?>/img/cont_btm_left.gif" alt="" width="16" height="16"></td>
                <td width=100% height=16 style="background:url(<?=$blog_skin_url?>/img/cont_btm.gif) no-repeat right bottom;"></td>
            </tr>
        </table>
    </div>

<? } // ���� ���� �� ��� ��?>


<? // ��α� �� ��� ���� ����
    for($i=0; $i<count($post); $i++) { 
        
        // ���α� ��� �㰡
        if( !$post[$i]['use_trackback'] || !$current['use_trackback'] )
            $use_trackback = false;
        else
            $use_trackback = true;

        // ��� ��� �㰡
        if( !$post[$i]['use_comment'] || !$current['use_comment'] )
            $use_comment = false;
        else
            $use_comment = true;

?>
    <div id="entry">

        <table border=0 cellpadding=0 cellspacing=0 width=100%>
            <tr>
                <td width=16 height=16><img src="<?=$blog_skin_url?>/img/cont_top_left.gif" alt="" width="16" height="16"></td>
                <td width=100% height=16 style="background: url(<?=$blog_skin_url?>/img/cont_top.gif) no-repeat right top; padding-left:16px;"></td>
            </tr>
            <tr>
                <td width=16 style="background:url(<?=$blog_skin_url?>/img/cont_left.gif) repeat-y left top;"></td>
                <td width=100% bgcolor="#FFFFFF" style="background:url(<?=$blog_skin_url?>/img/cont_bg_01.gif) repeat-y right top; padding-right:16px;">


                    <!-- ���� - ����,ī�װ� ��¥ ��� -->
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-bottom:6px;">
                        <tr>
                            <td style="width:3px;"><img src="<?=$blog_skin_url?>/img/cont_subj_left.gif" alt=""></td>
                            <td style="background:url(<?=$blog_skin_url?>/img/cont_subj_bg.gif) repeat-x left top; padding-left:17px;" class="title"><a href="<?=$post[$i]['url']?>"><?=$post[$i]['title']?></a><span class="secret"><? if( $post[$i]['secret'] != 1 ) echo "(�����)"; ?></span></td>
                            <td style="background:url(<?=$blog_skin_url?>/img/cont_subj_bg.gif) repeat-x left top; padding-right:5px; text-align:right;">
                            <img src="<?=$blog_skin_url?>/img/img_divide_head.gif" alt="" width="2" height="15" align="absmiddle"><img src="<?=$blog_skin_url?>/img/icon_category.gif" alt="" hspace="3" align="absmiddle"><font class="category"><?=$post[$i]['category_name']?></font><img src="<?=$blog_skin_url?>/img/img_divide_head.gif" alt="" width="2" height="15" hspace="3" align="absmiddle"><font class="datetime"><?=$post[$i]['post_date']?></font>
                            </td>
                            <td style="width:3px;"><img src="<?=$blog_skin_url?>/img/cont_subj_right.gif" alt=""></td>
                        </tr>
                    </table>

                    <div class="article_02">

                    <?if( is_myblog() || $is_admin =="super" ) {?>
                    <!--���� - ������ ����/������ư-->						
                        <a href="javascript:post_mod(<?=$post[$i]['id']?>)"><img src="<?=$blog_skin_url?>/img/btn_adm_edit.gif" alt="" align=absmiddle></a>&nbsp;<a href="javascript:post_del(<?=$post[$i]['id']?>)"><img src="<?=$blog_skin_url?>/img/btn_adm_del.gif" alt="" align=absmiddle></a>
                        ��ȸ�� : <?=$post[$i]['hit']?>, 
                    <? } ?>
                    <? if ($gb4[bitly_id] && $gb4[bitly_key]) { ?>
                    &nbsp;&nbsp;Bit.ly : <span id="bitly_url"><a href="<?=$post[$i]['bitly_url']?>" target=_new><?=$post[$i]['bitly_url']?></a></span>
                    <? } ?>
                    </div>

                    <!--���� - ÷������ ���-->
                    <div style="padding-left:7px;" class="article_02">
                        <?for($j=0; $j<($post[$i]['file']['count']); $j++) {
                            if ($post[$i][file][$j][save_name] && !$post[$i][file][$j][view]) {
                            ?>
                                <font class="files_01">÷������ <?=sprintf("%02d",$j+1)?> : </font><a href="<?=$post[$i]['file'][$j]['href']?>"><font class="files_02"><?=$post[$i]['file'][$j]['real_name']?></font></a><br>
                            <?}?>
                        <?}?>
                    </div>

                    <? 
                    // ���� ���
                    for ($j=0; $j<$post[$i]['file']['count']; $j++) {
                        if ($post[$i][file][$j][view]) 
                            echo resize_dica($post[$i][file][$j][view],250,300) . "<BR/>&nbsp;&nbsp;&nbsp;" . $post[$i][file][$j][content] . "<br/>"; if (trim($post[$i][file][$j][content])) echo "<br/>"; 
                            //echo $post[$i][file][$j][view] . "<p>";
                    }
                    ?>

                    <!--//////// ������� ����~ ��! ///////-->
                    <div class="article">
                        <div><?=$current['content_head']?></div>
                        <?=$post[$i]['content']?>
                        <div><?=$current['content_tail']?></div>
                    </div>

                    <!-- �±����-->
                    <div class="tag">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <?if($post[$i]['tag']){?>
                        <tr>
                            <td colspan="2" style="height:1px; background:#D2D2D2;"></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="tag_01"><img src="<?=$blog_skin_url?>/img/icon_tag.gif" hspace="5" style="vertical-align:middle;"><?=$post[$i]['tag'];?></td>
                        </tr>
                        <?}?>
                        <tr>
                            <td colspan="2" style="height:1px; background:#D2D2D2;"></td>
                        </tr>
                        <tr>
                            <td style="padding-left:7px;" class="tag_02">
                                <? if( $use_comment ) { ?>
                                <a href="javascript:comment_open_close('<?=$post[$i]['id']?>')">��� <span id=comment_count<?=$post[$i]['id']?>><?=$post[$i]['comment_count']?></span>��</a>
                                <? } ?>
                                
                                <? if( $use_comment ) { ?>
                                <img src="<?=$blog_skin_url?>/img/img_divide_01.gif" alt="" width="1" height="15" hspace="6" align="absmiddle">
                                <? } ?>
                                <? if( $use_trackback ) { ?>
                                <a href="javascript:trackback_open_close('<?=$post[$i]['id']?>')">���α� <span id=trackback_count<?=$post[$i]['id']?>><?=$post[$i]['trackback_count']?>��</span></a>
                                <? }?>
                            </td>
                            <td style="text-align:right; padding-right:7px;" class="tag_02">
                                <? if ($post[$i]['use_ccl_writer']) { ?><img src="<?=$blog_skin_url?>/img/ccl_by.gif" alt='������ǥ��' align=absmiddle border=0><?}?><? if ($post[$i]['use_ccl_commecial']) { ?><img src="<?=$blog_skin_url?>/img/ccl_nc.gif" alt='�񿵸�' align=absmiddle border=0><?}?><? if ($post[$i]['use_ccl_modify']) { ?><img src="<?=$blog_skin_url?>/img/ccl_nd.gif" alt='�������' align=absmiddle border=0><?}?><? if ($post[$i]['use_ccl_allow']) { ?><img src="<?=$blog_skin_url?>/img/ccl_sa.gif" alt='�������Ǻ������' align=absmiddle border=0><?}?>
                                <? if( $use_trackback ) { ?>
                                <a href="javascript:trackback_send_server('<?=$post[$i]['trackback_url']?>');" style="letter-spacing:0;" title='�� ���� �Ұ��� ���� �� �ּҸ� ����ϼ���'>�� �ۿ� ���α� ������</a>
                                <? }?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="height:1px; background:#D2D2D2;"></td>
                        </tr>
                    </table>
                    </div>



                    <!-- Ʈ���� ���̸���. ����~ -->
                    <div id=trackback<?=$post[$i]['id']?> <?if( empty($id) || !$use_trackback || !count($post[$i]['trackback']) ) echo "style='display:none'"?>>
                    <?if (count($post[$i]['trackback'])) {?>
                    <div><img src="<?=$blog_skin_url?>/img/img_trackback.gif" alt="" width="129" height="15"></div>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" id="trackback">
                        <tr>
                            <td width="3" height="3"><img src="<?=$blog_skin_url?>/img/comment_top_left.gif" alt=""></td>
                            <td width=100% height="3" style="background:url(<?=$blog_skin_url?>/img/comment_top.gif) no-repeat right top;"></td>
                        </tr>
                        <tr>
                            <td width="3" style="background:url(<?=$blog_skin_url?>/img/comment_left.gif) repeat-y left top;"></td>
                            <td width="100%" style="background:url(<?=$blog_skin_url?>/img/comment_bg_01.gif) repeat-y right top; padding:5px 10px 0 7px;">
                                <!-- Ʈ���� ��� -->
                                <div>
                                    <?for($j=0; $j<count($post[$i]['trackback']); $j++){?>
                                    <div id="t<?=$post[$i]['trackback'][$j]['id']?>">
                                        <?if($j>0){?><ul style="background:url(<?=$blog_skin_url?>/img/line_01.gif) repeat-x left center; height:15px;"></ul><?}?>
                                        <ul>
                                            <li class="name_01">
                                                <font class="name_02"><?=$post[$i]['trackback'][$j]['writer_subject']?>  </font>
                                                &nbsp;
                                                <font class="date"><?=$post[$i]['trackback'][$j]['regdate']?></font>
                                             </li>
                                            <?if($member['mb_id']==$current['mb_id']){?>
                                            <li class="function">
                                                <a href="javascript:trackback_del(<?=$post[$i]['trackback'][$j]['id']?>,<?=$post[$i]['id']?>)">delete</a>
                                            </li>
                                            <?}?>
                                        </ul>
                                        <ul class="cont" style="margin-top:10px;">
                                        <?=$post[$i]['trackback'][$j]['writer_content']?>
                                        </ul>
                                    </div>
                                    <?}?>

                                </div>
                                <!-- Ʈ���� ��� �� -->

                            </td>
                        </tr>
                        <tr>
                            <td width="3" height="3"><img src="<?=$blog_skin_url?>/img/comment_btm_left.gif" alt=""></td>
                            <td width=100% height="3" style="background:url(<?=$blog_skin_url?>/img/comment_btm.gif) no-repeat right ;"></td>
                        </tr>
                    </table>
                    <?}?>
                    </div>

                    <!--Ʈ���� ���̸��� �� -_-a -->


                    <!-- ��� ����-->
                    <div id=comment<?=$post[$i]['id']?> <?if( empty($id) || !$use_comment ) echo "style='display:none'"?>><?=$comment?></div>
                    <!-- ��� ���� -->

                </td>
            </tr>
            <tr>
                <td width=16 height=16><img src="<?=$blog_skin_url?>/img/cont_btm_left.gif" alt="" width="16" height="16"></td>
                <td width=100% height=16 style="background:url(<?=$blog_skin_url?>/img/cont_btm.gif) no-repeat right bottom;"></td>
            </tr>
        </table>

    </div>


    <?if( !empty($id) && ($post[$i]['next']['title'] || $post[$i]['prev']['title']) ){?>

		<!--����/������ ���-->	
		<table border=0 cellpadding=0 cellspacing=0 width=100%>
			<tr>
				<td width=16 height=16><img src="<?=$blog_skin_url?>/img/cont_top_left.gif" alt="" width="16" height="16"></td>
				<td width=100% height=16 style="background: url(<?=$blog_skin_url?>/img/cont_top.gif) no-repeat right top; padding-left:16px;"></td>
			</tr>
			<tr>
				<td width=16 style="background:url(<?=$blog_skin_url?>/img/cont_left.gif) repeat-y left top;"></td>
				<td width=100% bgcolor="#FFFFFF" style="background:url(<?=$blog_skin_url?>/img/cont_bg_01.gif) repeat-y right top; padding-right:16px;">
			        <div class="move_post" style="display:<?=$post[$i]['next']['display']?>;"><a href="<?=$post[$i]['next']['href']?>"><img src="<?=$blog_skin_url?>/img/btn_prev.gif" alt="" align="absmiddle"></a> <a href="<?=$post[$i]['next']['href']?>"><?=$post[$i]['next']['title']?> </a>&nbsp;&nbsp;<span class="move_post_date">(<?=$post[$i]['next']['post_date']?>)</span></div>
			        <div class="move_post" style="display:<?=$post[$i]['prev']['display']?>;"><a href="<?=$post[$i]['prev']['href']?>"><img src="<?=$blog_skin_url?>/img/btn_next.gif" alt="" align="absmiddle"></a> <a href="<?=$post[$i]['prev']['href']?>"><?=$post[$i]['prev']['title']?> </a>&nbsp;&nbsp;<span class="move_post_date">(<?=$post[$i]['prev']['post_date']?>)</span></div>
				</td>
			</tr>
			<tr>
				<td width=16 height=16><img src="<?=$blog_skin_url?>/img/cont_btm_left.gif" alt="" width="16" height="16"></td>
				<td width=100% height=16 style="background:url(<?=$blog_skin_url?>/img/cont_btm.gif) no-repeat right bottom;"></td>
			</tr>
		</table>

    <?}?>


<? }  // ��α� �� ��� ���� �� ?>

<!-- ����¡ -->
<?if( empty($id) ){?>
<div class="move_list"><?=$paging?></div>
<?}?>