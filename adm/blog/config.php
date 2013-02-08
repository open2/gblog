<?
include_once("./_common.php");

auth_check($auth[$sub_menu], "r");

$gb4[title] = "블로그 기본설정";

include_once("$g4[admin_path]/admin.head.php");

if( !trim(strlen($gb4['make_level']))             ) $gb4['make_level'] = 2;
if( !trim(strlen($gb4['make_point']))             ) $gb4['make_point'] = 0;
if( !trim(strlen($gb4['upload_blog_file_size']))  ) $gb4['upload_blog_file_size'] = 1024*1024*20; // 20메가
if( (int)$gb4['gb_page_rows'] <= 0                ) $gb4['gb_page_rows'] = 24;
if( !trim(strlen($gb4['upload_file_number']))     ) $gb4['upload_file_number'] = 5;
if( !trim(strlen($gb4['upload_one_file_size']))   ) $gb4['upload_one_file_size'] = 1024*1024; // 1메가
if( !trim(strlen($gb4['use_random_blog']))        ) $gb4['use_random_blog'] = 1;
if( !trim(strlen($gb4['use_permalink']))          ) $gb4['use_permalink'] = 'none';
if( !trim(strlen($gb4['profile_image_size']))     ) $gb4['profile_image_size'] = 66560;
if( !trim(strlen($gb4['top_image_size']))         ) $gb4['top_image_size'] = 1048576;
if( !trim(strlen($gb4['background_image_size']))  ) $gb4['background_image_size'] = 1048576;
?>
<table width=100% cellpadding=0 cellspacing=0 border=0>
<form name=form method=post action='config_update.php'>
<tr class='ht'>
    <td align=left><?=subtitle("블로그 기본설정")?></td>
</tr>
</table>

<table border=0 cellpadding=0 cellspacing=0>
    <tr class='ht'>
        <td width=300 height=40>
            블로그 개설 가능한 회원 레벨을 설정해주세요.
        </td>
        <td>
            <select name="make_level" required>
            <?for($i=2; $i<=10; $i++){
                if($gb4['make_level']==$i) $selected = 'selected'; else $selected = '';
                echo "<option value=$i $selected>$i 레벨</option>\n";
            }?>
            </select>
            이상
        </td>
    </tr>
    <tr><td height=1 colspan=2 bgcolor=#efefef></td></tr>
    <tr class='ht'>
        <td width=300 height=40>
            블로그 생성시 소진 포인트
        </td>
        <td>
            <input type=text class=ed name=make_point value="<?=$gb4['make_point']?>"  numeric required itemname="블로그 생성시 소진 포인트" size=10>
            포인트. <span style="color:gray;font-size:10px">(0 이면 소진 포인트 없음.)</span>
        </td>
    </tr>
    <tr><td height=1 colspan=2 bgcolor=#efefef></td></tr>
    <tr class='ht'>
        <td width=300 height=40>
            블로그 한개당 총 제한 용량.
        </td>
        <td>
            <input type=text class=ed name=upload_blog_file_size value="<?=$gb4['upload_blog_file_size']?>"  numeric required itemname="블로그 한개당 총제한 용량" size=15>
            byte.<span style="color:gray;font-size:10px">(0 이면 제한 없음)</span>
        </td>
    </tr>
    <tr><td height=1 colspan=2 bgcolor=#efefef></td></tr>
    <tr class='ht'>
        <td width=300 height=40>
            블로그관리자의 페이지당 목록수
        </td>
        <td>
            <input type=text class=ed name=gb_page_rows value="<?=$gb4['gb_page_rows']?>" numeric required itemname="블로그관리자의 페이지당 목록수" size=15>
        </td>
    </tr>
    <tr><td height=1 colspan=2 bgcolor=#efefef></td></tr>
    <tr class='ht'>
        <td width=300 height=40>
            첨부파일을 몇개까지 등록 가능합니까?
        </td>
        <td>
            <input type=text class=ed name=upload_file_number value="<?=$gb4['upload_file_number']?>"  numeric required itemname="등록가능한 첨부파일 갯수" size=10>
            개. <span style="color:gray;font-size:10px">(0 이면 첨부파일 등록 불가, 999면 제한 없음.)</span>
        </td>
    </tr>
    <tr><td height=1 colspan=2 bgcolor=#efefef></td></tr>
    <tr class='ht'>
        <td width=300 height=40>
            첨부파일 한개 당 제한 용량을 설정해주세요.
        </td>
        <td>
            <input type=text class=ed name=upload_one_file_size value="<?=$gb4['upload_one_file_size']?>"  numeric required itemname="첨부파일 한개 당 제한 용량" size=15>
            byte.<span style="color:gray;font-size:10px">(0 이면 제한 없음)</span>
        </td>
    </tr>
    <tr><td height=1 colspan=2 bgcolor=#efefef></td></tr>
    <tr class='ht'>
        <td width=300 height=40>
            프로필 이미지 제한 용량을 설정해주세요.
        </td>
        <td>
            <input type=text class=ed name=profile_image_size value="<?=$gb4['profile_image_size']?>"  numeric required itemname="프로필 이미지 제한 용량" size=15>
            byte. <span style="color:gray;font-size:10px">(0 이면 제한 없음)</span>
        </td>
    </tr>
    <tr><td height=1 colspan=2 bgcolor=#efefef></td></tr>
    <tr class='ht'>
        <td width=300 height=40>
            상단 이미지 제한 용량을 설정해주세요.
        </td>
        <td>
            <input type=text class=ed name=top_image_size value="<?=$gb4['top_image_size']?>"  numeric required itemname="상단 이미지 제한 용량" size=15>
            byte. <span style="color:gray;font-size:10px">(0 이면 제한 없음)</span>
        </td>
    </tr>
    <tr><td height=1 colspan=2 bgcolor=#efefef></td></tr>
    <tr class='ht'>
        <td width=300 height=40>
            배경 이미지 제한 용량을 설정해주세요.
        </td>
        <td>
            <input type=text class=ed name=background_image_size value="<?=$gb4['background_image_size']?>"  numeric required itemname="배경 이미지 제한 용량" size=15>
            byte. <span style="color:gray;font-size:10px">(0 이면 제한 없음)</span>
        </td>
    </tr>
    <tr><td height=1 colspan=2 bgcolor=#efefef></td></tr>
    <tr class='ht'>
        <td width=300 height=60>
            랜덤 블로그 기능을 사용하시겠습니까?
        </td>
        <td>
            <input type=radio name=use_random_blog value=1<?if($gb4['use_random_blog']==1) echo ' checked'?> itemname='랜덤 블로그'> 사용합니다.<br/>
            <input type=radio name=use_random_blog value=0<?if($gb4['use_random_blog']==0) echo ' checked'?> itemname='랜덤 블로그'> 사용하지 않습니다.
        </td>
    </tr>
    <tr><td height=1 colspan=2 bgcolor=#efefef></td></tr>
    <tr class='ht'>
        <td width=300 height=100>
            퍼머링크(Permalink) 를 사용하시겠습니까?
        </td>
        <td style="line-height:25px;">
            <input type=radio name=use_permalink value='none'<?if($gb4['use_permalink']=='none') echo ' checked'?> itemname='퍼머링크'>
            사용하지 않습니다. 
            <br>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <span style="color:gray;font-size:10px">(예: <?=$g4['url']?>/<?=$gb4['blog']?>/?mb_id=userid&id=305)</span><br/>
            <!--
            <input type=radio name=use_permalink value='numeric'<?if($gb4['use_permalink']=='numeric') echo ' checked'?> itemname='퍼머링크'> 
            사용합니다.<br>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <span style="color:gray;font-size:10px">(예: <?=$g4['url']?>/<?=$gb4['blog']?>/userid/305)</span>
            <br/>
            <input type=radio name=use_permalink value='none'<?if($gb4['use_permalink']=='none') echo ' checked'?> itemname='퍼머링크'>
            사용하지 않습니다. 
            <span style="color:gray;font-size:10px">(예: http://<?=$HTTP_HOST?>/blog/?mb_id=userid&id=305)</span>
            <br/>

            <input type=radio name=use_permalink value='numeric'<?if($gb4['use_permalink']=='numeric') echo ' checked'?> itemname='퍼머링크'> 
            숫자로 사용합니다.
            <span style="color:gray;font-size:10px">(예: http://<?=$HTTP_HOST?>/blog/userid/305/)</span>
            <br/>

            <input type=radio name=use_permalink value='character'<?if($gb4['use_permalink']=='character') echo ' checked'?> itemname='퍼머링크'>
            문자로 사용합니다. 
            <span style="color:gray;font-size:10px">(예: http://<?=$HTTP_HOST?>/blog/userid/hello-world/)</span>
            <br/>

            <input type=radio name=use_permalink value='date'<?if($gb4['use_permalink']=='date') echo ' date'?> itemname='퍼머링크'>
            날짜와 문자로 사용합니다. 
            <span style="color:gray;font-size:10px">(예: http://<?=$HTTP_HOST?>/blog/userid/2006/12/25/hello-world/)</span>
            <br/>
            -->
        </td>
    </tr>
    <tr><td height=1 colspan=2 bgcolor=#efefef></td></tr>
</table>

<p align=center>
    <input type=submit class=btn1 accesskey='s' value='  확  인  '>
</p>
</form>

<?
include_once("../admin.tail.php");
?>