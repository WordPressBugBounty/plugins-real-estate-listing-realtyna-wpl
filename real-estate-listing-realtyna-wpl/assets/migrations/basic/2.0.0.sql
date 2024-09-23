UPDATE `#__wpl_dbst` SET `enabled`='2' WHERE `id`='313';
UPDATE `#__wpl_dbst` SET `enabled`='2', `deletable`='0' WHERE `id`='312';
UPDATE `#__wpl_dbst` SET `enabled`='2', `deletable`='0' WHERE `id`='308';

UPDATE `#__wpl_settings` SET `index`='3.00' WHERE `id`='50';
INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
(90, 'property_alias_pattern', '[property_type][glue][listing_type][glue][location][glue][rooms][glue][bedrooms][glue][bathrooms][glue][price]', 1, 4, 'pattern', 'Property Link Pattern', '{"tooltip":"You can remove the parameters or change the positions. Don''t add new parameters!"}', '', 4.00);

UPDATE `#__wpl_settings` SET `type`='pattern' WHERE `id`='55';
UPDATE `#__wpl_settings` SET `type`='pattern' WHERE `id`='56';

ALTER TABLE `#__wpl_dbst` CHANGE `index` `index` FLOAT(9, 3) NOT NULL DEFAULT '99.00';

ALTER TABLE `#__wpl_property_types` DROP `keyword`;
ALTER TABLE `#__wpl_menus` CHANGE `index` `index` FLOAT(6, 3) NOT NULL DEFAULT '1.00';

INSERT INTO `#__wpl_extensions` (`id`, `type`, `title`, `parent`, `description`, `enabled`, `param1`, `param2`, `param3`, `param4`, `param5`, `params`, `editable`, `index`, `client`)
VALUES (111, 'javascript', 'ImageLoaded', '0', '', '1', 'imageloaded', 'js/qtips/imagesloaded.pkg.min.js', '', '', '', '', '0', '110.01', '1');
