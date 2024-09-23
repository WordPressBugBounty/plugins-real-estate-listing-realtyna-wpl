ALTER TABLE `#__wpl_dbst` CHANGE `index` `index` FLOAT(9,4) NOT NULL DEFAULT '99.0000';

UPDATE `#__wpl_dbst` SET `mandatory`='2' WHERE `id`='41';
UPDATE `#__wpl_settings` SET `title`='Locations' WHERE `id`='65';

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
(188, 'wizard_map_zoomlevel', '11', 1, 3, 'text', 'Wizard Map Zoom-level', '', '', 7.00);

INSERT INTO `#__wpl_units` (`id`, `name`, `type`, `enabled`, `tosi`, `index`, `extra`, `extra2`, `extra3`, `extra4`, `seperator`, `d_seperator`, `after_before`) VALUES
(270, '¥', 4, 0, 0.16, 999, 'CNY', 'Chinese Yuan Renminbi', 'china', 'Fen', NULL, NULL, 0);

UPDATE `#__wpl_extensions` SET `param2`='//fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic|Open+Sans:300italic,400italic,600italic,700italic,400,300,600,700|Scada:400italic,700italic,400,700|Archivo+Narrow:400,40|Lato:400,700,900,400italic|BenchNine|Roboto:400,700' WHERE `id`='99';

ALTER TABLE `#__wpl_dbst` ADD `sortable` TINYINT NOT NULL DEFAULT '1' COMMENT '0=not sortable,1=sortable,2=always';
UPDATE `#__wpl_dbst` SET `sortable`='2' WHERE `id` IN (5,911,1105);
UPDATE `#__wpl_dbst` SET `sortable`='0' WHERE `table_column`='' OR `table_column` IS NULL;

UPDATE `#__wpl_extensions` SET `param1`='wp' WHERE `id`='18';

UPDATE `#__wpl_units` SET `extra`='GBP' WHERE `id`='103';
UPDATE `#__wpl_units` SET `extra`='NZD' WHERE `id`='135';
UPDATE `#__wpl_units` SET `extra`='CUC' WHERE `id`='138';
UPDATE `#__wpl_units` SET `extra`='GBP' WHERE `id`='159';
UPDATE `#__wpl_units` SET `extra`='GBP' WHERE `id`='174';
UPDATE `#__wpl_units` SET `extra`='AUD' WHERE `id`='178';
UPDATE `#__wpl_units` SET `extra`='GBP' WHERE `id`='194';
UPDATE `#__wpl_units` SET `extra`='PRB' WHERE `id`='251';

INSERT INTO `#__wpl_notifications` (`id`, `description`, `template`, `subject`, `additional_memberships`, `additional_users`, `additional_emails`, `options`, `params`, `enabled`) VALUES (8, 'When a listing creates', 'listing_create', 'New listing', '', '', '', NULL, '', 0);
INSERT INTO `#__wpl_events` (`id`, `type`, `trigger`, `class_location`, `class_name`, `function_name`, `params`, `enabled`) VALUES (42, 'notification', 'add_property', 'libraries.event_handlers.notifications', 'wpl_events_notifications', 'listing_create', '', 1);

ALTER TABLE `#__wpl_cronjobs` CHANGE `period` `period` FLOAT(6,2) NOT NULL;

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES ('200', 'autoupdate_exchange_rates', '0', '1', '1', 'select', 'Auto-update exchange rates', '', '{"values":[{"key":0,"value":"Disabled"},{"key":1,"value":"Enabled"}]}', '97.00');
INSERT INTO `#__wpl_cronjobs` (`id`, `cronjob_name`, `period`, `class_location`, `class_name`, `function_name`, `params`, `enabled`, `latest_run`) VALUES (9, 'Auto-Update Exchange Rates', 24, 'libraries.units', 'wpl_units', 'auto_update_rates', '', 1, '2016-03-01 00:00:00');

