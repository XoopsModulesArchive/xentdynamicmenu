-- phpMyAdmin SQL Dump
-- version 2.6.0-pl2
-- 
-- Generation Time: Nov 25, 2004 at 03:27 PM
-- Server version: 4.0.22
-- PHP Version: 4.3.9
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `xent_dm_menus`
-- 

CREATE TABLE `xent_dm_menus` (
    `ID_MENU`        INT(5)       NOT NULL AUTO_INCREMENT,
    `name`           VARCHAR(255) NOT NULL DEFAULT '',
    `content`        TEXT         NOT NULL,
    `link`           VARCHAR(255) NOT NULL DEFAULT '',
    `priority`       INT(5)       NOT NULL DEFAULT '0',
    `id_menu_parent` INT(5)       NOT NULL DEFAULT '0',
    KEY `ID_MENU` (`ID_MENU`)
)
    ENGINE = ISAM
    AUTO_INCREMENT = 1;

-- --------------------------------------------------------

-- 
-- Table structure for table `xent_dm_link_menu_block`
-- 

CREATE TABLE `xent_dm_link_menu_block` (
    `ID_MENU`  INT(5) NOT NULL DEFAULT '0',
    `ID_BLOCK` INT(5) NOT NULL DEFAULT '0'
)
    ENGINE = ISAM;



