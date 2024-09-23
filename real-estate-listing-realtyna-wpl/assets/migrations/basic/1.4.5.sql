INSERT INTO `#__wpl_dbst` (`id`, `kind`, `mandatory`, `name`, `type`, `options`, `enabled`, `pshow`, `plisting`, `searchmod`, `editable`, `deletable`, `index`, `css`, `style`, `specificable`, `listing_specific`, `property_type_specific`, `table_name`, `table_column`, `category`, `rankable`, `rank_point`, `comments`, `pwizard`, `text_search`, `params`) VALUES
(313, 0, 3, 'Property Title', 'text', 'null', 1, '0', 1, 0, 1, 0, 1.00, '', '', 1, '', '', 'wpl_properties', 'field_313', 1, 0, 0, '', '1', 0, '[]');

ALTER TABLE `#__wpl_properties` ADD `field_313` VARCHAR( 50 ) NULL AFTER `field_312`;
UPDATE `#__wpl_dbcat` SET `listing_specific`='' WHERE `id`='7';

INSERT INTO `#__wpl_dbst_types` (`id`, `kind`, `type`, `enabled`, `index`, `queries_add`, `queries_delete`) VALUES
(14, '[0][1]', 'url', 1, 1.00, 'ALTER TABLE `[TB_PREFIX][TABLE_NAME]` ADD `field_[FIELD_ID]` varchar(50) NULL; UPDATE [TB_PREFIX]wpl_dbst SET `table_name`=''[TABLE_NAME]'', `table_column`=''field_[FIELD_ID]'' WHERE id=[FIELD_ID];', 'ALTER TABLE `[TB_PREFIX][TABLE_NAME]`\r\nDROP `field_[FIELD_ID]`;');

INSERT INTO `#__wpl_extensions` (`id`, `type`, `title`, `parent`, `description`, `enabled`, `param1`, `param2`, `param3`, `param4`, `param5`, `params`, `editable`, `index`, `client`) VALUES
(24, 'sidebar', 'Property Listing Top', 0, 'Appears below of Google map in property listing page', 1, 'wpl-plisting-top', '', '', '', '', '', 0, 99.99, 2);
