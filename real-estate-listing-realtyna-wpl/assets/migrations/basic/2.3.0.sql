UPDATE `#__wpl_dbst` SET `name`='Gender' WHERE `id`='906';

ALTER TABLE `#__wpl_kinds` ADD `plural` VARCHAR(100) NULL, ADD `dbcat` TINYINT(4) NOT NULL DEFAULT '1', ADD `addon_id` INT(10) NOT NULL DEFAULT '0';
UPDATE `#__wpl_kinds` SET `plural`='Properties' WHERE `id`='0';
UPDATE `#__wpl_kinds` SET `plural`='Users', `dbcat`='0', `addon_id`='9' WHERE `id`='2';

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
(126, 'google_api_key', null, 1, 1, 'text', 'Google API key', NULL, NULL, 55.00);

ALTER TABLE `#__wpl_users` ADD `about` TEXT NULL AFTER `last_name`;
INSERT INTO `#__wpl_dbst` (`id`, `kind`, `mandatory`, `name`, `type`, `options`, `enabled`, `pshow`, `plisting`, `searchmod`, `editable`, `deletable`, `index`, `css`, `style`, `specificable`, `listing_specific`, `property_type_specific`, `user_specific`, `table_name`, `table_column`, `category`, `rankable`, `rank_point`, `comments`, `pwizard`, `text_search`, `params`, `flex`) VALUES
(915, 2, 0, 'Personal Data', 'separator', 'null', 1, '1', 0, 1, 1, 1, 10.000, NULL, NULL, 1, NULL, NULL, NULL, 'wpl_users', NULL, 10, 1, 0, NULL, '1', 1, NULL, 1),
(916, 2, 0, 'Company Data', 'separator', 'null', 1, '1', 0, 1, 1, 1, 10.060, NULL, NULL, 1, NULL, NULL, NULL, 'wpl_users', NULL, 10, 1, 0, NULL, '1', 1, NULL, 1),
(917, 2, 0, 'Contact information', 'separator', 'null', 1, '1', 0, 1, 1, 1, 10.100, NULL, NULL, 1, NULL, NULL, NULL, 'wpl_users', NULL, 10, 1, 0, NULL, '1', 1, NULL, 1),
(918, 2, 0, 'About', 'textarea', '{"advanced_editor":"0","rows":"7","cols":"41"}', 1, '1', 1, 1, 1, 1, 10.035, NULL, NULL, 1, '', '', '', 'wpl_users', 'about', 10, 1, 0, NULL, '1', 1, NULL, 1);

UPDATE `#__wpl_dbst` SET `searchmod`='1' WHERE `id`='900';
UPDATE `#__wpl_dbst` SET `searchmod`='1' WHERE `id`='901';
UPDATE `#__wpl_dbst` SET `searchmod`='1' WHERE `id`='906';
UPDATE `#__wpl_dbst` SET `searchmod`='1' WHERE `id`='902';
UPDATE `#__wpl_dbst` SET `searchmod`='1' WHERE `id`='903';
UPDATE `#__wpl_dbst` SET `searchmod`='1' WHERE `id`='914';
UPDATE `#__wpl_dbst` SET `searchmod`='1' WHERE `id`='905';
UPDATE `#__wpl_dbst` SET `searchmod`='1' WHERE `id`='904';
UPDATE `#__wpl_dbst` SET `searchmod`='1' WHERE `id`='907';
UPDATE `#__wpl_dbst` SET `searchmod`='1' WHERE `id`='908';
UPDATE `#__wpl_dbst` SET `searchmod`='1' WHERE `id`='909';

INSERT INTO `#__wpl_extensions` (`id`, `type`, `title`, `parent`, `description`, `enabled`, `param1`, `param2`, `param3`, `param4`, `param5`, `params`, `editable`, `index`, `client`) VALUES
(26, 'sidebar', 'Profile Listing Top', 0, 'Appears in Profile listing page', 1, 'wpl-profile-listing-top', '', '', '', '', '', 0, 99.99, 2),
(27, 'sidebar', 'WPL Hidden', 0, 'Appears no where! Use it for widget short-codes.', 1, 'wpl-hidden', '', '', '', '', '', 0, 99.99, 2);

ALTER TABLE `#__wpl_kinds` CHANGE `addon_id` `addon_name` VARCHAR(100) NULL;
UPDATE `#__wpl_kinds` SET `addon_name`='' WHERE `id` ='0';
UPDATE `#__wpl_kinds` SET `addon_name`='membership' WHERE `id`='2';

UPDATE `#__wpl_extensions` SET `client`='2' WHERE `id`='116';

ALTER TABLE `#__wpl_dbst` ADD `accesses` TEXT NULL AFTER `user_specific`;
ALTER TABLE `#__wpl_dbst` ADD `accesses_message` VARCHAR(100) NULL AFTER `accesses`;

ALTER TABLE `#__wpl_dbst_types` CHANGE `kind` `kind` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '[0][1]';

UPDATE `#__wpl_dbst_types` SET `kind`='[0][1][2][4]' WHERE `id`='1';
UPDATE `#__wpl_dbst_types` SET `kind`='[0][1][2][4]' WHERE `id`='2';
UPDATE `#__wpl_dbst_types` SET `kind`='[0][1][2][4]' WHERE `id`='3';
UPDATE `#__wpl_dbst_types` SET `kind`='[0][1][4]' WHERE `id`='4';
UPDATE `#__wpl_dbst_types` SET `kind`='[0][1][2][4]' WHERE `id`='5';
UPDATE `#__wpl_dbst_types` SET `kind`='[0][1][2][4]' WHERE `id`='6';
UPDATE `#__wpl_dbst_types` SET `kind`='[0][1][4]' WHERE `id`='7';
UPDATE `#__wpl_dbst_types` SET `kind`='[0][1][4]' WHERE `id`='8';
UPDATE `#__wpl_dbst_types` SET `kind`='[0][1][4]' WHERE `id`='9';
UPDATE `#__wpl_dbst_types` SET `kind`='[0][1][4]' WHERE `id`='10';
UPDATE `#__wpl_dbst_types` SET `kind`='[0][1][2][4]' WHERE `id`='11';
UPDATE `#__wpl_dbst_types` SET `kind`='[0][1][2][4]' WHERE `id`='12';
UPDATE `#__wpl_dbst_types` SET `kind`='[0][1][2][4]' WHERE `id`='13';
UPDATE `#__wpl_dbst_types` SET `kind`='[0][1][2][4]' WHERE `id`='14';
UPDATE `#__wpl_dbst_types` SET `kind`='[0][1][2][4]' WHERE `id`='19';
UPDATE `#__wpl_dbst_types` SET `kind`='[0][1][2][4]' WHERE `id`='20';

UPDATE `#__wpl_extensions` SET `client`='2' WHERE `id`='109';

ALTER TABLE `#__wpl_kinds` ADD `index` FLOAT(6, 3) NOT NULL DEFAULT '99.00', ADD `params` TEXT NULL, ADD `enabled` TINYINT(4) NOT NULL DEFAULT '1';
ALTER TABLE `#__wpl_kinds` ADD `map` VARCHAR(10) NULL DEFAULT 'marker' AFTER `addon_name`;

UPDATE `#__wpl_dbst` SET `plisting`='0' WHERE `id`='51';
UPDATE `#__wpl_dbst` SET `plisting`='0' WHERE `id`='52';

INSERT INTO `#__wpl_dbst` (`id`, `kind`, `mandatory`, `name`, `type`, `options`, `enabled`, `pshow`, `plisting`, `searchmod`, `editable`, `deletable`, `index`, `css`, `style`, `specificable`, `listing_specific`, `property_type_specific`, `user_specific`, `accesses`, `accesses_message`, `table_name`, `table_column`, `category`, `rankable`, `rank_point`, `comments`, `pwizard`, `text_search`, `params`, `flex`) VALUES
(910, 2, 0, 'Location Text', 'text', '{"if_zero":"1","call_text":"Call"}', 2, '0', 0, 0, 1, 0, 10.055, '', '', 0, '', '', '', '', '', 'wpl_users', 'location_text', 10, 0, 0, '', '0', 0, '', 1);

UPDATE `#__wpl_dbst` SET `table_column`='', `text_search`='0' WHERE `id`='53';
