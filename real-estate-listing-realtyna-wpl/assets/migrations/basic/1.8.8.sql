INSERT INTO `#__wpl_dbst_types` (`id`, `kind`, `type`, `enabled`, `index`, `queries_add`, `queries_delete`) VALUES
(19, '[0][1][2]', 'boolean', 1, 19.00, 'ALTER TABLE `[TB_PREFIX][TABLE_NAME]` ADD `field_[FIELD_ID]` TINYINT( 4 ) NOT NULL DEFAULT ''[DEFAULT_VALUE]''; UPDATE `[TB_PREFIX]wpl_dbst` SET `table_name`=''[TABLE_NAME]'', `table_column`=''field_[FIELD_ID]'' WHERE id=[FIELD_ID];', 'ALTER TABLE `[TB_PREFIX][TABLE_NAME]`\r\nDROP `field_[FIELD_ID]`;');

ALTER TABLE `#__wpl_properties` ADD `show_address` TINYINT(4) NOT NULL DEFAULT '1' AFTER `location7_name`;

UPDATE `#__wpl_settings` SET `title`='Watermark Logo' WHERE `id`='11';
DELETE FROM `#__wpl_activities` WHERE `id`='1';

ALTER TABLE `#__wpl_dbst_types` ADD `options` TEXT NULL;

ALTER TABLE `#__wpl_users` ADD `index` FLOAT(5, 2) NOT NULL DEFAULT '99.00' AFTER `membership_type`;

UPDATE `#__wpl_dbst` SET `editable`='1', `specificable`='0' WHERE `id`='51';
UPDATE `#__wpl_dbst` SET `editable`='1', `specificable`='0' WHERE `id`='52';
