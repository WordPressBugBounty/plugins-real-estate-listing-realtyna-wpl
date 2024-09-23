UPDATE `#__wpl_dbst` SET `deletable`='0' WHERE `table_column` IN ('bedrooms', 'bathrooms', 'living_area', 'rooms');

UPDATE `#__wpl_dbst` SET `deletable`='0' WHERE `table_column` IN ('field_42', 'street_no', 'post_code', 'street_suffix', 'field_54', 'field_55');

UPDATE `#__wpl_dbst` SET `deletable`='0' WHERE `type` IN ('meta_desc', 'meta_key');

UPDATE `#__wpl_addons` SET `version`='1.0.1' WHERE `id` = '45';

ALTER TABLE `#__wpl_dbst` ADD `savesearch` tinyint(4) NOT NULL DEFAULT '1';

-- Update IDX to 1.1.0
UPDATE `#__wpl_addons` SET `version`='1.1.0' WHERE `id` = '45';
