# phpMyAdmin MySQL-Dump
# version 2.4.0
# http://www.phpmyadmin.net/ (download page)
#
# Host: localhost
# Generation Time: Mar 16, 2003 at 11:12 PM
# Server version: 3.23.52
# PHP Version: 4.2.0
# Database : `test`
# --------------------------------------------------------

#
# Dumping data for table `liveuser_applications`
#

INSERT INTO liveuser_applications VALUES (1, 'BACKOFFICE');
# --------------------------------------------------------

#
# Dumping data for table `liveuser_areas`
#

INSERT INTO liveuser_areas VALUES (1, 1, 'NEWS');
# --------------------------------------------------------

#
# Dumping data for table `liveuser_grouprights`
#

INSERT INTO liveuser_grouprights VALUES (1, 3, 3);
INSERT INTO liveuser_grouprights VALUES (2, 3, 2);
INSERT INTO liveuser_grouprights VALUES (3, 2, 1);
# --------------------------------------------------------

#
# Dumping data for table `liveuser_groups`
#

INSERT INTO liveuser_groups (group_id, group_type, is_active, group_define_name) VALUES (1, 0, 'Y', 'GROUP1');
INSERT INTO liveuser_groups (group_id, group_type, is_active, group_define_name) VALUES (2, 0, 'Y', 'GROUP2');
INSERT INTO liveuser_groups (group_id, group_type, is_active, group_define_name) VALUES (3, 0, 'Y', 'GROUP3');
# --------------------------------------------------------

#
# Dumping data for table `liveuser_groupusers`
#

INSERT INTO liveuser_groupusers VALUES (1, 1);
INSERT INTO liveuser_groupusers VALUES (2, 2);
INSERT INTO liveuser_groupusers VALUES (2, 3);
INSERT INTO liveuser_groupusers VALUES (3, 2);
INSERT INTO liveuser_groupusers VALUES (4, 3);
# --------------------------------------------------------

#
# Dumping data for table `liveuser_languages`
#

INSERT INTO liveuser_languages VALUES (1, 'en');
# --------------------------------------------------------

#
# Dumping data for table `liveuser_translations`
#

INSERT INTO liveuser_translations VALUES (1, 1, 1, 'BACKOFFICE', 'BackOffice for testing');
INSERT INTO liveuser_translations VALUES (1, 2, 1, 'NEWS', 'News');
INSERT INTO liveuser_translations VALUES (1, 3, 1, 'ADMINS', 'The admin group can change everything.');
INSERT INTO liveuser_translations VALUES (2, 3, 1, 'GroupA', 'Standard user group.');
INSERT INTO liveuser_translations VALUES (3, 3, 1, 'GroupB', 'Another group.');
INSERT INTO liveuser_translations VALUES (1, 4, 1, 'English', 'English language');
INSERT INTO liveuser_translations VALUES (1, 5, 1, 'NEW', 'Write news');
INSERT INTO liveuser_translations VALUES (2, 5, 1, 'CHANGE', 'Change news');
INSERT INTO liveuser_translations VALUES (3, 5, 1, 'DELETE', 'Delete news');
# --------------------------------------------------------

#
# Dumping data for table `liveuser_perm_users`
#

INSERT INTO liveuser_perm_users VALUES (1, 'c14cbf141ab1b7cd009356f555b607dc', 1, '');
INSERT INTO liveuser_perm_users VALUES (2, '185cd5095e899ab43a225e42d7232807', 1, '');
INSERT INTO liveuser_perm_users VALUES (3, '11551a03b7de857163fd2e519c16a960', 1, '');
INSERT INTO liveuser_perm_users VALUES (4, '7ddf260b66b9a5c182a91a413f1aa461', 1, '');
# --------------------------------------------------------

#
# Dumping data for table `liveuser_right_implied`
#

INSERT INTO liveuser_right_implied VALUES (2, 1);
INSERT INTO liveuser_right_implied VALUES (3, 2);
# --------------------------------------------------------

#
# Dumping data for table `liveuser_rights`
#

INSERT INTO liveuser_rights VALUES (1, 1, 'NEW', 'N', 'N', 'N');
INSERT INTO liveuser_rights VALUES (2, 1, 'CHANGE', 'Y', 'Y', 'N');
INSERT INTO liveuser_rights VALUES (3, 1, 'DELETE', 'Y', 'Y', 'N');
# --------------------------------------------------------

#
# Dumping data for table `liveuser_userrights`
#

INSERT INTO liveuser_userrights VALUES (2, 2, 3);
# --------------------------------------------------------

#
# Dumping data for table `liveuser_users`
#

INSERT INTO liveuser_users VALUES ('c14cbf141ab1b7cd009356f555b607dc', 'admin', '098f6bcd4621d373cade4e832627b4f6', '1999-11-30 00:00:00', NULL, NULL, 'Y');
INSERT INTO liveuser_users VALUES ('185cd5095e899ab43a225e42d7232807', 'userA', '098f6bcd4621d373cade4e832627b4f6', '2003-03-16 22:34:44', NULL, NULL, 'Y');
INSERT INTO liveuser_users VALUES ('11551a03b7de857163fd2e519c16a960', 'userB', '098f6bcd4621d373cade4e832627b4f6', '2003-03-16 22:16:44', NULL, NULL, 'Y');
INSERT INTO liveuser_users VALUES ('7ddf260b66b9a5c182a91a413f1aa461', 'userC', '098f6bcd4621d373cade4e832627b4f6', '2003-03-16 22:43:29', NULL, NULL, 'Y');
# --------------------------------------------------------

#
# Table structure for table `news`
#

CREATE TABLE news (
  news_id int(11) NOT NULL auto_increment,
  created_at datetime NOT NULL default '0000-00-00 00:00:00',
  valid_to datetime NOT NULL default '0000-00-00 00:00:00',
  news text NOT NULL,
  owner_user_id bigint(20) NOT NULL default '0',
  owner_group_id int(11) NOT NULL default '0',
  PRIMARY KEY  (news_id),
  KEY news_id (news_id),
  KEY valid_to (valid_to)
) TYPE=MyISAM PACK_KEYS=1;

#
# Dumping data for table `news`
#

INSERT INTO news VALUES (1, '2003-03-16 22:17:21', '2003-03-30 23:17:21', 'Just testing my rights.', 3, 2);
INSERT INTO news VALUES (2, '2003-03-16 21:53:41', '2003-04-13 22:53:41', 'Another test ;-)', 1, 1);
INSERT INTO news VALUES (3, '2003-03-16 22:42:27', '2003-04-06 23:42:27', 'Yeah! I can make some test postings here', 2, 2);
INSERT INTO news VALUES (4, '2003-03-16 23:00:29', '2003-03-23 23:00:29', 'LiveUser is really a cool tool :-)', 4, 3);
