ALTER TABLE `#__wpl_activities` ADD `client` TINYINT(4) NOT NULL DEFAULT '2';

UPDATE `#__wpl_dbst_types` SET `queries_add`='ALTER TABLE `[TB_PREFIX][TABLE_NAME]` ADD `field_[FIELD_ID]` double NOT NULL DEFAULT ''0''; ALTER TABLE `[TB_PREFIX][TABLE_NAME]` ADD `field_[FIELD_ID]_si` double NOT NULL DEFAULT ''0''; ALTER TABLE `[TB_PREFIX][TABLE_NAME]` ADD `field_[FIELD_ID]_unit` int NULL; UPDATE `[TB_PREFIX]wpl_dbst` SET `table_name`=''[TABLE_NAME]'', `table_column`=''field_[FIELD_ID]'' WHERE id=[FIELD_ID];' WHERE `id`='8';
UPDATE `#__wpl_dbst_types` SET `queries_add`='ALTER TABLE `[TB_PREFIX][TABLE_NAME]` ADD `field_[FIELD_ID]` double NOT NULL DEFAULT ''0''; ALTER TABLE `[TB_PREFIX][TABLE_NAME]` ADD `field_[FIELD_ID]_si` double NOT NULL DEFAULT ''0''; ALTER TABLE `[TB_PREFIX][TABLE_NAME]` ADD `field_[FIELD_ID]_unit` int NULL; UPDATE `[TB_PREFIX]wpl_dbst` SET `table_name`=''[TABLE_NAME]'', `table_column`=''field_[FIELD_ID]'' WHERE id=[FIELD_ID];' WHERE `id`='9';
UPDATE `#__wpl_dbst_types` SET `queries_add`='ALTER TABLE `[TB_PREFIX][TABLE_NAME]` ADD `field_[FIELD_ID]` double NOT NULL DEFAULT ''0''; ALTER TABLE `[TB_PREFIX][TABLE_NAME]` ADD `field_[FIELD_ID]_si` double NOT NULL DEFAULT ''0''; ALTER TABLE `[TB_PREFIX][TABLE_NAME]` ADD `field_[FIELD_ID]_unit` int NULL; UPDATE `[TB_PREFIX]wpl_dbst` SET `table_name`=''[TABLE_NAME]'', `table_column`=''field_[FIELD_ID]'' WHERE id=[FIELD_ID];' WHERE `id`='10';
UPDATE `#__wpl_dbst_types` SET `queries_add`='ALTER TABLE `[TB_PREFIX][TABLE_NAME]` ADD `field_[FIELD_ID]` double NOT NULL DEFAULT ''0''; ALTER TABLE `[TB_PREFIX][TABLE_NAME]` ADD `field_[FIELD_ID]_si` double NOT NULL DEFAULT ''0''; ALTER TABLE `[TB_PREFIX][TABLE_NAME]` ADD `field_[FIELD_ID]_unit` int NULL; UPDATE `[TB_PREFIX]wpl_dbst` SET `table_name`=''[TABLE_NAME]'', `table_column`=''field_[FIELD_ID]'' WHERE id=[FIELD_ID];' WHERE `id`='11';

INSERT INTO `#__wpl_cronjobs` (`id`, `cronjob_name`, `period`, `class_location`, `class_name`, `function_name`, `params`, `enabled`, `latest_run`) VALUES
(5, 'Maintenance', 24, 'global', 'wpl_global', 'execute_maintenance_job', '', 1, '2014-12-31 11:54:17');

UPDATE `#__wpl_extensions` SET `param2`='js/libraries/wpl.imagesloaded.min.js' WHERE `id`='111';
UPDATE `#__wpl_extensions` SET `param2`='js/libraries/wpl.jquery.chosen.min.js' WHERE `id`='101';
UPDATE `#__wpl_extensions` SET `param2`='js/libraries/wpl.handlebars.min.js' WHERE `id`='109';
UPDATE `#__wpl_extensions` SET `param2`='js/libraries/wpl.jquery.hoverintent.js' WHERE `id`='104';
UPDATE `#__wpl_extensions` SET `param2`='js/libraries/wpl.jquery.mcustomscrollbar.min.js' WHERE `id`='102';
UPDATE `#__wpl_extensions` SET `param2`='js/libraries/wpl.ajaxfileupload.min.js' WHERE `id`='105';
UPDATE `#__wpl_extensions` SET `param2`='js/libraries/wpl.jquery.transit.min.js' WHERE `id`='103';

INSERT INTO `#__wpl_extensions` (`id`, `type`, `title`, `parent`, `description`, `enabled`, `param1`, `param2`, `param3`, `param4`, `param5`, `params`, `editable`, `index`, `client`) VALUES
('116', 'javascript', 'Realtyna Framework', '0', '', '1', 'realtyna-framework', 'js/libraries/realtyna/realtyna.min.js', '', '', '', '', '0', '200.00', '1');

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
('107', 'js_default_path', 'js/libraries', '0', '1', 'text', 'JS Default Path', '', '', '106.00');

INSERT INTO `#__wpl_dbst` (`id`, `kind`, `mandatory`, `name`, `type`, `options`, `enabled`, `pshow`, `plisting`, `searchmod`, `editable`, `deletable`, `index`, `css`, `style`, `specificable`, `listing_specific`, `property_type_specific`, `user_specific`, `table_name`, `table_column`, `category`, `rankable`, `rank_point`, `comments`, `pwizard`, `text_search`, `params`) VALUES
(20, 0, 0, 'Alias / Permalink', 'text', '', 2, '0', 0, 0, 1, 0, 1.020, '', '', 0, '', '', '', 'wpl_properties', 'alias', 1, 0, 0, '', '0', 0, '');

UPDATE `#__wpl_extensions` SET `param2`='wpl_extensions->wpl_admin_menus' WHERE `id`='1';
ALTER TABLE `#__wpl_users` ADD `maccess_wpl_color` VARCHAR(10) NULL AFTER `maccess_long_description`;

UPDATE `#__wpl_sort_options` SET `field_name`='p.mls_id' WHERE `id`='1';
UPDATE `#__wpl_settings` SET `setting_value`='p.mls_id' WHERE `id`='23' AND `setting_value`='p.mls_id+0';

INSERT INTO `#__wpl_dbst` (`id`, `kind`, `mandatory`, `name`, `type`, `options`, `enabled`, `pshow`, `plisting`, `searchmod`, `editable`, `deletable`, `index`, `css`, `style`, `specificable`, `listing_specific`, `property_type_specific`, `user_specific`, `table_name`, `table_column`, `category`, `rankable`, `rank_point`, `comments`, `pwizard`, `text_search`, `params`) VALUES
(21, 0, 0, 'Location Text', 'text', '{"if_zero":"1","call_text":"Call"}', 2, '0', 0, 0, 1, 0, 1.021, '', '', 0, '', '', '', 'wpl_properties', 'location_text', 2, 0, 0, '', '0', 0, '');

ALTER TABLE `#__wpl_dbst` ADD `flex` TINYINT(4) NOT NULL DEFAULT '1' AFTER `params`;
UPDATE `#__wpl_dbst_types` SET `queries_add`='ALTER TABLE `[TB_PREFIX][TABLE_NAME]` ADD `field_[FIELD_ID]` varchar(100) NULL; UPDATE `[TB_PREFIX]wpl_dbst` SET `table_name`=''[TABLE_NAME]'', `table_column`=''field_[FIELD_ID]'' WHERE id=[FIELD_ID];' WHERE `id`='14';
