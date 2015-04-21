CREATE TABLE `jasc_awh_newsletters` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `created_on` int(11) NOT NULL DEFAULT '0',
  `last_sent` int(5) NOT NULL DEFAULT '0',
  `sent_on` int(11) DEFAULT NULL,
  `send_cron` tinyint(1) NOT NULL DEFAULT '0',
  `read_receipts` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci