ALTER TABLE `#__wpl_properties` DROP `description`;
ALTER TABLE `#__wpl_properties` DROP `googlemap_title`;
ALTER TABLE `#__wpl_properties` DROP `property_rank`;

UPDATE `#__wpl_dbst` SET `name`='List Date' WHERE `id`='19';

INSERT INTO `#__wpl_dbst` (`id`, `kind`, `mandatory`, `name`, `type`, `options`, `enabled`, `pshow`, `plisting`, `searchmod`, `editable`, `deletable`, `index`, `css`, `style`, `specificable`, `listing_specific`, `property_type_specific`, `user_specific`, `accesses`, `accesses_message`, `table_name`, `table_column`, `category`, `rankable`, `rank_point`, `comments`, `pwizard`, `text_search`, `params`, `flex`) VALUES
(22, 0, 0, 'Category', 'ptcategory', '', 2, '0', 0, 1, 0, 0, 1.045, '', '', 0, '', '', '', NULL, NULL, NULL, NULL, 1, 0, 0, '', '0', 0, '', 0);

UPDATE `#__wpl_settings` SET `params`='{"html_element_id":"wpl_watermark_uploader","request_str":"admin.php?wpl_format=b:settings:ajax&wpl_function=save_watermark_image"}' WHERE `id`='11';
UPDATE `#__wpl_extensions` SET `param2`='https://maps.google.com/maps/api/js?libraries=places,drawing&sensor=true' WHERE `id`='94';

UPDATE `#__wpl_dbst` SET `type`='text' WHERE `id`='5';

ALTER TABLE `#__wpl_properties` ADD `parent` int(11) unsigned NOT NULL COMMENT 'Parent' AFTER `mls_id`;

ALTER TABLE `#__wpl_activities` AUTO_INCREMENT=1000;

UPDATE `#__wpl_extensions` SET `title`='Helps Service', `description`='For running WPL Helps', `param2`='helps->run' WHERE `id`='36';

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
(141, 'location_suffix_prefix', 'County, Avenue, Ave, Boulevard, Blvd, Highway, Hwy, Lane, Ln, Square, Sq, Street, St, Road, Rd', 0, 3, 'text', 'Location Suffixes/Prefixes', '{"html_class":"long"}', '', 141.00);

INSERT INTO `#__wpl_events` (`id`, `type`, `trigger`, `class_location`, `class_name`, `function_name`, `params`, `enabled`) VALUES
(39, 'notification', 'user_registered', 'libraries.event_handlers.notifications', 'wpl_events_notifications', 'user_registered', '', 1);

INSERT INTO `#__wpl_notifications` (`id`, `description`, `template`, `subject`, `additional_memberships`, `additional_users`, `additional_emails`, `options`, `params`, `enabled`) VALUES
(5, 'Sends after registration process.', 'user_registered', 'Your Account has been registered.', '', '', '', NULL, '', '1');
