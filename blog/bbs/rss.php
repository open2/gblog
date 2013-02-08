<?
include_once("./_common.php");

if (!$current[rss_open]) {
    $xml  = "<?xml version=\"1.0\" encoding=\"$g4[charset]\"?>\n";
    $xml .= "<rss version=\"2.0\">\n";
    $xml .= "<items>\n";
    $xml .= "<errnum>1</errnum>\n";
    $xml .= "<errmsg>RSS가 공개되지 않았습니다.</errmsg>\n";
    $xml .= "</items>\n";
    $xml .= "</rss>\n";
    echo_xml($xml);
    exit;
}

$xml  = "<?xml version=\"1.0\" encoding=\"$g4[charset]\"?>\n";
$xml .= "<rss version=\"2.0\">\n";
$xml .= "<channel>\n";
$xml .= "<title>".specialchars_replace($current[blog_name])."</title>\n";
$xml .= "<link>".get_full_url($current[blog_url])."</link>\n";
$xml .= "<description>".specialchars_replace($current[blog_about])."</description>\n";
$xml .= "<language>ko</language>\n";

$profile_image_url = "$g4[url]/data/blog/profile_image/$current[mb_id]";
$profile_image_path = "$g4[path]/data/blog/profile_image/$current[mb_id]";

if (file_exists($profile_image_path) ) 
{
    $size = getImageSize($profile_image_path);
    $xml .= "<image>\n";
    $xml .= "<title>".specialchars_replace($current[blog_name])."</title>\n";
    $xml .= "<url>".specialchars_replace($profile_image_url)."</url>\n";
    $xml .= "<link>".get_full_url($current[blog_url])."</link>\n";
    $xml .= "<width>{$size[0]}</width>\n";
    $xml .= "<height>{$size[1]}</height>\n";
    $xml .= "<description>".specialchars_replace($current[blog_about])."</description>\n";
    $xml .= "</image>\n";
}

$sql  = " select ";
$sql .= " p.*, c.category_name ";
$sql .= " from ";
$sql .= " $gb4[post_table] p left join $gb4[category_table] c on p.category_id = c.id ";
$sql .= " where ";
$sql .= " p.blog_id='$current[id]' ";
$sql .= " and secret=1 ";
$sql .= " and use_rss=1 ";
$sql .= " order by p.post_date desc ";
$sql .= " limit 0, {$current[rss_count]} ";

$qry = sql_query($sql);

for ($i=0; $row=sql_fetch_array($qry); $i++) 
{
    if ($current[rss_open] == 2)
        $row[content] = cut_str(strip_tags($row[content]), 255);

    if (empty($row[category_name]))
        $row[category_name] = '분류없음';

    $link = get_post_url($row[id]);
    $link = get_full_url($link);

    $date = date('r', strtotime($row[post_date]));

    $xml .= "<item>\n";
    $xml .= "<title>".specialchars_replace($row[title])."</title>\n";
    $xml .= "<link>".specialchars_replace($link)."</link>\n";
    $xml .= "<description><![CDATA[".$row[content]."]]></description>\n";
    $xml .= "<category>".specialchars_replace($row[category_name])."</category>\n";
    $xml .= "<author>".specialchars_replace($current[writer])."</author>\n";
    $xml .= "<pubDate>".$date."</pubDate>\n";
    $xml .= "</item>\n";
}

$xml .= "</channel>\n";
$xml .= "</rss>\n";

//print_r($xml);

echo_xml($xml);

?>