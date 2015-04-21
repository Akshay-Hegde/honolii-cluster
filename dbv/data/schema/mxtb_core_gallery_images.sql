CREATE TABLE `mxtb_core_gallery_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_id` char(15) COLLATE utf8_unicode_ci NOT NULL,
  `gallery_id` int(11) NOT NULL,
  `order` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `gallery_id` (`gallery_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci