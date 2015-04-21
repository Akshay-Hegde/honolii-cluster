CREATE TABLE `mxtb_core_quote_request_v2` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `ordering_count` int(11) DEFAULT NULL,
  `full_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(12) COLLATE utf8_unicode_ci DEFAULT NULL,
  `track_address` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `track_address_city` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `track_address_state` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `track_address_zip` varchar(12) COLLATE utf8_unicode_ci DEFAULT NULL,
  `track_lot_size` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lot_photograph` char(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `project_budget` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `track_permits` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `expenses_fuel` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `project_start_date` date DEFAULT NULL,
  `marketing` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'Undefined',
  `more_information` longtext COLLATE utf8_unicode_ci,
  `existing_track` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `irrigation_system` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `soil_comp` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `soil_comp_rocks` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tree_removal` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `topography` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `track_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci