<?
include_once("./_common.php");

if ($is_admin != "super")
    alert("최고관리자만 접근 가능합니다.", $g4[path]);

$g4[title] = "블로그 업그레이드";

include_once("../admin.head.php");

// 블로그 버젼 (예. 지블로그 1.01 -> 101)
$gb_version = 106;

echo "지블로그를 {$gb_version} 버젼으로 업그레이드 합니다.<Br>업그레이드 완료창이 나타날때까지 기다리시기 바랍니다.";

$sql    = " select gb_version from $gb4[config_table] ";
$row = sql_fetch($sql);

// 1.01 이전
if ($row[gb_version] < $gb_version) {
  
    // 0.2.0 - config를 파일에서 테이블로 변경
    $sql = "
    CREATE TABLE `$gb4[config_table]` (
    `root` VARCHAR( 255 ) NOT NULL ,
    `make_level` TINYINT( 4 ) NOT NULL ,
    `make_point` TINYINT( 4 ) NOT NULL ,
    `upload_blog_file_size` INT( 11 ) NOT NULL ,
    `upload_file_number` INT( 11 ) NOT NULL ,
    `upload_one_file_size` INT( 11 ) NOT NULL ,
    `profile_image_size` INT( 11 ) NOT NULL ,
    `top_image_size` INT( 11 ) NOT NULL ,
    `background_image_size` INT( 11 ) NOT NULL ,
    `use_random_blog` TINYINT( 4 ) NOT NULL ,
    `use_permalink` VARCHAR( 12 ) NOT NULL ,
    `ampersand` VARCHAR( 1 ) NOT NULL 
    ) 
    ";
    sql_query($sql, FALSE);

    // 0.2.0 - 방문자 테이블 튜닝
    sql_query("ALTER TABLE `$gb4[visit_table]` CHANGE `vi_id` `vi_id` INT( 11 ) NOT NULL AUTO_INCREMENT ", false);

    // 0.2.0 - 블로그설정에서 편집기선택 삭제
    sql_query(" ALTER TABLE `$gb4[blog_table]` DROP `editor_mode` ", FALSE);

    // 0.2.1 - config 테이블에서 root를 삭제
    sql_query(" ALTER TABLE `$gb4[config_table]` DROP `root` ", FALSE);

    // 0.2.5 - 태그사용 항목 추가
    sql_query(" ALTER TABLE `$gb4[blog_table]` ADD `use_tag` TINYINT( 4 ) NOT NULL AFTER `use_trackback` ", FALSE);

    // 0.2.5 - ccl, 자동출처 항목 추가
    sql_query(" ALTER TABLE `$gb4[blog_table]` ADD `use_ccl` TINYINT( 4 ) NOT NULL AFTER `use_tag` ,
    ADD `use_autosource` TINYINT( 4 ) NOT NULL AFTER `use_ccl` ", FALSE);

    // 0.2.6 - 페이지당 목록수
    sql_query(" ALTER TABLE `$gb4[config_table]` ADD `gb_page_rows` TINYINT( 4 ) NOT NULL ", FALSE);

    // 0.2.7 - db 튜닝
    sql_query(" ALTER TABLE `$gb4[file_table]` ADD INDEX `blog_id` ( `blog_id` ) ", FALSE);
    sql_query(" ALTER TABLE `$gb4[division_table]` ADD INDEX `dv_rank` ( `dv_rank` ) ", FALSE);
    sql_query(" ALTER TABLE `$gb4[skin_table]` ADD INDEX `skin` ( `skin` ) ", FALSE);
    sql_query(" ALTER TABLE `$gb4[post_table]` DROP INDEX `blog_id` , ADD INDEX `blog_id` ( `blog_id` , `category_id` , `secret` ) ", FALSE);
    sql_query(" ALTER TABLE `$gb4[category_table]` ADD INDEX `category_name` ( `category_name` ) ", FALSE);
    sql_query(" ALTER TABLE `$gb4[tag_table]` DROP INDEX `tag` ", FALSE);
    sql_query(" ALTER TABLE `$gb4[tag_table]` ADD UNIQUE ( `tag` ) ", FALSE);

    // 1.0.0 - 알파7
    sql_query(" ALTER TABLE `$gb4[post_table]` ADD `use_ccl_writer` TINYINT( 4 ) NOT NULL ", FALSE);
    sql_query(" ALTER TABLE `$gb4[post_table]` ADD `use_ccl_commecial` TINYINT( 4 ) NOT NULL ", FALSE);
    sql_query(" ALTER TABLE `$gb4[post_table]` ADD `use_ccl_modify` TINYINT( 4 ) NOT NULL ", FALSE);
    sql_query(" ALTER TABLE `$gb4[post_table]` ADD `use_ccl_allow` TINYINT( 4 ) NOT NULL ", FALSE);

    // 0.2.7 - link use flag 추가 (사용/사용하지 않음)
    sql_query(" ALTER TABLE `$gb4[link_table]` ADD `used` TINYINT( 4 ) NOT NULL ", FALSE);
    sql_query(" ALTER TABLE `$gb4[link_table]` ADD INDEX `used` ( `used` ) ", FALSE);

    // 0.2.7 - 블로그 쓰기 권한
    sql_query(" ALTER TABLE `$gb4[blog_table]` ADD `use_post` TINYINT( 4 ) NOT NULL AFTER `use_comment` ,
    ADD `use_guestbook` TINYINT( 4 ) NOT NULL AFTER `use_post` ", FALSE);

}

// 1.0.0 - 베타2
if ($row[gb_version] < $gb_version) {
    sql_query(" ALTER TABLE `$gb4[post_table]` ADD `bo_table` VARCHAR( 255 ) NOT NULL ", FALSE);
    sql_query(" ALTER TABLE `$gb4[post_table]` ADD `wr_id` INT( 11 ) NOT NULL ", FALSE);
}

// 1.0.0 - 베타3
if ($row[gb_version] < $gb_version) {
    sql_query(" ALTER TABLE `$gb4[file_table]` ADD `bf_width` INT( 11 ) NOT NULL AFTER `file_size` ", FALSE);
    sql_query(" ALTER TABLE `$gb4[file_table]` ADD `bf_height` INT( 11 ) NOT NULL AFTER `bf_width` ", FALSE);
    sql_query(" ALTER TABLE `$gb4[file_table]` ADD `bf_type` TINYINT( 4 ) NOT NULL AFTER `bf_height` ", FALSE);
    sql_query(" ALTER TABLE `$gb4[blog_table]` ADD `gb_point` INT( 11 ) NOT NULL ", FALSE);
    sql_query(" ALTER TABLE `$gb4[blog_table]` ADD `gb_suggest` TINYINT( 4 ) NOT NULL ", FALSE);
    sql_query(" ALTER TABLE `$gb4[post_table]` ADD `suggest` TINYINT( 4 ) NOT NULL ", FALSE);
    sql_query(" ALTER TABLE `$gb4[config_table] ADD `point_visit` INT( 11 ) NOT NULL ", FALSE);
    sql_query(" ALTER TABLE `$gb4[config_table]` ADD `point_view` INT( 11 ) NOT NULL ", FALSE);
    sql_query(" ALTER TABLE `$gb4[config_table]` ADD `point_write` INT( 11 ) NOT NULL ", FALSE);
    sql_query(" ALTER TABLE `$gb4[config_table]` ADD `point_comment` INT( 11 ) NOT NULL ", FALSE);
    sql_query(" ALTER TABLE `$gb4[config_table]` ADD `point_write_guestbook` INT( 11 ) NOT NULL ", FALSE);
}

// 1.0.0 - 베타6
if ($row[gb_version] < $gb_version) {
    sql_query(" ALTER TABLE `$gb4[post_table]` ADD `bitly_url` VARCHAR( 255 ) NOT NULL ", FALSE);
}


// 지블로그 db 버젼을 기록
sql_query(" UPDATE `$gb4[config_table]` SET `gb_version`= '$gb_version' ", false);

echo "<br><br>UPGRADE 완료.";

include_once("../admin.tail.php");
?>