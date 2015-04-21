CREATE TABLE `jasc_core_redirects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `to` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` int(11) NOT NULL DEFAULT '302',
  PRIMARY KEY (`id`),
  KEY `request` (`from`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8