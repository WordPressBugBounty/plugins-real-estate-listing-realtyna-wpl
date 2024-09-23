UPDATE `#__wpl_activities` SET `association_type`='1';
UPDATE `#__wpl_extensions` SET `client`='2' WHERE `id`='98';
UPDATE `#__wpl_extensions` SET `client`='2' WHERE `id`='102';
UPDATE `#__wpl_extensions` SET `client`='2' WHERE `id`='89';
UPDATE `#__wpl_extensions` SET `client`='2' WHERE `id`='101';

INSERT INTO `#__wpl_units` (`id`, `name`, `type`, `enabled`, `tosi`, `index`, `extra`, `extra2`, `extra3`, `extra4`, `seperator`, `d_seperator`, `after_before`) VALUES
(7, 'Hectare', 2, 0, 10000, 7, '', '', '', '', '', '', 0);

ALTER TABLE `#__wpl_settings` CHANGE `setting_value` `setting_value` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;