CREATE TABLE `wetumka_core_pages_home_page` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `ordering_count` int(11) DEFAULT NULL,
  `about_splash` longtext COLLATE utf8_unicode_ci,
  `about_block` longtext COLLATE utf8_unicode_ci,
  `service_splash` longtext COLLATE utf8_unicode_ci,
  `service_block` longtext COLLATE utf8_unicode_ci,
  `tech_splash` longtext COLLATE utf8_unicode_ci,
  `tech_block` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci