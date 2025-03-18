CREATE  TABLE `test_rating_scale`  (
`scaleId` VARCHAR( 32  )  NOT  NULL ,
`scaleTitleId` VARCHAR( 32  )  NOT  NULL ,
PRIMARY  KEY ( `scaleId`   ) 
);

CREATE  TABLE `test_rating_scale_value`  (
`scaleValueId` VARCHAR( 32  )  NOT  NULL ,
`scaleValue` INT( 10  )  NOT  NULL ,
`scaleValueCaptionId` VARCHAR( 32  )  NOT  NULL ,
`scaleId` VARCHAR( 32  )  NOT  NULL 
);

ALTER  TABLE `test_rating_scale_value`   DROP  PRIMARY  KEY ,
ADD  PRIMARY  KEY ( `scaleValueId`   );
