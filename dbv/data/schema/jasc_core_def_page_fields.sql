CREATE TABLE `jasc_core_def_page_fields` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `ordering_count` int(11) DEFAULT NULL,
  `page_subtitle` varchar(110) COLLATE utf8_unicode_ci DEFAULT NULL,
  `page_content_area` longtext COLLATE utf8_unicode_ci,
  `section_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `page_content_class` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'span6',
  `aside_content` longtext COLLATE utf8_unicode_ci,
  `aside_content_class` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `background_image` char(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci