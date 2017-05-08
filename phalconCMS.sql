/*
SQLyog 企业版 - MySQL GUI v8.14 
MySQL - 5.7.11-log : Database - PhalconCMS
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`PhalconCMS` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

USE `PhalconCMS`;

/*Table structure for table `articles` */

DROP TABLE IF EXISTS `articles`;

CREATE TABLE `articles` (
  `aid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT ' 文章ID',
  `title` varchar(255) NOT NULL COMMENT '文章标题',
  `introduce` text NOT NULL COMMENT '文章摘要',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态 0：删除  1：发布 2：草稿',
  `view_number` int(10) NOT NULL DEFAULT '0' COMMENT '浏览量',
  `is_recommend` char(1) NOT NULL DEFAULT '0' COMMENT '是否为推荐阅读',
  `is_top` char(1) NOT NULL DEFAULT '0' COMMENT '是否置顶',
  `create_by` int(10) unsigned NOT NULL COMMENT '创建者',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `modify_by` int(10) unsigned NOT NULL COMMENT '修改者',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间',
  PRIMARY KEY (`aid`),
  KEY `INDEX_TITLE` (`title`)
) ENGINE=InnoDB AUTO_INCREMENT=125 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='文章表';

/*Table structure for table `articles_categorys` */

DROP TABLE IF EXISTS `articles_categorys`;

CREATE TABLE `articles_categorys` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `aid` int(10) unsigned NOT NULL COMMENT '文章ID',
  `cid` int(10) unsigned NOT NULL COMMENT '分类ID',
  PRIMARY KEY (`id`),
  KEY `INDEX_AID_CID` (`aid`,`cid`)
) ENGINE=InnoDB AUTO_INCREMENT=154 DEFAULT CHARSET=utf8mb4;

/*Table structure for table `articles_tags` */

DROP TABLE IF EXISTS `articles_tags`;

CREATE TABLE `articles_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `aid` int(11) NOT NULL COMMENT '文章ID',
  `tid` int(11) NOT NULL COMMENT '标签ID',
  PRIMARY KEY (`id`),
  KEY `INDEX_AID_TID` (`aid`,`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=324 DEFAULT CHARSET=utf8mb4;

/*Table structure for table `categorys` */

DROP TABLE IF EXISTS `categorys`;

CREATE TABLE `categorys` (
  `cid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT ' 分类ID',
  `category_name` varchar(50) NOT NULL COMMENT '分类名称',
  `slug` varchar(50) NOT NULL DEFAULT '' COMMENT '分类缩略名',
  `sort` mediumint(9) NOT NULL DEFAULT '999' COMMENT '分类排序',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '分类描述',
  `parent_cid` int(10) unsigned NOT NULL COMMENT '父分类ID',
  `path` varchar(255) NOT NULL COMMENT '分类路径',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态 0：删除',
  `create_by` int(10) unsigned NOT NULL COMMENT '创建者',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `modify_by` int(10) unsigned NOT NULL COMMENT '修改者',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间',
  PRIMARY KEY (`cid`),
  KEY `INDEX_SLUG` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=107 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='分类表';

/*Table structure for table `contents` */

DROP TABLE IF EXISTS `contents`;

CREATE TABLE `contents` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `relateid` int(10) NOT NULL COMMENT '关联ID',
  `markdown` text NOT NULL COMMENT 'markdown内容',
  `content` text NOT NULL COMMENT 'html内容',
  PRIMARY KEY (`id`),
  KEY `INDEX_RELATEID` (`relateid`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='内容表';

/*Table structure for table `menu` */

DROP TABLE IF EXISTS `menu`;

CREATE TABLE `menu` (
  `mid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '菜单ID',
  `menu_name` varchar(50) NOT NULL COMMENT '菜单名',
  `menu_url` varchar(50) NOT NULL COMMENT '菜单URL',
  `sort` mediumint(9) NOT NULL DEFAULT '999' COMMENT '菜单排序',
  `parent_mid` int(10) unsigned NOT NULL COMMENT '父菜单ID',
  `path` varchar(255) NOT NULL COMMENT '菜单路径',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态 0：删除',
  `create_by` int(10) unsigned NOT NULL COMMENT '创建者',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `modify_by` int(10) unsigned NOT NULL COMMENT '修改者',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间',
  PRIMARY KEY (`mid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='菜单表';

/*Table structure for table `options` */

DROP TABLE IF EXISTS `options`;

CREATE TABLE `options` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `op_key` varchar(50) NOT NULL COMMENT '配置key',
  `op_value` varchar(50) NOT NULL DEFAULT '' COMMENT '配置value',
  `create_by` int(10) unsigned NOT NULL COMMENT '创建者',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `modify_by` int(10) unsigned NOT NULL COMMENT '修改者',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UQ_OP_KEY` (`op_key`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='配置表';

insert  into `options`(`id`,`op_key`,`op_value`,`create_by`,`create_time`,`modify_by`,`modify_time`) values (1,'site_name','Marser',1,'2016-11-28 10:48:58',1,'2016-12-01 12:01:33'),(2,'site_url','http://www.marser.cn/',1,'2016-11-28 10:49:20',1,'2016-12-22 12:22:35'),(3,'site_description','描述',1,'2016-11-28 10:49:33',1,'2016-11-28 10:53:10'),(4,'site_keywords','关键字',1,'2016-11-28 10:49:45',1,'2016-11-28 10:53:10'),(5,'page_article_number','10',1,'2016-11-28 11:05:10',1,'2016-12-29 16:11:46'),(6,'recommend_article_number','10',1,'2016-11-28 11:05:19',1,'2016-12-29 16:11:43'),(7,'site_title','标题',1,'2016-12-01 11:54:17',1,'2016-12-01 12:01:33'),(8,'relate_article_number','8',1,'2016-12-21 10:00:38',1,'2016-12-21 10:00:38'),(9,'cdn_url','',1,'2016-12-22 12:16:41',1,'2016-12-24 15:51:59');

/*Table structure for table `tags` */

DROP TABLE IF EXISTS `tags`;

CREATE TABLE `tags` (
  `tid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '标签ID',
  `tag_name` varchar(50) NOT NULL COMMENT '标签名称',
  `slug` varchar(50) NOT NULL DEFAULT '' COMMENT '标签缩略名',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '标签状态 0：删除',
  `create_by` int(10) unsigned NOT NULL COMMENT '创建者',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `modify_by` int(10) unsigned NOT NULL COMMENT '修改者',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间',
  PRIMARY KEY (`tid`),
  UNIQUE KEY `UQ_TAG_NAME` (`tag_name`),
  KEY `INDEX_SLUG` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=113 DEFAULT CHARSET=utf8 COMMENT='标签表';

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` varchar(50) NOT NULL COMMENT '用户名',
  `password` varchar(64) NOT NULL COMMENT '密码',
  `realname` varchar(50) NOT NULL COMMENT '用户真实姓名',
  `phone_number` varchar(20) NOT NULL COMMENT '联系方式 ',
  `intro` varchar(255) NOT NULL DEFAULT '' COMMENT '用户简介',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态 1：激活 0：冻结',
  `create_by` int(10) unsigned NOT NULL COMMENT '创建者',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `modify_by` int(10) unsigned NOT NULL COMMENT '修改者',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `UQ_USERNAME` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='用户表';

insert  into `users`(`uid`,`username`,`password`,`realname`,`phone_number`,`intro`,`status`,`create_by`,`create_time`,`modify_by`,`modify_time`) values (1,'admin','$2a$08$a5xQpBGe70h2giRTST9KYOoNuKZMFFW2vRJj50t5Yy00dEtPUQKJi','admin1','15866669999','',1,1,'2016-10-24 22:58:44',1,'2016-10-24 22:58:44');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
