INSERT INTO `#__wpl_dbst_types` (`id`, `kind`, `type`, `enabled`, `index`, `queries_add`, `queries_delete`) VALUES
(20, '[0][1][2][4]', 'checkbox', 1, 20.00, 'ALTER TABLE `[TB_PREFIX][TABLE_NAME]` ADD `field_[FIELD_ID]` TINYINT( 4 ) NOT NULL DEFAULT ''0''; UPDATE `[TB_PREFIX]wpl_dbst` SET `table_name`=''[TABLE_NAME]'', `table_column`=''field_[FIELD_ID]'' WHERE id=[FIELD_ID];', 'ALTER TABLE `[TB_PREFIX][TABLE_NAME]`\r\nDROP `field_[FIELD_ID]`;');

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
(166, 'txtimg_color1', '000000', 0, 1, 'text', 'Text to Image color', NULL, NULL, 166.00);

INSERT INTO `#__wpl_extensions` (`id`, `type`, `title`, `parent`, `description`, `enabled`, `param1`, `param2`, `param3`, `param4`, `param5`, `params`, `editable`, `index`, `client`) VALUES
(129, 'javascript', 'WPL Common javascript', '0', '', '1', 'wpl_common_javascript', 'js/wpl.commons.min.js', '', '', '', '', '0', '100.11', '2');

INSERT INTO `#__wpl_notifications` (`id`, `description`, `template`, `subject`, `additional_memberships`, `additional_users`, `additional_emails`, `options`, `params`, `enabled`) VALUES
(6, 'Send to friend', 'send_to_friend', 'Send to friend', '', '', '', NULL, '', 1),
(7, 'Request a visit', 'request_a_visit', 'Request a visit', '', '', '', NULL, '', 1);

INSERT INTO `#__wpl_events` (`id`, `type`, `trigger`, `class_location`, `class_name`, `function_name`, `params`, `enabled`) VALUES
(40, 'notification', 'request_a_visit_send', 'libraries.event_handlers.notifications', 'wpl_events_notifications', 'request_a_visit', '', 1),
(41, 'notification', 'send_to_friend', 'libraries.event_handlers.notifications', 'wpl_events_notifications', 'send_to_friend', '', 1);

UPDATE `#__wpl_dbst` SET `type`='tag', `options`='{"ribbon":"1","widget":"1","color":"29a9df","text_color":"ffffff","default_value":"0"}' WHERE `id`='400';
UPDATE `#__wpl_dbst` SET `type`='tag', `options`='{"ribbon":"1","widget":"1","color":"d21a10","text_color":"ffffff","default_value":"0"}' WHERE `id`='401';
UPDATE `#__wpl_dbst` SET `type`='tag', `options`='{"ribbon":"1","widget":"1","color":"3cae2c","text_color":"ffffff","default_value":"0"}' WHERE `id`='402';
UPDATE `#__wpl_dbst` SET `type`='tag', `options`='{"ribbon":"1","widget":"1","color":"666666","text_color":"ffffff","default_value":"0"}' WHERE `id`='403';

UPDATE `#__wpl_dbst` SET `pshow`='0' WHERE `type`='separator';

ALTER TABLE `#__wpl_dbcat` ADD `pdf` TINYINT(4) NOT NULL DEFAULT '0' AFTER `pshow`;
UPDATE `#__wpl_dbcat` SET `pdf`=`pshow`;

ALTER TABLE `#__wpl_dbst` ADD `pdf` TINYINT(4) NOT NULL DEFAULT '0' AFTER `pshow`;
UPDATE `#__wpl_dbst` SET `pdf`=`pshow`;
