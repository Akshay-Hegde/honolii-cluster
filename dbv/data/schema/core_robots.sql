CREATE TABLE `core_robots` (
  `robots_id` int(11) NOT NULL AUTO_INCREMENT,
  `sites_id` int(5) NOT NULL,
  `site_ref` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `txt` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`robots_id`,`sites_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci