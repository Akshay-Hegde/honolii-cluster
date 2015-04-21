CREATE TABLE `jasc_core_tour-content` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `ordering_count` int(11) DEFAULT NULL,
  `section_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `section_sub_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `section_content` longtext COLLATE utf8_unicode_ci,
  `section_class` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'span7',
  `aside_content` longtext COLLATE utf8_unicode_ci,
  `aside_class` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'span5',
  `section_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `content_background` char(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `product_group` varchar(255) COLLATE utf8_unicode_ci DEFAULT '0',
  `section_buttons` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci