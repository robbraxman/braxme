CREATE DATABASE  IF NOT EXISTS `braxproduction` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `braxproduction`;
-- MySQL dump 10.13  Distrib 8.0.18, for Linux (x86_64)
--
-- Host: braxencrypted01.c0v3fmih5g1c.us-west-2.rds.amazonaws.com    Database: braxproduction
-- ------------------------------------------------------
-- Server version	5.6.41-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `activitylog`
--

DROP TABLE IF EXISTS `activitylog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activitylog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `loginid` varchar(15) NOT NULL,
  `providerid` varchar(15) NOT NULL,
  `xacdate` datetime NOT NULL,
  `xaccode` varchar(10) NOT NULL,
  `sessionid` varchar(30) NOT NULL,
  `usertype` varchar(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `providerid` (`providerid`,`xacdate`),
  KEY `xacdate` (`xacdate`)
) ENGINE=InnoDB AUTO_INCREMENT=3734714 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `alertrefresh`
--

DROP TABLE IF EXISTS `alertrefresh`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `alertrefresh` (
  `deviceid` varchar(255) NOT NULL,
  `providerid` int(11) NOT NULL,
  `lastnotified` datetime NOT NULL,
  PRIMARY KEY (`deviceid`,`providerid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `alerts`
--

DROP TABLE IF EXISTS `alerts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `alerts` (
  `providerid` int(11) NOT NULL,
  `alertdate` datetime NOT NULL,
  `alerttype` varchar(15) NOT NULL,
  `status` varchar(1) NOT NULL,
  PRIMARY KEY (`providerid`,`alertdate`,`alerttype`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appidentity`
--

DROP TABLE IF EXISTS `appidentity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `appidentity` (
  `appname` varchar(100) NOT NULL,
  `appidentity` varchar(100) NOT NULL,
  `replyemail` varchar(512) DEFAULT NULL,
  `status` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`appname`,`appidentity`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appmeetup`
--

DROP TABLE IF EXISTS `appmeetup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `appmeetup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `replyemail` varchar(255) DEFAULT NULL,
  `appname` varchar(100) DEFAULT NULL,
  `appidentity` varchar(100) DEFAULT NULL,
  `status` varchar(1) DEFAULT NULL,
  `reqdate` datetime DEFAULT NULL,
  `greeting` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `replyemail` (`replyemail`,`appname`,`appidentity`)
) ENGINE=InnoDB AUTO_INCREMENT=653 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `attachments`
--

DROP TABLE IF EXISTS `attachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `attachments` (
  `sessionid` varchar(30) NOT NULL,
  `item` int(11) NOT NULL,
  `attachfilename` varchar(255) NOT NULL,
  `origfilename` varchar(255) NOT NULL,
  `providerid` int(11) NOT NULL,
  `encoding` varchar(30) NOT NULL,
  `filesize` int(11) NOT NULL,
  `filetype` varchar(10) NOT NULL,
  `archive` varchar(1) NOT NULL,
  PRIMARY KEY (`sessionid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `attacker`
--

DROP TABLE IF EXISTS `attacker`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `attacker` (
  `ip` varchar(45) NOT NULL,
  `ip2` varchar(45) NOT NULL,
  `accessdate` datetime DEFAULT NULL,
  `accesscount` int(11) DEFAULT NULL,
  PRIMARY KEY (`ip`,`ip2`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ban`
--

DROP TABLE IF EXISTS `ban`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ban` (
  `banid` varchar(64) NOT NULL,
  `chatid` int(11) NOT NULL,
  PRIMARY KEY (`banid`,`chatid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `batchrequest`
--

DROP TABLE IF EXISTS `batchrequest`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `batchrequest` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `providerid` int(11) NOT NULL,
  `requestdate` datetime NOT NULL,
  `requesttype` varchar(45) NOT NULL,
  `status` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `providerid` (`providerid`,`requestdate`,`requesttype`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `beacon`
--

DROP TABLE IF EXISTS `beacon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `beacon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(40) DEFAULT NULL,
  `beacontime` datetime DEFAULT NULL,
  `parms` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=194 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `blocked`
--

DROP TABLE IF EXISTS `blocked`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blocked` (
  `blocker` int(11) NOT NULL,
  `blockee` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`blocker`,`blockee`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `chatmaster`
--

DROP TABLE IF EXISTS `chatmaster`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chatmaster` (
  `chatid` int(11) NOT NULL AUTO_INCREMENT,
  `owner` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `lastmessage` datetime DEFAULT NULL,
  `status` varchar(1) NOT NULL,
  `archive` varchar(1) DEFAULT 'N',
  `userstatus` varchar(45) DEFAULT 'Active',
  `title` varchar(255) DEFAULT NULL,
  `keyhash` varchar(512) DEFAULT NULL,
  `lifespan` int(11) DEFAULT NULL,
  `encoding` varchar(30) DEFAULT '',
  `roomid` int(11) DEFAULT NULL,
  `radiostation` varchar(255) DEFAULT '',
  `broadcaster` int(11) DEFAULT NULL,
  `radiotitle` varchar(255) DEFAULT NULL,
  `streamid` varchar(45) DEFAULT NULL,
  `reservestation` datetime DEFAULT NULL,
  `broadcastmode` varchar(1) DEFAULT NULL,
  `hidemode` varchar(1) DEFAULT 'N',
  `question` varchar(255) DEFAULT NULL,
  `adminstation` varchar(1) DEFAULT '',
  `live` varchar(1) DEFAULT NULL,
  `broadcasttype` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`chatid`),
  KEY `owner` (`owner`),
  KEY `status` (`status`),
  KEY `streamid` (`radiotitle`,`streamid`)
) ENGINE=InnoDB AUTO_INCREMENT=12545 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `chatmembers`
--

DROP TABLE IF EXISTS `chatmembers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chatmembers` (
  `chatid` int(11) NOT NULL,
  `providerid` int(11) NOT NULL,
  `status` varchar(1) NOT NULL,
  `lastactive` datetime NOT NULL,
  `lastmessage` datetime NOT NULL,
  `lastread` datetime NOT NULL,
  `techsupport` varchar(1) DEFAULT NULL,
  `mute` varchar(1) DEFAULT '',
  `broadcaster` int(11) DEFAULT NULL,
  PRIMARY KEY (`chatid`,`providerid`),
  KEY `providerid` (`providerid`),
  KEY `lastmessage` (`lastmessage`),
  KEY `chatid` (`chatid`,`lastmessage`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `chatmessage`
--

DROP TABLE IF EXISTS `chatmessage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chatmessage` (
  `chatid` int(11) NOT NULL,
  `msgid` int(11) NOT NULL AUTO_INCREMENT,
  `message` text NOT NULL,
  `providerid` int(11) NOT NULL,
  `msgdate` datetime NOT NULL,
  `encoding` varchar(30) NOT NULL,
  `status` varchar(1) NOT NULL DEFAULT 'Y',
  `loginid` varchar(30) DEFAULT NULL,
  `flag` varchar(45) DEFAULT '',
  PRIMARY KEY (`msgid`,`chatid`),
  KEY `chatid` (`chatid`,`msgid`,`msgdate`,`status`,`providerid`),
  KEY `providerid` (`providerid`)
) ENGINE=InnoDB AUTO_INCREMENT=716017 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `chatpopup`
--

DROP TABLE IF EXISTS `chatpopup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chatpopup` (
  `chatid` int(11) NOT NULL,
  `url` varchar(1024) DEFAULT NULL,
  `broadcaster` int(11) NOT NULL,
  PRIMARY KEY (`chatid`,`broadcaster`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `chatspawned`
--

DROP TABLE IF EXISTS `chatspawned`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chatspawned` (
  `chatid` int(11) NOT NULL,
  `roomid` int(11) NOT NULL,
  `providerid` varchar(45) DEFAULT NULL,
  `createdate` datetime DEFAULT NULL,
  PRIMARY KEY (`chatid`,`roomid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `contactgroups`
--

DROP TABLE IF EXISTS `contactgroups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contactgroups` (
  `providerid` int(11) NOT NULL,
  `groupname` varchar(30) NOT NULL,
  `contactname` varchar(30) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`providerid`,`groupname`,`contactname`,`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contacts` (
  `providerid` int(11) NOT NULL,
  `contactname` varchar(40) NOT NULL,
  `email` varchar(255) NOT NULL,
  `sms` varchar(30) NOT NULL,
  `handle` varchar(45) NOT NULL DEFAULT '',
  `friend` varchar(1) NOT NULL DEFAULT ' ',
  `imapbox` int(11) DEFAULT NULL,
  `source` varchar(1) DEFAULT NULL,
  `blocked` varchar(1) DEFAULT NULL,
  `createdate` datetime DEFAULT NULL,
  `targetproviderid` int(11) DEFAULT NULL,
  PRIMARY KEY (`providerid`,`contactname`,`email`,`handle`),
  KEY `email` (`email`),
  KEY `handle` (`handle`),
  KEY `targetproviderid` (`targetproviderid`,`blocked`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `coupon`
--

DROP TABLE IF EXISTS `coupon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `coupon` (
  `couponcode` varchar(255) NOT NULL,
  `company` varchar(255) DEFAULT NULL,
  `licensecount` int(11) DEFAULT NULL,
  `bandwidthplan` varchar(45) DEFAULT NULL,
  `coupontype` varchar(45) DEFAULT NULL,
  `storage` int(11) DEFAULT '0',
  `roomcreator` varchar(1) DEFAULT 'Y',
  `broadcaster` varchar(1) DEFAULT 'Y',
  `store` varchar(1) DEFAULT 'N',
  `web` varchar(1) DEFAULT 'Y',
  `expiration` datetime DEFAULT NULL,
  `trial` int(11) DEFAULT '7',
  `trackerid` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`couponcode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cryptkeys`
--

DROP TABLE IF EXISTS `cryptkeys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cryptkeys` (
  `keyid` varchar(30) NOT NULL,
  `passphrase` varchar(512) NOT NULL,
  `encoding` varchar(30) NOT NULL,
  `expiration` datetime NOT NULL,
  PRIMARY KEY (`keyid`,`encoding`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `csvemail`
--

DROP TABLE IF EXISTS `csvemail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `csvemail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ownerid` int(11) DEFAULT NULL,
  `to` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text,
  `email` varchar(45) DEFAULT NULL,
  `uploaded` datetime DEFAULT NULL,
  `status` varchar(1) DEFAULT NULL,
  `error` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `csvsignup`
--

DROP TABLE IF EXISTS `csvsignup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `csvsignup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) DEFAULT NULL,
  `sms` varchar(45) DEFAULT NULL,
  `name` varchar(80) DEFAULT NULL,
  `handle` varchar(30) DEFAULT NULL,
  `sponsor` varchar(30) DEFAULT NULL,
  `companyname` varchar(80) DEFAULT NULL,
  `ownerid` int(11) DEFAULT NULL,
  `roomid` int(11) DEFAULT NULL,
  `temppassword` varchar(30) DEFAULT NULL,
  `uploaded` datetime DEFAULT NULL,
  `status` varchar(1) DEFAULT NULL,
  `error` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=205640 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `csvtemp`
--

DROP TABLE IF EXISTS `csvtemp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `csvtemp` (
  `email` varchar(255) NOT NULL,
  `sms` varchar(45) DEFAULT NULL,
  `name` varchar(80) DEFAULT NULL,
  `ownerid` int(11) DEFAULT NULL,
  `roomid` int(11) NOT NULL,
  `uploaded` datetime DEFAULT NULL,
  PRIMARY KEY (`email`,`roomid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `csvtext`
--

DROP TABLE IF EXISTS `csvtext`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `csvtext` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ownerid` int(11) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `sms` varchar(45) DEFAULT NULL,
  `uploaded` datetime DEFAULT NULL,
  `status` varchar(1) DEFAULT NULL,
  `error` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2656 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dealers`
--

DROP TABLE IF EXISTS `dealers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dealers` (
  `dealername` varchar(30) NOT NULL,
  `password` varchar(30) NOT NULL,
  PRIMARY KEY (`dealername`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `debuglog`
--

DROP TABLE IF EXISTS `debuglog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `debuglog` (
  `logid` int(11) NOT NULL AUTO_INCREMENT,
  `logdate` datetime DEFAULT NULL,
  `providerid` int(11) DEFAULT NULL,
  `event` text,
  PRIMARY KEY (`logid`)
) ENGINE=InnoDB AUTO_INCREMENT=181811 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `emaillist`
--

DROP TABLE IF EXISTS `emaillist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `emaillist` (
  `email` varchar(255) NOT NULL,
  `source` varchar(30) NOT NULL,
  UNIQUE KEY `email` (`email`,`source`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `events` (
  `eventid` int(11) NOT NULL AUTO_INCREMENT,
  `roomid` int(11) DEFAULT NULL,
  `eventdate` datetime DEFAULT NULL,
  `eventtime` varchar(8) DEFAULT NULL,
  `eventname` varchar(45) DEFAULT NULL,
  `eventdesc` varchar(512) DEFAULT NULL,
  `createdate` datetime DEFAULT NULL,
  `status` varchar(1) DEFAULT NULL,
  `notificationstatus` varchar(1) DEFAULT NULL,
  `notificationdate` datetime DEFAULT NULL,
  `providerid` int(11) DEFAULT NULL,
  `timezone` int(11) NOT NULL DEFAULT '-7',
  `station` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`eventid`,`timezone`)
) ENGINE=InnoDB AUTO_INCREMENT=910 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `filefolders`
--

DROP TABLE IF EXISTS `filefolders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `filefolders` (
  `providerid` int(11) NOT NULL,
  `foldername` varchar(255) NOT NULL,
  `folderid` int(11) NOT NULL AUTO_INCREMENT,
  `parentfolder` varchar(255) DEFAULT NULL,
  `parentfolderid` int(11) DEFAULT NULL,
  PRIMARY KEY (`folderid`,`providerid`,`foldername`)
) ENGINE=InnoDB AUTO_INCREMENT=619 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `filelib`
--

DROP TABLE IF EXISTS `filelib`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `filelib` (
  `providerid` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `folder` varchar(255) NOT NULL,
  `origfilename` varchar(255) NOT NULL,
  `filesize` double NOT NULL,
  `filetype` varchar(10) NOT NULL,
  `title` varchar(255) NOT NULL,
  `createdate` datetime NOT NULL,
  `views` int(11) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `fileid` int(11) NOT NULL AUTO_INCREMENT,
  `fileencoding` varchar(30) DEFAULT 'PLAINTEXT',
  `encoding` varchar(30) DEFAULT NULL,
  `folderid` int(11) DEFAULT '0',
  `sendtoid` int(11) DEFAULT NULL,
  `status` varchar(1) DEFAULT NULL,
  `convertflag` varchar(1) DEFAULT NULL,
  `lifetimeviews` int(11) DEFAULT '0',
  `pin` varchar(1) DEFAULT '',
  PRIMARY KEY (`fileid`),
  KEY `Filename` (`filename`),
  KEY `providerid` (`providerid`,`folder`,`filename`)
) ENGINE=InnoDB AUTO_INCREMENT=19475 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `fileviews`
--

DROP TABLE IF EXISTS `fileviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fileviews` (
  `viewid` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) DEFAULT NULL,
  `providerid` int(11) DEFAULT NULL,
  `viewdate` datetime DEFAULT NULL,
  `filesize` int(11) DEFAULT NULL,
  `views` int(11) DEFAULT NULL,
  `status` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`viewid`),
  KEY `filename` (`filename`,`viewdate`)
) ENGINE=InnoDB AUTO_INCREMENT=107239 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `followers`
--

DROP TABLE IF EXISTS `followers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `followers` (
  `providerid` int(11) NOT NULL,
  `followerid` int(11) NOT NULL,
  `level` varchar(1) DEFAULT NULL,
  `followdate` datetime DEFAULT NULL,
  PRIMARY KEY (`providerid`,`followerid`),
  KEY `followdate` (`providerid`,`followdate`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `forgotlog`
--

DROP TABLE IF EXISTS `forgotlog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `forgotlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) DEFAULT NULL,
  `loginid` varchar(45) DEFAULT NULL,
  `createdate` datetime DEFAULT NULL,
  `status` varchar(1) DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `temppassword` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3135 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `friends`
--

DROP TABLE IF EXISTS `friends`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `friends` (
  `providerid` int(11) NOT NULL,
  `friendid` int(11) NOT NULL,
  `level` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`providerid`,`friendid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `gifts`
--

DROP TABLE IF EXISTS `gifts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gifts` (
  `xacid` int(11) NOT NULL AUTO_INCREMENT,
  `xacdate` datetime DEFAULT NULL,
  `providerid` int(11) DEFAULT NULL,
  `owner` int(11) DEFAULT NULL,
  `method` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`xacid`),
  KEY `providerid` (`providerid`,`xacdate`,`method`)
) ENGINE=InnoDB AUTO_INCREMENT=4986 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `groupmembers`
--

DROP TABLE IF EXISTS `groupmembers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `groupmembers` (
  `groupid` int(11) NOT NULL,
  `providerid` int(11) NOT NULL,
  `createdate` datetime DEFAULT NULL,
  PRIMARY KEY (`groupid`,`providerid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `groups` (
  `groupid` int(11) NOT NULL AUTO_INCREMENT,
  `groupname` varchar(255) NOT NULL,
  `creator` int(11) DEFAULT NULL,
  `createdate` datetime DEFAULT NULL,
  `groupdesc` varchar(255) DEFAULT NULL,
  `photourl` varchar(255) DEFAULT NULL,
  `organization` varchar(45) DEFAULT NULL,
  `roomid` int(11) DEFAULT NULL,
  PRIMARY KEY (`groupid`,`groupname`)
) ENGINE=InnoDB AUTO_INCREMENT=474 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `handle`
--

DROP TABLE IF EXISTS `handle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `handle` (
  `handle` varchar(30) NOT NULL,
  `email` varchar(255) NOT NULL,
  `providerid` int(11) DEFAULT NULL,
  PRIMARY KEY (`handle`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `invite_temp`
--

DROP TABLE IF EXISTS `invite_temp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invite_temp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `createdate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`,`createdate`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `invites`
--

DROP TABLE IF EXISTS `invites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invites` (
  `providerid` int(11) NOT NULL,
  `name` varchar(80) NOT NULL,
  `email` varchar(255) NOT NULL,
  `handle` varchar(45) DEFAULT NULL,
  `status` varchar(1) NOT NULL,
  `invitedate` datetime NOT NULL,
  `roomid` int(11) NOT NULL,
  `contactlist` varchar(1) NOT NULL,
  `sms` varchar(20) NOT NULL,
  `retries` int(11) DEFAULT NULL,
  `retrydate` datetime DEFAULT NULL,
  `chatid` int(11) DEFAULT NULL,
  `inviteid` varchar(45) DEFAULT NULL,
  `sponsor` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`email`,`invitedate`),
  KEY `invitedate` (`invitedate`),
  KEY `sms` (`sms`),
  KEY `inviteid` (`inviteid`),
  KEY `handle` (`handle`),
  KEY `providerid` (`providerid`,`chatid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `iphash`
--

DROP TABLE IF EXISTS `iphash`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `iphash` (
  `ip` varchar(255) NOT NULL,
  `activitydate` datetime DEFAULT NULL,
  `signupcookie` varchar(45) DEFAULT NULL,
  `lastaction` datetime DEFAULT NULL,
  `icount` int(11) DEFAULT NULL,
  `ipattacker` varchar(45) DEFAULT NULL,
  `deviceid` varchar(45) DEFAULT NULL,
  `lastuser` varchar(45) DEFAULT NULL,
  `innerwidth` varchar(45) DEFAULT NULL,
  `innerheight` varchar(45) DEFAULT NULL,
  `timezone` varchar(4) DEFAULT NULL,
  PRIMARY KEY (`ip`),
  KEY `deviceid` (`deviceid`),
  KEY `lastuser` (`lastuser`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `keysend`
--

DROP TABLE IF EXISTS `keysend`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `keysend` (
  `providerid` int(11) NOT NULL,
  `chatid` int(11) NOT NULL,
  `passkey` varchar(512) DEFAULT NULL,
  `encoding` varchar(30) DEFAULT NULL,
  `senderid` int(11) NOT NULL DEFAULT '0',
  `expiration` datetime DEFAULT NULL,
  PRIMARY KEY (`providerid`,`chatid`,`senderid`),
  KEY `sender` (`senderid`,`chatid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `landing`
--

DROP TABLE IF EXISTS `landing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `landing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `createdate` datetime DEFAULT NULL,
  `landingcode` varchar(30) DEFAULT NULL,
  `mobile` varchar(1) DEFAULT NULL,
  `target` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37324 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lastfunc`
--

DROP TABLE IF EXISTS `lastfunc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lastfunc` (
  `providerid` int(11) NOT NULL,
  `deviceid` varchar(512) NOT NULL,
  `func` varchar(2) DEFAULT NULL,
  `parm1` varchar(255) DEFAULT NULL,
  `funcdate` datetime DEFAULT NULL,
  PRIMARY KEY (`providerid`,`deviceid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `leads`
--

DROP TABLE IF EXISTS `leads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `leads` (
  `email` varchar(255) NOT NULL,
  `name` varchar(80) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `created` datetime NOT NULL,
  `source` varchar(15) NOT NULL,
  `specialty` varchar(30) NOT NULL,
  `lastmailed` datetime DEFAULT NULL,
  `optout` varchar(1) DEFAULT ' ',
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `notification`
--

DROP TABLE IF EXISTS `notification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notification` (
  `notifyid` int(11) NOT NULL AUTO_INCREMENT,
  `providerid` int(11) NOT NULL,
  `notifydate` datetime NOT NULL,
  `status` varchar(1) NOT NULL DEFAULT '',
  `notifytype` varchar(30) NOT NULL DEFAULT '',
  `notifysubtype` varchar(30) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `sms` varchar(15) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `recipientid` int(11) NOT NULL DEFAULT '0',
  `roomid` int(11) DEFAULT NULL,
  `chatid` int(11) DEFAULT NULL,
  `mobile` varchar(1) DEFAULT NULL,
  `payload` varchar(512) DEFAULT NULL,
  `payloadsms` varchar(512) DEFAULT NULL,
  `encoding` varchar(30) DEFAULT NULL,
  `displayed` varchar(1) NOT NULL DEFAULT '',
  `notifymethod` varchar(1) DEFAULT NULL,
  `soundalert` varchar(1) DEFAULT '0',
  `reference` varchar(80) DEFAULT '',
  `notifyread` varchar(1) DEFAULT '',
  `mute` varchar(1) DEFAULT '',
  `msgid` int(11) DEFAULT NULL,
  PRIMARY KEY (`notifyid`),
  KEY `notifydate` (`notifydate`),
  KEY `notifytype` (`notifytype`,`roomid`,`notifydate`,`recipientid`),
  KEY `recipientid` (`recipientid`,`status`,`displayed`,`chatid`,`notifydate`),
  KEY `providerid` (`providerid`,`notifydate`,`notifytype`,`status`)
) ENGINE=InnoDB AUTO_INCREMENT=59128544 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `notifymute`
--

DROP TABLE IF EXISTS `notifymute`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifymute` (
  `id` int(11) NOT NULL,
  `idtype` varchar(1) NOT NULL,
  `providerid` int(11) NOT NULL,
  PRIMARY KEY (`id`,`idtype`,`providerid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `notifyrequest`
--

DROP TABLE IF EXISTS `notifyrequest`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifyrequest` (
  `requestid` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(1) NOT NULL DEFAULT '',
  `requestdate` datetime NOT NULL,
  `providerid` int(11) DEFAULT NULL,
  `chatid` int(11) DEFAULT NULL,
  `encodeshort` varchar(255) DEFAULT NULL,
  `encoding` varchar(30) DEFAULT NULL,
  `roomid` int(11) DEFAULT NULL,
  `postid` varchar(255) DEFAULT NULL,
  `msgid` int(11) DEFAULT NULL,
  `subtype` varchar(3) DEFAULT NULL,
  `shareid` varchar(255) DEFAULT NULL,
  `anonymous` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`requestid`,`status`),
  KEY `status` (`status`,`requestdate`)
) ENGINE=InnoDB AUTO_INCREMENT=2815168 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `notifytokens`
--

DROP TABLE IF EXISTS `notifytokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifytokens` (
  `providerid` int(11) NOT NULL,
  `app` varchar(30) NOT NULL,
  `platform` varchar(10) NOT NULL,
  `token` varchar(512) NOT NULL,
  `registered` datetime NOT NULL,
  `status` varchar(1) NOT NULL,
  `arn` varchar(512) DEFAULT NULL,
  `error` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`providerid`,`app`,`token`),
  KEY `arn` (`arn`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `parms`
--

DROP TABLE IF EXISTS `parms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `parms` (
  `parmkey` varchar(30) NOT NULL,
  `parmcode` varchar(30) NOT NULL,
  `val1` int(11) unsigned NOT NULL,
  `val2` int(11) NOT NULL,
  `date1` datetime DEFAULT NULL,
  PRIMARY KEY (`parmkey`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `photolib`
--

DROP TABLE IF EXISTS `photolib`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `photolib` (
  `providerid` int(11) NOT NULL,
  `album` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `folder` varchar(255) NOT NULL,
  `filesize` int(11) NOT NULL,
  `filetype` varchar(10) NOT NULL,
  `title` varchar(255) NOT NULL,
  `createdate` datetime NOT NULL,
  `views` int(11) NOT NULL,
  `likes` int(11) NOT NULL,
  `comment` text NOT NULL,
  `photoid` int(11) NOT NULL AUTO_INCREMENT,
  `alias` varchar(255) NOT NULL,
  `owner` int(11) DEFAULT NULL,
  `aws_url` varchar(255) NOT NULL,
  `aws_expire` datetime NOT NULL,
  `public` varchar(1) NOT NULL DEFAULT ' ',
  `t_filename` varchar(255) DEFAULT NULL,
  `t_aws_url` varchar(255) DEFAULT NULL,
  `f_filename` varchar(255) DEFAULT NULL,
  `f_aws_url` varchar(255) DEFAULT NULL,
  `hide` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`photoid`),
  UNIQUE KEY `Filename` (`filename`),
  UNIQUE KEY `alias` (`alias`),
  UNIQUE KEY `album` (`album`,`public`,`providerid`,`filename`)
) ENGINE=InnoDB AUTO_INCREMENT=90045 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `photolibshare`
--

DROP TABLE IF EXISTS `photolibshare`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `photolibshare` (
  `providerid` int(11) NOT NULL,
  `album` varchar(255) NOT NULL,
  `sharetype` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`providerid`,`album`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `photoproxy`
--

DROP TABLE IF EXISTS `photoproxy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `photoproxy` (
  `providerid` int(11) NOT NULL,
  `folder` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `section` varchar(1) NOT NULL,
  PRIMARY KEY (`providerid`,`filename`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `profile`
--

DROP TABLE IF EXISTS `profile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `profile` (
  `providerid` int(11) NOT NULL,
  `body` text,
  PRIMARY KEY (`providerid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `provaddressbook`
--

DROP TABLE IF EXISTS `provaddressbook`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `provaddressbook` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `providerid` int(11) NOT NULL,
  `recipientname` varchar(150) NOT NULL,
  `recipientid` varchar(30) NOT NULL,
  `recipientsms` varchar(30) NOT NULL,
  `recipientemail` varchar(255) NOT NULL,
  `recipientmasterkey` varchar(255) NOT NULL,
  `recipienttype` varchar(2) NOT NULL,
  `ChallengeText` varchar(255) NOT NULL,
  `challenge` varchar(255) DEFAULT '',
  `ResponseText` varchar(255) NOT NULL,
  `referenceid` varchar(15) NOT NULL,
  `LoginID` varchar(15) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `encoding` varchar(30) DEFAULT NULL,
  `alias` varchar(30) NOT NULL DEFAULT '',
  `vpnflag` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `recipientsms` (`providerid`,`recipientname`(30),`recipientsms`,`recipientemail`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `provider`
--

DROP TABLE IF EXISTS `provider`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `provider` (
  `providerid` int(11) NOT NULL,
  `providername` varchar(100) NOT NULL,
  `replyemail` varchar(255) NOT NULL,
  `alias` varchar(30) NOT NULL,
  `name2` varchar(45) DEFAULT NULL,
  `handle` varchar(45) DEFAULT NULL,
  `companyname` varchar(100) NOT NULL,
  `LoginId` varchar(15) NOT NULL,
  `Active` varchar(1) NOT NULL DEFAULT 'Y',
  `enterprise` varchar(1) DEFAULT NULL,
  `enterprisehost` varchar(1) DEFAULT NULL,
  `industry` varchar(30) NOT NULL,
  `sponsor` varchar(30) DEFAULT NULL,
  `createdate` datetime NOT NULL,
  `publishprofile` text,
  `publish` varchar(1) DEFAULT NULL,
  `useragent` varchar(512) DEFAULT NULL,
  `devicecode` varchar(15) DEFAULT NULL,
  `avatarURL` varchar(512) NOT NULL,
  `fails` int(11) NOT NULL DEFAULT '0',
  `roomdiscovery` varchar(1) DEFAULT 'Y',
  `notifications` varchar(1) DEFAULT NULL,
  `siteid` int(10) NOT NULL,
  `ContractPeriod` int(11) DEFAULT '1',
  `ContractType` varchar(30) DEFAULT NULL,
  `DealerPhone` varchar(15) NOT NULL,
  `usercode` varchar(10) NOT NULL,
  `dealer` varchar(10) NOT NULL,
  `lastactive` datetime DEFAULT NULL,
  `AutoSendKey` varchar(1) NOT NULL,
  `ServerHost` varchar(255) NOT NULL,
  `timeout` int(11) NOT NULL DEFAULT '60',
  `mobile` varchar(1) NOT NULL DEFAULT 'N',
  `stafflimit` int(11) NOT NULL DEFAULT '10',
  `msglifespan` int(11) NOT NULL,
  `allowtexting` varchar(1) NOT NULL,
  `uploadcount` int(11) NOT NULL,
  `allowkeydownload` varchar(1) NOT NULL,
  `allowrandomkey` varchar(1) NOT NULL,
  `verified` varchar(1) NOT NULL,
  `verifiedemail` varchar(255) NOT NULL,
  `cookies_recipient` varchar(1) NOT NULL,
  `cookies_sender` varchar(1) NOT NULL,
  `superadmin` varchar(1) DEFAULT NULL,
  `dealeremail` varchar(255) DEFAULT NULL,
  `inactivitytimeout` int(5) DEFAULT NULL,
  `encoding` varchar(30) DEFAULT NULL,
  `defaultsmtp` int(11) NOT NULL DEFAULT '0',
  `accountstatus` varchar(1) NOT NULL DEFAULT 'N',
  `menustyle` varchar(1) DEFAULT NULL,
  `proxy` varchar(1) DEFAULT NULL,
  `lastfunc` varchar(2) DEFAULT NULL,
  `lastfuncdate` datetime DEFAULT NULL,
  `lastfuncparm1` varchar(255) DEFAULT NULL,
  `lastroomid` int(11) DEFAULT '0',
  `lasttip` int(11) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `featureemail` varchar(1) DEFAULT NULL,
  `archivechat` varchar(1) DEFAULT 'N',
  `invitesource` varchar(2) DEFAULT NULL,
  `termsofuse` datetime DEFAULT NULL,
  `deviceheight` int(11) DEFAULT NULL,
  `devicewidth` int(11) DEFAULT NULL,
  `pixelratio` int(11) DEFAULT NULL,
  `techsupport` varchar(1) NOT NULL DEFAULT '',
  `techname` varchar(45) DEFAULT NULL,
  `chgpassword` varchar(1) DEFAULT '',
  `member` varchar(1) DEFAULT NULL,
  `eowner` int(11) DEFAULT NULL,
  `notificationflags` varchar(15) DEFAULT NULL,
  `blindsound` varchar(45) DEFAULT NULL,
  `streamingaccount` varchar(255) DEFAULT NULL,
  `costexempt` varchar(1) DEFAULT 'N',
  `blockdownload` varchar(1) DEFAULT 'N',
  `sponsorlist` varchar(1) DEFAULT NULL,
  `pinlock` varchar(1) DEFAULT '',
  `lastnotified` datetime DEFAULT NULL,
  `profileroomid` int(11) DEFAULT NULL,
  `colorscheme` varchar(45) DEFAULT 'std',
  `score` int(11) DEFAULT NULL,
  `lastaccess` datetime DEFAULT NULL,
  `positiontitle` varchar(45) DEFAULT NULL,
  `gift` varchar(1) DEFAULT 'Y',
  `wallpaper` varchar(45) DEFAULT NULL,
  `language` varchar(45) DEFAULT 'english',
  `roomfeed` varchar(1) DEFAULT 'Y',
  `photosharelevel` varchar(1) DEFAULT NULL,
  `stealth` varchar(1) DEFAULT NULL,
  `joinedvia` varchar(45) DEFAULT '',
  `appname` varchar(45) DEFAULT NULL,
  `iphash` varchar(64) DEFAULT NULL,
  `iphash2` varchar(64) DEFAULT NULL,
  `iphash3` varchar(64) DEFAULT NULL,
  `banid` varchar(64) DEFAULT NULL,
  `timezone` varchar(4) DEFAULT NULL,
  `ipsource` varchar(45) DEFAULT NULL,
  `multi` int(11) DEFAULT '0',
  `trackerid` varchar(45) DEFAULT NULL,
  `roomcreator` varchar(1) DEFAULT NULL,
  `broadcaster` varchar(1) DEFAULT NULL,
  `store` varchar(1) DEFAULT NULL,
  `web` varchar(1) DEFAULT NULL,
  `paypalemail` varchar(255) DEFAULT NULL,
  `sandbox` varchar(1) DEFAULT NULL,
  `newbie` varchar(1) DEFAULT NULL,
  `allowiot` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`providerid`,`techsupport`),
  KEY `providername` (`providername`),
  KEY `replyemail` (`replyemail`),
  KEY `handle` (`handle`),
  KEY `techsupport` (`techsupport`,`handle`),
  KEY `owner` (`eowner`,`handle`),
  KEY `createdate` (`createdate`),
  KEY `iphash` (`iphash`),
  KEY `iphash2` (`iphash2`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `provideralias`
--

DROP TABLE IF EXISTS `provideralias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `provideralias` (
  `providerid` int(11) NOT NULL,
  `alias` varchar(30) NOT NULL,
  PRIMARY KEY (`providerid`,`alias`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `publicrooms`
--

DROP TABLE IF EXISTS `publicrooms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `publicrooms` (
  `providerid` int(11) NOT NULL,
  `roomid` int(11) NOT NULL,
  `alias` varchar(60) NOT NULL,
  PRIMARY KEY (`roomid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `roomfavorites`
--

DROP TABLE IF EXISTS `roomfavorites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roomfavorites` (
  `providerid` int(11) NOT NULL,
  `roomid` int(11) NOT NULL,
  PRIMARY KEY (`providerid`,`roomid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `roomfilefolders`
--

DROP TABLE IF EXISTS `roomfilefolders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roomfilefolders` (
  `folderid` int(11) NOT NULL AUTO_INCREMENT,
  `roomid` int(11) NOT NULL,
  `foldername` varchar(255) NOT NULL,
  `parentfolderid` int(11) DEFAULT NULL,
  `createdate` datetime DEFAULT NULL,
  `providerid` int(11) DEFAULT NULL,
  PRIMARY KEY (`folderid`,`roomid`)
) ENGINE=InnoDB AUTO_INCREMENT=228 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `roomfiles`
--

DROP TABLE IF EXISTS `roomfiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roomfiles` (
  `roomid` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `createdate` datetime DEFAULT NULL,
  `providerid` int(11) DEFAULT NULL,
  `downloads` int(11) DEFAULT '0',
  `folderid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`roomid`,`filename`,`folderid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `roomforms`
--

DROP TABLE IF EXISTS `roomforms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roomforms` (
  `roomid` int(11) NOT NULL,
  `formid` int(11) NOT NULL,
  PRIMARY KEY (`roomid`,`formid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `roomhandle`
--

DROP TABLE IF EXISTS `roomhandle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roomhandle` (
  `roomid` int(11) NOT NULL,
  `handle` varchar(80) NOT NULL,
  `name` varchar(30) DEFAULT NULL,
  `public` varchar(1) DEFAULT NULL,
  `roomdesc` varchar(255) DEFAULT NULL,
  `category` varchar(80) DEFAULT NULL,
  `tags` varchar(512) NOT NULL DEFAULT '',
  `rank` int(11) NOT NULL DEFAULT '0',
  `minage` int(11) NOT NULL DEFAULT '0',
  `photourl` text,
  `wizardenterprise` varchar(45) DEFAULT NULL,
  `community` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`handle`),
  UNIQUE KEY `roomid` (`roomid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `roominfo`
--

DROP TABLE IF EXISTS `roominfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roominfo` (
  `roomid` int(11) NOT NULL,
  `room` varchar(255) DEFAULT NULL,
  `roomdesc` varchar(255) DEFAULT NULL,
  `roomstyle` varchar(5) DEFAULT 'std',
  `profileflag` varchar(1) DEFAULT '',
  `photourl` varchar(512) DEFAULT NULL,
  `photourl2` varchar(512) DEFAULT NULL,
  `anonymousflag` varchar(1) DEFAULT NULL,
  `external` varchar(1) DEFAULT NULL,
  `organization` varchar(40) DEFAULT NULL,
  `private` varchar(1) DEFAULT NULL,
  `contactexchange` varchar(1) DEFAULT 'N',
  `adminonly` varchar(1) DEFAULT 'N',
  `notifications` varchar(1) DEFAULT 'Y',
  `soundalert` varchar(1) DEFAULT '0',
  `sharephotoflag` varchar(1) DEFAULT NULL,
  `adminroom` varchar(1) DEFAULT NULL,
  `showmembers` varchar(1) DEFAULT 'Y',
  `rsscategory` varchar(255) DEFAULT NULL,
  `rsstimestamp` varchar(225) DEFAULT NULL,
  `lastactive` datetime DEFAULT NULL,
  `groupid` int(11) DEFAULT NULL,
  `rsssource` varchar(512) DEFAULT NULL,
  `rsssourceid` varchar(255) DEFAULT NULL,
  `radiostation` varchar(1) DEFAULT '',
  `quizroom` varchar(1) DEFAULT '',
  `sponsor` varchar(45) DEFAULT NULL,
  `parentroom` varchar(45) DEFAULT NULL,
  `featured` varchar(3) DEFAULT '',
  `childsort` int(11) DEFAULT '0',
  `roominvitehandle` varchar(45) DEFAULT NULL,
  `webcolorscheme` varchar(45) DEFAULT NULL,
  `webpublishprofile` varchar(1) DEFAULT NULL,
  `webflags` varchar(512) DEFAULT NULL,
  `storeurl` varchar(255) DEFAULT NULL,
  `webtextcolor` varchar(45) DEFAULT NULL,
  `searchengine` varchar(1) DEFAULT NULL,
  `analytics` varchar(4096) DEFAULT NULL,
  `subscriptiondays` int(11) DEFAULT NULL,
  `wallpaper` varchar(255) DEFAULT '',
  `subscription` decimal(5,2) DEFAULT NULL,
  `subscriptionusd` decimal(5,2) DEFAULT NULL,
  `autochatuser` varchar(30) DEFAULT NULL,
  `autochatmsg` varchar(4096) DEFAULT NULL,
  `wizardenterprise` varchar(45) DEFAULT NULL,
  `communitylink` varchar(45) DEFAULT NULL,
  `store` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`roomid`),
  KEY `parent` (`parentroom`,`roomid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `roominvite`
--

DROP TABLE IF EXISTS `roominvite`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roominvite` (
  `roomid` int(11) NOT NULL,
  `inviteid` varchar(255) NOT NULL,
  `expires` datetime NOT NULL,
  `status` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`roomid`,`inviteid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `roommoderator`
--

DROP TABLE IF EXISTS `roommoderator`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roommoderator` (
  `roomid` int(11) NOT NULL,
  `providerid` int(11) NOT NULL,
  PRIMARY KEY (`roomid`,`providerid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `roomwebstyle`
--

DROP TABLE IF EXISTS `roomwebstyle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roomwebstyle` (
  `roomid` int(11) NOT NULL,
  `seq` int(11) NOT NULL,
  `stylekey` varchar(30) NOT NULL,
  `styledata` text,
  PRIMARY KEY (`roomid`,`seq`,`stylekey`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `service`
--

DROP TABLE IF EXISTS `service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `service` (
  `msglevel` varchar(30) NOT NULL DEFAULT 'STATUS',
  `announcement` varchar(1024) NOT NULL DEFAULT 'Service will be temporarily unavailable, We are working hard to restore operations.',
  `Active` varchar(1) NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`msglevel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sharecollection`
--

DROP TABLE IF EXISTS `sharecollection`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sharecollection` (
  `providerid` int(11) NOT NULL,
  `collection` varchar(255) NOT NULL,
  `album` varchar(255) NOT NULL,
  `seq` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `url1` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`providerid`,`collection`,`seq`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `shareposts`
--

DROP TABLE IF EXISTS `shareposts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shareposts` (
  `shareid` varchar(255) NOT NULL,
  `ip` varchar(45) NOT NULL,
  `shareto` varchar(100) NOT NULL,
  `comment` varchar(1024) NOT NULL,
  `postdate` datetime NOT NULL,
  `name` varchar(30) NOT NULL,
  `device` varchar(80) NOT NULL,
  `postid` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`postid`),
  KEY `shareid` (`shareid`,`postdate`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sharereads`
--

DROP TABLE IF EXISTS `sharereads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sharereads` (
  `shareid` varchar(255) NOT NULL,
  `ip` varchar(45) NOT NULL,
  `device` varchar(80) NOT NULL,
  `views` int(11) NOT NULL,
  `printscreen` int(11) NOT NULL,
  `lastread` datetime NOT NULL,
  PRIMARY KEY (`shareid`,`ip`,`device`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `shares`
--

DROP TABLE IF EXISTS `shares`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shares` (
  `providerid` int(11) NOT NULL,
  `sharetype` varchar(1) NOT NULL,
  `shareid` varchar(255) NOT NULL,
  `sharelocal` varchar(255) NOT NULL,
  `sharetitle` varchar(255) NOT NULL,
  `shareopentitle` varchar(255) NOT NULL,
  `shareto` varchar(80) NOT NULL,
  `sharedate` datetime NOT NULL,
  `shareexpire` datetime NOT NULL,
  `securetype` varchar(1) NOT NULL,
  `platform` varchar(15) NOT NULL,
  `views` int(11) NOT NULL,
  `likes` int(11) NOT NULL,
  `setid` varchar(255) NOT NULL,
  `proxyfilename` varchar(255) NOT NULL,
  `collection` varchar(255) NOT NULL,
  `roomid` int(11) DEFAULT NULL,
  PRIMARY KEY (`shareid`),
  KEY `sharelocal` (`sharelocal`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sites`
--

DROP TABLE IF EXISTS `sites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sites` (
  `siteid` int(11) NOT NULL AUTO_INCREMENT,
  `sitename` varchar(80) NOT NULL DEFAULT ' ',
  `address1` varchar(60) NOT NULL,
  `address2` varchar(60) NOT NULL,
  `city` varchar(30) NOT NULL,
  `state` varchar(2) NOT NULL,
  `zip` varchar(10) NOT NULL,
  PRIMARY KEY (`siteid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `slideshowpref`
--

DROP TABLE IF EXISTS `slideshowpref`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `slideshowpref` (
  `providerid` int(11) NOT NULL,
  `sortorder` varchar(4) DEFAULT NULL,
  `slideseconds` int(11) DEFAULT NULL,
  PRIMARY KEY (`providerid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sms`
--

DROP TABLE IF EXISTS `sms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sms` (
  `providerid` int(11) NOT NULL,
  `sms` varchar(255) NOT NULL,
  `encoding` varchar(30) NOT NULL,
  `unencoded` varchar(45) DEFAULT NULL,
  `verified` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`providerid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `smslog`
--

DROP TABLE IF EXISTS `smslog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `smslog` (
  `providerid` int(11) NOT NULL,
  `sms` varchar(45) NOT NULL,
  `sentdate` datetime NOT NULL,
  `source` varchar(2) DEFAULT NULL,
  `recipientid` int(11) DEFAULT NULL,
  PRIMARY KEY (`sentdate`,`providerid`,`sms`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `staff`
--

DROP TABLE IF EXISTS `staff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `staff` (
  `staffid` int(11) NOT NULL AUTO_INCREMENT,
  `providerid` int(11) NOT NULL,
  `lastaccess` datetime DEFAULT NULL,
  `staffname` varchar(80) NOT NULL,
  `loginid` varchar(30) NOT NULL DEFAULT '',
  `pwd_ver` int(11) NOT NULL DEFAULT '1',
  `pwd_hash` varchar(255) NOT NULL DEFAULT '',
  `replyright` varchar(1) NOT NULL DEFAULT 'N',
  `sendright` varchar(1) NOT NULL DEFAULT 'N',
  `viewright` varchar(1) NOT NULL DEFAULT 'N',
  `adminright` varchar(1) NOT NULL DEFAULT 'N',
  `WorkGroup` varchar(10) NOT NULL,
  `active` varchar(1) NOT NULL,
  `email` varchar(255) NOT NULL,
  `fails` int(11) NOT NULL DEFAULT '0',
  `emailalert` varchar(1) NOT NULL DEFAULT '',
  `salt` varchar(255) NOT NULL DEFAULT '',
  `onetimeflag` varchar(1) DEFAULT NULL,
  `auth_hash` varchar(255) DEFAULT NULL,
  `encoding` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`staffid`),
  UNIQUE KEY `PROVIDERID` (`providerid`,`loginid`)
) ENGINE=InnoDB AUTO_INCREMENT=184212 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `statuspost`
--

DROP TABLE IF EXISTS `statuspost`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `statuspost` (
  `providerid` int(11) NOT NULL,
  `shareid` varchar(30) NOT NULL,
  `comment` text NOT NULL,
  `postdate` datetime NOT NULL,
  `parent` varchar(1) NOT NULL,
  `owner` int(11) NOT NULL,
  `likes` int(11) NOT NULL,
  `roomid` int(11) NOT NULL,
  `postid` varchar(30) NOT NULL,
  `encoding` varchar(30) NOT NULL,
  `link` text NOT NULL,
  `photo` text NOT NULL,
  `video` text NOT NULL,
  `anonymous` varchar(1) NOT NULL DEFAULT 'N',
  `videotitle` varchar(255) DEFAULT NULL,
  `pin` int(11) DEFAULT '0',
  `articleid` int(11) DEFAULT NULL,
  `locked` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `commentcount` int(11) DEFAULT '0',
  `album` varchar(100) DEFAULT '',
  PRIMARY KEY (`postid`),
  UNIQUE KEY `owner` (`owner`,`shareid`,`postid`),
  KEY `roomid` (`roomid`,`postdate`),
  KEY `shareid` (`shareid`,`roomid`),
  KEY `articleid` (`articleid`,`roomid`),
  KEY `providerid` (`providerid`,`postdate`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `statuspostpublic`
--

DROP TABLE IF EXISTS `statuspostpublic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `statuspostpublic` (
  `providerid` int(11) NOT NULL,
  `shareid` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `postdate` datetime NOT NULL,
  `parent` varchar(1) NOT NULL,
  `postername` varchar(30) NOT NULL,
  `likes` int(11) NOT NULL,
  `postid` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`postid`),
  UNIQUE KEY `provider` (`providerid`,`shareid`,`postdate`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `statusreads`
--

DROP TABLE IF EXISTS `statusreads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `statusreads` (
  `providerid` int(11) NOT NULL,
  `shareid` varchar(255) NOT NULL,
  `actiontime` datetime NOT NULL,
  `postid` varchar(255) NOT NULL,
  `xaccode` varchar(1) NOT NULL,
  `roomid` int(11) DEFAULT '0',
  PRIMARY KEY (`providerid`,`shareid`,`postid`,`xaccode`,`actiontime`),
  KEY `providerid` (`roomid`,`providerid`,`shareid`,`postid`,`xaccode`),
  KEY `shareid` (`shareid`,`actiontime`),
  KEY `roomid` (`roomid`,`xaccode`),
  KEY `xaccode` (`xaccode`,`actiontime`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `statusroom`
--

DROP TABLE IF EXISTS `statusroom`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `statusroom` (
  `roomid` int(11) NOT NULL,
  `owner` int(11) NOT NULL,
  `providerid` int(11) NOT NULL,
  `room` varchar(100) NOT NULL,
  `status` varchar(1) NOT NULL,
  `createdate` datetime DEFAULT NULL,
  `creatorid` int(11) DEFAULT NULL,
  `lastaccess` datetime DEFAULT NULL,
  `lastemail` datetime DEFAULT NULL,
  `notifications` varchar(1) DEFAULT NULL,
  `pin` int(11) DEFAULT '0',
  `blocked` varchar(1) DEFAULT NULL,
  `subscribedate` datetime DEFAULT NULL,
  `expiredate` datetime DEFAULT NULL,
  PRIMARY KEY (`roomid`,`owner`,`providerid`),
  KEY `roomid` (`roomid`),
  KEY `providerid` (`providerid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tasks`
--

DROP TABLE IF EXISTS `tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tasks` (
  `eventid` int(11) NOT NULL AUTO_INCREMENT,
  `roomid` int(11) DEFAULT NULL,
  `eventdate` datetime DEFAULT NULL,
  `eventtime` varchar(8) DEFAULT NULL,
  `eventname` varchar(45) DEFAULT NULL,
  `eventdesc` varchar(512) DEFAULT NULL,
  `eventassign` varchar(512) DEFAULT NULL,
  `createdate` datetime DEFAULT NULL,
  `status` varchar(1) DEFAULT NULL,
  `notificationstatus` varchar(1) DEFAULT NULL,
  `notificationdate` datetime DEFAULT NULL,
  `donebyid` int(11) DEFAULT NULL,
  `providerid` int(11) DEFAULT NULL,
  `priority` varchar(6) DEFAULT NULL,
  PRIMARY KEY (`eventid`)
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tasksaction`
--

DROP TABLE IF EXISTS `tasksaction`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tasksaction` (
  `eventid` int(11) NOT NULL,
  `roomid` int(11) NOT NULL DEFAULT '0',
  `donecode` varchar(80) NOT NULL DEFAULT '',
  `donebyid` int(11) NOT NULL DEFAULT '0',
  `donedate` datetime DEFAULT NULL,
  `providerid` int(11) DEFAULT NULL,
  PRIMARY KEY (`eventid`,`roomid`,`donebyid`,`donecode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `textmsg`
--

DROP TABLE IF EXISTS `textmsg`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `textmsg` (
  `providerid` int(11) NOT NULL,
  `sms` varchar(30) NOT NULL,
  `createdate` datetime NOT NULL,
  `reply` varchar(1) NOT NULL,
  `message` varchar(2048) NOT NULL,
  `readtime` datetime DEFAULT NULL,
  `sessionid` varchar(15) NOT NULL,
  `status` varchar(1) NOT NULL,
  `contactname` varchar(80) NOT NULL,
  `alias` varchar(90) NOT NULL,
  PRIMARY KEY (`providerid`,`sessionid`),
  KEY `createdate` (`providerid`,`createdate`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `timeout`
--

DROP TABLE IF EXISTS `timeout`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `timeout` (
  `providerid` int(11) NOT NULL,
  `pin` varchar(255) NOT NULL,
  `encoding` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`providerid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tokens`
--

DROP TABLE IF EXISTS `tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tokens` (
  `xacid` int(11) NOT NULL AUTO_INCREMENT,
  `xacdate` datetime DEFAULT NULL,
  `tokens` decimal(5,2) DEFAULT NULL,
  `dc` varchar(1) DEFAULT NULL,
  `providerid` int(11) DEFAULT NULL,
  `roomid` int(11) DEFAULT NULL,
  `owner` int(11) DEFAULT NULL,
  `method` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`xacid`),
  KEY `roomid` (`roomid`,`providerid`,`xacdate`)
) ENGINE=InnoDB AUTO_INCREMENT=545 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `verification`
--

DROP TABLE IF EXISTS `verification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `verification` (
  `type` varchar(10) NOT NULL,
  `verificationkey` varchar(30) NOT NULL,
  `providerid` int(11) NOT NULL,
  `loginid` varchar(15) NOT NULL,
  `createdate` datetime NOT NULL,
  `email` varchar(255) NOT NULL,
  `verifieddate` datetime DEFAULT NULL,
  PRIMARY KEY (`providerid`,`verificationkey`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-12-08  2:07:23
