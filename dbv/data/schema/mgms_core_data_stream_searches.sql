CREATE TABLE `mgms_core_data_stream_searches` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `search_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `search_term` text COLLATE utf8_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `total_results` int(11) NOT NULL,
  `query_string` longtext COLLATE utf8_unicode_ci NOT NULL,
  `stream_slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci