UPDATE `#__wpl_dbst` SET `plisting`='1' WHERE `id`='136';

ALTER TABLE `#__wpl_dbcat` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `#__wpl_units` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT;

UPDATE `#__wpl_dbst` SET `deletable`='0' WHERE `id`='567';
UPDATE `#__wpl_dbst` SET `deletable`='0' WHERE `id`='300';
UPDATE `#__wpl_dbst` SET `deletable`='0' WHERE `id`='301';

UPDATE `#__wpl_settings` SET `options`='{"show_empty":"1"}' WHERE `id`='25';
UPDATE `#__wpl_dbst` SET `plisting`='1' WHERE `id`='150';

UPDATE `#__wpl_settings` SET `setting_name`='io_public_key', `params`='{"readonly":"readonly"}' WHERE `id`='34';
UPDATE `#__wpl_settings` SET `setting_name`='io_private_key', `params`='{"readonly":"readonly"}' WHERE `id`='35';

INSERT INTO `#__wpl_extensions` (`id`, `type`, `title`, `parent`, `description`, `enabled`, `param1`, `param2`, `param3`, `param4`, `param5`, `params`, `editable`, `index`, `client`) VALUES
(131, 'service', 'WPL Service', 0, 'For running WPL service', 1, 'init', 'wpl->run', '9999', '', '', '', 0, 99.99, 2);

DELETE FROM `#__wpl_extensions` WHERE `id`='94';
