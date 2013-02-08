<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// 페이징 가져오기
$paging = get_paging($page_size, $page, $total_page, get_guestbook_page_url() ); 

// 기본으로 넘어오는 페이지를 아래와 같이 변환하여 이미지로도 출력할 수 있습니다.
$paging = str_replace("처음", "<img src='$blog_skin_url/img/btn_list_start.gif' border='0' align='absmiddle' title='처음'>", $paging);
$paging = str_replace("이전", "<img src='$blog_skin_url/img/btn_list_prev.gif' border='0' align='absmiddle' title='이전'>", $paging);
$paging = str_replace("다음", "<img src='$blog_skin_url/img/btn_list_next.gif' border='0' align='absmiddle' title='다음'>", $paging);
$paging = str_replace("맨끝", "<img src='$blog_skin_url/img/btn_list_end.gif' border='0' align='absmiddle' title='맨끝'>", $paging);
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
                    <td width="60">이름</td>
                    <td><input id="writer_name" required itemname="이름" value="" name="writer_name" type="text" style="width:85px;" class="input_search">&nbsp;비밀번호&nbsp;<input type="password" size=40 id="writer_pw" name="writer_pw" required itemname="비밀번호" minlength=4 style="width:85px;" class="input_search"></td>
                    <td width="180" rowspan=3 valign="top">
                        <img id='kcaptcha_image' />
                        <input title="왼쪽의 글자를 입력하세요." type="input" name="wr_key" id="wr_key" size="10" itemname="자동등록방지" required class=ed>
                    </td>
                </td>

                </tr>
                <tr>
                    <td width="60">이메일</td>
                    <td><input id="writer_email" email itemname="이메일" value="" name="writer_email" type="text" style="width:226px;" class="input_search"></td>
                </tr>
                <tr>
                    <td width="60">홈페이지</td>
                    <td><input id="writer_url" name="writer_name" value="" type="text" style="width:226px;" class="input_search"></td>
                </tr>
                <? } ?>
                <tr>
                    <td width="60">비밀글</td>
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

                <!-- 방명록 출력 시작 -->
                <?
                for($i=0; $i<count($guestbook_list); $i++) { 
                    if( $guestbook_list[$i]['secret'] && $member['mb_id'] && ($member['mb_id']==$current['mb_id'] || $guestbook_list[$i]['mb_id'] == $member['mb_id']) )
                        $guestbook_list[$i]['writer_name'] .= " <font color=red style=font-weight:normal>(비밀글)</font>";

                    // 회원의 경우에는 내글만, 비회원의 경우에는 비회원글만 수정/삭제가 가능하게 flag 설정
                    $mod_flag = 0;
                    if ($member[mb_id] && $member[mb_id] ==  $guestbook_list[$i]['mb_id'])
                        $mod_flag = 1;
                    if (!$is_member && $guestbook_list[$i]['mb_id'] == "")
                        $mod_flag = 1;
                    if ($member[mb_id] && $member[mb_id] ==  $current['mb_id'])
                        $mod_flag = 1;
                ?>

                <!-- 방명록 출력 -->
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
                                <!-- 방명록 수정/삭제/방명록의방명록 버튼 -->
                                <? if ($mod_flag) { ?>
                                &nbsp;<a href="javascript:guestbook_permission(<?=$guestbook_list[$i]['id']?>,'mod')">modify</a> |
                                &nbsp;<a href="javascript:guestbook_permission(<?=$guestbook_list[$i]['id']?>,'del')">delete</a>
                                <? } ?>
                                <? if (empty($guestbook_list[$i]['answer_content'])&&$member['mb_id']==$current['mb_id']) { ?>  |
                                &nbsp;<a href="javascript:guestbook_reply(<?=$guestbook_list[$i]['id']?>)">reply</a>
                                <? } ?>
                                <!-- 버튼 종료-->
                            </td>
                        </tr>
                        <tr>
                            <td colspan=2>
                            <!-----------------------------------
                                숨겨진 폼 시작
                            ------------------------------------->

                            <!-- 방명록의 방명록 작성시 이용하는 레이어 -->
                            <div id="r<?=$guestbook_list[$i]['id']?>"></div>

                            <!-- 수정/삭제 시 비밀번호 입력창 -->
                            <div id="p<?=$guestbook_list[$i]['id']?>" style="display:none">
                                비밀번호 :
                                <input type=password name=input_pw<?=$guestbook_list[$i]['id']?> id=input_pw<?=$guestbook_list[$i]['id']?> size=20>
                                <input type=button value=전송 onclick="guestbook_password_confirm('<?=$guestbook_list[$i]['id']?>')">
                            </div>

                            <!-- 수정 시 폼 입력창 -->
                            <div id="m<?=$guestbook_list[$i]['id']?>" style="display:none"></div>

                            <!-----------------------------------
                                숨겨진 폼 종료
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
                    
                    <!--답변방명록 출력-->
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
                                        <!-- 방명록 수정/삭제/방명록의방명록 버튼 -->
                                        <?if ($member['mb_id']==$current['mb_id']) {?>
                                        &nbsp;<a href="javascript:guestbook_permission(<?=$guestbook_list[$i]['id']?>,'mod',1)">modify</a> |
                                        &nbsp;<a href="javascript:guestbook_permission(<?=$guestbook_list[$i]['id']?>,'del',1)">delete</a>
                                        <?}?>
                                        <!-- 버튼 종료-->
                                    </td>
                                </tr>
                                </table>

                                <!-- 수정 시 폼 입력창 -->
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
                    <!--답변방명록 출력 끝-->

                    <?}?>
                </div>
                <!-- 방명록 출력 끝 -->

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

<!-- 수정 혹은 댓글의 댓글 등록시 사용하는 입력폼 시작-->
<div id="hidden_comment" style="display:none">
    <table border=0 cellpadding=0 cellspacing=0>
    <?if( empty($member['mb_id']) ) {?>
    <tr><td class=input_td> 이름 </td><td> <input type=text id=guestbook_mod_name name=guestbook_mod_name size=40 value="input_name"> </td></tr>
    <tr><td class=input_td> 이메일 </td><td> <input type=text id=guestbook_mod_email name=guestbook_mod_email size=40 value="input_email"> </td></tr>
    <tr><td class=input_td> 홈페이지 </td><td> <input type=text id=guestbook_mod_url name=guestbook_mod_url size=40 value="input_url"> </td></tr>
    <?}?>
    <tr><td colspan=2><textarea id='guestbook_mod_content' name='guestbook_mod_content' cols=<?=$cols?> rows=<?=$rows?>>input_content</textarea></td></tr>
    </table>
    <input type=hidden id=guestbook_mod_pw name=guestbook_mod_pw value="input_pw">
    <input type=button value='전     송' onclick='guestbook_mod_guest_form_send(input_id,input_re)'>
    <input type=button value='취     소' onclick='guestbook_on()'>
</div>
<!-- 수정 혹은 댓글의 댓글 등록시 사용하는 입력폼 종료 -->

<script type="text/javascript" src="<?="$g4[path]/js/jquery.kcaptcha.js"?>"></script>