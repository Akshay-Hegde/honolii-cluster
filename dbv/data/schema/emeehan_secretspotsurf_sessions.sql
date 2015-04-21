CREATE TABLE `emeehan_secretspotsurf_sessions` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `ordering_count` int(11) DEFAULT NULL,
  `ss_time` int(11) DEFAULT NULL,
  `ss_spot_id` int(11) DEFAULT NULL,
  `ss_board_id` int(11) DEFAULT NULL,
  `ss_rate` varchar(255) COLLATE utf8_unicode_ci DEFAULT '5',
  `notes` longtext COLLATE utf8_unicode_ci,
  `ss_session_photo` char(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ss_session_length` int(24) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci