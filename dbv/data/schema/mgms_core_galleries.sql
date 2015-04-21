CREATE TABLE `mgms_core_galleries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `folder_id` int(11) NOT NULL,
  `thumbnail_id` int(11) DEFAULT NULL,
  `description` text,
  `updated_on` int(15) NOT NULL,
  `preview` varchar(255) DEFAULT NULL,
  `enable_comments` int(1) DEFAULT NULL,
  `published` int(1) DEFAULT NULL,
  `css` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `js` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `sort` int(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `thumbnail_id` (`thumbnail_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8