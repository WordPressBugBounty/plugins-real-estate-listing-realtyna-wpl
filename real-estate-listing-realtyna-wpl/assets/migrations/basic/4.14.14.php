<?php

$this->runQuery("
CREATE TABLE `#__wpl_location_tries` (
	`id` bigint NOT NULL AUTO_INCREMENT,
	`location` varchar(255) NULL,
	`latitude` decimal(10, 8) NULL,
	`longitude` decimal(11, 8) NULL,
	`created_at` datetime NULL,
	PRIMARY KEY (`id`),
	INDEX(`location`) USING BTREE
);
");
$this->runQuery("
INSERT INTO `#__wpl_cronjobs` (`id`, `cronjob_name`, `period`, `class_location`, `class_name`, `function_name`, `params`, `enabled`, `latest_run`) VALUES
(39, 'Purge location cache data', 24, 'libraries.locations', 'wpl_locations', 'purge_cached_locations', '', 1, '2020-01-01 00:00:00');
");

$this->runQuery("
DELETE FROM `#__wpl_extensions` WHERE id = 99 and param1 = 'wpl-google-font';
");