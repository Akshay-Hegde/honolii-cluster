CREATE TABLE `mgms_core_newsletter_urls` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `target` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hash` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `newsletter_id` int(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci