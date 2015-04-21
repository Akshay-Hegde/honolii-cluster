CREATE TABLE `jasc_core_keywords_applied` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hash` char(32) NOT NULL,
  `keyword_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1