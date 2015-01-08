-- MySQL dump 10.13  Distrib 5.1.50, for Win32 (ia32)
--
-- Host: localhost    Database: familydecoration
-- ------------------------------------------------------
-- Server version	5.1.50-community

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `basic_item`
--

DROP TABLE IF EXISTS `basic_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `basic_item` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `itemId` varchar(100) NOT NULL COMMENT '基础项目大项id',
  `itemName` varchar(100) NOT NULL COMMENT '基础项目大项名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `basic_item`
--

LOCK TABLES `basic_item` WRITE;
/*!40000 ALTER TABLE `basic_item` DISABLE KEYS */;
INSERT INTO `basic_item` VALUES (1,'basic-201412061743412957','吊顶工程'),(2,'basic-201412061745099855','彭浩工程'),(3,'basic-201412061745209628','测试工程'),(4,'basic-201412061745208299','测试工程1'),(5,'basic-201412241009184445','电子设备工程'),(6,'basic-201412241009184440','桌面工程'),(7,'basic-201412241009181369','喷漆工程'),(8,'basic-201412241009187201','电灯工程'),(9,'basic-201412241009184474','汽车工程'),(10,'basic-201412241009185763','玻璃工程'),(11,'basic-201412241009184112','室内工程');
/*!40000 ALTER TABLE `basic_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `basic_sub_item`
--

DROP TABLE IF EXISTS `basic_sub_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `basic_sub_item` (
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
  `remark` text NOT NULL COMMENT '多行备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `basic_sub_item`
--

LOCK TABLES `basic_sub_item` WRITE;
/*!40000 ALTER TABLE `basic_sub_item` DISABLE KEYS */;
INSERT INTO `basic_sub_item` VALUES (1,'basic-sub-201412061744566090','家装吊顶','㎡',1.123,3.12,4.125,1.312,0.14,'basic-201412061743412957',1.01,'1、50系列U型轻钢龙骨或木条龙骨.2、9mm厚纸面石膏板罩面，自攻螺丝钉固定.3、按展开平面面积计算工程量，不足1㎡按1㎡计算.4、窗帘盒另计.'),(2,'basic-sub-201412061744562808','屋顶装修','㎡',3.12,9.12,11.234,10.98,0.1,'basic-201412061743412957',3.01,''),(3,'basic-sub-201412061746241275','美工','ml',1.31,9.1,11.21,0.13,0.1,'basic-201412061745099855',0.987,''),(4,'basic-sub-201412061746245245','美化','km',10.12,9.12,11.21,11.98,0.901,'basic-201412061745099855',0,''),(5,'basic-sub-201412061747255522','测试名称1','hh',12.1,2.01,0.11,9.101,0,'basic-201412061745209628',0,''),(6,'basic-sub-201412061747257423','测试名称2','hl',109.1,89.1,0.123,98.1,0.001,'basic-201412061745209628',0.1,''),(7,'basic-sub-201412241015528280','顶部翻新','m',0.9,2.2,54,6,0.1,'basic-201412061743412957',0.4,''),(8,'basic-sub-201412241015521911','顶部装潢','m',9,3.9,0.14,7,0.2,'basic-201412061743412957',7,''),(9,'basic-sub-201412241015523660','顶梁切除','m',24.9,0.1,0.1,6.31,0.1,'basic-201412061743412957',14,''),(10,'basic-sub-201412241015526470','名字1','m',2.3,0.1,4.45,8.6,0.4,'basic-201412061743412957',1.1,''),(11,'basic-sub-201412241015524962','名字2','f',0.3,0.7,0.54,0.4,0.7,'basic-201412061743412957',0.1,''),(12,'basic-sub-201412241015526366','名字3','m',0.4,10.2,0.2,4.6,0.1,'basic-201412061743412957',0,''),(13,'basic-sub-201412241015528970','名字4','a',5,2.4,0.1,0.41,0.01,'basic-201412061743412957',0.5,''),(14,'basic-sub-201412241136392687','玻璃1','h',1.21,21.2,0.21,0.21,0.3,'basic-201412241009185763',1.01,''),(15,'basic-sub-201412241138575707','玻璃2','k1',12.12,31.1,12.1,41.1,0.2,'basic-201412241009185763',11,''),(16,'basic-sub-201412241138577250','玻璃3','a',12.31,12.21,12.1,19.12,0.11,'basic-201412241009185763',9.1,''),(17,'basic-sub-201412241138571101','玻璃4','o',91,10.2,0.21,2.3,0.08,'basic-201412241009185763',80,''),(18,'basic-sub-201412241140029652','汽车1','p',10021.5,1990.2,890.12,980.12,0.2,'basic-201412241009184474',10000.12,''),(19,'basic-sub-201412241140025042','汽车2','i',9809,980,2019,871,0.31,'basic-201412241009184474',9000,''),(20,'basic-sub-201412241140509295','热备盘','m',31,10.21,11.2,19.1,0.1,'basic-201412241009184445',6,''),(21,'basic-sub-201412241141158187','桌面','k',21,91,12,21,0.2,'basic-201412241009184440',10,''),(22,'basic-sub-201412241141474234','墙上漆','px',12,213,123,113,0.91,'basic-201412241009181369',0.21,''),(23,'basic-sub-201412241142148890','灯泡','个',0.12,21,21.31,10.2,0.21,'basic-201412241009187201',0.01,''),(24,'basic-sub-201501041354226726','小项测试1','df',0,0,0,0,0,'basic-201412061745208299',0,'asfdas\ndfasdfasdfa\nsdfasdf\n1234\n123'),(25,'basic-sub-201501041356086266','小项测试1','df',0,0,0,0,0,'basic-201412061745208299',0,'asfd\nasdfasdfasdf\nasdf\nasdf'),(26,'basic-sub-201501041417323382','asdfa','asfa',0,0,0,0,0,'basic-201412241009187201',0,'asdfasdfa\nsd\nfa\nsd\nfa\ns\ndf\na\n'),(27,'basic-sub-201501041417323509','asdfas','sdfa',0,0,0,0,0,'basic-201412241009187201',0,'asdfasdfasdfasdfjalksjdlkfjalksdjlfkajlsdjfal');
/*!40000 ALTER TABLE `basic_sub_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `budget`
--

DROP TABLE IF EXISTS `budget`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `budget` (
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `budget`
--

LOCK TABLES `budget` WRITE;
/*!40000 ALTER TABLE `budget` DISABLE KEYS */;
INSERT INTO `budget` VALUES (1,'budget-201412241230051238','李先生','测试工程','102','2256.78964','阿斯顿发手机的路口附近阿拉斯加的立方阿加思考的>>><<<爱睡觉了快点交罚款辣椒水老地方加速度啊','false'),(2,'budget-201412301726085628','test','西山美墅','12342','5016.14432','asdfasdfa','false'),(3,'budget-201501041440302265','asdfasdaf','天鹏工程','11111','10.27402','fasdfasdfa','false');
/*!40000 ALTER TABLE `budget` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `budget_item`
--

DROP TABLE IF EXISTS `budget_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `budget_item` (
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
  `basicItemId` varchar(100) DEFAULT NULL COMMENT '基础项目大项的itemId',
  `basicSubItemId` varchar(120) DEFAULT NULL COMMENT '基础项目子项id',
  PRIMARY KEY (`id`),
  KEY `buget_item_buget_idx` (`budgetId`),
  CONSTRAINT `buget_item_buget` FOREIGN KEY (`budgetId`) REFERENCES `budget` (`budgetId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `budget_item`
--

LOCK TABLES `budget_item` WRITE;
/*!40000 ALTER TABLE `budget_item` DISABLE KEYS */;
INSERT INTO `budget_item` VALUES (1,'budget-item-201412241230054209','吊顶工程','budget-201412241230051238','A','NULL',0,0,0,0,0,0,'true','NULL','basic-201412061743412957','NULL'),(2,'budget-item-201412241230057020','家装吊顶','budget-201412241230051238','A-1','㎡',1.2,1.123,3.12,4.125,1.312,0.59402,'true','阿拉斯加懒得开房间爱丽丝顶梁\n爱健康路发生的几率发卡机螺丝钉放','basic-201412061743412957','basic-sub-201412061744566090'),(3,'budget-item-201412241230055761','屋顶装修','budget-201412241230051238','A-2','㎡',13.4,3.12,9.12,11.234,10.98,1.224,'true','NULL','basic-201412061743412957','basic-sub-201412061744562808'),(4,'budget-item-201412241230054501','顶部翻新','budget-201412241230051238','A-3','m',10.1,0.9,2,54,6,0.29,'true','阿斯顿发老师讲道理阿斯蒂芬绿卡','basic-201412061743412957','basic-sub-201412241015528280'),(5,'budget-item-201412241230055745','顶部装潢','budget-201412241230051238','A-4','m',1.3,9,3.9,0.14,7,2.58,'true','NULL','basic-201412061743412957','basic-sub-201412241015521911'),(6,'budget-item-201412241230057405','顶梁切除','budget-201412241230051238','A-5','m',0.3,24.9,0.1,0.1,6.31,2.5,'true','NULL','basic-201412061743412957','basic-sub-201412241015523660'),(7,'budget-item-201412241230051597','名字1','budget-201412241230051238','A-6','m',11,2.3,0.1,4.45,8.6,0.96,'true','阿斯顿飞机阿拉卡三等奖发了是的','basic-201412061743412957','basic-sub-201412241015526470'),(8,'budget-item-201412241230057903','名字2','budget-201412241230051238','A-6','f',1.3,0.3,0.7,0.54,0.4,0.7,'true','NULL','basic-201412061743412957','basic-sub-201412241015524962'),(9,'budget-item-201412241230059944','名字3','budget-201412241230051238','A-7','m',1.3,0.4,10.2,0.2,4.6,1.06,'true','案件少两块豆腐讲我爱睡觉都疯了','basic-201412061743412957','basic-sub-201412241015526366'),(10,'budget-item-201412241230051312','名字4','budget-201412241230051238','A-8','a',14.1,5,2.4,0.1,0.41,0.074,'true','NULL','basic-201412061743412957','basic-sub-201412241015528970'),(11,'budget-item-201412241230051936','彭浩工程','budget-201412241230051238','B','NULL',0,0,0,0,0,0,'true','NULL','basic-201412061745099855','NULL'),(12,'budget-item-201412241230052074','美工','budget-201412241230051238','B-1','ml',1.3,1.31,9.1,11.21,0.13,1.041,'true','NULL','basic-201412061745099855','basic-sub-201412061746241275'),(13,'budget-item-201412241230058496','美化','budget-201412241230051238','B-2','km',1.4,10.12,9.12,11.21,11.98,17.33524,'true','阿斯顿飞机阿拉斯加单份暗恋','basic-201412061745099855','basic-sub-201412061746245245'),(14,'budget-item-201412241230052557','测试工程','budget-201412241230051238','C','NULL',0,0,0,0,0,0,'true','NULL','basic-201412061745209628','NULL'),(15,'budget-item-201412241230053072','测试名称1','budget-201412241230051238','C-1','hh',23.1,12.1,2.01,0.11,9.101,0,'true','NULL','basic-201412061745209628','basic-sub-201412061747255522'),(16,'budget-item-201412241230051253','测试名称2','budget-201412241230051238','C-2','hl',0.4,109.1,89.1,0.123,98.1,0.1982,'true','NULL','basic-201412061745209628','basic-sub-201412061747257423'),(17,'budget-item-201412241230057428','工程直接费','budget-201412241230051238','N','元',1,620.870146,0,0,0,0,'false','NULL','NULL','NULL'),(18,'budget-item-201412241230051938','设计费3%','budget-201412241230051238','O','元',0.31,192.46974526,0,0,0,0,'false','NULL','NULL','NULL'),(19,'budget-item-201412241230056654','效果图','budget-201412241230051238','P','张',4,2000,0,0,0,0,'false','NULL','NULL','NULL'),(20,'budget-item-201412241230052349','5%管理费','budget-201412241230051238','Q','元',0.05,31.0435073,0,0,0,0,'false','NULL','NULL','NULL'),(21,'budget-item-201412241230051749','0%税金','budget-201412241230051238','R','元',0.08,49.66961168,0,0,0,0,'false','NULL','NULL','NULL'),(22,'budget-item-201412241230059936','工程总造价','budget-201412241230051238','S','元',1,2894.05301024,0,0,0,0,'false','NULL','NULL','NULL'),(23,'budget-item-201412241235533660','测试名称1','budget-201412241230051238','C-1','hh',0,12.1,2.01,0.11,9.101,0,'true','NULL','basic-201412061745209628','basic-sub-201412061747255522'),(24,'budget-item-201412241247511000','吊顶工程','budget-201412241230051238','A','NULL',0,0,0,0,0,0,'false','NULL','basic-201412061743412957','NULL'),(25,'budget-item-201412241247517049','家装吊顶','budget-201412241230051238','A-1','㎡',1.3,1.123,3.12,4.125,1.312,0.59402,'false','asdkfasl;dkf;aks;dkfa;\nasdfjalksjdflajls','basic-201412061743412957','basic-sub-201412061744566090'),(26,'budget-item-201412241247511933','屋顶装修','budget-201412241230051238','A-2','㎡',12.31,3.12,9.12,11.234,10.98,1.224,'false','sdjfjlkajslkjflkjalskdjk','basic-201412061743412957','basic-sub-201412061744562808'),(27,'budget-item-201412241247514438','顶部翻新','budget-201412241230051238','A-3','m',1,0.9,2,54,6,0.29,'false','asdflajsdjflajsdlk','basic-201412061743412957','basic-sub-201412241015528280'),(28,'budget-item-201412241247515055','顶部装潢','budget-201412241230051238','A-4','m',0,9,3.9,0.14,7,2.58,'false','NULL','basic-201412061743412957','basic-sub-201412241015521911'),(29,'budget-item-201412241247513460','顶梁切除','budget-201412241230051238','A-5','m',0.2,24.9,0.1,0.1,6.31,2.5,'false','NULL','basic-201412061743412957','basic-sub-201412241015523660'),(30,'budget-item-201412241247516536','名字1','budget-201412241230051238','A-6','m',0.31,2.3,0.1,4.45,8.6,0.96,'false','NULL','basic-201412061743412957','basic-sub-201412241015526470'),(31,'budget-item-201412241247516885','名字2','budget-201412241230051238','A-7','f',0,0.3,0.7,0.54,0.4,0.7,'false','看下新装的adobe reader','basic-201412061743412957','basic-sub-201412241015524962'),(32,'budget-item-201412241247516425','名字3','budget-201412241230051238','A-8','m',2,0.4,10.2,0.2,4.6,1.06,'false','NULL','basic-201412061743412957','basic-sub-201412241015526366'),(33,'budget-item-201412241247511301','名字4','budget-201412241230051238','A-9','a',0.41,5,2.4,0.1,0.41,0.074,'false','NULL','basic-201412061743412957','basic-sub-201412241015528970'),(34,'budget-item-201412241254303942','电子设备工程','budget-201412241230051238','B','NULL',0,0,0,0,0,0,'false','NULL','basic-201412241009184445','NULL'),(35,'budget-item-201412241254303270','热备盘','budget-201412241230051238','B-1','m',0,31,10.21,11.2,19.1,4.121,'false','NULL','basic-201412241009184445','basic-sub-201412241140509295'),(36,'budget-item-201412241254379820','室内工程','budget-201412241230051238','C','NULL',0,0,0,0,0,0,'true','NULL','basic-201412241009184112','NULL'),(37,'budget-item-201412241259322651','电灯工程','budget-201412241230051238','C','NULL',0,0,0,0,0,0,'false','NULL','basic-201412241009187201','NULL'),(38,'budget-item-201412241259321311','灯泡','budget-201412241230051238','C-1','个',1,0.12,21,21.31,10.2,4.4352,'false','NULL','basic-201412241009187201','basic-sub-201412241142148890'),(39,'budget-item-201412301726081859','吊顶工程','budget-201412301726085628','A','NULL',0,0,0,0,0,0,'false','NULL','basic-201412061743412957','NULL'),(40,'budget-item-201412301726084424','家装吊顶','budget-201412301726085628','A-1','㎡',1,1.123,3.12,4.125,1.312,0.59402,'false','NULL','basic-201412061743412957','basic-sub-201412061744566090'),(41,'budget-item-201412301726087195','屋顶装修','budget-201412301726085628','A-2','㎡',0.3,3.12,9.12,11.234,10.98,1.224,'false','NULL','basic-201412061743412957','basic-sub-201412061744562808'),(42,'budget-item-201412301726081923','顶部翻新','budget-201412301726085628','A-3','m',0,0.9,2,54,6,0.29,'false','sajdfjalsjdlfa<br />asdjkfalsdjl<br />丁\n俊晖将客户肯定好看','basic-201412061743412957','basic-sub-201412241015528280'),(43,'budget-item-201412301726088012','顶部装潢','budget-201412301726085628','A-4','m',0,9,3.9,0.14,7,2.58,'false','NULL','basic-201412061743412957','basic-sub-201412241015521911'),(44,'budget-item-201412301726083363','顶梁切除','budget-201412301726085628','A-5','m',0,24.9,0.1,0.1,6.31,2.5,'false','sdfajsldkfjla\nasjdfjalksdjlaf','basic-201412061743412957','basic-sub-201412241015523660'),(45,'budget-item-201412301726082992','名字1','budget-201412301726085628','A-6','m',123.1,2.3,0.1,4.45,8.6,0.96,'false','NULL','basic-201412061743412957','basic-sub-201412241015526470'),(46,'budget-item-201412301726083737','名字2','budget-201412301726085628','A-7','f',0,0.3,0.7,0.54,0.4,0.7,'false','NULL','basic-201412061743412957','basic-sub-201412241015524962'),(47,'budget-item-201412301726088891','名字3','budget-201412301726085628','A-8','m',1,0.4,10.2,0.2,4.6,1.06,'false','NULL','basic-201412061743412957','basic-sub-201412241015526366'),(48,'budget-item-201412301726082262','名字4','budget-201412301726085628','A-9','a',0,5,2.4,0.1,0.41,0.074,'false','NULL','basic-201412061743412957','basic-sub-201412241015528970'),(49,'budget-item-201412301726088265','测试工程','budget-201412301726085628','B','NULL',0,0,0,0,0,0,'false','NULL','basic-201412061745209628','NULL'),(50,'budget-item-201412301726089367','测试名称1','budget-201412301726085628','B-1','hh',13.1,12.1,2.01,0.11,9.101,0,'false','asjdfjaklsjdlfaj','basic-201412061745209628','basic-sub-201412061747255522'),(51,'budget-item-201412301726089864','测试名称2','budget-201412301726085628','B-2','hl',9,109.1,89.1,0.123,98.1,0.1982,'false','NULL','basic-201412061745209628','basic-sub-201412061747257423'),(52,'budget-item-201412301726081766','工程直接费','budget-201412301726085628','N','元',1,5032.60432,0,0,0,0,'false','NULL','NULL','NULL'),(53,'budget-item-201412301726085768','设计费3%','budget-201412301726085628','O','元',0.3,1509.781296,0,0,0,0,'false','NULL','NULL','NULL'),(54,'budget-item-201412301726084168','效果图','budget-201412301726085628','P','张',11,5500,0,0,0,0,'false','NULL','NULL','NULL'),(55,'budget-item-201412301726084321','5%管理费','budget-201412301726085628','Q','元',0.05,251.630216,0,0,0,0,'false','NULL','NULL','NULL'),(56,'budget-item-201412301726085205','0%税金','budget-201412301726085628','R','元',0.3,1509.781296,0,0,0,0,'false','NULL','NULL','NULL'),(57,'budget-item-201412301726084934','工程总造价','budget-201412301726085628','S','元',1,13803.797128,0,0,0,0,'false','NULL','NULL','NULL'),(58,'budget-item-201501041440305398','吊顶工程','budget-201501041440302265','A','NULL',0,0,0,0,0,0,'false','NULL','basic-201412061743412957','NULL'),(59,'budget-item-201501041440304307','家装吊顶','budget-201501041440302265','A-1','㎡',1,1.123,3.12,4.125,1.312,0.59402,'false','测试下\n换行','basic-201412061743412957','basic-sub-201412061744566090'),(60,'budget-item-201501041440308168','屋顶装修','budget-201501041440302265','A-2','㎡',0,3.12,9.12,11.234,10.98,1.224,'false','NULL','basic-201412061743412957','basic-sub-201412061744562808'),(61,'budget-item-201501041440305624','顶部翻新','budget-201501041440302265','A-3','m',0,0.9,2,54,6,0.29,'false','NULL','basic-201412061743412957','basic-sub-201412241015528280'),(62,'budget-item-201501041440303222','顶部装潢','budget-201501041440302265','A-4','m',0,9,3.9,0.14,7,2.58,'false','asdfasdfa\nsd\nfa\nsd\nf\n','basic-201412061743412957','basic-sub-201412241015521911'),(63,'budget-item-201501041440301224','顶梁切除','budget-201501041440302265','A-5','m',0,24.9,0.1,0.1,6.31,2.5,'false','NULL','basic-201412061743412957','basic-sub-201412241015523660'),(64,'budget-item-201501041440306650','名字1','budget-201501041440302265','A-6','m',0,2.3,0.1,4.45,8.6,0.96,'false','NULL','basic-201412061743412957','basic-sub-201412241015526470'),(65,'budget-item-201501041440306918','名字2','budget-201501041440302265','A-7','f',0,0.3,0.7,0.54,0.4,0.7,'false','NULL','basic-201412061743412957','basic-sub-201412241015524962'),(66,'budget-item-201501041440309626','名字3','budget-201501041440302265','A-8','m',0,0.4,10.2,0.2,4.6,1.06,'false','NULL','basic-201412061743412957','basic-sub-201412241015526366'),(67,'budget-item-201501041440303144','名字4','budget-201501041440302265','A-9','a',0,5,2.4,0.1,0.41,0.074,'false','NULL','basic-201412061743412957','basic-sub-201412241015528970'),(68,'budget-item-201501041440305140','工程直接费','budget-201501041440302265','N','元',1,10.27402,0,0,0,0,'false','NULL','NULL','NULL'),(69,'budget-item-201501041440308128','设计费3%','budget-201501041440302265','O','元',0,0,0,0,0,0,'false','NULL','NULL','NULL'),(70,'budget-item-201501041440308904','效果图','budget-201501041440302265','P','张',0,0,0,0,0,0,'false','NULL','NULL','NULL'),(71,'budget-item-201501041440308477','5%管理费','budget-201501041440302265','Q','元',0.05,0.513701,0,0,0,0,'false','NULL','NULL','NULL'),(72,'budget-item-201501041440307222','0%税金','budget-201501041440302265','R','元',0,0,0,0,0,0,'false','NULL','NULL','NULL'),(73,'budget-item-201501041440302529','工程总造价','budget-201501041440302265','S','元',1,10.787721,0,0,0,0,'false','NULL','NULL','NULL');
/*!40000 ALTER TABLE `budget_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bulletin`
--

DROP TABLE IF EXISTS `bulletin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bulletin` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bulletin`
--

LOCK TABLES `bulletin` WRITE;
/*!40000 ALTER TABLE `bulletin` DISABLE KEYS */;
INSERT INTO `bulletin` VALUES (1,'%u6D4B%u8BD5%u516C%u544A'),(2,'%u5404%u90E8%u95E8%u6CE8%u610F%uFF1A%0A%20%20%20%20%20%20%20%20%20%20%20%20%u5168%u529B%u524D%u8FDB'),(3,'ajsdklfjajsdlfjalsdfasdfasdaf');
/*!40000 ALTER TABLE `bulletin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chart`
--

DROP TABLE IF EXISTS `chart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chart` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `chartId` varchar(40) NOT NULL,
  `chartCategory` varchar(100) NOT NULL,
  `chartContent` mediumtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chart`
--

LOCK TABLES `chart` WRITE;
/*!40000 ALTER TABLE `chart` DISABLE KEYS */;
INSERT INTO `chart` VALUES (13,'chart-201411191102082014','欧式家装',''),(14,'chart-201411191110147930','田园风情','');
/*!40000 ALTER TABLE `chart` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project`
--

DROP TABLE IF EXISTS `project`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `projectId` varchar(20) NOT NULL COMMENT '工程项目id',
  `projectName` varchar(100) NOT NULL COMMENT '工程项目名称',
  `projectProgress` text COMMENT '工程进度',
  `projectChart` mediumtext COMMENT '工程图片',
  `projectTime` datetime NOT NULL,
  `budgetId` varchar(30) DEFAULT NULL COMMENT 'corresponding budget',
  `isFrozen` tinyint(2) NOT NULL COMMENT '是否为死单，默认不是',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project`
--

LOCK TABLES `project` WRITE;
/*!40000 ALTER TABLE `project` DISABLE KEYS */;
INSERT INTO `project` VALUES (8,'201412301639279398','西山美墅','阿斯顿将法律卡加速度李开复阿斯顿解封卢卡斯的法律<>fasdfasdfa','1','2014-12-23 00:00:00','budget-201412301726085628',0),(9,'201412301639437839','天鹏工程','','','2011-12-07 00:00:00','budget-201501041440302265',0),(10,'201412301640035296','测试工程','fasdfasd<>asdfasdaf','','2012-12-06 00:00:00','budget-201412241230051238',0);
/*!40000 ALTER TABLE `project` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system`
--

DROP TABLE IF EXISTS `system`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `system` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `paramName` varchar(250) NOT NULL COMMENT '参数名',
  `paramValue` text COMMENT '参数值',
  `isDeleted` varchar(5) NOT NULL DEFAULT 'false' COMMENT '是否已删除',
  `createTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updateTime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `paramDesc` text NOT NULL COMMENT '参数描述',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='系统参数表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system`
--

LOCK TABLES `system` WRITE;
/*!40000 ALTER TABLE `system` DISABLE KEYS */;
INSERT INTO `system` VALUES (1,'adminpassword','admin:admin','false','2014-12-24 08:59:08','0000-00-00 00:00:00','管理员密码'),(2,'sessionId','taov710hjae1i7ueq53if9qhq3','false','2014-12-24 09:01:02','2015-01-08 09:11:51','用来限制admin用户同一时刻只能在一个地方登陆'),(3,'pageView','0','false','2014-12-24 09:01:38','0000-00-00 00:00:00','网页访问次数');
/*!40000 ALTER TABLE `system` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `level` varchar(2) NOT NULL COMMENT '用户等级',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'admin','858c86a3843be5e3001b7db637cb67ab','1'),(2,'test','9dbe87d7bcd06079e681b60d5e7c43b9','2'),(3,'visitor','9a96de2483722aed08b4b190568a425a','3'),(8,'lll','3899417b88bdc0aae5ccfcdce0126ca2','3');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-01-08 17:15:34
