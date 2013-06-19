-- phpMyAdmin SQL Dump
-- version 3.4.8
-- http://www.phpmyadmin.net
--
-- 主机: 127.0.0.1
-- 生成日期: 2013 年 06 月 19 日 23:41
-- 服务器版本: 5.6.10
-- PHP 版本: 5.3.15

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- 数据库: `club121`
--

-- --------------------------------------------------------

--
-- 表的结构 `club121_renren_connect`
--

CREATE TABLE IF NOT EXISTS `club121_renren_connect` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT 'user_id',
  `access_token` text NOT NULL,
  `refresh_token` text NOT NULL,
  `expires_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `club121_task`
--

CREATE TABLE IF NOT EXISTS `club121_task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT 'user_id',
  `condition` varchar(100) NOT NULL COMMENT '条件模块',
  `last_condition` text NOT NULL COMMENT '上次执行后的条件内容',
  `command` varchar(100) NOT NULL COMMENT '执行模块',
  `status` int(2) NOT NULL DEFAULT '1' COMMENT '任务状态，0不可用，1可用',
  `add_time` int(11) NOT NULL COMMENT '添加任务时间戳',
  `last_time` int(11) NOT NULL COMMENT '上次执行执行',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `club121_user`
--

CREATE TABLE IF NOT EXISTS `club121_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nickname` text NOT NULL COMMENT '昵称',
  `renren_id` int(18) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
