CREATE TABLE `wetumka_core_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` decimal(12,2) NOT NULL,
  `invoice` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `confirm` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci