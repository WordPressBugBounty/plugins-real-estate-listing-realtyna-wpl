CREATE TABLE IF NOT EXISTS `#__wpl_notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `template` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subject` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `additional_memberships` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `additional_users` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `additional_emails` text COLLATE utf8_unicode_ci,
  `options` text COLLATE utf8_unicode_ci,
  `params` text COLLATE utf8_unicode_ci,
  `enabled` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

INSERT INTO `#__wpl_menus` (`id`, `client`, `type`, `parent`, `page_title`, `menu_title`, `capability`, `menu_slug`, `function`, `separator`, `enabled`, `index`, `position`, `dashboard`) VALUES
(13, 'backend', 'submenu', 'WPL_main_menu', 'Notifications', 'Notifications', 'admin', 'wpl_admin_notifications', 'b:notifications:home', 0, 1, 2.05, 0, 0);

INSERT INTO `#__wpl_extensions` (`id`, `type`, `title`, `parent`, `description`, `enabled`, `param1`, `param2`, `param3`, `param4`, `param5`, `params`, `editable`, `index`, `client`) VALUES
(109, 'javascript', 'Handlebars', 0, '', 1, 'handlebars', 'js/handlebars.js', '', '', '', '', 0, 109.99, 0);

INSERT INTO `#__wpl_setting_categories` (`id`, `name`, `showable`, `index`) VALUES (5, 'Notifications', 1, 99.00);

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
(53, 'wpl_sender_email', '', 1, 5, 'text', 'Sender email', NULL, '', 121.00),
(54, 'wpl_sender_name', '', 1, 5, 'text', 'Sender name', NULL, '', 122.00);

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
(55, 'property_location_pattern', '[street_no] [street] [street_suffix][glue] [location4_name][glue] [location2_name] [zip_name]', 1, 3, 'text', 'Property Location Pattern', NULL, '', 123.00),
(56, 'user_location_pattern', '[location5_name][glue] [location4_name][glue] [location2_name] [zip_name]', 1, 3, 'text', 'User Location Pattern', NULL, '', 124.00);

UPDATE `#__wpl_extensions` SET `param2`='https://maps.google.com/maps/api/js?libraries=places&sensor=true' WHERE `id`='94';
UPDATE `#__wpl_settings` SET `type`='wppages' WHERE `id`='25';

UPDATE `#__wpl_extensions` SET `param2`='https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic|Open+Sans:300italic,400italic,600italic,700italic,400,300,600,700|Scada:400italic,700italic,400,700|Archivo+Narrow:400,40|Lato:400,700,900,400italic|BenchNine' WHERE `id`='99';

INSERT INTO `#__wpl_events` (`id`, `type`, `trigger`, `class_location`, `class_name`, `function_name`, `params`, `enabled`) VALUES
(4, 'notification', 'contact_agent', 'libraries.event_handlers.notifications', 'wpl_events_notifications', 'contact_agent', '', 1);

INSERT INTO `#__wpl_notifications` (`id`, `description`, `template`, `subject`, `additional_memberships`, `additional_users`, `additional_emails`, `options`, `params`, `enabled`) VALUES
(2, 'Contact to listing agent from listing page', 'contact_agent', 'New Contact', '', '', '', NULL, '', 1);

INSERT INTO `#__wpl_activities` (`id`, `activity`, `position`, `enabled`, `index`, `params`, `show_title`, `title`, `association_type`, `associations`) VALUES
(23, 'listing_contact', 'pshow_position2', 1, 99.00, '', 1, 'Contact Agent', 1, '');
