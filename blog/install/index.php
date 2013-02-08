<?
include_once ("../../config.php");
include_once ("../gblog.config.php");

// �۹̼��� ������ ���� �������� ��´�. drwxrwxrwx
function get_perms($mode)
{
    /* Determine Type */
    if( $mode & 0x1000 )
        $perms["type"] = 'p'; /* FIFO pipe */
    else if( $mode & 0x2000 )
        $perms["type"] = 'c'; /* Character special */
    else if( $mode & 0x4000 )
        $perms["type"] = 'd'; /* Directory */
    else if( $mode & 0x6000 )
        $perms["type"] = 'b'; /* Block special */
    else if( $mode & 0x8000 )
        $perms["type"] = '-'; /* Regular */
    else if( $mode & 0xA000 )
        $perms["type"] = 'l'; /* Symbolic Link */
    else if( $mode & 0xC000 )
        $perms["type"] = 's'; /* Socket */
    else
        $perms["type"] = 'u'; /* UNKNOWN */

    /* Determine permissions */
    $perms["owner_read"]    = ($mode & 00400) ? 'r' : '-';
    $perms["owner_write"]   = ($mode & 00200) ? 'w' : '-';
    $perms["owner_execute"] = ($mode & 00100) ? 'x' : '-';
    $perms["group_read"]    = ($mode & 00040) ? 'r' : '-';
    $perms["group_write"]   = ($mode & 00020) ? 'w' : '-';
    $perms["group_execute"] = ($mode & 00010) ? 'x' : '-';
    $perms["world_read"]    = ($mode & 00004) ? 'r' : '-';
    $perms["world_write"]   = ($mode & 00002) ? 'w' : '-';
    $perms["world_execute"] = ($mode & 00001) ? 'x' : '-';

    /* Adjust for SUID, SGID and sticky bit */
    if( $mode & 0x800 )
        $perms["owner_execute"] = ($perms["owner_execute"]=='x') ? 's' : 'S';
    if( $mode & 0x400 )
        $perms["group_execute"] = ($perms["group_execute"]=='x') ? 's' : 'S';
    if( $mode & 0x200 )
        $perms["world_execute"] = ($perms["world_execute"]=='x') ? 't' : 'T';

    return $perms;
}

// ��Ʈ ���丮�� ���� ���� �������� �˻�.
if (!is_writeable("../..")) 
{
    echo "<meta http-equiv='content-type' content='text/html; charset=$g4[charset]'>";
    echo "<script language='JavaScript'>alert('��Ʈ ���丮�� �۹̼��� 707�� �����Ͽ� �ֽʽÿ�.\\n\\ncommon.php ������ �ִ°��� ��Ʈ ���丮 �Դϴ�.\\n\\n$> chmod 707 . \\n\\n�� ���� ��ġ�Ͽ� �ֽʽÿ�.');</script>"; 
    exit;
}

// data ���丮�� ���� ���� �������� �˻�.
if (!is_writeable("../../data")) 
{
    echo "<meta http-equiv='content-type' content='text/html; charset=$g4[charset]'>";
    echo "<script language='JavaScript'>alert('data ���丮�� �۹̼��� 707�� �����Ͽ� �ֽʽÿ�.\\n\\n$> chmod 707 data \\n\\n�� ���� ��ġ�Ͽ� �ֽʽÿ�.');</script>"; 
    exit;
}

// blog ���丮�� ���� ���� �������� �˻�.
if (!is_writeable("../../blog")) 
{
    echo "<meta http-equiv='content-type' content='text/html; charset=$g4[charset]'>";
    echo "<script language='JavaScript'>alert('blog ���丮�� �۹̼��� 707�� �����Ͽ� �ֽʽÿ�.\\n\\n$> chmod 707 blog \\n\\n�� ���� ��ġ�Ͽ� �ֽʽÿ�.');</script>"; 
    exit;
}
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=<?=$g4[charset]?>">
<title>�״�����4 GBlog ��ġ (1/2) - ���̼���(License)</title>
<style type="text/css">
<!--
.body {
	font-size: 12px;
}
.box {
	background-color: #D6D3CE;
	font-size: 12px;
}
-->
</style>
</head>

<body background="img/all_bg.gif" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <table width="587" border="0" cellspacing="0" cellpadding="0" align=center>
    <tr> 
        <td colspan="3"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="587" height="22">
                <param name="movie" value="img/top.swf">
                <param name="quality" value="high">
                <embed src="img/top.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="587" height="22"></embed></object></td>
    </tr>
    <tr> 
      <td width="3"><img src="img/box_left.gif" width="3" height="340"></td>
      <td width="581" valign="top" bgcolor="#FCFCFC">
	  <table width="581" border="0" cellspacing="0" cellpadding="0">
          <tr> 
                    <td><img src="img/box_title.gif" width="581" height="56"></td>
          </tr>
      </table>
      <table width="541" border="0" align="center" cellpadding="0" cellspacing="0" class="body">
          <tr> 
            <td height="10"></td>
          </tr>
          <tr> 
            <td>���̼���(License) ������ �ݵ�� Ȯ���Ͻʽÿ�.</td>
          </tr>
          <tr> 
            <td height="10"></td>
          </tr>
          <tr> 
            <td align="center">
			
<textarea name="textarea" style='width:99%' rows="9" class="box" readonly>
<?=implode("", file("../LICENSE_GBLOG"));?>
</textarea> 

            </td>
          </tr>
          <tr>
            <td height=10></td>
          </tr>
          <tr> 
            <td>��ġ�� ���Ͻø� �� ���뿡 �����ϼž� �մϴ�.<br>
              ���Ǹ� ���Ͻø� &lt;��, �����մϴ�&gt; ��ư�� Ŭ���� �ּ���.</td>
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
                <form name=frm method=post action="javascript:frm_submit(document.frm);">
                <input type="hidden" name="agree" value="������">
                <input type="submit" name="btn_submit" value="��, �����մϴ� ">
                </form>
            </td>
          </tr>
        </table>
		</td>
      <td width="3"><img src="img/box_right.gif" width="3" height="340"></td>
    </tr>
    <tr> 
      <td colspan="3"><img src="img/box_bottom.gif" width="587" height="3"></td>
    </tr>
  </table>

<script language="JavaScript">
function frm_submit(f)
{
    f.action = "./install_config.php";
    f.submit();
}

document.frm.btn_submit.focus();
</script>

</body>
</html>