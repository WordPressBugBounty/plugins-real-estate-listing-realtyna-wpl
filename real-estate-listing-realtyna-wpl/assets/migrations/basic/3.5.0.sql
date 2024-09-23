UPDATE `#__wpl_dbst` SET `name`='Square Footage' WHERE `id`='10' AND `name`='Built Up Area';

INSERT INTO `#__wpl_dbst_types` (`id`, `kind`, `type`, `enabled`, `index`, `queries_add`, `queries_delete`, `options`) VALUES
(24, '[0][1][2][4]', 'array', 1, 1.00, 'ALTER TABLE `[TB_PREFIX][TABLE_NAME]` ADD `field_[FIELD_ID]` text NULL; UPDATE `[TB_PREFIX]wpl_dbst` SET `table_name`=''[TABLE_NAME]'', `table_column`=''field_[FIELD_ID]'' WHERE id=[FIELD_ID];', 'ALTER TABLE `[TB_PREFIX][TABLE_NAME]` DROP `field_[FIELD_ID]`;', NULL);

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
(257, 'googlemap_display_status', '1', 1, 1, 'select', 'Google Maps Display', '{"tooltip":"If you hided it, you can place a Google Maps widget into your desired sidebar. Then your website users can view the map using the Google Maps widget."}', '{"values":[{"key":1,"value":"Show By Default"},{"key":0,"value":"Hide By Default"}]}', 56.00);

INSERT INTO `#__wpl_extensions` (`id`, `type`, `title`, `parent`, `description`, `enabled`, `param1`, `param2`, `param3`, `param4`, `param5`, `params`, `editable`, `index`, `client`) VALUES
(140, 'widget', 'WPL Google Maps Widget', 0, '', 1, 'widgets.googlemap.main', 'widgets_init', 'WPL_googlemap_widget', '', '', '', 0, 99.99, 2);

UPDATE `#__wpl_extensions` SET `param2`='//fonts.googleapis.com/css?family=Droid+Serif|Open+Sans|Lato|BenchNine', `client`='0' WHERE `id`='99';

INSERT INTO `#__wpl_extensions` (`id`, `type`, `title`, `parent`, `description`, `enabled`, `param1`, `param2`, `param3`, `param4`, `param5`, `params`, `editable`, `index`, `client`) VALUES
(141, 'style', 'Backend Googlefont', '0', '', '1', 'wpl-google-font-backend', '//fonts.googleapis.com/css?family=Droid+Serif|Open+Sans|Lato|Scada|Archivo+Narrow', '', '', '1', '', '0', '36.00', '1');

DELETE FROM `#__wpl_extensions` WHERE `id`='103';
DELETE FROM `#__wpl_extensions` WHERE `id`='104';
DELETE FROM `#__wpl_extensions` WHERE `id`='111';
DELETE FROM `#__wpl_extensions` WHERE `id`='129';
DELETE FROM `#__wpl_extensions` WHERE `id`='123';
DELETE FROM `#__wpl_extensions` WHERE `id`='105';
DELETE FROM `#__wpl_extensions` WHERE `id`='101';
DELETE FROM `#__wpl_extensions` WHERE `id`='109';

UPDATE `#__wpl_extensions` SET `client`='1' WHERE `id`='102';
UPDATE `#__wpl_extensions` SET `client`='1' WHERE `id`='89';
UPDATE `#__wpl_extensions` SET `client`='1' WHERE `id`='88';

UPDATE `#__wpl_extensions` SET `param4`='1' WHERE `id`='102';
UPDATE `#__wpl_extensions` SET `param4`='1' WHERE `id`='116';
UPDATE `#__wpl_extensions` SET `param4`='1' WHERE `id`='89';
UPDATE `#__wpl_extensions` SET `param4`='1' WHERE `id`='88';

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
('258', 'wpl_cronjobs', '1', '0', '1', 'text', NULL, NULL, NULL, '99.00');

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
('259', 'wpl_last_cpanel_cronjobs', '', '0', '1', 'text', NULL, NULL, NULL, '99.00');

ALTER TABLE `#__wpl_sort_options` ADD `asc_label` VARCHAR(50) NULL AFTER `name`, ADD `desc_label` VARCHAR(50) NULL AFTER `asc_label`;

ALTER TABLE `#__wpl_sort_options` ADD `asc_enabled` TINYINT(4) NOT NULL DEFAULT '1' AFTER `asc_label`;
ALTER TABLE `#__wpl_sort_options` ADD `desc_enabled` TINYINT(4) NOT NULL DEFAULT '1' AFTER `desc_label`;

INSERT INTO `#__wpl_extensions` (`id`, `type`, `title`, `parent`, `description`, `enabled`, `param1`, `param2`, `param3`, `param4`, `param5`, `params`, `editable`, `index`, `client`) VALUES
(109, 'javascript', 'Handlebars', 0, '', 1, 'handlebars', 'js/libraries/wpl.handlebars.min.js', '', '', '', '', 0, 109.99, 1);

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
(203, 'wpl_theme_compatibility', '0', 1, 1, 'select', 'Theme Compatibility', '{"tooltip":"If enabled, WPL tries to make the frontend styles compatible with your theme. It works only for main style of some famous themes such as Bridge, Avada, X, Be etc."}', '{"values":[{"key":1,"value":"Enabled"},{"key":0,"value":"Disabled"}]}', 5.00);

UPDATE `#__wpl_settings` SET `category`='1', `index`='5.00' WHERE `id`='203';
DELETE FROM `#__wpl_addons` WHERE `id` IN ('31','32','33','34','35','36','40','41');

INSERT INTO `#__wpl_events` (`id`, `type`, `trigger`, `class_location`, `class_name`, `function_name`, `params`, `enabled`) VALUES
('44', 'notification', 'schedule_tour', 'libraries.event_handlers.notifications', 'wpl_events_notifications', 'schedule_tour', '', '1');

INSERT INTO `#__wpl_notifications` (`id`, `description`, `template`, `subject`, `additional_memberships`, `additional_users`, `additional_emails`, `options`, `params`, `enabled`) VALUES
(9, 'Schedule a Tour',  'schedule_tour',  'New Tour Schedule Request',  '', '', '', NULL, '', 0);

ALTER TABLE `#__wpl_properties` DROP INDEX `city_id`;
ALTER TABLE `#__wpl_properties` DROP INDEX `zone_id`;
ALTER TABLE `#__wpl_properties` DROP INDEX `price`;
ALTER TABLE `#__wpl_properties` DROP INDEX `location_text`;
ALTER TABLE `#__wpl_properties` DROP INDEX `deleted`;
ALTER TABLE `#__wpl_properties` DROP INDEX `confirmed`;
ALTER TABLE `#__wpl_properties` DROP INDEX `finalized`;

ALTER TABLE `#__wpl_properties` ADD INDEX (`kind`, `deleted`, `finalized`, `confirmed`, `expired`);
ALTER TABLE `#__wpl_properties` ADD INDEX (`user_id`);
ALTER TABLE `#__wpl_properties` ADD INDEX (`googlemap_lt`, `googlemap_ln`);
ALTER TABLE `#__wpl_properties` ADD INDEX (`show_address`);
ALTER TABLE `#__wpl_properties` ADD INDEX (`price_si`);
ALTER TABLE `#__wpl_properties` ADD INDEX (`add_date`);
ALTER TABLE `#__wpl_properties` ADD INDEX (`ref_id`);

ALTER TABLE `#__wpl_dbcat` ADD INDEX (`kind`, `enabled`);
ALTER TABLE `#__wpl_dbst` ADD INDEX (`kind`, `enabled`);
ALTER TABLE `#__wpl_dbst` ADD INDEX (`plisting`);
ALTER TABLE `#__wpl_dbst` ADD INDEX (`pshow`);
ALTER TABLE `#__wpl_dbst_types` ADD INDEX (`kind`, `enabled`);

ALTER TABLE `#__wpl_items` ADD INDEX (`item_type`);
ALTER TABLE `#__wpl_items` ADD INDEX (`item_cat`);

ALTER TABLE `#__wpl_sort_options` DROP INDEX `kind`;
ALTER TABLE `#__wpl_sort_options` ADD INDEX (`kind`, `enabled`);

ALTER TABLE `#__wpl_location1` ADD INDEX (`enabled`);
ALTER TABLE `#__wpl_locationtextsearch` ADD INDEX (location_text(255));

ALTER TABLE `#__wpl_filters` ADD INDEX (`enabled`);
ALTER TABLE `#__wpl_events` ADD INDEX (`enabled`);

ALTER TABLE `#__wpl_users` ADD INDEX (`membership_id`);
ALTER TABLE `#__wpl_users` ADD INDEX (`expired`, `expiry_date`);

ALTER TABLE `#__wpl_settings` ADD INDEX (`setting_name`);
ALTER TABLE `#__wpl_settings` ADD INDEX (`category`);

ALTER TABLE `#__wpl_listing_types` ADD INDEX (`parent`);
ALTER TABLE `#__wpl_property_types` ADD INDEX (`parent`);

INSERT INTO `#__wpl_cronjobs` (`id`, `cronjob_name`, `period`, `class_location`, `class_name`, `function_name`, `params`, `enabled`, `latest_run`) VALUES
(32, 'Sort Option Indexes', 120.00, 'libraries.sort_options', 'wpl_sort_options', 'sort_options_add_indexes', '', 1, '2017-06-06 12:39:35'),
(33, 'Tags Indexes', 120.00, 'global', 'wpl_global', 'tags_add_indexes', '', 1, '2017-06-06 12:40:35');

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
(274, 'microdata_separator', '', 1, 4, 'separator', 'Micordata', '', '', 274.00),
(275, 'microdata', '0', 1, 4, 'select', 'Microdata(schema.org)', '', '{"values":[{"key":0,"value":"Disabled"},{"key":1,"value":"Enabled"}]} ', 274.01);

ALTER TABLE `#__wpl_properties` ADD `mls_id_num` BIGINT NULL AFTER `mls_id`;
ALTER TABLE `#__wpl_properties` ADD INDEX (`mls_id_num`);
UPDATE `#__wpl_properties` SET `mls_id_num`=cast(`mls_id` AS unsigned) WHERE mls_id_num IS NULL;

ALTER TABLE `#__wpl_properties` ADD FULLTEXT `textsearch` (`textsearch`);


ALTER TABLE `#__wpl_users` ADD FULLTEXT `textsearch` (`textsearch`);

INSERT INTO `#__wpl_notifications` (`id`, `description`, `template`, `subject`, `additional_memberships`, `additional_users`, `additional_emails`, `options`, `params`, `enabled`) VALUES
(10, 'Adding Price Request notification', 'adding_price_request', 'A new price request', '', '', '', null, null, '1');

INSERT INTO `#__wpl_events` (`id`, `type`, `trigger`, `class_location`, `class_name`, `function_name`, `params`, `enabled`) VALUES
(45, 'notification', 'wpl_adding_price_request', 'libraries.event_handlers.notifications', 'wpl_events_notifications', 'adding_price_request', '', '1');

INSERT INTO `#__wpl_activities` (`id`, `activity`, `position`, `enabled`, `index`, `params`, `show_title`, `title`, `association_type`, `associations`, `client`) VALUES
(46, 'property_stats', 'pmanager_position2', 0, 99.00, '{"contacts":"1","including_in_listing":"1","view_parent":"1","visit":"1"}', 1, 'Property Stats', 1, '', 2);