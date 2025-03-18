CREATE  TABLE `test_ratingscheme_scale`  (
`schemeId` VARCHAR( 32  )  NOT  NULL ,
`scaleId` VARCHAR( 32  )  NOT  NULL ,
PRIMARY  KEY ( `schemeId`  , `scaleId`   ) 
);

CREATE TABLE `test_ratingscheme` (
  `schemeId` varchar(32) NOT NULL default '',
  `schemeTitleId` varchar(32) NOT NULL default '',
  `schemeResultOperation` char(10) NOT NULL default '',
  PRIMARY KEY  (`schemeId`)
) TYPE=MyISAM;