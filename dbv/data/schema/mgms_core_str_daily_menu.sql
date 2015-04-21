CREATE TABLE `mgms_core_str_daily_menu` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `ordering_count` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `am_snack` longtext COLLATE utf8_unicode_ci,
  `lunch` longtext COLLATE utf8_unicode_ci,
  `pm_snack` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci