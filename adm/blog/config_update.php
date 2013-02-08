<?
include_once("./_common.php");

auth_check($auth[$sub_menu], "w");

if ($is_admin != "super")
    alert("�ְ�����ڸ� ���� �����մϴ�.");

// blog ���丮�� ���� ���� �������� �˻�.
if (!is_writeable("../../{$gb4['blog']}")) 
    alert("{$gb4['blog']} ���丮�� �۹̼��� 707�� �����Ͽ� �ֽʽÿ�.\\n\\n$> chmod 707 {$gb4['blog']} \\n\\n�� ���� ������ �õ��� �ֽʽÿ�.");

$multi_mode_htaccess = '
<IfModule mod_rewrite.c>
RewriteEngine On

RewriteRule ^([a-zA-Z0-9_]+)$ index\.php\?mb_id=$1
RewriteRule ^([a-zA-Z0-9_]+)/([0-9]+)$ index\.php\?mb_id=$1&id=$2
RewriteRule ^([a-zA-Z0-9_]+)/([0-9]+)/([0-9]+)/([0-9]+)$ index\.php\?mb_id=$1&yyyy=$2&mm=$3&dd=$4
RewriteRule ^([a-zA-Z0-9_]+)/([0-9]+)/([0-9]+)$ index\.php\?mb_id=$1&yyyy=$2&mm=$3
RewriteRule ^([a-zA-Z0-9_]+)/tag/([^\/]*)$ index\.php\?mb_id=$1&tag=$2
RewriteRule ^([a-zA-Z0-9_]+)/category/([^\/]*)$ index\.php\?mb_id=$1&cate=$2
RewriteRule ^([a-zA-Z0-9_]+)/search/([^\/]*)$ index\.php\?mb_id=$1&search=$2
RewriteRule ^([a-zA-Z0-9_]+)/rss$ rss\.php\?mb_id=$1
RewriteRule ^([a-zA-Z0-9_]+)/tags$ tags\.php\?mb_id=$1
RewriteRule ^([a-zA-Z0-9_]+)/guestbook$ guestbook\.php\?mb_id=$1

RewriteRule ^([a-zA-Z0-9_]+)/page/([0-9]+)$ index\.php\?mb_id=$1&page=$2
RewriteRule ^([a-zA-Z0-9_]+)/tag/([^\/]*)/page/([0-9]+)$ index\.php\?mb_id=$1&tag=$2&page=$3
RewriteRule ^([a-zA-Z0-9_]+)/category/([^\/]*)/page/([0-9]+)$ index\.php\?mb_id=$1&cate=$2&page=$3
RewriteRule ^([a-zA-Z0-9_]+)/search/([^\/]*)/page/([0-9]+)$ index\.php\?mb_id=$1&search=$2&page=$3
RewriteRule ^([a-zA-Z0-9_]+)/guestbook/page/([0-9]+)$ guestbook\.php\?mb_id=$1&page=$2
RewriteRule ^([a-zA-Z0-9_]+)/([0-9]+)/page/([0-9]+)$ index\.php\?mb_id=$1&id=$2&page=$3
RewriteRule ^([a-zA-Z0-9_]+)/([0-9]+)/([0-9]+)/([0-9]+)/page/([0-9]+)$ index\.php\?mb_id=$1&yyyy=$2&mm=$3&dd=$4&page=$5
RewriteRule ^([a-zA-Z0-9_]+)/([0-9]+)/([0-9]+)/page/([0-9]+)$ index\.php\?mb_id=$1&yyyy=$2&mm=$3&page=$4

RewriteRule ^([a-zA-Z0-9_]+)/preview/(.+)$ index\.php\?mb_id=$1&preview=$2
</IfModule>
';

$htaccess_path = '../../'.$gb4['blog'];
$htaccess_file = $htaccess_path.'/.htaccess';

if ($use_permalink=='none') { // �۸Ӹ�ũ ������� ���� ���

    @unlink($htaccess_file);

} else { // �۸Ӹ�ũ ����� ���

    $fp = @fopen($htaccess_file,'w');

    @fwrite($fp, $multi_mode_htaccess);
    @chmod($htaccess_file,0707);

    @fclose($fp);
}

$ampersand = '&';

if ($gb_page_rows <= 0) $gb_page_rows = 24;

$sql = " update $gb4[config_table]
            set 
                make_level              = '$make_level',
                make_point              = '$make_point',
                upload_blog_file_size   = '$upload_blog_file_size',
                gb_page_rows            = '$gb_page_rows',
                upload_file_number      = '$upload_file_number',
                upload_one_file_size    = '$upload_one_file_size',
                profile_image_size      = '$profile_image_size',
                top_image_size          = '$top_image_size',
                background_image_size   = '$background_image_size',
                use_random_blog         = '$use_random_blog',
                use_permalink           = '$use_permalink',
                ampersand               = '$ampersand'
                ";
sql_query($sql);

goto_url("./config.php");

?>