<?
include_once ("../../config.php");
include_once ("../gblog.config.php");

if (!file_exists("../../dbconfig.php")) {
    echo "<meta http-equiv='content-type' content='text/html; charset=$g4[charset]'>";
    echo <<<HEREDOC
    <script language="JavaScript">
    alert("��ġ�Ͻ� �� �����ϴ�.");
    location.href="../";
    </script>
HEREDOC;
    exit;
}

include_once("../../dbconfig.php");

$dblink = @mysql_connect($mysql_host, $mysql_user, $mysql_password);
if (!$dblink) {
    echo "<meta http-equiv='content-type' content='text/html; charset=$g4[charset]'>";
    echo "<script language='JavaScript'>alert('MySQL Host, User, Password �� Ȯ���� �ֽʽÿ�.');history.back();</script>"; 
    exit;
}

$select_db = @mysql_select_db($mysql_db, $dblink);
if (!$select_db) {
    echo "<meta http-equiv='content-type' content='text/html; charset=$g4[charset]'>";
    echo "<script language='JavaScript'>alert('MySQL DB �� Ȯ���� �ֽʽÿ�.');history.back();</script>"; 
    exit;
}

// $gb4[config_table]�� �������� �����ϸ� ��ġ�� �� ����
$sql = " select count(*) as cnt from $gb4[config_table] ";
$result = mysql_query($sql);
$row = @mysql_fetch_assoc($result);
if ($row['cnt'] != 0)
{
    echo "<meta http-equiv='content-type' content='text/html; charset=$g4[charset]'>";
    echo <<<HEREDOC
    <script language="JavaScript">
    alert("$gb4[config_table]�� �������� �����ϱ� ������ ��ġ�Ͻ� �� �����ϴ�.\\n\\n�缳ġ�� �ش� ���̺� �Ǵ� �ش� ���̺��� �������� ���� �� �ٽ� �õ��� �ֽʽÿ�. ");
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

if ($_POST["agree"] != "������") {
    echo "<meta http-equiv='content-type' content='text/html; charset=$g4[charset]'>";
    echo <<<HEREDOC
    <script language="JavaScript">
    alert("���̼���(License) ���뿡 �����ϼž� ��ġ�� ����Ͻ� �� �ֽ��ϴ�.");
    history.back();
    </script>
HEREDOC;
    exit;
}

header("location:install_db.php");
?>