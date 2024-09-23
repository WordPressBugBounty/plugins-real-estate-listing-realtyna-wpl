INSERT INTO `#__wpl_extensions` (`id`, `type`, `title`, `parent`, `description`, `enabled`, `param1`, `param2`, `param3`, `param4`, `param5`, `params`, `editable`, `index`, `client`) VALUES
(137, 'sidebar', 'Property Listing Bottom', 0, 'Appears in the bottom of property listing page', 1, 'wpl-plisting-bottom', '', '', '', '', '', 0, 99.99, 2);

INSERT INTO `#__wpl_dbst` (`id`, `kind`, `mandatory`, `name`, `type`, `options`, `enabled`, `pshow`, `pdf`, `plisting`, `searchmod`, `editable`, `deletable`, `index`, `css`, `style`, `specificable`, `listing_specific`, `property_type_specific`, `user_specific`, `accesses`, `accesses_message`, `table_name`, `table_column`, `category`, `rankable`, `rank_point`, `comments`, `pwizard`, `text_search`, `params`, `flex`) VALUES
(57, 0, 0, 'Street Suffix', 'text', NULL, 1, '1', 0, 1, 0, 1, 1, 14.0000, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 'wpl_properties', 'street_suffix', 2, 1, 0, NULL, '1', 1, NULL, 1);

ALTER TABLE `#__wpl_properties` ADD `street_suffix` varchar(50) NULL;

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
(234, 'listing_media_confirm', '0', 1, 1, 'select', 'Media Confirm', '{"tooltip":"It''s for showing/hiding tick icon for images/attachments in Add/Edit Listing menu."}', '{"values":[{"key":0,"value":"Disabled" },{"key":1,"value":"Enabled"}]}', 97.50);

ALTER TABLE `#__wpl_users` ADD `show_address` TINYINT(4) NOT NULL DEFAULT '1' AFTER `location7_name`;
ALTER TABLE `#__wpl_property_types` ADD `show_marker` tinyint(4) NULL DEFAULT '1' AFTER `name`;
ALTER TABLE `#__wpl_dbst` ADD `field_specific` varchar(200) NULL;

UPDATE `#__wpl_settings` SET `setting_value`='County, Avenue, Ave, Boulevard, Blvd, Highway, Hwy, Lane, Ln, Square, Sq, Street, St, Road, Rd, Drive, Dr, Place, Pl, Plaza, Crescent, Cresc, Court, Cou, Path. Pa, Gate, Gt, Terrace, Ter' WHERE `id`='141';

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES ('241', 'load_gmap_js_libraries', '1', '0', '1', 'select', 'Load Google Maps JS libraries', '', '{"values":[{"key":0,"value":"Disabled"},{"key":1,"value":"Enabled"}]}', '241.00');
INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES ('242', 'multiple_marker_icon', 'multiple.png', '0', '1', 'text', 'Multiple Marker Icon', '', '', '242.00');
INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES ('251', 'base_currency', 'USD', '1', '1', 'currency', 'Base Currency', '{"tooltip":"This is used for currency conversion."}', '', '96.00');

DELETE FROM `#__wpl_sort_options` WHERE `id`='5';

ALTER TABLE `#__wpl_properties` ADD `meta_description_manual` TINYINT(4) UNSIGNED NOT NULL DEFAULT '0' AFTER `meta_description`;
ALTER TABLE `#__wpl_properties` ADD `meta_keywords_manual` TINYINT(4) UNSIGNED NOT NULL DEFAULT '0' AFTER `meta_keywords`;

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES ('254', 'hide_invalid_markers', '1', '1', '3', 'select', 'Hide invalid markers', '{"tooltip":"Hide the marker from maps when the geo point is not available or it set''s to 0,0."}', '{"values":[{"key":1,"value":"Yes"},{"key":0,"value":"No"}]}', '8.00');

ALTER TABLE `#__wpl_users` ADD COLUMN `maccess_lrestrict_pshow` int(4) NULL DEFAULT 0;
ALTER TABLE `#__wpl_users` ADD COLUMN `maccess_ptrestrict_pshow` int(4) NULL DEFAULT 0;
ALTER TABLE `#__wpl_users` ADD COLUMN `maccess_listings_pshow` varchar(255) NULL;
ALTER TABLE `#__wpl_users` ADD COLUMN `maccess_property_types_pshow` varchar(255) NULL;

ALTER TABLE `#__wpl_users` ADD COLUMN `maccess_lrestrict_plisting` int(4) NULL DEFAULT 0;
ALTER TABLE `#__wpl_users` ADD COLUMN `maccess_ptrestrict_plisting` int(4) NULL DEFAULT 0;
ALTER TABLE `#__wpl_users` ADD COLUMN `maccess_listings_plisting` varchar(255) NULL;
ALTER TABLE `#__wpl_users` ADD COLUMN `maccess_property_types_plisting` varchar(255) NULL;
