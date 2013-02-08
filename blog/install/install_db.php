<?
set_time_limit(0);

include_once ("../../config.php");
include_once ("../gblog.config.php");

if (!file_exists("../../dbconfig.php")) {
    echo "<meta http-equiv='content-type' content='text/html; charset=$g4[charset]'>";
    echo <<<HEREDOC
    <script language="JavaScript">
    alert("그누보드4를 먼저 설치하신 후 GBlog 를 설치해 주세요.");
    location.href="../";
    </script>
HEREDOC;
    exit;
}

include_once("../../dbconfig.php");

$dblink = @mysql_connect($mysql_host, $mysql_user, $mysql_password);
if (!$dblink) {
    echo "<meta http-equiv='content-type' content='text/html; charset=$g4[charset]'>";
    echo "<script language='JavaScript'>alert('MySQL Host, User, Password 를 확인해 주십시오.');history.back();</script>"; 
    exit;
}

$select_db = @mysql_select_db($mysql_db, $dblink);
if (!$select_db) {
    echo "<meta http-equiv='content-type' content='text/html; charset=$g4[charset]'>";
    echo "<script language='JavaScript'>alert('MySQL DB 를 확인해 주십시오.');history.back();</script>"; 
    exit;
}

// $gb4[config_table]에 설정값이 존재하면 설치할 수 없다
$sql = " select count(*) as cnt from $gb4[config_table] ";
$result = mysql_query($sql);
$row = @mysql_fetch_assoc($result);
if ($row['cnt'] != 0)
{
    echo "<meta http-equiv='content-type' content='text/html; charset=$g4[charset]'>";
    echo <<<HEREDOC
    <script language="JavaScript">
    alert("$gb4[config_table]에 설정값이 존재하기 때문에 설치하실 수 없습니다.\\n\\n재설치시 해당 테이블 또는 해당 테이블의 설정값을 삭제 후 다시 시도해 주십시오. ");
    location.href="../";
    </script>
HEREDOC;
    exit;
}

$gmnow = gmdate("D, d M Y H:i:s") . " GMT";
header("Expires: 0"); // rfc2616 - Section 14.21
header("Last-Modified: " . $gmnow);
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: pre-check=0, post-check=0, max-age=0"); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0


?>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=<?=$g4[charset]?>">
<title>그누보드4 GBlog 설치 (2/2) - DB</title>
<style type="text/css">
<!--
.body {
	font-size: 12px;
}
.box {
	background-color: #FCFCFC;
    color:#18307B;
	font-size: 12px;
}
.nobox {
	background-color: #FCFCFC;
    border-style:none;
    font-size: 12px;
}
-->
</style>
</head>

<body background="img/all_bg.gif" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div align="center">
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <table width="587" border="0" cellspacing="0" cellpadding="0">
    <form name=frminstall2>
    <tr> 
                <td colspan="3"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="587" height="22">
                        <param name="movie" value="img/top.swf">
                        <param name="quality" value="high">
                        <embed src="img/top.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="587" height="22"></embed></object></td>
    </tr>
    <tr> 
      <td width="3"><img src="img/box_left.gif" width="3" height="340"></td>
      <td width="581" valign="top" bgcolor="#FCFCFC"><table width="581" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td><img src="img/box_title.gif" width="581" height="56"></td>
          </tr>
        </table>
        <br>
        <table width="541" border="0" align="center" cellpadding="0" cellspacing="0" class="body">
          <tr> 
            <td>설치를 시작합니다. <font color="#CC0000">설치중 작업을 중단하지 마십시오. </font></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td><div align="left">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input name="status_bar" type="text" class="box" size="76" readonly></div></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td><table width="350" border="0" align="center" cellpadding="5" cellspacing="0" class="body">
                <tr> 
                  <td width="50"> </td>
                  <td width="300"><input type=text name=job1 class=nobox size=80 readonly></td>
                </tr>
                <tr> 
                  <td width="50"> </td>
                  <td width="300"><input type=text name=job2 class=nobox size=80 readonly></td>
                </tr>
                <tr> 
                  <td width="50"> </td>
                  <td width="300"><input type=text name=job3 class=nobox size=80 readonly></td>
                </tr>
                <tr> 
                  <td width="50"> 
                    <div align="center"></div></td>
                  <td width="300"><input type=text name=job4 class=nobox size=80 readonly></td>
                </tr>
              </table></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td><input type=text name=job5 class=nobox size=90 readonly></td>
          </tr>
        </table>
        <table width="562" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td height=20><img src="img/box_line.gif" width="562" height="2"></td>
          </tr>
        </table>
        <table width="551" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr> 
            <td align="right"> 
              <input type="button" name="btn_next" disabled value="메인화면" onclick="location.href='../gblog.index.php';">
            </td>
          </tr>
        </table></td>
      <td width="3"><img src="img/box_right.gif" width="3" height="340"></td>
    </tr>
    <tr> 
      <td colspan="3"><img src="img/box_bottom.gif" width="587" height="3"></td>
    </tr>
    </form>
  </table>
</div>
<?
flush(); usleep(50000); 

// 테이블 생성 ------------------------------------
$file = implode("", file("./sql_gnuboard4_gblog.sql"));
eval("\$file = \"$file\";");

$f = explode(";", $file);
for ($i=0; $i<count($f); $i++) {
    if (trim($f[$i]) == "") continue;
    mysql_query($f[$i]) or die(mysql_error());
}
// 테이블 생성 ------------------------------------

echo "<script>document.frminstall2.job1.value='전체 테이블 생성중';</script>";
flush(); usleep(50000); 

for ($i=0; $i<45; $i++)
{
    echo "<script language='JavaScript'>document.frminstall2.status_bar.value += '■';</script>\n";
    flush();
    usleep(500); 
}

echo "<script>document.frminstall2.job1.value='전체 테이블 생성 완료';</script>";
flush(); usleep(50000); 

echo "<script>document.frminstall2.job2.value='DB설정 완료';</script>";
flush(); usleep(50000); 

//-------------------------------------------------------------------------------------------------
// config 기본 설정
$make_level = 2;
$make_point = 0;
$upload_blog_file_size = 20971520;
$upload_file_number = 5;
$upload_one_file_size = 1048576;
$profile_image_size = 66560;
$top_image_size = 1048576;
$background_image_size = 1048576;
$use_random_blog = 1;
$use_permalink = 'none';
$ampersand = '&';

//-------------------------------------------------------------------------------------------------
// config 테이블 설정
$sql = " insert into $gb4[config_table]
            set 
                make_level              = '$make_level',
                make_point              = '$make_point',
                upload_blog_file_size   = '$upload_blog_file_size',
                upload_file_number      = '$upload_file_number',
                upload_one_file_size    = '$upload_one_file_size',
                profile_image_size      = '$profile_image_size',
                top_image_size          = '$top_image_size',
                background_image_size   = '$background_image_size',
                use_random_blog         = '$use_random_blog',
                use_permalink           = '$use_permalink',
                ampersand               = '$ampersand'
                ";
mysql_query($sql) or die(mysql_error() . "<p>" . $sql);

$skin_path = '../skin/blog';

// 스킨 목록을 디렉토리에서 읽어옴. !== 은 4.0.0-RC2까지 존재하지 않았던 점에 주의.
$skins = array();
if ($handle = opendir($skin_path)) {
   while (false !== ($dir = readdir($handle))) {
       if(trim($dir)!='.'&&trim($dir)!='..') {
           array_push($skins,trim($dir));
       }
   }
   closedir($handle);
}

// 새로운 스킨 DB 에 저장
while($skin = array_pop($skins)) {
    $q = mysql_query("select count(*) as cnt from $gb4[skin_table] where skin='$skin'");
    $r = mysql_fetch_array($q);
    if(!$r['cnt'])
        mysql_query("insert into $gb4[skin_table] set skin='$skin', used=1, regdate='{$g4['time_ymdhis']}'");
}

echo "<script>document.frminstall2.job3.value='GBlog 파일 생성 완료';</script>";

flush(); usleep(50000); 

// 디렉토리 생성
$dir_arr = array ("../../data/blog",
                  "../../data/blog/top_image",
                  "../../data/blog/background_image",
                  "../../data/blog/background_repeat",
                  "../../data/blog/profile_image",
                  "../../data/blog/stylesheet",
                  "../../data/blog/file");
for ($i=0; $i<count($dir_arr); $i++) 
{
    @mkdir($dir_arr[$i], 0707);
    @chmod($dir_arr[$i], 0707);

    // 디렉토리에 있는 파일의 목록을 보이지 않게 한다.
    $file = $dir_arr[$i] . "/index.php";
    $f = @fopen($file, "w");
    @fwrite($f, "");
    @fclose($f);
    @chmod($file, 0606);
}

@rename("../install", "../install.bak");
//-------------------------------------------------------------------------------------------------

echo "<script language='JavaScript'>document.frminstall2.status_bar.value += '■';</script>\n";
flush();
sleep(1);

echo "<script>document.frminstall2.job4.value='필요한 Table, File, 디렉토리 생성을 모두 완료 하였습니다.';</script>";
echo "<script>document.frminstall2.job5.value='* 메인화면에서 운영자 로그인을 한 후 운영자 화면으로 이동하여 환경설정을 변경해 주십시오.';</script>";
flush(); usleep(50000); 
?>

<script>document.frminstall2.btn_next.disabled = false;</script>
<script>document.frminstall2.btn_next.focus();</script>

</body>
</html>