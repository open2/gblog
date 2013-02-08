<?
include_once("./_common.php");

if ($is_admin != "super")
    alert("�ְ�����ڸ� ���� �����մϴ�.", $g4[path]);

$g4[title] = "��α� ���׷��̵�";

include_once("../admin.head.php");

// ��α� ���� (��. ����α� 1.01 -> 101)
$gb_version = 106;

echo "����α׸� {$gb_version} �������� ���׷��̵� �մϴ�.<Br>���׷��̵� �Ϸ�â�� ��Ÿ�������� ��ٸ��ñ� �ٶ��ϴ�.";

$sql    = " select gb_version from $gb4[config_table] ";
$row = sql_fetch($sql);

// 1.01 ����
if ($row[gb_version] < $gb_version) {
  
    // 0.2.0 - config�� ���Ͽ��� ���̺�� ����
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

    // 0.2.0 - �湮�� ���̺� Ʃ��
    sql_query("ALTER TABLE `$gb4[visit_table]` CHANGE `vi_id` `vi_id` INT( 11 ) NOT NULL AUTO_INCREMENT ", false);

    // 0.2.0 - ��α׼������� �����⼱�� ����
    sql_query(" ALTER TABLE `$gb4[blog_table]` DROP `editor_mode` ", FALSE);

    // 0.2.1 - config ���̺��� root�� ����
    sql_query(" ALTER TABLE `$gb4[config_table]` DROP `root` ", FALSE);

    // 0.2.5 - �±׻�� �׸� �߰�
    sql_query(" ALTER TABLE `$gb4[blog_table]` ADD `use_tag` TINYINT( 4 ) NOT NULL AFTER `use_trackback` ", FALSE);

    // 0.2.5 - ccl, �ڵ���ó �׸� �߰�
    sql_query(" ALTER TABLE `$gb4[blog_table]` ADD `use_ccl` TINYINT( 4 ) NOT NULL AFTER `use_tag` ,
    ADD `use_autosource` TINYINT( 4 ) NOT NULL AFTER `use_ccl` ", FALSE);

    // 0.2.6 - �������� ��ϼ�
    sql_query(" ALTER TABLE `$gb4[config_table]` ADD `gb_page_rows` TINYINT( 4 ) NOT NULL ", FALSE);

    // 0.2.7 - db Ʃ��
    sql_query(" ALTER TABLE `$gb4[file_table]` ADD INDEX `blog_id` ( `blog_id` ) ", FALSE);
    sql_query(" ALTER TABLE `$gb4[division_table]` ADD INDEX `dv_rank` ( `dv_rank` ) ", FALSE);
    sql_query(" ALTER TABLE `$gb4[skin_table]` ADD INDEX `skin` ( `skin` ) ", FALSE);
    sql_query(" ALTER TABLE `$gb4[post_table]` DROP INDEX `blog_id` , ADD INDEX `blog_id` ( `blog_id` , `category_id` , `secret` ) ", FALSE);
    sql_query(" ALTER TABLE `$gb4[category_table]` ADD INDEX `category_name` ( `category_name` ) ", FALSE);
    sql_query(" ALTER TABLE `$gb4[tag_table]` DROP INDEX `tag` ", FALSE);
    sql_query(" ALTER TABLE `$gb4[tag_table]` ADD UNIQUE ( `tag` ) ", FALSE);

    // 1.0.0 - ����7
    sql_query(" ALTER TABLE `$gb4[post_table]` ADD `use_ccl_writer` TINYINT( 4 ) NOT NULL ", FALSE);
    sql_query(" ALTER TABLE `$gb4[post_table]` ADD `use_ccl_commecial` TINYINT( 4 ) NOT NULL ", FALSE);
    sql_query(" ALTER TABLE `$gb4[post_table]` ADD `use_ccl_modify` TINYINT( 4 ) NOT NULL ", FALSE);
    sql_query(" ALTER TABLE `$gb4[post_table]` ADD `use_ccl_allow` TINYINT( 4 ) NOT NULL ", FALSE);

    // 0.2.7 - link use flag �߰� (���/������� ����)
    sql_query(" ALTER TABLE `$gb4[link_table]` ADD `used` TINYINT( 4 ) NOT NULL ", FALSE);
    sql_query(" ALTER TABLE `$gb4[link_table]` ADD INDEX `used` ( `used` ) ", FALSE);

    // 0.2.7 - ��α� ���� ����
    sql_query(" ALTER TABLE `$gb4[blog_table]` ADD `use_post` TINYINT( 4 ) NOT NULL AFTER `use_comment` ,
    ADD `use_guestbook` TINYINT( 4 ) NOT NULL AFTER `use_post` ", FALSE);

}

// 1.0.0 - ��Ÿ2
if ($row[gb_version] < $gb_version) {
    sql_query(" ALTER TABLE `$gb4[post_table]` ADD `bo_table` VARCHAR( 255 ) NOT NULL ", FALSE);
    sql_query(" ALTER TABLE `$gb4[post_table]` ADD `wr_id` INT( 11 ) NOT NULL ", FALSE);
}

// 1.0.0 - ��Ÿ3
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

// 1.0.0 - ��Ÿ6
if ($row[gb_version] < $gb_version) {
    sql_query(" ALTER TABLE `$gb4[post_table]` ADD `bitly_url` VARCHAR( 255 ) NOT NULL ", FALSE);
}


// ����α� db ������ ���
sql_query(" UPDATE `$gb4[config_table]` SET `gb_version`= '$gb_version' ", false);

echo "<br><br>UPGRADE �Ϸ�.";

include_once("../admin.tail.php");
?>