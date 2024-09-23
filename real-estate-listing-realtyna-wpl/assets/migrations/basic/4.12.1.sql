ALTER TABLE `#__wpl_properties` CHANGE `last_finalize_date` `last_finalize_date` DATETIME NULL DEFAULT CURRENT_TIMESTAMP, CHANGE `last_sync_date` `last_sync_date` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP;

-- Color's Column for "Listing & Property" Types
ALTER TABLE `#__wpl_listing_types` ADD COLUMN `color` VARCHAR(20) NOT NULL DEFAULT '#dddddd';

ALTER TABLE `#__wpl_property_types` ADD COLUMN `color` VARCHAR(20) NOT NULL DEFAULT '#dddddd';