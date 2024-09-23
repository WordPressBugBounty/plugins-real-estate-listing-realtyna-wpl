<?php
/**
* Make nullable some fields to fix the import issue
*/
$this->runQuery('ALTER TABLE `#__wpl_properties` MODIFY COLUMN `build_year` int NULL DEFAULT 0');
$this->runQuery('ALTER TABLE `#__wpl_properties` MODIFY COLUMN `field_7` int NULL DEFAULT 0');
$this->runQuery('ALTER TABLE `#__wpl_properties` MODIFY COLUMN `half_bathrooms` FLOAT NULL DEFAULT 0');
$this->runQuery('ALTER TABLE `#__wpl_properties` MODIFY COLUMN `field_55` FLOAT NULL DEFAULT 0');