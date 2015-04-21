CREATE TABLE `jasc_core_contact_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `message` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `sender_agent` varchar(255) NOT NULL,
  `sender_ip` varchar(45) NOT NULL,
  `sender_os` varchar(255) NOT NULL,
  `sent_at` int(13) DEFAULT NULL,
  `attachments` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8