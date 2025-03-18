-- phpMyAdmin SQL Dump
-- version 2.6.2-rc1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Jun 27, 2005 at 12:43 PM
-- Server version: 4.0.18
-- PHP Version: 5.0.4
-- 
-- Database: `test_hem_clean`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `test_activity`
-- 

CREATE TABLE `test_activity` (
  `actId` varchar(32) NOT NULL default '',
  `actType` int(11) NOT NULL default '0',
  `actUser` varchar(100) NOT NULL default '',
  `actDescription` varchar(200) NOT NULL default '',
  `actTime` timestamp(6) NOT NULL,
  PRIMARY KEY  (`actId`)
) ENGINE=MyISAM;

-- 
-- Dumping data for table `test_activity`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `test_environment`
-- 

CREATE TABLE `test_environment` (
  `envId` varchar(32) NOT NULL default '',
  `envTitleId` varchar(32) NOT NULL default '',
  `envDescriptionId` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`envId`)
) ENGINE=MyISAM;

-- 
-- Dumping data for table `test_environment`
-- 

INSERT INTO `test_environment` VALUES ('krhlwkcb40dvhhsaghu1u8azgig8ouct', 's56yfez6aueu7kuqjqwhu7ml38iempdz', 'ytsnm1hcfib0wg17rkqmv57a7ap4hs17');

-- --------------------------------------------------------

-- 
-- Table structure for table `test_environment_attributes`
-- 

CREATE TABLE `test_environment_attributes` (
  `envAttributeId` varchar(32) NOT NULL default '',
  `envId` varchar(32) NOT NULL default '',
  `envOrder` int(10) NOT NULL default '0',
  `envAttributeNameId` varchar(32) NOT NULL default '',
  `envAttributeType` varchar(100) NOT NULL default 'text',
  `envAttributeValues` varchar(100) default NULL,
  PRIMARY KEY  (`envAttributeId`)
) ENGINE=MyISAM;

-- 
-- Dumping data for table `test_environment_attributes`
-- 

INSERT INTO `test_environment_attributes` VALUES ('v9qm74nf4yzjzzi07hyj30o6859ew9dm', 'krhlwkcb40dvhhsaghu1u8azgig8ouct', 10, 'zize3c5tjviygghwf8y22qz9cvtvre1f', 'text', 'NULL');
INSERT INTO `test_environment_attributes` VALUES ('m9opq6umur457iwqxdqeh7tbgwwpb3q5', 'krhlwkcb40dvhhsaghu1u8azgig8ouct', 9, 'wavtuk69we503if1xceglkn22riaiwh5', 'text', 'NULL');
INSERT INTO `test_environment_attributes` VALUES ('a5yut9txp2bczmtesnl8njhcm7tgx80y', 'krhlwkcb40dvhhsaghu1u8azgig8ouct', 8, 'meihqd403bqjpvr0kbzl52kww267kw3s', 'text', 'NULL');
INSERT INTO `test_environment_attributes` VALUES ('r8dtrwdce8ul5d8bx29j05gh114a5nje', 'krhlwkcb40dvhhsaghu1u8azgig8ouct', 7, '6kb0gb5nu85ju22353evj2hwgqowrdaz', 'text', 'NULL');
INSERT INTO `test_environment_attributes` VALUES ('a8hak40kt9rsvn9jjy60tbwoij8bof5y', 'krhlwkcb40dvhhsaghu1u8azgig8ouct', 6, 'i3797jdxvsjumwb5uexivp4makaadm8i', 'text', 'NULL');
INSERT INTO `test_environment_attributes` VALUES ('t1dai7igddolepkbd2bhczr2ypsgii6m', 'krhlwkcb40dvhhsaghu1u8azgig8ouct', 5, 'e38mi4soc436ze33adw0y1350uyhl6ex', 'text', 'NULL');
INSERT INTO `test_environment_attributes` VALUES ('wi4m7da77x7nvt6qag594tkjbc1lwnoy', 'krhlwkcb40dvhhsaghu1u8azgig8ouct', 4, 'g3p8wwlg5kx9kd4f9lrk0tl4b9iu51vx', 'text', 'NULL');
INSERT INTO `test_environment_attributes` VALUES ('wq4phhuot1j0w87i9n5s88ta05jfxu1j', 'krhlwkcb40dvhhsaghu1u8azgig8ouct', 3, 'b572c4c6cdvkgzd0upjksdidfazwxstg', 'text', 'NULL');
INSERT INTO `test_environment_attributes` VALUES ('4fsuo4tokf1u4m2lo1e5267owxikm4c9', 'krhlwkcb40dvhhsaghu1u8azgig8ouct', 2, 'yd1zs93vsab50cbg0jowno1zyajqnc2a', 'text', 'NULL');
INSERT INTO `test_environment_attributes` VALUES ('ywrhh2zwst1uwlvjk5zwedc1w1r9uxf8', 'krhlwkcb40dvhhsaghu1u8azgig8ouct', 1, 'at745blwm8485dzc8v7wcj2j0fzc335l', 'text', 'NULL');

-- --------------------------------------------------------

-- 
-- Table structure for table `test_environment_data`
-- 

CREATE TABLE `test_environment_data` (
  `envDataId` varchar(32) NOT NULL default '',
  `pId` varchar(32) NOT NULL default '',
  `envAttributeId` varchar(32) NOT NULL default '',
  `envAttributeData` tinytext NOT NULL,
  `envDataOwnerId` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`envDataId`)
) ENGINE=MyISAM;

-- 
-- Dumping data for table `test_environment_data`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `test_finding`
-- 

CREATE TABLE `test_finding` (
  `fId` varchar(32) NOT NULL default '',
  `fText` text NOT NULL,
  `pId` varchar(32) NOT NULL default '',
  `uId` varchar(32) NOT NULL default '',
  `heurId` varchar(32) NOT NULL default '',
  `fPositive` char(1) NOT NULL default '',
  `fManagerFinding` char(1) NOT NULL default '',
  `fTimestamp` timestamp(6) NOT NULL,
  `fLastEditedTimestamp` timestamp(6) NOT NULL default '00000000000000',
  `fOrder` int(11) NOT NULL default '0',
  PRIMARY KEY  (`fId`)
) ENGINE=MyISAM;

-- 
-- Dumping data for table `test_finding`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `test_finding_rate`
-- 

CREATE TABLE `test_finding_rate` (
  `uId` varchar(32) NOT NULL default '',
  `fId` varchar(32) NOT NULL default '',
  `scaleId` varchar(32) NOT NULL default '',
  `scaleValueId` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`uId`,`fId`,`scaleValueId`)
) ENGINE=MyISAM;

-- 
-- Dumping data for table `test_finding_rate`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `test_heuristic`
-- 

CREATE TABLE `test_heuristic` (
  `hId` varchar(32) NOT NULL default '',
  `hTitle` varchar(100) NOT NULL default '',
  `hTitleId` varchar(32) NOT NULL default '',
  `hDescription` text NOT NULL,
  `hDescriptionId` varchar(32) NOT NULL default '',
  `hSetId` varchar(32) NOT NULL default '',
  `hOrder` int(10) NOT NULL default '0',
  PRIMARY KEY  (`hId`)
) ENGINE=MyISAM;

-- 
-- Dumping data for table `test_heuristic`
-- 

INSERT INTO `test_heuristic` VALUES ('ik44cv5t8pisxdov4lpvn9scn4qnqswn', '', 'qvkbomj8aun5e7xq4mdo9yczrq80xt1x', '', 'tldy5cuysoh4hinwehzpp1czw217725o', 'io1cd6u3g7e6xp75hp6cpwvnd2ivvvfk', 10);
INSERT INTO `test_heuristic` VALUES ('0ihy914vv9z6uu44qk20xq75h2p3iune', '', 'g0ji712rc6ki7wc18r8ixvbehqmcrqby', '', 'a6svqge47yrkmnl5civmbmordlypvghb', 'io1cd6u3g7e6xp75hp6cpwvnd2ivvvfk', 9);
INSERT INTO `test_heuristic` VALUES ('evluu4amjc4k2ljd3oota475fp5edhvm', '', 'koe29tbg6b6k43vxxjh4ci2bvabcjxbo', '', 'vt6p3k53cusph4fvfped35m4k3fzdtdx', 'io1cd6u3g7e6xp75hp6cpwvnd2ivvvfk', 8);
INSERT INTO `test_heuristic` VALUES ('xvo6v2ygbnnuqyx07k9mzblvt9vy3w83', '', 'il0ciqyt086bcl7xjnhfumub8h2dg26m', '', '0fxcsxg3z8iu4paptd98kf2bqal7jblg', 'io1cd6u3g7e6xp75hp6cpwvnd2ivvvfk', 7);
INSERT INTO `test_heuristic` VALUES ('67vxzy2lbvjsis1hpjoy9a0bb3ayuulv', '', 'f7cmzah1jnkcipvucnqc6gwz5lis1ic0', '', 'qqib359hvnp9y8o7u5vmebyx7p4et3k0', 'io1cd6u3g7e6xp75hp6cpwvnd2ivvvfk', 6);
INSERT INTO `test_heuristic` VALUES ('webb4si1ovaaapzf1ze6auccvlf32gng', '', 'wkc0kbarcmy5yeh7l63py70rgtvozy06', '', 'mad9252uzlu3vb1z7bjcw221gctjoy87', 'io1cd6u3g7e6xp75hp6cpwvnd2ivvvfk', 5);
INSERT INTO `test_heuristic` VALUES ('uvoaw6h95ihi8awcphqk0952r50achmi', '', 'ej6epsgjj6rhfoa9o39djic8g8sz3f3p', '', 'h1x6lzwcblv8ervu3c4ohc9ukzzj9wym', 'io1cd6u3g7e6xp75hp6cpwvnd2ivvvfk', 4);
INSERT INTO `test_heuristic` VALUES ('ud260zwckfpva0xl0fnach1agwpbb1kt', '', '5ooa2zb9bdpobpdkzqq0kzl2sui5wdcc', '', 'ej944is9kvs2pnm6vutsed2kd5twrlk5', 'io1cd6u3g7e6xp75hp6cpwvnd2ivvvfk', 3);
INSERT INTO `test_heuristic` VALUES ('wgl69bi7ugy6c9i9x4tuca7hizssi42r', '', 'anggqtkpq5tftxopwlwm0ipjtehr7uq5', '', 'drc2k3jp7xvx2stzjmlaujg7tkdnuitp', 'io1cd6u3g7e6xp75hp6cpwvnd2ivvvfk', 2);
INSERT INTO `test_heuristic` VALUES ('4ot0hthyub3fitrqzyeyyzlic5tjbne7', '', '5jsnx8xbmjr690nfcyru1iw1zrnajgzl', '', 'xeea3yzlvp076rj0rkqzl8wmyhjau3ml', 'io1cd6u3g7e6xp75hp6cpwvnd2ivvvfk', 1);

-- --------------------------------------------------------

-- 
-- Table structure for table `test_heuristic_set`
-- 

CREATE TABLE `test_heuristic_set` (
  `hSetId` varchar(32) NOT NULL default '',
  `hSetTitle` varchar(100) NOT NULL default '',
  `hSetTitleId` varchar(32) NOT NULL default '',
  `hSetDescription` text NOT NULL,
  `hSetDescriptionId` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`hSetId`)
) ENGINE=MyISAM;

-- 
-- Dumping data for table `test_heuristic_set`
-- 

INSERT INTO `test_heuristic_set` VALUES ('io1cd6u3g7e6xp75hp6cpwvnd2ivvvfk', '', 'wjpjn8frbexxnko1ublijm75v5mges5q', '', 'p0up8b5s1ok43w0xp4i2nwmcmf20mawa');

-- --------------------------------------------------------

-- 
-- Table structure for table `test_liveuser_applications`
-- 

CREATE TABLE `test_liveuser_applications` (
  `application_id` int(11) unsigned NOT NULL default '0',
  `application_define_name` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`application_id`),
  UNIQUE KEY `application_define_name` (`application_define_name`)
) ENGINE=MyISAM;

-- 
-- Dumping data for table `test_liveuser_applications`
-- 

INSERT INTO `test_liveuser_applications` VALUES (1, 'HEM');

-- --------------------------------------------------------

-- 
-- Table structure for table `test_liveuser_applications_seq`
-- 

CREATE TABLE `test_liveuser_applications_seq` (
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `test_liveuser_applications_seq`
-- 

INSERT INTO `test_liveuser_applications_seq` VALUES (1);

-- --------------------------------------------------------

-- 
-- Table structure for table `test_liveuser_area_admin_areas`
-- 

CREATE TABLE `test_liveuser_area_admin_areas` (
  `area_id` int(11) unsigned NOT NULL default '0',
  `perm_user_id` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`area_id`,`perm_user_id`)
) ENGINE=MyISAM;

-- 
-- Dumping data for table `test_liveuser_area_admin_areas`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `test_liveuser_areas`
-- 

CREATE TABLE `test_liveuser_areas` (
  `area_id` int(11) unsigned NOT NULL default '0',
  `application_id` int(11) unsigned NOT NULL default '0',
  `area_define_name` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`area_id`),
  UNIQUE KEY `area_define_name` (`application_id`,`area_define_name`),
  KEY `areas_application_id` (`application_id`)
) ENGINE=MyISAM;

-- 
-- Dumping data for table `test_liveuser_areas`
-- 

INSERT INTO `test_liveuser_areas` VALUES (1, 1, 'AREA');

-- --------------------------------------------------------

-- 
-- Table structure for table `test_liveuser_areas_seq`
-- 

CREATE TABLE `test_liveuser_areas_seq` (
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `test_liveuser_areas_seq`
-- 

INSERT INTO `test_liveuser_areas_seq` VALUES (1);

-- --------------------------------------------------------

-- 
-- Table structure for table `test_liveuser_group_subgroups`
-- 

CREATE TABLE `test_liveuser_group_subgroups` (
  `group_id` int(11) unsigned NOT NULL default '0',
  `subgroup_id` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`group_id`,`subgroup_id`)
) ENGINE=MyISAM;

-- 
-- Dumping data for table `test_liveuser_group_subgroups`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `test_liveuser_grouprights`
-- 

CREATE TABLE `test_liveuser_grouprights` (
  `group_id` int(11) unsigned NOT NULL default '0',
  `right_id` int(11) unsigned NOT NULL default '0',
  `right_level` tinyint(3) unsigned default '3',
  PRIMARY KEY  (`group_id`,`right_id`)
) ENGINE=MyISAM;

-- 
-- Dumping data for table `test_liveuser_grouprights`
-- 

INSERT INTO `test_liveuser_grouprights` VALUES (2, 1, 3);
INSERT INTO `test_liveuser_grouprights` VALUES (3, 1, 3);
INSERT INTO `test_liveuser_grouprights` VALUES (2, 2, 3);
INSERT INTO `test_liveuser_grouprights` VALUES (3, 2, 3);
INSERT INTO `test_liveuser_grouprights` VALUES (2, 3, 3);
INSERT INTO `test_liveuser_grouprights` VALUES (3, 3, 3);
INSERT INTO `test_liveuser_grouprights` VALUES (2, 4, 3);
INSERT INTO `test_liveuser_grouprights` VALUES (3, 4, 3);
INSERT INTO `test_liveuser_grouprights` VALUES (2, 5, 3);
INSERT INTO `test_liveuser_grouprights` VALUES (3, 5, 3);
INSERT INTO `test_liveuser_grouprights` VALUES (3, 7, 3);
INSERT INTO `test_liveuser_grouprights` VALUES (2, 7, 3);
INSERT INTO `test_liveuser_grouprights` VALUES (2, 6, 3);
INSERT INTO `test_liveuser_grouprights` VALUES (3, 6, 3);
INSERT INTO `test_liveuser_grouprights` VALUES (3, 9, 3);
INSERT INTO `test_liveuser_grouprights` VALUES (2, 9, 3);
INSERT INTO `test_liveuser_grouprights` VALUES (3, 8, 3);
INSERT INTO `test_liveuser_grouprights` VALUES (2, 8, 3);
INSERT INTO `test_liveuser_grouprights` VALUES (3, 10, 3);
INSERT INTO `test_liveuser_grouprights` VALUES (2, 10, 3);
INSERT INTO `test_liveuser_grouprights` VALUES (1, 10, 3);
INSERT INTO `test_liveuser_grouprights` VALUES (3, 11, 3);
INSERT INTO `test_liveuser_grouprights` VALUES (2, 11, 3);
INSERT INTO `test_liveuser_grouprights` VALUES (3, 13, 3);
INSERT INTO `test_liveuser_grouprights` VALUES (2, 13, 3);
INSERT INTO `test_liveuser_grouprights` VALUES (1, 13, 3);
INSERT INTO `test_liveuser_grouprights` VALUES (3, 14, 3);
INSERT INTO `test_liveuser_grouprights` VALUES (2, 14, 3);
INSERT INTO `test_liveuser_grouprights` VALUES (3, 15, 3);
INSERT INTO `test_liveuser_grouprights` VALUES (2, 15, 3);
INSERT INTO `test_liveuser_grouprights` VALUES (3, 16, 3);
INSERT INTO `test_liveuser_grouprights` VALUES (2, 16, 3);
INSERT INTO `test_liveuser_grouprights` VALUES (1, 16, 3);
INSERT INTO `test_liveuser_grouprights` VALUES (3, 17, 3);
INSERT INTO `test_liveuser_grouprights` VALUES (2, 17, 3);
INSERT INTO `test_liveuser_grouprights` VALUES (3, 18, 3);
INSERT INTO `test_liveuser_grouprights` VALUES (2, 18, 3);
INSERT INTO `test_liveuser_grouprights` VALUES (3, 19, 3);
INSERT INTO `test_liveuser_grouprights` VALUES (2, 19, 3);
INSERT INTO `test_liveuser_grouprights` VALUES (3, 20, 3);
INSERT INTO `test_liveuser_grouprights` VALUES (2, 20, 3);
INSERT INTO `test_liveuser_grouprights` VALUES (3, 21, 3);
INSERT INTO `test_liveuser_grouprights` VALUES (2, 21, 3);

-- --------------------------------------------------------

-- 
-- Table structure for table `test_liveuser_groups`
-- 

CREATE TABLE `test_liveuser_groups` (
  `group_id` int(11) unsigned NOT NULL default '0',
  `group_type` int(11) unsigned default '1',
  `group_define_name` varchar(32) NOT NULL default '',
  `owner_user_id` int(11) unsigned default NULL,
  `owner_group_id` int(11) unsigned default NULL,
  `is_active` char(1) NOT NULL default 'N',
  PRIMARY KEY  (`group_id`),
  UNIQUE KEY `group_define_name` (`group_define_name`)
) ENGINE=MyISAM;

-- 
-- Dumping data for table `test_liveuser_groups`
-- 

INSERT INTO `test_liveuser_groups` VALUES (1, 1, 'EVALUATOR', NULL, NULL, 'Y');
INSERT INTO `test_liveuser_groups` VALUES (2, 1, 'MANAGER', NULL, NULL, 'Y');
INSERT INTO `test_liveuser_groups` VALUES (3, 1, 'ADMIN', NULL, NULL, 'Y');

-- --------------------------------------------------------

-- 
-- Table structure for table `test_liveuser_groups_seq`
-- 

CREATE TABLE `test_liveuser_groups_seq` (
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 ;

-- 
-- Dumping data for table `test_liveuser_groups_seq`
-- 

INSERT INTO `test_liveuser_groups_seq` VALUES (3);

-- --------------------------------------------------------

-- 
-- Table structure for table `test_liveuser_groupusers`
-- 

CREATE TABLE `test_liveuser_groupusers` (
  `perm_user_id` int(11) unsigned NOT NULL default '0',
  `group_id` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`group_id`,`perm_user_id`)
) ENGINE=MyISAM;

-- 
-- Dumping data for table `test_liveuser_groupusers`
-- 

INSERT INTO `test_liveuser_groupusers` VALUES (10, 1);
INSERT INTO `test_liveuser_groupusers` VALUES (10, 2);
INSERT INTO `test_liveuser_groupusers` VALUES (10, 3);

-- --------------------------------------------------------

-- 
-- Table structure for table `test_liveuser_languages`
-- 

CREATE TABLE `test_liveuser_languages` (
  `language_id` smallint(5) unsigned NOT NULL default '0',
  `two_letter_name` char(2) NOT NULL default '',
  PRIMARY KEY  (`language_id`),
  UNIQUE KEY `two_letter_name` (`two_letter_name`)
) ENGINE=MyISAM;

-- 
-- Dumping data for table `test_liveuser_languages`
-- 

INSERT INTO `test_liveuser_languages` VALUES (1, 'en');

-- --------------------------------------------------------

-- 
-- Table structure for table `test_liveuser_languages_seq`
-- 

CREATE TABLE `test_liveuser_languages_seq` (
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `test_liveuser_languages_seq`
-- 

INSERT INTO `test_liveuser_languages_seq` VALUES (1);

-- --------------------------------------------------------

-- 
-- Table structure for table `test_liveuser_perm_users`
-- 

CREATE TABLE `test_liveuser_perm_users` (
  `perm_user_id` int(11) unsigned NOT NULL default '0',
  `auth_user_id` varchar(32) NOT NULL default '0',
  `perm_type` tinyint(3) unsigned default NULL,
  `auth_container_name` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`perm_user_id`)
) ENGINE=MyISAM;

-- 
-- Dumping data for table `test_liveuser_perm_users`
-- 

INSERT INTO `test_liveuser_perm_users` VALUES (10, '29214857b12575501c5c731353c7217e', 1, 'DB_Local');

-- --------------------------------------------------------

-- 
-- Table structure for table `test_liveuser_perm_users_seq`
-- 

CREATE TABLE `test_liveuser_perm_users_seq` (
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=49 ;

-- 
-- Dumping data for table `test_liveuser_perm_users_seq`
-- 

INSERT INTO `test_liveuser_perm_users_seq` VALUES (48);

-- --------------------------------------------------------

-- 
-- Table structure for table `test_liveuser_right_implied`
-- 

CREATE TABLE `test_liveuser_right_implied` (
  `right_id` int(11) unsigned NOT NULL default '0',
  `implied_right_id` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`implied_right_id`,`right_id`)
) ENGINE=MyISAM;

-- 
-- Dumping data for table `test_liveuser_right_implied`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `test_liveuser_right_scopes`
-- 

CREATE TABLE `test_liveuser_right_scopes` (
  `right_id` int(11) unsigned NOT NULL default '0',
  `right_type` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`right_id`)
) ENGINE=MyISAM;

-- 
-- Dumping data for table `test_liveuser_right_scopes`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `test_liveuser_rights`
-- 

CREATE TABLE `test_liveuser_rights` (
  `right_id` int(11) unsigned NOT NULL default '0',
  `area_id` int(11) unsigned NOT NULL default '0',
  `right_define_name` varchar(32) NOT NULL default '',
  `has_implied` char(1) NOT NULL default 'N',
  `has_level` char(1) NOT NULL default 'N',
  `has_scope` char(1) NOT NULL default 'N',
  PRIMARY KEY  (`right_id`),
  UNIQUE KEY `right_define_name` (`area_id`,`right_define_name`),
  KEY `rights_area_id` (`area_id`)
) ENGINE=MyISAM;

-- 
-- Dumping data for table `test_liveuser_rights`
-- 

INSERT INTO `test_liveuser_rights` VALUES (1, 1, 'VIEW_MANAGER_DATA', 'N', 'N', 'N');
INSERT INTO `test_liveuser_rights` VALUES (2, 1, 'CHANGE_GROUP_MEMBERSHIP', 'N', 'N', 'N');
INSERT INTO `test_liveuser_rights` VALUES (3, 1, 'CHANGE_OTHER_USERS', 'N', 'N', 'N');
INSERT INTO `test_liveuser_rights` VALUES (4, 1, 'CHANGE_OTHER_PASSWORD', 'N', 'N', 'N');
INSERT INTO `test_liveuser_rights` VALUES (5, 1, 'CHANGE_OTHER_USERNAME', 'N', 'N', 'N');
INSERT INTO `test_liveuser_rights` VALUES (6, 1, 'CHANGE_ACTIVE_FLAG', 'N', 'N', 'N');
INSERT INTO `test_liveuser_rights` VALUES (7, 1, 'EDIT_HEURISTIC_SETS', 'N', 'N', 'N');
INSERT INTO `test_liveuser_rights` VALUES (8, 1, 'MERGE_FINDINGS', 'N', 'N', 'N');
INSERT INTO `test_liveuser_rights` VALUES (9, 1, 'MANAGE_PROJECTS', 'N', 'N', 'N');
INSERT INTO `test_liveuser_rights` VALUES (10, 1, 'COLLECT_FINDINGS', 'N', 'N', 'N');
INSERT INTO `test_liveuser_rights` VALUES (11, 1, 'MANAGE_HEURISTICS', 'N', 'N', 'N');
INSERT INTO `test_liveuser_rights` VALUES (12, 1, 'MANAGER_ENVIRONMENTS', 'N', 'N', 'N');
INSERT INTO `test_liveuser_rights` VALUES (13, 1, 'ADD_ENVIRONMENT_DATA', 'N', 'N', 'N');
INSERT INTO `test_liveuser_rights` VALUES (14, 1, 'CHANGE_OTHER_FINDINGS', 'N', 'N', 'N');
INSERT INTO `test_liveuser_rights` VALUES (15, 1, 'CHANGE_OTHER_RATINGS', 'N', 'N', 'N');
INSERT INTO `test_liveuser_rights` VALUES (16, 1, 'COLLECT_RATINGS', 'N', 'N', 'N');
INSERT INTO `test_liveuser_rights` VALUES (17, 1, 'MANAGE_RATINGSCALES', 'N', 'N', 'N');
INSERT INTO `test_liveuser_rights` VALUES (18, 1, 'MANAGE_RATINGSCHEMES', 'N', 'N', 'N');
INSERT INTO `test_liveuser_rights` VALUES (19, 1, 'MANAGE_ENVIRONMENTS', 'N', 'N', 'N');
INSERT INTO `test_liveuser_rights` VALUES (20, 1, 'CHANGE_OTHER_ENVIRONMENTS', 'N', 'N', 'N');
INSERT INTO `test_liveuser_rights` VALUES (21, 1, 'MANAGE_REPORTS', 'N', 'N', 'N');

-- --------------------------------------------------------

-- 
-- Table structure for table `test_liveuser_rights_seq`
-- 

CREATE TABLE `test_liveuser_rights_seq` (
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 ;

-- 
-- Dumping data for table `test_liveuser_rights_seq`
-- 

INSERT INTO `test_liveuser_rights_seq` VALUES (21);

-- --------------------------------------------------------

-- 
-- Table structure for table `test_liveuser_translations`
-- 

CREATE TABLE `test_liveuser_translations` (
  `section_id` int(11) unsigned NOT NULL default '0',
  `section_type` tinyint(3) unsigned NOT NULL default '0',
  `language_id` smallint(5) unsigned NOT NULL default '0',
  `name` varchar(50) NOT NULL default '',
  `description` varchar(255) default NULL,
  PRIMARY KEY  (`section_id`,`section_type`,`language_id`)
) ENGINE=MyISAM;

-- 
-- Dumping data for table `test_liveuser_translations`
-- 

INSERT INTO `test_liveuser_translations` VALUES (1, 4, 1, 'english', 'English language');
INSERT INTO `test_liveuser_translations` VALUES (1, 1, 1, 'Heuristic Evaluation Manager', NULL);
INSERT INTO `test_liveuser_translations` VALUES (1, 2, 1, 'The Only Area', NULL);
INSERT INTO `test_liveuser_translations` VALUES (1, 3, 1, 'evaluator', 'The Evaluators');
INSERT INTO `test_liveuser_translations` VALUES (2, 3, 1, 'manager', 'The Managers');
INSERT INTO `test_liveuser_translations` VALUES (3, 3, 1, 'admin', 'The Administrators');
INSERT INTO `test_liveuser_translations` VALUES (1, 5, 1, 'View Manager Data', 'Right to view Manager Data');
INSERT INTO `test_liveuser_translations` VALUES (2, 5, 1, 'Change Group Membership', 'Change the group membership of a User');
INSERT INTO `test_liveuser_translations` VALUES (3, 5, 1, 'Change other users data', 'Change other users data');
INSERT INTO `test_liveuser_translations` VALUES (4, 5, 1, 'Change other users password', 'Change the Password of another user');
INSERT INTO `test_liveuser_translations` VALUES (5, 5, 1, 'Change other username', 'The Right to change the username from an other user');
INSERT INTO `test_liveuser_translations` VALUES (6, 5, 1, 'Change Active Flag', 'Right to activate a useraccount');
INSERT INTO `test_liveuser_translations` VALUES (7, 5, 1, 'Edit Heuristics', 'The Right to change and delete Heuristoc Sets and Heuristics');
INSERT INTO `test_liveuser_translations` VALUES (8, 5, 1, 'Merge Findings', 'The right to merge Findings');
INSERT INTO `test_liveuser_translations` VALUES (9, 5, 1, 'Manage Projects', 'The right to manage Projects');
INSERT INTO `test_liveuser_translations` VALUES (10, 5, 1, 'Evaluate', 'The right to add findings to database');
INSERT INTO `test_liveuser_translations` VALUES (11, 5, 1, 'Manage Heuristic Sets', 'The Right to manage heuristic Sets');
INSERT INTO `test_liveuser_translations` VALUES (12, 5, 1, 'Manage Environments', 'The Right to manage Evaluation Environemnet Forms');
INSERT INTO `test_liveuser_translations` VALUES (13, 5, 1, 'Add Environment Data', 'The right to fill out environment Data for Project');
INSERT INTO `test_liveuser_translations` VALUES (14, 5, 1, 'Change other findings', 'The right to change other findings, also after end of E Phase');
INSERT INTO `test_liveuser_translations` VALUES (15, 5, 1, 'Change other users ratings', 'The right to chnage other users ratings');
INSERT INTO `test_liveuser_translations` VALUES (16, 5, 1, 'The right to collect ratings', 'Use the rating collector');
INSERT INTO `test_liveuser_translations` VALUES (17, 5, 1, 'The right to manage rating scales', '');
INSERT INTO `test_liveuser_translations` VALUES (18, 5, 1, 'The right to manage rating schemes', '');
INSERT INTO `test_liveuser_translations` VALUES (19, 5, 1, 'The right to manage environments', '');
INSERT INTO `test_liveuser_translations` VALUES (20, 5, 1, 'The right to change other users Environment Data', '');
INSERT INTO `test_liveuser_translations` VALUES (21, 5, 1, 'The right to generate and view reports', '');

-- --------------------------------------------------------

-- 
-- Table structure for table `test_liveuser_userrights`
-- 

CREATE TABLE `test_liveuser_userrights` (
  `perm_user_id` int(11) unsigned NOT NULL default '0',
  `right_id` int(11) unsigned NOT NULL default '0',
  `right_level` tinyint(3) default '3',
  PRIMARY KEY  (`right_id`,`perm_user_id`)
) ENGINE=MyISAM;

-- 
-- Dumping data for table `test_liveuser_userrights`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `test_liveuser_users`
-- 

CREATE TABLE `test_liveuser_users` (
  `auth_user_id` varchar(32) NOT NULL default '0',
  `handle` varchar(32) NOT NULL default '',
  `passwd` varchar(32) NOT NULL default '',
  `lastlogin` datetime default NULL,
  `owner_user_id` bigint(20) unsigned default NULL,
  `owner_group_id` int(11) unsigned default NULL,
  `is_active` char(1) NOT NULL default 'N',
  PRIMARY KEY  (`auth_user_id`,`handle`)
) ENGINE=MyISAM;

-- 
-- Dumping data for table `test_liveuser_users`
-- 

INSERT INTO `test_liveuser_users` VALUES ('29214857b12575501c5c731353c7217e', 'johndoe', '6579e96f76baa00787a28653876c6127', '2005-06-27 11:59:04', NULL, NULL, 'Y');

-- --------------------------------------------------------

-- 
-- Table structure for table `test_liveuser_users_seq`
-- 

CREATE TABLE `test_liveuser_users_seq` (
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 ;

-- 
-- Dumping data for table `test_liveuser_users_seq`
-- 

INSERT INTO `test_liveuser_users_seq` VALUES (3);

-- --------------------------------------------------------

-- 
-- Table structure for table `test_manager_evaluator_finding`
-- 

CREATE TABLE `test_manager_evaluator_finding` (
  `aID` varchar(32) NOT NULL default '',
  `mfId` varchar(32) NOT NULL default '',
  `efId` varchar(32) NOT NULL default '',
  `aDate` timestamp(6) NOT NULL,
  PRIMARY KEY  (`aID`)
) ENGINE=MyISAM;

-- 
-- Dumping data for table `test_manager_evaluator_finding`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `test_project`
-- 

CREATE TABLE `test_project` (
  `pId` varchar(32) NOT NULL default '',
  `pNameId` varchar(32) NOT NULL default '',
  `pDescriptionId` varchar(32) NOT NULL default '',
  `pPhase` char(1) NOT NULL default '',
  `heurSetId` varchar(32) NOT NULL default '',
  `envId` varchar(32) NOT NULL default '',
  `schemeId` varchar(32) NOT NULL default '',
  `pAdded` timestamp(6) NOT NULL,
  PRIMARY KEY  (`pId`)
) ENGINE=MyISAM;

-- 
-- Dumping data for table `test_project`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `test_project_user`
-- 

CREATE TABLE `test_project_user` (
  `pId` varchar(32) NOT NULL default '',
  `uId` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`pId`,`uId`)
) ENGINE=MyISAM;

-- 
-- Dumping data for table `test_project_user`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `test_rating_scale`
-- 

CREATE TABLE `test_rating_scale` (
  `scaleId` varchar(32) NOT NULL default '',
  `scaleTitleId` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`scaleId`)
) ENGINE=MyISAM;

-- 
-- Dumping data for table `test_rating_scale`
-- 

INSERT INTO `test_rating_scale` VALUES ('1', '10');

-- --------------------------------------------------------

-- 
-- Table structure for table `test_rating_scale_value`
-- 

CREATE TABLE `test_rating_scale_value` (
  `scaleValueId` varchar(32) NOT NULL default '',
  `scaleValue` int(10) NOT NULL default '0',
  `scaleValueCaptionId` varchar(32) NOT NULL default '',
  `scaleId` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`scaleValueId`)
) ENGINE=MyISAM;

-- 
-- Dumping data for table `test_rating_scale_value`
-- 

INSERT INTO `test_rating_scale_value` VALUES ('uzmhm5fncydx5pa65fa11d6ttozlj0mu', 4, 'yg1etueb0otsoxevic7ca6yaw03ikmfp', '1');
INSERT INTO `test_rating_scale_value` VALUES ('kbzp80b2xnpe0rkan8v8n3pc391gsflg', 3, 'xqljf8q070jo9pdgq0adcynifccqnvk0', '1');
INSERT INTO `test_rating_scale_value` VALUES ('c9z9aum8nkafq74nfusv93ef3rwog6k4', 2, 'zqb7bjl82k1ha0ekscr3kpivsmj83v4i', '1');
INSERT INTO `test_rating_scale_value` VALUES ('112', 1, '12', '1');
INSERT INTO `test_rating_scale_value` VALUES ('j1qiay8xep4m7uzeod1792z419j63rn2', 0, '5jr6y95jbakr4rke70djkotjmtfru0bc', '1');

-- --------------------------------------------------------

-- 
-- Table structure for table `test_ratingscheme`
-- 

CREATE TABLE `test_ratingscheme` (
  `schemeId` varchar(32) NOT NULL default '',
  `schemeTitleId` varchar(32) NOT NULL default '',
  `schemeResultOperation` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`schemeId`)
) ENGINE=MyISAM;

-- 
-- Dumping data for table `test_ratingscheme`
-- 

INSERT INTO `test_ratingscheme` VALUES ('9inwqh48640qim60l2c67bz1lgscmh1z', 'cmupsnpds9y8fujqodgkexwnc2n4eq33', 'sum');

-- --------------------------------------------------------

-- 
-- Table structure for table `test_ratingscheme_scale`
-- 

CREATE TABLE `test_ratingscheme_scale` (
  `schemeId` varchar(32) NOT NULL default '',
  `scaleId` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`schemeId`,`scaleId`)
) ENGINE=MyISAM;

-- 
-- Dumping data for table `test_ratingscheme_scale`
-- 

INSERT INTO `test_ratingscheme_scale` VALUES ('9inwqh48640qim60l2c67bz1lgscmh1z', '1');

-- --------------------------------------------------------

-- 
-- Table structure for table `test_report_element`
-- 

CREATE TABLE `test_report_element` (
  `elementId` varchar(32) NOT NULL default '',
  `elementType` varchar(100) NOT NULL default '',
  `elementOrder` int(11) NOT NULL default '0',
  `elementData` text NOT NULL,
  `chId` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`elementId`)
) ENGINE=MyISAM;

-- 
-- Dumping data for table `test_report_element`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `test_screenshot`
-- 

CREATE TABLE `test_screenshot` (
  `sId` varchar(32) NOT NULL default '',
  `sFileName` varchar(255) NOT NULL default '',
  `sName` varchar(255) NOT NULL default '',
  `sMimeType` varchar(40) NOT NULL default '',
  `sFileSize` int(11) NOT NULL default '0',
  `sKind` varchar(100) NOT NULL default '',
  `fId` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`sId`)
) ENGINE=MyISAM;

-- 
-- Dumping data for table `test_screenshot`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `test_themes`
-- 

CREATE TABLE `test_themes` (
  `theme_id` varchar(32) NOT NULL default '',
  `css_file_name` varchar(100) NOT NULL default '',
  `theme_name` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`theme_id`),
  UNIQUE KEY `theme_name` (`theme_name`)
) ENGINE=MyISAM;

-- 
-- Dumping data for table `test_themes`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `test_translation`
-- 

CREATE TABLE `test_translation` (
  `tId` varchar(32) NOT NULL default '',
  `tLanguage` char(2) NOT NULL default '',
  `tString` text NOT NULL,
  PRIMARY KEY  (`tId`,`tLanguage`)
) ENGINE=MyISAM;

-- 
-- Dumping data for table `test_translation`
-- 

INSERT INTO `test_translation` VALUES ('wjpjn8frbexxnko1ublijm75v5mges5q', 'US', 'Nielsen: Ten Usability Heuristics');
INSERT INTO `test_translation` VALUES ('wjpjn8frbexxnko1ublijm75v5mges5q', 'DE', 'Nielsen: 10 Usability Heuristiken');
INSERT INTO `test_translation` VALUES ('p0up8b5s1ok43w0xp4i2nwmcmf20mawa', 'US', 'These are ten general principles for user interface design. They are called "heuristics" because they are more in the nature of rules of thumb than specific usability guidelines');
INSERT INTO `test_translation` VALUES ('p0up8b5s1ok43w0xp4i2nwmcmf20mawa', 'DE', '10 Prinzipien f�r das Design von Benutzerschnitstellen. Diese hei�en "Heuristiken", weil sie eher einen Leitfaden, als ein festes Regelwerk darstellen');
INSERT INTO `test_translation` VALUES ('qvkbomj8aun5e7xq4mdo9yczrq80xt1x', 'US', 'Match between system and the real world');
INSERT INTO `test_translation` VALUES ('qvkbomj8aun5e7xq4mdo9yczrq80xt1x', 'DE', 'Verbindung des Systems und der realen Welt');
INSERT INTO `test_translation` VALUES ('tldy5cuysoh4hinwehzpp1czw217725o', 'US', 'The system should speak the users language, with words, phrases and concepts familiar to the user, rather than system-oriented terms. Follow real-world conventions, making information appear in a natural and logical order.');
INSERT INTO `test_translation` VALUES ('tldy5cuysoh4hinwehzpp1czw217725o', 'DE', 'Das System sollte die Sprache des Benutzers sprechen. Ausdr�cke und Konzepte, die der Benutzer kennt sollten gegen�ber systemorientierten Begriffen bevorzugt werden. Konventionen aus der realen Welt stellen Informationen in einer nat�rlichen und logischen Ordnung dar.');
INSERT INTO `test_translation` VALUES ('g0ji712rc6ki7wc18r8ixvbehqmcrqby', 'US', 'User control and freedom');
INSERT INTO `test_translation` VALUES ('g0ji712rc6ki7wc18r8ixvbehqmcrqby', 'DE', 'Kontrolle durch Benutzer und Freiheit(?)');
INSERT INTO `test_translation` VALUES ('a6svqge47yrkmnl5civmbmordlypvghb', 'US', 'Users often choose system functions by mistake and will need a clearly marked "emergency exit" to leave the unwanted state without having to go through an extended dialogue. Support undo and redo.');
INSERT INTO `test_translation` VALUES ('a6svqge47yrkmnl5civmbmordlypvghb', 'DE', 'Funktionen des Systems werden vom Benutzer oft durch einen Irrtum aktiviert. Das System sollte klar erkennbare M�glichkeiten bieten, einen ungewollten Zustand zu korrigieren, ohne daf�r komplexe Dialoge �ber sich ergehen zu lassen. Das System sollte Funktionen zum R�ckg�ngigmachen zur Verf�gung stellen.');
INSERT INTO `test_translation` VALUES ('koe29tbg6b6k43vxxjh4ci2bvabcjxbo', 'US', 'Visibility of system status');
INSERT INTO `test_translation` VALUES ('koe29tbg6b6k43vxxjh4ci2bvabcjxbo', 'DE', 'Sichtbarkeit des System Status');
INSERT INTO `test_translation` VALUES ('vt6p3k53cusph4fvfped35m4k3fzdtdx', 'US', 'The system should always keep users informed about what is going on, through appropriate feedback within reasonable time.');
INSERT INTO `test_translation` VALUES ('vt6p3k53cusph4fvfped35m4k3fzdtdx', 'DE', 'Das System sollte den Benutzer stehts informaieren, was das System gerade macht, adequate Informationen zur richtigen Zeit liefern.');
INSERT INTO `test_translation` VALUES ('il0ciqyt086bcl7xjnhfumub8h2dg26m', 'US', 'Consistency and standards');
INSERT INTO `test_translation` VALUES ('il0ciqyt086bcl7xjnhfumub8h2dg26m', 'DE', 'Konsistenz und Einhaltung von Standards');
INSERT INTO `test_translation` VALUES ('0fxcsxg3z8iu4paptd98kf2bqal7jblg', 'US', 'Users should not have to wonder whether different words, situations, or actions mean the same thing. Follow platform conventions.');
INSERT INTO `test_translation` VALUES ('0fxcsxg3z8iu4paptd98kf2bqal7jblg', 'DE', 'Der Benutzer sollte nicht dazu gezwungen sein unterschiedliche W�rter, Situationen und Aktionen, die das selbe bedeuten jeweils neu interpretieren zu m�ssen. Man sollte sich an (plattformabh�ngige) Konventionen halten.');
INSERT INTO `test_translation` VALUES ('f7cmzah1jnkcipvucnqc6gwz5lis1ic0', 'US', 'Help and documentation');
INSERT INTO `test_translation` VALUES ('f7cmzah1jnkcipvucnqc6gwz5lis1ic0', 'DE', 'Hilfe und Dokumentation');
INSERT INTO `test_translation` VALUES ('qqib359hvnp9y8o7u5vmebyx7p4et3k0', 'US', 'Even though it is better if the system can be used without documentation, it may be necessary to provide help and documentation. Any such information should be easy to search, focused on the users task, list concrete steps to be carried out, and not be too large.');
INSERT INTO `test_translation` VALUES ('qqib359hvnp9y8o7u5vmebyx7p4et3k0', 'DE', 'Auch wenn das System so intuitiv ist, das es ohne Dokumenation auskommt, kann es n�tig sein Hilfe und Dokumentation bereit zu stellen. Jeder Teil dieser Dokumentation sollte einfach zu durchsuchen sein, im Kontext zur Aufgabe des Benutzers stehen, Schritte f�r ein konkretes Vorgehen auflisten und nicht zu gro� sein.');
INSERT INTO `test_translation` VALUES ('wkc0kbarcmy5yeh7l63py70rgtvozy06', 'US', 'Help users recognize, diagnose, and recover from errors');
INSERT INTO `test_translation` VALUES ('wkc0kbarcmy5yeh7l63py70rgtvozy06', 'DE', 'Der Benutzer soll Fehler erkennen, einsch�tzen und korrigieren k�nnen');
INSERT INTO `test_translation` VALUES ('mad9252uzlu3vb1z7bjcw221gctjoy87', 'US', 'Error messages should be expressed in plain language (no codes), precisely indicate the problem, and constructively suggest a solution.');
INSERT INTO `test_translation` VALUES ('mad9252uzlu3vb1z7bjcw221gctjoy87', 'DE', 'Fehlermeldungen sollten in Textform ausgegeben werden (keine Codes), pr�zise das Problem beschreiben und konstruktiv eine L�sung vorschlagen.');
INSERT INTO `test_translation` VALUES ('ej6epsgjj6rhfoa9o39djic8g8sz3f3p', 'US', 'Aesthetic and minimalist design');
INSERT INTO `test_translation` VALUES ('ej6epsgjj6rhfoa9o39djic8g8sz3f3p', 'DE', '�stetik und Minimalismus im Design');
INSERT INTO `test_translation` VALUES ('h1x6lzwcblv8ervu3c4ohc9ukzzj9wym', 'US', 'Dialogues should not contain information which is irrelevant or rarely needed. Every extra unit of information in a dialogue competes with the relevant units of information and diminishes their relative visibility.');
INSERT INTO `test_translation` VALUES ('h1x6lzwcblv8ervu3c4ohc9ukzzj9wym', 'DE', 'Dialoge sollten keine Informationen beeinhalten, die im Kontext irrelevant sind, oder selten ben�tigt werden. Jede �berfl�ssige Information im Dialog, steht im Konflikt mit wichtigen Informationen und verschleiert deren Sichtbarkeit.');
INSERT INTO `test_translation` VALUES ('5ooa2zb9bdpobpdkzqq0kzl2sui5wdcc', 'US', 'Flexibility and efficiency of use');
INSERT INTO `test_translation` VALUES ('5ooa2zb9bdpobpdkzqq0kzl2sui5wdcc', 'DE', 'Flexibilit�t und Effektivit�t in der Anwendung');
INSERT INTO `test_translation` VALUES ('ej944is9kvs2pnm6vutsed2kd5twrlk5', 'US', 'Accelerators -- unseen by the novice user -- may often speed up the interaction for the expert user such that the system can cater to both inexperienced and experienced users. Allow users to tailor frequent actions.');
INSERT INTO `test_translation` VALUES ('ej944is9kvs2pnm6vutsed2kd5twrlk5', 'DE', 'Tastenk�rzel - vor dem Anf�nger verborgen - k�nnen die Geschwindigkeit der Interaktion f�r einen erfahrenen Benutzer beschleunigen. Ein solches System kann von beiden, unerfahrenen und erfahrenen Benutzerm effektiv benutzt werden. Es sollte die M�glichkeit bestehen, eigene Tastenk�rzel zu definieren.');
INSERT INTO `test_translation` VALUES ('anggqtkpq5tftxopwlwm0ipjtehr7uq5', 'US', 'Recognition rather than recall');
INSERT INTO `test_translation` VALUES ('anggqtkpq5tftxopwlwm0ipjtehr7uq5', 'DE', 'Erkennung ist besser als Erinnerung');
INSERT INTO `test_translation` VALUES ('drc2k3jp7xvx2stzjmlaujg7tkdnuitp', 'US', 'Make objects, actions, and options visible. The user should not have to remember information from one part of the dialogue to another. Instructions for use of the system should be visible or easily retrievable whenever appropriate.');
INSERT INTO `test_translation` VALUES ('drc2k3jp7xvx2stzjmlaujg7tkdnuitp', 'DE', 'Objekte, Aktionen und Optionen sollen sichtbar sein. Der Benutzer sollte nicht dazu gezwungen sein, sich Informationen �ber mehrere Teile des Dialogs hinweg, zu merken. Hinweise f�r die Benutzung des Systems sollten sichtbar sein, oder einfach zu erhalten sein, wann immer diese begraucht werden.');
INSERT INTO `test_translation` VALUES ('5jsnx8xbmjr690nfcyru1iw1zrnajgzl', 'US', 'Error prevention');
INSERT INTO `test_translation` VALUES ('5jsnx8xbmjr690nfcyru1iw1zrnajgzl', 'DE', 'Vermeidung von Fehlern');
INSERT INTO `test_translation` VALUES ('xeea3yzlvp076rj0rkqzl8wmyhjau3ml', 'US', 'Even better than good error messages is a careful design which prevents a problem from occurring in the first place.');
INSERT INTO `test_translation` VALUES ('xeea3yzlvp076rj0rkqzl8wmyhjau3ml', 'DE', 'Besser als gute Fehlermeldungen ist ein Konzept, das Fehler vermeidet.');
INSERT INTO `test_translation` VALUES ('cmupsnpds9y8fujqodgkexwnc2n4eq33', 'US', 'Severity');
INSERT INTO `test_translation` VALUES ('cmupsnpds9y8fujqodgkexwnc2n4eq33', 'DE', 'Gewicht');
INSERT INTO `test_translation` VALUES ('10', 'US', 'Severity');
INSERT INTO `test_translation` VALUES ('10', 'DE', 'Schwere');
INSERT INTO `test_translation` VALUES ('yg1etueb0otsoxevic7ca6yaw03ikmfp', 'US', 'catastrophic problem');
INSERT INTO `test_translation` VALUES ('yg1etueb0otsoxevic7ca6yaw03ikmfp', 'DE', 'katastrophales Problem');
INSERT INTO `test_translation` VALUES ('xqljf8q070jo9pdgq0adcynifccqnvk0', 'US', 'major problem');
INSERT INTO `test_translation` VALUES ('xqljf8q070jo9pdgq0adcynifccqnvk0', 'DE', 'schweres Problem');
INSERT INTO `test_translation` VALUES ('zqb7bjl82k1ha0ekscr3kpivsmj83v4i', 'US', 'minor problem changed');
INSERT INTO `test_translation` VALUES ('zqb7bjl82k1ha0ekscr3kpivsmj83v4i', 'DE', 'leichtes Problem');
INSERT INTO `test_translation` VALUES ('12', 'US', 'cosmetic problem');
INSERT INTO `test_translation` VALUES ('12', 'DE', 'kosmetisches Problem');
INSERT INTO `test_translation` VALUES ('5jr6y95jbakr4rke70djkotjmtfru0bc', 'US', 'no problem at all');
INSERT INTO `test_translation` VALUES ('5jr6y95jbakr4rke70djkotjmtfru0bc', 'DE', 'gar kein Problem');
INSERT INTO `test_translation` VALUES ('s56yfez6aueu7kuqjqwhu7ml38iempdz', 'US', 'Website Evaluation');
INSERT INTO `test_translation` VALUES ('s56yfez6aueu7kuqjqwhu7ml38iempdz', 'DE', 'Evaluierung von Webseiten');
INSERT INTO `test_translation` VALUES ('ytsnm1hcfib0wg17rkqmv57a7ap4hs17', 'US', '');
INSERT INTO `test_translation` VALUES ('ytsnm1hcfib0wg17rkqmv57a7ap4hs17', 'DE', '');
INSERT INTO `test_translation` VALUES ('zize3c5tjviygghwf8y22qz9cvtvre1f', 'US', 'Time of Evaluation');
INSERT INTO `test_translation` VALUES ('zize3c5tjviygghwf8y22qz9cvtvre1f', 'DE', 'Zeitraum der Evaluierung');
INSERT INTO `test_translation` VALUES ('wavtuk69we503if1xceglkn22riaiwh5', 'US', 'Date of Evaluation');
INSERT INTO `test_translation` VALUES ('wavtuk69we503if1xceglkn22riaiwh5', 'DE', 'Datum der Evaluierung');
INSERT INTO `test_translation` VALUES ('meihqd403bqjpvr0kbzl52kww267kw3s', 'US', 'Monitor Size');
INSERT INTO `test_translation` VALUES ('meihqd403bqjpvr0kbzl52kww267kw3s', 'DE', 'Monitor Gr��e');
INSERT INTO `test_translation` VALUES ('6kb0gb5nu85ju22353evj2hwgqowrdaz', 'US', 'Monitor Resolution');
INSERT INTO `test_translation` VALUES ('6kb0gb5nu85ju22353evj2hwgqowrdaz', 'DE', 'Monitor Aufl�sung');
INSERT INTO `test_translation` VALUES ('i3797jdxvsjumwb5uexivp4makaadm8i', 'US', 'Monitor Colours');
INSERT INTO `test_translation` VALUES ('i3797jdxvsjumwb5uexivp4makaadm8i', 'DE', 'Monitor Farben');
INSERT INTO `test_translation` VALUES ('e38mi4soc436ze33adw0y1350uyhl6ex', 'US', 'Connection');
INSERT INTO `test_translation` VALUES ('e38mi4soc436ze33adw0y1350uyhl6ex', 'DE', 'Verbindung');
INSERT INTO `test_translation` VALUES ('g3p8wwlg5kx9kd4f9lrk0tl4b9iu51vx', 'US', 'Operating System');
INSERT INTO `test_translation` VALUES ('g3p8wwlg5kx9kd4f9lrk0tl4b9iu51vx', 'DE', 'Betriebssystem');
INSERT INTO `test_translation` VALUES ('b572c4c6cdvkgzd0upjksdidfazwxstg', 'US', 'Web Browser');
INSERT INTO `test_translation` VALUES ('b572c4c6cdvkgzd0upjksdidfazwxstg', 'DE', 'Webbrowser');
INSERT INTO `test_translation` VALUES ('yd1zs93vsab50cbg0jowno1zyajqnc2a', 'US', 'Sex');
INSERT INTO `test_translation` VALUES ('yd1zs93vsab50cbg0jowno1zyajqnc2a', 'DE', 'Geschlecht');
INSERT INTO `test_translation` VALUES ('at745blwm8485dzc8v7wcj2j0fzc335l', 'US', 'Age');
INSERT INTO `test_translation` VALUES ('at745blwm8485dzc8v7wcj2j0fzc335l', 'DE', 'Alter');

-- --------------------------------------------------------

-- 
-- Table structure for table `test_user_attributes`
-- 

CREATE TABLE `test_user_attributes` (
  `auth_user_id` varchar(32) NOT NULL default '',
  `first_name` varchar(100) NOT NULL default '',
  `last_name` varchar(100) NOT NULL default '',
  `email` varchar(100) NOT NULL default '',
  `street` varchar(100) NOT NULL default '',
  `no` varchar(50) NOT NULL default '',
  `city` varchar(100) NOT NULL default '',
  `zip` varchar(20) NOT NULL default '',
  `country` varchar(100) NOT NULL default '',
  `phone` varchar(50) NOT NULL default '',
  `comment` text NOT NULL,
  PRIMARY KEY  (`auth_user_id`)
) ENGINE=MyISAM;

-- 
-- Dumping data for table `test_user_attributes`
-- 

INSERT INTO `test_user_attributes` VALUES ('29214857b12575501c5c731353c7217e', 'John', 'Doe', 'hem@iicm.edu', 'Infeldgasse', '16c', 'Graz', '8010', 'Austria', '', 'Admin Stuff   \r\n\r\nHey its me...');

-- --------------------------------------------------------

-- 
-- Table structure for table `test_user_pref`
-- 

CREATE TABLE `test_user_pref` (
  `auth_user_id` varchar(32) NOT NULL default '',
  `pref_id` varchar(32) NOT NULL default '',
  `value` varchar(32) NOT NULL default ''
) ENGINE=MyISAM;

-- 
-- Dumping data for table `test_user_pref`
-- 

INSERT INTO `test_user_pref` VALUES ('29214857b12575501c5c731353c7217e', '2', 'DE');
