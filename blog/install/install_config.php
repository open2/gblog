<?
include_once ("../../config.php");
include_once ("../gblog.config.php");

if (!file_exists("../../dbconfig.php")) {
    echo "<meta http-equiv='content-type' content='text/html; charset=$g4[charset]'>";
    echo <<<HEREDOC
    <script language="JavaScript">
    alert("설치하실 수 없습니다.");
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

if ($_POST["agree"] != "동의함") {
    echo "<meta http-equiv='content-type' content='text/html; charset=$g4[charset]'>";
    echo <<<HEREDOC
    <script language="JavaScript">
    alert("라이센스(License) 내용에 동의하셔야 설치를 계속하실 수 있습니다.");
    history.back();
    </script>
HEREDOC;
    exit;
}

header("location:install_db.php");
?>