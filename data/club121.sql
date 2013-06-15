-- phpMyAdmin SQL Dump
-- version 3.4.8
-- http://www.phpmyadmin.net
--
-- 主机: 127.0.0.1
-- 生成日期: 2013 年 06 月 15 日 18:47
-- 服务器版本: 5.6.10
-- PHP 版本: 5.3.15

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `club121`
--

-- --------------------------------------------------------

--
-- 表的结构 `club121_renren_connect`
--

CREATE TABLE IF NOT EXISTS `club121_renren_connect` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL COMMENT 'user_id',
  `access_token` text NOT NULL,
  `refresh_token` text NOT NULL,
  `expires_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `club121_user`
--

CREATE TABLE IF NOT EXISTS `club121_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nickname` text NOT NULL COMMENT '昵称',
  `renren_id` mediumint(18) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
