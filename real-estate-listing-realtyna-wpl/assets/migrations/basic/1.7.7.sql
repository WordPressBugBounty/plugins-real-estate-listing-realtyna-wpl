UPDATE `#__wpl_dbst` SET `text_search`='1' WHERE `id`='312';
UPDATE `#__wpl_dbst` SET `text_search`='1' WHERE `id`='313';

CREATE TABLE IF NOT EXISTS `#__wpl_kinds` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `table` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

INSERT INTO `#__wpl_kinds` (`id`, `name`, `table`) VALUES
(0, 'Property', 'wpl_properties'),
(2, 'User', 'wpl_users');

UPDATE `#__wpl_settings` SET `index`='50.00' WHERE `id`='50';
UPDATE `#__wpl_settings` SET `title`='Property Pattern' WHERE `id`='55';
UPDATE `#__wpl_settings` SET `title`='User Pattern' WHERE `id`='56';

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
(65, 'location_separator3', '', 1, 3, 'separator', 'Locations', '', '', 4.50),
(63, 'location_separator1', '', 1, 3, 'separator', 'Location Keywords', '', '', 98.00),
(64, 'location_separator2', '', 1, 3, 'separator', 'Location Patterns', '', '', 122.00);

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
(66, 'permalink_separator', '', 1, 4, 'separator', 'WPL Permalink', '', '', 0.90);

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
(67, 'sender_separator', NULL, 1, 5, 'separator', 'Notification Sender', NULL, NULL, 120.50);

DELETE FROM `#__wpl_settings` WHERE `id`='3';

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
(68, 'resize_separator', NULL, 1, 2, 'separator', 'Resize', NULL, NULL, 1.50),
(69, 'watermark_separator', NULL, 1, 2, 'separator', 'Watermark', NULL, NULL, 4.50);

UPDATE `#__wpl_settings` SET `index`='109.00' WHERE `id`='31';
UPDATE `#__wpl_settings` SET `index`='51.00' WHERE `id`='22';
UPDATE `#__wpl_settings` SET `index`='52.00' WHERE `id`='27';
UPDATE `#__wpl_settings` SET `index`='53.00' WHERE `id`='51';

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
(70, 'global_separator', NULL, 1, 1, 'separator', 'Global', NULL, NULL, 0.05),
(71, 'listing_pages_separator', NULL, 1, 1, 'separator', 'Listings', NULL, NULL, 98.00),
(72, 'users_separator', NULL, 1, 1, 'separator', 'Users', NULL, NULL, 107.00),
(73, 'io_separator', NULL, 1, 1, 'separator', 'I/O Application', NULL, NULL, 116.00);
