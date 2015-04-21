CREATE TABLE `emeehan_str_social_network` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `ordering_count` int(11) NOT NULL,
  `name` varchar(125) COLLATE utf8_unicode_ci DEFAULT NULL,
  `class` varchar(125) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url` varchar(125) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci