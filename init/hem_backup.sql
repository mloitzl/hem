-- MySQL dump 10.9
--
-- Host: mysql    Database: hem
-- ------------------------------------------------------
-- Server version	4.1.22

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `test_activity`
--

DROP TABLE IF EXISTS `test_activity`;
CREATE TABLE `test_activity` (
  `actId` varchar(32) NOT NULL default '',
  `actType` int(11) NOT NULL default '0',
  `actUser` varchar(100) NOT NULL default '',
  `actDescription` varchar(200) NOT NULL default '',
  `actTime` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`actId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `test_activity`
--

LOCK TABLES `test_activity` WRITE;
/*!40000 ALTER TABLE `test_activity` DISABLE KEYS */;
INSERT INTO `test_activity` VALUES ('hnxy74ngumwi1ch5fcto18qjq8m75xxa',1,'none','has to logged in, or login failed 1 times','2025-03-16 15:03:34'),('h5l57d4d3dqunibdoniwjr27ltjlgdb4',1,'johndoe','Succesfully logged in','2025-03-16 15:03:38'),('a66qhg1zjjyqkg0d963s76b2yj4mfq7h',1,'none','has to logged in, or login failed 1 times','2025-03-16 15:03:36'),('q9gzcbauygeoofkc8qosug16zbwdjb4m',1,'joe','Succesfully logged in','2025-03-16 15:03:40'),('ankei37pj52ppr3eqmd07efx6541f73k',1,'none','has to logged in, or login failed 1 times','2025-03-16 15:03:28'),('gakhw10pf29s8wtivtmrq6omb104mgzm',1,'johndoe','Succesfully logged in','2025-03-16 15:03:37'),('jfhxfwsnkca3memyrh2u0jt2b5v0sbvy',1,'none','has to logged in, or login failed 1 times','2025-03-16 15:03:02'),('uwk2hngdc53uyoj9u96kjfs5r15jcn64',1,'mloitzl','has to logged in, or login failed 2 times','2025-03-16 15:03:05'),('h32eu91hyo1uelubhtapuaz4uae4du0t',1,'mloitzl','Succesfully logged in','2025-03-16 15:03:09'),('a3llk31yxg8vwn4egyw22j0q2eiqsicg',1,'none','has to logged in, or login failed 1 times','2025-03-16 15:03:59'),('058ipsdxah5fq47sgx1h6r3ltj84osuu',1,'harry','Succesfully logged in','2025-03-16 15:03:03'),('5962kumzvaj40p2fxbhcklltxifnnva9',1,'none','has to logged in, or login failed 1 times','2025-03-16 15:03:44'),('o7421z1gordq7goza1srhmf2kpxzcjyu',1,'ali','Succesfully logged in','2025-03-16 15:03:47'),('p1rtusj12zaai832zthp0qniy79ugobm',1,'none','has to logged in, or login failed 1 times','2025-03-16 15:03:27'),('0w1iwpbj96mkghv0cnmx902qlufz85xk',1,'mloitzl','Succesfully logged in','2025-03-16 15:03:31'),('slpr08di5ix4x6k2eou47dn6fc1xjdla',1,'none','has to logged in, or login failed 1 times','2025-03-16 15:03:55'),('z384yywhc8f1hhigx4grwakdeg5iwufm',1,'harry','Succesfully logged in','2025-03-16 15:03:58'),('0skslfo51nvn5mfklif906qvyhm5atz6',1,'none','has to logged in, or login failed 1 times','2025-03-16 15:03:07'),('bcgnr823yp9yt613otcq82x4ec8rhvtm',1,'joe','Succesfully logged in','2025-03-16 15:03:12'),('yju12m07nobs7590d4rvq8xiywapru4d',1,'none','has to logged in, or login failed 1 times','2025-03-16 15:03:15'),('pfgmk7zt7t6n9axiwj2sjy5rbyxwh38f',1,'ali','Succesfully logged in','2025-03-16 15:03:19'),('wd4qd7e5f7uhvg5s5hm6cffa08e3tglw',1,'none','has to logged in, or login failed 1 times','2025-03-16 15:03:43'),('i7541nyhi8mubzd2ewitpok866inoinh',1,'mloitzl','Succesfully logged in','2025-03-16 15:03:47'),('t64pn1s1zj2a77dzysnlhbnyz4qsnfne',1,'none','has to logged in, or login failed 1 times','2025-03-16 16:03:20'),('x6mtvc1d7b2oz4bic0255y86c8sw179z',1,'harry','Succesfully logged in','2025-03-16 16:03:24'),('r0s464jb314b824dbkpye5t8y9jriici',1,'none','has to logged in, or login failed 1 times','2025-03-16 16:03:02'),('0vimf34xxflmpb9h7avsrvna618yiagt',1,'ali','Succesfully logged in','2025-03-16 16:03:06'),('3nut8aq86sjnke544tyxyb465ptbz23w',1,'none','has to logged in, or login failed 1 times','2025-03-16 16:03:24'),('64hd555x14acto5zf30ct77kzb1vcpma',1,'joe','Succesfully logged in','2025-03-16 16:03:28'),('9ooi04zfoqk41yx8w2vr51m20h4g7fer',1,'none','has to logged in, or login failed 1 times','2025-03-16 16:03:59'),('ujc3u1p54fkldvkl0bjn3xkh4ydxv5tw',1,'mloitzl','Succesfully logged in','2025-03-16 16:03:09'),('z1kffeqhx4cch9q1utyupd1jdbwg8qld',1,'none','has to logged in, or login failed 1 times','2025-03-18 06:03:03'),('f5iqridd9joorb3euat8raoexjv7z4rk',1,'johndoe','Succesfully logged in','2025-03-18 06:03:11'),('pi0vozhsgzicbhrh0f8mkc7qb86k8gp1',1,'none','has to logged in, or login failed 1 times','2025-03-18 06:03:05'),('9t52rotco1b7k2k4b3u0sc76ojza66wv',1,'ali','Succesfully logged in','2025-03-18 06:03:08'),('egzfzrar3txg9z8v712ujknimn1klr4h',1,'none','has to logged in, or login failed 1 times','2025-03-18 06:03:11'),('pyp2z8k16432ry78rug9gbsss9lz5u4z',1,'mloitzl','Succesfully logged in','2025-03-18 06:03:17'),('56j6avefk24k4bn2f8vbz98993rgy4yy',1,'none','has to logged in, or login failed 1 times','2025-03-18 06:03:19'),('4evufqbgnv19yyq1zv06uhgcg946smxq',1,'ali','Succesfully logged in','2025-03-18 06:03:24'),('woaygjcw1mb3lctdev8ro2yx72uwlxpk',1,'none','has to logged in, or login failed 1 times','2025-03-18 06:03:28'),('mr3dqrtw9ckmrzm1sn160167e5rsso0y',1,'joe','Succesfully logged in','2025-03-18 06:03:32'),('xbd072evlsrijzaer1lto9dtcftagghk',1,'none','has to logged in, or login failed 1 times','2025-03-18 06:03:45'),('ua548q16xjeahrdp9jpucjp3cra98ym9',1,'mloitzl','Succesfully logged in','2025-03-18 06:03:50'),('snirvxy9xuj9278y7m492ns1i34e8wcs',1,'none','has to logged in, or login failed 1 times','2025-03-18 06:03:54'),('e3w2ic18fpk7319iiz9qkt0g6ktaaixf',1,'ali','Succesfully logged in','2025-03-18 06:03:56'),('4rg32epxkc9a3k5fr2kzcjdm4yr2amps',1,'none','has to logged in, or login failed 1 times','2025-03-18 06:03:59'),('4ajv30lfn4n8v7mxg0c858e0p7seaa7j',1,'harry','Succesfully logged in','2025-03-18 06:03:07'),('pqn98t2i2kciwbjuswzsefe4i5idd4en',1,'none','has to logged in, or login failed 1 times','2025-03-18 06:03:09');
/*!40000 ALTER TABLE `test_activity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_environment`
--

DROP TABLE IF EXISTS `test_environment`;
CREATE TABLE `test_environment` (
  `envId` varchar(32) NOT NULL default '',
  `envTitleId` varchar(32) NOT NULL default '',
  `envDescriptionId` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`envId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `test_environment`
--

LOCK TABLES `test_environment` WRITE;
/*!40000 ALTER TABLE `test_environment` DISABLE KEYS */;
INSERT INTO `test_environment` VALUES ('krhlwkcb40dvhhsaghu1u8azgig8ouct','s56yfez6aueu7kuqjqwhu7ml38iempdz','ytsnm1hcfib0wg17rkqmv57a7ap4hs17');
/*!40000 ALTER TABLE `test_environment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_environment_attributes`
--

DROP TABLE IF EXISTS `test_environment_attributes`;
CREATE TABLE `test_environment_attributes` (
  `envAttributeId` varchar(32) NOT NULL default '',
  `envId` varchar(32) NOT NULL default '',
  `envOrder` int(10) NOT NULL default '0',
  `envAttributeNameId` varchar(32) NOT NULL default '',
  `envAttributeType` varchar(100) NOT NULL default 'text',
  `envAttributeValues` varchar(100) default NULL,
  PRIMARY KEY  (`envAttributeId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `test_environment_attributes`
--

LOCK TABLES `test_environment_attributes` WRITE;
/*!40000 ALTER TABLE `test_environment_attributes` DISABLE KEYS */;
INSERT INTO `test_environment_attributes` VALUES ('ywrhh2zwst1uwlvjk5zwedc1w1r9uxf8','krhlwkcb40dvhhsaghu1u8azgig8ouct',1,'at745blwm8485dzc8v7wcj2j0fzc335l','text','NULL'),('4fsuo4tokf1u4m2lo1e5267owxikm4c9','krhlwkcb40dvhhsaghu1u8azgig8ouct',2,'yd1zs93vsab50cbg0jowno1zyajqnc2a','text','NULL'),('wq4phhuot1j0w87i9n5s88ta05jfxu1j','krhlwkcb40dvhhsaghu1u8azgig8ouct',3,'b572c4c6cdvkgzd0upjksdidfazwxstg','text','NULL'),('wi4m7da77x7nvt6qag594tkjbc1lwnoy','krhlwkcb40dvhhsaghu1u8azgig8ouct',4,'g3p8wwlg5kx9kd4f9lrk0tl4b9iu51vx','text','NULL'),('t1dai7igddolepkbd2bhczr2ypsgii6m','krhlwkcb40dvhhsaghu1u8azgig8ouct',5,'e38mi4soc436ze33adw0y1350uyhl6ex','text','NULL'),('a8hak40kt9rsvn9jjy60tbwoij8bof5y','krhlwkcb40dvhhsaghu1u8azgig8ouct',6,'i3797jdxvsjumwb5uexivp4makaadm8i','text','NULL'),('r8dtrwdce8ul5d8bx29j05gh114a5nje','krhlwkcb40dvhhsaghu1u8azgig8ouct',7,'6kb0gb5nu85ju22353evj2hwgqowrdaz','text','NULL'),('a5yut9txp2bczmtesnl8njhcm7tgx80y','krhlwkcb40dvhhsaghu1u8azgig8ouct',8,'meihqd403bqjpvr0kbzl52kww267kw3s','text','NULL'),('m9opq6umur457iwqxdqeh7tbgwwpb3q5','krhlwkcb40dvhhsaghu1u8azgig8ouct',9,'wavtuk69we503if1xceglkn22riaiwh5','text','NULL'),('v9qm74nf4yzjzzi07hyj30o6859ew9dm','krhlwkcb40dvhhsaghu1u8azgig8ouct',10,'zize3c5tjviygghwf8y22qz9cvtvre1f','text','NULL');
/*!40000 ALTER TABLE `test_environment_attributes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_environment_data`
--

DROP TABLE IF EXISTS `test_environment_data`;
CREATE TABLE `test_environment_data` (
  `envDataId` varchar(32) NOT NULL default '',
  `pId` varchar(32) NOT NULL default '',
  `envAttributeId` varchar(32) NOT NULL default '',
  `envAttributeData` tinytext NOT NULL,
  `envDataOwnerId` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`envDataId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `test_environment_data`
--

LOCK TABLES `test_environment_data` WRITE;
/*!40000 ALTER TABLE `test_environment_data` DISABLE KEYS */;
INSERT INTO `test_environment_data` VALUES ('x22em9qz9yoe5ckh3spln00zrw33ywcf','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','wq4phhuot1j0w87i9n5s88ta05jfxu1j','Firefox v1.0.4','2swcmpex84arrcckfjd0iw9yf2ub9fen'),('jnhxj6ns1ypnr0k4vlw3lxh5ptvtib67','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','wi4m7da77x7nvt6qag594tkjbc1lwnoy','Windows XP V2002 SP2','2swcmpex84arrcckfjd0iw9yf2ub9fen'),('dae7m5ueakibjelf79cm2xgfj8rtzmt1','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','t1dai7igddolepkbd2bhczr2ypsgii6m','hoch','2swcmpex84arrcckfjd0iw9yf2ub9fen'),('hq148i2g9yiowso1bq85a789jv0ktk7u','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','a8hak40kt9rsvn9jjy60tbwoij8bof5y','32Bit','2swcmpex84arrcckfjd0iw9yf2ub9fen'),('kxvz63wtuxa7iq853acafl7siie6sz9t','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','r8dtrwdce8ul5d8bx29j05gh114a5nje','1280x1024','2swcmpex84arrcckfjd0iw9yf2ub9fen'),('et7gyej61tvjpbgy9uu51c1klp4m7vw1','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','a5yut9txp2bczmtesnl8njhcm7tgx80y','17\"','2swcmpex84arrcckfjd0iw9yf2ub9fen'),('xb1288kp79qsqcbdhxbrjun2l0qb9wt6','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','m9opq6umur457iwqxdqeh7tbgwwpb3q5','29.09.2005','2swcmpex84arrcckfjd0iw9yf2ub9fen'),('wj0vq82j03kksnty346s90yl6m9ty4bf','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','v9qm74nf4yzjzzi07hyj30o6859ew9dm','16:00-18:00','2swcmpex84arrcckfjd0iw9yf2ub9fen'),('5p4arim4gan0l4l6xs71i9su9zgy8rn2','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','wq4phhuot1j0w87i9n5s88ta05jfxu1j','Firefox 1.0.7','ayfht61xipijjnbmpt6swb63su25h0i2'),('w36pnfisitwdzoh3befuas1mokmc6r25','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','4fsuo4tokf1u4m2lo1e5267owxikm4c9','mÃ¤nnlich','ayfht61xipijjnbmpt6swb63su25h0i2'),('nxa67raufwu1qi9qhuoymswlavtmv1dn','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','v9qm74nf4yzjzzi07hyj30o6859ew9dm','10:30 - 12:30','d4lep4hd1ssrdenoki58rxky96cx8jxn'),('polefwsu6ezfili9sq14lsfkjjh4com3','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','m9opq6umur457iwqxdqeh7tbgwwpb3q5','29.09.2005','d4lep4hd1ssrdenoki58rxky96cx8jxn'),('2g88t7yrtue05s87qgja8d4cr3pw3vg7','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','a5yut9txp2bczmtesnl8njhcm7tgx80y','12\"','d4lep4hd1ssrdenoki58rxky96cx8jxn'),('afza7m6ao768z0ae0eh0xaw4x5as8uoe','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','r8dtrwdce8ul5d8bx29j05gh114a5nje','1024x786','d4lep4hd1ssrdenoki58rxky96cx8jxn'),('jjjtqzfeu07sncuqwtkmvd9r9edfm90n','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','a8hak40kt9rsvn9jjy60tbwoij8bof5y','24bit','d4lep4hd1ssrdenoki58rxky96cx8jxn'),('ebc8nb9s7iwfy2omud7xhe2rssq7kinr','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','t1dai7igddolepkbd2bhczr2ypsgii6m','ADSL 768kbit','d4lep4hd1ssrdenoki58rxky96cx8jxn'),('ysm0yrl51bfhek8c5dlhjdn0lcskegbn','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','wi4m7da77x7nvt6qag594tkjbc1lwnoy','Apple Mac OS X 10.4.2','d4lep4hd1ssrdenoki58rxky96cx8jxn'),('7tvcdd4smiro6wnywg0scmsihxylps3v','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','wq4phhuot1j0w87i9n5s88ta05jfxu1j','Safari 2.0.1','d4lep4hd1ssrdenoki58rxky96cx8jxn'),('v17spebe6yd3xtux9j6b1abmmj6jb8i1','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','v9qm74nf4yzjzzi07hyj30o6859ew9dm','13:00-17:00','ayfht61xipijjnbmpt6swb63su25h0i2'),('oowyjoj001bkuxa0czmwh3cjz7xh82y7','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','m9opq6umur457iwqxdqeh7tbgwwpb3q5','29.09.2005','ayfht61xipijjnbmpt6swb63su25h0i2'),('solq7cy8y2omtb72ll8dmww7fbyq3oyl','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','a5yut9txp2bczmtesnl8njhcm7tgx80y','15\"','ayfht61xipijjnbmpt6swb63su25h0i2'),('yuymdddid9jlhquoezltjs3f8qi5y5xd','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','r8dtrwdce8ul5d8bx29j05gh114a5nje','1400 x 1050','ayfht61xipijjnbmpt6swb63su25h0i2'),('4ft1sumc8yi24ikska43jhuq2436e0s5','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','a8hak40kt9rsvn9jjy60tbwoij8bof5y','32','ayfht61xipijjnbmpt6swb63su25h0i2'),('16tnwr0akv8zoe9i104fyw7j2znm71kj','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','t1dai7igddolepkbd2bhczr2ypsgii6m','LAN','ayfht61xipijjnbmpt6swb63su25h0i2'),('15eilc0pfv1ja0inx2wkqui92w7gi8iw','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','wi4m7da77x7nvt6qag594tkjbc1lwnoy','Windows XP','ayfht61xipijjnbmpt6swb63su25h0i2'),('l8rnxhbqf3udfk45z7s7ithe04nn0g5s','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','ywrhh2zwst1uwlvjk5zwedc1w1r9uxf8','31','iiny2otwmid92y0njh8d7ohb7rsy0ll9'),('z2c67ap3fbk73xa8ta3jcer5nam6lgeb','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','4fsuo4tokf1u4m2lo1e5267owxikm4c9','mÃ¤nnlich','d4lep4hd1ssrdenoki58rxky96cx8jxn'),('h5ciapod40kb1vkj4z4iyfqy3xx1zzza','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','v9qm74nf4yzjzzi07hyj30o6859ew9dm','19.30','iiny2otwmid92y0njh8d7ohb7rsy0ll9'),('xcdrrn5tzo1jqzidct5zd3d0htgeccrx','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','m9opq6umur457iwqxdqeh7tbgwwpb3q5','26.9.2005','iiny2otwmid92y0njh8d7ohb7rsy0ll9'),('bbr7tol1eu5wxjk5rvwwiutgx0ceyrit','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','a5yut9txp2bczmtesnl8njhcm7tgx80y','17 Zoll','iiny2otwmid92y0njh8d7ohb7rsy0ll9'),('qgg0k4ev1dvvuuyi46f1eou1edtzkt6z','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','r8dtrwdce8ul5d8bx29j05gh114a5nje','1024x768','iiny2otwmid92y0njh8d7ohb7rsy0ll9'),('o99o5qituwjs8vh4hu6k97qb63m74lpw','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','a8hak40kt9rsvn9jjy60tbwoij8bof5y','32 Bit','iiny2otwmid92y0njh8d7ohb7rsy0ll9'),('u20345gr0s9dkebdsbq0ull1qqmkv4mj','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','t1dai7igddolepkbd2bhczr2ypsgii6m','LAN','iiny2otwmid92y0njh8d7ohb7rsy0ll9'),('9qwb6ya8wi3iuylp9vrl189af0e9lsqc','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','wi4m7da77x7nvt6qag594tkjbc1lwnoy','Windows XP','iiny2otwmid92y0njh8d7ohb7rsy0ll9'),('d1zspjyfvz3r5u6zofrrofcts5pyiml6','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','wq4phhuot1j0w87i9n5s88ta05jfxu1j','Opera 8.5 Build 7700','iiny2otwmid92y0njh8d7ohb7rsy0ll9'),('uym0q6ahqchwtu24eiv1a6gor2u7l2n1','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','4fsuo4tokf1u4m2lo1e5267owxikm4c9','male','iiny2otwmid92y0njh8d7ohb7rsy0ll9'),('sqgrocuuz7cs12gz3zzd27pep1dfed93','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','ywrhh2zwst1uwlvjk5zwedc1w1r9uxf8','28','d4lep4hd1ssrdenoki58rxky96cx8jxn'),('rpzqesuaiqbo4y7sgpap042qjiv112hb','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','ywrhh2zwst1uwlvjk5zwedc1w1r9uxf8','31','ayfht61xipijjnbmpt6swb63su25h0i2'),('49i6uuzee2cfzcdjwbvado5qx88e202j','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','4fsuo4tokf1u4m2lo1e5267owxikm4c9','mÃ¤nnlich','2swcmpex84arrcckfjd0iw9yf2ub9fen'),('wvospkswsrawqvyuv0c1ftl78fy7zbat','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','ywrhh2zwst1uwlvjk5zwedc1w1r9uxf8','33','2swcmpex84arrcckfjd0iw9yf2ub9fen');
/*!40000 ALTER TABLE `test_environment_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_finding`
--

DROP TABLE IF EXISTS `test_finding`;
CREATE TABLE `test_finding` (
  `fId` varchar(32) NOT NULL default '',
  `fText` text NOT NULL,
  `pId` varchar(32) NOT NULL default '',
  `uId` varchar(32) NOT NULL default '',
  `heurId` varchar(32) NOT NULL default '',
  `fPositive` char(1) NOT NULL default '',
  `fManagerFinding` char(1) NOT NULL default '',
  `fTimestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `fLastEditedTimestamp` timestamp NOT NULL default '0000-00-00 00:00:00',
  `fOrder` int(11) NOT NULL default '0',
  PRIMARY KEY  (`fId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `test_finding`
--

LOCK TABLES `test_finding` WRITE;
/*!40000 ALTER TABLE `test_finding` DISABLE KEYS */;
INSERT INTO `test_finding` VALUES ('ix25lyesrj0d5gbr6do9f9sjqn87rhfx','Die Seite verwendet teilweise zum Anzeigen von externen Inhalte iFrames. Diese sind schwer zu bookmarken, was die FlexibilitÃ¤t des benutzers einschrÃ¤nkt.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','d4lep4hd1ssrdenoki58rxky96cx8jxn','lqu799emv33yfd9dc64kezy1nf6hv22m','N','Y','2025-03-16 16:05:15','2025-03-16 16:03:15',31),('uuw4wjax85l0ogqaudm5cff3ojynnnyp','Der Seitentitel gibt keine RÃ¼ckmeldung Ã¼ber die altuelle Position auf der Webseite. Die URL und die Navigation jedoch schon. Das macht die Seiten kompliziert zu bookmarken, was die Benutzerfreiheit einschrÃ¤nkt.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','d4lep4hd1ssrdenoki58rxky96cx8jxn','hwk33tkcyt12897l8iuljcurdud3dpbl','N','Y','2025-03-16 16:05:05','2025-03-16 16:03:05',30),('20n171xkeacafqec8a2hmrofmug5ubh9','Die Seite ist kein valides HTML 4.01. Einige Browser kÃ¶nnten bei der Darstellung der Seite Probleme mit der Darstellung haben.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','d4lep4hd1ssrdenoki58rxky96cx8jxn','kh6xkvtrcluamb2llgl72pbs0a5ffna6','N','Y','2025-03-16 16:04:51','2025-03-16 16:03:51',29),('9eon2idt84ytdlappmdt6naoixo7zjug','Es werden externe Inhalte eingebunden, wie im Screenshot vom BÃ¼ro des Rektors, in dem anderes Link Design verwendet wird.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','d4lep4hd1ssrdenoki58rxky96cx8jxn','kh6xkvtrcluamb2llgl72pbs0a5ffna6','N','Y','2025-03-16 16:04:45','2025-03-16 16:03:45',28),('7zsqv2caerqp1ex0sy40jn6dnmmi7zsf','WeiterfÃ¼hrende Information auf anderen Webseiten wird teilweise in neuen Fenstern, teilweise auch im selben Fenster, oder in iFrames dargestellt.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','d4lep4hd1ssrdenoki58rxky96cx8jxn','kh6xkvtrcluamb2llgl72pbs0a5ffna6','N','Y','2025-03-16 16:04:38','2025-03-16 16:03:38',27),('zrmhem77t2rokt3fax8muoz5w3pve37r','Suche: beim Suchfeld Nachname bei Tel.-Nr/Mailadressen kann kein Stern eingegeben werden (wie explizit angegeben), es kommt fÃ¼r \'hub\' die Meldung, dass fÃ¼r \'hub*\' keine EintrÃ¤ge gefunden wurden. Bei Firefox funktionierts allerdings, wie gerade zufÃ¤llig gemerkt.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','d4lep4hd1ssrdenoki58rxky96cx8jxn','kyc7hytxv6cmta3gw8ogh58fdha4qp3x','N','Y','2025-03-16 16:04:30','2025-03-16 16:03:30',26),('tib78p6qoex1cgs2kx6e6e8n10pr0yrn','Kontaktinformationen kÃ¶nne nicht Ã¼ber ein Mailformular verwertet werden, sonder nur Ã¼ber eine e-Mail adresse. Dies kann zu Problemen fÃ¼hren, falls am gerade verwendeten GerÃ¤t kein konfigurierter e-Mail Client zur VerfÃ¼gung steht.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','d4lep4hd1ssrdenoki58rxky96cx8jxn','hwk33tkcyt12897l8iuljcurdud3dpbl','N','Y','2025-03-16 16:03:50','2025-03-16 16:03:50',25),('r4acglsodt8zvnqe56skr5pdf2tdl9ae','Die Darstellung von Hyperlinks ist inkonsistentg. DarÃ¼berhinaus sind auch die Darstellung von Ãœberschriften und Inhaltsgliderung inkonsistent.\r\n\r\nHyperlinks und reiner Text ist oft nicht zu unterscheiden.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','d4lep4hd1ssrdenoki58rxky96cx8jxn','kh6xkvtrcluamb2llgl72pbs0a5ffna6','N','Y','2025-03-16 16:03:25','2025-03-16 16:03:25',24),('fvdmbucv6rew7p042mnr5jyy28dgb9nw','Die Suche ist aufgeteilt in die jeweiligen Datenhaltungssysteme und nicht einheitlich. Der Benutzer erwartet jedoch in allen DatenbestÃ¤nden der TUGraz als Einheit zu suchen. Eine geteilte Suche entspricht nicht dem mentalen Modell des Benutzers.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','d4lep4hd1ssrdenoki58rxky96cx8jxn','kh6xkvtrcluamb2llgl72pbs0a5ffna6','N','Y','2025-03-16 16:03:13','2025-03-16 16:03:13',23),('2miqxhu876l8u1jp3gqblzflv0g8i5rz','Aktuelles: zum Punkt \'Aktuelle Themen\':- die Ãœberschriften sehen aus wie Links, sind allerdings keine Links.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','d4lep4hd1ssrdenoki58rxky96cx8jxn','kh6xkvtrcluamb2llgl72pbs0a5ffna6','N','Y','2025-03-16 16:03:05','2025-03-16 16:03:05',22),('xvhu40gfzx8e9feh7ec8kmsl9zmxisro','Dirket von Fremdanbietern eingebundene Information ist teilweise falsch. Ein Beipsiel ist, das das aktuelle Wetter in Graz angezeigt wird, die Werte jeoch falsch oder nicht aktuell sind.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','d4lep4hd1ssrdenoki58rxky96cx8jxn','4h2220h2skh9a3vhgcb13flqnhuqhs98','N','Y','2005-09-29 19:53:40','2005-09-29 19:09:40',21),('ofazdwamcrbz97qaxwyqn56y5p143ap7','Zwar gut aufgebaut Sitemap, aber es gibt WidersprÃ¼che zum logischen Aufbau der Seite\r\n\r\n','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','d4lep4hd1ssrdenoki58rxky96cx8jxn','kh6xkvtrcluamb2llgl72pbs0a5ffna6','N','Y','2025-03-16 16:02:50','2025-03-16 16:03:50',20),('03bydn8c0kyv2gj458oa2yzah9tsd80z','Link fÃ¼hrt nur zu Quellcode.Keine Aussage ob falsch verlinkt oder ob Plugin fehlt','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','d4lep4hd1ssrdenoki58rxky96cx8jxn','kyc7hytxv6cmta3gw8ogh58fdha4qp3x','N','Y','2025-03-16 16:02:42','2025-03-16 16:03:42',19),('dto51f1gefyzekennavhinvww9cu112t','Das Layout der Webseite Ã¤ndert sich im verlauf der unterschiedlichen Inhalte stÃ¤ndig.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','d4lep4hd1ssrdenoki58rxky96cx8jxn','kh6xkvtrcluamb2llgl72pbs0a5ffna6','N','Y','2025-03-16 16:02:34','2025-03-16 16:03:34',18),('6vatxwtjr9d5f6i0e9wy4heoqbtc6juo','Kein permanentes Aussehen der Startseite. Durch Einbinden von News sieht die Startseite jede Woche anders aus. Grund dafÃ¼r ist auch, dass die Grafik alles Ã¼berragt.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','d4lep4hd1ssrdenoki58rxky96cx8jxn','p3pscjz696y6ajd3zdug67p3lpcmw0qd','N','Y','2025-03-16 16:02:24','2025-03-16 16:03:24',17),('hmf6pl12wv8lbhi2pjames6o5gthzirk','Diverse Seiten (z.b. Abschnitt Studium) bieten zu viel undstrukturierte Information. Diese Seiten stellen reine Linklisten dar und bieten keine relevante Information.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','d4lep4hd1ssrdenoki58rxky96cx8jxn','4h2220h2skh9a3vhgcb13flqnhuqhs98','N','Y','2005-09-29 19:13:41','2005-09-29 19:09:41',16),('xqgalxd0imyqlrdqjgpcwllxf01tcktw','Das ZurÃ¼cknavigieren funcktioniert nur Ã¼ber die Browser Funktion \'zurÃ¼ck\' zuverlÃ¤ssig.\r\n\r\nDie Funktion zum ZurÃ¼cknavigieren die von der Webseite selbst angeboten wird liefert oft nicht das gewÃ¼nschte Ergebnis.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','d4lep4hd1ssrdenoki58rxky96cx8jxn','hwk33tkcyt12897l8iuljcurdud3dpbl','N','Y','2025-03-16 16:02:13','2025-03-16 16:03:13',15),('2i9xvejw1k9qa6hx6na21l038x8z95ox','Das SeitenmenÃ¼ ist nicht einheitlich auf der ganzen Seite und liefert auch keine RÃ¼ckmeldung Ã¼ber die aktuelle Position des Benutzers auf der Webseite.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','d4lep4hd1ssrdenoki58rxky96cx8jxn','c9y1ffwjxjmhgt6ieqe5qt5rlxh1iap6','N','Y','2025-03-16 16:01:43','2025-03-16 16:03:43',14),('x2116j9rm76lw43mnty5gx7mixzikfn0','Wenn eine Suche im ersten Suchmodul keine Ergebnisse liefert wird der Benutzer darauf hingewiesen und bekommt einen Link mit dem Hinweis auf eine neue Suche. Dieser Link fÃ¼hrt aber nicht auf die ursprÃ¼ngliche Suche, sondern auf eine andere Webseite.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','d4lep4hd1ssrdenoki58rxky96cx8jxn','uaj0987e3ikcfthgc16bi578pwsihnvd','N','Y','2025-03-16 16:01:25','2025-03-16 16:03:25',13),('qfgf7rzlwk9tsd8na73yrxt0mdh37j2q','Fehlermeldungen liefern keine Information darÃ¼ber, was die Ursache fÃ¼r das Problem sein kÃ¶nnte, bzw. wie der Benutzer das Problem lÃ¶sen kÃ¶nnte.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','d4lep4hd1ssrdenoki58rxky96cx8jxn','uaj0987e3ikcfthgc16bi578pwsihnvd','N','Y','2025-03-16 16:01:13','2025-03-16 16:03:13',12),('1cbmswokquxqxx88kr8eopmu4hfzv6ok','Es gibt keine Unterscheidung zwischen internen und externen Links. Es wird teilwweise auch Information die an und fÃ¼r sich zur selben webseite gehÃ¶rt in neuen Fenstern geÃ¶ffnet.\r\n\r\n','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','d4lep4hd1ssrdenoki58rxky96cx8jxn','c9y1ffwjxjmhgt6ieqe5qt5rlxh1iap6','N','Y','2025-03-16 16:00:43','2025-03-16 16:03:43',11),('ulp71df7hiwpmuycr0u7gkbvrdy19l48','Hilfe nicht vorhanden','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','d4lep4hd1ssrdenoki58rxky96cx8jxn','p6ysbha80utxpxov4fg8sv3l6o4b8vq5','N','Y','2005-09-29 18:58:02','2005-09-29 17:09:00',10),('1fsfomhuz5mc5dcty54mkngtsqkhil99','Die Kontaktseite wird in einem neuen Fenster geÃ¶ffnet, was unÃ¼blich ist. Weiters ist die dort angebotene Information sehr spÃ¤rlich und weicht damit stark von vergleichbaren Webseiten ab.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','d4lep4hd1ssrdenoki58rxky96cx8jxn','kh6xkvtrcluamb2llgl72pbs0a5ffna6','N','Y','2025-03-16 16:00:30','2025-03-16 16:03:30',9),('b9cavrt3hsj0fs3soqr1k97v07ixq32e','Die Webseite benutzt ein funktionales Layout. Die Elemente werden aber nicht konsitstent verwendet. Auf manchen Seite gibt es zum Beispiel einen \'Sidebar\', auf anderen wird er jedoch nicht verwendet.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','d4lep4hd1ssrdenoki58rxky96cx8jxn','kh6xkvtrcluamb2llgl72pbs0a5ffna6','N','Y','2025-03-16 16:07:22','2025-03-16 16:03:22',8),('32yffuqvtqnkq3f6ztlidp7en1nvo1nz','Die Seite ist zwar mehrsprachig implementiert, jedoch werden Inhalte die nur in deutscher Sprache vorhanden sind ohne RÃ¼ckmeldung bei englischer Sprachwahl trotzdem in deutsch angezeigt.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','d4lep4hd1ssrdenoki58rxky96cx8jxn','kh6xkvtrcluamb2llgl72pbs0a5ffna6','N','Y','2025-03-16 16:00:14','2025-03-16 16:03:14',7),('ccqhqsanh4kxiqgc8t7yjzkbnb6wadj6','Sehr mÃ¤chtiges Suchportal, das auch TUG-Online einbezieht (leider aber nur in Deutsch)','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','d4lep4hd1ssrdenoki58rxky96cx8jxn','lqu799emv33yfd9dc64kezy1nf6hv22m','Y','Y','2025-03-16 16:00:06','2025-03-16 16:03:06',6),('gv59nkpkb9i64aigf7fpqbv4dvsuh9kw','Die URL sind permanent verfÃ¼gbar und der Name gibt RÃ¼ckmeldung Ã¼ber den Inhalt.\r\nAdresse der Seite in der Adressleiste des Browsres ist \'hackable\' und spiegelt die Struktur der Seite wieder','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','d4lep4hd1ssrdenoki58rxky96cx8jxn','hwk33tkcyt12897l8iuljcurdud3dpbl','Y','Y','2025-03-16 16:07:51','2025-03-16 16:03:51',5),('elqqcyetppfje2u1dxqzuwfzo54o4qhd','Die Webseite funktioniert auch mit deaktiviertem Stylesheet, was sehr gut fÃ¼r Menschen mit kÃ¶rperlichen einschrÃ¤nkungen ist, die alternative Ein/Ausgabe GerÃ¤te verwenden.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','d4lep4hd1ssrdenoki58rxky96cx8jxn','hwk33tkcyt12897l8iuljcurdud3dpbl','Y','Y','2025-03-16 15:59:45','2025-03-16 15:03:45',4),('zhpsxq5tv0mvdhkqac20k81rrq649145','Die Website gibt RÃ¼ckmeldung Ã¼ber die aktuelle Position innerhalb der Site.\r\nAktuelle Position innerhalb der Site einfach festzustellen.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','d4lep4hd1ssrdenoki58rxky96cx8jxn','c9y1ffwjxjmhgt6ieqe5qt5rlxh1iap6','Y','Y','2025-03-16 15:59:21','2025-03-16 15:03:21',3),('4dgsy4v2qlgqoi7huwcgwzziqe77r7bc','Ich weiÃŸ sofort ob es sich rentiert auf die Uni zu gehen und ob es die WitterungseinflÃ¼sse zulassen.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','d4lep4hd1ssrdenoki58rxky96cx8jxn','lqu799emv33yfd9dc64kezy1nf6hv22m','Y','Y','2025-03-16 15:59:09','2025-03-16 15:03:09',2),('zvs4td921b3b1e1g12rz5sqk5yoh9r53','Ãœbersichlich und schÃ¶n gestaltete Sitemap\r\nMenÃ¼zeilen, Farbgebung, Ã¼berhaupt Design sehr durchgÃ¤ngig, Ã¼bersichtlich.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','d4lep4hd1ssrdenoki58rxky96cx8jxn','4h2220h2skh9a3vhgcb13flqnhuqhs98','Y','Y','2025-03-16 15:58:32','2025-03-16 15:03:32',1),('ujshdzkwu2a3hglzwi7s4126zxd745ve','Es wurde versucht so viele Informationen wie mÃ¶glich anzubieten, dies hat zur Folge dass viele Seiten nur eine lange unÃ¼bersichtliche Linksammlung sind.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','2swcmpex84arrcckfjd0iw9yf2ub9fen','p3pscjz696y6ajd3zdug67p3lpcmw0qd','N','N','2025-03-16 15:54:03','2025-03-16 15:03:03',13),('6sf9iw71dl0f7tu0tj51kffixkj57wjp','RÃ¼ckgÃ¤ngmachen einer Aktion nur Ã¼ber Browsernavigation (vor - zurÃ¼ck) mÃ¶glich','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','2swcmpex84arrcckfjd0iw9yf2ub9fen','hwk33tkcyt12897l8iuljcurdud3dpbl','N','N','2025-03-16 15:54:22','2025-03-16 15:03:22',12),('ibymzkf9cdy547g46jxwv1k6walcnq1n','Feststellen der aktuellen Position nur Ã¼ber Breadcrumb Navigation mÃ¶glich','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','2swcmpex84arrcckfjd0iw9yf2ub9fen','c9y1ffwjxjmhgt6ieqe5qt5rlxh1iap6','N','N','2025-03-16 15:56:13','2025-03-16 15:03:13',11),('r3y7gib8gsrh3rkxyki8b4ocqsx60y6b','MenÃ¼ sehr unÃ¼bersichlich:\r\nDer ausgewÃ¤hlte HauptmenÃ¼punkt wird nicht markiert\r\nUntermenÃ¼ in der rechten Spalte (unÃ¼blich und gewÃ¶hnungsbedÃ¼rftig)\r\nUntermenÃ¼ unstrukturiert und inkonsistent auf den einzelnen Seiten.\r\n\r\nNach klicken auf den markierten MenÃ¼punkt siehe Bild 2','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','2swcmpex84arrcckfjd0iw9yf2ub9fen','c9y1ffwjxjmhgt6ieqe5qt5rlxh1iap6','N','N','2025-03-16 15:55:59','2025-03-16 15:03:59',10),('f31qolktovn0avp4nczxvau04e1j4y9v','Adresse der Seite in der Adressleiste des Browsres ist \'hackable\' und spiegelt die Struktur der Seite wieder','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','ayfht61xipijjnbmpt6swb63su25h0i2','lqu799emv33yfd9dc64kezy1nf6hv22m','Y','N','2025-03-16 16:09:54','2025-03-16 16:03:54',13),('m3v49x9gpbrhogj315e5hb3bvjg6q6dg','Fehlermeldung ohne Informationen, ohne Design, andere Sprache','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','2swcmpex84arrcckfjd0iw9yf2ub9fen','uaj0987e3ikcfthgc16bi578pwsihnvd','N','N','2005-09-29 14:09:23','0000-00-00 00:00:00',9),('o0mob64spyu1que4sn4l7nlhv2lcplks','Wenn schon Sidebars eingefÃ¼hrt werden dann sollen sie auch auf allen Hauptseiten verwendet werden.\r\nManchmal recht der Text bis an den Rand, dann fehlt wieder mal eine Beschriftung dafÃ¼r, oder die Reihenfolge Ã¤ndert sich....','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','ayfht61xipijjnbmpt6swb63su25h0i2','kh6xkvtrcluamb2llgl72pbs0a5ffna6','N','N','2025-03-16 15:51:21','2025-03-16 15:03:21',12),('58nko3ujv2imb2af8glablc5bnzpb8zd','Interne und Externe Links nicht markiert.\r\nIm gleichen MenÃ¼ ein interner danache ein externer Link','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','2swcmpex84arrcckfjd0iw9yf2ub9fen','p3pscjz696y6ajd3zdug67p3lpcmw0qd','N','N','2025-03-16 15:55:23','2025-03-16 15:03:23',8),('ggpjlujlsr3sgfjegxa66h883zkouryq','Hilfe nicht vorhanden','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','2swcmpex84arrcckfjd0iw9yf2ub9fen','p6ysbha80utxpxov4fg8sv3l6o4b8vq5','N','N','2005-09-29 14:09:31','0000-00-00 00:00:00',7),('cqqrsot2483dqg84d9u08w62ginjojpt','Vermischung von externen Links mit internen und solchen, die ein neues Fenster Ã¶ffnen, obwohl die Information zur gleichen Seite gehÃ¶rt.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','ayfht61xipijjnbmpt6swb63su25h0i2','kh6xkvtrcluamb2llgl72pbs0a5ffna6','N','N','2025-03-16 15:53:34','2025-03-16 15:03:34',11),('q0id83wtr18bfv9v8o66g6kpipa16pqw','Kontakt: FÃ¼r diese Information muÃŸ kein neues Fenster geÃ¶ffnet werden.\r\n\r\nWeiters ist die Information nicht ausreichend.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','2swcmpex84arrcckfjd0iw9yf2ub9fen','kh6xkvtrcluamb2llgl72pbs0a5ffna6','N','N','2025-03-16 15:55:15','2025-03-16 15:03:15',6),('f0fgmidtzf24jh8atifkrgatcob2b3vm','WeiterfÃ¼hrende Links werden als solche nur schwer erkannt -> Verlust von Information.\r\n\r\n\r\nBroken links','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','ayfht61xipijjnbmpt6swb63su25h0i2','kyc7hytxv6cmta3gw8ogh58fdha4qp3x','N','N','2025-03-16 15:53:24','2025-03-16 15:03:24',10),('vy90b9f0xt4q6hguktqwwijgufof0a1z','Sprach nicht konsitent\r\nButton schwer erkennbar','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','2swcmpex84arrcckfjd0iw9yf2ub9fen','kh6xkvtrcluamb2llgl72pbs0a5ffna6','N','N','2005-09-29 14:09:23','0000-00-00 00:00:00',5),('ahmuqrgm0ujxpvrlak545585er6m6pd9','Ãœbersichlich und schÃ¶n gestlatete Sitemap','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','2swcmpex84arrcckfjd0iw9yf2ub9fen','4h2220h2skh9a3vhgcb13flqnhuqhs98','Y','N','2025-03-16 15:55:02','2025-03-16 15:03:02',4),('pae35111y4ymgfx4dmqsy0zpvuwid6bk','UnnÃ¶tige Informationen: Wetterbericht \r\nUneinheitliche und unÃ¼bersichtliche verwendung der linken und rechten Spalte','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','2swcmpex84arrcckfjd0iw9yf2ub9fen','4h2220h2skh9a3vhgcb13flqnhuqhs98','N','N','2025-03-16 15:54:50','2025-03-16 15:03:50',3),('vt54yyqhtunsu1somuiletu9p1b778c7','UnÃ¼bersichtliche und uneinheitliche Aufteilung des Inhaltbereiches.\r\nzB: Aktuelles - Veranstaltungen','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','2swcmpex84arrcckfjd0iw9yf2ub9fen','kh6xkvtrcluamb2llgl72pbs0a5ffna6','N','N','2025-03-16 15:54:40','2025-03-16 15:03:40',2),('t5qmjt2s4jj53b0pvfxllpexl8o7whhb','Beim Umschalten der Sprache zwischen deutsch/englisch und englisch/deutsch, wird das MenÃ¼ und die Titelzeile verÃ¤ndert der Inhalt bleibt immer in deutsch.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','2swcmpex84arrcckfjd0iw9yf2ub9fen','5a9jqiuoyt10eljlbx48pmefoxni9h9i','N','N','2025-03-16 15:54:33','2025-03-16 15:03:33',1),('kdlyamie51pfkajhc7xc29703u8zlxmm','Wenn ich nach Telefonnummern suche, komme ich auf eine Seite bei der das Design komplett Ã¤ndert.\r\nZurÃ¼ck komme ich auch nur mehr Ã¼ber die Browsernavigation.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','ayfht61xipijjnbmpt6swb63su25h0i2','kh6xkvtrcluamb2llgl72pbs0a5ffna6','N','N','2025-03-16 15:53:14','2025-03-16 15:03:14',9),('08mx18vurelv8w3b38viyh5gmmyghmd9','Sehr mÃ¤chtiges Suchportal, das auch TUG-Online einbezieht (leider aber nur in Deutsch)','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','ayfht61xipijjnbmpt6swb63su25h0i2','lqu799emv33yfd9dc64kezy1nf6hv22m','Y','N','2025-03-16 15:52:55','2025-03-16 15:03:55',8),('0vde40wh1g0jaxsl57e0mm5c123rq8k4','Ich weiÃŸ sofort ob es sich rentiert auf die Uni zu gehen und ob es die WitterungseinflÃ¼sse zulassen.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','ayfht61xipijjnbmpt6swb63su25h0i2','lqu799emv33yfd9dc64kezy1nf6hv22m','Y','N','2025-03-16 15:52:48','2025-03-16 15:03:48',7),('qa24re40hdx59u2lbfzqhyyif097kzjl','Zwar gut aufgebaut Sitemap, aber es gibt WidersprÃ¼che zum logischen Aufbau der Seite','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','ayfht61xipijjnbmpt6swb63su25h0i2','kh6xkvtrcluamb2llgl72pbs0a5ffna6','N','N','2025-03-16 15:52:32','2025-03-16 15:03:32',6),('fmj2t4quayq7pdir8esr23bumlcgnn7b','Link fÃ¼hrt nur zu Quellcode.\r\nKeine Aussage ob falsch verlinkt oder ob Plugin fehlt','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','ayfht61xipijjnbmpt6swb63su25h0i2','kyc7hytxv6cmta3gw8ogh58fdha4qp3x','N','N','2025-03-16 15:52:26','2025-03-16 15:03:26',5),('n6aruaai06fiwv4fglmjjzfnlkni84pg','Ãœber die Seite ist es schwierig wieder zurÃ¼ckzukommen.\r\nA: Ãœber diese vermeintliche MÃ¶glichkeit kommt man nur zur Hauptseite der Pressestelle\r\nB: Es wird ein neues Fenster mit der Startseite geÃ¶ffnet (passiert bei den anderen Seiten aber nicht)','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','ayfht61xipijjnbmpt6swb63su25h0i2','hwk33tkcyt12897l8iuljcurdud3dpbl','N','N','2025-03-16 15:52:18','2025-03-16 15:03:18',4),('2s83rgito6t3brq9df6zi2fd2n8ps1ni','Will man auf der Startseite mehr von der aktuellen Information sehen, kommt man auf eine Seite mit stark geÃ¤ndertem Design:\r\nA: Header\r\nB: Seitenleiste','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','ayfht61xipijjnbmpt6swb63su25h0i2','kh6xkvtrcluamb2llgl72pbs0a5ffna6','N','N','2025-03-16 15:51:39','2025-03-16 15:03:39',3),('1gm8rcais4dx8ab6apd6imqabec6c9c0','Kein permanentes Aussehen der Startseite. Durch Einbinden von News sieht die Startseite jede Woche anders aus. Grund dafÃ¼r ist auch, dass die Grafik alles Ã¼berragt.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','ayfht61xipijjnbmpt6swb63su25h0i2','p3pscjz696y6ajd3zdug67p3lpcmw0qd','N','N','2025-03-16 15:51:32','2025-03-16 15:03:32',2),('6h0frb0yj1kay4x4bl71cc6nmmlwa3on','In der englischen Version sollte wenigstens die Startseite komplett in Englisch gehalten sein.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','ayfht61xipijjnbmpt6swb63su25h0i2','5a9jqiuoyt10eljlbx48pmefoxni9h9i','N','N','2005-09-29 11:09:32','0000-00-00 00:00:00',1),('l384fwl474xtu4tbw6tydldf5gcumo8g','Wenn eine Suche im ersten Suchmodul keine Ergebnisse liefert wird man darauf hingewiesen und bekommt einen Link auf eine neue Suche. Dieser Link fÃ¼hrt aber nicht auf die ursprÃ¼ngliche Suche, sondern auf ein anderes Suchmodul.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','d4lep4hd1ssrdenoki58rxky96cx8jxn','uaj0987e3ikcfthgc16bi578pwsihnvd','N','N','2025-03-16 15:50:50','2025-03-16 15:03:50',9),('jd6702froqwezpb6grxnqr38j3rvl2ig','Die Seite wechselt innerhalb der selben Domain www.tugraz.at oft das design. Zum Beispiel die Stadtplan seite.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','d4lep4hd1ssrdenoki58rxky96cx8jxn','kh6xkvtrcluamb2llgl72pbs0a5ffna6','N','N','2005-09-29 09:09:54','0000-00-00 00:00:00',3),('lscjt3g3c90ee62m3euab7h939v3dsbo','Die Suche ist aufgeteilt in die jeweiligen Datenhaltungssysteme und nicht einheitlich. Der Benutzer erwartet jedoch in allen DatenbestÃ¤nden der TUGraz als Einheit zu suchen. Eine geteilte Suche entspricht nicht dem mentalen Modell des Benutzers.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','d4lep4hd1ssrdenoki58rxky96cx8jxn','5a9jqiuoyt10eljlbx48pmefoxni9h9i','N','N','2025-03-16 15:48:35','2025-03-16 15:03:35',4),('vbt4m1ip4etaazsuo6f9wnekkmmise4d','Die Seite verwendet teilweise zum Anzeigen von externen Inhalte iFrames. Diese sind schwer zu bookmarken, was die FlexibilitÃ¤t des benutzers einschrÃ¤nkt.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','d4lep4hd1ssrdenoki58rxky96cx8jxn','lqu799emv33yfd9dc64kezy1nf6hv22m','N','N','2025-03-16 15:49:42','2025-03-16 15:03:42',8),('81azkr02v0b9hq8abrz5w43nxyqpbalx','Die Webseite macht keinen Unterschied zwischen Links die zu anderen Seiten fÃ¼hren und links die auf die selbe seite zeigen.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','d4lep4hd1ssrdenoki58rxky96cx8jxn','c9y1ffwjxjmhgt6ieqe5qt5rlxh1iap6','N','N','2025-03-16 15:48:42','2025-03-16 15:03:42',5),('e2ipuip73zixefk8ilaze4pvazdz9y21','Die URL sind permanent verfÃ¼gbar und der Name gibt RÃ¼ckmeldung Ã¼ber den Inhalt.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','d4lep4hd1ssrdenoki58rxky96cx8jxn','hwk33tkcyt12897l8iuljcurdud3dpbl','Y','N','2025-03-16 15:49:02','2025-03-16 15:03:02',12),('wwsnp5mxq0yosobjwkvp251o9fw64yaa','Der Seitentitel gibt keine RÃ¼ckmeldung Ã¼ber die altuelle Position auf der Webseite. Die URL und die Navigation jedoch schon. Das macht die Seiten kompliziert zu bookmarken, was die Benutzerfreiheit einschrÃ¤nkt.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','d4lep4hd1ssrdenoki58rxky96cx8jxn','hwk33tkcyt12897l8iuljcurdud3dpbl','N','N','2025-03-16 15:49:56','2025-03-16 15:03:56',6),('3mmgf6pc067rtzua9i3gaqk94xmenei6','Die Seite ist kein valides HTML 4.01. Einige Browser kÃ¶nnten bei der Darstellung der Seite Probleme mit der Darstellung haben.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','d4lep4hd1ssrdenoki58rxky96cx8jxn','kh6xkvtrcluamb2llgl72pbs0a5ffna6','N','N','2025-03-16 15:48:28','2025-03-16 15:03:28',2),('v2foel4rwvm057b2lsewqrwm50hwgub5','Die Kontaktseite bietet kein Mailformular an, sonder nur eine e-Mail adresse. Dies kann zu Problemen fÃ¼hren, falls am gerade verwendeten GerÃ¤t kein konfigurierter e-Mail Client zur VerfÃ¼gung steht.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','d4lep4hd1ssrdenoki58rxky96cx8jxn','hwk33tkcyt12897l8iuljcurdud3dpbl','N','N','2025-03-16 15:50:11','2025-03-16 15:03:11',7),('xk2ieu55zs8lwo8eu2z9lbi1wikb3hpk','Die Webseite funktioniert auch mit deaktiviertem Stylesheet, was sehr gut fÃ¼r Menschen mit kÃ¶rperlichen einschrÃ¤nkungen ist, die alternative Ein/Ausgabe GerÃ¤te verwenden.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','d4lep4hd1ssrdenoki58rxky96cx8jxn','hwk33tkcyt12897l8iuljcurdud3dpbl','Y','N','2025-03-16 15:49:31','2025-03-16 15:03:31',11),('cvqodjbjjr9gdk3vuqtfpu4z3wf3ou45','Die Website gibt rÃ¼ckmeldung Ã¼ber die aktuelle Position innerhalb der Site.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','d4lep4hd1ssrdenoki58rxky96cx8jxn','c9y1ffwjxjmhgt6ieqe5qt5rlxh1iap6','Y','N','2025-03-16 15:49:13','2025-03-16 15:03:13',10),('1jqzpg2hytfbjei3nw2pg094e4mj49c0','Es werden externe Inhalte eingebunden, wie im Screenshot vom BÃ¼ro des Rektors, in dem anderes Link Design verwendet wird.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','d4lep4hd1ssrdenoki58rxky96cx8jxn','kh6xkvtrcluamb2llgl72pbs0a5ffna6','N','N','2025-03-16 15:48:20','2025-03-16 15:03:20',1),('1dgp5pfufxlxgrsgfatnywl92vzty2wu','\\Suche \'Tel.-Nr / Mailadressen\'->Ergebnisse: Feld \'Neue Suche / New search\' fÃ¼hrt auf <http://www.cis.tugraz.at/phonesearch.html>, nicht zurÃ¼ck auf die tugraz.at-Suchseite.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','iiny2otwmid92y0njh8d7ohb7rsy0ll9','hwk33tkcyt12897l8iuljcurdud3dpbl','N','N','2025-03-16 15:43:00','2025-03-16 15:03:00',7),('opyntn92yrwzkn7nh6sd17f3bf5g1vi3','\\die TU Graz: Punkt \'Links->Organigramm\' (rechts unten) fÃ¼hrt auf eine JPG-Grafik, warum wird die Grafik nicht in das normale Design eingebungen?','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','iiny2otwmid92y0njh8d7ohb7rsy0ll9','c9y1ffwjxjmhgt6ieqe5qt5rlxh1iap6','N','N','2025-03-16 15:45:42','2025-03-16 15:03:42',15),('1wssia9fkk756wdyz6lmxlunoz22flwn','Mit weiterfÃ¼hrenden Links Ã¼berladene Seiten, zB Rubrik \'Studium\'.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','iiny2otwmid92y0njh8d7ohb7rsy0ll9','c9y1ffwjxjmhgt6ieqe5qt5rlxh1iap6','N','N','2025-03-16 15:46:02','2025-03-16 15:03:02',14),('bp5awivmtv7iao04ml3eu40a7qspzcsy','MenÃ¼zeilen, Farbgebung, Ã¼berhaupt Design sehr durchgÃ¤ngig, Ã¼bersichtlich.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','iiny2otwmid92y0njh8d7ohb7rsy0ll9','4h2220h2skh9a3vhgcb13flqnhuqhs98','Y','N','2025-03-16 15:46:26','2025-03-16 15:03:26',13),('2nioto97qxlf7ug9rfwge9ni18c9l6zt','PlÃ¶tzliches andere Schrift bei einigen Links (Kontakt | Sitemap | Finde), passt Ã¼berhaupt nicht zum Rest.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','iiny2otwmid92y0njh8d7ohb7rsy0ll9','kh6xkvtrcluamb2llgl72pbs0a5ffna6','N','N','2025-03-16 15:46:59','2025-03-16 15:03:59',12),('hkblhdik5fdz67zqgaffgxw2igtob0kj','Aktuelle Position innerhalb der Site einfach festzustellen.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','iiny2otwmid92y0njh8d7ohb7rsy0ll9','c9y1ffwjxjmhgt6ieqe5qt5rlxh1iap6','Y','N','2005-09-26 18:43:17','2005-09-26 18:09:54',11),('58scson2j8w754hhme9z7jyls1m9gp04','\\Services: praktisch alle Links Ã¶ffnen ein neues Fenster und FÃ¼hren direkt in TUGOnline, warum wird hier nicht TUGOnline eingebunden, wie sonst so oft.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','iiny2otwmid92y0njh8d7ohb7rsy0ll9','kh6xkvtrcluamb2llgl72pbs0a5ffna6','N','N','2025-03-16 15:47:33','2025-03-16 15:03:33',10),('lnbxnl0pduudzgbgzbd06ce4fp6i0j2l','\\Services: anderes Design, nur Links, allerdings in grÃ¶ÃŸerer Schrift als sonst, Unterlinks haben wieder richtige Schrift.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','iiny2otwmid92y0njh8d7ohb7rsy0ll9','kh6xkvtrcluamb2llgl72pbs0a5ffna6','N','N','2025-03-16 15:47:17','2025-03-16 15:03:17',9),('kgb7lmyt0ru0ul4z1n2jvkl0h2y5wrs3','Ãœberschriften sind nicht durchgÃ¤ngig, meistens in GroÃŸ-Kleinschreibung, manche nur in GroÃŸschreibung, siehe Screenshot.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','iiny2otwmid92y0njh8d7ohb7rsy0ll9','kh6xkvtrcluamb2llgl72pbs0a5ffna6','N','N','2025-03-16 15:44:58','2025-03-16 15:03:58',8),('ox3pasydxmdz5o9lgxen4i7mqeproh73','\\Suche: Ergebnisse mit Emailadressen und direktem mailto-Link. Emailadressen kÃ¶nnen einfach ausgelesen werden, sollte Webmailinterface sein, wie bei TUGOnline.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','iiny2otwmid92y0njh8d7ohb7rsy0ll9','kh6xkvtrcluamb2llgl72pbs0a5ffna6','N','N','2025-03-16 15:41:12','2025-03-16 15:03:12',5),('uof6uaautg96inx45deikk7399rjyexb','\\Suche: beim Suchfeld Nachname bei Tel.-Nr/Mailadressen kann kein Stern eingegeben werden (wie explizit angegeben), es kommt fÃ¼r \'hub*\'die Meldung, dass fÃ¼r \'hub*\'; keine EintrÃ¤ge gefunden wurden. Bei Firefox funktionierts allerdings, wie gerade zufÃ¤llig gemerkt.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','iiny2otwmid92y0njh8d7ohb7rsy0ll9','kyc7hytxv6cmta3gw8ogh58fdha4qp3x','N','N','2025-03-16 15:44:24','2025-03-16 15:03:24',6),('oyz1i6t58l0bl8olt0p10n2udjg15jjl','\\Suche, sehr unÃ¼bersichtliches Layout durch Mischen von tugraz.at-Design mit TUGOnline, Newsserver, Google und GebÃ¤udesuche.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','iiny2otwmid92y0njh8d7ohb7rsy0ll9','p3pscjz696y6ajd3zdug67p3lpcmw0qd','N','N','2025-03-16 15:41:00','2025-03-16 15:03:00',4),('8rfx9qqus2vzwzupk0yhinfy9dochn66','Englische Sprache:\r\n- keine durchgÃ¤ngige Gestaltung auf Englisch\r\n- nicht einmal die Startseite (siehe Screenshots)\r\n- zieht sich durch gesammte Seite','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','iiny2otwmid92y0njh8d7ohb7rsy0ll9','kh6xkvtrcluamb2llgl72pbs0a5ffna6','N','N','2025-03-16 15:40:44','2025-03-16 15:03:44',3),('jpbuun5k9jmw8kc35ixmp4pdvknz897l','\\Aktuelles: zum Punkt \'Aktuelle Themen\':\r\n- die Ãœberschriften sehen aus wie Links, sind allerdings keine Links.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','iiny2otwmid92y0njh8d7ohb7rsy0ll9','kh6xkvtrcluamb2llgl72pbs0a5ffna6','N','N','2025-03-16 16:10:51','2025-03-16 16:03:51',2),('f527dnopn1996v30zd59u0vbkms6xwww','\\Aktuelles, zum Punkt \'Das Wetter\':\r\n- gute Idee\r\n- allerdings wird das Wetter vom Nachmittag (?) angezeigt, es hat jetzt sicher keine 22 Grad...\r\nGleich ned anzeigen, aber falsch schaut echt komisch aus.','3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','iiny2otwmid92y0njh8d7ohb7rsy0ll9','4h2220h2skh9a3vhgcb13flqnhuqhs98','N','N','2025-03-16 15:40:15','2025-03-16 15:03:15',1);
/*!40000 ALTER TABLE `test_finding` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_finding_rate`
--

DROP TABLE IF EXISTS `test_finding_rate`;
CREATE TABLE `test_finding_rate` (
  `uId` varchar(32) NOT NULL default '',
  `fId` varchar(32) NOT NULL default '',
  `scaleId` varchar(32) NOT NULL default '',
  `scaleValueId` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`uId`,`fId`,`scaleValueId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `test_finding_rate`
--

LOCK TABLES `test_finding_rate` WRITE;
/*!40000 ALTER TABLE `test_finding_rate` DISABLE KEYS */;
INSERT INTO `test_finding_rate` VALUES ('d4lep4hd1ssrdenoki58rxky96cx8jxn','ix25lyesrj0d5gbr6do9f9sjqn87rhfx','1','kbzp80b2xnpe0rkan8v8n3pc391gsflg'),('2swcmpex84arrcckfjd0iw9yf2ub9fen','ix25lyesrj0d5gbr6do9f9sjqn87rhfx','1','c9z9aum8nkafq74nfusv93ef3rwog6k4'),('iiny2otwmid92y0njh8d7ohb7rsy0ll9','ix25lyesrj0d5gbr6do9f9sjqn87rhfx','1','112'),('ayfht61xipijjnbmpt6swb63su25h0i2','ix25lyesrj0d5gbr6do9f9sjqn87rhfx','1','c9z9aum8nkafq74nfusv93ef3rwog6k4'),('d4lep4hd1ssrdenoki58rxky96cx8jxn','uuw4wjax85l0ogqaudm5cff3ojynnnyp','1','c9z9aum8nkafq74nfusv93ef3rwog6k4'),('2swcmpex84arrcckfjd0iw9yf2ub9fen','uuw4wjax85l0ogqaudm5cff3ojynnnyp','1','kbzp80b2xnpe0rkan8v8n3pc391gsflg'),('iiny2otwmid92y0njh8d7ohb7rsy0ll9','uuw4wjax85l0ogqaudm5cff3ojynnnyp','1','c9z9aum8nkafq74nfusv93ef3rwog6k4'),('ayfht61xipijjnbmpt6swb63su25h0i2','uuw4wjax85l0ogqaudm5cff3ojynnnyp','1','c9z9aum8nkafq74nfusv93ef3rwog6k4'),('d4lep4hd1ssrdenoki58rxky96cx8jxn','20n171xkeacafqec8a2hmrofmug5ubh9','1','uzmhm5fncydx5pa65fa11d6ttozlj0mu'),('2swcmpex84arrcckfjd0iw9yf2ub9fen','20n171xkeacafqec8a2hmrofmug5ubh9','1','c9z9aum8nkafq74nfusv93ef3rwog6k4'),('iiny2otwmid92y0njh8d7ohb7rsy0ll9','20n171xkeacafqec8a2hmrofmug5ubh9','1','c9z9aum8nkafq74nfusv93ef3rwog6k4'),('ayfht61xipijjnbmpt6swb63su25h0i2','20n171xkeacafqec8a2hmrofmug5ubh9','1','uzmhm5fncydx5pa65fa11d6ttozlj0mu'),('d4lep4hd1ssrdenoki58rxky96cx8jxn','9eon2idt84ytdlappmdt6naoixo7zjug','1','kbzp80b2xnpe0rkan8v8n3pc391gsflg'),('2swcmpex84arrcckfjd0iw9yf2ub9fen','9eon2idt84ytdlappmdt6naoixo7zjug','1','112'),('iiny2otwmid92y0njh8d7ohb7rsy0ll9','9eon2idt84ytdlappmdt6naoixo7zjug','1','kbzp80b2xnpe0rkan8v8n3pc391gsflg'),('ayfht61xipijjnbmpt6swb63su25h0i2','9eon2idt84ytdlappmdt6naoixo7zjug','1','kbzp80b2xnpe0rkan8v8n3pc391gsflg'),('d4lep4hd1ssrdenoki58rxky96cx8jxn','7zsqv2caerqp1ex0sy40jn6dnmmi7zsf','1','uzmhm5fncydx5pa65fa11d6ttozlj0mu'),('2swcmpex84arrcckfjd0iw9yf2ub9fen','7zsqv2caerqp1ex0sy40jn6dnmmi7zsf','1','kbzp80b2xnpe0rkan8v8n3pc391gsflg'),('iiny2otwmid92y0njh8d7ohb7rsy0ll9','7zsqv2caerqp1ex0sy40jn6dnmmi7zsf','1','kbzp80b2xnpe0rkan8v8n3pc391gsflg'),('ayfht61xipijjnbmpt6swb63su25h0i2','7zsqv2caerqp1ex0sy40jn6dnmmi7zsf','1','c9z9aum8nkafq74nfusv93ef3rwog6k4'),('d4lep4hd1ssrdenoki58rxky96cx8jxn','zrmhem77t2rokt3fax8muoz5w3pve37r','1','kbzp80b2xnpe0rkan8v8n3pc391gsflg'),('2swcmpex84arrcckfjd0iw9yf2ub9fen','zrmhem77t2rokt3fax8muoz5w3pve37r','1','c9z9aum8nkafq74nfusv93ef3rwog6k4'),('iiny2otwmid92y0njh8d7ohb7rsy0ll9','zrmhem77t2rokt3fax8muoz5w3pve37r','1','kbzp80b2xnpe0rkan8v8n3pc391gsflg'),('ayfht61xipijjnbmpt6swb63su25h0i2','zrmhem77t2rokt3fax8muoz5w3pve37r','1','kbzp80b2xnpe0rkan8v8n3pc391gsflg'),('d4lep4hd1ssrdenoki58rxky96cx8jxn','tib78p6qoex1cgs2kx6e6e8n10pr0yrn','1','kbzp80b2xnpe0rkan8v8n3pc391gsflg'),('2swcmpex84arrcckfjd0iw9yf2ub9fen','tib78p6qoex1cgs2kx6e6e8n10pr0yrn','1','kbzp80b2xnpe0rkan8v8n3pc391gsflg'),('iiny2otwmid92y0njh8d7ohb7rsy0ll9','tib78p6qoex1cgs2kx6e6e8n10pr0yrn','1','kbzp80b2xnpe0rkan8v8n3pc391gsflg'),('ayfht61xipijjnbmpt6swb63su25h0i2','tib78p6qoex1cgs2kx6e6e8n10pr0yrn','1','uzmhm5fncydx5pa65fa11d6ttozlj0mu'),('d4lep4hd1ssrdenoki58rxky96cx8jxn','r4acglsodt8zvnqe56skr5pdf2tdl9ae','1','kbzp80b2xnpe0rkan8v8n3pc391gsflg'),('2swcmpex84arrcckfjd0iw9yf2ub9fen','r4acglsodt8zvnqe56skr5pdf2tdl9ae','1','112'),('iiny2otwmid92y0njh8d7ohb7rsy0ll9','r4acglsodt8zvnqe56skr5pdf2tdl9ae','1','kbzp80b2xnpe0rkan8v8n3pc391gsflg'),('ayfht61xipijjnbmpt6swb63su25h0i2','r4acglsodt8zvnqe56skr5pdf2tdl9ae','1','kbzp80b2xnpe0rkan8v8n3pc391gsflg'),('d4lep4hd1ssrdenoki58rxky96cx8jxn','fvdmbucv6rew7p042mnr5jyy28dgb9nw','1','kbzp80b2xnpe0rkan8v8n3pc391gsflg'),('2swcmpex84arrcckfjd0iw9yf2ub9fen','fvdmbucv6rew7p042mnr5jyy28dgb9nw','1','112'),('iiny2otwmid92y0njh8d7ohb7rsy0ll9','fvdmbucv6rew7p042mnr5jyy28dgb9nw','1','kbzp80b2xnpe0rkan8v8n3pc391gsflg'),('ayfht61xipijjnbmpt6swb63su25h0i2','fvdmbucv6rew7p042mnr5jyy28dgb9nw','1','kbzp80b2xnpe0rkan8v8n3pc391gsflg'),('d4lep4hd1ssrdenoki58rxky96cx8jxn','2miqxhu876l8u1jp3gqblzflv0g8i5rz','1','c9z9aum8nkafq74nfusv93ef3rwog6k4'),('2swcmpex84arrcckfjd0iw9yf2ub9fen','2miqxhu876l8u1jp3gqblzflv0g8i5rz','1','112'),('iiny2otwmid92y0njh8d7ohb7rsy0ll9','2miqxhu876l8u1jp3gqblzflv0g8i5rz','1','112'),('ayfht61xipijjnbmpt6swb63su25h0i2','2miqxhu876l8u1jp3gqblzflv0g8i5rz','1','112'),('d4lep4hd1ssrdenoki58rxky96cx8jxn','xvhu40gfzx8e9feh7ec8kmsl9zmxisro','1','112'),('2swcmpex84arrcckfjd0iw9yf2ub9fen','xvhu40gfzx8e9feh7ec8kmsl9zmxisro','1','c9z9aum8nkafq74nfusv93ef3rwog6k4'),('iiny2otwmid92y0njh8d7ohb7rsy0ll9','xvhu40gfzx8e9feh7ec8kmsl9zmxisro','1','c9z9aum8nkafq74nfusv93ef3rwog6k4'),('ayfht61xipijjnbmpt6swb63su25h0i2','xvhu40gfzx8e9feh7ec8kmsl9zmxisro','1','kbzp80b2xnpe0rkan8v8n3pc391gsflg'),('d4lep4hd1ssrdenoki58rxky96cx8jxn','ofazdwamcrbz97qaxwyqn56y5p143ap7','1','c9z9aum8nkafq74nfusv93ef3rwog6k4'),('2swcmpex84arrcckfjd0iw9yf2ub9fen','ofazdwamcrbz97qaxwyqn56y5p143ap7','1','112'),('iiny2otwmid92y0njh8d7ohb7rsy0ll9','ofazdwamcrbz97qaxwyqn56y5p143ap7','1','112'),('ayfht61xipijjnbmpt6swb63su25h0i2','ofazdwamcrbz97qaxwyqn56y5p143ap7','1','c9z9aum8nkafq74nfusv93ef3rwog6k4'),('d4lep4hd1ssrdenoki58rxky96cx8jxn','03bydn8c0kyv2gj458oa2yzah9tsd80z','1','112'),('2swcmpex84arrcckfjd0iw9yf2ub9fen','03bydn8c0kyv2gj458oa2yzah9tsd80z','1','uzmhm5fncydx5pa65fa11d6ttozlj0mu'),('iiny2otwmid92y0njh8d7ohb7rsy0ll9','03bydn8c0kyv2gj458oa2yzah9tsd80z','1','kbzp80b2xnpe0rkan8v8n3pc391gsflg'),('ayfht61xipijjnbmpt6swb63su25h0i2','03bydn8c0kyv2gj458oa2yzah9tsd80z','1','uzmhm5fncydx5pa65fa11d6ttozlj0mu'),('d4lep4hd1ssrdenoki58rxky96cx8jxn','dto51f1gefyzekennavhinvww9cu112t','1','kbzp80b2xnpe0rkan8v8n3pc391gsflg'),('2swcmpex84arrcckfjd0iw9yf2ub9fen','dto51f1gefyzekennavhinvww9cu112t','1','kbzp80b2xnpe0rkan8v8n3pc391gsflg'),('iiny2otwmid92y0njh8d7ohb7rsy0ll9','dto51f1gefyzekennavhinvww9cu112t','1','112'),('ayfht61xipijjnbmpt6swb63su25h0i2','dto51f1gefyzekennavhinvww9cu112t','1','kbzp80b2xnpe0rkan8v8n3pc391gsflg'),('d4lep4hd1ssrdenoki58rxky96cx8jxn','6vatxwtjr9d5f6i0e9wy4heoqbtc6juo','1','112'),('2swcmpex84arrcckfjd0iw9yf2ub9fen','6vatxwtjr9d5f6i0e9wy4heoqbtc6juo','1','112'),('iiny2otwmid92y0njh8d7ohb7rsy0ll9','6vatxwtjr9d5f6i0e9wy4heoqbtc6juo','1','j1qiay8xep4m7uzeod1792z419j63rn2'),('ayfht61xipijjnbmpt6swb63su25h0i2','6vatxwtjr9d5f6i0e9wy4heoqbtc6juo','1','kbzp80b2xnpe0rkan8v8n3pc391gsflg'),('d4lep4hd1ssrdenoki58rxky96cx8jxn','hmf6pl12wv8lbhi2pjames6o5gthzirk','1','c9z9aum8nkafq74nfusv93ef3rwog6k4'),('2swcmpex84arrcckfjd0iw9yf2ub9fen','hmf6pl12wv8lbhi2pjames6o5gthzirk','1','kbzp80b2xnpe0rkan8v8n3pc391gsflg'),('iiny2otwmid92y0njh8d7ohb7rsy0ll9','hmf6pl12wv8lbhi2pjames6o5gthzirk','1','c9z9aum8nkafq74nfusv93ef3rwog6k4'),('ayfht61xipijjnbmpt6swb63su25h0i2','hmf6pl12wv8lbhi2pjames6o5gthzirk','1','112'),('d4lep4hd1ssrdenoki58rxky96cx8jxn','xqgalxd0imyqlrdqjgpcwllxf01tcktw','1','uzmhm5fncydx5pa65fa11d6ttozlj0mu'),('2swcmpex84arrcckfjd0iw9yf2ub9fen','xqgalxd0imyqlrdqjgpcwllxf01tcktw','1','c9z9aum8nkafq74nfusv93ef3rwog6k4'),('iiny2otwmid92y0njh8d7ohb7rsy0ll9','xqgalxd0imyqlrdqjgpcwllxf01tcktw','1','c9z9aum8nkafq74nfusv93ef3rwog6k4'),('ayfht61xipijjnbmpt6swb63su25h0i2','xqgalxd0imyqlrdqjgpcwllxf01tcktw','1','c9z9aum8nkafq74nfusv93ef3rwog6k4'),('d4lep4hd1ssrdenoki58rxky96cx8jxn','2i9xvejw1k9qa6hx6na21l038x8z95ox','1','uzmhm5fncydx5pa65fa11d6ttozlj0mu'),('2swcmpex84arrcckfjd0iw9yf2ub9fen','2i9xvejw1k9qa6hx6na21l038x8z95ox','1','uzmhm5fncydx5pa65fa11d6ttozlj0mu'),('iiny2otwmid92y0njh8d7ohb7rsy0ll9','2i9xvejw1k9qa6hx6na21l038x8z95ox','1','c9z9aum8nkafq74nfusv93ef3rwog6k4'),('ayfht61xipijjnbmpt6swb63su25h0i2','2i9xvejw1k9qa6hx6na21l038x8z95ox','1','112'),('d4lep4hd1ssrdenoki58rxky96cx8jxn','x2116j9rm76lw43mnty5gx7mixzikfn0','1','kbzp80b2xnpe0rkan8v8n3pc391gsflg'),('2swcmpex84arrcckfjd0iw9yf2ub9fen','x2116j9rm76lw43mnty5gx7mixzikfn0','1','uzmhm5fncydx5pa65fa11d6ttozlj0mu'),('iiny2otwmid92y0njh8d7ohb7rsy0ll9','x2116j9rm76lw43mnty5gx7mixzikfn0','1','112'),('ayfht61xipijjnbmpt6swb63su25h0i2','x2116j9rm76lw43mnty5gx7mixzikfn0','1','kbzp80b2xnpe0rkan8v8n3pc391gsflg'),('d4lep4hd1ssrdenoki58rxky96cx8jxn','qfgf7rzlwk9tsd8na73yrxt0mdh37j2q','1','kbzp80b2xnpe0rkan8v8n3pc391gsflg'),('2swcmpex84arrcckfjd0iw9yf2ub9fen','qfgf7rzlwk9tsd8na73yrxt0mdh37j2q','1','kbzp80b2xnpe0rkan8v8n3pc391gsflg'),('iiny2otwmid92y0njh8d7ohb7rsy0ll9','qfgf7rzlwk9tsd8na73yrxt0mdh37j2q','1','kbzp80b2xnpe0rkan8v8n3pc391gsflg'),('ayfht61xipijjnbmpt6swb63su25h0i2','qfgf7rzlwk9tsd8na73yrxt0mdh37j2q','1','c9z9aum8nkafq74nfusv93ef3rwog6k4'),('d4lep4hd1ssrdenoki58rxky96cx8jxn','1cbmswokquxqxx88kr8eopmu4hfzv6ok','1','uzmhm5fncydx5pa65fa11d6ttozlj0mu'),('2swcmpex84arrcckfjd0iw9yf2ub9fen','1cbmswokquxqxx88kr8eopmu4hfzv6ok','1','uzmhm5fncydx5pa65fa11d6ttozlj0mu'),('iiny2otwmid92y0njh8d7ohb7rsy0ll9','1cbmswokquxqxx88kr8eopmu4hfzv6ok','1','c9z9aum8nkafq74nfusv93ef3rwog6k4'),('ayfht61xipijjnbmpt6swb63su25h0i2','1cbmswokquxqxx88kr8eopmu4hfzv6ok','1','kbzp80b2xnpe0rkan8v8n3pc391gsflg'),('d4lep4hd1ssrdenoki58rxky96cx8jxn','ulp71df7hiwpmuycr0u7gkbvrdy19l48','1','c9z9aum8nkafq74nfusv93ef3rwog6k4'),('2swcmpex84arrcckfjd0iw9yf2ub9fen','ulp71df7hiwpmuycr0u7gkbvrdy19l48','1','c9z9aum8nkafq74nfusv93ef3rwog6k4'),('iiny2otwmid92y0njh8d7ohb7rsy0ll9','ulp71df7hiwpmuycr0u7gkbvrdy19l48','1','kbzp80b2xnpe0rkan8v8n3pc391gsflg'),('ayfht61xipijjnbmpt6swb63su25h0i2','ulp71df7hiwpmuycr0u7gkbvrdy19l48','1','j1qiay8xep4m7uzeod1792z419j63rn2'),('d4lep4hd1ssrdenoki58rxky96cx8jxn','1fsfomhuz5mc5dcty54mkngtsqkhil99','1','kbzp80b2xnpe0rkan8v8n3pc391gsflg'),('2swcmpex84arrcckfjd0iw9yf2ub9fen','1fsfomhuz5mc5dcty54mkngtsqkhil99','1','kbzp80b2xnpe0rkan8v8n3pc391gsflg'),('iiny2otwmid92y0njh8d7ohb7rsy0ll9','1fsfomhuz5mc5dcty54mkngtsqkhil99','1','kbzp80b2xnpe0rkan8v8n3pc391gsflg'),('ayfht61xipijjnbmpt6swb63su25h0i2','1fsfomhuz5mc5dcty54mkngtsqkhil99','1','c9z9aum8nkafq74nfusv93ef3rwog6k4'),('d4lep4hd1ssrdenoki58rxky96cx8jxn','b9cavrt3hsj0fs3soqr1k97v07ixq32e','1','kbzp80b2xnpe0rkan8v8n3pc391gsflg'),('2swcmpex84arrcckfjd0iw9yf2ub9fen','b9cavrt3hsj0fs3soqr1k97v07ixq32e','1','kbzp80b2xnpe0rkan8v8n3pc391gsflg'),('iiny2otwmid92y0njh8d7ohb7rsy0ll9','b9cavrt3hsj0fs3soqr1k97v07ixq32e','1','112'),('ayfht61xipijjnbmpt6swb63su25h0i2','b9cavrt3hsj0fs3soqr1k97v07ixq32e','1','kbzp80b2xnpe0rkan8v8n3pc391gsflg'),('d4lep4hd1ssrdenoki58rxky96cx8jxn','32yffuqvtqnkq3f6ztlidp7en1nvo1nz','1','112'),('2swcmpex84arrcckfjd0iw9yf2ub9fen','32yffuqvtqnkq3f6ztlidp7en1nvo1nz','1','kbzp80b2xnpe0rkan8v8n3pc391gsflg'),('iiny2otwmid92y0njh8d7ohb7rsy0ll9','32yffuqvtqnkq3f6ztlidp7en1nvo1nz','1','kbzp80b2xnpe0rkan8v8n3pc391gsflg'),('ayfht61xipijjnbmpt6swb63su25h0i2','32yffuqvtqnkq3f6ztlidp7en1nvo1nz','1','c9z9aum8nkafq74nfusv93ef3rwog6k4');
/*!40000 ALTER TABLE `test_finding_rate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_heuristic`
--

DROP TABLE IF EXISTS `test_heuristic`;
CREATE TABLE `test_heuristic` (
  `hId` varchar(32) NOT NULL default '',
  `hTitle` varchar(100) NOT NULL default '',
  `hTitleId` varchar(32) NOT NULL default '',
  `hDescription` text NOT NULL,
  `hDescriptionId` varchar(32) NOT NULL default '',
  `hSetId` varchar(32) NOT NULL default '',
  `hOrder` int(10) NOT NULL default '0',
  PRIMARY KEY  (`hId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `test_heuristic`
--

LOCK TABLES `test_heuristic` WRITE;
/*!40000 ALTER TABLE `test_heuristic` DISABLE KEYS */;
INSERT INTO `test_heuristic` VALUES ('ik44cv5t8pisxdov4lpvn9scn4qnqswn','','qvkbomj8aun5e7xq4mdo9yczrq80xt1x','','tldy5cuysoh4hinwehzpp1czw217725o','io1cd6u3g7e6xp75hp6cpwvnd2ivvvfk',10),('0ihy914vv9z6uu44qk20xq75h2p3iune','','g0ji712rc6ki7wc18r8ixvbehqmcrqby','','a6svqge47yrkmnl5civmbmordlypvghb','io1cd6u3g7e6xp75hp6cpwvnd2ivvvfk',9),('evluu4amjc4k2ljd3oota475fp5edhvm','','koe29tbg6b6k43vxxjh4ci2bvabcjxbo','','vt6p3k53cusph4fvfped35m4k3fzdtdx','io1cd6u3g7e6xp75hp6cpwvnd2ivvvfk',8),('xvo6v2ygbnnuqyx07k9mzblvt9vy3w83','','il0ciqyt086bcl7xjnhfumub8h2dg26m','','0fxcsxg3z8iu4paptd98kf2bqal7jblg','io1cd6u3g7e6xp75hp6cpwvnd2ivvvfk',7),('67vxzy2lbvjsis1hpjoy9a0bb3ayuulv','','f7cmzah1jnkcipvucnqc6gwz5lis1ic0','','qqib359hvnp9y8o7u5vmebyx7p4et3k0','io1cd6u3g7e6xp75hp6cpwvnd2ivvvfk',6),('webb4si1ovaaapzf1ze6auccvlf32gng','','wkc0kbarcmy5yeh7l63py70rgtvozy06','','mad9252uzlu3vb1z7bjcw221gctjoy87','io1cd6u3g7e6xp75hp6cpwvnd2ivvvfk',5),('uvoaw6h95ihi8awcphqk0952r50achmi','','ej6epsgjj6rhfoa9o39djic8g8sz3f3p','','h1x6lzwcblv8ervu3c4ohc9ukzzj9wym','io1cd6u3g7e6xp75hp6cpwvnd2ivvvfk',4),('ud260zwckfpva0xl0fnach1agwpbb1kt','','5ooa2zb9bdpobpdkzqq0kzl2sui5wdcc','','ej944is9kvs2pnm6vutsed2kd5twrlk5','io1cd6u3g7e6xp75hp6cpwvnd2ivvvfk',3),('wgl69bi7ugy6c9i9x4tuca7hizssi42r','','anggqtkpq5tftxopwlwm0ipjtehr7uq5','','drc2k3jp7xvx2stzjmlaujg7tkdnuitp','io1cd6u3g7e6xp75hp6cpwvnd2ivvvfk',2),('4ot0hthyub3fitrqzyeyyzlic5tjbne7','','5jsnx8xbmjr690nfcyru1iw1zrnajgzl','','xeea3yzlvp076rj0rkqzl8wmyhjau3ml','io1cd6u3g7e6xp75hp6cpwvnd2ivvvfk',1),('c9y1ffwjxjmhgt6ieqe5qt5rlxh1iap6','','drqz1pkzuum5n6lz3ydr93n7sm7zrmws','','g51rxmso3flw37h1ccdghe4xwuf29xkv','x0v3wq83jcq8ub2baervqsxn8csup2i2',1),('5a9jqiuoyt10eljlbx48pmefoxni9h9i','','f7i1tt9umgpxtl936wksv4msol379lsa','','tltomgmg2hsbdexulaftcaoujh5pkm3y','x0v3wq83jcq8ub2baervqsxn8csup2i2',2),('hwk33tkcyt12897l8iuljcurdud3dpbl','','uzdvaa19p4hzj9nwh2bxi7ts39gu2apl','','lokgo8z82pit1x70432ydz4i9yke8t9d','x0v3wq83jcq8ub2baervqsxn8csup2i2',3),('kh6xkvtrcluamb2llgl72pbs0a5ffna6','','d7qfl46n8v73l25ulrn7s2ejuv1gmot1','','t0tt30dyo0ux1oq8iac60uyqxq6x7net','x0v3wq83jcq8ub2baervqsxn8csup2i2',4),('kyc7hytxv6cmta3gw8ogh58fdha4qp3x','','lvu7kn2mtpj7ztihyfk3f6xclzwnx2yp','','wu4cgmesr0444e4zcpnbsih5c3badhlu','x0v3wq83jcq8ub2baervqsxn8csup2i2',5),('p3pscjz696y6ajd3zdug67p3lpcmw0qd','','9hxtxyo265y5gqqb10dzbqteng4pvcqr','','739acl03tnp7cqumwbmghm7ogd24692k','x0v3wq83jcq8ub2baervqsxn8csup2i2',6),('lqu799emv33yfd9dc64kezy1nf6hv22m','','52uknnuce58dwqwlucdqtbcysfz7yvpi','','kca1xjbzme7f3sq1cxo8d5y7ww8bi5pj','x0v3wq83jcq8ub2baervqsxn8csup2i2',7),('4h2220h2skh9a3vhgcb13flqnhuqhs98','','rj5khgj43jqxkef3245vigf6a44llq3p','','pp22lfrr4vyzil1lkk2plitfpa4d4bll','x0v3wq83jcq8ub2baervqsxn8csup2i2',8),('uaj0987e3ikcfthgc16bi578pwsihnvd','','lv3a6rm5ya4wdj6abnp9enh5ha1agin4','','pl9fpincixt1elwdv9qx67bd4nolirnp','x0v3wq83jcq8ub2baervqsxn8csup2i2',9),('p6ysbha80utxpxov4fg8sv3l6o4b8vq5','','zmqlwm5669mzo3lye16ttrm9y41rfdln','','y15d2kmq9myi479qohlbxyp50cu0vycm','x0v3wq83jcq8ub2baervqsxn8csup2i2',10);
/*!40000 ALTER TABLE `test_heuristic` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_heuristic_set`
--

DROP TABLE IF EXISTS `test_heuristic_set`;
CREATE TABLE `test_heuristic_set` (
  `hSetId` varchar(32) NOT NULL default '',
  `hSetTitle` varchar(100) NOT NULL default '',
  `hSetTitleId` varchar(32) NOT NULL default '',
  `hSetDescription` text NOT NULL,
  `hSetDescriptionId` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`hSetId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `test_heuristic_set`
--

LOCK TABLES `test_heuristic_set` WRITE;
/*!40000 ALTER TABLE `test_heuristic_set` DISABLE KEYS */;
INSERT INTO `test_heuristic_set` VALUES ('io1cd6u3g7e6xp75hp6cpwvnd2ivvvfk','','wjpjn8frbexxnko1ublijm75v5mges5q','','p0up8b5s1ok43w0xp4i2nwmcmf20mawa'),('x0v3wq83jcq8ub2baervqsxn8csup2i2','','kqhn8lvo9sp03pmylyvgk2qn3gyrwaa3','','7izar5bzuvlul7id2nssj2nbnv75168i');
/*!40000 ALTER TABLE `test_heuristic_set` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_liveuser_applications`
--

DROP TABLE IF EXISTS `test_liveuser_applications`;
CREATE TABLE `test_liveuser_applications` (
  `application_id` int(11) unsigned NOT NULL default '0',
  `application_define_name` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`application_id`),
  UNIQUE KEY `application_define_name` (`application_define_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `test_liveuser_applications`
--

LOCK TABLES `test_liveuser_applications` WRITE;
/*!40000 ALTER TABLE `test_liveuser_applications` DISABLE KEYS */;
INSERT INTO `test_liveuser_applications` VALUES (1,'HEM');
/*!40000 ALTER TABLE `test_liveuser_applications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_liveuser_applications_seq`
--

DROP TABLE IF EXISTS `test_liveuser_applications_seq`;
CREATE TABLE `test_liveuser_applications_seq` (
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `test_liveuser_applications_seq`
--

LOCK TABLES `test_liveuser_applications_seq` WRITE;
/*!40000 ALTER TABLE `test_liveuser_applications_seq` DISABLE KEYS */;
INSERT INTO `test_liveuser_applications_seq` VALUES (1);
/*!40000 ALTER TABLE `test_liveuser_applications_seq` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_liveuser_area_admin_areas`
--

DROP TABLE IF EXISTS `test_liveuser_area_admin_areas`;
CREATE TABLE `test_liveuser_area_admin_areas` (
  `area_id` int(11) unsigned NOT NULL default '0',
  `perm_user_id` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`area_id`,`perm_user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `test_liveuser_area_admin_areas`
--

LOCK TABLES `test_liveuser_area_admin_areas` WRITE;
/*!40000 ALTER TABLE `test_liveuser_area_admin_areas` DISABLE KEYS */;
/*!40000 ALTER TABLE `test_liveuser_area_admin_areas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_liveuser_areas`
--

DROP TABLE IF EXISTS `test_liveuser_areas`;
CREATE TABLE `test_liveuser_areas` (
  `area_id` int(11) unsigned NOT NULL default '0',
  `application_id` int(11) unsigned NOT NULL default '0',
  `area_define_name` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`area_id`),
  UNIQUE KEY `area_define_name` (`application_id`,`area_define_name`),
  KEY `areas_application_id` (`application_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `test_liveuser_areas`
--

LOCK TABLES `test_liveuser_areas` WRITE;
/*!40000 ALTER TABLE `test_liveuser_areas` DISABLE KEYS */;
INSERT INTO `test_liveuser_areas` VALUES (1,1,'AREA');
/*!40000 ALTER TABLE `test_liveuser_areas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_liveuser_areas_seq`
--

DROP TABLE IF EXISTS `test_liveuser_areas_seq`;
CREATE TABLE `test_liveuser_areas_seq` (
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `test_liveuser_areas_seq`
--

LOCK TABLES `test_liveuser_areas_seq` WRITE;
/*!40000 ALTER TABLE `test_liveuser_areas_seq` DISABLE KEYS */;
INSERT INTO `test_liveuser_areas_seq` VALUES (1);
/*!40000 ALTER TABLE `test_liveuser_areas_seq` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_liveuser_group_subgroups`
--

DROP TABLE IF EXISTS `test_liveuser_group_subgroups`;
CREATE TABLE `test_liveuser_group_subgroups` (
  `group_id` int(11) unsigned NOT NULL default '0',
  `subgroup_id` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`group_id`,`subgroup_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `test_liveuser_group_subgroups`
--

LOCK TABLES `test_liveuser_group_subgroups` WRITE;
/*!40000 ALTER TABLE `test_liveuser_group_subgroups` DISABLE KEYS */;
/*!40000 ALTER TABLE `test_liveuser_group_subgroups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_liveuser_grouprights`
--

DROP TABLE IF EXISTS `test_liveuser_grouprights`;
CREATE TABLE `test_liveuser_grouprights` (
  `group_id` int(11) unsigned NOT NULL default '0',
  `right_id` int(11) unsigned NOT NULL default '0',
  `right_level` tinyint(3) unsigned default '3',
  PRIMARY KEY  (`group_id`,`right_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `test_liveuser_grouprights`
--

LOCK TABLES `test_liveuser_grouprights` WRITE;
/*!40000 ALTER TABLE `test_liveuser_grouprights` DISABLE KEYS */;
INSERT INTO `test_liveuser_grouprights` VALUES (2,1,3),(3,1,3),(2,2,3),(3,2,3),(2,3,3),(3,3,3),(2,4,3),(3,4,3),(2,5,3),(3,5,3),(3,7,3),(2,7,3),(2,6,3),(3,6,3),(3,9,3),(2,9,3),(3,8,3),(2,8,3),(3,10,3),(2,10,3),(1,10,3),(3,11,3),(2,11,3),(3,13,3),(2,13,3),(1,13,3),(3,14,3),(2,14,3),(3,15,3),(2,15,3),(3,16,3),(2,16,3),(1,16,3),(3,17,3),(2,17,3),(3,18,3),(2,18,3),(3,19,3),(2,19,3),(3,20,3),(2,20,3),(3,21,3),(2,21,3);
/*!40000 ALTER TABLE `test_liveuser_grouprights` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_liveuser_groups`
--

DROP TABLE IF EXISTS `test_liveuser_groups`;
CREATE TABLE `test_liveuser_groups` (
  `group_id` int(11) unsigned NOT NULL default '0',
  `group_type` int(11) unsigned default '1',
  `group_define_name` varchar(32) NOT NULL default '',
  `owner_user_id` int(11) unsigned default NULL,
  `owner_group_id` int(11) unsigned default NULL,
  `is_active` char(1) NOT NULL default 'N',
  PRIMARY KEY  (`group_id`),
  UNIQUE KEY `group_define_name` (`group_define_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `test_liveuser_groups`
--

LOCK TABLES `test_liveuser_groups` WRITE;
/*!40000 ALTER TABLE `test_liveuser_groups` DISABLE KEYS */;
INSERT INTO `test_liveuser_groups` VALUES (1,1,'EVALUATOR',NULL,NULL,'Y'),(2,1,'MANAGER',NULL,NULL,'Y'),(3,1,'ADMIN',NULL,NULL,'Y');
/*!40000 ALTER TABLE `test_liveuser_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_liveuser_groups_seq`
--

DROP TABLE IF EXISTS `test_liveuser_groups_seq`;
CREATE TABLE `test_liveuser_groups_seq` (
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `test_liveuser_groups_seq`
--

LOCK TABLES `test_liveuser_groups_seq` WRITE;
/*!40000 ALTER TABLE `test_liveuser_groups_seq` DISABLE KEYS */;
INSERT INTO `test_liveuser_groups_seq` VALUES (3);
/*!40000 ALTER TABLE `test_liveuser_groups_seq` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_liveuser_groupusers`
--

DROP TABLE IF EXISTS `test_liveuser_groupusers`;
CREATE TABLE `test_liveuser_groupusers` (
  `perm_user_id` int(11) unsigned NOT NULL default '0',
  `group_id` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`group_id`,`perm_user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `test_liveuser_groupusers`
--

LOCK TABLES `test_liveuser_groupusers` WRITE;
/*!40000 ALTER TABLE `test_liveuser_groupusers` DISABLE KEYS */;
INSERT INTO `test_liveuser_groupusers` VALUES (10,1),(49,1),(50,1),(51,1),(52,1),(10,2),(51,2),(10,3);
/*!40000 ALTER TABLE `test_liveuser_groupusers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_liveuser_languages`
--

DROP TABLE IF EXISTS `test_liveuser_languages`;
CREATE TABLE `test_liveuser_languages` (
  `language_id` smallint(5) unsigned NOT NULL default '0',
  `two_letter_name` char(2) NOT NULL default '',
  PRIMARY KEY  (`language_id`),
  UNIQUE KEY `two_letter_name` (`two_letter_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `test_liveuser_languages`
--

LOCK TABLES `test_liveuser_languages` WRITE;
/*!40000 ALTER TABLE `test_liveuser_languages` DISABLE KEYS */;
INSERT INTO `test_liveuser_languages` VALUES (1,'en');
/*!40000 ALTER TABLE `test_liveuser_languages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_liveuser_languages_seq`
--

DROP TABLE IF EXISTS `test_liveuser_languages_seq`;
CREATE TABLE `test_liveuser_languages_seq` (
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `test_liveuser_languages_seq`
--

LOCK TABLES `test_liveuser_languages_seq` WRITE;
/*!40000 ALTER TABLE `test_liveuser_languages_seq` DISABLE KEYS */;
INSERT INTO `test_liveuser_languages_seq` VALUES (1);
/*!40000 ALTER TABLE `test_liveuser_languages_seq` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_liveuser_perm_users`
--

DROP TABLE IF EXISTS `test_liveuser_perm_users`;
CREATE TABLE `test_liveuser_perm_users` (
  `perm_user_id` int(11) unsigned NOT NULL default '0',
  `auth_user_id` varchar(32) NOT NULL default '0',
  `perm_type` tinyint(3) unsigned default NULL,
  `auth_container_name` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`perm_user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `test_liveuser_perm_users`
--

LOCK TABLES `test_liveuser_perm_users` WRITE;
/*!40000 ALTER TABLE `test_liveuser_perm_users` DISABLE KEYS */;
INSERT INTO `test_liveuser_perm_users` VALUES (10,'29214857b12575501c5c731353c7217e',1,'DB_Local'),(49,'2swcmpex84arrcckfjd0iw9yf2ub9fen',1,'DB_Local'),(50,'ayfht61xipijjnbmpt6swb63su25h0i2',1,'DB_Local'),(51,'d4lep4hd1ssrdenoki58rxky96cx8jxn',1,'DB_Local'),(52,'iiny2otwmid92y0njh8d7ohb7rsy0ll9',1,'DB_Local');
/*!40000 ALTER TABLE `test_liveuser_perm_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_liveuser_perm_users_seq`
--

DROP TABLE IF EXISTS `test_liveuser_perm_users_seq`;
CREATE TABLE `test_liveuser_perm_users_seq` (
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=53 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `test_liveuser_perm_users_seq`
--

LOCK TABLES `test_liveuser_perm_users_seq` WRITE;
/*!40000 ALTER TABLE `test_liveuser_perm_users_seq` DISABLE KEYS */;
INSERT INTO `test_liveuser_perm_users_seq` VALUES (52);
/*!40000 ALTER TABLE `test_liveuser_perm_users_seq` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_liveuser_right_implied`
--

DROP TABLE IF EXISTS `test_liveuser_right_implied`;
CREATE TABLE `test_liveuser_right_implied` (
  `right_id` int(11) unsigned NOT NULL default '0',
  `implied_right_id` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`implied_right_id`,`right_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `test_liveuser_right_implied`
--

LOCK TABLES `test_liveuser_right_implied` WRITE;
/*!40000 ALTER TABLE `test_liveuser_right_implied` DISABLE KEYS */;
/*!40000 ALTER TABLE `test_liveuser_right_implied` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_liveuser_right_scopes`
--

DROP TABLE IF EXISTS `test_liveuser_right_scopes`;
CREATE TABLE `test_liveuser_right_scopes` (
  `right_id` int(11) unsigned NOT NULL default '0',
  `right_type` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`right_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `test_liveuser_right_scopes`
--

LOCK TABLES `test_liveuser_right_scopes` WRITE;
/*!40000 ALTER TABLE `test_liveuser_right_scopes` DISABLE KEYS */;
/*!40000 ALTER TABLE `test_liveuser_right_scopes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_liveuser_rights`
--

DROP TABLE IF EXISTS `test_liveuser_rights`;
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `test_liveuser_rights`
--

LOCK TABLES `test_liveuser_rights` WRITE;
/*!40000 ALTER TABLE `test_liveuser_rights` DISABLE KEYS */;
INSERT INTO `test_liveuser_rights` VALUES (1,1,'VIEW_MANAGER_DATA','N','N','N'),(2,1,'CHANGE_GROUP_MEMBERSHIP','N','N','N'),(3,1,'CHANGE_OTHER_USERS','N','N','N'),(4,1,'CHANGE_OTHER_PASSWORD','N','N','N'),(5,1,'CHANGE_OTHER_USERNAME','N','N','N'),(6,1,'CHANGE_ACTIVE_FLAG','N','N','N'),(7,1,'EDIT_HEURISTIC_SETS','N','N','N'),(8,1,'MERGE_FINDINGS','N','N','N'),(9,1,'MANAGE_PROJECTS','N','N','N'),(10,1,'COLLECT_FINDINGS','N','N','N'),(11,1,'MANAGE_HEURISTICS','N','N','N'),(12,1,'MANAGER_ENVIRONMENTS','N','N','N'),(13,1,'ADD_ENVIRONMENT_DATA','N','N','N'),(14,1,'CHANGE_OTHER_FINDINGS','N','N','N'),(15,1,'CHANGE_OTHER_RATINGS','N','N','N'),(16,1,'COLLECT_RATINGS','N','N','N'),(17,1,'MANAGE_RATINGSCALES','N','N','N'),(18,1,'MANAGE_RATINGSCHEMES','N','N','N'),(19,1,'MANAGE_ENVIRONMENTS','N','N','N'),(20,1,'CHANGE_OTHER_ENVIRONMENTS','N','N','N'),(21,1,'MANAGE_REPORTS','N','N','N');
/*!40000 ALTER TABLE `test_liveuser_rights` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_liveuser_rights_seq`
--

DROP TABLE IF EXISTS `test_liveuser_rights_seq`;
CREATE TABLE `test_liveuser_rights_seq` (
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `test_liveuser_rights_seq`
--

LOCK TABLES `test_liveuser_rights_seq` WRITE;
/*!40000 ALTER TABLE `test_liveuser_rights_seq` DISABLE KEYS */;
INSERT INTO `test_liveuser_rights_seq` VALUES (21);
/*!40000 ALTER TABLE `test_liveuser_rights_seq` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_liveuser_translations`
--

DROP TABLE IF EXISTS `test_liveuser_translations`;
CREATE TABLE `test_liveuser_translations` (
  `section_id` int(11) unsigned NOT NULL default '0',
  `section_type` tinyint(3) unsigned NOT NULL default '0',
  `language_id` smallint(5) unsigned NOT NULL default '0',
  `name` varchar(50) NOT NULL default '',
  `description` varchar(255) default NULL,
  PRIMARY KEY  (`section_id`,`section_type`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `test_liveuser_translations`
--

LOCK TABLES `test_liveuser_translations` WRITE;
/*!40000 ALTER TABLE `test_liveuser_translations` DISABLE KEYS */;
INSERT INTO `test_liveuser_translations` VALUES (1,4,1,'english','English language'),(1,1,1,'Heuristic Evaluation Manager',NULL),(1,2,1,'The Only Area',NULL),(1,3,1,'evaluator','The Evaluators'),(2,3,1,'manager','The Managers'),(3,3,1,'admin','The Administrators'),(1,5,1,'View Manager Data','Right to view Manager Data'),(2,5,1,'Change Group Membership','Change the group membership of a User'),(3,5,1,'Change other users data','Change other users data'),(4,5,1,'Change other users password','Change the Password of another user'),(5,5,1,'Change other username','The Right to change the username from an other user'),(6,5,1,'Change Active Flag','Right to activate a useraccount'),(7,5,1,'Edit Heuristics','The Right to change and delete Heuristoc Sets and Heuristics'),(8,5,1,'Merge Findings','The right to merge Findings'),(9,5,1,'Manage Projects','The right to manage Projects'),(10,5,1,'Evaluate','The right to add findings to database'),(11,5,1,'Manage Heuristic Sets','The Right to manage heuristic Sets'),(12,5,1,'Manage Environments','The Right to manage Evaluation Environemnet Forms'),(13,5,1,'Add Environment Data','The right to fill out environment Data for Project'),(14,5,1,'Change other findings','The right to change other findings, also after end of E Phase'),(15,5,1,'Change other users ratings','The right to chnage other users ratings'),(16,5,1,'The right to collect ratings','Use the rating collector'),(17,5,1,'The right to manage rating scales',''),(18,5,1,'The right to manage rating schemes',''),(19,5,1,'The right to manage environments',''),(20,5,1,'The right to change other users Environment Data',''),(21,5,1,'The right to generate and view reports','');
/*!40000 ALTER TABLE `test_liveuser_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_liveuser_userrights`
--

DROP TABLE IF EXISTS `test_liveuser_userrights`;
CREATE TABLE `test_liveuser_userrights` (
  `perm_user_id` int(11) unsigned NOT NULL default '0',
  `right_id` int(11) unsigned NOT NULL default '0',
  `right_level` tinyint(3) default '3',
  PRIMARY KEY  (`right_id`,`perm_user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `test_liveuser_userrights`
--

LOCK TABLES `test_liveuser_userrights` WRITE;
/*!40000 ALTER TABLE `test_liveuser_userrights` DISABLE KEYS */;
/*!40000 ALTER TABLE `test_liveuser_userrights` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_liveuser_users`
--

DROP TABLE IF EXISTS `test_liveuser_users`;
CREATE TABLE `test_liveuser_users` (
  `auth_user_id` varchar(32) NOT NULL default '0',
  `handle` varchar(32) NOT NULL default '',
  `passwd` varchar(32) NOT NULL default '',
  `lastlogin` datetime default NULL,
  `owner_user_id` bigint(20) unsigned default NULL,
  `owner_group_id` int(11) unsigned default NULL,
  `is_active` char(1) NOT NULL default 'N',
  PRIMARY KEY  (`auth_user_id`,`handle`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `test_liveuser_users`
--

LOCK TABLES `test_liveuser_users` WRITE;
/*!40000 ALTER TABLE `test_liveuser_users` DISABLE KEYS */;
INSERT INTO `test_liveuser_users` VALUES ('29214857b12575501c5c731353c7217e','johndoe','6579e96f76baa00787a28653876c6127','2025-03-18 06:16:11',NULL,NULL,'Y'),('2swcmpex84arrcckfjd0iw9yf2ub9fen','ali','86318e52f5ed4801abe1d13d509443de','2025-03-18 06:17:56',NULL,NULL,'Y'),('ayfht61xipijjnbmpt6swb63su25h0i2','harry','3b87c97d15e8eb11e51aa25e9a5770e9','2025-03-18 06:18:07',NULL,NULL,'Y'),('d4lep4hd1ssrdenoki58rxky96cx8jxn','mloitzl','aac207e1fd10bcd1fccd94bf7f2b392a','2025-03-18 06:17:50',NULL,NULL,'Y'),('iiny2otwmid92y0njh8d7ohb7rsy0ll9','joe','8ff32489f92f33416694be8fdc2d4c22','2025-03-18 06:17:32',NULL,NULL,'Y');
/*!40000 ALTER TABLE `test_liveuser_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_liveuser_users_seq`
--

DROP TABLE IF EXISTS `test_liveuser_users_seq`;
CREATE TABLE `test_liveuser_users_seq` (
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `test_liveuser_users_seq`
--

LOCK TABLES `test_liveuser_users_seq` WRITE;
/*!40000 ALTER TABLE `test_liveuser_users_seq` DISABLE KEYS */;
INSERT INTO `test_liveuser_users_seq` VALUES (3);
/*!40000 ALTER TABLE `test_liveuser_users_seq` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_manager_evaluator_finding`
--

DROP TABLE IF EXISTS `test_manager_evaluator_finding`;
CREATE TABLE `test_manager_evaluator_finding` (
  `aID` varchar(32) NOT NULL default '',
  `mfId` varchar(32) NOT NULL default '',
  `efId` varchar(32) NOT NULL default '',
  `aDate` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`aID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `test_manager_evaluator_finding`
--

LOCK TABLES `test_manager_evaluator_finding` WRITE;
/*!40000 ALTER TABLE `test_manager_evaluator_finding` DISABLE KEYS */;
INSERT INTO `test_manager_evaluator_finding` VALUES ('46wgvna0u2qge3upsyjnd1xtdlk9uqtc','ix25lyesrj0d5gbr6do9f9sjqn87rhfx','vbt4m1ip4etaazsuo6f9wnekkmmise4d','2025-03-16 16:03:15'),('d9f23xx8fc19kpwjh77tzihy9e9x900k','uuw4wjax85l0ogqaudm5cff3ojynnnyp','wwsnp5mxq0yosobjwkvp251o9fw64yaa','2025-03-16 16:03:05'),('nn5epu09l432bv7262h4ak88hdv9hqxw','20n171xkeacafqec8a2hmrofmug5ubh9','3mmgf6pc067rtzua9i3gaqk94xmenei6','2025-03-16 16:03:51'),('pwxkx9a4ddy1437xzb8k6nya7mybhj4z','9eon2idt84ytdlappmdt6naoixo7zjug','1jqzpg2hytfbjei3nw2pg094e4mj49c0','2025-03-16 16:03:45'),('p8kgogn0xlvpj80axptwzfm4relbqzuw','7zsqv2caerqp1ex0sy40jn6dnmmi7zsf','58scson2j8w754hhme9z7jyls1m9gp04','2025-03-16 16:03:38'),('dtuv3mfmmlfmzr6j146x4um3idg3enmj','zrmhem77t2rokt3fax8muoz5w3pve37r','uof6uaautg96inx45deikk7399rjyexb','2025-03-16 16:03:30'),('ey64r45gf7kx2ugmrek8p4uy3s1j1l0g','tib78p6qoex1cgs2kx6e6e8n10pr0yrn','ox3pasydxmdz5o9lgxen4i7mqeproh73','2025-03-16 16:03:50'),('8nk2rhhlo0duzist7rmja06wb64plaq4','tib78p6qoex1cgs2kx6e6e8n10pr0yrn','v2foel4rwvm057b2lsewqrwm50hwgub5','2025-03-16 16:03:50'),('lmtks6hvk12th43rkjisqlgxw9kub4k1','r4acglsodt8zvnqe56skr5pdf2tdl9ae','2nioto97qxlf7ug9rfwge9ni18c9l6zt','2025-03-16 16:03:25'),('y6opi27zias9mmigt80v54fv0eh6vfnf','r4acglsodt8zvnqe56skr5pdf2tdl9ae','lnbxnl0pduudzgbgzbd06ce4fp6i0j2l','2025-03-16 16:03:25'),('hj5zm2wedj8nrmtrlyqtolo3obec5oio','r4acglsodt8zvnqe56skr5pdf2tdl9ae','kgb7lmyt0ru0ul4z1n2jvkl0h2y5wrs3','2025-03-16 16:03:25'),('tngw43s5ul8oy3xl2lq5mt1b2o6ffaq6','fvdmbucv6rew7p042mnr5jyy28dgb9nw','oyz1i6t58l0bl8olt0p10n2udjg15jjl','2025-03-16 16:03:13'),('bx67i9mremmndfuw40vp7c6wtw10ejdy','fvdmbucv6rew7p042mnr5jyy28dgb9nw','lscjt3g3c90ee62m3euab7h939v3dsbo','2025-03-16 16:03:13'),('lsx1h03rjpr30w84ne3jmsp3d5ck9dug','2miqxhu876l8u1jp3gqblzflv0g8i5rz','jpbuun5k9jmw8kc35ixmp4pdvknz897l','2025-03-16 16:03:05'),('je54qk9vxonda1ck61r3xkp1rr2hc8a9','xvhu40gfzx8e9feh7ec8kmsl9zmxisro','f527dnopn1996v30zd59u0vbkms6xwww','2005-09-29 19:09:40'),('frqwu4lqc89605atfb2m8knda1af06sa','ofazdwamcrbz97qaxwyqn56y5p143ap7','qa24re40hdx59u2lbfzqhyyif097kzjl','2025-03-16 16:03:50'),('hp8xfao0gswvfidssb0yzym1kl018opo','03bydn8c0kyv2gj458oa2yzah9tsd80z','fmj2t4quayq7pdir8esr23bumlcgnn7b','2025-03-16 16:03:42'),('3hofbsv1qjond70svvhdgukrx4qv9k0e','dto51f1gefyzekennavhinvww9cu112t','2s83rgito6t3brq9df6zi2fd2n8ps1ni','2025-03-16 16:03:34'),('ddarzqc8dchzjr1y93fncovkwnf2x85x','dto51f1gefyzekennavhinvww9cu112t','jd6702froqwezpb6grxnqr38j3rvl2ig','2025-03-16 16:03:34'),('6yq7hhgg0j4s6s7u1ouoolj8frfywtt3','dto51f1gefyzekennavhinvww9cu112t','opyntn92yrwzkn7nh6sd17f3bf5g1vi3','2025-03-16 16:03:34'),('jmrbeocjl9wtr76m074mngy68b0gchmi','6vatxwtjr9d5f6i0e9wy4heoqbtc6juo','1gm8rcais4dx8ab6apd6imqabec6c9c0','2025-03-16 16:03:24'),('4jvio3itzdg6hizq78rlfxzkli5nsgo2','hmf6pl12wv8lbhi2pjames6o5gthzirk','ujshdzkwu2a3hglzwi7s4126zxd745ve','2005-09-29 19:09:41'),('ogkh1c3qpsov2w0aoib3mijykcxr2z8z','hmf6pl12wv8lbhi2pjames6o5gthzirk','1wssia9fkk756wdyz6lmxlunoz22flwn','2005-09-29 19:09:41'),('zszm27kwwfhhdgkmbj2d0v0zwn8xd8et','xqgalxd0imyqlrdqjgpcwllxf01tcktw','6sf9iw71dl0f7tu0tj51kffixkj57wjp','2025-03-16 16:03:13'),('bez7rxzsf937u0vll0oyhq1hiy3bv8xm','xqgalxd0imyqlrdqjgpcwllxf01tcktw','n6aruaai06fiwv4fglmjjzfnlkni84pg','2025-03-16 16:03:13'),('50h7o2ls0cfokowjnv16ll0acpysytgd','2i9xvejw1k9qa6hx6na21l038x8z95ox','ibymzkf9cdy547g46jxwv1k6walcnq1n','2025-03-16 16:03:43'),('7sc1lwmi4850t2q8yikc5ve5dtniko1z','2i9xvejw1k9qa6hx6na21l038x8z95ox','r3y7gib8gsrh3rkxyki8b4ocqsx60y6b','2025-03-16 16:03:43'),('wbbhbqftc1qfi7gqoyqdxzb69lw3t33z','x2116j9rm76lw43mnty5gx7mixzikfn0','1dgp5pfufxlxgrsgfatnywl92vzty2wu','2025-03-16 16:03:25'),('jv1n5pw5qa2im0l97bajw3v9r6wfexod','x2116j9rm76lw43mnty5gx7mixzikfn0','l384fwl474xtu4tbw6tydldf5gcumo8g','2025-03-16 16:03:25'),('r32wp09o9k7jhlwto9d3t6qfayory4g1','x2116j9rm76lw43mnty5gx7mixzikfn0','kdlyamie51pfkajhc7xc29703u8zlxmm','2025-03-16 16:03:25'),('qth2rbvxlpuj6ktvs4xg8ept85s4p6ah','qfgf7rzlwk9tsd8na73yrxt0mdh37j2q','m3v49x9gpbrhogj315e5hb3bvjg6q6dg','2025-03-16 16:03:13'),('6otc130s2czfhbqwper573ebeaz9kyq6','1cbmswokquxqxx88kr8eopmu4hfzv6ok','81azkr02v0b9hq8abrz5w43nxyqpbalx','2025-03-16 16:03:43'),('xq41whoqawfhljmcz2wit99qdreudpn6','1cbmswokquxqxx88kr8eopmu4hfzv6ok','f0fgmidtzf24jh8atifkrgatcob2b3vm','2025-03-16 16:03:43'),('fj5rjjplahxpb70pqpkd6ufd8icf08bq','1cbmswokquxqxx88kr8eopmu4hfzv6ok','58nko3ujv2imb2af8glablc5bnzpb8zd','2025-03-16 16:03:43'),('5xa7waiws2knolcyzq1b9mfhye8ohhf8','1cbmswokquxqxx88kr8eopmu4hfzv6ok','cqqrsot2483dqg84d9u08w62ginjojpt','2025-03-16 16:03:43'),('6j7se7ahj8e7zxjogjxv6dihwleyol2i','ulp71df7hiwpmuycr0u7gkbvrdy19l48','ggpjlujlsr3sgfjegxa66h883zkouryq','2005-09-29 17:09:00'),('udc34kq07bauqu4h1rt9vh2mgwwlo57k','1fsfomhuz5mc5dcty54mkngtsqkhil99','q0id83wtr18bfv9v8o66g6kpipa16pqw','2025-03-16 16:03:30'),('smortcc9r399pw0vkugy3mnfaxtfulrf','b9cavrt3hsj0fs3soqr1k97v07ixq32e','o0mob64spyu1que4sn4l7nlhv2lcplks','2025-03-16 16:03:22'),('uumme82zeseqtolvwr1nt231pyjx2r5m','b9cavrt3hsj0fs3soqr1k97v07ixq32e','pae35111y4ymgfx4dmqsy0zpvuwid6bk','2025-03-16 16:03:22'),('wtt8lmm4qadwd1uzegchiyvy9alaeupj','b9cavrt3hsj0fs3soqr1k97v07ixq32e','vt54yyqhtunsu1somuiletu9p1b778c7','2025-03-16 16:03:22'),('npdh8rc9kc065gr2rzeva8u7z77b4eu3','32yffuqvtqnkq3f6ztlidp7en1nvo1nz','6h0frb0yj1kay4x4bl71cc6nmmlwa3on','2025-03-16 16:03:14'),('pe5zmks74m145vmzmpdjdsbzk2e3ovlj','32yffuqvtqnkq3f6ztlidp7en1nvo1nz','vy90b9f0xt4q6hguktqwwijgufof0a1z','2025-03-16 16:03:14'),('s0l31fmyn4h2hi6726rwtszjw1wa72v8','32yffuqvtqnkq3f6ztlidp7en1nvo1nz','t5qmjt2s4jj53b0pvfxllpexl8o7whhb','2025-03-16 16:03:14'),('5l8urndiiw5c01exvrv60yevrrii0m08','32yffuqvtqnkq3f6ztlidp7en1nvo1nz','8rfx9qqus2vzwzupk0yhinfy9dochn66','2025-03-16 16:03:14'),('316qmq1vbyvqjjwqsq4rn526mzbwieie','ccqhqsanh4kxiqgc8t7yjzkbnb6wadj6','08mx18vurelv8w3b38viyh5gmmyghmd9','2025-03-16 16:03:06'),('ywbtorrgikxa8rcztiml0levw9f4w3n2','gv59nkpkb9i64aigf7fpqbv4dvsuh9kw','e2ipuip73zixefk8ilaze4pvazdz9y21','2025-03-16 16:03:51'),('oe2xmz6y6506xfh4zxtk0iur2kivfxgx','gv59nkpkb9i64aigf7fpqbv4dvsuh9kw','f31qolktovn0avp4nczxvau04e1j4y9v','2025-03-16 16:03:51'),('tck508oetajbfqrabpm181rq9xruebk1','elqqcyetppfje2u1dxqzuwfzo54o4qhd','xk2ieu55zs8lwo8eu2z9lbi1wikb3hpk','2025-03-16 15:03:45'),('o7fd8033lvuwxvdlks29mt16hjgva1nc','zhpsxq5tv0mvdhkqac20k81rrq649145','cvqodjbjjr9gdk3vuqtfpu4z3wf3ou45','2025-03-16 15:03:21'),('jcvavunuq816668368gledyxiv4nmnqf','zhpsxq5tv0mvdhkqac20k81rrq649145','hkblhdik5fdz67zqgaffgxw2igtob0kj','2025-03-16 15:03:21'),('nw7aswt4uycwwi7d1cd494qjddey93ra','4dgsy4v2qlgqoi7huwcgwzziqe77r7bc','0vde40wh1g0jaxsl57e0mm5c123rq8k4','2025-03-16 15:03:09'),('2fc09gh3igdzsq2abxeo3i8yurn5l0ef','zvs4td921b3b1e1g12rz5sqk5yoh9r53','ahmuqrgm0ujxpvrlak545585er6m6pd9','2025-03-16 15:03:32'),('7zdqieii0nxb479j4vb0rx0rr9a9h467','zvs4td921b3b1e1g12rz5sqk5yoh9r53','bp5awivmtv7iao04ml3eu40a7qspzcsy','2025-03-16 15:03:32');
/*!40000 ALTER TABLE `test_manager_evaluator_finding` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_project`
--

DROP TABLE IF EXISTS `test_project`;
CREATE TABLE `test_project` (
  `pId` varchar(32) NOT NULL default '',
  `pNameId` varchar(32) NOT NULL default '',
  `pDescriptionId` varchar(32) NOT NULL default '',
  `pPhase` char(1) NOT NULL default '',
  `heurSetId` varchar(32) NOT NULL default '',
  `envId` varchar(32) NOT NULL default '',
  `schemeId` varchar(32) NOT NULL default '',
  `pAdded` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`pId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `test_project`
--

LOCK TABLES `test_project` WRITE;
/*!40000 ALTER TABLE `test_project` DISABLE KEYS */;
INSERT INTO `test_project` VALUES ('3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','i9su0t74wto2amg8p7dsxfrr6btdnpcv','7465vb0xuniyaxf3a2rwwean82lelqtd','4','x0v3wq83jcq8ub2baervqsxn8csup2i2','krhlwkcb40dvhhsaghu1u8azgig8ouct','9inwqh48640qim60l2c67bz1lgscmh1z','2025-03-16 16:11:45');
/*!40000 ALTER TABLE `test_project` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_project_user`
--

DROP TABLE IF EXISTS `test_project_user`;
CREATE TABLE `test_project_user` (
  `pId` varchar(32) NOT NULL default '',
  `uId` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`pId`,`uId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `test_project_user`
--

LOCK TABLES `test_project_user` WRITE;
/*!40000 ALTER TABLE `test_project_user` DISABLE KEYS */;
INSERT INTO `test_project_user` VALUES ('3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','2swcmpex84arrcckfjd0iw9yf2ub9fen'),('3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','ayfht61xipijjnbmpt6swb63su25h0i2'),('3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','d4lep4hd1ssrdenoki58rxky96cx8jxn'),('3evzbbxe8jdhb9x2t1z246y2gmsk4ok0','iiny2otwmid92y0njh8d7ohb7rsy0ll9');
/*!40000 ALTER TABLE `test_project_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_rating_scale`
--

DROP TABLE IF EXISTS `test_rating_scale`;
CREATE TABLE `test_rating_scale` (
  `scaleId` varchar(32) NOT NULL default '',
  `scaleTitleId` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`scaleId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `test_rating_scale`
--

LOCK TABLES `test_rating_scale` WRITE;
/*!40000 ALTER TABLE `test_rating_scale` DISABLE KEYS */;
INSERT INTO `test_rating_scale` VALUES ('1','10');
/*!40000 ALTER TABLE `test_rating_scale` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_rating_scale_value`
--

DROP TABLE IF EXISTS `test_rating_scale_value`;
CREATE TABLE `test_rating_scale_value` (
  `scaleValueId` varchar(32) NOT NULL default '',
  `scaleValue` int(10) NOT NULL default '0',
  `scaleValueCaptionId` varchar(32) NOT NULL default '',
  `scaleId` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`scaleValueId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `test_rating_scale_value`
--

LOCK TABLES `test_rating_scale_value` WRITE;
/*!40000 ALTER TABLE `test_rating_scale_value` DISABLE KEYS */;
INSERT INTO `test_rating_scale_value` VALUES ('uzmhm5fncydx5pa65fa11d6ttozlj0mu',4,'yg1etueb0otsoxevic7ca6yaw03ikmfp','1'),('kbzp80b2xnpe0rkan8v8n3pc391gsflg',3,'xqljf8q070jo9pdgq0adcynifccqnvk0','1'),('c9z9aum8nkafq74nfusv93ef3rwog6k4',2,'zqb7bjl82k1ha0ekscr3kpivsmj83v4i','1'),('112',1,'12','1'),('j1qiay8xep4m7uzeod1792z419j63rn2',0,'5jr6y95jbakr4rke70djkotjmtfru0bc','1');
/*!40000 ALTER TABLE `test_rating_scale_value` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_ratingscheme`
--

DROP TABLE IF EXISTS `test_ratingscheme`;
CREATE TABLE `test_ratingscheme` (
  `schemeId` varchar(32) NOT NULL default '',
  `schemeTitleId` varchar(32) NOT NULL default '',
  `schemeResultOperation` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`schemeId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `test_ratingscheme`
--

LOCK TABLES `test_ratingscheme` WRITE;
/*!40000 ALTER TABLE `test_ratingscheme` DISABLE KEYS */;
INSERT INTO `test_ratingscheme` VALUES ('9inwqh48640qim60l2c67bz1lgscmh1z','cmupsnpds9y8fujqodgkexwnc2n4eq33','sum');
/*!40000 ALTER TABLE `test_ratingscheme` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_ratingscheme_scale`
--

DROP TABLE IF EXISTS `test_ratingscheme_scale`;
CREATE TABLE `test_ratingscheme_scale` (
  `schemeId` varchar(32) NOT NULL default '',
  `scaleId` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`schemeId`,`scaleId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `test_ratingscheme_scale`
--

LOCK TABLES `test_ratingscheme_scale` WRITE;
/*!40000 ALTER TABLE `test_ratingscheme_scale` DISABLE KEYS */;
INSERT INTO `test_ratingscheme_scale` VALUES ('9inwqh48640qim60l2c67bz1lgscmh1z','1');
/*!40000 ALTER TABLE `test_ratingscheme_scale` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_report_element`
--

DROP TABLE IF EXISTS `test_report_element`;
CREATE TABLE `test_report_element` (
  `elementId` varchar(32) NOT NULL default '',
  `elementType` varchar(100) NOT NULL default '',
  `elementOrder` int(11) NOT NULL default '0',
  `elementData` text NOT NULL,
  `chId` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`elementId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `test_report_element`
--

LOCK TABLES `test_report_element` WRITE;
/*!40000 ALTER TABLE `test_report_element` DISABLE KEYS */;
/*!40000 ALTER TABLE `test_report_element` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_screenshot`
--

DROP TABLE IF EXISTS `test_screenshot`;
CREATE TABLE `test_screenshot` (
  `sId` varchar(32) NOT NULL default '',
  `sFileName` varchar(255) NOT NULL default '',
  `sName` varchar(255) NOT NULL default '',
  `sMimeType` varchar(40) NOT NULL default '',
  `sFileSize` int(11) NOT NULL default '0',
  `sKind` varchar(100) NOT NULL default '',
  `fId` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`sId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `test_screenshot`
--

LOCK TABLES `test_screenshot` WRITE;
/*!40000 ALTER TABLE `test_screenshot` DISABLE KEYS */;
INSERT INTO `test_screenshot` VALUES ('8s4chjmrenxd2sh5d36g6ey79xzw5qln','8s4chjmrenxd2sh5d36g6ey79xzw5qln.png','wetter_all.png','image/png',69590,'fullsize','f527dnopn1996v30zd59u0vbkms6xwww'),('p91np25j7z5hvxvrwggy02lnydltaoz5','p91np25j7z5hvxvrwggy02lnydltaoz5.png','wetter.png','image/png',14921,'annotated','f527dnopn1996v30zd59u0vbkms6xwww'),('yyv0kvgjn6kbntkmmalmyywcuon4ptxu','yyv0kvgjn6kbntkmmalmyywcuon4ptxu.png','AktuelleThemen_all.png','image/png',45562,'fullsize','jpbuun5k9jmw8kc35ixmp4pdvknz897l'),('7gojj86703rylh72i464wz9aw8c2wfai','7gojj86703rylh72i464wz9aw8c2wfai.png','AktuelleThemen.png','image/png',6998,'annotated','jpbuun5k9jmw8kc35ixmp4pdvknz897l'),('jm9vmh97gef7zv1zkrniq72q7l07kbbb','jm9vmh97gef7zv1zkrniq72q7l07kbbb.png','Englisch_all.png','image/png',163585,'fullsize','8rfx9qqus2vzwzupk0yhinfy9dochn66'),('88vx6c1rqcugxmgqpl60f3cusdpwmg7l','88vx6c1rqcugxmgqpl60f3cusdpwmg7l.png','Englisch.png','image/png',11463,'annotated','8rfx9qqus2vzwzupk0yhinfy9dochn66'),('ih4dw7bx142sl8l2bwmq0t8omyxlsezh','ih4dw7bx142sl8l2bwmq0t8omyxlsezh.png','Suche_all.png','image/png',48391,'fullsize','oyz1i6t58l0bl8olt0p10n2udjg15jjl'),('ugdv0ogmaqryav39zqcqbj52e479rjkz','ugdv0ogmaqryav39zqcqbj52e479rjkz.png','Suche.png','image/png',16881,'annotated','oyz1i6t58l0bl8olt0p10n2udjg15jjl'),('17i6fdiynus559f3unoxmcf2it6ixod8','17i6fdiynus559f3unoxmcf2it6ixod8.png','SucheStern_all.png','image/png',17395,'fullsize','uof6uaautg96inx45deikk7399rjyexb'),('ez85urpwok0d2aqzppf5uxb35pcbb4w7','ez85urpwok0d2aqzppf5uxb35pcbb4w7.png','SucheStern.png','image/png',5391,'annotated','uof6uaautg96inx45deikk7399rjyexb'),('uweeydr199jrmlndpqdqqk4hksjv1yft','uweeydr199jrmlndpqdqqk4hksjv1yft.png','SucheErgebnis_all.png','image/png',23258,'fullsize','ox3pasydxmdz5o9lgxen4i7mqeproh73'),('0l34m14n96j73lnkqwyb8hlwjmgdliz2','0l34m14n96j73lnkqwyb8hlwjmgdliz2.png','SucheErgebnis.png','image/png',8722,'annotated','ox3pasydxmdz5o9lgxen4i7mqeproh73'),('i5bwv6vqhnjrdoy473hub5xwqyoxetoq','i5bwv6vqhnjrdoy473hub5xwqyoxetoq.png','Ueberschriften_all.png','image/png',137024,'fullsize','kgb7lmyt0ru0ul4z1n2jvkl0h2y5wrs3'),('itbpf8th1kdj9p838d6sqyke4y7ftlro','itbpf8th1kdj9p838d6sqyke4y7ftlro.png','Ueberschriften.png','image/png',15237,'annotated','kgb7lmyt0ru0ul4z1n2jvkl0h2y5wrs3'),('fen4jv9dwcwy4swj2ry1tgmbmoabyv7j','fen4jv9dwcwy4swj2ry1tgmbmoabyv7j.png','Services1_all.png','image/png',129473,'fullsize','lnbxnl0pduudzgbgzbd06ce4fp6i0j2l'),('rpn8s62c22aenu6h7707pne0ol49qh7j','rpn8s62c22aenu6h7707pne0ol49qh7j.png','Services1.png','image/png',18938,'annotated','lnbxnl0pduudzgbgzbd06ce4fp6i0j2l'),('b92x8k0e9gbqc5uu92g79kdygx7a9lc7','b92x8k0e9gbqc5uu92g79kdygx7a9lc7.png','Services2_all.png','image/png',24791,'fullsize','58scson2j8w754hhme9z7jyls1m9gp04'),('6cwa9cvsfcnf8iubmvea3rpf47p1n9sz','6cwa9cvsfcnf8iubmvea3rpf47p1n9sz.png','Services2.png','image/png',11683,'annotated','58scson2j8w754hhme9z7jyls1m9gp04'),('drfsrtnukiwaunlu8b0h1yadisyg8j1v','drfsrtnukiwaunlu8b0h1yadisyg8j1v.png','Position_all.png','image/png',188680,'fullsize','hkblhdik5fdz67zqgaffgxw2igtob0kj'),('vjpgmtqcflmnre4q2vbjkuw60i2t0wph','vjpgmtqcflmnre4q2vbjkuw60i2t0wph.png','Position.png','image/png',17560,'annotated','hkblhdik5fdz67zqgaffgxw2igtob0kj'),('bflbh4vc8m3eazfxziei7i4t3orjdxjz','bflbh4vc8m3eazfxziei7i4t3orjdxjz.png','Links_all.png','image/png',164796,'fullsize','2nioto97qxlf7ug9rfwge9ni18c9l6zt'),('xwvkkm221chrlgnaezt4gbd9pwndowlt','xwvkkm221chrlgnaezt4gbd9pwndowlt.png','Links.png','image/png',7580,'annotated','2nioto97qxlf7ug9rfwge9ni18c9l6zt'),('d1fdv02h0hckgiwa0xko8iqcof6c91ut','d1fdv02h0hckgiwa0xko8iqcof6c91ut.png','Ueberladen_all.png','image/png',154525,'fullsize','1wssia9fkk756wdyz6lmxlunoz22flwn'),('c7sw9cz8pl32dcyvvuevbezhmmynu3zm','c7sw9cz8pl32dcyvvuevbezhmmynu3zm.png','Ueberladen.png','image/png',13415,'annotated','1wssia9fkk756wdyz6lmxlunoz22flwn'),('m6ntyo7jv0eg6vpho9a37trq7cks11sl','m6ntyo7jv0eg6vpho9a37trq7cks11sl.png','Organigramm_all.png','image/png',389550,'fullsize','opyntn92yrwzkn7nh6sd17f3bf5g1vi3'),('xesmskb7dkge574jai361aa72vccc3hi','xesmskb7dkge574jai361aa72vccc3hi.png','Organigramm.png','image/png',25838,'annotated','opyntn92yrwzkn7nh6sd17f3bf5g1vi3'),('25er0idw3h9oacjtpc4zeptkebio049g','25er0idw3h9oacjtpc4zeptkebio049g.png','SucheNeu_all.png','image/png',18852,'fullsize','1dgp5pfufxlxgrsgfatnywl92vzty2wu'),('gedr3ag1sln0nikvcjazkwgovgohyxwm','gedr3ag1sln0nikvcjazkwgovgohyxwm.png','SucheNeu.png','image/png',9674,'annotated','1dgp5pfufxlxgrsgfatnywl92vzty2wu'),('eu5zs2fr23iu3gq2xc2cr6oifb5pgihx','eu5zs2fr23iu3gq2xc2cr6oifb5pgihx.png','externe inhalte.png','image/png',178036,'fullsize','1jqzpg2hytfbjei3nw2pg094e4mj49c0'),('timhfgfpxl3e1ydh84zmw0jmd3lpfacx','timhfgfpxl3e1ydh84zmw0jmd3lpfacx.png','externe inhalte.png','image/png',178036,'annotated','1jqzpg2hytfbjei3nw2pg094e4mj49c0'),('qw9h7pwvknblv1jrrqs4gcuvdryd2o66','qw9h7pwvknblv1jrrqs4gcuvdryd2o66.png','pos-loc-feedback-full.png','image/png',296232,'fullsize','cvqodjbjjr9gdk3vuqtfpu4z3wf3ou45'),('e32xyn6b5jymy9pdo31lc2bsmn8qyaxq','e32xyn6b5jymy9pdo31lc2bsmn8qyaxq.png','pos-loc-feedback-ann.png','image/png',302691,'annotated','cvqodjbjjr9gdk3vuqtfpu4z3wf3ou45'),('eql66bn5y9gbn2ivc9myl73t02c2t0dy','eql66bn5y9gbn2ivc9myl73t02c2t0dy.png','kontakt-noform.png','image/png',48489,'fullsize','v2foel4rwvm057b2lsewqrwm50hwgub5'),('ke76f6tj44cuu3buyibjinyspelnf4mh','ke76f6tj44cuu3buyibjinyspelnf4mh.png','kontakt-noform.png','image/png',48489,'annotated','v2foel4rwvm057b2lsewqrwm50hwgub5'),('mhx21c59s1q32pcxqt2zafo0mg5a1ild','mhx21c59s1q32pcxqt2zafo0mg5a1ild.png','no-valid-html.png','image/png',213519,'fullsize','3mmgf6pc067rtzua9i3gaqk94xmenei6'),('qimuf9sun7giu98erxu0ugo231lprhhl','qimuf9sun7giu98erxu0ugo231lprhhl.png','no-valid-html.png','image/png',213519,'annotated','3mmgf6pc067rtzua9i3gaqk94xmenei6'),('nj7mffbvh2r0doa9kbke01b4l4ldd0bj','nj7mffbvh2r0doa9kbke01b4l4ldd0bj.png','title-tag-full.png','image/png',279786,'fullsize','wwsnp5mxq0yosobjwkvp251o9fw64yaa'),('tednq2nmlk8lr4hl2uwbja6eknrqn9nz','tednq2nmlk8lr4hl2uwbja6eknrqn9nz.png','title-tag-ann.png','image/png',72110,'annotated','wwsnp5mxq0yosobjwkvp251o9fw64yaa'),('sxo44g190i0nadkb0lc5zvii63516pl7','sxo44g190i0nadkb0lc5zvii63516pl7.png','frames-full.png','image/png',232939,'fullsize','vbt4m1ip4etaazsuo6f9wnekkmmise4d'),('1s8v5q4khfn2ckhvtblwuoxq9p9a8fs6','1s8v5q4khfn2ckhvtblwuoxq9p9a8fs6.png','frames-ann.png','image/png',244466,'annotated','vbt4m1ip4etaazsuo6f9wnekkmmise4d'),('1l5kwxpy8m0wu0trinmdfbjdsdewth1d','1l5kwxpy8m0wu0trinmdfbjdsdewth1d.png','suche.png','image/png',183079,'fullsize','lscjt3g3c90ee62m3euab7h939v3dsbo'),('tn7sgbzoyhjzwo8g6sbi3w10ttmnhd0l','tn7sgbzoyhjzwo8g6sbi3w10ttmnhd0l.png','stadtplan-design.png','image/png',224870,'fullsize','jd6702froqwezpb6grxnqr38j3rvl2ig'),('mmhsl76n30i2w0yabzl54uav41ldt8va','mmhsl76n30i2w0yabzl54uav41ldt8va.png','falsche-suche.png','image/png',70759,'fullsize','l384fwl474xtu4tbw6tydldf5gcumo8g'),('m8iw2invsdw4i8wfnwmnf8ekns0i08nk','m8iw2invsdw4i8wfnwmnf8ekns0i08nk.png','original.png','image/png',207520,'fullsize','6h0frb0yj1kay4x4bl71cc6nmmlwa3on'),('wrr5xbs9c4tq7d3kj9i15nb0l2etkuo0','wrr5xbs9c4tq7d3kj9i15nb0l2etkuo0.png','marked.png','image/png',320983,'annotated','6h0frb0yj1kay4x4bl71cc6nmmlwa3on'),('aul2g1tlwi1fsbj92t5e9gcix34lk9hp','aul2g1tlwi1fsbj92t5e9gcix34lk9hp.png','original.png','image/png',207759,'fullsize','1gm8rcais4dx8ab6apd6imqabec6c9c0'),('62ht3lss3iqoyl173odf2leczg5u4b4j','62ht3lss3iqoyl173odf2leczg5u4b4j.png','marked.png','image/png',319645,'annotated','1gm8rcais4dx8ab6apd6imqabec6c9c0'),('jk8uyx1xgt6gsw21jfqylzp9sq2diolt','jk8uyx1xgt6gsw21jfqylzp9sq2diolt.png','original.png','image/png',92076,'fullsize','2s83rgito6t3brq9df6zi2fd2n8ps1ni'),('l1zu3hj7ptxhi6md652ftx17ewbtivd2','l1zu3hj7ptxhi6md652ftx17ewbtivd2.png','marked.png','image/png',118643,'annotated','2s83rgito6t3brq9df6zi2fd2n8ps1ni'),('gdmktkhkvp4pc9dsoo32nqjr1cln58bt','gdmktkhkvp4pc9dsoo32nqjr1cln58bt.png','original.png','image/png',92076,'fullsize','n6aruaai06fiwv4fglmjjzfnlkni84pg'),('owv1i4b4tt8xrphl63u6mk0nrdx50fw9','owv1i4b4tt8xrphl63u6mk0nrdx50fw9.png','marked.png','image/png',118659,'annotated','n6aruaai06fiwv4fglmjjzfnlkni84pg'),('woz4erzvpoyn0v0h2h62jqq6ktq4y2il','woz4erzvpoyn0v0h2h62jqq6ktq4y2il.png','original.png','image/png',100750,'fullsize','fmj2t4quayq7pdir8esr23bumlcgnn7b'),('4om9v6ltk7fwu209rd5trtc7kbagol46','4om9v6ltk7fwu209rd5trtc7kbagol46.png','marked.png','image/png',137217,'annotated','fmj2t4quayq7pdir8esr23bumlcgnn7b'),('benybz3nyapqmqcb6w8z58s6kbog5eoc','benybz3nyapqmqcb6w8z58s6kbog5eoc.png','original.png','image/png',83071,'fullsize','qa24re40hdx59u2lbfzqhyyif097kzjl'),('5ztqkdntyfi4y9zsa6t6mfc659hdypkn','5ztqkdntyfi4y9zsa6t6mfc659hdypkn.png','marked.png','image/png',129402,'annotated','qa24re40hdx59u2lbfzqhyyif097kzjl'),('tn6wg1x1121x8zrcrrtwud4o9wiho4m0','tn6wg1x1121x8zrcrrtwud4o9wiho4m0.png','original.png','image/png',106790,'fullsize','0vde40wh1g0jaxsl57e0mm5c123rq8k4'),('qoidwi5c5t0xyqswt6tobx1zayu6cpj8','qoidwi5c5t0xyqswt6tobx1zayu6cpj8.png','marked.png','image/png',143376,'annotated','0vde40wh1g0jaxsl57e0mm5c123rq8k4'),('ydjgy1ub4tj2z5bikjlsmhymt95vyyap','ydjgy1ub4tj2z5bikjlsmhymt95vyyap.png','Original.png','image/png',75057,'fullsize','08mx18vurelv8w3b38viyh5gmmyghmd9'),('5yuwr44y2y43mrbqn6xgnflowchy589j','5yuwr44y2y43mrbqn6xgnflowchy589j.png','marked.png','image/png',111183,'annotated','08mx18vurelv8w3b38viyh5gmmyghmd9'),('kydfqmmohd07477yv3op66xl2p45obpl','kydfqmmohd07477yv3op66xl2p45obpl.png','original.png','image/png',43944,'fullsize','kdlyamie51pfkajhc7xc29703u8zlxmm'),('8ny3fno0gakp4cd7sw79a42rd3a2y7cv','8ny3fno0gakp4cd7sw79a42rd3a2y7cv.png','original.png','image/png',43944,'annotated','kdlyamie51pfkajhc7xc29703u8zlxmm'),('8sfj5nryc32f5fh2nh4p5er5mml75o8s','8sfj5nryc32f5fh2nh4p5er5mml75o8s.png','u_schreenshot_1.png','image/png',217038,'fullsize','t5qmjt2s4jj53b0pvfxllpexl8o7whhb'),('gx9sce2hfr1zvnpuwbh9g756ho5w4hcf','gx9sce2hfr1zvnpuwbh9g756ho5w4hcf.png','m_schreenshot_1.png','image/png',215867,'annotated','t5qmjt2s4jj53b0pvfxllpexl8o7whhb'),('h8v33k6m8s18w5i53mt3exs9kns35ihs','h8v33k6m8s18w5i53mt3exs9kns35ihs.png','u_schreenshot_2.png','image/png',121470,'fullsize','vt54yyqhtunsu1somuiletu9p1b778c7'),('ugvh02axxmlhltpuwurjwtulywvspk8y','ugvh02axxmlhltpuwurjwtulywvspk8y.png','m_schreenshot_2.png','image/png',106178,'annotated','vt54yyqhtunsu1somuiletu9p1b778c7'),('u7x3ca4q1ueg7yg7v4tt91shrgn7otud','u7x3ca4q1ueg7yg7v4tt91shrgn7otud.png','m_schreenshot_2.png','image/png',106178,'fullsize','pae35111y4ymgfx4dmqsy0zpvuwid6bk'),('feucf019vtbci28p8jobjiu7m4nukr9h','feucf019vtbci28p8jobjiu7m4nukr9h.png','m_schreenshot_3.png','image/png',106336,'annotated','pae35111y4ymgfx4dmqsy0zpvuwid6bk'),('ccssil1fni15uowaz8pc3d8d2b9pnazy','ccssil1fni15uowaz8pc3d8d2b9pnazy.png','u_schreenshot_4.png','image/png',88290,'fullsize','ahmuqrgm0ujxpvrlak545585er6m6pd9'),('cadrhkm3h79o8ds9w3yh0byh00sv0hcv','cadrhkm3h79o8ds9w3yh0byh00sv0hcv.png','u_schreenshot_5.png','image/png',19605,'fullsize','vy90b9f0xt4q6hguktqwwijgufof0a1z'),('b1czqjc1sa5jvj4ocj94rxcmj80ojkyd','b1czqjc1sa5jvj4ocj94rxcmj80ojkyd.png','m_schreenshot_5.png','image/png',19697,'annotated','vy90b9f0xt4q6hguktqwwijgufof0a1z'),('8gnu607pneeuhruzkkxmhaf3tu1lxlik','8gnu607pneeuhruzkkxmhaf3tu1lxlik.png','Original.png','image/png',231621,'fullsize','f0fgmidtzf24jh8atifkrgatcob2b3vm'),('1riyeizv7yjsdfsq1j3d3mfzwnseei91','1riyeizv7yjsdfsq1j3d3mfzwnseei91.png','marked.png','image/png',309483,'annotated','f0fgmidtzf24jh8atifkrgatcob2b3vm'),('o6l10ewhpeypuz4qr81vvhl8b7hie7fb','o6l10ewhpeypuz4qr81vvhl8b7hie7fb.png','u_schreenshot_6.png','image/png',96992,'fullsize','q0id83wtr18bfv9v8o66g6kpipa16pqw'),('kqk0qyy0v1k56t9f73kdbioc2y16c4gn','kqk0qyy0v1k56t9f73kdbioc2y16c4gn.png','original.png','image/png',207968,'fullsize','cqqrsot2483dqg84d9u08w62ginjojpt'),('recuwnd6oxyutdh9y2ggm3k8a9nkig19','recuwnd6oxyutdh9y2ggm3k8a9nkig19.png','marked.png','image/png',321623,'annotated','cqqrsot2483dqg84d9u08w62ginjojpt'),('2gjyeje2dtfvvqahvka81bptbk0m94u8','2gjyeje2dtfvvqahvka81bptbk0m94u8.png','u_schreenshot_7.png','image/png',239070,'fullsize','58nko3ujv2imb2af8glablc5bnzpb8zd'),('86xxdlx6o7lgwtlbg4qra2hn442ywvda','86xxdlx6o7lgwtlbg4qra2hn442ywvda.png','m_schreenshot_7.png','image/png',240462,'annotated','58nko3ujv2imb2af8glablc5bnzpb8zd'),('alq2en6cr0gzyt4mq1lp0g0actzytp5u','alq2en6cr0gzyt4mq1lp0g0actzytp5u.png','original.png','image/png',180750,'fullsize','o0mob64spyu1que4sn4l7nlhv2lcplks'),('lz85pxosiyjwq9tiv46lr9agzlkqmjj1','lz85pxosiyjwq9tiv46lr9agzlkqmjj1.png','marked.png','image/png',255572,'annotated','o0mob64spyu1que4sn4l7nlhv2lcplks'),('0nde2ajo4kiom9c1f44mli8ko8nd4wg2','0nde2ajo4kiom9c1f44mli8ko8nd4wg2.png','u_schreenshot_8.png','image/png',47754,'fullsize','m3v49x9gpbrhogj315e5hb3bvjg6q6dg'),('vz9zuqj2t8dfkevddpada4xtylrhhjl1','vz9zuqj2t8dfkevddpada4xtylrhhjl1.png','original.png','image/png',210705,'fullsize','f31qolktovn0avp4nczxvau04e1j4y9v'),('2qs2d7ml736ewxrj7gosi81s9357zwms','2qs2d7ml736ewxrj7gosi81s9357zwms.png','marked.png','image/png',322391,'annotated','f31qolktovn0avp4nczxvau04e1j4y9v'),('0zq1t1j82a72jo46pe1jfomu5rpeqaq1','0zq1t1j82a72jo46pe1jfomu5rpeqaq1.png','u_schreenshot_9.png','image/png',111294,'fullsize','r3y7gib8gsrh3rkxyki8b4ocqsx60y6b'),('dfobrgnjmofczoeq5sclein6bzncq7ho','dfobrgnjmofczoeq5sclein6bzncq7ho.png','m_schreenshot_9.png','image/png',238948,'annotated','r3y7gib8gsrh3rkxyki8b4ocqsx60y6b'),('bccw05j7v86l37jvswexj413aq93z22l','bccw05j7v86l37jvswexj413aq93z22l.png','u_schreenshot_10.png','image/png',243735,'fullsize','ibymzkf9cdy547g46jxwv1k6walcnq1n'),('ciwrge7kjn6x9wu6ow3s4na2nmfq5lft','ciwrge7kjn6x9wu6ow3s4na2nmfq5lft.png','u_schreenshot_11.png','image/png',243729,'fullsize','6sf9iw71dl0f7tu0tj51kffixkj57wjp'),('z9kpje564sxp0htakhup48amr9g8e3b4','z9kpje564sxp0htakhup48amr9g8e3b4.png','u_schreenshot_12.png','image/png',168965,'fullsize','ujshdzkwu2a3hglzwi7s4126zxd745ve'),('fltmfvhgwhnl6vvyegy0biykwil4y6nd','ccssil1fni15uowaz8pc3d8d2b9pnazy.png','u_schreenshot_4.png','image/png',88290,'fullsize','zvs4td921b3b1e1g12rz5sqk5yoh9r53'),('u6eze87vbg38y3io8cgqdta3nkdxqbrw','drfsrtnukiwaunlu8b0h1yadisyg8j1v.png','Position_all.png','image/png',188680,'fullsize','zhpsxq5tv0mvdhkqac20k81rrq649145'),('ct1kk03anvkm7c6rpmj5i9051sbsp16j','vjpgmtqcflmnre4q2vbjkuw60i2t0wph.png','Position.png','image/png',17560,'annotated','zhpsxq5tv0mvdhkqac20k81rrq649145'),('dsy6fxi7y0h4inf08fak7z8dn7tsfdi2','vz9zuqj2t8dfkevddpada4xtylrhhjl1.png','original.png','image/png',210705,'fullsize','gv59nkpkb9i64aigf7fpqbv4dvsuh9kw'),('tljiye17rb3auxrl9eannwf2clejpl5g','2qs2d7ml736ewxrj7gosi81s9357zwms.png','marked.png','image/png',322391,'annotated','gv59nkpkb9i64aigf7fpqbv4dvsuh9kw'),('lklk7w80j771ty1ss5prvjxuyyd0ttq2','u7x3ca4q1ueg7yg7v4tt91shrgn7otud.png','m_schreenshot_2.png','image/png',106178,'fullsize','b9cavrt3hsj0fs3soqr1k97v07ixq32e'),('2b8r6ri63lrbhk13xgcw6e3y58j5l8mc','feucf019vtbci28p8jobjiu7m4nukr9h.png','m_schreenshot_3.png','image/png',106336,'annotated','b9cavrt3hsj0fs3soqr1k97v07ixq32e'),('e6zaph8oyezoodgna1mmpnaprhn7d8s2','0nde2ajo4kiom9c1f44mli8ko8nd4wg2.png','u_schreenshot_8.png','image/png',47754,'fullsize','qfgf7rzlwk9tsd8na73yrxt0mdh37j2q'),('y2pspuzas7kql9l468f1d4qzedv6bqa9','benybz3nyapqmqcb6w8z58s6kbog5eoc.png','original.png','image/png',83071,'fullsize','ofazdwamcrbz97qaxwyqn56y5p143ap7'),('rzf76gkuhh2wiun90e9q75samejxj7zl','5ztqkdntyfi4y9zsa6t6mfc659hdypkn.png','marked.png','image/png',129402,'annotated','ofazdwamcrbz97qaxwyqn56y5p143ap7'),('x73n4o2d43uevuxehtklkz4blzx1fxhz','yyv0kvgjn6kbntkmmalmyywcuon4ptxu.png','AktuelleThemen_all.png','image/png',45562,'fullsize','2miqxhu876l8u1jp3gqblzflv0g8i5rz'),('29d55ktv3pzyfixyoifmtbs62ew4i9rn','7gojj86703rylh72i464wz9aw8c2wfai.png','AktuelleThemen.png','image/png',6998,'annotated','2miqxhu876l8u1jp3gqblzflv0g8i5rz'),('85574dyeuuj7vtxqcm6t78h4wqnf4h3k','1l5kwxpy8m0wu0trinmdfbjdsdewth1d.png','suche.png','image/png',183079,'fullsize','fvdmbucv6rew7p042mnr5jyy28dgb9nw'),('ug59iwv3d99kqrjczbxo7k8im9r03iwk','ugdv0ogmaqryav39zqcqbj52e479rjkz.png','Suche.png','image/png',16881,'annotated','fvdmbucv6rew7p042mnr5jyy28dgb9nw'),('zfkiwqjoj1e7u27fjj3qttzzqnex6lvl','uweeydr199jrmlndpqdqqk4hksjv1yft.png','SucheErgebnis_all.png','image/png',23258,'fullsize','tib78p6qoex1cgs2kx6e6e8n10pr0yrn'),('32rcebpgld555ndv93ohaq8lhvj0eulm','0l34m14n96j73lnkqwyb8hlwjmgdliz2.png','SucheErgebnis.png','image/png',8722,'annotated','tib78p6qoex1cgs2kx6e6e8n10pr0yrn'),('4faepkjuwowhm1ou6qe0hw579uckky8k','17i6fdiynus559f3unoxmcf2it6ixod8.png','SucheStern_all.png','image/png',17395,'fullsize','zrmhem77t2rokt3fax8muoz5w3pve37r'),('hicgmc3g7e76vfhjyextkgrul1qbr2t3','ez85urpwok0d2aqzppf5uxb35pcbb4w7.png','SucheStern.png','image/png',5391,'annotated','zrmhem77t2rokt3fax8muoz5w3pve37r'),('0yut9vykm7juqfo72ouhsk82kse9ng2l','nj7mffbvh2r0doa9kbke01b4l4ldd0bj.png','title-tag-full.png','image/png',279786,'fullsize','uuw4wjax85l0ogqaudm5cff3ojynnnyp'),('rh61p4sl76jg9rrsjigp37ks0d020g8i','tednq2nmlk8lr4hl2uwbja6eknrqn9nz.png','title-tag-ann.png','image/png',72110,'annotated','uuw4wjax85l0ogqaudm5cff3ojynnnyp'),('67zs7gvkioei6vixr7drq275taipnxgz','sxo44g190i0nadkb0lc5zvii63516pl7.png','frames-full.png','image/png',232939,'fullsize','ix25lyesrj0d5gbr6do9f9sjqn87rhfx'),('yk8jo4gmm17oy8mcjd1ppjtshrkcyilz','1s8v5q4khfn2ckhvtblwuoxq9p9a8fs6.png','frames-ann.png','image/png',244466,'annotated','ix25lyesrj0d5gbr6do9f9sjqn87rhfx');
/*!40000 ALTER TABLE `test_screenshot` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_themes`
--

DROP TABLE IF EXISTS `test_themes`;
CREATE TABLE `test_themes` (
  `theme_id` varchar(32) NOT NULL default '',
  `css_file_name` varchar(100) NOT NULL default '',
  `theme_name` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`theme_id`),
  UNIQUE KEY `theme_name` (`theme_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `test_themes`
--

LOCK TABLES `test_themes` WRITE;
/*!40000 ALTER TABLE `test_themes` DISABLE KEYS */;
/*!40000 ALTER TABLE `test_themes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_translation`
--

DROP TABLE IF EXISTS `test_translation`;
CREATE TABLE `test_translation` (
  `tId` varchar(32) NOT NULL default '',
  `tLanguage` char(2) NOT NULL default '',
  `tString` text NOT NULL,
  PRIMARY KEY  (`tId`,`tLanguage`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `test_translation`
--

LOCK TABLES `test_translation` WRITE;
/*!40000 ALTER TABLE `test_translation` DISABLE KEYS */;
INSERT INTO `test_translation` VALUES ('wjpjn8frbexxnko1ublijm75v5mges5q','US','Nielsen: Ten Usability Heuristics'),('wjpjn8frbexxnko1ublijm75v5mges5q','DE','Nielsen: 10 Usability Heuristiken'),('p0up8b5s1ok43w0xp4i2nwmcmf20mawa','US','These are ten general principles for user interface design. They are called \"heuristics\" because they are more in the nature of rules of thumb than specific usability guidelines'),('p0up8b5s1ok43w0xp4i2nwmcmf20mawa','DE','10 Prinzipien fÃ¼r das Design von Benutzerschnitstellen. Diese heiÃŸen \"Heuristiken\", weil sie eher einen Leitfaden, als ein festes Regelwerk darstellen'),('drc2k3jp7xvx2stzjmlaujg7tkdnuitp','US','Make objects, actions, and options visible. The user should not have to remember information from one part of the dialogue to another. Instructions for use of the system should be visible or easily retrievable whenever appropriate.'),('drc2k3jp7xvx2stzjmlaujg7tkdnuitp','DE','Objekte, Aktionen und Optionen sollen sichtbar sein. Der Benutzer sollte nicht dazu gezwungen sein, sich Informationen Ã¼ber mehrere Teile des Dialogs hinweg, zu merken. Hinweise fÃ¼r die Benutzung des Systems sollten sichtbar sein, oder einfach zu erhalten sein, wann immer diese begraucht werden.'),('5ooa2zb9bdpobpdkzqq0kzl2sui5wdcc','US','Flexibility and efficiency of use'),('5ooa2zb9bdpobpdkzqq0kzl2sui5wdcc','DE','FlexibilitÃ¤t und EffektivitÃ¤t in der Anwendung'),('ej944is9kvs2pnm6vutsed2kd5twrlk5','US','Accelerators -- unseen by the novice user -- may often speed up the interaction for the expert user such that the system can cater to both inexperienced and experienced users. Allow users to tailor frequent actions.'),('ej944is9kvs2pnm6vutsed2kd5twrlk5','DE','TastenkÃ¼rzel - vor dem AnfÃ¤nger verborgen - kÃ¶nnen die Geschwindigkeit der Interaktion fÃ¼r einen erfahrenen Benutzer beschleunigen. Ein solches System kann von beiden, unerfahrenen und erfahrenen Benutzerm effektiv benutzt werden. Es sollte die MÃ¶glichkeit bestehen, eigene TastenkÃ¼rzel zu definieren.'),('ej6epsgjj6rhfoa9o39djic8g8sz3f3p','US','Aesthetic and minimalist design'),('ej6epsgjj6rhfoa9o39djic8g8sz3f3p','DE','Ã„stetik und Minimalismus im Design'),('h1x6lzwcblv8ervu3c4ohc9ukzzj9wym','US','Dialogues should not contain information which is irrelevant or rarely needed. Every extra unit of information in a dialogue competes with the relevant units of information and diminishes their relative visibility.'),('h1x6lzwcblv8ervu3c4ohc9ukzzj9wym','DE','Dialoge sollten keine Informationen beeinhalten, die im Kontext irrelevant sind, oder selten benÃ¶tigt werden. Jede Ã¼berflÃ¼ssige Information im Dialog, steht im Konflikt mit wichtigen Informationen und verschleiert deren Sichtbarkeit.'),('wkc0kbarcmy5yeh7l63py70rgtvozy06','US','Help users recognize, diagnose, and recover from errors'),('wkc0kbarcmy5yeh7l63py70rgtvozy06','DE','Der Benutzer soll Fehler erkennen, einschÃ¤tzen und korrigieren kÃ¶nnen'),('mad9252uzlu3vb1z7bjcw221gctjoy87','US','Error messages should be expressed in plain language (no codes), precisely indicate the problem, and constructively suggest a solution.'),('mad9252uzlu3vb1z7bjcw221gctjoy87','DE','Fehlermeldungen sollten in Textform ausgegeben werden (keine Codes), prÃ¤zise das Problem beschreiben und konstruktiv eine LÃ¶sung vorschlagen.'),('f7cmzah1jnkcipvucnqc6gwz5lis1ic0','US','Help and documentation'),('f7cmzah1jnkcipvucnqc6gwz5lis1ic0','DE','Hilfe und Dokumentation'),('qqib359hvnp9y8o7u5vmebyx7p4et3k0','US','Even though it is better if the system can be used without documentation, it may be necessary to provide help and documentation. Any such information should be easy to search, focused on the users task, list concrete steps to be carried out, and not be too large.'),('qqib359hvnp9y8o7u5vmebyx7p4et3k0','DE','Auch wenn das System so intuitiv ist, das es ohne Dokumenation auskommt, kann es nÃ¶tig sein Hilfe und Dokumentation bereit zu stellen. Jeder Teil dieser Dokumentation sollte einfach zu durchsuchen sein, im Kontext zur Aufgabe des Benutzers stehen, Schritte fÃ¼r ein konkretes Vorgehen auflisten und nicht zu groÃŸ sein.'),('il0ciqyt086bcl7xjnhfumub8h2dg26m','US','Consistency and standards'),('il0ciqyt086bcl7xjnhfumub8h2dg26m','DE','Konsistenz und Einhaltung von Standards'),('0fxcsxg3z8iu4paptd98kf2bqal7jblg','US','Users should not have to wonder whether different words, situations, or actions mean the same thing. Follow platform conventions.'),('0fxcsxg3z8iu4paptd98kf2bqal7jblg','DE','Der Benutzer sollte nicht dazu gezwungen sein unterschiedliche WÃ¶rter, Situationen und Aktionen, die das selbe bedeuten jeweils neu interpretieren zu mÃ¼ssen. Man sollte sich an (plattformabhÃ¤ngige) Konventionen halten.'),('koe29tbg6b6k43vxxjh4ci2bvabcjxbo','US','Visibility of system status'),('koe29tbg6b6k43vxxjh4ci2bvabcjxbo','DE','Sichtbarkeit des System Status'),('vt6p3k53cusph4fvfped35m4k3fzdtdx','US','The system should always keep users informed about what is going on, through appropriate feedback within reasonable time.'),('vt6p3k53cusph4fvfped35m4k3fzdtdx','DE','Das System sollte den Benutzer stehts informaieren, was das System gerade macht, adequate Informationen zur richtigen Zeit liefern.'),('g0ji712rc6ki7wc18r8ixvbehqmcrqby','US','User control and freedom'),('g0ji712rc6ki7wc18r8ixvbehqmcrqby','DE','Kontrolle durch Benutzer und Freiheit(?)'),('a6svqge47yrkmnl5civmbmordlypvghb','US','Users often choose system functions by mistake and will need a clearly marked \"emergency exit\" to leave the unwanted state without having to go through an extended dialogue. Support undo and redo.'),('a6svqge47yrkmnl5civmbmordlypvghb','DE','Funktionen des Systems werden vom Benutzer oft durch einen Irrtum aktiviert. Das System sollte klar erkennbare MÃ¶glichkeiten bieten, einen ungewollten Zustand zu korrigieren, ohne dafÃ¼r komplexe Dialoge Ã¼ber sich ergehen zu lassen. Das System sollte Funktionen zum RÃ¼ckgÃ¤ngigmachen zur VerfÃ¼gung stellen.'),('qvkbomj8aun5e7xq4mdo9yczrq80xt1x','US','Match between system and the real world'),('qvkbomj8aun5e7xq4mdo9yczrq80xt1x','DE','Verbindung des Systems und der realen Welt'),('tldy5cuysoh4hinwehzpp1czw217725o','US','The system should speak the users language, with words, phrases and concepts familiar to the user, rather than system-oriented terms. Follow real-world conventions, making information appear in a natural and logical order.'),('tldy5cuysoh4hinwehzpp1czw217725o','DE','Das System sollte die Sprache des Benutzers sprechen. AusdrÃ¼cke und Konzepte, die der Benutzer kennt sollten gegenÃ¼ber systemorientierten Begriffen bevorzugt werden. Konventionen aus der realen Welt stellen Informationen in einer natÃ¼rlichen und logischen Ordnung dar.'),('cmupsnpds9y8fujqodgkexwnc2n4eq33','US','Severity'),('cmupsnpds9y8fujqodgkexwnc2n4eq33','DE','Gewicht'),('10','US','Severity'),('10','DE','Schwere'),('yg1etueb0otsoxevic7ca6yaw03ikmfp','US','catastrophic problem'),('yg1etueb0otsoxevic7ca6yaw03ikmfp','DE','katastrophales Problem'),('xqljf8q070jo9pdgq0adcynifccqnvk0','US','major problem'),('xqljf8q070jo9pdgq0adcynifccqnvk0','DE','schweres Problem'),('zqb7bjl82k1ha0ekscr3kpivsmj83v4i','US','minor problem changed'),('zqb7bjl82k1ha0ekscr3kpivsmj83v4i','DE','leichtes Problem'),('12','US','cosmetic problem'),('12','DE','kosmetisches Problem'),('5jr6y95jbakr4rke70djkotjmtfru0bc','US','no problem at all'),('5jr6y95jbakr4rke70djkotjmtfru0bc','DE','gar kein Problem'),('s56yfez6aueu7kuqjqwhu7ml38iempdz','US','Website Evaluation'),('s56yfez6aueu7kuqjqwhu7ml38iempdz','DE','Evaluierung von Webseiten'),('ytsnm1hcfib0wg17rkqmv57a7ap4hs17','US',''),('ytsnm1hcfib0wg17rkqmv57a7ap4hs17','DE',''),('at745blwm8485dzc8v7wcj2j0fzc335l','US','Age'),('at745blwm8485dzc8v7wcj2j0fzc335l','DE','Alter'),('yd1zs93vsab50cbg0jowno1zyajqnc2a','US','Sex'),('yd1zs93vsab50cbg0jowno1zyajqnc2a','DE','Geschlecht'),('b572c4c6cdvkgzd0upjksdidfazwxstg','US','Web Browser'),('b572c4c6cdvkgzd0upjksdidfazwxstg','DE','Webbrowser'),('g3p8wwlg5kx9kd4f9lrk0tl4b9iu51vx','US','Operating System'),('g3p8wwlg5kx9kd4f9lrk0tl4b9iu51vx','DE','Betriebssystem'),('e38mi4soc436ze33adw0y1350uyhl6ex','US','Connection'),('e38mi4soc436ze33adw0y1350uyhl6ex','DE','Verbindung'),('i3797jdxvsjumwb5uexivp4makaadm8i','US','Monitor Colours'),('i3797jdxvsjumwb5uexivp4makaadm8i','DE','Monitor Farben'),('6kb0gb5nu85ju22353evj2hwgqowrdaz','US','Monitor Resolution'),('6kb0gb5nu85ju22353evj2hwgqowrdaz','DE','Monitor AuflÃ¶sung'),('meihqd403bqjpvr0kbzl52kww267kw3s','US','Monitor Size'),('meihqd403bqjpvr0kbzl52kww267kw3s','DE','Monitor GrÃ¶ÃŸe'),('wavtuk69we503if1xceglkn22riaiwh5','US','Date of Evaluation'),('wavtuk69we503if1xceglkn22riaiwh5','DE','Datum der Evaluierung'),('zize3c5tjviygghwf8y22qz9cvtvre1f','US','Time of Evaluation'),('5jsnx8xbmjr690nfcyru1iw1zrnajgzl','US','Error prevention'),('5jsnx8xbmjr690nfcyru1iw1zrnajgzl','DE','Vermeidung von Fehlern'),('xeea3yzlvp076rj0rkqzl8wmyhjau3ml','US','Even better than good error messages is a careful design which prevents a problem from occurring in the first place.'),('xeea3yzlvp076rj0rkqzl8wmyhjau3ml','DE','Besser als gute Fehlermeldungen ist ein Konzept, das Fehler vermeidet.'),('anggqtkpq5tftxopwlwm0ipjtehr7uq5','US','Recognition rather than recall'),('anggqtkpq5tftxopwlwm0ipjtehr7uq5','DE','Erkennung ist besser als Erinnerung'),('zize3c5tjviygghwf8y22qz9cvtvre1f','DE','Zeitraum der Evaluierung'),('i9su0t74wto2amg8p7dsxfrr6btdnpcv','US','Evaluation of www.tugraz.at'),('i9su0t74wto2amg8p7dsxfrr6btdnpcv','DE','Evaluierung von www.tugraz.at'),('7465vb0xuniyaxf3a2rwwean82lelqtd','US','The evaluation of www.tugraz.at using the HEM web application.'),('7465vb0xuniyaxf3a2rwwean82lelqtd','DE','Die Evaluierung von www.tugraz.at mit HEM.'),('kqhn8lvo9sp03pmylyvgk2qn3gyrwaa3','US','Instone: Site Usability Heuristics'),('kqhn8lvo9sp03pmylyvgk2qn3gyrwaa3','DE','Instone: Web Usability Heuristiken'),('7izar5bzuvlul7id2nssj2nbnv75168i','US','Jakob Nielsen\'s 10 usabilty heuristics with his description in each first paragraph and Instone\'s Web-specific comment following.'),('7izar5bzuvlul7id2nssj2nbnv75168i','DE','10 Heuristiken von Jakob Nielsen, darunter jeweils seine Beschreibung im ersten Absatz und Instones Kommentare darunter'),('y15d2kmq9myi479qohlbxyp50cu0vycm','US','Even though it is better if the system can be used without documentation, it may be necessary to provide help and documentation. Any such information should be easy to search, focused on the user\'s task, list concrete steps to be carried out, and not be too large.\r\n\r\nSome of the more basic sites will not need much documentation, if any. But as soon as you try any complicated tasks, you will need some help for those tasks.\r\n\r\nFor the Web, the key is to not just slap up some help pages, but to integrate the documentation into your site. There should be links from your main sections into specific help and vice versa. Help could even be fully integrated into each page so that users never feel like assistance is too far away.\r\n'),('y15d2kmq9myi479qohlbxyp50cu0vycm','DE','In jedem Fall ist es besser, wenn das System auch ohne Dokumentation verwendet werden kann. Hilfe und Dokumentation sind\r\naber meistens notwendig.\r\n\r\nSome of the more basic sites will not need much documentation, if any. But as soon as you try any complicated tasks, you will need some help for those tasks.\r\n\r\nFor the Web, the key is to not just slap up some help pages, but to integrate the documentation into your site. There should be links from your main sections into specific help and vice versa. Help could even be fully integrated into each page so that users never feel like assistance is too far away.\r\n'),('zmqlwm5669mzo3lye16ttrm9y41rfdln','US','Help and documentation'),('zmqlwm5669mzo3lye16ttrm9y41rfdln','DE','Hilfe und Dokumentation'),('pl9fpincixt1elwdv9qx67bd4nolirnp','DE','Gute Fehlermeldung ermÃ¶glichen es dem Benutzer, Fehler zu erkennen, diese einzuschÃ¤tzen und zu bewÃ¤ltigen. Gute Fehlermeldung sind: in einfacher Sprache (keine Codes), prÃ¤zis (den Fehler genau beschreibend), defensiv (niemals dem\r\nBenutzer die Schuld geben), konstruktiv (sollen einen LÃ¶sungsweg aufzeigen), und mehrstufig (einen Hinweis zu weiteren Informationen\r\nbeeinhalten).\r\n\r\nErrors will happen, despite all your efforts to prevent them. Every error message should offer a solution (or a link to a solution) on the error page.\r\n\r\nFor example, if a user\'s search yields no hits, do not just tell him to broaden his search. Provide him with a link that will broaden his search for him.\r\n\r\n'),('pp22lfrr4vyzil1lkk2plitfpa4d4bll','DE','â€œWeniger ist mehrâ€. Dialoge sollten keine Informationen enthalten, die unwichtig sind oder kaum benÃ¶tigt werden. Jede zusÃ¤tzliche Information konkuriert\r\nmit den wichtigen Informationen und vermindern ihre relative Sichtbarkeit.\r\n\r\nExtraneous information on a page is a distraction and a slow-down. Make rarely needed information accessible via a link so that the details are there when needed but do not interfere much with the more relevant content.\r\n\r\nThe best way to help make sure you are not providing too much (or too little) information at once is to use progressive levels of detail. Put the more general information higher up in your hierarchy and let users drill down deeper if they want the details. Likewise, make sure there is a way to go \"up\" to get the bigger picture, in case users jump into the middle of your site.\r\n\r\nMake sure your content is written for the Web and not just a repackaged brochure. Break information into chunks and use links to connect the relevant chunks so that you can support different uses of your content.\r\n'),('lv3a6rm5ya4wdj6abnp9enh5ha1agin4','US','Help users recognize, diagnose, and recover from errors'),('lv3a6rm5ya4wdj6abnp9enh5ha1agin4','DE','Gute Fehlermeldungen'),('pl9fpincixt1elwdv9qx67bd4nolirnp','US','Error messages should be expressed in plain language (no codes), precisely indicate the problem, and constructively suggest a solution.\r\n\r\nErrors will happen, despite all your efforts to prevent them. Every error message should offer a solution (or a link to a solution) on the error page.\r\n\r\nFor example, if a user\'s search yields no hits, do not just tell him to broaden his search. Provide him with a link that will broaden his search for him.\r\n'),('kca1xjbzme7f3sq1cxo8d5y7ww8bi5pj','DE','AbkÃ¼rzungen, die fÃ¼r unerfahrene Anwender unsichtbar sind, kÃ¶nnen die Geschwindigkeit der Benutzung fÃ¼r erfahrene Anwender\r\nerhÃ¶hen. Der Anwender sollte diese zudem selbst gestalten kÃ¶nnen.\r\n\r\nSome of the best accelerators are provided by the browser. Like bookmarks.\r\n\r\nMake pages at your your site easy to bookmark. If a user is only interested in one corner of your site, make it easy for him to get there. Better that than have him get frustrated trying to get from your home page to what he is looking for.\r\n\r\nDo not use frames in a way that prevent users from bookmarking effectively.\r\n\r\nSupport bookmarking by not generating temporary URLs that have a short lifespan. If every week you come out with a new feature article for your site, make sure your URL lives on, even after the content is taken down. Web Review uses long-term locations by putting date information into the URLs. Or, you could re-use your URLs for the newer content.\r\n\r\nConsider using GET instead of POST on your forms. GET attaches the paramters to the URL, so users can bookmark the results of a search. When they come back, they get their query re-evaluated without having to type anything in again.\r\n\r\nAll of these rules for \"design to be bookmarked\" also help you design to be linked to. If the contents of your site can easily be linked to, others can create specialized views of your site for specific users and tasks. Amazon.com\'s associates program is just one example of the value of being easy to link to.\r\n'),('rj5khgj43jqxkef3245vigf6a44llq3p','US','Aesthetic and minimalist design'),('rj5khgj43jqxkef3245vigf6a44llq3p','DE','Ã„sthetik und minimales Design'),('pp22lfrr4vyzil1lkk2plitfpa4d4bll','US','Dialogues should not contain information which is irrelevant or rarely needed. Every extra unit of information in a dialogue competes with the relevant units of information and diminishes their relative visibility.\r\n\r\nExtraneous information on a page is a distraction and a slow-down. Make rarely needed information accessible via a link so that the details are there when needed but do not interfere much with the more relevant content.\r\n\r\nThe best way to help make sure you are not providing too much (or too little) information at once is to use progressive levels of detail. Put the more general information higher up in your hierarchy and let users drill down deeper if they want the details. Likewise, make sure there is a way to go \"up\" to get the bigger picture, in case users jump into the middle of your site.\r\n\r\nMake sure your content is written for the Web and not just a repackaged brochure. Break information into chunks and use links to connect the relevant chunks so that you can support different uses of your content.\r\n\r\n'),('52uknnuce58dwqwlucdqtbcysfz7yvpi','DE','FlexibilitÃ¤t und Effizienz'),('kca1xjbzme7f3sq1cxo8d5y7ww8bi5pj','US','Accelerators -- unseen by the novice user -- may often speed up the interaction for the expert user such that the system can cater to both inexperienced and experienced users. Allow users to tailor frequent actions.\r\n\r\nSome of the best accelerators are provided by the browser. Like bookmarks.\r\n\r\nMake pages at your your site easy to bookmark. If a user is only interested in one corner of your site, make it easy for him to get there. Better that than have him get frustrated trying to get from your home page to what he is looking for.\r\n\r\nDo not use frames in a way that prevent users from bookmarking effectively.\r\n\r\nSupport bookmarking by not generating temporary URLs that have a short lifespan. If every week you come out with a new feature article for your site, make sure your URL lives on, even after the content is taken down. Web Review uses long-term locations by putting date information into the URLs. Or, you could re-use your URLs for the newer content.\r\n\r\nConsider using GET instead of POST on your forms. GET attaches the paramters to the URL, so users can bookmark the results of a search. When they come back, they get their query re-evaluated without having to type anything in again.\r\n\r\nAll of these rules for \"design to be bookmarked\" also help you design to be linked to. If the contents of your site can easily be linked to, others can create specialized views of your site for specific users and tasks. Amazon.com\'s associates program is just one example of the value of being easy to link to.\r\n\r\n'),('52uknnuce58dwqwlucdqtbcysfz7yvpi','US','Flexibility and efficiency of use'),('9hxtxyo265y5gqqb10dzbqteng4pvcqr','DE','Erkennen ist besser als Erinnern'),('739acl03tnp7cqumwbmghm7ogd24692k','US','Make objects, actions, and options visible. The user should not have to remember information from one part of the dialogue to another. Instructions for use of the system should be visible or easily retrievable whenever appropriate.\r\n\r\nFor the Web, this heuristic is closely related to system status. If users can recognize where they are by looking at the current page, without having to recall their path from the home page, they are less likely to get lost.\r\n\r\nCertainly the most invisible objects created on the Web are server-side image maps. Client-side image maps are a lot better, but it still takes very well-crafted images to help users recognize them as links.\r\n\r\nGood labels and descriptive links are also crucial for recognition.\r\n'),('739acl03tnp7cqumwbmghm7ogd24692k','DE','Wissen in die Welt platzieren. Objekte, Aktionen, und Optionen sollten klar sichtbar sein.\r\n\r\nFor the Web, this heuristic is closely related to system status. If users can recognize where they are by looking at the current page, without having to recall their path from the home page, they are less likely to get lost.\r\n\r\nCertainly the most invisible objects created on the Web are server-side image maps. Client-side image maps are a lot better, but it still takes very well-crafted images to help users recognize them as links.\r\n\r\nGood labels and descriptive links are also crucial for recognition.\r\n'),('9hxtxyo265y5gqqb10dzbqteng4pvcqr','US','Recognition rather than recall'),('t0tt30dyo0ux1oq8iac60uyqxq6x7net','DE','Das gleiche Wort, die gleiche Situation, oder die gleiche Aktion sollte immer dasselbe bedeuten. Die Anwendung sollte Plattformkonventionen folgen. Falls es eine Standardmethode gibt, sollte man diese auch verwenden, wenn\r\nes nicht einen sehr guten Grund gibt, dies nicht zu tun.\r\n\r\nWithin your site, use wording in your content and buttons consistently. One of the most common cases of inconsistent wording I see deals with links, page titles and page headers. Check the titles and headers for your pages against the links that point to them. Inconsistent wording here can confuse users who think they ended up in the wrong spot because the destination page had a title that differed vastly from the link that took them there.\r\n\r\n\"Platform conventions\" on the Web means realizing your site is not an island. Users will be jumping onto (and off of) your site from others, so you need to fit in with the rest of the Web to some degree. Custom link colors is just one example where it may work well for your site but since it couldconflict with the rest of the Web, it may make your site hard to use.\r\n\r\nAnd \"standards\" on the Web means following HTML and other specifications. Deviations form the standards will be opportunities for unusable features to creep into your site.\r\n'),('lvu7kn2mtpj7ztihyfk3f6xclzwnx2yp','US','Error prevention'),('lvu7kn2mtpj7ztihyfk3f6xclzwnx2yp','DE','Fehlervermeidung'),('wu4cgmesr0444e4zcpnbsih5c3badhlu','US','Even better than good error messages is a careful design which prevents a problem from occurring in the first place.\r\n\r\nBecause of the limitations of HTML forms, inputting information on the Web is a common source of errors for users. Full-featured, GUI-style widgets are on their way; in the meanwhile you can use JavaScript to prevent some errors before users submit, but you still have to double-check after submission.\r\n\r\n'),('wu4cgmesr0444e4zcpnbsih5c3badhlu','DE','Fehler erkennen ist gut, Fehler vermeiden ist besser. Ein umsichtiges Design, welches Fehlern vorbeugt, bevor sie auftreten kÃ¶nnen\r\nist immer besser als eine gute Fehlermeldung.\r\n\r\nBecause of the limitations of HTML forms, inputting information on the Web is a common source of errors for users. Full-featured, GUI-style widgets are on their way; in the meanwhile you can use JavaScript to prevent some errors before users submit, but you still have to double-check after submission.\r\n'),('lokgo8z82pit1x70432ydz4i9yke8t9d','DE','Der Benutzer sollte die OberflÃ¤che frei erkunden kÃ¶nnen und dabei erreichte ungewollte ZustÃ¤nde mittels eines klar ersichtlichen\r\nWeges rÃ¼ckgÃ¤ngig machen kÃ¶nnen. Bei der Bedienung des Systems machen Benutzer manchmal Fehler. Der Benutzer sollte jedoch\r\nnicht durch komplexe Dialoge dafÃ¼r bestraft werden.\r\nFunktionen wie RÃ¼ckgÃ¤ngig und Wiederholen unterstÃ¼tzen den Benutzer bei der intuitiven Benutzung einer OberflÃ¤che.\r\n\r\nMany of the \"emergency exits\" are provided by the browser, but there is still plenty of room on your site to support user control and freedom. Or, there are many ways authors can take away user control that is built into the Web. A \"home\" button on every page is a simple way to let users feel in control of your site. \r\n\r\nBe careful when forcing users into certain fonts, colors, screen widths or browser versions. And watch out for some of those \"advanced technologies\": usually user control is not added until the technology has matured. One example is animated GIFs. Until browsers let users stop and restart the animations, they can do more harm than good.\r\n'),('d7qfl46n8v73l25ulrn7s2ejuv1gmot1','US','Consistency and standards'),('d7qfl46n8v73l25ulrn7s2ejuv1gmot1','DE','Konsistenz'),('t0tt30dyo0ux1oq8iac60uyqxq6x7net','US','Users should not have to wonder whether different words, situations, or actions mean the same thing. Follow platform conventions.\r\n\r\nWithin your site, use wording in your content and buttons consistently. One of the most common cases of inconsistent wording I see deals with links, page titles and page headers. Check the titles and headers for your pages against the links that point to them. Inconsistent wording here can confuse users who think they ended up in the wrong spot because the destination page had a title that differed vastly from the link that took them there.\r\n\r\n\"Platform conventions\" on the Web means realizing your site is not an island. Users will be jumping onto (and off of) your site from others, so you need to fit in with the rest of the Web to some degree. Custom link colors is just one example where it may work well for your site but since it couldconflict with the rest of the Web, it may make your site hard to use.\r\n\r\nAnd \"standards\" on the Web means following HTML and other specifications. Deviations form the standards will be opportunities for unusable features to creep into your site.\r\n\r\n'),('tltomgmg2hsbdexulaftcaoujh5pkm3y','DE','Das System sollte die Sprache des Anwenders verwenden, mit Worten, Phrasen und Konzepten, welche dem Benutzer vertraut sind. System-orientierte Fachbegriffe sollten vermieden werden. Es sollten Konventionen aus der realen Welt umgesetzt werden. Informationen sollten dem Benutzer in natÃ¼rlicher und logischer\r\nReihenfolge prÃ¤sentiert werden, dem mentalen Modell des Benutzers entsprechen. IrrefÃ¼hrende Metaphern sollten vermieden werden.\r\n\r\nOn the Web, you have to be aware that users will probably be coming from diverse backgrounds, so figuring out their \"language\" can be a challenge.\r\n'),('uzdvaa19p4hzj9nwh2bxi7ts39gu2apl','US','User control and freedom'),('uzdvaa19p4hzj9nwh2bxi7ts39gu2apl','DE','Umkehrbare Aktionen'),('lokgo8z82pit1x70432ydz4i9yke8t9d','US','Users often choose system functions by mistake and will need a clearly marked \"emergency exit\" to leave the unwanted state without having to go through an extended dialogue. Support undo and redo.\r\n\r\nMany of the \"emergency exits\" are provided by the browser, but there is still plenty of room on your site to support user control and freedom. Or, there are many ways authors can take away user control that is built into the Web. A \"home\" button on every page is a simple way to let users feel in control of your site. \r\n\r\nBe careful when forcing users into certain fonts, colors, screen widths or browser versions. And watch out for some of those \"advanced technologies\": usually user control is not added until the technology has matured. One example is animated GIFs. Until browsers let users stop and restart the animations, they can do more harm than good.\r\n\r\n'),('drqz1pkzuum5n6lz3ydr93n7sm7zrmws','US','Visibility of system status'),('drqz1pkzuum5n6lz3ydr93n7sm7zrmws','DE','RÃ¼ckmeldung des Systemzustandes (Feedback)'),('g51rxmso3flw37h1ccdghe4xwuf29xkv','US','The system should always keep users informed about what is going on, through appropriate feedback within reasonable time.\r\n\r\nProbably the two most important things that users need to know at your site are \"Where am I?\" and \"Where can I go next?\"\r\n\r\nMake sure each page is branded and that you indicate which section it belongs to. Links to other pages should be clearly marked. Since users could be jumping to any part of your site from somewhere else, you need to include this status on every page.\r\n'),('g51rxmso3flw37h1ccdghe4xwuf29xkv','DE','Das System sollte dem Benutzer immer (zum richtigen Zeitpunkt) RÃ¼ckmeldung geben, womit es sich gerade beschÃ¤ftigt.\r\n\r\nProbably the two most important things that users need to know at your site are \"Where am I?\" and \"Where can I go next?\"\r\n\r\nMake sure each page is branded and that you indicate which section it belongs to. Links to other pages should be clearly marked. Since users could be jumping to any part of your site from somewhere else, you need to include this status on every page.\r\n'),('f7i1tt9umgpxtl936wksv4msol379lsa','US','Match between system and the real world'),('f7i1tt9umgpxtl936wksv4msol379lsa','DE','Sprache des Benutzers verwenden'),('tltomgmg2hsbdexulaftcaoujh5pkm3y','US','The system should speak the users\' language, with words, phrases and concepts familiar to the user, rather than system-oriented terms. Follow real-world conventions, making information appear in a natural and logical order.\r\n\r\nOn the Web, you have to be aware that users will probably be coming from diverse backgrounds, so figuring out their \"language\" can be a challenge.');
/*!40000 ALTER TABLE `test_translation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_user_attributes`
--

DROP TABLE IF EXISTS `test_user_attributes`;
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `test_user_attributes`
--

LOCK TABLES `test_user_attributes` WRITE;
/*!40000 ALTER TABLE `test_user_attributes` DISABLE KEYS */;
INSERT INTO `test_user_attributes` VALUES ('29214857b12575501c5c731353c7217e','John','Doe','hem@iicm.edu','Infeldgasse','16c','Graz','8010','Austria','','Admin Stuff   \r\n\r\nHey its me...'),('2swcmpex84arrcckfjd0iw9yf2ub9fen','Alois','Dengg','ali@sbox.tugraz.at','PetersbergenstraÃŸe','2','Graz','8042','Ã–sterreich','',''),('ayfht61xipijjnbmpt6swb63su25h0i2','Harald','Auer','rassna@sbox.tugraz.at','','','','','','',''),('d4lep4hd1ssrdenoki58rxky96cx8jxn','Martin','Loitzl','mloitzl@iicm.edu','','','','','','',''),('iiny2otwmid92y0njh8d7ohb7rsy0ll9','Joe','Zehenter','joe@derjoe.net','','','','','','','');
/*!40000 ALTER TABLE `test_user_attributes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_user_pref`
--

DROP TABLE IF EXISTS `test_user_pref`;
CREATE TABLE `test_user_pref` (
  `auth_user_id` varchar(32) NOT NULL default '',
  `pref_id` varchar(32) NOT NULL default '',
  `value` varchar(32) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `test_user_pref`
--

LOCK TABLES `test_user_pref` WRITE;
/*!40000 ALTER TABLE `test_user_pref` DISABLE KEYS */;
INSERT INTO `test_user_pref` VALUES ('29214857b12575501c5c731353c7217e','2','DE'),('iiny2otwmid92y0njh8d7ohb7rsy0ll9','2','US'),('d4lep4hd1ssrdenoki58rxky96cx8jxn','2','US'),('2swcmpex84arrcckfjd0iw9yf2ub9fen','2','US'),('ayfht61xipijjnbmpt6swb63su25h0i2','2','US');
/*!40000 ALTER TABLE `test_user_pref` ENABLE KEYS */;
UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

