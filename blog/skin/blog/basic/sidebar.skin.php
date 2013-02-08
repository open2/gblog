<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

/*-----------------------------------------------------------------------
    프로필
-------------------------------------------------------------------------*/
if( $use_sb['profile'] ) {
    if( !file_exists($current['profile_image_path']) ) {
        $current['profile_image_path'] = "{$blog_skin_path}/img/profile.gif";
        $current['profile_image_url'] = "{$blog_skin_url}/img/profile.gif";
    }
    @$profile_image_size = getimagesize($current['profile_image_path']);
    if( $profile_image_size[0] > 183 )
        $profile_image_size_width = 183;
    else
        $profile_image_size_width = $profile_image_size[0];
    ob_start();
?>

<div class="sidebar">

    <table border=0 cellpadding=0 cellspacing=0 width=100%>
        <tr>
            <td width=6 height=6><img src="<?=$blog_skin_url?>/img/line_top_left_profile.gif" alt="" width="6" height="6"></td>
            <td width=189 height=6 style="background:url(<?=$blog_skin_url?>/img/line_top_profile.gif) no-repeat right top;"></td>
        </tr>
        <tr>
            <td width=6 style="background:url(<?=$blog_skin_url?>/img/line_left_profile.gif) repeat-y left top; padding-left:6px;"></td>
            <td width=189 bgcolor=#FFFFFF style="background:url(<?=$blog_skin_url?>/img/line_bg_01_profile.gif) repeat-y right top; padding-right:6px;">
                <!-- 프로필 이미지 -->
                <div style="margin-bottom:10px; text-align:center;"><a href="<?=$current['blog_url']?>"><img src="<?=$current['profile_image_url']?>?<?=time()?>" width=<?=$profile_image_size_width?> alt=profile_image name='target_resize_image[]' style='cursor:pointer;'></a></div>
            </td>
        </tr>
        <tr>
            <td width=6 style="background:url(<?=$blog_skin_url?>/img/line_left_profile.gif) repeat-y left top; padding-left:6px;"></td>
            <td width=100% bgcolor=#FFFFFF style="background:url(<?=$blog_skin_url?>/img/line_bg_01_profile.gif) repeat-y right top; padding-right:6px;">
                <!-- 블로그 소개 및 필명 -->
                <div id="profile"> 
                    <ul>
                        <li id="about"><img src="<?=$blog_skin_url?>/img/icon_arow_01.gif" alt="" align="absmiddle">&nbsp;<?=$current['blog_about']?></li>
                    </ul>
                    <ul>
                        <li><?=get_sideview($mb_id, $current['writer'], $current['mb_email'], $current['mb_homepage'])?></li>
                    </ul>
                </div>
            </td>
        </tr>
        <tr>
            <td width=6 height=6><img src="<?=$blog_skin_url?>/img/line_btm_left_profile.gif" alt="" width="6" height="6"></td>
            <td width=189 height=6 style="background:url(<?=$blog_skin_url?>/img/line_btm_profile.gif) no-repeat right bottom;"></td>
        </tr>
    </table>


</div>
<? 
    $sidebar['profile'] = ob_get_contents();
    ob_clean();
}

/*-----------------------------------------------------------------------
    관리자
-------------------------------------------------------------------------*/
if( $use_sb['admin'] && $current['mb_id'] == $member['mb_id'] ) {
    ob_start();
?>
<div id="admin" class="sidebar">

    <table border=0 cellpadding=0 cellspacing=0 width=100%>
        <tr>
            <td width=4 height=4><img src="<?=$blog_skin_url?>/img/line_top_left.gif" alt="" width="4" height="4"></td>
            <td width=100% height=4 style="background:url(<?=$blog_skin_url?>/img/line_top.gif) no-repeat right top;"></td>
        </tr>
        <tr>
        <td width=4 style="background:url(<?=$blog_skin_url?>/img/line_left.gif) repeat-y right top;"></td>
        <td width=191 bgcolor=#FFFFFF class="sidebar_content">

            <!--오리주둥이 추가 - 사이드바 타이틀-->
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr style="background:#D2D2D2;">
                    <td width="1"><img src="<?=$blog_skin_url?>/img/side_title_01.gif" alt="" width="1" height="19"></td>
                    <td class="title">Admin</td>
                    <td style="text-align:right; padding-right:4px;"><!--<img src="<?=$blog_skin_url?>/img/icon_<?=$sud_btn['']?>.gif" alt="" width="9" height="9">--></td>
                    <td width="1"><img src="<?=$blog_skin_url?>/img/side_title_01.gif" alt="" width="1" height="19"></td>
                </tr>
            </table>

            <div style="padding-top:5px">
            <a href="<?=$current['admin_url']?>"><img src="<?=$blog_skin_url?>/img/btn_adm_adm.gif" alt="" hspace="4"></a>
            <a href="<?=$current['write_url']?>"><img src="<?=$blog_skin_url?>/img/btn_adm_wirte.gif" alt=""></a>
            </div>
        </td>
        </tr>
        <tr>
            <td width=4 height=4><img src="<?=$blog_skin_url?>/img/line_btm_left.gif" alt="" width="4" height="4"></td>
            <td width=191 height=4 style="background:url(<?=$blog_skin_url?>/img/line_btm.gif) no-repeat right bottom;"></td>
        </tr>
    </table>

</div>
<?
    $sidebar['admin'] = ob_get_contents();
    ob_clean();
} 

/*-----------------------------------------------------------------------
    검색
-------------------------------------------------------------------------*/
if( $use_sb['search']) {
    ob_start();
?>
<div id="search" class="sidebar">


    <table border=0 cellpadding=0 cellspacing=0 width=100%>
        <tr>
            <td width=4 height=4><img src="<?=$blog_skin_url?>/img/line_top_left.gif" alt="" width="4" height="4"></td>
            <td width=191 height=4 style="background:url(<?=$blog_skin_url?>/img/line_top.gif) no-repeat right top;"></td>
        </tr>
        <tr>
        <td width=4 style="background:url(<?=$blog_skin_url?>/img/line_left.gif) repeat-y right top;"></td>
        <td width=191 bgcolor=#FFFFFF class="sidebar_content">

            <!--오리주둥이 추가 - 사이드바 타이틀-->
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr style="background:#D2D2D2;">
                    <td width="1"><img src="<?=$blog_skin_url?>/img/side_title_01.gif" alt="" width="1" height="19"></td>
                    <td class="title">Search</td>
                    <td style="text-align:right; padding-right:4px;"><a href="javascript:sud('sud_search')"><img src="<?=$blog_skin_url?>/img/icon_<?=$sud_btn['sud_search']?>.gif" alt="" width="9" height="9" id=sud_search_button></a></td>
                    <td width="1"><img src="<?=$blog_skin_url?>/img/side_title_01.gif" alt="" width="1" height="19"></td>
                </tr>
            </table>

            <div style="padding:5px 0 3px 0;" id=sud_search>
            <input type=text id="search_content" name="search_content" value="<?=$search?>" onKeyPress="if(event.keyCode==13) search()" style="margin-left:2px;" class="input_search">
            <input type=hidden id="search_url" name="search_url" value="<?=$search_url?>" onKeyPress="if(event.keyCode==13) search()" style="margin-left:2px;" class="input_search">
            <img src="<?=$blog_skin_url?>/img/btn_search.gif" alt="" style="cursor:pointer" align="absmiddle" onclick="search()">
            </div>
        </td>
        </tr>
        <tr>
            <td width=4 height=4><img src="<?=$blog_skin_url?>/img/line_btm_left.gif" alt="" width="4" height="4"></td>
            <td width=191 height=4 style="background:url(<?=$blog_skin_url?>/img/line_btm.gif) no-repeat right bottom;"></td>
        </tr>
    </table>

</div>
<?
    $sidebar['search'] = ob_get_contents();
    ob_clean();
}

/*-----------------------------------------------------------------------
    글분류
-------------------------------------------------------------------------*/

if( $use_sb['category']) {
    ob_start();
?>
<div id="category" class="sidebar">

    <table border=0 cellpadding=0 cellspacing=0 width=100%>
        <tr>
            <td width=4 height=4><img src="<?=$blog_skin_url?>/img/line_top_left.gif" alt="" width="4" height="4"></td>
            <td width=191 height=4 style="background:url(<?=$blog_skin_url?>/img/line_top.gif) no-repeat right top; padding-right:4px;"></td>
        </tr>
        <tr>
            <td width=4 style="background:url(<?=$blog_skin_url?>/img/line_left.gif) repeat-y right top;"></td>
            <td width=191 bgcolor=#FFFFFF class="sidebar_content">

                <!--오리주둥이 추가 - 사이드바 타이틀-->
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-bottom:5px;">
                    <tr style="background:#D2D2D2;">
                        <td width="1"><img src="<?=$blog_skin_url?>/img/side_title_01.gif" alt="" width="1" height="19"></td>
                        <td class="title">Categories</td>
                        <td style="text-align:right; padding-right:4px;"><a href="javascript:sud('sud_category')"><img src="<?=$blog_skin_url?>/img/icon_<?=$sud_btn['sud_category']?>.gif" alt="" width="9" height="9" id=sud_category_button></a></td>
                        <td width="1"><img src="<?=$blog_skin_url?>/img/side_title_01.gif" alt="" width="1" height="19"></td>
                    </tr>
                </table>
                <div id=sud_category>
                <ul class="list_category">
                <? for($i=0; $i<count($category); $i++) { ?>
                <li> <a href="<?=$category[$i]['url']?>"><?=$category[$i]['category_name']?></a> <span class="count_info">(<?=$category[$i]['post_count']?>)</span></li>
                <? } ?>
                </ul>
                </div>
           </td>
        </tr>
        <tr>
            <td width=4 height=4><img src="<?=$blog_skin_url?>/img/line_btm_left.gif" alt="" width="4" height="4"></td>
            <td width=191 height=4 style="background:url(<?=$blog_skin_url?>/img/line_btm.gif) no-repeat right bottom;"></td>
        </tr>
    </table>


</div>
<? 
    $sidebar['category'] = ob_get_contents();
    ob_clean();
}

/*-----------------------------------------------------------------------
    최신글
-------------------------------------------------------------------------*/
if( $use_sb['recent_post']) {
    ob_start();
?>
<div id="new_post" class="sidebar">

    <table border=0 cellpadding=0 cellspacing=0 width=100%>
        <tr>
            <td width=4 height=4><img src="<?=$blog_skin_url?>/img/line_top_left.gif" alt="" width="4" height="4"></td>
            <td width=191 height=4 style="background:url(<?=$blog_skin_url?>/img/line_top.gif) no-repeat right top;"></td>
        </tr>
        <tr>
        <td width=4 style="background:url(<?=$blog_skin_url?>/img/line_left.gif) repeat-y right top;"></td>
        <td width=191 bgcolor=#FFFFFF class="sidebar_content">
            
            <!--오리주둥이 추가 - 사이드바 타이틀-->
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-bottom:5px;">
                <tr style="background:#D2D2D2;">
                    <td width="1"><img src="<?=$blog_skin_url?>/img/side_title_01.gif" alt="" width="1" height="19"></td>
                    <td class="title">New post</td>
                    <td style="text-align:right; padding-right:4px;"><a href="javascript:sud('sud_post')"><img src="<?=$blog_skin_url?>/img/icon_<?=$sud_btn['sud_post']?>.gif" alt="" width="9" height="9" id=sud_post_button></a></td>
                    <td width="1"><img src="<?=$blog_skin_url?>/img/side_title_01.gif" alt="" width="1" height="19"></td>
                </tr>
            </table>
            <div id=sud_post>
                <ul class="list_post">
                <? for($i=0; $i<count($new_post); $i++) { ?>
                <li><a href="<?=$new_post[$i]['url']?>"><?=$new_post[$i]['title']?></a> <span class="count_info">(<?=$new_post[$i]['comment_count']?>)</span></li>
                <? } ?>
                </ul>
            </div>

        </td>
        </tr>
        <tr>
            <td width=4 height=4><img src="<?=$blog_skin_url?>/img/line_btm_left.gif" alt="" width="4" height="4"></td>
            <td width=191 height=4 style="background:url(<?=$blog_skin_url?>/img/line_btm.gif) no-repeat right bottom;"></td>
        </tr>
    </table>


</div>
<?
    $sidebar['recent_post'] = ob_get_contents();
    ob_clean();
}

/*-----------------------------------------------------------------------
    최신댓글
-------------------------------------------------------------------------*/
if( $use_sb['recent_comment']) {
    ob_start();
?>
<div id="new_comment" class="sidebar">

    <table border=0 cellpadding=0 cellspacing=0 width=100%>
        <tr>
            <td width=4 height=4><img src="<?=$blog_skin_url?>/img/line_top_left.gif" alt="" width="4" height="4"></td>
            <td width=191 height=4 style="background:url(<?=$blog_skin_url?>/img/line_top.gif) no-repeat right top;"></td>
        </tr>
        <tr>
        <td width=4 style="background:url(<?=$blog_skin_url?>/img/line_left.gif) repeat-y right top;"></td>
        <td width=191 bgcolor=#FFFFFF class="sidebar_content">

            <!--오리주둥이 추가 - 사이드바 타이틀-->
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-bottom:5px;">
                <tr style="background:#D2D2D2;">
                    <td width="1"><img src="<?=$blog_skin_url?>/img/side_title_01.gif" alt="" width="1" height="19"></td>
                    <td class="title">New comment</td>
                    <td style="text-align:right; padding-right:4px;"><a href="javascript:sud('sud_comment')"><img src="<?=$blog_skin_url?>/img/icon_<?=$sud_btn['sud_comment']?>.gif" alt="" width="9" height="9" id=sud_comment_button></a></td>
                    <td width="1"><img src="<?=$blog_skin_url?>/img/side_title_01.gif" alt="" width="1" height="19"></td>
                </tr>
            </table>
            <div id=sud_comment>
                <ul class="list_comment">
                <? for($i=0; $i<count($new_comment); $i++) { ?>
                <li><a href="<?=$new_comment[$i]['url']?>"><?=$new_comment[$i]['writer_content']?></a> <div class="sidebar_date"><?=$new_comment[$i]['regdate']?></div></li>
                <? } ?>
                </ul>
            </div>
       </td>
        </tr>
        <tr>
            <td width=4 height=4><img src="<?=$blog_skin_url?>/img/line_btm_left.gif" alt="" width="4" height="4"></td>
            <td width=191 height=4 style="background:url(<?=$blog_skin_url?>/img/line_btm.gif) no-repeat right bottom;"></td>
        </tr>
    </table>
</div>
<?
    $sidebar['recent_comment'] = ob_get_contents();
    ob_clean();
}

/*-----------------------------------------------------------------------
    최신 엮인글
-------------------------------------------------------------------------*/
if( $use_sb['recent_trackback']) {
    ob_start();
?>
<div id="new_trackback" class="sidebar">

    <table border=0 cellpadding=0 cellspacing=0 width=100%>
        <tr>
            <td width=4 height=4><img src="<?=$blog_skin_url?>/img/line_top_left.gif" alt="" width="4" height="4"></td>
            <td width=191 height=4 style="background:url(<?=$blog_skin_url?>/img/line_top.gif) no-repeat right top;"></td>
        </tr>
        <tr>
        <td width=4 style="background:url(<?=$blog_skin_url?>/img/line_left.gif) repeat-y right top;"></td>
        <td width=191 bgcolor=#FFFFFF class="sidebar_content">

            <!--오리주둥이 추가 - 사이드바 타이틀-->
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-bottom:5px;">
                <tr style="background:#D2D2D2;">
                    <td width="1"><img src="<?=$blog_skin_url?>/img/side_title_01.gif" alt="" width="1" height="19"></td>
                    <td class="title">New trackback</td>
                    <td style="text-align:right; padding-right:4px;"><a href="javascript:sud('sud_trackback')"><img src="<?=$blog_skin_url?>/img/icon_<?=$sud_btn['sud_trackback']?>.gif" alt="" width="9" height="9" id=sud_trackback_button></a></td>
                    <td width="1"><img src="<?=$blog_skin_url?>/img/side_title_01.gif" alt="" width="1" height="19"></td>
                </tr>
            </table>
            <div id=sud_trackback>
                <ul class="list_trackback">
                <? for($i=0; $i<count($new_trackback); $i++) { ?>
                <li><a href="<?=$new_trackback[$i]['url']?>"><?=$new_trackback[$i]['writer_content']?></a> <div class="sidebar_date"><?=$new_trackback[$i]['regdate']?></div></li>
                <? } ?>
                </ul>
            </div>
        </td>
        </tr>
        <tr>
            <td width=4 height=4><img src="<?=$blog_skin_url?>/img/line_btm_left.gif" alt="" width="4" height="4"></td>
            <td width=191 height=4 style="background:url(<?=$blog_skin_url?>/img/line_btm.gif) no-repeat right bottom;"></td>
        </tr>
    </table>

</div>
<?
    $sidebar['recent_trackback'] = ob_get_contents();
    ob_clean();
}

/*-----------------------------------------------------------------------
    태그구름
-------------------------------------------------------------------------*/
if( $current['use_tag'] && $use_sb['tag']) {
    ob_start();
?>
<div id="tag" class="sidebar">

    <table border=0 cellpadding=0 cellspacing=0 width=100%>
        <tr>
            <td width=4 height=4><img src="<?=$blog_skin_url?>/img/line_top_left.gif" alt="" width="4" height="4"></td>
            <td width=191 height=4 style="background:url(<?=$blog_skin_url?>/img/line_top.gif) no-repeat right top;"></td>
        </tr>
        <tr>
        <td width=4 style="background:url(<?=$blog_skin_url?>/img/line_left.gif) repeat-y right top;"></td>
        <td width=191 bgcolor=#FFFFFF class="sidebar_content">

            <!--오리주둥이 추가 - 사이드바 타이틀-->
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-bottom:5px;">
                <tr style="background:#D2D2D2;">
                    <td width="1"><img src="<?=$blog_skin_url?>/img/side_title_01.gif" alt="" width="1" height="19"></td>
                    <td class="title">Tags</td>
                    <td style="text-align:right; padding-right:4px;"><a href="<?=get_tag_cloud_url()?>"><img src="<?=$blog_skin_url?>/img/btn_tagcloud_02.gif" alt="" width="60" height="15" hspace="6" align="absmiddle"></a><a href="javascript:sud('sud_tag')"><img src="<?=$blog_skin_url?>/img/icon_<?=$sud_btn['sud_tag']?>.gif" alt="" width="9" height="9" align="absmiddle" id=sud_tag_button></a></td>
                    <td width="1"><img src="<?=$blog_skin_url?>/img/side_title_01.gif" alt="" width="1" height="19"></td>
                </tr>
            </table>
            <ul class="tag_cloud" id=sud_tag>
                <li>
                    <? for($i=0; $i<count($tags); $i++) { ?>
                    <a href="<?=$tags[$i]['url']?>"><span class="tag_cloud_<?=$tags[$i]['rank']?>"><?=$tags[$i]['tag']?></span></a>
                    <? } ?>
                </li>
            </ul>
        </td>
        </tr>
        <tr>
            <td width=4 height=4><img src="<?=$blog_skin_url?>/img/line_btm_left.gif" alt="" width="4" height="4"></td>
            <td width=191 height=4 style="background:url(<?=$blog_skin_url?>/img/line_btm.gif) no-repeat right bottom;"></td>
        </tr>
    </table>

</div>
<?
    $sidebar['tag'] = ob_get_contents();
    ob_clean();
}

/*-----------------------------------------------------------------------
    달력
-------------------------------------------------------------------------*/
if( $use_sb['calendar']) {
    ob_start();
?>
<div id="calendar" class="sidebar">
        <table border=0 cellpadding=0 cellspacing=0 width=100%>
            <tr>
                <td width=4 height=4><img src="<?=$blog_skin_url?>/img/line_top_left.gif" alt="" width="4" height="4"></td>
                <td width=191 height=4 style="background:url(<?=$blog_skin_url?>/img/line_top.gif) no-repeat right top;"></td>
            </tr>
        <tr>
            <td width=4 style="background:url(<?=$blog_skin_url?>/img/line_left.gif) repeat-y right top;"></td>
            <td width=191 bgcolor=#FFFFFF class="sidebar_content">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-bottom:5px;">
                    <tr style="background:#D2D2D2;">
                        <td width="1"><img src="<?=$blog_skin_url?>/img/side_title_01.gif" alt="" width="1" height="19"></td>
                        <td class="title">Calendar</td>
                        <td style="text-align:right; padding-right:4px;"><a href="javascript:sud('sud_calendar')"><img src="<?=$blog_skin_url?>/img/icon_<?=$sud_btn['sud_calendar']?>.gif" alt="" width="9" height="9" id=sud_calendar_button></a></td>
                        <td width="1"><img src="<?=$blog_skin_url?>/img/side_title_01.gif" alt="" width="1" height="19"></td>
                    </tr>
                </table>
                <div id=sud_calendar>
                <?=$calendar?>
                </div>
            </td>
        </tr>
        <tr>
            <td width=4 height=4><img src="<?=$blog_skin_url?>/img/line_btm_left.gif" alt="" width="4" height="4"></td>
            <td width=191 height=4 style="background:url(<?=$blog_skin_url?>/img/line_btm.gif) no-repeat right bottom;"></td>
        </tr>
    </table>
</div>
<?
    $sidebar['calendar'] = ob_get_contents();
    ob_clean();
}

/*-----------------------------------------------------------------------
    월별 글 묶음
-------------------------------------------------------------------------*/
if( $use_sb['monthly']) {
    ob_start();
?>
<div id="monthly" class="sidebar">

    <table border=0 cellpadding=0 cellspacing=0 width=100%>
        <tr>
            <td width=4 height=4><img src="<?=$blog_skin_url?>/img/line_top_left.gif" alt="" width="4" height="4"></td>
            <td width=191 height=4 style="background:url(<?=$blog_skin_url?>/img/line_top.gif) no-repeat right top;"></td>
        </tr>
        <tr>
            <td width=4 style="background:url(<?=$blog_skin_url?>/img/line_left.gif) repeat-y right top;"></td>
            <td width=191 bgcolor=#FFFFFF class="sidebar_content">


            <!--오리주둥이 추가 - 사이드바 타이틀-->
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-bottom:5px;">
                <tr style="background:#D2D2D2;">
                    <td width="1"><img src="<?=$blog_skin_url?>/img/side_title_01.gif" alt="" width="1" height="19"></td>
                    <td class="title">Monthly</td>
                    <td style="text-align:right; padding-right:4px;"><a href="javascript:sud('sud_monthly')"><img src="<?=$blog_skin_url?>/img/icon_<?=$sud_btn['sud_monthly']?>.gif" alt="" width="9" height="9" id=sud_monthly_button></a></td>
                    <td width="1"><img src="<?=$blog_skin_url?>/img/side_title_01.gif" alt="" width="1" height="19"></td>
                </tr>
                </table>
                <div id=sud_monthly>
                    <ul class="list_monthly">
                    <? for($i=0; $i<count($monthly); $i++) { ?>
                    <li><a href="<?=$monthly[$i]['url']?>"><?=$monthly[$i]['monthly']?></a> <span class="count_info">(<?=$monthly[$i]['post_count']?>)</span><br></li>
                    <? } ?>
                    </ul>
                </div>
            </td>
        </tr>
        <tr>
            <td width=4 height=4><img src="<?=$blog_skin_url?>/img/line_btm_left.gif" alt="" width="4" height="4"></td>
            <td width=191 height=4 style="background:url(<?=$blog_skin_url?>/img/line_btm.gif) no-repeat right bottom;"></td>
        </tr>
    </table>

</div>
<?
    $sidebar['monthly'] = ob_get_contents();
    ob_clean();
}

/*-----------------------------------------------------------------------
    즐겨찾기
-------------------------------------------------------------------------*/
if( $use_sb['link']) {
    ob_start();
?>
<div id="link" class="sidebar">

    <table border=0 cellpadding=0 cellspacing=0 width=100%>
        <tr>
            <td width=4 height=4><img src="<?=$blog_skin_url?>/img/line_top_left.gif" alt="" width="4" height="4"></td>
            <td width=191 height=4 style="background:url(<?=$blog_skin_url?>/img/line_top.gif) no-repeat right top;"></td>
        </tr>
        <tr>
        <td width=4 style="background:url(<?=$blog_skin_url?>/img/line_left.gif) repeat-y right top;"></td>
        <td width=191 bgcolor=#FFFFFF class="sidebar_content">


            <!--오리주둥이 추가 - 사이드바 타이틀-->
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-bottom:5px;">
                <tr style="background:#D2D2D2;">
                    <td width="1"><img src="<?=$blog_skin_url?>/img/side_title_01.gif" alt="" width="1" height="19"></td>
                    <td class="title">Link</td>
                    <td style="text-align:right; padding-right:4px;"><a href="javascript:sud('sud_link')"><img src="<?=$blog_skin_url?>/img/icon_<?=$sud_btn['sud_link']?>.gif" alt="" width="9" height="9" id=sud_link_button></a></td>
                    <td width="1"><img src="<?=$blog_skin_url?>/img/side_title_01.gif" alt="" width="1" height="19"></td>
                </tr>
            </table>
            <div id=sud_link>
            <div>
            <? 
            for($i=0; $i<count($link); $i++) { 
                if( $cn != $link[$i]['category_name'] ) {
                    //if( $i ) echo "</ul>";
                    $cn = $link[$i]['category_name'];
                    echo "</div><ul class=\"list_link\"><li><a href=\"javascript:link_click({$i})\">{$cn}</a></li></ul><div id=list_link_{$i} style=\"display:none;\">";
                }
            ?>
            <ul class="list_link_sub"><li><a href="http://<?=$link[$i]['site_url']?>" target="_blank"><?=$link[$i]['site_name']?></a></li></ul>
            <? } ?>
            </div>
            </div>
        </td>
        </tr>
        <tr>
            <td width=4 height=4><img src="<?=$blog_skin_url?>/img/line_btm_left.gif" alt="" width="4" height="4"></td>
            <td width=191 height=4 style="background:url(<?=$blog_skin_url?>/img/line_btm.gif) no-repeat right bottom;"></td>
        </tr>
    </table>

    <script language=javascript>
    function link_click(n) {
        var btn = document.getElementById("list_link_"+n);

        if (btn) {
            if (btn.style.display != 'block')
                btn.style.display = 'block';
            else
                btn.style.display = 'none';
        }
    }
    </script>

</div>
<?
    $sidebar['link'] = ob_get_contents();
    ob_clean();
}

/*-----------------------------------------------------------------------
    사용자 정의
-------------------------------------------------------------------------*/
for($i=1; $i<=5; $i++) {
    if( $use_sb['user_'.$i]) {
        ob_start();
?>
<div id="sidebar_use<?=$i?>" class="sidebar">

    <table border=0 cellpadding=0 cellspacing=0 width=100%>
        <tr>
            <td width=4 height=4><img src="<?=$blog_skin_url?>/img/line_top_left.gif" alt="" width="4" height="4"></td>
            <td width=191 height=4 style="background:url(<?=$blog_skin_url?>/img/line_top.gif) no-repeat right top;"></td>
        </tr>
        <tr>
        <td width=4 style="background:url(<?=$blog_skin_url?>/img/line_left.gif) repeat-y right top;"></td>
        <td width=191 bgcolor=#FFFFFF class="sidebar_content">


            <!--오리주둥이 추가 - 사이드바 타이틀-->
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr style="background:#D2D2D2;">
                    <td width="1"><img src="<?=$blog_skin_url?>/img/side_title_01.gif" alt="" width="1" height="19"></td>
                    <td class="title"><?=$current['sidebar_user'.$i.'_title']?></td>
                    <td style="text-align:right; padding-right:4px;"><a href="javascript:sud('sud_custom<?=$i?>')"><img src="<?=$blog_skin_url?>/img/icon_<?=$sud_btn['sud_custom'.$i]?>.gif" alt="" width="9" height="9" id="sud_custom<?=$i?>_button"></td>
                    <td width="1"><img src="<?=$blog_skin_url?>/img/side_title_01.gif" alt="" width="1" height="19"></td>
                </tr>
            </table>
            <div id=sud_custom<?=$i?> style="margin-top:5px;">
            <?=$current['sidebar_user'.$i.'_content']?>
            </div>
        </td>
        </tr>
        <tr>
            <td width=4 height=4><img src="<?=$blog_skin_url?>/img/line_btm_left.gif" alt="" width="4" height="4"></td>
            <td width=191 height=4 style="background:url(<?=$blog_skin_url?>/img/line_btm.gif) no-repeat right bottom;"></td>
        </tr>
    </table>

</div>
<? 
        $sidebar['user_'.$i] = ob_get_contents();
        ob_clean();
    }
}

/*-----------------------------------------------------------------------
    카운터
-------------------------------------------------------------------------*/
if( $use_sb['counter']) {
    ob_start();
?>
<div id="visit" class="sidebar">

    <table border=0 cellpadding=0 cellspacing=0 width=100%>
        <tr>
            <td width=6 height=6><img src="<?=$blog_skin_url?>/img/line_top_left_profile.gif" alt="" width="6" height="6"></td>
            <td width=189 height=6 style="background:url(<?=$blog_skin_url?>/img/line_top_profile.gif) no-repeat right top;"></td>
        </tr>
        <tr>
            <td width=6 style="background:url(<?=$blog_skin_url?>/img/line_left_profile.gif) repeat-y left top;"></td>
            <td width="189" bgcolor="#FFFFFF" style="background:url(<?=$blog_skin_url?>/img/line_bg_01_profile.gif) repeat-y right top; padding-right:6px;">
                
                <div>
                    <ul class="list_visit_today"><li><img src="<?=$blog_skin_url?>/img/visit_today.gif" alt="" align="absmiddle"><?=$current['visit_today']?></li></ul>
                    <ul class="list_visit_yesterday"><li><img src="<?=$blog_skin_url?>/img/visit_yesterday.gif" alt="" align="absmiddle"><?=$current['visit_yesterday']?></li></ul>
                    <ul class="list_visit_total"><li><img src="<?=$blog_skin_url?>/img/visit_total.gif" alt="" align="absmiddle"><?=$current['visit_total']?></li></ul>
                </div>

            </td>
        </tr>
        <tr>
            <td width=6 height=6><img src="<?=$blog_skin_url?>/img/line_btm_left_profile.gif" alt="" width="6" height="6"></td>
            <td width=189 height=6 style="background:url(<?=$blog_skin_url?>/img/line_btm_profile.gif) no-repeat right bottom;"></td>
        </tr>
    </table>

</div>
<?
    $sidebar['counter'] = ob_get_contents();
    ob_clean();
}

/*-----------------------------------------------------------------------
    RSS
-------------------------------------------------------------------------*/
if($use_sb['rss']) {
    ob_start();
?>
<div id="feed">
<a href="<?=$current['rss']?>"><img src="<?=$blog_skin_url?>/img/rss.gif" border="0"></a>
</div>
<?
    $sidebar['rss'] = ob_get_contents();
    ob_clean();
}
?>