## 마이에스큐엘 dump 9.11
##
## Host: localhost    Database: gblog
## ######################################################
## Server version	4.0.27-log

##
## Table structure for table `$gb4[blog_table]`
##

DROP TABLE IF EXISTS $gb4[blog_table];
CREATE TABLE $gb4[blog_table] (
  id int(11) NOT NULL auto_increment,
  mb_id varchar(20) NOT NULL default '',
  writer varchar(30) NOT NULL default '',
  regdate datetime NOT NULL default '0000-00-00 00:00:00',
  last_update datetime NOT NULL default '0000-00-00 00:00:00',
  total_file_size int(10) unsigned NOT NULL default '0',
  page_count int(11) NOT NULL default '5',
  list_count int(11) NOT NULL default '10',
  post_count int(11) NOT NULL default '0',
  secret_count int(11) NOT NULL default '0',
  comment_count int(11) NOT NULL default '0',
  trackback_count int(11) NOT NULL default '0',
  blog_name varchar(255) NOT NULL default '',
  blog_about varchar(255) NOT NULL default '',
  rss_open tinyint(4) NOT NULL default '0',
  rss_count int(11) NOT NULL default '0',
  use_table varchar(255) NOT NULL default '',
  use_comment tinyint(4) NOT NULL default '0',
  use_trackback tinyint(4) NOT NULL default '0',
  use_random tinyint(4) NOT NULL default '0',
  editor_mode tinyint(4) NOT NULL default '0',
  visit_today int(11) NOT NULL default '0',
  visit_yesterday int(11) NOT NULL default '0',
  visit_total int(11) NOT NULL default '0',
  visit_max int(11) NOT NULL default '0',
  blog_head text NOT NULL,
  blog_tail text NOT NULL,
  content_head text NOT NULL,
  content_tail text NOT NULL,
  skin_id int(11) NOT NULL default '0',
  blog_align varchar(10) NOT NULL default 'center',
  image_width int(11) NOT NULL default '500',
  blog_width tinyint(4) NOT NULL default '0',
  top_menu_color varchar(10) NOT NULL default '#5A5A5A',
  background_repeat varchar(30) NOT NULL default 'repeat',
  sidebar_post_num int(11) NOT NULL default '5',
  sidebar_comment_num int(11) NOT NULL default '5',
  sidebar_trackback_num int(11) NOT NULL default '5',
  sidebar_post_length int(11) NOT NULL default '20',
  sidebar_comment_length int(11) NOT NULL default '25',
  sidebar_trackback_length int(11) NOT NULL default '25',
  sidebar_tag_print int(11) NOT NULL default '1',
  sidebar_tag_length int(11) NOT NULL default '10',
  sidebar_tag_gap int(11) NOT NULL default '0',
  sidebar_user1_title varchar(255) NOT NULL default 'user define - sidebar1 title',
  sidebar_user2_title varchar(255) NOT NULL default 'user define - sidebar2 title',
  sidebar_user3_title varchar(255) NOT NULL default 'user define - sidebar3 title',
  sidebar_user4_title varchar(255) NOT NULL default 'user define - sidebar4 title',
  sidebar_user5_title varchar(255) NOT NULL default 'user define - sidebar5 title',
  sidebar_user1_content text NOT NULL,
  sidebar_user2_content text NOT NULL,
  sidebar_user3_content text NOT NULL,
  sidebar_user4_content text NOT NULL,
  sidebar_user5_content text NOT NULL,
  sidebar_left varchar(255) NOT NULL default '',
  sidebar_right varchar(255) NOT NULL default '',
  sidebar_garbage varchar(255) NOT NULL default '',
  PRIMARY KEY  (id),
  KEY mb_id (mb_id),
  KEY last_update (last_update)
) ;

##
## Table structure for table `$gb4[category_table]`
##

DROP TABLE IF EXISTS $gb4[category_table];
CREATE TABLE $gb4[category_table] (
  id int(11) NOT NULL auto_increment,
  blog_id int(11) NOT NULL default '0',
  category_name varchar(255) NOT NULL default '',
  post_count int(11) NOT NULL default '0',
  secret_count int(11) NOT NULL default '0',
  rank tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY blog_id (blog_id,rank)
) ;

##
## Table structure for table `$gb4[comment_table]`
##

DROP TABLE IF EXISTS $gb4[comment_table];
CREATE TABLE $gb4[comment_table] (
  id int(11) NOT NULL auto_increment,
  blog_id int(11) NOT NULL default '0',
  post_id int(11) NOT NULL default '0',
  comment_num int(11) NOT NULL default '0',
  comment_re_num int(11) NOT NULL default '0',
  mb_id varchar(30) NOT NULL default '',
  secret tinyint(4) NOT NULL default '0',
  writer_name varchar(30) NOT NULL default '',
  writer_pw varchar(255) NOT NULL default '',
  writer_email varchar(255) NOT NULL default '',
  writer_url varchar(255) NOT NULL default '',
  writer_content text NOT NULL,
  writer_ip varchar(15) NOT NULL default '',
  regdate datetime NOT NULL default '0000-00-00 00:00:00',
  moddate datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (id),
  KEY post_id (post_id,comment_num,comment_re_num)
) ;

##
## Table structure for table `$gb4[file_table]`
##

DROP TABLE IF EXISTS $gb4[file_table];
CREATE TABLE $gb4[file_table] (
  id int(11) NOT NULL auto_increment,
  blog_id int(11) NOT NULL default '0',
  post_id int(11) NOT NULL default '0',
  file_num int(11) NOT NULL default '0',
  file_size int(11) NOT NULL default '0',
  save_name varchar(255) NOT NULL default '',
  real_name varchar(255) NOT NULL default '',
  download_count int(11) NOT NULL default '0',
  file_datetime datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (id),
  KEY post_id (post_id,file_num)
) ;

##
## Table structure for table `$gb4[link_table]`
##

DROP TABLE IF EXISTS $gb4[link_table];
CREATE TABLE $gb4[link_table] (
  id int(11) NOT NULL auto_increment,
  blog_id int(11) NOT NULL default '0',
  category_id int(11) NOT NULL default '0',
  site_name varchar(255) NOT NULL default '',
  site_url varchar(255) NOT NULL default '',
  rank tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY blog_id (blog_id,category_id,rank)
) ;

##
## Table structure for table `$gb4[link_category_table]`
##

DROP TABLE IF EXISTS $gb4[link_category_table];
CREATE TABLE $gb4[link_category_table] (
  id int(11) NOT NULL auto_increment,
  blog_id int(11) NOT NULL default '0',
  category_name varchar(255) NOT NULL default '',
  category_count int(11) NOT NULL default '0',
  rank tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY blog_id (blog_id,rank)
) ;

##
## Table structure for table `$gb4[monthly_table]`
##

DROP TABLE IF EXISTS $gb4[monthly_table];
CREATE TABLE $gb4[monthly_table] (
  blog_id int(11) NOT NULL default '0',
  monthly char(7) NOT NULL default '',
  post_count int(11) NOT NULL default '0',
  secret_count int(11) NOT NULL default '0',
  PRIMARY KEY  (blog_id,monthly)
) ;

##
## Table structure for table `$gb4[post_table]`
##

DROP TABLE IF EXISTS $gb4[post_table];
CREATE TABLE $gb4[post_table] (
  id int(11) NOT NULL auto_increment,
  blog_id int(11) NOT NULL default '0',
  category_id int(11) NOT NULL default '0',
  division_id int(11) NOT NULL default '0',
  title varchar(255) NOT NULL default '',
  content text NOT NULL,
  trackback_url varchar(255) NOT NULL default '',
  post_date datetime NOT NULL default '0000-00-00 00:00:00',
  secret tinyint(4) NOT NULL default '0',
  use_rss tinyint(4) NOT NULL default '0',
  use_comment tinyint(4) NOT NULL default '0',
  use_trackback tinyint(4) NOT NULL default '0',
  use_eolin tinyint(4) NOT NULL default '0',
  comment_count int(11) NOT NULL default '0',
  trackback_count int(11) NOT NULL default '0',
  real_date datetime NOT NULL default '0000-00-00 00:00:00',
  hit int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY blog_id (blog_id,category_id)
) ;

##
## Table structure for table `$gb4[skin_table]`
##

DROP TABLE IF EXISTS $gb4[skin_table];
CREATE TABLE $gb4[skin_table] (
  id int(11) NOT NULL auto_increment,
  skin varchar(255) NOT NULL default '',
  used tinyint(4) NOT NULL default '0',
  use_count int(11) NOT NULL default '0',
  regdate datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (id)
) ;

##
## Table structure for table `$gb4[tag_table]`
##

DROP TABLE IF EXISTS $gb4[tag_table];
CREATE TABLE $gb4[tag_table] (
  id int(11) NOT NULL auto_increment,
  tag varchar(255) NOT NULL default '',
  tag_count int(11) NOT NULL default '0',
  regdate datetime NOT NULL default '0000-00-00 00:00:00',
  lastdate datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (id),
  UNIQUE KEY `tag` (`tag`)
);

##
## Table structure for table `$gb4[taglog_table]`
##

DROP TABLE IF EXISTS $gb4[taglog_table];
CREATE TABLE $gb4[taglog_table] (
  id int(11) NOT NULL auto_increment,
  blog_id int(11) NOT NULL default '0',
  post_id int(11) NOT NULL default '0',
  tag_id int(11) NOT NULL default '0',
  regdate datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (id),
  KEY blog_id (blog_id,post_id,tag_id)
) ;

##
## Table structure for table `$gb4[trackback_table]`
##

DROP TABLE IF EXISTS $gb4[trackback_table];
CREATE TABLE $gb4[trackback_table] (
  id int(11) NOT NULL auto_increment,
  blog_id int(11) NOT NULL default '0',
  post_id int(11) NOT NULL default '0',
  writer_name varchar(30) NOT NULL default '',
  writer_pw varchar(32) NOT NULL default '',
  writer_email varchar(255) NOT NULL default '',
  writer_url varchar(255) NOT NULL default '',
  writer_subject varchar(255) NOT NULL default '',
  writer_content varchar(255) NOT NULL default '',
  writer_ip varchar(255) NOT NULL default '',
  referer varchar(255) NOT NULL default '',
  regdate datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (id),
  KEY post_id (post_id),
  KEY blog_id (blog_id)
) ;

##
## Table structure for table `$gb4[guestbook_table]`
##

DROP TABLE IF EXISTS $gb4[guestbook_table];
CREATE TABLE $gb4[guestbook_table] (
  id int(11) NOT NULL auto_increment,
  blog_id int(11) NOT NULL default '0',
  mb_id varchar(30) NOT NULL default '',
  secret tinyint(4) NOT NULL default '0',
  writer_name varchar(255) NOT NULL default '',
  writer_pw varchar(255) NOT NULL default '',
  writer_email varchar(255) NOT NULL default '',
  writer_url varchar(255) NOT NULL default '',
  writer_content text NOT NULL,
  answer_content text NOT NULL,
  writer_ip varchar(255) NOT NULL default '',
  regdate datetime NOT NULL default '0000-00-00 00:00:00',
  ansdate datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (id,blog_id)
) ;

##
## Table structure for table `$gb4[visit_table]`
##

DROP TABLE IF EXISTS $gb4[visit_table];
CREATE TABLE $gb4[visit_table] (
  vi_id int(11) NOT NULL default '0',
  vi_blog_id int(11) NOT NULL default '0',
  vi_mb_id varchar(30) NOT NULL default '',
  vi_ip varchar(255) NOT NULL default '',
  vi_date date NOT NULL default '0000-00-00',
  vi_time time NOT NULL default '00:00:00',
  vi_referer text NOT NULL,
  vi_agent varchar(255) NOT NULL default '',
  PRIMARY KEY  (vi_id),
  UNIQUE KEY index1 (vi_blog_id,vi_ip,vi_date),
  KEY index2 (vi_date,vi_mb_id)
) ;

##
## Table structure for table `gb4_division`
##

DROP TABLE IF EXISTS $gb4[division_table];
CREATE TABLE $gb4[division_table] (
  dv_id int(11) NOT NULL auto_increment,
  dv_name varchar(255) NOT NULL default '',
  dv_rank int(11) NOT NULL default '0',
  PRIMARY KEY  (dv_id)
) ;

##
## Table structure for table `$gb4[visit_sum_table]`
##

DROP TABLE IF EXISTS $gb4[visit_sum_table];
CREATE TABLE $gb4[visit_sum_table] (
  vs_blog_id int(11) NOT NULL default '0',
  vs_date date NOT NULL default '0000-00-00',
  vs_count int(11) NOT NULL default '0',
  PRIMARY KEY  (vs_blog_id,vs_date),
  KEY index1 (vs_count)
) ;

# 0.2.0 - 방문자 테이블 튜닝
ALTER TABLE `$gb4[visit_table]` CHANGE `vi_id` `vi_id` INT( 11 ) NOT NULL AUTO_INCREMENT ;

# 0.2.0 - config를 파일에서 테이블로 변경
DROP TABLE IF EXISTS $gb4[config_table];
CREATE TABLE `$gb4[config_table]` (
`root` VARCHAR( 255 ) NOT NULL ,
`make_level` TINYINT( 4 ) NOT NULL ,
`make_point` INT( 11 ) NOT NULL ,
`upload_blog_file_size` INT( 11 ) NOT NULL ,
`upload_file_number` INT( 11 ) NOT NULL ,
`upload_one_file_size` INT( 11 ) NOT NULL ,
`profile_image_size` INT( 11 ) NOT NULL ,
`top_image_size` INT( 11 ) NOT NULL ,
`background_image_size` INT( 11 ) NOT NULL ,
`use_random_blog` TINYINT( 4 ) NOT NULL ,
`use_permalink` VARCHAR( 12 ) NOT NULL ,
`ampersand` VARCHAR( 1 ) NOT NULL 
);

# 0.2.0 - 블로그설정에서 편집기선택 삭제
ALTER TABLE `$gb4[blog_table]` DROP `editor_mode` ;

# 0.2.1 - config 테이블에서 root를 삭제
ALTER TABLE `$gb4[config_table]` DROP `root` ;

# 0.2.5 - 태그사용 항목 추가
ALTER TABLE `$gb4[blog_table]` ADD `use_tag` TINYINT( 4 ) NOT NULL AFTER `use_trackback` ;

# 0.2.5 - ccl, 자동출처 항목 추가
ALTER TABLE `$gb4[blog_table]` ADD `use_ccl` TINYINT( 4 ) NOT NULL AFTER `use_tag` ,
ADD `use_autosource` TINYINT( 4 ) NOT NULL AFTER `use_ccl` ;

# 0.2.6 - 페이지당 목록수
ALTER TABLE `$gb4[config_table]` ADD `gb_page_rows` TINYINT( 4 ) NOT NULL default '24';

# 0.2.7 - db 튜닝
ALTER TABLE `$gb4[file_table]` ADD INDEX `blog_id` ( `blog_id` ) ;
ALTER TABLE `$gb4[division_table]` ADD INDEX `dv_rank` ( `dv_rank` ) ;
ALTER TABLE `$gb4[skin_table]` ADD INDEX `skin` ( `skin` ) ;
ALTER TABLE `$gb4[post_table]` DROP INDEX `blog_id` , ADD INDEX `blog_id` ( `blog_id` , `category_id` , `secret` ) ;
ALTER TABLE `$gb4[category_table]` ADD INDEX `category_name` ( `category_name` ) ;

# 0.2.7 - 블로그 쓰기 권한
ALTER TABLE `$gb4[blog_table]` ADD `use_post` TINYINT( 4 ) NOT NULL AFTER `use_comment` ,
ADD `use_guestbook` TINYINT( 4 ) NOT NULL AFTER `use_post` ;

# 0.2.7 - link use flag 추가 (사용/사용하지 않음)
ALTER TABLE `$gb4[link_table]` ADD `used` TINYINT( 4 ) NOT NULL ;
ALTER TABLE `$gb4[link_table]` ADD INDEX `used` ( `used` ) ;

# 1.0.0 - 베타1
ALTER TABLE `$gb4[post_table]` ADD `use_ccl_writer` TINYINT( 4 ) NOT NULL ;
ALTER TABLE `$gb4[post_table]` ADD `use_ccl_commecial` TINYINT( 4 ) NOT NULL ;
ALTER TABLE `$gb4[post_table]` ADD `use_ccl_modify` TINYINT( 4 ) NOT NULL ;
ALTER TABLE `$gb4[post_table]` ADD `use_ccl_allow` TINYINT( 4 ) NOT NULL ;

# 1.0.0 - 베타2
ALTER TABLE `$gb4[post_table]` ADD `bo_table` VARCHAR( 255 ) NOT NULL ;
ALTER TABLE `$gb4[post_table]` ADD `wr_id` INT( 11 ) NOT NULL ;

# 1.0.0 - 베타3
ALTER TABLE `$gb4[file_table]` ADD `bf_width` INT( 11 ) NOT NULL AFTER `file_size` ;
ALTER TABLE `$gb4[file_table]` ADD `bf_height` INT( 11 ) NOT NULL AFTER `bf_width` ;
ALTER TABLE `$gb4[file_table]` ADD `bf_type` TINYINT( 4 ) NOT NULL AFTER `bf_height` ;
ALTER TABLE `$gb4[blog_table]` ADD `gb_point` INT( 11 ) NOT NULL ;
ALTER TABLE `$gb4[blog_table]` ADD `gb_suggest` TINYINT( 4 ) NOT NULL ;
ALTER TABLE `$gb4[post_table]` ADD `suggest` TINYINT( 4 ) NOT NULL ;
ALTER TABLE `$gb4[config_table]` ADD `point_visit` INT( 11 ) NOT NULL ;
ALTER TABLE `$gb4[config_table]` ADD `point_view` INT( 11 ) NOT NULL ;
ALTER TABLE `$gb4[config_table]` ADD `point_write` INT( 11 ) NOT NULL ;
ALTER TABLE `$gb4[config_table]` ADD `point_comment` INT( 11 ) NOT NULL ;
ALTER TABLE `$gb4[config_table]` ADD `point_write_guestbook` INT( 11 ) NOT NULL ;

# 1.0.0 - 베타6
ALTER TABLE `$gb4[post_table]` ADD `bitly_url` VARCHAR( 255 ) NOT NULL  ;


# db_version 정보를 업데이트
ALTER TABLE `$gb4[config_table]` ADD `gb_version` INT( 11 ) default '106' ;