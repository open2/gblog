<?
include_once("./_common.php");

$g4[title] = "��������Ȳ";

include_once("{$g4[path]}/head.sub.php");
include_once("./admin.head.php");
include_once("./visit.sub.php");


$colspan = 5;
?>
<script language=javascript>
function whois(ip) {
    whois_win = window.open('whois.php?mb_id=<?=$mb_id?>&query='+ip,'whois_win','width=600,height=500,scrollbars=yes');
    whois_win.focus();
}
</script>

<table width=100% cellpadding=0 cellspacing=1 border=0>
<colgroup width=100>
<colgroup width=350>
<colgroup width=100>
<colgroup width=100>
<colgroup width=''>
<tr><td colspan='<?=$colspan?>' class='line1'></td></tr>
<tr class='bgcol1 bold col1 ht center'>
    <td><? if ($is_admin == 'super') { ?>IP<? } ?></td>
    <td>���� ���</td>
    <td>������</td>
    <td>OS</td>
    <td>�Ͻ�</td>
</tr>
<tr><td colspan='<?=$colspan?>' class='line2'></td></tr>
<?
//unset($br); // ������
//unset($os); // OS

$sql_common = " from {$gb4['visit_table']} ";
$sql_search = " where vi_date between '$fr_date' and '$to_date' and vi_blog_id='{$current['id']}'  ";
if ($domain) {
    $sql_search .= " and vi_referer like '%$domain%' ";
}

$sql = " select count(*) as cnt
         $sql_common 
         $sql_search ";
$row = sql_fetch($sql);
$total_count = $row[cnt];

$rows = $config[cf_page_rows];
$total_page  = ceil($total_count / $rows);  // ��ü ������ ���
if ($page == "") $page = 1; // �������� ������ ù ������ (1 ������)
$from_record = ($page - 1) * $rows; // ���� ���� ����

$sql = " select * 
          $sql_common
          $sql_search
          order by vi_id desc
          limit $from_record, $rows ";
$result = sql_query($sql);

for ($i=0; $row=sql_fetch_array($result); $i++) {
    $brow = get_brow($row[vi_agent]);
    $os   = get_os($row[vi_agent]);

    $link = "";
    $referer = "";
    $title = "";
    if ($row[vi_referer]) {
        $referer = get_text(cut_str($row[vi_referer], 255, ""));
        $title = urldecode($row[vi_referer]);
        $link = "<a href='$row[vi_referer]' target=_blank title='$title '>";
        //$link = "<a href='$row[vi_referer]' target=_blank>";
    }

    if ($is_admin == 'super')
        $ip = "<a href=\"javascript:whois('$row[vi_ip]')\" style=\"color:black;\">$row[vi_ip]</a>";
//    else
//        $ip = preg_replace("/([0-9]+).([0-9]+).([0-9]+).([0-9]+)/", "\\1.��.\\3.\\4", $row[vi_ip]);

    if ($brow == '��Ÿ') { $brow = "<span title='$row[vi_agent]'>$brow</span>"; }
    if ($os == '��Ÿ') { $os = "<span title='$row[vi_agent]'>$os</span>"; }

    $list = ($i%2);
    echo "
    <tr class='list$list col1 ht center'>
        <td>$ip</td>
        <td align=left><nobr style='display:block; overflow:hidden; width:350;'>$link$title</a></nobr></td>
        <td>$brow</td>
        <td>$os</td>
        <td>$row[vi_date] $row[vi_time]</td>
    </tr>";
}

if ($i == 0)
    echo "<tr><td colspan='$colspan' height=100 align=center>�ڷᰡ �����ϴ�.</td></tr>"; 

echo "<tr><td colspan='$colspan' class='line2'></td></tr>";
echo "</table>";

$page = get_paging($config[cf_write_pages], $page, $total_page, "$_SERVER[PHP_SELF]?mb_id=$current[mb_id]&$qstr&domain=$domain&page=");
if ($page) {
    echo "<table width=100% cellpadding=3 cellspacing=1><tr><td align=right>$page</td></tr></table>";
}



include_once("./admin.tail.php");
include_once("{$g4[path]}/tail.sub.php");

?>