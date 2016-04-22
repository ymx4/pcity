-- phpMyAdmin SQL Dump
-- version 4.4.15.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2016-04-22 16:08:01
-- 服务器版本： 5.5.47-MariaDB
-- PHP Version: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pcity`
--

-- --------------------------------------------------------

--
-- 表的结构 `admin_member`
--

DROP TABLE IF EXISTS `admin_member`;
CREATE TABLE IF NOT EXISTS `admin_member` (
  `uid` int(11) unsigned NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `admin_member`
--

INSERT INTO `admin_member` (`uid`, `username`, `password`) VALUES
(1, 'admin', '626e2ba2c7a3dc2b9ffe793076ec8c5f4d76c927');

-- --------------------------------------------------------

--
-- 表的结构 `announcement`
--

DROP TABLE IF EXISTS `announcement`;
CREATE TABLE IF NOT EXISTS `announcement` (
  `id` int(11) unsigned NOT NULL,
  `title` varchar(64) NOT NULL DEFAULT '',
  `file_name` varchar(64) NOT NULL DEFAULT '',
  `content` varchar(1000) DEFAULT '',
  `create_time` int(11) unsigned DEFAULT '0',
  `update_time` int(11) unsigned DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `group`
--

DROP TABLE IF EXISTS `group`;
CREATE TABLE IF NOT EXISTS `group` (
  `id` int(11) unsigned NOT NULL,
  `title` varchar(64) NOT NULL DEFAULT '',
  `create_time` int(11) unsigned DEFAULT '0',
  `update_time` int(11) unsigned DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `group_role`
--

DROP TABLE IF EXISTS `group_role`;
CREATE TABLE IF NOT EXISTS `group_role` (
  `group_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `role` tinyint(1) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `task`
--

DROP TABLE IF EXISTS `task`;
CREATE TABLE IF NOT EXISTS `task` (
  `id` int(11) unsigned NOT NULL,
  `title` varchar(64) NOT NULL DEFAULT '',
  `content` varchar(1000) NOT NULL DEFAULT '',
  `create_time` int(11) unsigned DEFAULT '0',
  `update_time` int(11) unsigned DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `template`
--

DROP TABLE IF EXISTS `template`;
CREATE TABLE IF NOT EXISTS `template` (
  `id` int(11) unsigned NOT NULL,
  `title` varchar(64) NOT NULL DEFAULT '',
  `category` varchar(61) NOT NULL DEFAULT '',
  `image` varchar(1000) NOT NULL DEFAULT '',
  `process_requirement` varchar(1000) NOT NULL DEFAULT '',
  `acceptance_criteria` varchar(1000) NOT NULL DEFAULT '',
  `manage_level` varchar(1000) NOT NULL DEFAULT '',
  `location` varchar(1000) NOT NULL DEFAULT '',
  `create_time` int(11) unsigned DEFAULT '0',
  `update_time` int(11) unsigned DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `wx_data_store`
--

DROP TABLE IF EXISTS `wx_data_store`;
CREATE TABLE IF NOT EXISTS `wx_data_store` (
  `app_id` varchar(100) NOT NULL,
  `data` varchar(255) DEFAULT NULL,
  `type` varchar(20) NOT NULL,
  `create_time` int(11) DEFAULT NULL,
  `expire_time` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `wx_user`
--

DROP TABLE IF EXISTS `wx_user`;
CREATE TABLE IF NOT EXISTS `wx_user` (
  `id` int(11) NOT NULL,
  `openid` varchar(255) DEFAULT NULL,
  `nickname` varchar(100) DEFAULT NULL,
  `sex` tinyint(4) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `language` varchar(255) DEFAULT NULL,
  `headimgurl` varchar(255) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `wx_user`
--

INSERT INTO `wx_user` (`id`, `openid`, `nickname`, `sex`, `city`, `country`, `province`, `language`, `headimgurl`, `create_time`, `update_time`) VALUES
(1, 'oGh9uwZ3H0v9l993Ozy3kv3hRqe0', 'Hypnos', 1, '苏州', '中国', '江苏', 'zh_CN', 'http://wx.qlogo.cn/mmopen/Q3auHgzwzM6dIVaJAM9sZZYuzicvTOdDyHWLyI2YE31VomQayJlI5GdApyUBsuc3TickJLq9FzGklz0LcMAr4BoSDCDHiaIjTxqelTdydYwqZ8/0', 1461224490, 1461224500),
(2, 'oGh9uweuonaX9YNwGpo55z9Ol3AM', '流云飞扬', 1, '苏州', '中国', '江苏', 'zh_CN', 'http://wx.qlogo.cn/mmopen/LCWtHyvj1E2Fiav7N72j907iblqeVH64TNO8MZ4ekCicZkvYicVMsbNLFXmKxoCM9Ngq4VPSAbX3qHKsVEibOKSCLQHUqeUtSmJZk/0', 1461224978, 1461224978),
(3, 'oGh9uwaQn8GdJQAHdv6nADfrCUkw', 'KAME', 1, '苏州', '中国', '江苏', 'zh_CN', 'http://wx.qlogo.cn/mmopen/jT2lu5VHvwicqdMuuiaibpMPRStnzBXK80brlfA9erkfMWbyCIF1ggWED0pv5UDswpQ9smFwMq5dOJuSz18344g7w/0', 1461225688, 1461225688),
(4, 'oGh9uwbY99GIBObZhNI8GnPpEEZw', '芑芥', 1, '', '中国', '', 'zh_CN', 'http://wx.qlogo.cn/mmopen/qlpfnZRbHJHjVCKTqICNz8AqKNs8RgCrXAorPYdkFicxGUMb56dz7pCsX3DwscklqibcrLL588feiaOojHuzzq37sBnmtLNc5Wn/0', 1461231780, 1461231780);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_member`
--
ALTER TABLE `admin_member`
  ADD PRIMARY KEY (`uid`),
  ADD UNIQUE KEY `u_username` (`username`);

--
-- Indexes for table `announcement`
--
ALTER TABLE `announcement`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `group`
--
ALTER TABLE `group`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `group_role`
--
ALTER TABLE `group_role`
  ADD PRIMARY KEY (`group_id`,`user_id`);

--
-- Indexes for table `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `template`
--
ALTER TABLE `template`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wx_data_store`
--
ALTER TABLE `wx_data_store`
  ADD PRIMARY KEY (`app_id`,`type`);

--
-- Indexes for table `wx_user`
--
ALTER TABLE `wx_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `openid` (`openid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_member`
--
ALTER TABLE `admin_member`
  MODIFY `uid` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `announcement`
--
ALTER TABLE `announcement`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `group`
--
ALTER TABLE `group`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `task`
--
ALTER TABLE `task`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `template`
--
ALTER TABLE `template`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `wx_user`
--
ALTER TABLE `wx_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
