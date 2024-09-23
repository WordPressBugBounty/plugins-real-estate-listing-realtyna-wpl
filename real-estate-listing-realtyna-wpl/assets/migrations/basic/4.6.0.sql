UPDATE `#__wpl_dbst` SET `deletable`='0' WHERE `id`='6';
ALTER TABLE `#__wpl_properties` CHANGE `parent` `parent` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Parent';

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES ('341', 'geocoding_separator', NULL, '1', '1', 'separator', 'Geocoding', NULL, NULL, '97.60');
INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
(342, 'geocoding_server', 'google_first', 1, 1, 'select', 'Geocoding Server', '{\"tooltip\":\"WPL use the first server for geocoding and then use the second server if first server failed! Google geocoding is more accurate but OSM is free.\"}', '{\"values\":[{\"key\":\"google_first\",\"value\":\"First Google\"},{\"key\":\"osm_first\",\"value\":\"First OpenStreetMap\"}]}', 97.65);

UPDATE `#__wpl_settings` SET `index`='97.62' WHERE `id`='277';