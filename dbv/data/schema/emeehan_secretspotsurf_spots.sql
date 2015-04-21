CREATE TABLE `emeehan_secretspotsurf_spots` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `ordering_count` int(11) DEFAULT NULL,
  `ss_rate` varchar(255) COLLATE utf8_unicode_ci DEFAULT '5',
  `name` varchar(125) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notes` longtext COLLATE utf8_unicode_ci,
  `ss_spot_photo` char(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ss_spot_latitude` int(11) DEFAULT NULL,
  `ss_spot_longitude` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci