CREATE TABLE `wetumka_core_work_details` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `ordering_count` int(11) DEFAULT NULL,
  `project_case_study` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `project_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `project_desktop` char(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `project_tablet` char(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `project_mobile` char(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `project_summary` longtext COLLATE utf8_unicode_ci,
  `project_title` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci