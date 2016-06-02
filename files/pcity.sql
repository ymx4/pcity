/*
SQLyog v10.2 
MySQL - 5.6.17 : Database - pcity
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `admin_member` */

DROP TABLE IF EXISTS `admin_member`;

CREATE TABLE `admin_member` (
  `uid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `u_username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `admin_member` */

insert  into `admin_member`(`uid`,`username`,`password`) values (1,'admin','626e2ba2c7a3dc2b9ffe793076ec8c5f4d76c927');

/*Table structure for table `announcement` */

DROP TABLE IF EXISTS `announcement`;

CREATE TABLE `announcement` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL DEFAULT '',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '3',
  `file_name` varchar(64) NOT NULL DEFAULT '',
  `content` varchar(1000) DEFAULT '',
  `create_time` int(11) unsigned DEFAULT '0',
  `update_time` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `announcement` */

/*Table structure for table `company` */

DROP TABLE IF EXISTS `company`;

CREATE TABLE `company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `type` tinyint(1) unsigned NOT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `company` */

insert  into `company`(`id`,`name`,`type`,`create_time`,`update_time`) values (1,'施工公司',1,1464573364,1464573398),(2,'监理公司',2,1464573386,1464573386),(3,'建设公司',3,1464573407,1464573407);

/*Table structure for table `group` */

DROP TABLE IF EXISTS `group`;

CREATE TABLE `group` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL DEFAULT '',
  `create_time` int(11) unsigned DEFAULT '0',
  `update_time` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `group` */

insert  into `group`(`id`,`title`,`create_time`,`update_time`) values (4,'房间1-1',1463289228,1463289228);

/*Table structure for table `group_role` */

DROP TABLE IF EXISTS `group_role`;

CREATE TABLE `group_role` (
  `group_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `role` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`group_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `group_role` */

insert  into `group_role`(`group_id`,`user_id`,`role`) values (4,1,1),(4,2,2),(4,4,3),(4,3,4);

/*Table structure for table `materiel` */

DROP TABLE IF EXISTS `materiel`;

CREATE TABLE `materiel` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL DEFAULT '',
  `position` varchar(1000) NOT NULL DEFAULT '',
  `constructor_image` varchar(1000) NOT NULL DEFAULT '',
  `supervisor_image` varchar(1000) NOT NULL DEFAULT '',
  `quantity` int(11) unsigned DEFAULT '0',
  `constructor_id` int(11) unsigned DEFAULT '0',
  `supervisor_id` int(11) unsigned DEFAULT '0',
  `builder_id` int(11) unsigned DEFAULT '0',
  `supervisor_check` tinyint(1) unsigned DEFAULT '0',
  `builder_check` tinyint(1) unsigned DEFAULT '0',
  `status` tinyint(1) unsigned DEFAULT '0',
  `create_time` int(11) unsigned DEFAULT '0',
  `update_time` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `materiel` */

/*Table structure for table `task` */

DROP TABLE IF EXISTS `task`;

CREATE TABLE `task` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL DEFAULT '',
  `content` varchar(1000) NOT NULL DEFAULT '',
  `create_time` int(11) unsigned DEFAULT '0',
  `update_time` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `task` */

insert  into `task`(`id`,`title`,`content`,`create_time`,`update_time`) values (1,'a','aaaa',1461594504,1461594504),(2,'bb','bbbbbbbb',1461594515,1461594515),(3,'','cccccccccccccccccccccccccccccccccccc',1461594574,1461594574),(4,'ddddddddddddddddddddddddddddddddddddddddddddddddddd','dddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd',1461594595,1461594595);

/*Table structure for table `template` */

DROP TABLE IF EXISTS `template`;

CREATE TABLE `template` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL DEFAULT '',
  `category` int(11) unsigned NOT NULL DEFAULT '0',
  `image` varchar(1000) NOT NULL DEFAULT '',
  `process_requirement` varchar(1000) NOT NULL DEFAULT '',
  `acceptance_criteria` varchar(1000) NOT NULL DEFAULT '',
  `manage_level` varchar(1000) NOT NULL DEFAULT '',
  `location` varchar(1000) NOT NULL DEFAULT '',
  `disclosure_criteria` varchar(1000) NOT NULL DEFAULT '',
  `materiel_name` varchar(1000) NOT NULL DEFAULT '',
  `materiel_mfr` varchar(1000) NOT NULL DEFAULT '',
  `materiel_specificatio` varchar(1000) NOT NULL DEFAULT '',
  `materiel_range` varchar(1000) NOT NULL DEFAULT '',
  `host` varchar(1000) NOT NULL DEFAULT '',
  `disclosure` varchar(1000) NOT NULL DEFAULT '',
  `attendee` varchar(1000) NOT NULL DEFAULT '',
  `adjustment` varchar(1000) NOT NULL DEFAULT '',
  `create_time` int(11) unsigned DEFAULT '0',
  `update_time` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `template` */

insert  into `template`(`id`,`title`,`category`,`image`,`process_requirement`,`acceptance_criteria`,`manage_level`,`location`,`disclosure_criteria`,`materiel_name`,`materiel_mfr`,`materiel_specificatio`,`materiel_range`,`host`,`disclosure`,`attendee`,`adjustment`,`create_time`,`update_time`) values (1,'aaa',1,'[\"1461933760.jpg\"]','aaa','aaa','aaa','aaa','','','','','','','','','',0,1461933762),(2,'bbbbbbbb',1,'[\"1461933971.jpg\"]','bbbbbbbb','bbbbbbbb','bbbbbbbb','bbbbbbbb','','','','','','','','','',0,1461933973),(3,'cccccccccc',1,'','cccccccccc','cccccccccc','cccccccccc','cccccccccc','','','','','','','','','',1461934119,1461934119),(4,'ddddddddddddd',2,'[\"1461934146.jpg\"]','ddddddddddddd','ddddddddddddd','ddddddddddddd','ddddddddddddd','','','','','','','','','',0,1461934149),(5,'eeeeeeee',1,'[\"1461934182.jpg\"]','eeeeeeee','eeeeeeee','eeeeeeee','eeeeeeee','','','','','','','','','',0,1461934504);

/*Table structure for table `user_auth` */

DROP TABLE IF EXISTS `user_auth`;

CREATE TABLE `user_auth` (
  `user_id` int(11) unsigned NOT NULL,
  `auth` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `user_auth` */

insert  into `user_auth`(`user_id`,`auth`) values (1,'[\"admin\"]');

/*Table structure for table `wx_data_store` */

DROP TABLE IF EXISTS `wx_data_store`;

CREATE TABLE `wx_data_store` (
  `app_id` varchar(100) NOT NULL,
  `data` varchar(255) DEFAULT NULL,
  `type` varchar(20) NOT NULL,
  `create_time` int(11) DEFAULT NULL,
  `expire_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`app_id`,`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `wx_data_store` */

insert  into `wx_data_store`(`app_id`,`data`,`type`,`create_time`,`expire_time`) values ('wx35de4aedd8758e5e','o2gTHmnDHYeEihTVEHEIkT-FQWIeZQpKLcp_XpQ6Fzpx6u9oLFDS0qGuIbUgE-04zrRbKjkiPHaUTB7yLqUML97pxMWGVh_p4IbwhqeA7rAWTPgACAPMI','access_token',1461480249,1461487389);

/*Table structure for table `wx_user` */

DROP TABLE IF EXISTS `wx_user`;

CREATE TABLE `wx_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(255) DEFAULT NULL,
  `nickname` varchar(100) DEFAULT NULL,
  `sex` tinyint(4) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `language` varchar(255) DEFAULT NULL,
  `headimgurl` varchar(255) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `company_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `openid` (`openid`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `wx_user` */

insert  into `wx_user`(`id`,`openid`,`nickname`,`sex`,`city`,`country`,`province`,`language`,`headimgurl`,`create_time`,`update_time`,`company_id`) values (1,'oGh9uwZ3H0v9l993Ozy3kv3hRqe0','Hypnos',1,'苏州','中国','江苏','zh_CN','http://wx.qlogo.cn/mmopen/Q3auHgzwzM6dIVaJAM9sZZYuzicvTOdDyHWLyI2YE31VomQayJlI5GdApyUBsuc3TickJLq9FzGklz0LcMAr4BoSDCDHiaIjTxqelTdydYwqZ8/0',1461224490,1461224500,1),(2,'oGh9uweuonaX9YNwGpo55z9Ol3AM','流云飞扬',1,'苏州','中国','江苏','zh_CN','http://wx.qlogo.cn/mmopen/LCWtHyvj1E2Fiav7N72j907iblqeVH64TNO8MZ4ekCicZkvYicVMsbNLFXmKxoCM9Ngq4VPSAbX3qHKsVEibOKSCLQHUqeUtSmJZk/0',1461224978,1461224978,0),(3,'oGh9uwaQn8GdJQAHdv6nADfrCUkw','KAME',1,'苏州','中国','江苏','zh_CN','http://wx.qlogo.cn/mmopen/jT2lu5VHvwicqdMuuiaibpMPRStnzBXK80brlfA9erkfMWbyCIF1ggWED0pv5UDswpQ9smFwMq5dOJuSz18344g7w/0',1461225688,1461225688,0),(4,'oGh9uwbY99GIBObZhNI8GnPpEEZw','芑芥',1,'','中国','','zh_CN','http://wx.qlogo.cn/mmopen/qlpfnZRbHJHjVCKTqICNz8AqKNs8RgCrXAorPYdkFicxGUMb56dz7pCsX3DwscklqibcrLL588feiaOojHuzzq37sBnmtLNc5Wn/0',1461231780,1461231780,0);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
