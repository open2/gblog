<?
include_once("./_common.php");

$g4['title'] = "블로그 기본정보설정";

// 블로그 기본정보수정일 경우
if( $w == 'u' ) 
{
    if( $mb_id != $member['mb_id'] )
        alert('자신의 블로그만 정보를 수정할 수 있습니다.');

    // 블로그 기본정보 로드
    $r = sql_fetch("select * from {$gb4['blog_table']} where mb_id='{$member['mb_id']}'");

    if( empty($r) ) 
        alert('존재하지 않는 블로그 입니다.', $g4['path']);

    extract($r);

    // 프로필 이미지
    $profile_image = "{$g4[path]}/data/blog/profile_image/{$current['mb_id']}";

}

// 블로그를 새로 만들 경우
else 
{
    // 블로그를 가지고 있다면 자신의 블로그로 이동한다.
    if( have_a_blog($member['mb_id']) ) 
        alert('이미 블로그를 가지고 계십니다.', $blog_url);

    // 회원 레벨 검사
    if( $member['mb_level'] < $gb4['make_level'] )
        alert("블로그를 생성은 {$gb4['make_level']} 레벨부터 가능합니다.", $g4['path']);

    // 회원 포인트 검사
    if( $member['mb_point'] < $gb4['make_point'] )
        alert('블로그를 생성하려면 '.number_format($gb4['make_point']).' 포인트가 필요합니다.', $g4['path']);

    // 초기값 설정
    $blog_name = $member['mb_nick'];
    $blog_about = "{$blog_name}님의 블로그";
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
    
 	  // 블로그 설치후 이동할 url
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
.w_message  { font-family:돋움; font-size:9pt; color:#4B4B4B; }
.w_norobot  { font-family:돋움; font-size:9pt; color:#BB4681; }
.w_hand     { cursor:pointer; }
-->
</style>

<? if( $w == 'u' ) { ?>
<div class="adm_info">
    <b>기본환경 설정</b> : <?=$member[mb_nick]?>님의 불로그 기본정보를 설정 합니다.
</div>
<? } else { ?>
<div class="adm_info">
    <b>블로그 만들기</b> : <?=$member[mb_nick]?>님의 불로그를 생성 합니다.
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
            <td width="160" class=m_title>블로그 제목</td>
            <td class=m_padding>
                <input class=m_text maxlength=150 size=50 name="blog_name" itemname="블로그 제목" required value="<?=$blog_name?>">
            </td>
        </tr>
        <tr bgcolor="#ffffff">
            <td width="160" class=m_title>블로그 설명</td>
            <td class=m_padding>
                <textarea class=m_text style="width:90%;height:50px;" name="blog_about" itemname="블로그 설명"><?=$blog_about?></textarea>
            </td>
        </tr>
        <tr bgcolor="#ffffff">
            <td width="160" class=m_title>프로필 이미지</td>
            <td class=m_padding>
                <input class=m_text type=file name='profile_image' size=30>
                <? if ($w == "u" && file_exists($profile_image)) { ?>
                <input type=checkbox name='profile_image_del' value='1'>  삭제
                <br><a href="<?=$profile_image?>" target=_new><img src="<?=$profile_image?>" width=100px></a>
                <? } ?>

                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td class=m_padding3>
                        <? if( $gb4['profile_image_size'] ) {?>
                        (gif,jpg,png 만 가능 / 용량:<?=number_format($gb4['profile_image_size'])?>바이트 이하만 등록됩니다.)
                        <? } ?>
                    </td>
                </tr>
                </table>
            </td>
        </tr>
        <tr bgcolor="#ffffff">
            <td width="160" class=m_title>블로그쓰기 권한</td>
            <td class=m_padding>
                <?=get_member_level_select('use_post', 0, $member[mb_level], $use_post) ?> (0: 나혼자, 1: 손님, 2: 회원)
            </td>
        </tr>
        <tr bgcolor="#ffffff">
            <td width="160" class=m_title>댓글쓰기 권한</td>
            <td class=m_padding>
                <?=get_member_level_select('use_comment', 0, $member[mb_level], $use_comment) ?> (0: 나혼자, 1: 손님, 2: 회원)
            </td>
        </tr>
        <tr bgcolor="#ffffff">
            <td width="160" class=m_title>방명록쓰기 권한</td>
            <td class=m_padding>
                <?=get_member_level_select('use_guestbook', 0, $member[mb_level], $use_guestbook) ?> (0: 나혼자, 1: 손님, 2: 회원)
            </td>
        </tr>
        <tr bgcolor="#ffffff">
            <td width="160" class=m_title>엮인글 사용여부</td>
            <td class=m_padding>
                <input type=checkbox name=use_trackback value="1"<?if($use_trackback) echo ' checked'?>> 사용합니다.
            </td>
        </tr>
        <tr bgcolor="#ffffff">
            <td width="160" class=m_title>태그 사용여부</td>
            <td class=m_padding>
                <input type=checkbox name=use_tag value="1"<?if($use_tag) echo ' checked'?>> 사용합니다. (개별 태그를 삭제하려면 <a href="<?=$gb4[bbs_path]?>/adm_tag_list.php?mb_id=<?=$member['mb_id']?>">태그관리</a>로)
            </td>
        </tr>
        <tr bgcolor="#ffffff">
            <td width="160" class=m_title>CCL 사용여부</td>
            <td class=m_padding>
                <input type=checkbox name=use_ccl value="1"<?if($use_ccl) echo ' checked'?>> 사용합니다.
            </td>
        </tr>
        <tr bgcolor="#ffffff">
            <td width="160" class=m_title>자동출처 사용여부</td>
            <td class=m_padding>
                <input type=checkbox name=use_autosource value="1"<?if($use_autosource) echo ' checked'?>> 사용합니다.
            </td>
        </tr>
        <tr bgcolor="#ffffff">
            <td width="160" class=m_title>랜덤블로그 사용여부</td>
            <td class=m_padding>
                <input type=checkbox name=use_random value="1"<?if($use_random) echo ' checked'?>> 사용합니다.
            </td>
        </tr>
        <tr bgcolor="#ffffff">
            <td width="160" class=m_title>RSS 공개설정</td>
            <td class=m_padding>
                <table border=0 cellpadding=0 cellspacing=0 width=100% height=23>
                <tr>
                    <td width=90>
                        <select name=rss_open onChange="is_rss_open();">
                            <option value="1"<?if($rss_open==1) echo ' selected'?>> 공개 </option>
                            <option value="0"<?if($rss_open==0) echo ' selected'?>> 비공개 </option>
                            <option value="2"<?if($rss_open==2) echo ' selected'?>> 일부공개 </option>
                        </select>
                    </td>
                    <td>
                        <div id=rss_count_layer> 
                            <table border=0 cellpadding=0 cellspacing=0>
                            <tr>
                                <td>최신</td>
                                <td><input type=text size=5 name=rss_count value="<?=$rss_count?>" numeric required itemname="최신 RSS 공개 갯수"></td>
                                <td>
                                개 공개
                                <?=help("공개 : 블로그에 작성한 본문 전체글을 RSS로 공개합니다.<br><br>일부공개 : 본문의 앞 255자 까지만 RSS로 공개합니다.<br><br>비공개 : RSS를 공개하지 않습니다. ")?>
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
            <td width="160" class=m_title>블로그 총 사용량</td>
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