<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// 3단인 경우
if ($current['sidebar_left'] && $current['sidebar_right']) 
{
    $gblog_width = 980;
    $main_width = 580;
} 
else // 2단인 경우
{
    if ($current[blog_width]) {
        $gblog_width = 980;
        $main_width = 780;
    } else {
        $gblog_width = 780;
        $main_width = 580;
    }
}

if( !file_exists($current['top_image_path']) ) 
    $current['top_image_url'] = "{$blog_skin_url}/img/top_img.jpg";

echo $current['blog_head'];

?>
<link rel="stylesheet" href="<?=$blog_skin_url?>/style.css" type="text/css">

<script language="javascript" src="<?=$blog_skin_url?>/png.js"></script>

<style>
#head {  background:url(<?=$current['top_image_url']?>?<?=time()?>) repeat-x; }
#gblog { width:<?=$gblog_width?>px; }

<? if( file_exists($current['background_repeat_path']) ) {?>
body { background:url(<?=$current['background_repeat_url']?>?<?=time()?>) repeat; }
<?}?>

<? if( file_exists($current['background_image_path']) ) {?>
#body2 { background:url(<?=$current['background_image_url']?>?<?=time()?>) repeat-x; }
<?}?>

<? if( $current['sidebar_left'] ) {?>
#sidebar_left { width:195px; float:left; margin-right:5px; }
<? }?>

<? if( $current['sidebar_right'] ) {?>
#sidebar_right { width:195px; float:right; }
<? }?>

.list_category li { padding:0 0 0 19px; background:url(<?=$blog_skin_url?>/img/icon_category.gif) 2px 3px no-repeat; }
.list_post li { padding:0 0 0 19px; background:url(<?=$blog_skin_url?>/img/icon_new_01.gif) 2px 3px no-repeat; }
.list_comment li { padding:0 0 0 19px; background:url(<?=$blog_skin_url?>/img/icon_comment.gif) 2px 1px no-repeat; }
.list_trackback li { padding:0 0 0 19px; background:url(<?=$blog_skin_url?>/img/icon_trackback.gif) 2px 1px no-repeat; }
.list_monthly li { padding:0 0 0 19px; background:url(<?=$blog_skin_url?>/img/icon_monthly.gif) 2px 2px no-repeat; }
.list_link li { padding:0 0 0 19px; background:url(<?=$blog_skin_url?>/img/icon_link.gif) 2px 4px no-repeat; }
.list_link_sub li { padding:0 0 0 19px; background:url(<?=$blog_skin_url?>/img/icon_link_sub.gif) 14px 5px no-repeat; }
.list_visit_today li { padding:0 0 0 0; background:url(<?=$blog_skin_url?>/img/visit_bg_today.gif) no-repeat left center; height:18px; }
.list_visit_yesterday li { padding:0 0 0 0; background:url(<?=$blog_skin_url?>/img/visit_bg_yesterday.gif) no-repeat left center; height:18px; }
.list_visit_total li { padding:0 0 0 0; background:url(<?=$blog_skin_url?>/img/visit_bg_total.gif) no-repeat left center; height:18px; }

.sidebar_content { background:url(<?=$blog_skin_url?>/img/line_bg_01.gif) repeat-y right top; padding-right:4px; word-break:break-all; }

div { overflow:hidden }

#top_menu a  { font-size:12px; color:<?=$current['top_menu_color']?>; text-decoration:none; }

#main { width:<?=$main_width?>px; float:left; }

</style>

<?if( file_exists($current['stylesheet_path']) ) {?>
<link rel="stylesheet" href="<?=$current['stylesheet_url']?>" type="text/css">
<?}?>

<!-- 정렬을 위한 div -->
<div id="body2" align="<?=$current['blog_align']?>">

<div id="gblog"><!--외곽 폭 제한 및 footer 의 both 선언을 위한 레이아웃 div-->


    <!--상단 로고 및 블로그이동-->
    <div id="top">

        <table border=0 cellpadding=0 cellspacing=0 width=100%>
        <tr>
            <td id="top_logo">
                <a href="<?=$gb4[url]?>/"><img src="<?=$blog_skin_url?>/img/gblog_logo.png" border=0 class="png24"></a>
            </td>
            <td id="top_menu">

                <a href="<?=$gb4[root_url]?>">홈</a> 
                <font color="<?=$current['top_menu_color']?>">&nbsp;|&nbsp;</font>
                <a href="<?=$gb4[url]?>/gblog.index.php">블로그 메인</a> 

                <? if( !empty($member['blog_url']) ) { ?>
                <font color="<?=$current['top_menu_color']?>">&nbsp;|&nbsp;</font>
                <a href="<?=$member['blog_url']?>">내 블로그</a> 
                <? } ?>

                <? if( $gb4['use_random_blog'] && $current['use_random'] ) { ?>
                <font color="<?=$current['top_menu_color']?>">&nbsp;|&nbsp;</font>
                <a href="#" onClick="go_random_blog();">랜덤 블로그</a> 
                <? } ?>

                <script language="javascript">
                function go_random_blog()
                {
                    window.location.href = '<?=get_random_blog_url()?>';
                }
                </script>
        
                <font color="<?=$current['top_menu_color']?>">&nbsp;|&nbsp;</font>
                <? if( empty($member['mb_id']) ) { ?>
                <a href="<?=$gb4[root]?>/<?=$gb4[bbs_path]?>/login.php?url=<?=$current['blog_url']?>">로그인</a> 
                <? } else {?>
                <a href="<?=$gb4[root]?>/<?=$g4[bbs]?>/logout.php?url=<?=$current['blog_url']?>">로그아웃</a> 
                <? } ?>

            </td>
        </tr>
        </table>

    </div>

    <!--헤더 이미지 및 블로그 제목출력-->
    <div id="head" class="head">

        <table border=0 cellpadding=0 cellspacing=0 align=center>
        <tr>
            <td align=right height=50>
                <a href="<?=$current['blog_url']?>" class="blog_name"><?=$current['blog_name']?></a>
            </td>
        </tr>
        </table>

    </div>
    <!--헤더 이미지 및 블로그 제목출력 끝//-->

    <!--블로그 유저명 및 태그구름 버튼출력-->
    <div id="head_menu">

        <table border=0 cellpadding=0 cellspacing=0 height=18 align=right>
        <tr>
            <? if ($current['use_tag']) { ?>
            <td class=button width=60 align=center valign=middle background="<?=$blog_skin_url?>/img/btn_head_background.gif">
                <a href="<?=get_tag_cloud_url()?>">태그구름</a>
            </td>
            <? } ?>
            <td width=5></td>
            <td class=button width=60 align=center valign=middle background="<?=$blog_skin_url?>/img/btn_head_background.gif">
                <a href="<?=get_guestbook_url()?>">방명록</a>
            </td>
            <td width=5></td>
        </tr>
        </table>

    </div>

    <?
    // sidebar skin을 읽어 들입니다.
    include_once("{$blog_skin_path}/sidebar.skin.php");
    ?>
    
    <div id="sidebar_top">
        <?for($i=0, $max=count($sidebar_top); $i<$max; $i++) echo $sidebar[$sidebar_top[$i]]?>
    </div>

    <div id="sidebar_right">
        <?for($i=0, $max=count($sidebar_right); $i<$max; $i++) echo $sidebar[$sidebar_right[$i]]?>
    </div>

    <div id="sidebar_left">
        <?for($i=0, $max=count($sidebar_left); $i<$max; $i++) echo $sidebar[$sidebar_left[$i]]?>
    </div>

    <div id="main">
