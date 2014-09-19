DROP TABLE IF EXISTS `partners_config_bbs`;
CREATE TABLE `partners_config_bbs` (
  `pbbs_id` int(11) NOT NULL AUTO_INCREMENT,
  `pbbs_tag` varchar(32) DEFAULT NULL,
  `pbbs_uri` varchar(128) DEFAULT NULL,
  `pbbs_estado` int(11) DEFAULT NULL,
  `idpartner` int(11) DEFAULT NULL,
  PRIMARY KEY (`pbbs_id`),
  KEY `partner_config_bbs_key` (`idpartner`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
INSERT INTO `partners_config_bbs` VALUES (1,'overstoc','http://ztransaction.bongous.com/',1,6884);
