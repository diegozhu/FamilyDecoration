-- phpMyAdmin SQL Dump
-- version 3.3.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 09, 2014 at 03:25 AM
-- Server version: 5.1.50
-- PHP Version: 5.3.14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `familydecoration`
--
DROP DATABASE `familydecoration`;
CREATE DATABASE `familydecoration` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `familydecoration`;

-- --------------------------------------------------------

--
-- Table structure for table `basic_item`
--

CREATE TABLE IF NOT EXISTS `basic_item` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `itemId` varchar(100) NOT NULL COMMENT '基础项目大项id',
  `itemName` varchar(100) NOT NULL COMMENT '基础项目大项名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `basic_item`
--

INSERT INTO `basic_item` (`id`, `itemId`, `itemName`) VALUES
(1, 'basic-201412061743412957', '吊顶工程'),
(2, 'basic-201412061745099855', '彭浩工程'),
(3, 'basic-201412061745209628', '测试工程'),
(4, 'basic-201412061745208299', '测试工程1');

-- --------------------------------------------------------

--
-- Table structure for table `basic_sub_item`
--

CREATE TABLE IF NOT EXISTS `basic_sub_item` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `subItemId` varchar(120) NOT NULL COMMENT '基础项目子项id',
  `subItemName` varchar(100) NOT NULL COMMENT '基础项目子项名称',
  `subItemUnit` varchar(20) NOT NULL,
  `mainMaterialPrice` double NOT NULL COMMENT '基础项目子项主材单价',
  `auxiliaryMaterialPrice` double NOT NULL COMMENT '基础项目子项辅材单价',
  `manpowerPrice` double NOT NULL COMMENT '基础项目子项人工单价',
  `machineryPrice` double NOT NULL COMMENT '基础项目子项机械单价',
  `lossPercent` double DEFAULT NULL COMMENT '基础项目子项损耗百分比',
  `parentId` varchar(100) NOT NULL COMMENT '基础项目大项的itemId',
  `cost` double NOT NULL COMMENT '基础子项目成本',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `basic_sub_item`
--

INSERT INTO `basic_sub_item` (`id`, `subItemId`, `subItemName`, `subItemUnit`, `mainMaterialPrice`, `auxiliaryMaterialPrice`, `manpowerPrice`, `machineryPrice`, `lossPercent`, `parentId`, `cost`) VALUES
(1, 'basic-sub-201412061744566090', '家装吊顶', '㎡', 1.123, 3.12, 4.125, 1.312, 0.14, 'basic-201412061743412957', 1.01),
(2, 'basic-sub-201412061744562808', '屋顶装修', '㎡', 3.12, 9.12, 11.234, 10.98, 0.1, 'basic-201412061743412957', 3.01),
(3, 'basic-sub-201412061746241275', '美工', 'ml', 1.31, 9.1, 11.21, 0.13, 0.1, 'basic-201412061745099855', 0.987),
(4, 'basic-sub-201412061746245245', '美化', 'km', 10.12, 9.12, 11.21, 11.98, 0.901, 'basic-201412061745099855', 0),
(5, 'basic-sub-201412061747255522', '测试名称1', 'hh', 12.1, 2.01, 0.11, 9.101, 0, 'basic-201412061745209628', 0),
(6, 'basic-sub-201412061747257423', '测试名称2', 'hl', 109.1, 89.1, 0.123, 98.1, 0.001, 'basic-201412061745209628', 0.1);

-- --------------------------------------------------------

--
-- Table structure for table `budget`
--

CREATE TABLE IF NOT EXISTS `budget` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `budgetId` varchar(30) DEFAULT NULL,
  `custName` varchar(45) NOT NULL,
  `projectName` varchar(45) DEFAULT NULL,
  `areaSize` varchar(45) DEFAULT NULL,
  `totalFee` varchar(45) DEFAULT NULL,
  `comments` varchar(4500) DEFAULT NULL,
  `isDeleted` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bugetId` (`budgetId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `budget`
--


-- --------------------------------------------------------

--
-- Table structure for table `budget_item`
--

CREATE TABLE IF NOT EXISTS `budget_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `budgetItemId` varchar(50) DEFAULT NULL,
  `itemName` varchar(45) NOT NULL,
  `budgetId` varchar(30) NOT NULL,
  `itemCode` varchar(45) DEFAULT NULL,
  `itemUnit` varchar(45) DEFAULT NULL,
  `itemAmount` double DEFAULT NULL,
  `mainMaterialPrice` double DEFAULT NULL,
  `auxiliaryMaterialPrice` double DEFAULT NULL,
  `manpowerPrice` double DEFAULT NULL,
  `machineryPrice` double DEFAULT NULL,
  `lossPercent` double DEFAULT NULL,
  `isDeleted` varchar(5) DEFAULT NULL,
  `remark` text COMMENT '备注多行',
  PRIMARY KEY (`id`),
  KEY `buget_item_buget_idx` (`budgetId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `budget_item`
--


-- --------------------------------------------------------

--
-- Table structure for table `bulletin`
--

CREATE TABLE IF NOT EXISTS `bulletin` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `bulletin`
--

INSERT INTO `bulletin` (`id`, `content`) VALUES
(1, '%u6D4B%u8BD5%u516C%u544A'),
(2, '%u5404%u90E8%u95E8%u6CE8%u610F%uFF1A%0A%20%20%20%20%20%20%20%20%20%20%20%20%u5168%u529B%u524D%u8FDB');

-- --------------------------------------------------------

--
-- Table structure for table `chart`
--

CREATE TABLE IF NOT EXISTS `chart` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `chartId` varchar(40) NOT NULL,
  `chartCategory` varchar(100) NOT NULL,
  `chartContent` mediumtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `chart`
--

INSERT INTO `chart` (`id`, `chartId`, `chartCategory`, `chartContent`) VALUES
(13, 'chart-201411191102082014', '欧式家装', ''),
(14, 'chart-201411191110147930', '田园风情', '');

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE IF NOT EXISTS `project` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `projectId` varchar(20) NOT NULL COMMENT '工程项目id',
  `projectName` varchar(100) NOT NULL COMMENT '工程项目名称',
  `projectProgress` text COMMENT '工程进度',
  `projectChart` mediumtext COMMENT '工程图片',
  `projectTime` datetime NOT NULL,
  `budgetId` varchar(30) DEFAULT NULL COMMENT 'corresponding budget',
  `isFrozen` tinyint(2) NOT NULL COMMENT '是否为死单，默认不是',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `project`
--

INSERT INTO `project` (`id`, `projectId`, `projectName`, `projectProgress`, `projectChart`, `projectTime`, `budgetId`, `isFrozen`) VALUES
(1, '201411151611584156', '天棚工程', '培训完成，开始动工<>人员培训<>动工完成<>完成设计图纸<>开始刷墙<>开始动工<>开始动工<>材料进场<>设计重构', '../resources/chart/201411151611584156/201411261222261285.jpg||1.jpg<>../resources/chart/201411151611584156/201411261222264694.jpg||2.jpg<>../resources/chart/201411151611584156/201411261222268686.jpg||3.jpg<>../resources/chart/201411151611584156/201411261222266970.jpg||4.jpg<>../resources/chart/201411151611584156/201411261222265588.jpg||5.jpg<>../resources/chart/201411151611584156/201411261222265388.jpg||6.jpg<>../resources/chart/201411151611584156/201411261222264630.jpg||7.jpg<>../resources/chart/201411151611584156/201411261222266032.jpg||8.jpg<>../resources/chart/201411151611584156/201411261222261744.png||QQ Photo20141028165545.png<>../resources/chart/201411151611584156/201411261222262935.jpg||项目进度界面1.jpg<>../resources/chart/201411151611584156/201411261222268948.jpg||项目进度界面2.jpg<>../resources/chart/201411151611584156/201411261222265687.jpg||项目进度界面3.jpg<>../resources/chart/201411151611584156/201411261222265283.jpg||项目进度界面4.jpg<>../resources/chart/201411151611584156/201411261222263334.jpg||预算界面1.jpg<>../resources/chart/201411151611584156/201411261222267448.jpg||预算界面2.jpg<>../resources/chart/201411151611584156/201411261222267650.jpg||预算界面3.jpg<>../resources/chart/201411151611584156/201411261222266712.jpg||预算界面4.jpg<>../resources/chart/201411151611584156/201411261222267829.jpg||预算界面5.jpg<>../resources/chart/201411151611584156/201411261222263660.jpg||预算界面6.jpg', '2014-04-15 00:00:00', 'NULL', 1),
(2, '201411151617138306', '西山美素', '人员资金链到位<>家装工程人员开始设计<>人员装备到齐<>材料入场', '1', '2011-06-15 00:00:00', 'NULL', 1),
(3, '201412062107494872', '测试工程', '', '', '2014-12-06 00:00:00', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `level` varchar(2) NOT NULL COMMENT '用户等级',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `password`, `level`) VALUES
(1, 'admin', '858c86a3843be5e3001b7db637cb67ab', '1'),
(2, 'test', '9dbe87d7bcd06079e681b60d5e7c43b9', '2'),
(3, 'visitor', '9a96de2483722aed08b4b190568a425a', '3'),
(8, 'lll', '3899417b88bdc0aae5ccfcdce0126ca2', '3');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `budget_item`
--
ALTER TABLE `budget_item`
  ADD CONSTRAINT `buget_item_buget` FOREIGN KEY (`budgetId`) REFERENCES `budget` (`budgetId`) ON DELETE NO ACTION ON UPDATE NO ACTION;
