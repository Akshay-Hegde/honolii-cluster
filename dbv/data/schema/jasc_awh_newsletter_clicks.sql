CREATE TABLE `jasc_awh_newsletter_clicks` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url_id` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `newsletter_id` int(6) DEFAULT NULL,
  `ip` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci